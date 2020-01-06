function getMondayGoPlus(d){
	var dDate 	= d.split(/[^0-9]/);
	d 				= new Date(dDate[0],parseInt(dDate[1]-1),dDate[2]);
	var day 		= d.getDay();
	var diff 	= d.getDate() - day + (day == 0 ? -6:1); // adjust when day is sunday
	return new Date(d.setDate(diff));
}


function getSundayGoPlus(d){
	var d 	= getMondayGoPlus(d);
	var diff = d.getDate() + 6;
	return new Date(d.setDate(diff));
}


function buildBroadcastMonthFromSmallDate(d){
	var dDate 	= d.split(/[^0-9]/);
	d 			= new Date(dDate[0],parseInt(dDate[1]-1),dDate[2]);	
	return d.toString("MMddyyyy");
}


function buildBroadcastMonthsGoPlus(start,end){
	var starts 		= getMondayGoPlus(start);
	var ends 		= getSundayGoPlus(end);
	var dates   	= {};
	var row, bmonth, column;

	while(starts < ends){
		bmonth 			= new Date(getBroadcastMonthGoPlus(starts.toString('yyyy-MM-dd')));
		column 			= bmonth.toString("MMddyyyy");
		row 			= {};
		row.date 		= bmonth;
		row.column 		= column;
		dates[bmonth] 	= row;
		starts 			= new Date(starts).add(7).days();
	}
	return dates;
}


//get broadcast month from date
function getBroadcastMonthGoPlus(d){
	var re 					= '';
	var dDate 				= d.split(/[^0-9]/);
	var thisDate 			= new Date(dDate[0],parseInt(dDate[1]-1),dDate[2]);
	var thisDate2 			= new Date(dDate[0],parseInt(dDate[1]-1),dDate[2]);
	var thisDatOfWeek 		= thisDate.getDay();
	var inputDateMonth 		= thisDate.toString("yyyy/MM/01");
	var isSun 				= thisDate2.moveToLastDayOfMonth().getDay();
	var lastSundayOfMonth;

	if(isSun === 0){
		lastSundayOfMonth = thisDate2.moveToLastDayOfMonth();
	}
	else{
		lastSundayOfMonth = thisDate2.moveToLastDayOfMonth().moveToDayOfWeek(0, -1);
	}
	
	if(thisDate > lastSundayOfMonth){
		re = thisDate.next().month().toString("yyyy/MM/01");
	}
	else{
		re = inputDateMonth;
	}
	
	return re;
}


function buildBroadcastWeeksGoPlus(start,end){
	var sDate 	= start.split(/[^0-9]/);
	var eDate 	= end.split(/[^0-9]/);
	var starts	= new Date(sDate[0],parseInt(sDate[1]-1),sDate[2]);
	var ends		= new Date(eDate[0],parseInt(eDate[1]-1),eDate[2]);
	var daynum 	= starts.getDay();
	var weeks	= [];
	var row;	
	if(daynum !== 1){
		starts = starts.last().monday();
	}
	
	while(starts <= ends){
		var date 		= starts.toString("MM/dd/yy");
		var dateFull 	= starts.toString("yyyy/MM/dd");
		var dateISO 	= starts.toString("yyyy-MM-dd");
		var column 		= starts.toString("MMddyyyy");
		row				= {};
		row.date 		= date;
		row.column 		= column;
		row.dateFull 	= dateFull;
		row.dateISO 	= dateISO;
		weeks.push(row);
		starts 			= new Date(starts).add(7).days();
	}
	
	return weeks;
}


