
function updateBackground(Id,numberOfTabs){};


function setSelectedWeek(referenceWeek){
	selectedWeek = referenceWeek;
};
		
			
function closeEz(){
	 window.close();	
};

				
function dateCompare(sDate, eDate) {
    var str1 	= sDate;
    var str2 	= eDate;
    var mon1  	= parseInt(str1.substring(0,2),10);
    var dt1 	= parseInt(str1.substring(3,5),10);
    var yr1  	= parseInt(str1.substring(6,10),10); 
    var mon2  	= parseInt(str2.substring(0,2),10);
    var dt2 	= parseInt(str2.substring(3,5),10);
    var yr2  	= parseInt(str2.substring(6,10),10);
    var date1 	= new Date(yr1, mon1, dt1); 
    var date2 	= new Date(yr2, mon2, dt2); 

    if(date2 < date1){
        return false; 
    } 
    else{ 
        return true; 
    }
};		
			
			
			
function validateGRange(sTime, eTime){
	if(eTime.substring(0,2) - sTime.substring(0,2) <= 8 && eTime.substring(0,2) - sTime.substring(0,2) >=0){
		var minutes = (eTime.substring(3,5) - sTime.substring(3,5))/60;
		var hours = (eTime.substring(0,2) - sTime.substring(0,2)) + minutes;
		if(8.0 - hours >= 0){
			return true;
		} 
		else{
			return false;
		}
	}
	else{
		return false;
	}
	
};		

	
// SENDING SHOWSEEKER + REQUEST TO UPDATE THE SELECTED PROPOSAL
function selectedProposal(){
	var proposalid = 0;
	if($('#proposalList').val() !== 0){
		proposalid = $('#proposalList').val();
	}
	return proposalid;
};
			
			
			
