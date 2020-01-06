/* Proposal Clear */
function clearProposal(){
	proposalid 			= 0;
	discountpackage 	= 0;
	discountpackagetype = 0;
	discountagency 		= 0;
	proposalRattingsOn	= 0;
	needSaving 			= false;
	weeksdata 			= [];
		
	$.when(myEzRating.reset()).then(function(){
		var d =[];
		datagridTotals.emptyGrid();
		datagridProposal.emptyGrid();
		datagridProposal.buildEmptyGrid(); 
		datagridSearchResults.buildDemoColumns(d);
		datagridProposalManager.unselectProposal();
		resetEzratingsPopUp();		
		ratingsCtrlButton();
	});

	$('#dialog-ratings').dialog('destroy');
	$(".label-proposal-name").html('No Proposal Loaded');
	$("ezRatingsBtn").prop("disabled",true).button('refresh');
	$("#ratingsProposalName").html('No Proposal Loaded');
	resetDiscounts();
	resetHiddenColumnsCtrl();
}


/* Set the proposal varibles for this page */
var eventrows = [];
var isloading = false;
var cntCloneProposal = 0;
var proposalHolder = [];

var paramcallAfterProposalCreate = null;
var callAfterProposalCreate      = null;
var proposalCount = 0;

/* Proposal List *Updated 10/15/2014 */
function getUserProposals() {
	$.ajax({
		type:'get',
		url: apiUrl+"proposal/list",
		dataType:"json",
		headers:{"Api-Key":apiKey,"User":userid},
		success:function(data){
			var reData = [];
			var zns = {};
			proposalCount = data.length;
			updateProposalCount(proposalCount);
			$.each(data,function(i,row){
				var t        = {};
				t.id         = row.id;
				t.name       = row.name;
				t.total      = row.totals.net;
				t.zone       = [];
				t.zoneMapping= {};
				t.linesttl   = row.totals.line;
				t.spots      = row.totals.spot;
				t.discountid = null;
				t.netttl     = row.totals.gross;
				t.net        = row.totals.net;
				t.amount     = 0;
				t.fstart     = row.startDate;
				t.fend       = row.endDate;
				t.created    = row.createdAt;
				t.updatedat  = row.updatedAt;
				t.istracked  = String(row.isTracked);
				t.ezratings  = row.ratings;

				$.each(row.zones, function(j,zone){
					t.zone.push(zone.name);
					zns 	 = {};
					zns.id 	 = zone.id;
					zns.name = zone.name;
					t.zoneMapping[zone.id] = zns;
				});

				t.zone = t.zone.join(', ');
				reData.push(t);
			});
			populateDownloadList(reData);
			datagridProposalManager.populateDataGrid(reData);
			datagridProposalManager.setSelectRow(proposalid);
			$('#fullwrapper').css('visibility', 'visible');
		}
	});

}



function ezcalendarSynch(proposalid){
	try{
		//if(ezcalendarOpen()){ezcalendar.selectedProposalFromSS(proposalid);}
	} 
	catch(err){}	
}


function ezgridsSynch(proposalid){
	try{
		if(isEzGridsOpen()){
			ezgrids.selectedProposalFromSS(proposalid);
		}
	} 
	catch(err){}	
}


