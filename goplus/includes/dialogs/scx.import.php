<div id="form-container">
	<form method="post" name="scximporterForm" id="scximporterForm" style="overflow: hidden">
	
			<br />
			<div class="fileinputs">
				<label for="scx-import"  class="scxLabel">SCX file:</label>
				<input type="file" class="file" name="SCXFile" id="scx-import" accept=".scx">
			</div>

			<div id="scxBody" style="display: none;">

				<br/>
				
				<div class="fileinputs">
					<label for="proposalSCXName"  class="scxLabel">Proposal Name:</label>
					<input type="text" class="rounded-corners input-half scxImporterOff scxProposalName" name="proposalSCXName" id="proposalSCXName">
				</div>

				<br/>

				<div class="fileinputs" >
					<label for="scxTitles"  class="scxLabel">Use ShowSeeker Titles:</label>
					<span id="scxTitles">
						<label class="label" for="ss-titlesOn">Yes</label>
						<input type="radio"  id="ss-titlesOn" 	name="addSSTitles" checked="checked" value="1">
						<label class="label" for="ss-titlesOff">No</label>
						<input type="radio"  id="ss-titlesOff"  name="addSSTitles" value="0">
					</span>
				</div>

				<br/>

				<center>
					<p>
						<div id="submit-container">
							<input class="btn-green" type="submit" name="scx-submit" value="Start Import" id="scx-submit">
						</div>
						<div style="display: none;" id="scx-wait-msg">
							<center>
								<img src="i/ajaxsm.gif">
								<br />
								<span>Processing File ...</span>
							</center>
						</div>
					</p>
				</center>
			</div>
	</form>
</div>
	
<script>
	// Variable to store your files
	var files;
	var sstitles = 1;
	
	$('#scx-import,#scx-submit').button();
	$('#ss-titles').button();		
	$('#renameOptions,#scxTitles').buttonset()
	
	$('input[type=file]').on('change', prepareUpload);

	$('input[name="renameSCX"]').on('change', function(){
		if(parseInt($(this).val()) === 1){
			$('#proposalSCXName').parents().eq(0).show();
		}
		else{
			$('#proposalSCXName').val('');
			$('#proposalSCXName').parents().eq(0).hide();
		}
	});

	$('#scx-import').on('change',function(){
		var name = $('input[type=file]')[0].files[0].name;		
		$('#proposalSCXName').val(name.replace('.scx',''));
		$('#scxBody').show();
	})

	
	
	$('input[name="addSSTitles"]').on('change',function(){
		sstitles = 0;
		
		if($(this).val() === 1){
			sstitles = 1;			
		}
		
		$('#ss-title-on').toggleClass('fa-check-square').toggleClass('fa-square-o');
		
	});
	
	function prepareUpload(event){
	  files = event.target.files;
	}	
	

	
	$('form').on('submit', uploadFiles);

	// Catch the form submit and upload the files
	function uploadFiles(event){

		var scxcfile 		= String($('#scx-import').val()).trim();
		var proposalName 	= $('#proposalSCXName').val();
		
		if(scxcfile === ''){
			event.stopPropagation(); // Stop stuff happening
			event.preventDefault(); // Totally stop stuff happening
			return;
		}
		
		if(scxcfile.length > 0){
			$('#scx-submit').prop('disabled', true);
		}
		
		clearProposal();		
		event.stopPropagation(); // Stop stuff happening
		event.preventDefault(); // Totally stop stuff happening

	
		// START A LOADING SPINNER HERE
		$('#scx-wait-msg,#submit-container').toggle();
	
		var url			= apiUrl + 'proposal/scximporter';
		var scxData 	= new FormData();
        var scxObj		= {};		

		scxData.append('SCXFile', files[0]);
		scxData.append("userid",userid);
		scxData.append("searchTitles",sstitles);
		scxData.append("proposalName",proposalName);

        if(sstitles === 1){
            scxObj.searchTitles = 'ShowSeeker Titles';
        }
        else{
            scxObj.searchTitles = 'Original Titles';            
        }

		
        scxObj.proposalName = proposalName;
        scxObj.fileName		= files[0].name;
		scxObj.fileSize		= files[0].size;
		scxObj.fileUpdatedAt= files[0].lastModifiedDate._toString();
					
		$.ajax({
			url: url,
			type: 'POST',
			data: scxData,
			cache: false,
			dataType: 'json',
			processData: false, // Don't process the files
			contentType: false, // Set content type to false as jQuery will tell the server its a query string request
			headers:{"Api-Key":apiKey,"User":userid},
			success: function(data, textStatus, jqXHR){

				if(!('error' in data)){// Success so call function to process the form
					//RESET PROPOSAL FINDER FILTER
					clearProposalFilter();	
					var errorMsg 	= 0;
					proposalid 		= data.proposalId;
					
					if(data.unMappedNet.length > 0){
						errorMsg++;
					}
					if(data.unMappedZone.length > 0){
						errorMsg++;
					}
					if(data.unknownDemos.length > 0){
						errorMsg++;
					}
					if(data.multipleDMA){
						errorMsg++;
					}
					if(errorMsg > 0){
						closeAllDialogs();
						openReportDialog(data,errorMsg);
                        var obj = Object.assign({}, data, scxObj);
                        logErrorMixPanel(obj);
					}
					if(data.proposalId !== null){
						closeAllDialogs(data);
						datagridProposalManager.addNewProposalData(data,proposalName);
						updateProposalsInDownloads(data.proposalId,proposalName);
						scxObj.proposalId   = data.proposalId;
						logMixPanel(scxObj);
					}
				}
				else{//error
					loadDialogWindow('scximporterror', 'ShowSeeker Plus', 450, 180, 1);
    				closeAllDialogs(data);
                    var obj = Object.assign({}, data, scxObj);
                    logErrorMixPanel(obj);
				}
			},
			error: function(jqXHR, textStatus, errorThrown){//error
				loadDialogWindow('scximporterror', 'ShowSeeker Plus', 450, 180, 1);
				closeAllDialogs(data);
				var errorData 			= {};
				errorData.textStatus 	= textStatus;
				errorData.errorThrown 	= errorThrown;
                logErrorMixPanel(errorData);
			}
		});
	}
	
	
	function openReportDialog(psllines,errorMsg){
		
		$("#dialog-image-ppt-selector").empty().dialog("destroy");
		var url 			= 'includes/dialogs.php?evt=scx-import-report&proposalid='+proposalid+'&downloadformat=';
		var d 			= {};
		var data 		= $("#dialog-disclaimer").data();
		var w			= 450;
		var h 			= 'auto';
		
		data.psllines 	= psllines;
		d.modal			= false;
		d.width 			= w;
		d.height			= h;
		d.minHeight		= 172;
		d.minWidth		= 450;
		d.maxWidth		= 700;
		d.maxHeight		= 400;
		d.resizable		= false;
		d.draggable		= true;
		d.title			= "ShowSeeker Plus Import Report";
		d.dialogClass 	= "pepper";
		d.open 			= function(event,ui){$('.ui-dialog :button').blur();};
		$("#dialog-disclaimer").dialog(d).load(url, function(){});
	};

	function logMixPanel(data){
    	try{
    		mixTrack('Proposal - SCX Import',data);
        }catch(e){}
	}

	function logErrorMixPanel(data){
    	try{
    		mixTrack('Proposal - SCX Import Error',data);
        }catch(e){}
	}
</script>
