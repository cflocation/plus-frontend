var groupedLinesQueue 	= [];
var groupedLineCurrIndex= 0;
var manualSpotAllocation= false;


function proposalAddRotator(close,rotatorData, callBack){
	var params = solrSearchParamaters();
	var hiddenWeeksArray = [];
	var tmpApiUr;
	
	if(dateTimeValidator() != 0){
		return;
	}
	
	var netid = params.networks[0].id;
	
	if(netid == 0 && rotatorData === undefined){
		loadDialogWindow('selectnetworksproposal', 'ShowSeeker Plus', 450, 180, 1);
		return;
	}

	$('input:radio[name=menu-items][value=proposal-build]').click();

	if(proposalid > 0){
		if(callBack === undefined){
			closeAllDialogs();
			loadDialogWindow('createrotators', 'ShowSeeker Plus', 450, 180, 1);
		}
		var data = {};

		//ADDING ROTATOR FROM FIXED LINES GROUPED
		if(rotatorData){
			data = rotatorData;
			tmpApiUrl =  apiUrl+"proposal/addline/rotator";	
		}
		//ADDING ROTATORS
		else{
						
			var startdate 	= Date.parse(params.startdate).toString("yyyy-MM-dd");
			var enddate 	= Date.parse(params.enddate).toString("yyyy-MM-dd");
			var starttime 	= Date.parse(params.starttime).toString("HH:mm:ss");
			var endtime 	= Date.parse(params.endtime).toString("HH:mm:ss");
			var lineOrder	= false; 
			var networks    = [];
			var rcs   		= [];
			var rateCardVal;
			var spotId;
			
			if($('#yes').is(':checked')){
				lineOrder = true;
			}
			var rcCorps = [4,10,13,16,18,20,32];
			if(rcCorps.indexOf(parseInt(corpid))  === -1){
				$.each(params.networks,function(i,net){ networks.push(parseInt(net.id));});
			}			
			else{//This is to collect RCs for Suddenlink, ZoloMedia and GCI
				var line = {};
				var rc;
				line.zoneid 		= parseInt(zoneid);
				line.starttime		= starttime;
				line.endtime		= endtime;
				line.startdatetime 	= startdate +' '+ starttime;
				line.enddatetime	= enddate +' '+ endtime;				
				line.linetype		= 'Rotator';
				line.day			= params.days;
				
				$.each(params.networks,function(i,net){
					rateCardVal 			= {};
					line.stationnum 		= net.id;
					rc = ratecardType(rateType,line,ratecardData);	
					rateCardVal.networkId 	= net.id;
					rateCardVal.rateValue 	= rc;
					rcs.push(rateCardVal);
					networks.push(parseInt(net.id));
				});
			}

			data.proposalId = proposalid;
			data.zoneId		= parseInt(zoneid);
			data.spots 		= {};
			tmpApiUrl	=  apiUrl+"proposal/addline/linebyday";
	
			if($('#daily').is(':checked')){
				
				//ALLOCATING SPOTS BY DAY
				var tmpDays = [];
				var v = 0;
				// PARSING DAYS OF THE WEEK
				for(var x in arrayDays){
					if(!isNaN(parseInt(arrayDays[x]))){
						tmpDays.push(parseInt(arrayDays[x]));
					}
				}
				
				//COUNTING ACTIVE DAYS TO COMPARA WITH THE CACHE	
				for(var ii in weekDaysObj){
					if($.inArray(weekDaysObj[ii], tmpDays) !== -1){
						v++;
					}
				}		
			
				if($.isEmptyObject(spotsByDayOfWeek) || v !== tmpDays.length){
					spotsByDayOfWeek = allocatingSpots();
				}
	
				for(var ii in weekDaysObj){
					data.spots[weekDaysObj[ii]] = parseInt(spotsByDayOfWeek[ii]);	
				}
	
	
				var tSpots = 0;
				
				for(var x in data.spots){
					tSpots += parseInt(data.spots[x]); 
				}
				
				if(tSpots === 0){
					data.spots		= parseInt(params.schedulespots);
				}
				//LINE IS CREATED FROM POPUP
				data.manualSpotAllocation = manualSpotAllocation;
				
			}
			else{
				tmpApiUrl =  apiUrl+"proposal/addline/rotator";
				data.spots		= parseInt(params.schedulespots);
			}
	
			data.networks		= networks;
			data.days 			= params.days;
			data.dayparts		= params.dayparts;
			data.startDate 		= startdate;
			data.endDate		= enddate;
			data.startTime		= starttime;
			data.endTime		= endtime;
			data.rate			= params.schedulerate;
			data.ratecardId		= params.ratecardId;
			data.lineOrder		= lineOrder;
			data.sendNewLines	= true;
			data.ratecards		= rcs;
			data.ratingsSettings=setProposalRatings();
		}
							
		if(weeksdata.length > 0){
			data.inactiveWeeks = getInactiveWeeks();
		}
		
		$.ajax({
			type:'post',
			url: tmpApiUrl,
			dataType:"json",
			headers:{"Api-Key":apiKey,"User":userid},
			processData: false,
			contentType: 'application/json',
	    	data: JSON.stringify(data),
			success:function(resp){
				datagridProposalManager.updateSelectedProposalRow(resp);
				datagridProposal.buildEmptyGrid();				
				proposalRattingsOn = resp.ratings;
				manualSpotAllocation = false;
				spotsByDayOfWeek = {};
				if(resp.lines.length>0){
					var lineActiveWk;
					$.each(resp.lines,function(i,l){
						//ADDING LINES TO THE PROPOSAL GRID
						t = formatProposalLine(l);
		                datagridProposal.addRotatorToProposal(t);
		            });

					datagridProposal.populateDataGrid();
					datagridProposal.scrollRowIntoViewPort(resp.lines[0].id);			
					datagridTotals.populateDataGridGoPlus(datagridProposal.dataSet(),resp.dates.startDate,resp.dates.endDate,resp.totals);	
				}

				if(callBack === undefined){
					$("#dialog-window").dialog("destroy");
					$("#dialog-networks").dialog("destroy");
				} else {
					callBack();
				}
			},
			error:function(){manualSpotAllocation = false;}
		});
	}
	else{
		saveProposal();
		callAfterProposalCreate 	= proposalAddRotator;
		paramcallAfterProposalCreate= close;
	}
}