/* MAKE THE SERVER CALL AND POPULATE THE PROPOSAL *Updated 10/15/2014*/
function loadProposalFromServer(id,loc) {	

	if(!id){
		return;
	}

	proposalid = parseInt(String(id).trim());
	$('#panel1,#panel3').css('display', 'none');
	builderpanel['panel3'] = builderpanel['panel1'] = false;

	if(builderpanel['panel2'] == false){
		setPanel('panel2');
	}

	//EZRatings
	proposalRattingsOn = 0;
	myEzRating.reset();
	resetEzratingsPopUp();	
	resetHiddenColumnsCtrl();
	resetSorting();
	resetWeeks();	
	weeksdata = [];	//reset hidden weeks 

	closeAllDialogs();
	loadDialogWindow('loading', 'ShowSeeker Plus', 450, 180, 1);

	$.ajax({
		type:'get',
		url: apiUrl+"proposal/load/"+proposalid,
		dataType:"json",
		headers:{"Api-Key":apiKey,"User":userid},
		success:function(data){
			swapSettingsPanel('search',false);
			datagridTotals.emptyGrid();
			setProposalHiddenCols(data.settings.hiddenColumns,data.settings.globalSetting);

			//FORMATTING PROPOSAL LINES
			var jdata = formatLines(data);
			var rtgsLoadDelay = 1;
						

			//ENABLE EZRATINGS
			var d = [];
			ratingsCtrlButton();
			if(parseInt(data.isRatingsEnabled) > 0){
				//INI TEMP SETTINGS
				var rtgsSettings = populateEzRatingsSettings(data.ratingsSettings);
				myEzRating.setRatings('saved',1);

				//SET PROPOSAL RATINGS SETTINGS
				myEzRating.saveTempParams();
				//setTimeout(function(){
					loadProposalRatingsSettings(myEzRating.get('savedRatingsSettings'));
				//},rtgsLoadDelay);
				d = formatDemos();
			}



			//GRIDS ZONE SYNC
			if(jdata.length > 0){
				if('zoneid' in jdata[0]){
					if(jdata[0].zoneid !== zoneid){
						autoSelectMarketAndZone(jdata[0].zoneid);
						zoneid = jdata[0].zoneid;
						rtgsLoadDelay = 1000;
					}
				}
			}

			discountpackage     	= (data['totals']['gross']==0)?0:parseFloat(data.discount);
			discountpackagetype 	= (data['totals']['gross']==1)?0:parseInt(data.discountType);
			discountagency 			= (data['totals']['gross']==0)?0:parseInt(data.agencyDiscount);
			stdcalendar				= 1;
			discountLabels();

			$(".label-proposal-name,#ratingsProposalName").html(data.name);
			$("#download-proposal-list").val(proposalid).change();

			if($('#broadcast').is(':checked')){
				$('#label-bc-cal').show();
				$('#label-sc-cal').hide();
			}
			else{
				$('#label-sc-cal').show();
				$('#label-bc-cal').hide();				
			}





			datagridSearchResults.buildDemoColumns(d);
			proposalRattingsOn = data.isRatingsEnabled;

			datagridProposal.emptyGrid();
			datagridProposal.buildEmptyGrid();
			datagridTotals.emptyGrid();

			if(jdata.length == 0){
				resetTotals();
			}

			$.when(datagridProposal.populateProposalDataGrid(jdata)).then(function(){
				datagridTotals.populateDataGridGoPlus(jdata,data.startDate,data.endDate,data.totals);
				}
			);

			flightLabel();
			
			needSaving = false;

			$("#dialog-window").dialog("close");

			if(loc != 'download'){
				menuSelect('proposal-build');
			}
			else if(loc == 'save'){
				needSaving = true;
				proposalSaveChanges(true);
			}


			//EZGRIDS SYNC
			if(isEzgridsOpen()){
				var ezgridsProposalId = $(ezgrids.document).contents().find('#proposalList').val();
				
				if(parseInt(proposalid) !== parseInt(ezgridsProposalId)){
					$(ezgrids.document).find('#proposalList').val(proposalid);
					ezgrids.resetCells();
					ezgrids.loadedProposalId = proposalid;
				}
				if(jdata.length > 0){ //HIGHLIGHTING ADDED SHOWS
					ezgrids.initialState(jdata);
				}

				ezgrids.rtgsStatus();


					
				setTimeout(function(){
					//var stationId = datagridNetworks.getNetworkById(ezgrids.station);
					var stationId = $(ezgrids.document).contents().find('#station option');
					//console.log(stationId.id,ezgrids.tmpStation);
					var inList = false;
					for(var s=0;s<stationId.length;s++){
						if(parseInt(stationId[s].value) === parseInt(ezgrids.tmpStation)){
							ezgrids.station = ezgrids.tmpStation;
							inList = true;
							break;
						}
					}
					
					if(inList){
						$(ezgrids.document).contents().find('#station').val(ezgrids.station);
						ezgrids.updategrid();
					}
					else{
						ezgrids.alertMsg('Please select a network and click Update Grid.');
					}
				}, 500);				
			}

			reset();
		}
	});
};



