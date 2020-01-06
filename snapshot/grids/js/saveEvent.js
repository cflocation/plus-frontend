	// Add spots to the selected Proposal
	var xhrEvent = false;
	
	function saveEvent(eventId){
		if(window.XMLHttpRequest){
			xhrEvent = new XMLHttpRequest();
		}
		else{
			if(window.ActiveXObject){
				try{
					xhrEvent = new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch(e){}
			}
		}
		
		if(xhrEvent){
			
			if(navigator.appName == "Microsoft Internet Explorer"){
				xhrEvent.open("POST","cfc/saveEvent.cfc?method=saveEvent&event="+eventId,true)
			}
			else{
				xhrEvent.open("GET","cfc/saveEvent.cfc?method=saveEvent&event="+eventId,true)
			}			
			
			xhrEvent.send(null);
		}
		else{
			alert("There was an error on the HTTPResuest")
		}
	
	}


	function ezEvent(eventId, zone, station, tz){
		if(window.XMLHttpRequest){
			xhrEvent = new XMLHttpRequest();
		}
		else{
			if(window.ActiveXObject){
				try{
					xhrEvent = new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch(e){}
			}
		}
		
		if(xhrEvent){
			
			if(navigator.appName == "Microsoft Internet Explorer"){
				xhrEvent.open("POST","cfc/saveEvent.cfc?method=saveEzDownload&event="+eventId+"&zone="+zone+"&station="+station+"&timezone="+tz,true)
			}
			else{
				xhrEvent.open("GET","cfc/saveEvent.cfc?method=saveEzDownload&event="+eventId+"&zone="+zone+"&station="+station+"&timezone="+tz,true)
			}			
			
			xhrEvent.send(null);
		}
		else{
			alert("There was an error on the HTTPResuest")
		}
	
	}