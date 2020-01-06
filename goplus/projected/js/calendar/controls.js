
function insertProgramFilterList(t){
    $("#topheaderId").show();
	$("#topheaderId2").show();
	$("#ctlMessage").hide();
	$(".filterprograms").html("<span class='filterlabel'>Filter by </span><select class='s1' id='filterSportType' multiple='multiple' size='2'></select>");
	$('.filterprograms select').append("<option value='All' "+((t =='all') ? 'selected':'')+">Show All</option>")
    .append("<option value='pNew'"+((t =='projected') ? 'selected':'')+">Proj New</option>")
    .append("<option value='premiereprojected' "+((t =='projected') ? 'selected':'')+">Proj Premiere</option>")
    .append("<option value='MoviePremiere' "+((t =='projected') ? 'selected':'')+">Proj Movie Premiere</option>")
	.append("<option value='package' "+((t =='packages') ? 'selected':'')+">Packages</option>")
	.append("<option value='SeriesPremiere' "+((t =='premieres') ? 'selected':'')+">Series Premieres</option>")
	.append("<option value='SeriesFinale' "+((t =='premieres') ? 'selected':'')+">Series Finales</option>")
	.append("<option value='SeasonPremiere' "+((t =='premieres') ? 'selected':'')+">Season Premieres</option>")
	.append("<option value='SeasonFinale' "+((t =='premieres') ? 'selected':'')+">Season Finales</option>");

	$.each(dataRespones['programFilter'], function (i, item) {
		$('#filterSportType').append('<option value="'+item+'"'+ ((t =="live") ? "selected":"")+'>'+item+'</option>');
	});
			
	$('.filterprograms select').append("<option value='Other'>Other Sports Live</option>");
	$("#filterSportType").dropdownchecklist("destroy");
}

function zonenetworks(){
	$("#ctlfilterprogram").html("<span>Nets </span> <select name='nets' class='filter' id='nets'></select>");
	$('#ctlfilterprogram select').append("<option value='0'>---- ALL ----</option>");
	 $.each(dataRespones['zonenetworks'], function (i, item) {
				$('#nets').append($('<option>', { 
					value: item['networkid'],
					text : item ['callsign']
				}));
			}); 
	
}

function zoneFilter(){
	$("#ctlzones").html("<span>Zones </span> <select name='zones' class='filter' id='zones'></select>");
}

function bcmonths(){
	const monthNames = ["January", "February", "March", "April", "May", "June",  "July", "August", "September", "October", "November", "December"];
	tms_data_enddate  = dataRespones['tms_data_enddate'].split('-');
	tmsEndDate        = new Date(tms_data_enddate[0],tms_data_enddate[1],tms_data_enddate[2]);
	$.each(dataRespones['bcmonths'], function (i, item) {
		endDate   = new Date(item['bcyear'],item['bcmonth'],'1');
		thisYear = endDate.getUTCFullYear();
		if (endDate <= tmsEndDate){
			$('#tabcontainer').append("<div class='top'><span id='tab"+item['bcmonth'].toString()+thisYear +"'class='tabNavigator' style='cursor:pointer;'>"+monthNames[item['bcmonth']-1]+"</span></div>");
		}else{
			$('#tabcontainer').append("<div class='topprojected'><span id='tab"+item['bcmonth'].toString()+thisYear+"'class='tabNavigator' style='cursor:pointer;'>"+monthNames[item['bcmonth']-1]+"</span></div>");
		}
	});
}

function showTime(){
	$("#timeSelector").html("Time <select name='sTime' id='sTime' class='filters'></select> to <select name='eTime' id='eTime' class='filters'></select>");
	$.each(dataRespones['hours'], function (i, item) {
		$('#sTime').append("<option value="+item['value']+" "+((item['value']== sTime) ? ' selected':'')+">"+item['hoursDisplay']+"</option>");
		$('#eTime').append("<option value="+item['value']+" "+((item['value']== eTime) ? ' selected':'')+">"+item['hoursDisplay']+"</option>");
	});
}