function loadProposalFromServerSilent(id,loc) {	
	if(id == null){
		return;
	}	
	
	proposalid = parseInt(String(id).trim());

	$.ajax({
		type:'get',
		url: apiUrl+"proposal/load/"+proposalid,
		dataType:"json",
		headers:{"Api-Key":apiKey,"User":userid},
		success:function(data){
			datagridTotals.emptyGrid();
			setProposalHiddenCols(data.settings.hiddenColumns,data.settings.globalSetting);

			//FORMATTING PROPOSAL LINES
			var jdata = formatLines(data);

			var dataweeks 	    = []; //TODO_ASIF
			discountpackage     = (data['totals']['gross']==0)?0:parseFloat(data.discount);
			discountpackagetype = (data['totals']['gross']==1)?0:parseInt(data.discountType);
			discountagency 	    = (data['totals']['gross']==0)?0:parseInt(data.agencyDiscount);
			weeksdata 		    = dataweeks;
			
			discountLabels();

			$(".label-proposal-name,#ratingsProposalName").html(data.name);
			$("#download-proposal-list").val(proposalid).change();

			stdcalendar	= 1;

			if($('#broadcast').is(':checked')){
				$('#label-bc-cal').show();
				toggleTotalsView(true,'bc');
			}
			else{
				$('#label-sc-cal').show();
				toggleTotalsView(true,'std')					
			}

			if(isEzgridsOpen()){ //EZGRIDS SYNCHING
				var ezgridsProposalId = $(ezgrids.document).contents().find('#proposalList').val();
				if(parseInt(proposalid) !== parseInt(ezgridsProposalId)){
					$(ezgrids.document).find('#proposalList').val(proposalid).change();
					ezgrids.resetCells();
					ezgrids.loadedProposalId = proposalid;
				}
			}

			if(jdata.length == 0){

				datagridProposal.emptyGrid();
				datagridProposal.buildEmptyGrid();
				datagridTotals.emptyGrid();
				resetTotals();

				$("#dialog-window").dialog("close");

				if(loc != 'download'){
					menuSelect('proposal-build');
				}

				if(loc == 'save'){
					needSaving = true;
					proposalSaveChanges(true);
				}

				return;
			}

			datagridProposal.emptyGrid();
			
			var dataset = datagridProposal.setDataSet(jdata);
			datagridProposal.buildSimpleGrid(jdata);

			var wdata = datagridProposal.filteredDataSet();
			datagridProposal.populateDataGridNew(wdata);

			datagridTotals.populateDataGrid(jdata);


			$("#dialog-window").dialog("close");
		}
	});
}




/* Proposal Download List */
function populateDownloadList(data){
	$('#download-proposal-list').empty();
	var pslId = parseInt(proposalid);
	for(var i = 0; i < data.length; i++) {
		var x  = Date.parse(data[i].created).toString("MM/dd/yyyy");
		if(pslId != parseInt(data[i].id)){
			$('#download-proposal-list').append($("<option></option>").attr("value", data[i].id).text(data[i].name + ' - ' + x));
		}
		else{
			$('#download-proposal-list').append($("<option selected=selected></option>").attr("value", data[i].id).text(data[i].name + ' - ' + x));
		}
	}
	$('#download-proposal-list').trigger('create');
};




/* Proposal Save the Clone  *Updated 10/15/2014 */
function proposalCloneProcessLines(rows){
	var flight = $("#flight2").attr("checked");
	var params = solrSearchParamaters();
	var myrows = [];
	$.each(rows, function(i, row){
		if(row.linetype == 'Fixed'){
			var temp = proposalCloneProcessLineFixed(row,params.startdate,params.enddate);
			if(temp){
				myrows.push(temp);
			}
		}
		if(row.linetype == 'Rotator'){
			var temp = proposalCloneProcessLineRotator(row,params.startdate,params.enddate);
			myrows.push(temp);
		}
		if(row.linetype == 'Line'){
			var temp = proposalCloneProcessLineLine(row,params.startdate,params.enddate);
			myrows.push(temp);
		}
	});
	return myrows;
};