function formatDatesForLine(lineDate){
	var newDate,d,y,m;
	newDate = new Date.parse(lineDate);
	d = newDate.getDate();
	y = newDate.getFullYear();
	m = parseInt(newDate.getMonth()) + 1;
	return m+'/'+d+'/'+y;
}




function gettoptitles(data,type){

	var titles = [];
	var re = 'Various';

	for (var i = 0; i < data.length; i++){
		var row = [data[i].doclist.numFound,data[i].groupValue,data[i].doclist.docs[0].showid];
		
		titles.push(row);
	}

	titles.sort( function( a, b ){
  		// Sort by the 2nd value in each array
  		if ( a[0] == b[0] ) return 0;
  		return a[0] < b[0] ? -1 : 1;
	});

	titles.reverse();

	if(type == 'ids'){
		re = '';
		if(titles.length > 0){
		re = '';
			for (var i = 0; i < titles.length; i++){
				re += titles[i][2]+', ';
				if(i == 5){
					break;
				}
			}
		}
	}
	else{	
		if(titles.length > 0){
		re = '';
			for (var i = 0; i < titles.length; i++){
				re += titles[i][1]+', ';
				if(i == 5){
					break;
				}
			}
		}
	}
	return re;
}


//ADD A LINE FROM A GROUP
function proposalAddLineFromGroup(rows,zoneid,zone,type){	
	var params = solrSearchParamaters();
	var availtype = datagridSearchResults.getGroupByColumn();

	var availgroup = [];

	//loop over all the records
	for(var i = 0; i < rows.length; i++) {

		//if this is a group please find all the shows in the group
		if (typeof rows[i].__group != 'undefined') {
			if(rows[i].rows.length == 1){
				addSelectionToProposal([rows[i].rows[0]],zoneid,zone,'drop');	
			}else{
				availgroup.push(rows[i]);
			}

		}else{
			addSelectionToProposal(rows,zoneid,zone,'drop');
		}
	}

	var rows = availgroup;
	processGroupLines(rows,zoneid,zone,type,availtype);
}


//PROCESS LINE FOR LINE ADD
function processGroupLines2(rowsObj,zoneid,zone,type,availtype){

	var allWeeks, availday, dayint, daysformat, enddate, endtime;
	var i, group, lineOrder, mon;
	var q, rate, rotatorData, row, rows,  spweek, startdate;
	var starttime, tempend, weeks, weektotals, weekDesc;

	groupedLinesQueue = []; //empty the queue
	groupedLineCurrIndex = 0; //reset index to 0
		
	for(var g=0; g<rowsObj.length; g++){
		
		rows 	= rowsObj[g].rows;
		
		if(rows.length === 0){
			$("#dialog-creating-avails").dialog("destroy");
			return;
		}
		
		if(z < rows.length){
	
			//loop over the group to build line
			group 			= rows;
			
			if(rows.length === 1){
				//addFixedLinesToProposal(rows);
				groupedLinesQueue.push({"type":"fixed","row":rows}); //add fixed line to queue
				continue;
			}

			startdate 		= Date.parse( group.sort(startDate)[0].startdatetime ).toString("yyyy-MM-dd");
			enddate 		= Date.parse( group.sort(endDate)[0].enddatetime ).toString("yyyy-MM-dd");
			starttime 		= Date.parse( group[0].startdatetime ).toString("HH:mm:ss");
			endtime 		= Date.parse( group[0].enddatetime ).toString("HH:mm:ss");
			availday 		= [];
	
			if(availtype === "availsDay"){
				tempend 	= Date.parse(group[0].startdatetime).add({hours: 1});
				endtime 	= Date.parse(tempend).toString("HH:mm:ss");
			}

			weeks 			= buildBroadcastWeeks(startdate,enddate);
			weektotals 		= {};
			row 			= {};
			weekDesc		= {};
			allWeeks 		= [];
			rotatorData 	= {};			
			weekcnt 		= weeks.length;

			//get the weeks for the selected dates	
			for(i = 0; i < group.length; i++) {
				mon = getMondayFromDate(group[i].startdatetime);
	
				if(! weektotals[mon]){
					weektotals[mon] = 1;
				}
				else{
					weektotals[mon] = weektotals[mon] + 1;
				}
				
				dayint = parseInt(group[i].day);
	
				if(dofind(availday,dayint) === false){
					availday.push(dayint);
				}
			}
			
			daysformat 		= getAvailDays(group);
				
			//do the math for the spots
			for (i=0; i < weeks.length; i++){
				q 				= 'w'+weeks[i].column;				
				weekDesc 		= {};
				weekDesc.week 	= weeks[i].dateISO;
				weekDesc.spots	= 0;
				if(weektotals[q]){
					weekDesc.spots	= parseInt(weektotals[q]);
				}
				row[q] 			= weektotals[q];
				allWeeks.push(weekDesc);	
			}
			
			spweek = parseInt(group.length/weeks.length);
			
			if(spweek === 0){
				spweek = 1;
			}
				
			rate 				= ratecardType(rateType,row,ratecardData);
			lineOrder   		= ($('input[name=line-mode-selector]:checked').val()=='yes');
			
			
			rotatorData.proposalId 	= proposalid;
			rotatorData.zoneId 		= parseInt(zoneid);
			rotatorData.spots		= parseInt(spweek);
			rotatorData.rate		= rate;
			rotatorData.networks	= [group[0].stationnum];
			rotatorData.days		= availday;
			rotatorData.startDate	= startdate;
			rotatorData.endDate		= enddate;
			rotatorData.startTime	= starttime;
			rotatorData.endTime		= endtime;
			rotatorData.lineOrder	= lineOrder;
			rotatorData.weekSpots	= allWeeks;
			rotatorData.title		= group[0].title;
			rotatorData.showId		= [group[0].showid];
			rotatorData.sendNewLines=true;		
			
			//proposalAddRotator(true,rotatorData);
			groupedLinesQueue.push({"type":"rotator","row":rotatorData}); //add rotator to queue
		}
	}

	processGroupedLinesQueue(); //process the queue
}


