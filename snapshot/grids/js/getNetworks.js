		// Add spots to the selected Proposal
	var xhrqt = false;
	var refSationNum = 001;
	function getNetworks(zoneId, stationNum){
		refSationNum = stationNum;
		if(window.XMLHttpRequest){
			xhrqt = new XMLHttpRequest();
		}
		else{
			if(window.ActiveXObject){
				try{
					xhrqt = new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch(e){
				}
			}
		}
		
	
		if(xhrqt){
			xhrqt.onreadystatechange = displayList;
			xhrqt.open("GET","cfc/networks.cfc?method=getNetworks&zoneId="+zoneId,true)
			xhrqt.send(null);
		}
		else{
			alert("There was an error on the HTTPResuest")
		}
	
	}	
	
	
	function displayList(){
		if(xhrqt.readyState == 4){
			if(xhrqt.status == 200){
				if(xhrqt.responseXML){
					var networkList = xhrqt.responseXML.getElementsByTagName("network");
					document.getElementById("networkSelector").options.length = 0;
					for(var i=0; i<networkList.length; i++){
						addOptions(networkList[i].getElementsByTagName("name")[0].firstChild.nodeValue, networkList[i].getElementsByTagName("value")[0].firstChild.nodeValue);
					}
					
					if(refSationNum == 0){
					 document.getElementById("networkSelector").selectedIndex = -1;
					}
					else{
						for(var j=0; j<networkList.length; j++){
							if(networkList[j].getElementsByTagName("value")[0].firstChild.nodeValue == refSationNum){
								 document.getElementById("networkSelector").selectedIndex = j;
								 break;
							}
						}
					}
					
				}
			}
			else{
				var networkList = "The was a problem  while getting the Network list" + xhrqt.status;
			}
		}
		return false;
	}

	
	
	
    function addOptions(Text,Value){
        var opt = document.createElement("option");
        document.getElementById("networkSelector").options.add(opt);
        opt.text = Text;
        opt.value = Value;

    }
	
 
	
	function changingZone(zoneId, optionIndex){
		var answer = confirm('\t    You have changed zones.\n\n  \tDid you really want to do that?\n\nPlease be sure you are finished with adding selections\n    to your current schedule before changing zones')
		if (answer){
			getNetworks(zoneId,0); 
			selectedZone = optionIndex;
		}
		else{
			document.getElementById('zones').selectedIndex = selectedZone; 
			return ;
		}
	}