/* Proposal Clone Processed Lines */
function proposalCloneProcessLineFixed(row,start,end){	
	var rowstart = new Date(row.startdate).toString("yyyyMMdd");
	var flightstart = new Date(start).toString("yyyyMMdd");

	if(flightstart > rowstart){
		return false;
	}
	return row;
}




/* Proposal Clone Rotator Lines */
function proposalCloneProcessLineRotator(xrow,start,end){	
	
	//break out the time from the dates
	var startdate = Date.parse(start).toString("yyyy/MM/dd");
	var enddate = Date.parse(end).toString("yyyy/MM/dd");

	var starttime = Date.parse(xrow.starttime).toString("hh:mm tt");
	var endtime = Date.parse(xrow.endtime).toString("hh:mm tt");

	//parse the dates as the new ones
	var startdateStr = startdate+' '+starttime;
	var enddateStr = enddate+' '+endtime;

	var formatStartDateTime = new Date(startdateStr).toString("yyyy/MM/dd HH:mm");
	var formatEndDateTime = new Date(enddateStr).toString("yyyy/MM/dd HH:mm");


	var weeks = buildBroadcastWeeks(start,end);
	var weeksOld = buildBroadcastWeeks(xrow.startdate,xrow.enddate);
	//var weekcnt = parseInt(weeks.length);
	var weekcnt = 0;
	var spotcount = 0;


	//key for line
	var uuid = GUID();

	var row = {};
	row.id = uuid + "-" + xrow.zoneid;
	row.ssid = uuid;
	row.zone = xrow.zone;
	row.zoneid =  xrow.zoneid;
	row.linetype = 'Rotator';
	row.title = xrow.title;
	row.callsign = xrow.callsign;
	row.stationnum = xrow.stationnum;
	row.stationname = xrow.stationname;
    row.startdate = startdate;
    row.enddate = enddate;
    row.starttime = starttime;
    row.endtime = endtime;
	row.startdatetime = formatStartDateTime;
	row.enddatetime = formatEndDateTime;
	row.day = xrow.day;
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

   	//scheduler features
   	row.locked = false;
   	row.ratecardid = 0;
   	row.rate = xrow.rate;
   	row.ratevalue = xrow.ratevalue;
   	row.ratename = '';
   	row.weeks = weekcnt;
   	row.weekdays = 0;
   	row.ncc = '';
   	row.avail = '';
   	row.broadcastweek = '';
   	row.timestamp = new Date();
   	row.split = 0;

	row.titleFormat = xrow.titleFormat;
	row.titlenetworkFormat = xrow.titlenetworkFormat;
	row.callsignFormat = xrow.callsignFormat;
	row.statusFormat = xrow.statusFormat;
	row.statusOrder = xrow.statusOrder;
	row.sortFormat =  xrow.sortFormat;
	row.dayFormat = xrow.dayFormat;




	for (var i = 0; i < weeks.length; i++){

		var z =  'w'+weeks[i].column;

		if (typeof weeksOld[i] != 'undefined') {
			var oldcol = 'w'+weeksOld[i].column;
			var oldspots = parseInt(xrow[oldcol]);
			spotcount+=oldspots;
			row[z] = oldspots;
			weekcnt++;
		}else{
			row[z] = 1;
			spotcount++;
		}

	}


	var spweek = parseInt(spotcount/weekcnt);
	var totalspots = spotcount;
	row.spotsweek = spweek;
	row.spots = parseFloat(totalspots);
	row.total = parseFloat(totalspots)*parseFloat(xrow.rate);
	return row;
}




