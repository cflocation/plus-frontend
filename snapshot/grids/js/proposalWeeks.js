var xhrWks = false;
	
function highlightTab(){
	
	var therSked = 0;
	
	for(var j=0; j<=13; j++){

		if($('#outerContainer'+j) != null && j == selectedTab){
			
			$('#wkNavigation'+j).css('background-image','url(images/wk2.gif)');
		}
		else{
			if($('#outerContainer'+j) != null && j != selectedTab){
				
				var x = $('#outerContainer'+j+' :checked');
				
				
				if(x.length > 0){
					$('#wkNavigation'+j).css('background-image','url(images/wk3.gif)');
					therSked = 1;
				}
					
				if(j != selectedTab && therSked == 0){
					$('#wkNavigation'+j).css('background-image','url(images/wk.gif)');
				}
				therSked = 0;
			}
		}
	}
}
	
	
	function highlightTabs(){
		if(xhrWks.readyState == 4){
			if(xhrWks.status == 200){
				if(xhrWks.responseXML){
					var weeks = xhrWks.responseXML.getElementsByTagName("sweek");
					try{
						var wkIds = weeks[0].getElementsByTagName("value")[0].firstChild.nodeValue;
						itemsTmp = wkIds;
						changeTabColor('',0);
					}
					catch(e){}
				}
				
			}
			else{
				var networkList = "There was a problem  while getting the broadcast weeks" + xhrWks.status;
			}
		}
		return false;
	}
	