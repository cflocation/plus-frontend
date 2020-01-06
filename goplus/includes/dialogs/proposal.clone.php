<div style="padding: 10px; width:95%" align="center">

	<table width="100%" class="copy-proposal-container">
		<tr>
			<td width="10%">Name:</td>
			<td><input class="rounded-corners forms" name="rename-proposal2" id="rename-proposal2" type="text" size="26"></td>
		</tr>
	</table>
	<br/>
	<table width="100%" class="copy-proposal-container">
		<tr>
			<td width="10%"><input id="flight2" name="flight2" type="checkbox" value="1" onchange="proposalChangeFlight();"/></td>		
			<td width="90%">
				<label for="flight2" class="hander">Change Flight</label>  
				<span> <i class="fa fa-question-circle hander copy-info"></i></span>
			</td>
		</tr>
		<tr class="copy-ctrls" id="change-flight-warning" style="display:none;">
			<td width="10%"></td>
			<td>
				<p style="padding: 0 .7em; color: #555;">
					<b>- "Change Flight"</b> option will copy all Rotator lines plus any Fixed programming that falls within the new date range.
				</p>
			</td>
		</tr>

		<tr class="new-flight-dates">
			<td width="10%"></td>
			<td>
				<br>
				<div>
					<b>Copy number of spots:</b>
					<span> <i class="fa fa-question-circle hander copy-spots-info"></i></span>
				</div>
				<table width="100%">
					<tr class="new-flight-dates">
						<td width="10%">
							<input id="clone-spots-1" name="clone-spots" type="radio" value="1" checked="checked"/>			
						</td>
						<td width="90%">
							<label for="clone-spots-1">From last active week to all weeks</label>
						</td>
					</tr>
					<tr class="new-flight-dates">
						<td>
							<input id="clone-spots-0" name="clone-spots" type="radio" value="0"/>
						</td>
						<td>
							<label for="clone-spots-0" class="hander">Insert zeros in new weeks</label>
						</td>
					</tr>	
					
					<tr class="copy-spots-info-desc" id="change-flight-details" style="display:none;">
						<td colspan="2">
							<p style="padding: 0 .7em; color: #555;">
								<b>- "From last active week:"</b> Spots are allocated across the new flight dates observing the number of spots from the last active week.
							</p>
							<p style="padding: 0 .7em; color: #555;">
								<b>- "Insert Zeroes:"</b> Maintains the pattern of spots allocation and will add zeros in the new extended week if any.
							</p>
						</td>
					</tr>					
					
									
				</table>				
				
			</td>
		</tr>
				
		
		<tr class="copy-ctrls1">
			<td width="10%"><input id="clone-zones" name="clone-zones" type="checkbox" value="1" onchange="proposalChangeZones();"/></td>
			<td width="90%"><label for="clone-zones" class="hander">Change Zones</label></td>
		</tr>

		<tr class="copy-ctrls">
			<td colspan="2" align="center">
				<select style="display:none;" class="selector-duplicate zone-selector" id="clone-zone-selector" multiple></select>
			</td>
		</tr>
		<tr class="copy-ctrls1">
			<td colspan="2">
				<center id="clone-proposal-buttons" >
					<button class="btn-blue" onclick="checkCopyParams()"><i class="fa fa-files-o"></i> Copy Proposal</button>
					<button class="btn-red" onclick='$("#dialog-window").dialog("destroy");'><i class="fa fa-times-circle"></i> Cancel</button>
				</center>
				<center id="clone-proposal-saving" style="display:none;">
					<h3><img src="i/ajaxsm.gif"><br>Copying... Please Wait</h3>
				</center>
			</td>
		</tr>
	</table>
</div>

<script>
	var pname 	= datagridProposalManager.getSelectedRows();
	var newname = unescape(pname[0].name) + ' | COPY';
	
	$("#rename-proposal2").val(newname);
	$("button").button();
	$('#clone-zone-selector').html('');
	
	zns = setAllZones();

	$('#zone-selector option[value!=DMA]').each(function(){
		if($(this).val() in zonesArray){
			if(zonesArray[$(this).val()].isdma === 'NO'){			
				$('#clone-zone-selector').append($("<option></option>").attr("value", $(this).val()).text($(this).text()));
			}
		}
	});
	
	
	$('.copy-info').on('click',function(){
		$('.copy-ctrls').toggle();
	});

	$('.copy-spots-info').on('click',function(){
		$('.copy-spots-info-desc').toggle();
		$('#clone-zone-selector').attr('size','5');
	});
	
	function proposalChangeZones(){
		var zones = $("#clone-zones").attr("checked");

		if(zones == 'checked'){
			$('#clone-zone-selector').css('display', 'inline');
		}else{
			$('#clone-zone-selector').css('display', 'none');
		}
	};
	
	function checkCopyParams(){
		if($("#clone-zones").is(':checked') && $('#clone-zone-selector :selected').length < 1){
			$('#clone-zone-selector').addClass('zeroZones');
			setTimeout(function(){$('#clone-zone-selector').removeClass('zeroZones');}, 3000);
			return;	
		}
		proposalCopyButtons();
		needSaving=true;
		proposalCopyChecked2();
	};

	function proposalChangeFlight(){

		if($("#flight2").is(':checked')){
			$('.new-flight-dates').show();
			$("#date-start").datepicker("show");
		}else{
			$('.new-flight-dates').hide();
			$("#date-start").datepicker("hide");
		}
	};

	function proposalCopyButtons(){
		$('#clone-proposal-saving').css('display', 'inline');
		$('#clone-proposal-buttons').css('display', 'none');
	};
</script>
