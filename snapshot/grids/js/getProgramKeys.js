	// Add spots to the selected Proposal
	var xhrSchedules = false;
	function grayOutSchedules(proposalId, clearGrids){
		if(clearGrids == 1){
			clearTiles();
		}		
		if(window.XMLHttpRequest){
			xhrSchedules = new XMLHttpRequest();
		}
		else{
			if(window.ActiveXObject){
				try{
					xhrSchedules = new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch(e){}
			}
		}
		
		if(xhrSchedules){
			xhrSchedules.onreadystatechange = sendProgramKeys;
			if(navigator.appName == "Microsoft Internet Explorer"){
				xhrSchedules.open("POST","cfc/programKeys.cfc?method=getProgramKeys&proposalId="+proposalId,true)
			}
			else{
				xhrSchedules.open("GET","cfc/programKeys.cfc?method=getProgramKeys&proposalId="+proposalId,true)			
			}
			xhrSchedules.send(null);
		}
		else{
			alert("There was an error on the HTTPResuest")
		}
		
	
	}
	
	
	function sendProgramKeys(){
		if(xhrSchedules.readyState == 4){
			if(xhrSchedules.status == 200){
				if(xhrSchedules.responseXML){
					var programKeysList = xhrSchedules.responseXML.getElementsByTagName("pkeys");
					for(var i=0; i< programKeysList.length; i++){														
						try{
							document.getElementById(programKeysList[i].getElementsByTagName("value")[0].firstChild.nodeValue).style.background = "#CCCCCC";
							document.getElementById('chk'+programKeysList[i].getElementsByTagName("value")[0].firstChild.nodeValue).checked = true;
							addTileId(programKeysList[i].getElementsByTagName("value")[0].firstChild.nodeValue);
							addScheduledProgram(programKeysList[i].getElementsByTagName("value")[0].firstChild.nodeValue);							
						}
						catch(e){
							//alert("Error returning line ids")
						}
					}
					if(tdIds > 0){
						tdIds = tdIds+","+scheduledPrograms;
					}
					checkAllState();
					highlightTab();
					
				}
				
			}
			else{
				alert("The was a problem  while getting the Scheduled Programs" + xhrSchedules.status);
			}
		}
		return false;
	}
	
