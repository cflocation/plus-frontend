//get broadcast month from date
function getBroadcastMon(d){
	var re = '';	
	var tmpArr  		= d.split(/[^0-9]/);
	var thisDate 		= new Date(parseInt(tmpArr[2]), parseInt(tmpArr[0])-1, parseInt(tmpArr[1]));
	var isSun 			= new Date(parseInt(tmpArr[2]), parseInt(tmpArr[0])-1, parseInt(tmpArr[1]));

	
	var lastSundayOfMonth;
	
	var inputDateMonth 	= thisDate.toString("yyyy/MM/01");
	var isSunday 			= isSun.moveToLastDayOfMonth().getDay();
	
	if(isSunday === 0){
		lastSundayOfMonth = isSun.moveToLastDayOfMonth();
	}else{
		lastSundayOfMonth = isSun.moveToLastDayOfMonth().moveToDayOfWeek(0, -1);
	}
	
	if(thisDate > lastSundayOfMonth){
		re = thisDate.next().month().toString("yyyy/MM/01");
	}
	else{
		re = inputDateMonth;
	}

	return re;
}


function getBroadcastWeek(start){
	
	var sDArray = start.split(/[^0-9]/);
	var starts 	= new Date(parseInt(sDArray[0]),parseInt(sDArray[1]-1),parseInt(sDArray[2]));	

	if(starts.getDay() != 1){
		starts = starts.last().monday();
	}	

	return "w"+starts.toString("MMddyyyy");
}


function formatStdMonth(d){
	var sDArray = d.split(/[^0-9]/);	
	var starts 	= new Date(parseInt(sDArray[0]),parseInt(sDArray[1]-1),parseInt(sDArray[2]));
	return starts.toString("MM01yyyy");
}