function processGroupedLinesQueue(){
	if(groupedLinesQueue.length > groupedLineCurrIndex){
		
		var currentLineNum = groupedLineCurrIndex+1;
		$("#avail_current").html(currentLineNum.toString());
		
		if(groupedLinesQueue[groupedLineCurrIndex].type=="fixed"){
			addFixedLinesToProposal(groupedLinesQueue[groupedLineCurrIndex].row,processGroupedLinesQueue);
		} else {
			proposalAddRotator(true,groupedLinesQueue[groupedLineCurrIndex].row,processGroupedLinesQueue);
		}

		groupedLineCurrIndex++;
	} else {
		$("#dialog-window").dialog("destroy");
		$("#dialog-networks").dialog("destroy");
		groupedLinesQueue = []; //reset queue
		groupedLineCurrIndex = 0; //reset counter
	}
}

//make the groups selected into an array
function proposalAddAvailDDD(rows,zoneid,zone,type){
	
	var params 		= solrSearchParamaters();
	var availtype 	= datagridSearchResults.getGroupByColumn();
	var startdate 	= Date.parse(params.startdate).toString("yyyy/MM/dd");
	var enddate 	= Date.parse(params.enddate).toString("yyyy/MM/dd");
	var availgroup 	= [];

	//loop over all the records
	for(var i = 0; i < rows.length; i++) {

		//if this is a group please find all the shows in the group
		if (typeof rows[i].__group != 'undefined') {
			var group = rows[i].rows;
			availgroup.push(rows[i]);
		}
	}

	var rows = availgroup;
	
	if(rows.length == 0){
		return;
	}
	processAvailLines(rows,zoneid,zone,type,startdate,enddate,availtype);
}


//make the groups selected into an array
function proposalAddAvailFromDrag(rows,zoneid,zone,type){

	var params = solrSearchParamaters();
	var availtype = datagridSearchResults.getGroupByColumn();

	var availgroup = [];
	//Object.find = function(arr,obj) {
	//loop over all the records
	for(var i = 0; i < rows.length; i++) {

		//if this is a group please find all the shows in the group
		if (typeof rows[i].__group != 'undefined') {
			if(rows[i].rows.length == 1){
				addSelectionToProposal([rows[i].rows[0]],zoneid,zone,'drop');
			}else{

			//var group = rows[i].rows;
			availgroup.push(rows[i]);
			}

		}else{
			addSelectionToProposal(rows,zoneid,zone,'drop');
		}
	}

	var rows = availgroup;
	processAvailLines(rows,zoneid,zone,type,availtype);
}



