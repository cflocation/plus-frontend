<div id="form-container">
	<form method="post" name="scximporterForm" id="scximporterForm" style="overflow: hidden">
		<center>
			<br />
			<div class="fileinputs">
				<label for="scx-import"><span style="font-weight: 700;">SCX file :</span></label>
				<input type="file" class="file" name="SCXFile" id="scx-import" accept=".scx">
			</div>
			<div id="scxBody" style="display: none;">
				<br /><br />
				<div class="fileinputs" >
					<label for="proposalSCXName"><span style="font-weight: 700;">Proposal Name :</span></label>
					<input type="text" class="rounded-corners input-half scxImporterOff" name="proposalSCXName" id="proposalSCXName">
				</div>			
					<br /><br />
				<div class="fileinputs">				
					<label for="ss-titles">
						<i class="fa fa-check-square" id="ss-title-on"></i>
						<span style="font-weight: 700;">Use ShowSeeker Titles</span>
					</label>
					<input type="checkbox"  id="ss-titles" checked="checked">
				</div>
				
				<br /><br />
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
			</div>
		</center>
	</form>
</div>
	
<script>
	// Variable to store your files
	var files;
	var sstitles = 1;
	
	$('#scx-import,#scx-submit').button();
	$('#ss-titles').button();		
	
	$('input[type=file]').on('change', prepareUpload);

	
	$('#ss-titles').on('change',function(){
		sstitles = 0;
		
		if($(this).is(':checked')){
			sstitles = 1;			
		}
		
		$('#ss-title-on').toggleClass('fa-check-square').toggleClass('fa-square-o');
		
	});
	
	$('#scx-import').on('change',function(){
		var name = $('input[type=file]')[0].files[0].name;		
		$('#proposalSCXName').val(name.replace('.scx',''));
		$('#scxBody').show();
	})
	
	function prepareUpload(event){
	  files = event.target.files;
	}	
	
	
	$('form').on('submit', uploadFiles);

	// Catch the form submit and upload the files
	function uploadFiles(event){

		var scxcfile 		= String($('#scx-import').val()).trim();
		var proposalName 	= $('#proposalSCXName').val().substr(0,75);
		 
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
	
		var url		= apiUrl + 'proposal/scximporter';
		var data 	= new FormData();
		data.append('SCXFile', files[0]);
		data.append("userid",userid);
		data.append("searchTitles",sstitles);
		data.append("proposalName",proposalName);
					
		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			cache: false,
			dataType: 'json',
			processData: false, // Don't process the files
			contentType: false, // Set content type to false as jQuery will tell the server its a query string request
			headers:{"Api-Key":apiKey,"User":userid},
			success: function(data, textStatus, jqXHR){

					if(!('error' in data)){// Success so call function to process the form

						if(data.proposalId !== null){

							//RESET PROPOSAL FINDER FILTER
							clearProposalFilter();	
							var errorMsg 	= 0;
							proposalid 		= data.proposalId;
							
							getUserProposals();
							
							if(data.unMappedNet.length > 0){
								errorMsg++;
							}
							if(data.unMappedZone.length > 0){
								errorMsg++;
							}
							if(data.unknownSurvey.length > 0){
								errorMsg++;
							}
							if(data.unknownDemos.length > 0){
								errorMsg++;
							}
							if(data.multipleDMA){
								errorMsg++;
							}
							if(errorMsg > 0){
								openReportDialog(data,errorMsg);
							}
							loadProposalFromServer(data.proposalId,'build');
						}
						else{
							closeAllDialogs();
							loadDialogWindow('scxnozones', 'ShowSeeker Plus', 450, 180, 1);
						}
					}
					else{//error
						loadDialogWindow('scximporterror', 'ShowSeeker Plus', 450, 180, 1);
					}
				},
				error: function(jqXHR, textStatus, errorThrown){//error
					loadDialogWindow('scximporterror', 'ShowSeeker Plus', 450, 180, 1);
				}
		});
	}
	
	
	function openReportDialog(psllines,errorMsg){
		
		$("#dialog-image-ppt-selector").empty().dialog("destroy");
		var url 			= 'includes/dialogs.php?evt=scx-import-report&proposalid='+proposalid+'&downloadformat=';
		var d 			= {};
		var data 		= $("#dialog-disclaimer").data();
		var w				= 450;
		var h 			= 172;
		
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


</script>
