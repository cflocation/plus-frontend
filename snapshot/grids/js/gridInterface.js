		function updateGridValues(timeRange){
			var range = timeRange.split(",");
			document.getElementById('sTime').value = range[0];
			document.getElementById('eTime').value = range[1];			
			newGridValues.target = 'gridArea';
			newGridValues.submit();
		}
		
		
		function goBackToShowSeeker(){
			window.opener.location.href = window.opener.location.href;
			window.opener.focus();
			 if (window.opener.progressWindow){
			 	window.opener.progressWindow.close();
 			 }
			 window.close();				
		}	
		
		// make sure the grid is all ready to be updated or closed
		function checkForgottenActions(action){ //added 01/20/09
			var newLines  =  parent.gridArea.document.getElementById('skedules').value;
			var spots =  newLines.substr(1,newLines.length);	
			var delLines = parent.frames["gridArea"].schedulesToDelete;
			if(spots.length == 0){
				if(action == 0){
					goBackToShowSeeker();				
				}
				
				if(action == 1){
					validateParameters();
				}
			}
			else{
				alert("Please be sure to Click the BLUE Add Shows to Proposal Button \nbefore  Updating the Grid or Going to ShowSeeker");
			}
		}			