//process the lines into the proposal function processAvailLines(rows,zoneid,zone,type,startdate,enddate,availtype){
function processAvailLines2(rows,zoneid,zone,type,availtype){
	if(rows.length == 0){
		$("#dialog-creating-avails").dialog("destroy");
		return;
	}

	var params = solrSearchParamaters();


	var startdate = startdate;
	var enddate = enddate;
	var datarows = rows[0].rows;


	//loop over the group to build line
	var group 			= rows[z].rows;
	var lineDateStart 	= group.sort(startDate)[0].startdatetime;
	var lineDateEnd 	= group.sort(endDate)[0].enddatetime;
	var startdate,enddate;
	
	if(availtype == 'availsShow'){
		startdate = Date.parse(lineDateStart).toString("yyyy/MM/dd");
		enddate = Date.parse(lineDateEnd).toString("yyyy/MM/dd");
	}
	else{
		startdate = Date.parse(params.startdate).toString("yyyy/MM/dd");
		enddate = Date.parse(params.enddate).toString("yyyy/MM/dd");
	}
	
	var availday 	= [];
	var availtitle 	= [];
	var availnew 	= [];
	var starttime 	= Date.parse(group[0].startdatetime).toString("hh:mm tt");
	var endtime 	= Date.parse(group[0].enddatetime).toString("hh:mm tt");

	if(availtype == "availsDay"){
		var tempend = Date.parse(group[0].startdatetime).add({hours: 1});
		endtime = Date.parse(tempend).toString("hh:mm tt");
	}

	//parse the dates as the new ones
	var startdateStr 		= startdate+' '+starttime;
	var enddateStr 			= enddate+' '+endtime;
	var formatStartDateTime = new Date(startdateStr).toString("yyyy/MM/dd HH:mm");
	var formatEndDateTime 	= new Date(enddateStr).toString("yyyy/MM/dd HH:mm");
	var formatStartDateTimeClean 	= Date.parse(startdateStr).toString("yyyyMMddHHmm");
	var formatEndDateTimeClean 		= Date.parse(enddateStr).toString("yyyyMMddHHmm");

	for(var i = 0; i < group.length; i++) {
	
		var dnum = parseInt(group[i].day);

		if(dofind(availday,dnum) == false){
			availday.push(dnum);
		}

		if(dofind(availtitle,group[i].title) == false){
			availtitle.push(group[i].title);
		}

		if(dofind(availnew,group[i].isnew) == false){
			availnew.push(group[i].isnew);
		}
	}


	var daysformat = getAvailDays(group);

	//get the weeks for the selected dates
	var weeks 	= buildBroadcastWeeks(startdate,enddate);

	weekcnt 	= weeks.length;


	var row = {};
	row.id = group[0].avail + "-" + zoneid;
	row.ssid = group[0].avail;
	row.zone = zone;
	row.zoneid =  zoneid;
	row.linetype = 'Rotator';
	row.title = availtitle;
	row.callsign = group[0].callsign;
	row.stationnum = group[0].stationnum;
	row.stationname = group[0].stationname;
    row.startdate = startdate;
    row.enddate = enddate;
    row.starttime = starttime;
    row.endtime = endtime;
	row.startdatetime = formatStartDateTime;
	row.enddatetime = formatEndDateTime;
	row.day = availday;
	row.desc = '';
	row.epititle = '';
	row.live = '';
	row.premiere = '';
    row.isnew =  '';
    row.stars = '';
    row.orgairdate = '';
    row.year = '';
    row.tvrating = '';
    row.showtype = '';
    row.programid = '';
    row.lineactive = 1;
    row.search = '';

   	//scheduler features
   	row.locked = false;
   	row.rate = 0;
   	row.ratecardid = 0;
   	row.ratevalue = 0;
   	row.weeks = weekcnt;
   	row.spotsweek = 1;
   	row.timestamp = new Date();
   	row.total = 0;
   	row.split = 0;

	// formaters //
	row.titleFormat = availtitle;
	row.callsignFormat = group[0].callsign + "|" + group[0].stationname;
	row.dayFormat = daysformat;
	row.statusFormat = '|||'
	row.sortingStartDate = zone + formatStartDateTimeClean + group[0].callsign + availtitle;

	//do the math for the spots
	var spcnt = 0;
	for (var i = 0; i < weeks.length; i++){
		var q =  'w'+weeks[i].column;
		var findweek = proposalFindInWeek(datarows,q);
		
		if(findweek == 1){
			row[q] = 1;
			spcnt++;
		}else{
			row[q] = 0;
		}
	}
	
	row.spots = parseInt(spcnt);
	datagridProposal.addRotatorToProposal(row);
}