/* Proposal Clone Fixed Lines */
function proposalCloneProcessLineLine(xrow,start,end){	
	
	//break out the time from the dates
	var startdate = Date.parse(start).toString("yyyy/MM/dd");
	var enddate = Date.parse(end).toString("yyyy/MM/dd");

	var starttime = Date.parse(xrow.starttime).toString("hh:mm tt");
	var endtime = Date.parse(xrow.endtime).toString("hh:mm tt");

	//parse the dates as the new ones
	var startdateStr = startdate+' '+starttime;
	var enddateStr = enddate+' '+endtime;

	var formatStartDateTime = new Date(startdateStr).toString("yyyy/MM/dd HH:mm");
	var formatEndDateTime = new Date(enddateStr).toString("yyyy/MM/dd HH:mm");


	var weeks = buildBroadcastWeeks(start,end);
	var weekcnt = parseInt(weeks.length);
	var spotcount = 0;



	//get start and end weeks and subtract 7 to get how many days of the week are left. Mon = 7 Sun = 1
	var startweekdaycount = 7- fixjsdayofweek(new Date(xrow.startdate).getDay());
	var endweekdaycount = 7- fixjsdayofweekEnd(new Date(xrow.enddate).getDay());

	//get the spots to be scheduled over the weeks and divide by the total weeks to get an avg for all the weeks
	var totalspots = parseInt(xrow.spots);
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

	//key for line
	var uuid = GUID();

	var row = {};
	row.id = uuid + "-" + xrow.zoneid;
	row.ssid = uuid;
	row.zone = xrow.zone;
	row.zoneid =  xrow.zoneid;
	row.linetype = 'Rotator';
	row.title = xrow.title;
	row.callsign = xrow.callsign;
	row.stationnum = xrow.stationnum;
	row.stationname = xrow.stationname;
    row.startdate = startdate;
    row.enddate = enddate;
    row.starttime = starttime;
    row.endtime = endtime;
	row.startdatetime = formatStartDateTime;
	row.enddatetime = formatEndDateTime;
	row.day = xrow.day;
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

   	//scheduler features
   	row.locked = false;
   	row.ratecardid = 0;
   	row.rate = xrow.rate;
   	row.ratevalue = xrow.ratevalue;
   	row.ratename = '';
   	row.weeks = weekcnt;
   	row.weekdays = 0;
   	
   	row.ncc = '';
   	row.avail = '';
   	row.broadcastweek = '';
   	row.timestamp = new Date();
   	
   	row.split = 0;

	row.titleFormat = xrow.titleFormat;
	row.titlenetworkFormat = xrow.titlenetworkFormat;
	row.callsignFormat = xrow.callsignFormat;
	row.statusFormat = xrow.statusFormat;
	row.statusOrder = xrow.statusOrder;
	row.sortFormat =  xrow.sortFormat;
	row.dayFormat = xrow.dayFormat;
	var z;
	
	for (var i = 0; i < weeks.length; i++){
		z =  'w'+weeks[i].column;
		row[z] = parseInt(weeks[i].count);
		spotcount+=weeks[i].count;
	}
	//var spweek = parseInt(spotcount/weekcnt);
	//var totalspots = spotcount;
	row.spotsweek = parseInt(spotcount/weekcnt);
	row.spots = parseInt(spotcount);
	row.total = parseInt(spotcount)*parseInt(xrow.rate);

	return row;
}



function proposalCopyChecked2(){
	var zones   	= $("#clone-zones").attr("checked");
	var flight  	= $("#flight2").attr("checked");
	var newname 	= $("#rename-proposal2").val();
	var copySpots 	= 0;

	if(!$("#clone-spots-0").is(':checked')){
		copySpots = 1;
	}
	
	if(!isBlank(String(newname).trim())){
		loadDialogWindow('blankproposalname', 'ShowSeeker Plus', 450, 180, 1, 0);
		$("#proposal-name").val("");
		return;
	}

	if( String(newname).length > 75 ){
		loadDialogWindow('longproposalname', 'ShowSeeker Plus', 450, 180, 1, 0);
		return;
	}	
	
	var eventrows   	= datagridProposalManager.getSelectedRows(); //get teh selected id
	
	
	if(eventrows[0].linesttl === 0){
		loadDialogWindow('emptyproposalcopy', 'ShowSeeker Plus', 450, 180, 1, 0);
		return;		
	}
	
	var data    		= {};
	data.proposalId 	= eventrows[0].id;
	data.proposalName	= newname;
	
	if(flight === 'checked'){
		var params     = solrSearchParamaters();
		data.startDate = params.startdate.split("T")[0];
		data.endDate   = params.enddate.split("T")[0];		
	} 
	else {
		data.startDate = '';
		data.endDate   = '';		
	}

	data.copyRatings = 0;
	//transfer ratings only in the same dma
	if(roles.ezRatings){
		var zns = datagridProposal.getZoneList();
		
		if($.isEmptyObject(zns)){		
			zns = datagridProposalManager.getSelectedRows()[0].zoneMapping;
		}

		for(var key in zns){
			if(key in zonesArray){

				if(zonesArray[key].dmaId === zonesArray[zoneid].dmaId){
					data.copyRatings = 1;
					break;	
				}
			}
		}
	}


	if(zones == 'checked'){
		data.zones = $("#clone-zone-selector").val();;	
	}

	data.copySpots = copySpots;

	
	$.ajax({
        type:'post',
        url: apiUrl+"proposal/copy",
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        processData: false,
        contentType: 'application/json',
        data: JSON.stringify(data),
        success:function(resp){
			//RESET PROPOSAL FINDER FILTER
            //loadProposalFromServer(resp.proposalId,"");
			//getUserProposals();
			clearProposalFilter();
			datagridProposalManager.addNewProposalData(resp,newname);
			updateProposalsInDownloads(resp.proposalId,newname);
			closeAllDialogs();
        }
    });

}




