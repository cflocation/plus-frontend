function datasourceBuildGrid(data){
	
	if(data.length === 0){
		$("#dialog-window").dialog("destroy");
		return;
	}
	$("#search-result-counter-total").html(data.length);
	
	var startdatetime, enddatetime, startdate,starttime,enddat,endtime,day,row,val,formatStartDateTimeClean;
	
	for(var j=0; j< data.length; j++){		
		val = data[j];
		//if(val.callsign !== ''){
		startdatetime 		= "tz_start_"+timezone;
		enddatetime 		= "tz_end_"+timezone;
        
        var startDT 		= val[startdatetime].split(/[^0-9]/);
        startdatetime 		= new Date(parseInt(startDT[0]),parseInt(startDT[1])-1,parseInt(startDT[2]),parseInt(startDT[3]),parseInt(startDT[4]));
        
		startdate 			= (startdatetime).toString("MM/dd/yyyy");
		starttime 			= (startdatetime).toString("hh:mm tt");	    		
		
        var endDT 			= val[enddatetime].split(/[^0-9]/);
		enddatetime 		= new Date(parseInt(endDT[0]),parseInt(endDT[1])-1,parseInt(endDT[2]),parseInt(endDT[3]),parseInt(endDT[4]));		
		
		enddate 			= (enddatetime).toString("MM/dd/yyyy");
		endtime 			= (enddatetime).toString("hh:mm tt");
		day 				= "day_"+timezone;
		day 				= val[day];

		formatStartDateTimeClean	= Date.parse(startdatetime).toString("yyyyMMddHHmm");
		formatStartDateTimeClean 	= formatStartDateTimeClean.replace(/[^a-zA-Z0-9 ]/g, '');
		
		
		row 				= {};
		row.id 				= val.id+'-'+zoneid;
		row.showtype 		= val.showtype;
		row.showid 			= val.showid;
		row.live 			= val.live;
		row.premiere		= val.premierefinale;
		row.isnew 			= val.isnew;
		row.stationnum 	= val.stationnum;
		row.stationname 	= val.stationname;
		row.callsign 		= val.callsign;
		row.title 			= val.title;
		row.desc 			= val.descembed;
		row.duration 		= val.duration;
		row.genre1 			= val.genre1;
		row.genre2 			= val.genre2;
		row.stars 			= val.stars;
		row.epititle 		= val.epititle;
		row.startdate 		= startdate;
		row.startdatetime 	= (startdatetime).toString("yyyy/MM/dd hh:mm:ss");//startdatetime;
		row.starttime 		= starttime;
		row.enddate 		= enddate;
		row.endtime 		= endtime;
		row.sortingStartDate= formatStartDateTimeClean;
		row.dayFormat 		= formatterDayOfWeek(String(day));
		if(val.epititle){
			row.titleFormat 	= val.title + "|" + val.epititle;
		}
		else{
			row.titleFormat 	= val.title;			
		}
		row.zone 			= zone;
		row.zoneid 			= zoneid;
		row.day 			= day;
		row.statusFormat 	= val.premierefinale || val.live || val.isnew;
		row.search 			= criteria(val);
		
		
		if(row.statusFormat === undefined){
			row.statusFormat = '';
		}
		dataSourceResult.push(row);
		//}
	}
	
	datagridSearchResults.populateDataGrid(dataSourceResult);
	setSearchCountLabel(data.length);
	$("#dialog-window").dialog("destroy");
	return;

};


function criteria(row){
	var r = [];
	
	if('genre1' in row){
		if(row.genre1.toLowerCase() in arrayGenre){
		r.push(row.genre1);		
		}
	}	

	if('genre2' in row){
		if(row.genre2.toLowerCase() in arrayGenre){
		r.push(row.genre2);		
		}
	}	
	

	return r;
}







// ***********************    DO NOT REMOVE THIS IS FOR GRIDS      **********************************

function datasourceBuildGridOld(data,inserttype){
	
	$('#search-result-counter').html('Starting');
	

	var re = [];

	for(var i = 0; i < data.length; i++) {


		var startdatetime = "tz_start_"+timezone;
		startdatetime = data[i][startdatetime];

		var startdate = Date.parse(startdatetime).toString("MM/dd/yyyy");
		var starttime = Date.parse(startdatetime).toString("hh:mm tt");
		var starttime24 = Date.parse(startdatetime).toString("HH:mm");
		

		var enddatetime = "tz_end_"+timezone;
		enddatetime = data[i][enddatetime];

		var enddate = Date.parse(enddatetime).toString("MM/dd/yyyy");
		var endtime = Date.parse(enddatetime).toString("hh:mm tt");

		var day = "day_"+timezone;
		day 	= data[i][day];

		var a 	= data[i].stationnum+data[i].title+startdatetime+enddatetime;
		var b 	= a.replace(/[^a-z0-9]/gi,'');
		

		var formatStartDateTime = Date.parse(startdatetime).toString("yyyy/MM/dd HH:mm");
		var formatEndDateTime = Date.parse(enddatetime).toString("yyyy/MM/dd HH:mm");
		
		var formatStartDateTimeClean = Date.parse(startdatetime).toString("yyyyMMddHHmm");
		var formatEndDateTimeClean = Date.parse(enddatetime).toString("yyyyMMddHHmm");

		var search = Object.searchcriteria(data[i]);

		if(data[i].projected == '1'){
			search = 'Projected';
		}
		if(parseInt(data[i].packageid) > 0 && data[i].projected == '1'){
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
};






Object.searchcriteria = function(row) {
	var re 			= [];
	if(row.genre2){
	var showgenre 	= [row.genre1.toLowerCase(),row.genre2.toLowerCase()];
	}
	else{
	var showgenre 	= [row.genre1.toLowerCase()];		
	}
	var genrelist 	= arrayGenre;
	var len 		= Object.size(genrelist);
	if(len > 0){
		for (key in genrelist) {
			if(Object.find(showgenre,key)){
				re.push(key);
			}
		}
	}
	return re;
};


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
};