//process the lines into the proposal function processAvailLines(rows,zoneid,zone,type,startdate,enddate,availtype){
var z = 0;
function processAvailLines(rows,zoneid,zone,type,availtype){



	if(rows.length == 0){
		$("#dialog-creating-avails").dialog("destroy");
		return;
	}

	var params = solrSearchParamaters();


	var startdate = startdate;
	var enddate = enddate;
	var datarows = rows[0].rows;


	$("#avail_total").html(rows.length);

	

	if(z < rows.length){

		
			//loop over the group to build line
			var group 			= rows[z].rows;
			var lineDateStart 	= group.sort(startDate)[0].startdatetime;
			var lineDateEnd 	= group.sort(endDate)[0].enddatetime;


			if(availtype == 'availsShow'){
				var startdate = Date.parse(lineDateStart).toString("yyyy/MM/dd");
				var enddate = Date.parse(lineDateEnd).toString("yyyy/MM/dd");
			}else{
				var startdate = Date.parse(params.startdate).toString("yyyy/MM/dd");
				var enddate = Date.parse(params.enddate).toString("yyyy/MM/dd");
			}

			var availday = [];
			var availtitle = [];
			var availnew = [];

			$("#avail_current").html(z+1);


			var starttime = Date.parse(group[0].startdatetime).toString("hh:mm tt");
			var endtime = Date.parse(group[0].enddatetime).toString("hh:mm tt");

			if(availtype == "availsDay"){
				var tempend = Date.parse(group[0].startdatetime).add({hours: 1});
				endtime = Date.parse(tempend).toString("hh:mm tt");
			}

			//parse the dates as the new ones
			var startdateStr = startdate+' '+starttime;
			var enddateStr = enddate+' '+endtime;

			var formatStartDateTime = new Date(startdateStr).toString("yyyy/MM/dd HH:mm");
			var formatEndDateTime = new Date(enddateStr).toString("yyyy/MM/dd HH:mm");


			var formatStartDateTimeClean = Date.parse(startdateStr).toString("yyyyMMddHHmm");
			var formatEndDateTimeClean = Date.parse(enddateStr).toString("yyyyMMddHHmm");

			for(var i = 0; i < group.length; i++) {

				if(dofind(availday,group[i].day) == false){
					availday.push(group[i].day);
				}

				if(dofind(availtitle,group[i].title) == false){
					availtitle.push(group[i].title);
				}

				if(dofind(availnew,group[i].isnew) == false){
					availnew.push(group[i].isnew);
				}
			}


			var daysformat = getAvailDays(group);

			//get the weeks for the selected dates
			var weeks = buildBroadcastWeeks(startdate,enddate);
			weekcnt = weeks.length;



			var row = {};
			row.id = group[0].avail + "-" + zoneid;
			row.ssid = group[0].avail;
			row.zone = zone;
			row.zoneid =  zoneid;
			row.linetype = 'Rotator';
			row.title = availtitle;
			row.callsign = group[0].callsign;
			row.stationnum = group[0].stationnum;
			row.stationname = group[0].stationname;
	        row.startdate = startdate;
	        row.enddate = enddate;
	        row.starttime = starttime;
	        row.endtime = endtime;
			row.startdatetime = formatStartDateTime;
			row.enddatetime = formatEndDateTime;
			row.day = availday;
			row.desc = '';
			row.epititle = '';
			row.live = '';
	        row.genre = '';
	        row.genre2 = '';
			row.premiere = '';
	        row.isnew =  availnew;
	        row.stars = '';
	        row.orgairdate = '';
	        row.year = '';
	        row.tvrating = '';
	        row.showtype = '';
	        row.programid = '';
	        row.lineactive = 1;
	        row.search = '';

	       	//scheduler features
	       	row.locked = false;
	       	row.rate = 0;
	       	row.ratecardid = 0;
	       	row.ratevalue = 0;
	       	row.weeks = weekcnt;
	       	row.spotsweek = 1;
	       	row.timestamp = new Date();
	       	row.total = 0;
	       	row.split = 0;

			// formaters //
			row.titleFormat = availtitle;
			row.callsignFormat = group[0].callsign + "|" + group[0].stationname;
			row.dayFormat = daysformat;
			row.statusFormat = '|||'
			row.sortingStartDate = zone + formatStartDateTimeClean + group[0].callsign + availtitle;


			//do the math for the spots
			var spcnt = 0;
			for (var i = 0; i < weeks.length; i++){
				var q =  'w'+weeks[i].column;
				var findweek = proposalFindInWeek(datarows,q);
				
				if(findweek == 1){
					row[q] = 1;
					spcnt++;
				}else{
					row[q] = 0;
				}
			}
			
			row.spots = parseInt(spcnt);
			datagridProposal.addRotatorToProposal(row);
			z++;
			setTimeout(function() { processAvailLines(rows,zoneid,zone,type,availtype) },100)
	}else{
		z = 0;
		needSaving = true;
		datagridProposal.buildGrid();
		datagridProposal.populateDataGrid();
		datagridTotals.populateDataGrid(datagridProposal.dataSet());
		$("#dialog-creating-avails").dialog("destroy");
		closeAllDialogs();

		if(proposalid == 0){
    		saveProposal();
    	}
	}
}


function proposalFindInWeek(data,week){
	for (var i = 0; i < data.length; i++){
		var mon = getMondayFromDate(data[i].startdatetime);
		if(mon == week)
			return 1;
		}
	return 0;
}


