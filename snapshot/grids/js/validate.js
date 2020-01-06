function validateParameters(){
	if(document.getElementById("idTimeRange").selectedIndex != 4 && (document.getElementById("startTime").value == 0 && document.getElementById("endTime").value == 0)){
		submitGridParameters(document.getElementById("idTimeRange").value);
	}
	else{
	
		if(document.getElementById("startTime").value != 0 && document.getElementById("endTime").value != 0){
			if(validateDifference(document.getElementById("startTime").value, document.getElementById("endTime").value)){
				//if(validateRange(document.getElementById("startTime").value, document.getElementById("endTime").value)){
					document.getElementById('sTime').value = 0;
					document.getElementById('eTime').value = 0;			
					document.getElementById('download').value = 0;	
					document.newGridValues.target = 'gridArea';
					document.newGridValues.submit();
				//}
				//else{
				//	alert("The Time range should be equal or less than eight hours")
				//}
			}
			else{
				alert("The End Time should be greater than the Start Time")
			}
		}
		else{
			alert("One of the values from the Time Range is missing");
		}
	
	}
}


function submitGridParameters(timeRange){
	var range = timeRange.split(",");
	document.getElementById('sTime').value = range[0];
	document.getElementById('eTime').value = range[1];			
	document.getElementById('download').value = 0;					
	document.newGridValues.target = 'gridArea';
	document.newGridValues.submit();
}


function validateDifference(sTime, eTime){
	if(eTime.substring(0,2) > sTime.substring(0,2)){
		return true;
	}
	else{
		return false;
	}
}


function validateRange(sTime, eTime){
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
	
}