/* Proposal Create Event */
function proposalCreateNewEvent(location){
	var name = String($('#proposal-save-name-input').val()).trim();
	
	if(name.length == 0)
		return

	if(name.length > 75){
		if($('#temp-psl-msg').length == 0){
			$('#saveerror').append('<center id=temp-psl-msg><BR><b>Please limit the proposal name to 75 characters or less.</b></center>');
			setTimeout(function(){$('#temp-psl-msg').empty().remove()},3500);
		}
		return;
	}
	proposalCreateNew(name,true,location);
}



/* Create Proposal  *Updated 10/15/2014 */
function proposalCreateNew(name,isnew,location){
	
	var createnewpopup = $('#dialog-window');

	if(createnewpopup.length > 0){
		$('#proposal-save-name,#discard-save-btn').hide();
		$('#dialog-window #div-center').append('<center><img src="i/ajax.gif"></center>');
	}

	if(!isBlank(String(name).trim())){
		loadDialogWindow('blankproposalname', 'ShowSeeker Plus', 450, 180, 1, 0);
		$("#proposal-name").val("");
		return;
	}

	if( String(name).length > 75 ){
		loadDialogWindow('longproposalname', 'ShowSeeker Plus', 450, 180, 1, 0);
		return;
	}

	$("#proposal-name").val("");
	weeksdata = [];	//reset hidden weeks holder
	flightLabel();
	resetHiddenColumnsCtrl();
	
	var d = {};
	d.proposalName = name;
	d.location 		= 1;
	d.ratingsSettings={};	
	
	if(proposalid === 0){
		d.ratingsSettings=setProposalRatings();		
	}

	$.ajax({
		type:'post',
		url: apiUrl+"proposal/create",
		dataType:"json",
		headers:{"Api-Key":apiKey,"User":userid},
		processData: false,
		contentType: 'application/json',
		data: JSON.stringify(d),
		success:function(resp){
			
			if(!resp.isPassSecure){
				loginMessage('NonSecurePwd');
				return false;
			}
			if(resp.requireLogout){
				loginMessage('EmailUpdate');
				return false;
			}		

			mixTrack("Proposal - Create",{	"proposalName":name,
											"proposalId":resp.id,
											"proposalLocation":location
										});
			closeAllDialogs();

			//RESET PROPOSAL FINDER FILTER
			clearProposalFilter();				
			
			if(callAfterProposalCreate !== null){
				loadProposalFromServerSilent(resp.id,"");
				callAfterProposalCreate(paramcallAfterProposalCreate);
				callAfterProposalCreate      = null;
				paramcallAfterProposalCreate = null;
				
			}else{
				
				if(proposalid > 0){
					var clearPrevious = clearProposal();
				}			
				proposalInitialState(resp,name);
			}
			
			proposalid = parseInt(String(resp.id).trim());
			//add new row
			//getUserProposals();
			datagridProposalManager.addNewProposal(resp.id,name);
			updateProposalsInDownloads(resp.id,name);
			$("#dialog-window").dialog("close");


			if(isEzgridsOpen()){
				
				var ua = window.navigator.userAgent;
				var msie = ua.indexOf("MSIE ");
				var msedge = ua.indexOf("Edge");
				
				if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)){  // If Internet Explorer, return version number
					var option 		= ezgrids.document.createElement("option");
					option.text 	= String(name).trim();
					option.value 	= resp.id;
					var select 		= ezgrids.document.getElementById("proposalList");
					select.options.insertBefore(option, select.options[1]);
					$(ezgrids.document).contents().find('#proposalList').val(resp.id).change();
				}
				else if(msedge > 0){
					$(ezgrids.document).contents().find('#proposalList option:first').after('<option  selected="selected" value="'+resp.id+'">'+String(name).trim()+'</option>');
				}
				else{// If another browser, return 0
					$(ezgrids.document).contents().find('#proposalList option:first').after($("<option selected='selected'></option>").attr("value", resp.id).text(String(name).trim()));
				}	
				ezgrids.resetCells();
				ezgrids.loadedProposalId = resp.id;
				ezgrids.ratingsOn = false;
			}
		}
	});

}