function proposalAddLine(close){

	var params = solrSearchParamaters();
	var netid = params.networks[0].id;
	
	if(netid == 0){
		loadDialogWindow('selectnetworksproposal', 'ShowSeeker Plus', 450, 180, 1);
		return;
	}

	var counter = 0;
	var weekcnt = 0;
	var networktotal = params.networks.length;
	var daysformat = schedulerDaysOfWeek($('#search-days').val());

	//break out the time from the dates
	var startdate = Date.parse(params.startdate).toString("yyyy/MM/dd");
	var enddate = Date.parse(params.enddate).toString("yyyy/MM/dd");

	var starttime = Date.parse(params.starttime).toString("hh:mm tt");
	var endtime = Date.parse(params.endtime).toString("hh:mm tt");

	//parse the dates as the new ones
	var startdateStr 			= startdate+' '+starttime;
	var enddateStr 				= enddate+' '+endtime;
	var formatStartDateTime 	= new Date(startdateStr).toString("yyyy/MM/dd HH:mm");
	var formatEndDateTime 		= new Date(enddateStr).toString("yyyy/MM/dd HH:mm");
	var formatStartDateTimeClean= Date.parse(startdateStr).toString("yyyyMMddHHmm");
	var formatEndDateTimeClean 	= Date.parse(enddateStr).toString("yyyyMMddHHmm");

	//setup the default if the values are blank
	if(params.schedulerate == ''){
		params.schedulerate = 0;
	}
	
	if(params.schedulespots == ''){
		params.schedulespots = 0;
	}
	
	//get the weeks for the selected dates
	var weeks 	= buildBroadcastWeeks(startdate,enddate);

	//calculating rotator number of weeks
	weekcnt 	= weeks.length;
	for(i=0;i<weeksdata.length;i++){
		for(j=0;j<weeks.length;j++){
			if(weeksdata[i] == 'w'+weeks[j]['column']){
				weekcnt = weekcnt-1;
				break;
			}	
		}
	}	
	
	if(weekcnt<1){
		weekcnt = 1;		
	}
	
	

	//get start and end weeks and subtract 7 to get how many days of the week are left. Mon = 7 Sun = 1
	var startweekdaycount = 7- fixjsdayofweek(new Date(startdate).getDay());
	var endweekdaycount = 7- fixjsdayofweekEnd(new Date(enddate).getDay());

	//get the spots to be scheduled over the weeks and divide by the total weeks to get an avg for all the weeks
	var totalspots = parseInt(params.schedulespots);
	var avgperweek = parseInt(totalspots/weekcnt);


	//multiply the average per week by the week count to the get total used spots then subtract them ftom the total spots this 
	//gives you the remaining spots to publish over the weeks
	var totalusedfromavg = avgperweek * weekcnt;
	var totalspotsleftfromavg = totalspots - totalusedfromavg;

	//take the total days in the start week and end week then divide by 7 and multiply by the average per week
	//this gives us the percentage of spots needed for the weeks. Use math.ceil to round up so there is always at least 1 spot in a week
	var avgfromfullweekstart = Math.ceil(startweekdaycount/7*avgperweek);
	var avgfromfullweekend = Math.ceil(endweekdaycount/7*avgperweek);

	//now get the reamining spots carried over from the start and end weeks
	var weekstartleftover = avgperweek - avgfromfullweekstart;
	var weekendleftover = avgperweek - avgfromfullweekend;

	//now add the remaining spots to the unused spots pool so we no what number is left
	var totalunusedspots = totalspotsleftfromavg + weekstartleftover + weekendleftover;


	//set count in the first and last week
	weeks[0]['count'] = avgfromfullweekstart;
	weeks[weekcnt-1]['count'] = avgfromfullweekend;


	//loop over the middle weeks and set the avg
	for (var i = 1; i < weekcnt-1; i++){
		weeks[i]['count'] = avgperweek;
	}


	//set the loop to reset the weeks for count addition
	var weekloop = 0;

	for (var i = 0; i < totalunusedspots; i++){
		weeks[weekloop]['count'] = weeks[weekloop]['count'] + 1;
		weekloop ++;
		if(weekloop == weekcnt){
			weekloop = 0;
		}
	}
	
	
	var url = "/services/1.0/search.php";
	var netlist = params.networks;
	
	$.when(buildToken(url)).done(function(token){

		$.each(params.networks, function(i, network){
			var w 	= weekcnt;
			//set the paramaters for the network
			params.networks = [network];

			//build the title url string for the title search
			var titleurl = solrSearchGroup(params,'full');

			var callsign = params.networks[0].callsign;
			var stationnum = params.networks[0].id;
			var stationname = params.networks[0].name;
			url = token['url']+"&xrl="+titleurl;	

			//grab the titles from the solr server
			$.getJSON(url, function(titledata) {	
			
				//set the title data for the rotatoe
				var titledata = titledata.grouped.sort.groups;
	
				//get the titles for the line
				var linetitles = gettoptitles(titledata);
				var showids = gettoptitles(titledata,'ids');
	
				//get the total spots for the line
				var totalspots = params.schedulespots*weekcnt;
	
				//key for line
				var uuid = GUID();
	
				var row = {};
				row.id = uuid + "-" + zoneid;
				row.ssid = uuid;
				row.zone = params.zone;
				row.zoneid =  params.zoneid;
				row.linetype = 'Line';
				row.title = linetitles;
				row.callsign = callsign;
				row.stationnum = stationnum;
				row.stationname = stationname;
		        row.startdate = startdate;
		        row.enddate = enddate;
		        row.starttime = starttime;
		        row.endtime = endtime;
				row.startdatetime = formatStartDateTime;
				row.enddatetime = formatEndDateTime;
				row.day = params.days;
				row.desc = '';
				row.epititle = '';
				row.live = '';
		        row.genre = '';
		        row.genre2 = '';
				row.premiere = '';
		        row.isnew =  '';
		        row.stars = '';
		        row.orgairdate = '';
		        row.year = '';
		        row.tvrating = '';
		        row.showtype = '';
		        row.programid = '';
		        row.lineactive = 1;
		        row.search = '';
		        row.showid =  showids;
	
		       	//scheduler features
		       	row.locked = false;
		       	row.rate = params.schedulerate;
		       	row.ratecardid = 0;
		       	row.ratevalue = 0;
		       	row.weeks = weekcnt;
		       	
		       	if(parseInt(avgperweek) > 0)
		       	row.spotsweek = parseInt(avgperweek);
		       	else
		       	row.spotsweek = 1;		       	
		       	row.spots = parseInt(params.schedulespots);
		       	row.timestamp = new Date();
		       	row.total = parseFloat(params.schedulespots)*parseFloat(params.schedulerate);
		       	row.split = 0;
	
		       	// formaters //
				row.titleFormat = linetitles;
				row.callsignFormat = callsign + "|" + stationname;
				row.dayFormat = daysformat;
				row.statusFormat = '|||';
				row.sortingStartDate = params.zone + formatStartDateTimeClean + callsign + linetitles;
	
				//var rate = ratecardType(rateType,row);
				var rate = ratecardType(rateType,row,ratecardData);
				row.ratevalue = rate;
	
				for (var i = 0; i < weeks.length; i++){
					var z =  'w'+weeks[i].column;
					row[z] = parseInt(weeks[i].count);
				}

				datagridProposal.addRotatorToProposal(row);

				counter++;

				if(counter == netlist.length){
					needSaving = true;
					datagridProposal.buildGrid();
					datagridProposal.populateDataGrid();
					datagridTotals.populateDataGrid(datagridProposal.dataSet());
	
					//clear the fields after insert
					$("#schedule-spots").val(0);
					$("#schedule-rate").val(0);
					$("#dialog-window").dialog("destroy");
					$("#dialog-networks").dialog("destroy");
	
					if(proposalid == 0){
	    				saveProposal();
	    			}
	
	    			if(close){
	    				swapSettingsPanel('search',false);
	    			}
				}			
			});
		});
	});

}