function loadGrid(){

	var zoneid		= $('#zones').val();
	var monthName 	= ["Jan", "Feb", "Mar", "Apr", "May", "Jun",  "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

	$.each(dataRespones['bcmonths'], function (i, bcMonth) {
		
		_month          = bcMonth['bcmonth'];
		fullBCMonth     = bcMonth['bcmonth']+'-'+ bcMonth['bcyear'];
	
		$("#boxBody").append("<div class='parent' id='dayname_"+fullBCMonth+"'></div>");
	
		$("#dayname_"+fullBCMonth).append("<div id='weekdays"+fullBCMonth+"'style='clear:both;'></div>");
	
		$.each(dataRespones['weekDays'], function (j, dayName) {
			$("#weekdays"+fullBCMonth).append("<div class='dayofWeek'>"+dayName+"</div>");
		});

		$.each(dataRespones['bcweeks'][_month.toString()], function (z, weekNum) { 
    		
            if(weekNum['bcyear'] === bcMonth['bcyear']){
    			_week = ("0"+weekNum['week']).slice(-2);
    			fullBCWeek = _week+'-'+ bcMonth['bcyear'];
    			
    			_date = weekNum['wdate'];
    	
    			$("#dayname_"+fullBCMonth).append("<div class='weekrow' id='weekrow_"+fullBCWeek+"'></div>");
    
    			var sDate  			= _date.split('-');
    			var d 				= new Date(sDate[0],parseInt(sDate[1])-1,sDate[2]);
    			var weekEndDate 	= d;
    			weekEndDate 		= weekEndDate.setDate(weekEndDate.getDate() + 6);
    			
    			for (var dd = new Date(sDate[0],parseInt(sDate[1])-1,sDate[2]); dd <= weekEndDate;dd.setDate(dd.getDate() + 1)){
    				
    				strdd = dd.getFullYear()+'-'+("0"+(parseInt(dd.getMonth())+1)).slice(-2)+'-'+("0"+dd.getDate()).slice(-2);
    				
    				
    				$("#weekrow_"+fullBCWeek).append("<div class='show' id='show_"+dd.getMonth()+"_"+dd.getDate()+"_"+dd.getUTCFullYear()+"'></div>");
    				
    				$("#show_"+dd.getMonth()+"_"+dd.getDate()+"_"+dd.getUTCFullYear()).append("<div class='calendarDay'>"+monthName[dd.getMonth()]+' '+("0"+dd.getDate()).slice(-2)+"</div>");
    				
    				$("#show_"+dd.getMonth()+"_"+dd.getDate()+"_"+dd.getUTCFullYear()).append("<div class='dailyProgramming' id='"+monthName[dd.getMonth()]+("0"+dd.getDate()).slice(-2)+"'></div>");
    
    				if(typeof dataRespones['showByWeek'][0][weekNum['week']] != 'undefined'){
    					
    					if(typeof dataRespones['showByWeek'][0][weekNum['week']][strdd] != 'undefined'){
    						
    						$.each(dataRespones['showByWeek'][0][weekNum['week']][strdd], function (s, item) { 
    							
    							if(item['projected'] === 1 && item['premiereandlive'] === 'Live'){
    								$("#"+monthName[dd.getMonth()]+("0"+dd.getDate()).slice(-2)).append("<div class='calendar-program pLive' id='"+item['program_key']+"' style='display:none;'></div>");
    							}
    							else{
    								$("#"+monthName[dd.getMonth()]+("0"+dd.getDate()).slice(-2)).append("<div class='calendar-program "+item['premiereandlive'].replace(' ','').replace('pNewLive','pLive')+"' id ='"+item['program_key']+"' style='display:none;'></div>");
    							}
    
    							$("#"+item['program_key']).append("<div class='externalprojected'><span class='createdRecord' style='float:right'>"+item['createdat']+"</span><span class='updatedRecord' style='float:right'>"+item['updatedat']+"</span></div>");
    
    							programStime = (tConv24(item['starttime']).replace(':00' ,'')).replace('12A','12M');
    							
    							$("#"+item['program_key']).append("<span class='schedule'><input type='checkbox' class='ssevent' id='"+item['program_key']+"-"+zoneid+"'><span class='starttimeclass'>"+" "+programStime+"</span><span class='tbd'>"+item['duration']+"</span><i>"+" "+item['live']+"</i> </span>");
    							
    							$("#"+item['program_key']).append("<a title='"+item['episode']+"' class='gamedetails programTitle'>"+item['program'].trim()+"</a>");
    							
    							$("#"+item['program_key']).append("<span class='callsign'>"+" "+item['callsign']+"</span>");
    							
    							allgamedetails = "<div class='allgamedetails'><span class='episode'>"+item['episode']+"</span>";
    							
    							if(item['premiereandlive'] !== 'Live'){
    								allgamedetails = allgamedetails + "<div class='showdescription'>"+item['showdesc']+"</span> <span class='premiereflag'> "+item['projected']+' '+item['premiere']+"</div>"
    							}
    							
    							allgamedetails = allgamedetails+"<span class='packageflag'> "+item['packageid']+"</span></div>"
    							$("#"+item['program_key']).append(allgamedetails);
    						});
    					}
    				}
    			}
			} 
		});

    	initialState(sswin.datagridProposal.dataSet());
		
	});
	
	function tConv24(time24) {
		  var ts = time24;
		  var H = +ts.substr(0, 2);
		  var h = (H % 12) || 12;
		  //h = (h < 10)?(h):h;  // leading 0 at the left for 1 digit hours
		  var ampm = H < 12 ? "A" : "P";
		  ts = h + ts.substr(2, 3) + ampm;
	  return ts;
	};
	
	
	//Togle Episode Description (Shows games details individually)
	$('.programTitle').on('click',function(e){
		
		$(this).siblings('.allgamedetails').toggle();

        updateCellHeight();
	});	
	
}

//display the dialog
function displayDialogWindow(position){
	var dialogSettings 			= {};
	dialogSettings.width 		= 320;
	dialogSettings.height 		= 180;
	dialogSettings.resizable 	= false;
	dialogSettings.title 		= 'ShowSeeker Projected Calendar';
	dialogSettings.modal 		= true;
	dialogSettings.draggable 	= true;
	dialogSettings.dialogClass 	= "pepper";
	dialogSettings.open 		= function( event, ui ){$('.ui-dialog :button').blur()};	
	
	if(position){
		dialogSettings.position = position;
	}
	$("button").button();
	$("#dialog-window").dialog(dialogSettings);
	$("#dialog-window").dialog("moveToTop");	
};
