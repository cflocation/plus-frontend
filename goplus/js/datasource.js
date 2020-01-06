function datasourceBuildGrid(data,inserttype){
	
	searchSelectedLines ={};
	
	if(data.length == 0){
		$("#dialog-window").dialog("destroy");
		return;
	}
	
	$("#search-result-counter-total").html(data.length);
		
	$.each(data,function(i,val){

		var startdatetime 			= "tz_start_"+timezone;
		var enddatetime 			= "tz_end_"+timezone;
	    startdatetime 				= data[i][startdatetime];
		var startdate 				= Date.parse(startdatetime).toString("MM/dd/yyyy");
		var starttime 				= Date.parse(startdatetime).toString("hh:mm tt");
		var starttime24 			= Date.parse(startdatetime).toString("HH:mm:ss");	    		

		enddatetime 				= data[i][enddatetime];
		var enddate 				= Date.parse(enddatetime).toString("MM/dd/yyyy");
		var endtime 				= Date.parse(enddatetime).toString("hh:mm tt");
		var day 					= "day_"+timezone;
		day 						= data[i][day];

		var a 						= data[i].stationnum+data[i].title+startdatetime+enddatetime;
		var b 						= a.replace(/[^a-z0-9]/gi,'');
	
		var formatStartDateTime 	= Date.parse(startdatetime).toString("yyyy/MM/dd HH:mm:ss");
		var formatEndDateTime 		= Date.parse(enddatetime).toString("yyyy/MM/dd HH:mm:ss");
		var formatStartDateTimeClean= Date.parse(startdatetime).toString("yyyyMMddHHmm");
		formatStartDateTimeClean 	= formatStartDateTimeClean.replace(/[^a-zA-Z0-9 ]/g, '');
		var formatEndDateTimeClean 	= Date.parse(enddatetime).toString("yyyyMMddHHmm");
		var search 					= Object.searchcriteria(data[i]);
	
		if(data[i].projected == '1'){
			search = 'Projected';
		}
	
		if(inserttype == 'Package'){
			search = 'Package';
		}	

		var row = {};
	
		row.avail 			= b;
		row.callsign 		= data[i].callsign;
		row.day 			= day;
		row.desc 			= data[i].descembed;
		row.desc60 			= data[i].desc60;
		row.enddate 		= enddate;
		row.enddatetime 	= formatEndDateTime;
		row.endtime 		= endtime;
		row.epititle 		= String(data[i].epititle);
		row.genre 			= data[i].genre1;
		row.genre2 			= data[i].genre2;
		row.id 				= data[i].id + "-" + zoneid;
		row.isnew 			= data[i].isnew;
		row.linetype 		= 'Fixed';
		row.lineactive 		= 1;
		row.live 			= data[i].live;
		row.locked 			= false;
		row.packageId	 	= data[i].packageid;
		row.orgairdate 		= data[i].orgairdate;
		row.premiere		= data[i].premierefinale;
		row.programid 		= data[i].tmsid;
		row.projected 		= data[i].projected;
		row.rate 			= 0;
		row.ratecardid 		= 0;
		row.ratevalue 		= 0;
		row.search 			= search;
		row.showid 			= data[i].showid;
		row.showtype 		= data[i].showtype;
		row.split 			= 0;
		row.spots 			= 1;
		row.spotsweek 		= 1;
		row.ssid 			= data[i].id;
		row.stars 			= data[i].stars;
		row.startdate 		= startdate;
		row.startdatetime 	= formatStartDateTime;
		row.starttime 		= starttime;
		row.stationnum 		= data[i].stationnum;
		row.stationname 	= data[i].stationname;
		row.timestamp 		= new Date();
		row.title 			= String(data[i].title);
		row.total 			= 0;
		row.tvrating 		= data[i].tvrating;
		row.weeks 			= 1;
		row.year 			= data[i].year;
		row.zone 			= zone;
		row.zoneid 			= zoneid;
		
		// formaters //
		row.availsDay 		= data[i].callsign + ' - ' + formatterDayOfWeek(day) + ' - ' + getAvailTimeByHour(starttime,12);
		row.availsShow 		= data[i].title + ' - ' + data[i].callsign + ' - ' + starttime + ' - ' + endtime;
		row.callsignFormat 	= data[i].callsign + "|" + data[i].stationname;
		row.dayFormat 		= formatterDayOfWeek(day);
		row.showLine 		= data[i].callsign + ' - ' + data[i].title + ' - ' + starttime + ' - ' + endtime;
		row.sortingStartDate= zone + formatStartDateTimeClean + data[i].callsign + data[i].title;
		row.sortingMarathons= data[i].callsign + formatStartDateTimeClean + data[i].title;
		

		row.statusFormat 	= 'z9';			
		
		if((data[i].premierefinale).length > 2){
			row.statusFormat 	= data[i].premierefinale;
		}
		else if((data[i].live).length >2){
			row.statusFormat 	= data[i].live;
		}
		else if((data[i].isnew).length > 2){
			row.statusFormat 	= data[i].isnew;			
		}

			
		row.titleFormat 	= data[i].title + "|" + data[i].epititle;
	
		dataSourceResult.push(row);
		dataSourceResultCounter++;
		$("#search-result-counter-current").html(dataSourceResultCounter+1);
	});
	

		stdcalendar	= 1;
		datagridSearchResults.populateDataGrid(dataSourceResult);


		if(myEzRating.getRatings('saved') === 1){
			var lns 	= [];
			var limit  	= 100;

			if(dataSourceResult.length < 101){
				limit = dataSourceResult.length;
			}
			for(var i = 0; i< limit; i++){
				lns.push(dataSourceResult[i]); 
				searchSelectedLines[dataSourceResult[i].id] = dataSourceResult[i];
			}	

			datagridSearchResults.getRatingsRecursivelly();
			datagridSearchResults.buildDemoColumns(formatDemos());
		}
					
			
					
		setSearchCountLabel(data.length);
		$("#dialog-window").dialog("destroy");

}