//ad avail to the proposal
function proposalAddAvail(availtype){
	var ttl          = datagridNetworks.getSelectedTrueRow();
	var titlesdetect = $('input:radio[name=avails-detect-selector]:checked').val();
	titlesdetect     = (titlesdetect=='on');

	if(ttl[0] == 0){
		loadDialogWindow('selectnetworksproposal', 'ShowSeeker Plus', 450, 180, 1);		
		return;
	}

	closeAllDialogs();
	var params = solrSearchParamaters();
	
	//check that date and times are valid
	if(dateTimeValidator() != 0){
		return;
	}
	
	if(proposalid > 0){
		//are there dayparts or not
		var daypartsformat; 
		var dayparts 		= []; 
		var tmpDayParts 	= $("#avails-"+availtype).val();
		var quarters 		= $("#quarter-selector").val();
		
		//start/end date and time
		var startdate 		= params.startdate;
		var enddate   		= params.enddate;
		var availstart 		= params.starttime;
		var availend   		= params.endtime;

		
		//if there is only 1 daypart and it is 0 then get the times from the sidebar
		if(tmpDayParts.length === 1 && tmpDayParts[0] === "0"){
			daypartsformat 	= availstart + "|" + availend;
			dayparts.push(daypartsformat);
		}		
		
		for(var i=0; i<tmpDayParts.length; i++){
			if(tmpDayParts[i] !== "0"){
				dayparts.push(tmpDayParts[i]);
			}
		}

		//if there is only 1 quater and it is 0 then get the dates from the sidebar
		if(quarters.length == 1 && quarters[0] == '0'){
			var quartersformat = startdate + "|" + enddate;
			quarters = [quartersformat];
		}

		dialogCreatingAvails();


		var networks    = [];
		$.each(params.networks,function(i,net){ 
			networks.push(parseInt(net.id)); 
		});

		var data  			= {};
		hiddenWeeksArray	= [];
		data.proposalId		= proposalid;
		data.zoneId 		= parseInt(zoneid);
		data.networks 		= networks;
		data.days			= params.days;
		data.startDate  	= startdate;
		data.endDate		= enddate;
		data.startTime 		= availstart;
		data.endTime		= availend;
		data.dayParts 		= dayparts;
		data.quarters		= quarters;
		data.detectTitles 	= titlesdetect;
		data.sendNewLines 	= true;
		
		
		if(weeksdata.length > 0){
			var tmpD;
			for(var k = 0; k < weeksdata.length; k++){
				tmpD = String(weeksdata[k]).substr(1, 8);
				hiddenWeeksArray.push(tmpD.substr(4,4) + '-' + tmpD.substr(0,2) + '-' + tmpD.substr(2,2));
			}
			data.inactiveWeeks = hiddenWeeksArray;
		}		
		
		$.ajax({
			type:'post',
			url: apiUrl+"proposal/addline/avails",
			dataType:"json",
			headers:{"Api-Key":apiKey,"User":userid},
			processData: false,
			contentType: 'application/json',
			data: JSON.stringify(data),
			success:function(resp){
				if(resp.lines.length>0){
					datagridProposal.buildEmptyGrid();
					var t;					
					$.each(resp.lines,function(i,l){					
		                t = formatProposalLine(l);
		                datagridProposal.addRotatorToProposal(t);
					});
					datagridProposal.populateDataGrid();
					datagridProposalManager.updateSelectedProposalRow(resp);
					datagridProposal.scrollRowIntoViewPort(resp.lines[0].id);			
					datagridTotals.populateDataGridGoPlus(datagridProposal.dataSet(),resp.dates.startDate,resp.dates.endDate,resp.totals);						
					
				}
			
				$("#dialog-window").dialog("destroy");
				$("#dialog-networks").dialog("destroy");
			}
		});
	} else {
		saveProposal();
		callAfterProposalCreate = proposalAddAvail;
		paramcallAfterProposalCreate = availtype;
	}
};


function buildQuatersList(t){

	var cal = buildBroadCastCal2();

	if(t !== 'broadcast'){
		var cal = buildBroadCastCalNormal();
	}

	$('#quarter-selector')[0].options.length = 0;
	$('#quarter-selector').append($("<option></option>").attr("value", 0).text("Select Quarters"));
	$('#quarter-selector').val(0);

	var val,title,st,ed,displaytitle,stcomp,nowcomp;
	
	for (var i = 0; i < cal.length; i++){
		val 	= cal[i].starts + '|' + cal[i].ends;
		title 	= cal[i].quarter.split('|');
		st 		= new Date(cal[i].starts).toString("MM/dd");
		ed 		= new Date(cal[i].ends).toString("MM/dd");
		displaytitle = 'Q'+title[0]+'-'+title[1]+' '+st+' to '+ed;
		stcomp 	= new Date(cal[i].starts).toString("yyyyMMdd");
		nowcomp = new Date().toString("yyyyMMdd");

		if(stcomp > nowcomp){
			$('#quarter-selector').append($("<option></option>").attr("value", val).text(displaytitle));
		}
	}
};


