function printGrid(){	
	var printOption 	= $('#pdfGridOption').val();
	var tz 				= sswin.solrSearchParamaters().timezone;
	var station 		= $('#station option:selected').text(); 
	var stationid 		= $('#station').val(); 	
	var sdate			= printDate;
	var stime			= $('#sTime').val();
	var etime			= $('#eTime').val();
	var zoneid			= $('#zones').val();	
	
	window.location= 'downloads/?zid='+zoneid+'&id='+stationid+'&st='+stime+'&et='+etime+'&sd='+sdate+'&tz='+tz+'&callsign='+station,'Download_Window','height=110,width=155,resizable=0,left=500,top=330';
	
}