/* Proposal Delete *Updated 10/16/2014 */
function proposalDeleteChecked(){
	var proposalIds = [];
	eventrows       = datagridProposalManager.getSelectedRows();

	$.each(eventrows, function(index, value) {
		proposalIds.push(value.id);
		if(proposalid==value.id){
			clearProposal();
		}
	});


	$.ajax({
        type:'post',
        url: apiUrl+"proposal/delete",
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        processData: false,
        contentType: 'application/json',
        data: JSON.stringify({"proposalIds":proposalIds}),
        success:function(resp){

			//RESET PROPOSAL FINDER FILTER
			clearProposalFilter();
         datagridProposalManager.deleteProposalRows(proposalIds);
         removeProposalsInDownloads(proposalIds);
        }
    });

}



/* Proposal Delete Confermation */
function proposalDeleteCheckedConfirmation(){
	var rows = [];
	eventrows = datagridProposalManager.getSelectedRows();

	if(eventrows.length == 0) {
		loadDialogWindow('leastone', 'ShowSeeker Plus', 450, 180, 1);
		return;
	}	
	loadDialogWindow('proposaldelete', 'ShowSeeker Plus', 450, 180, 1);
}



/* Proposal Merged Checked *Updated 10/16/2014 */
function proposalMergeChecked(){
	var eventrows 		= datagridProposalManager.getSelectedRows();

	if(eventrows.length < 2){
		loadDialogWindow('mergeamount', 'ShowSeeker Plus', 450, 180, 1);
		return;
	}
	
	var name = $.trim($("#proposal-merge-name").val());	

	if(!isBlank(name)){
		loadDialogWindow('blankproposalname', 'ShowSeeker Plus', 450, 180, 1, 0);
		$("#proposal-name").val("");
		return;
	}

	if( String(name).length > 75 ){
		loadDialogWindow('longproposalname', 'ShowSeeker Plus', 450, 180, 1, 0);
		return;
	}
	
	var data 			= {};
	data.proposalName 	= $('#proposal-merge-name').val()
	data.proposalIds 	= [];	


	$.each(eventrows, function(index, value) {
		data.proposalIds.push(value.id);
	});

	$.ajax({
        type:'post',
        url: apiUrl+"proposal/merge",
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        processData: false,
        contentType: 'application/json',
        data: JSON.stringify(data),
        success:function(resp){
			
			clearProposalFilter();
			datagridProposalManager.addNewProposalData(resp,data.proposalName);
			updateProposalsInDownloads(resp.proposalId,data.proposalName);
			closeAllDialogs();			
			
			
        }
    });

}



/* Proposal Rename Event Checked */
function proposalRenameChecked() {
	var eventrows = datagridProposalManager.getSelectedRows();
	
	if(eventrows.length === 0){
		loadDialogWindow('leastone', 'ShowSeeker Plus', 450, 180, 1);
		return;
	}
	
	if(eventrows.length > 1) {
		loadDialogWindow('onlyOneProposal', 'ShowSeeker Plus', 450, 180, 1);
		return;
	}
	
	$("#proposal-rename").val(eventrows[0].name);
	loadDialogWindow('proposal-rename', 'Rename Proposal', 380, 150, 0);
}



