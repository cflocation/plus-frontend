function printGrid(){	
	var printOption 	= $('#pdfGridOption').val();
	var tz 				= $('#timezone').val();
	var station 		= $('#station option:selected').text(); 
	var stationid 		= $('#station').val(); 	
	var sdate			= printDate;
	var stime			= $('#sTime').val();
	var etime			= $('#eTime').val();
	var zoneid			= $('#zones').val();		

	if(printOption == 1){
		sswin.mixTrack("Grids - Download Week",{"networkId":stationid,"callsign":station,"startTime":stime,"startDate":sdate,"zoneId":zoneid});	
		window.location= 'https://plus.showseeker.com/goplus/grids/downloads/?zid='+zoneid+'&id='+stationid+'&st='+stime+'&et='+etime+'&sd='+sdate,'Download_Window','height=110,width=155,resizable=0,left=500,top=330';		}
	else{
		sswin.mixTrack("Grids - Download Full",{"networkId":station,"zoneid":zoneid,"timeZone":tz});
		window.location = 'http://ezgrids.com/download.php?tz='+sswin.timezone+'&net='+station+'&zoneid='+zoneid;
	}
};