function buildBorarcastCalNormal(){
	var re = [];
	var quatercnt = 1;

	var firstrday = Date.january().first();

	for(var i = 0; i < 12; i++) {

		var y =  new Date(firstrday).toString("yy");
		var s =  new Date(firstrday).toString("yyyy/MM/dd");
		var e =  new Date(firstrday).addMonths(2).moveToLastDayOfMonth().toString("yyyy/MM/dd");

		var xrow  = {};
		xrow.quarter = quatercnt+'|'+y;
		xrow.starts = s;
		xrow.ends = e;
		re.push(xrow);
		
		quatercnt++;
		if(quatercnt == 5){
			quatercnt = 1;
		}

		firstrday = new Date(firstrday).addMonths(3);
	}
	return re;
}

function buildBroadCastCalNormal(){
	var re = [];
	var quatercnt = 1;

	var firstrday = Date.january().first();

	for(var i = 0; i < 12; i++) {

		var y =  new Date(firstrday).toString("yy");
		var s =  new Date(firstrday).toString("yyyy-MM-dd");
		var e =  new Date(firstrday).addMonths(2).moveToLastDayOfMonth().toString("yyyy-MM-dd");

		var xrow  = {};
		xrow.quarter = quatercnt+'|'+y;
		xrow.starts = s+'T00:00:00Z';
		xrow.ends = e+'T23:59:59Z';
		re.push(xrow);
		
		quatercnt++;
		if(quatercnt == 5){
			quatercnt = 1;
		}

		firstrday = new Date(firstrday).addMonths(3);
	}
	return re;
}

function buildBorarcastCal2(){
	var re = [];
	var fisrday = Date.january().first();
	var starts = fisrday;
	
	var weekblock = []; 	
	var weekblockcnt = 0;
	var quatercnt = 1;

	if(fisrday.getDay() != 1){
		starts = new Date(fisrday).last().monday();
	}

	var weekcnt = 1;

	for(var i = 0; i < 104; i++) {
		var row  = {};

		row.week = weekcnt++;

		if(weekcnt == 53){
			weekcnt = 1;
		}

		row.year = new Date(starts).toString("yyyy");
		row.date = new Date(starts).toString("yyyy/MM/dd");
		weekblock.push(row);

		weekblockcnt++;

		if(weekblockcnt == 13){
			var xrow  = {};
			//quatercnt
			var y =  new Date(weekblock[5].date).toString("yy");
			var ends = new Date(weekblock[12].date).add(6).days().toString("yyyy/MM/dd");
			xrow.quarter = quatercnt+'|'+y;
			xrow.starts = weekblock[0].date;
			//xrow.starts = weekblock[0].date+'T00:00:00Z';
			xrow.ends = ends;
			re.push(xrow);
			weekblockcnt = 0;
			weekblock = [];
			quatercnt++;

			if(quatercnt == 5){
				quatercnt = 1;
			}
		}


		starts = new Date(starts).add({days: 7});
		//.add({ months: 1, days: 5 }).
		//quater

	}

	return re;
}


function buildBroadCastCal2(){
	var re = [];
	var fisrday = Date.january().first();
	var starts = fisrday;
	
	var weekblock = []; 	
	var weekblockcnt = 0;
	var quatercnt = 1;
	var y, ends, xrow;

	if(fisrday.getDay() != 1){
		starts = new Date(fisrday).last().monday();
	}

	var weekcnt = 1;

	for(var i = 0; i < 104; i++) {
		var row  = {};

		row.week = weekcnt++;

		if(weekcnt == 53){
			weekcnt = 1;
		}

		row.year = new Date(starts).toString("yyyy");
		row.date = new Date(starts).toString("yyyy-MM-dd");
		weekblock.push(row);

		weekblockcnt++;

		if(weekblockcnt == 13){
			xrow  = {};
			
			//quatercnt
			y 				= new Date(weekblock[5].date).toString("yy");
			ends 			= new Date(weekblock[12].date).add(6).days().toString("yyyy-MM-dd");
			xrow.quarter 	= quatercnt+'|'+y;
			xrow.starts 	= weekblock[0].date+'T00:00:00Z';
			xrow.ends 		= ends+'T23:59:59Z';
			re.push(xrow);
			weekblockcnt = 0;
			weekblock = [];
			quatercnt++;

			if(quatercnt == 5){
				quatercnt = 1;
			}
		}


		starts = new Date(starts).add({days: 7});

	}

	return re;
}


function fixjsdayofweek(day){
	switch (day){
		case 0:
			return 6;
		case 1:
			return 0;
		case 2:
			return 1;
		case 3:
			return 2;
		case 4:
			return 3;
		case 5:
			return 4;
		case 6:
			return 5;
	 }
}


function fixjsdayofweekEnd(day){
	switch (day){
		case 0:
			return 0;
		case 1:
			return 6;
		case 2:
			return 5;
		case 3:
			return 4;
		case 4:
			return 3;
		case 5:
			return 2;
		case 6:
			return 1;
	 }
}