// ***********************    DO NOT REMOVE THIS IS FOR GRIDS      **********************************

function datasourceBuildGridOld(data,inserttype){
	
	$('#search-result-counter').html('Starting');
	

	var re = [];

	for(var i = 0; i < data.length; i++) {


		var startdatetime 	= "tz_start_"+timezone;
		var enddatetime 	= "tz_end_"+timezone;
	    var sD  			= data[i][startdatetime].split(/[^0-9]/);
	    var eD  			= data[i][enddatetime].split(/[^0-9]/);
		var startdatetime 	= new Date(sD[0],parseInt(sD[1])-1,sD[2],sD[3],sD[4]);
		var enddatetime 	= new Date(eD[0],parseInt(eD[1])-1,eD[2],eD[3],eD[4]);
		var startdate 		= startdatetime.toString("MM/dd/yyyy");
		var starttime 		= startdatetime.toString("hh:mm tt");
		var starttime24 	= startdatetime.toString("HH:mm");
		var enddate 		= enddatetime.toString("MM/dd/yyyy");
		var endtime 		= enddatetime.toString("hh:mm tt");

		var day 						= "day_"+timezone;		
		day 							= data[i][day];
		var a 							= data[i].stationnum+data[i].title+startdatetime+enddatetime;
		var b 							= a.replace(/[^a-z0-9]/gi,'');
		var formatStartDateTime 		= startdatetime.toString("yyyy/MM/dd HH:mm");
		var formatEndDateTime 			= enddatetime.toString("yyyy/MM/dd HH:mm");
		var formatStartDateTimeClean 	= startdatetime.toString("yyyyMMddHHmm");
		var formatEndDateTimeClean 		= enddatetime.toString("yyyyMMddHHmm");

		var search 	= Object.searchcriteria(data[i]);

		if(data[i].projected == '1'){
			search = 'Projected';
		}
		
		if(parseInt(data[i].packageid) > 0 && parseInt(data[i].projected) === 1){
			search = 'Package';
		}

		var row = {};
		
		row.avail 		= b;
		row.callsign 	= data[i].callsign;
		row.day 		= day;
		row.desc 		= data[i].descembed;
		row.desc60 		= data[i].desc60;
		row.enddate 	= enddate;
		row.enddatetime = formatEndDateTime;
		row.endtime 	= endtime;
		row.epititle 	= data[i].epititle;		
		row.genre 		= data[i].genre1;
		row.genre2 		= data[i].genre2;
		row.id 			= data[i].id + "-" + zoneid;
		row.isnew 		=  data[i].isnew;
		row.linetype 	= 'Fixed';		
		row.lineactive 	= 1;
		row.live 		= data[i].live;
		row.locked 		= false;
		row.packageId	= data[i].packageid;
		row.orgairdate 	= data[i].orgairdate;
		row.premiere 	= data[i].premierefinale;
		row.programid 	= data[i].tmsid;
		row.projected 	= data[i].projected;
		row.rate 		= 0;
		row.ratecardid 	= 0;
		row.ratevalue 	= 0;
		row.search 		= search;
		row.showid 		= data[i].showid;
		row.showtype 	= data[i].showtype;		
		row.split 		= 0;		
		row.spots 		= 1;
		row.spotsweek 	= 1;
		row.ssid 		= data[i].id;		
		row.stars 		= data[i].stars;		
		row.startdate 	= startdate;		
		row.startdatetime = formatStartDateTime;
		row.starttime 	= starttime;		
		row.stationnum 	= data[i].stationnum;
		row.stationname = data[i].stationname;
		row.timestamp 	= new Date();
		row.title 		= data[i].title;		
		row.total 		= 0;
		row.tvrating 	= data[i].tvrating;
		row.weeks 		= 1;
		row.year 		= data[i].year;		
		row.zone 		= zone;
		row.zoneid 		=  zoneid;

       	// formaters //
		row.availsDay 		= data[i].callsign + ' - ' + formatterDayOfWeek(day) + ' - ' + getAvailTimeByHour(starttime,12);
		row.availsShow 		= data[i].title + ' - ' + data[i].callsign + ' - ' + starttime + ' - ' + endtime;
		row.callsignFormat 	= data[i].callsign + "|" + data[i].stationname;
		row.dayFormat 		= formatterDayOfWeek(day);
		row.showLine 		= data[i].callsign + ' - ' + data[i].title + ' - ' + starttime + ' - ' + endtime;
		row.sortingStartDate= zone + formatStartDateTime + data[i].callsign + data[i].title;
		row.sortingMarathons= data[i].callsign + data[i].title + formatStartDateTime;
		row.statusFormat	= data[i].premierefinale + "|" + data[i].live + "|" + data[i].isnew;		
		row.statusOrder 	= Object.status(data[i].premierefinale,data[i].live,data[i].isnew);
		row.titleFormat 	= data[i].title + "|" + data[i].epititle;
		
		re.push(row);
	}

	$('#search-result-counter').html('Ending');

	return re;
}




