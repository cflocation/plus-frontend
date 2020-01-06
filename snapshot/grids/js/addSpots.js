// Add spots to the selected Proposal
	
function addSpots(proposalId){

	if(parent.document.getElementById('proposalList').value != 0){
		
			if(document.displayNetworkGrid.skedules.value != ''){
			
				var propoalId = parent.document.getElementById('proposalList').value;
				var zoneId    = parent.document.getElementById('zones').value;
				var timeZone  = parent.document.getElementById('timezone').value;
				
				var newLines  = document.getElementById('skedules').value;
				var itmsSelected = document.getElementById('selectedItems').value;
				
				var spots =  newLines.substr(1,newLines.length);
				var selectedItems = itmsSelected.substr(1,itmsSelected.length);

				document.displayNetworkGrid.selectedItems.value='';					

				var thisIframe = parent.document.getElementById('gridArea2');
				thisIframe.contentWindow.document.getElementById('spotLines').value = spots;
				thisIframe.contentWindow.document.getElementById('pslId').value = propoalId;
				thisIframe.contentWindow.document.getElementById('zn').value = zoneId;
				thisIframe.contentWindow.document.getElementById('tzn').value = timeZone;					
				thisIframe.contentWindow.document.getElementById('addingSpots').submit();
					
				document.getElementById('skedules').value = '';
				skedulesList = '';	
				newTilestdIds    = tdIds.split(",");
				
				for(var j=0; j<newTilestdIds.length; j++){
					if(scheduledPrograms.indexOf(newTilestdIds[j]) == -1 && newTilestdIds[j] != '' ){
						addScheduledProgram(newTilestdIds[j]);
					}
				}
				
			 	grayout();	
					

			}
	}

	else{

		alert("Please Select or Create a Proposal in order to complete this operation.");

	}
}