/* Save proposal to server *Updated 10/15/2014 */
function proposalSaveChanges(locked) {}


/* Save proposal to server *Updated 10/15/2014 */
function proposalQuickSave() {}



/* Proposal Share 10/20/2014*/
function proposalShare(type){
	closeAllDialogs();
	proposalShareType = type;

	var rows 	= [];
	eventrows 	= datagridProposalManager.getSelectedRows();

	if(eventrows.length === 0){
		loadDialogWindow('leastone', 'ShowSeeker Plus', 450, 180, 1);
		return;
	}
	loadDialogWindow('share', 'Share Proposals', 700, 600, 2, true,'','',[100, 15]);
}

function trackProposal(){
	var trackingUrl,psl;
	psl = JSON.stringify({"proposalId": trackProposalRow.id});
	
	if(String(trackProposalRow.istracked) === '1'){
		trackProposalRow.istracked = '0';
		trackingUrl = 'https://plusapi.showseeker.com/proposal/untrack';		
	}
	else{
		trackProposalRow.istracked = '1';
		trackingUrl = 'https://plusapi.showseeker.com/proposal/track';
	}
	datagridProposalManager.updateProposalTrack();	
		
	$.ajax({
        type:'post',
        url: trackingUrl,
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        processData: false,
        contentType: 'application/json',
        data: psl,
        success:function(resp){
	        
        }
    });
    
	return;
}



/* Proposal Rename  *Updated 10/15/2014 */
function renameCheckedProposalEvent(){
	var name = $.trim($("#proposal-rename").val());

	if(!isBlank(name)){
		loadDialogWindow('blankproposalname', 'ShowSeeker Plus', 450, 180, 1, 0);
		$("#proposal-name").val("");
		return;
	}

	if( String(name).length > 75 ){
		loadDialogWindow('longproposalname', 'ShowSeeker Plus', 450, 180, 1, 0);
		return;
	}

	$('#label-proposal-name,#ratingsProposalName').text(name);
	
	var selectedIndexes = datagridProposalManager.getSelectedRows();	

	$.ajax({
        type:'post',
        url: apiUrl+"proposal/rename/"+selectedIndexes[0].id,
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        processData: false,
        contentType: 'application/json',
        data: JSON.stringify({"name":$.trim($("#proposal-rename").val())}),
        success:function(resp){
            //getUserProposals();
            datagridProposalManager.updateRow(selectedIndexes[0].id,'name',name);
            $("#dialog-window").dialog("destroy");
        }
    });
};


function findRatings(totals){
	var r = false;
	if(totals.ratingsTotals.length > 0){
		r = true;
	}
	return r;
};


function proposalInitialState(pslData,name){
	proposalid 				= parseInt(String(pslData.id).trim());
	$('#panel1,#panel3').css('display', 'none');	
	builderpanel['panel3'] 	= builderpanel['panel1'] = false;

	if(builderpanel['panel2'] == false){
		setPanel('panel2');
	}
	$('#label-proposal-name,#ratingsProposalName').text(name);
	setProposalHiddenCols(pslData.settings.hiddenColumns,pslData.settings.globalSetting);
	datagridProposal.emptyGrid();
	datagridProposal.buildEmptyGrid();
	datagridTotals.emptyGrid();
	resetTotals();
	$("#dialog-window").dialog("close");

	menuSelect('proposal-build');
	ezgridsSynch(pslData.id);
	ratingsCtrlButton();
	displayColumns();
	return false;	
};

function updateProposalsInDownloads(id,name){
	var today 	= getTimeStamp();
	var todayIs	= today[1]+'/'+today[2]+'/'+today[0];
	var pslName = name +' - '+todayIs;
	$('#download-proposal-list').prepend($('<option/>',{value: id,text : pslName}));
};
function removeProposalsInDownloads(ids){
	$('#download-proposal-list > option').each(function(){
		for(var i=0; i<ids.length;i++){
			if(parseInt(this.value) === parseInt(ids[i])){
				$(this).remove();
				break;
			}
		}
	});
}