Object.searchcriteria = function(row) {
	var re 				= [];
	var showgenre 		= [row.genre1.toLowerCase(),row.genre2.toLowerCase()];
	var search 			= row.search.toLowerCase();
	var list 			= datagridKeywords.getSelectedData();
	var genrelist 		= arrayGenre;
	var len 			= Object.size(genrelist);
    var selectedValues 	= $('#search-tvr').val();
    		
	if(searchType == 'actor'){
		list = datagridActorsSelected.getSelectedData();
	}

	for(var i = 0; i < list.length; i++) {
		var n = search.indexOf(list[i].id.toLowerCase().trim());
		if(parseInt(n) != -1){
			re.push(list[i].id);
		}
	}
	
	if(len > 0){
		for (key in genrelist) {
			var z = Object.find(showgenre,key);
			if(z == true){
				re.push(key);
			}
		}
	}

    if(selectedValues[0] !== 0) {
		if(row.tvrating !=='' &&  $.inArray(row.tvrating,selectedValues) !== -1){
			re.push(row.tvrating);
		}
	}
	
	return re;
}


//format the premiere line sorting
Object.status = function(premiere,live,isnew){
	var re = 500;

  	if(isnew == 'New'){re = 7;}
    if(live == 'Live'){re = 6;}
    if(premiere == 'Series Finale'){re = 5;}
    if(premiere == 'Season Finale'){re = 4;}
    if(premiere == 'Series Premiere'){re = 3;}
    if(premiere == 'Season Premiere'){re = 2;}
    if(premiere == 'Premiere'){re = 1;}

  	return re;
}


