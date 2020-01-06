var newSpotsByDay 			= 0;	
var spotsByDayOfWeek		= {}
var weekDaysObj 			= {};	

weekDaysObj.spotsMonday 	= 2;
weekDaysObj.spotsTuesday 	= 3;
weekDaysObj.spotsWednesday	= 4;
weekDaysObj.spotsThursday 	= 5;
weekDaysObj.spotsFriday 	= 6;
weekDaysObj.spotsSaturday 	= 7;
weekDaysObj.spotsSunday 	= 1;


$('#weekly').on('change',function(){
	if($(this).is(':checked')){
		$('#spotByDayButton').hide();
		$('#sidebar-row-weeks').css('background-color', '#f1f1f1');
	}
});

$('#daily').on('change',function(){
	if($(this).is(':checked')){
		$('#spotByDayButton').show();
		$('#sidebar-row-weeks').css('background-color', '#f1f1f1');
		$('#yes').prop('checked',false);
	}
});

$('#yes').on('change',function(){
	if($(this).is(':checked')){
		//$('#sidebar-row-weeks').css('background-color', '#fe7272');
		$('#spotByDayButton').hide();
	}
	else{
		$('#sidebar-row-weeks').css('background-color', '#f1f1f1');
	}
});

$('#createLineByDayBtn').live('click',function(){
	manualSpotAllocation = true;
	proposalAddRotator();
	needSaving=true;
});

$(".spotsbyweek,.spotsbyweekinline").live("keypress",function(event){
	return isValidNumberOnKeyPress(event,this.value);
});

$('div#dialog-spots-by-day').on('dialogclose', function(event) {
    closeSpotsBydayModal();
 });


//UPDATING SPOTS IN LINE
$(".spotsbyweekinline").live("input",function(evt){
	
	var sptPerWeek = 0;
	
	$(".spotsbyweekinline").each(function(i,spt){
		sptPerWeek += parseInt(spt.value);
		spotsByDayOfWeek[$(this).prop('id')] = parseInt(spt.value);
	});
});

$(".spotsbyweekinline").live('focusout',function(){
    if(!$(this).val() || isNaN($(this).val())){
		$(this).val(0);
    }
});


//UPDATE SPOTS FROM THE SIDE PANEL	
$(".spotsbyweek").live("input",function(evt){
	
	if(!isValidNumberOnKeyUpAlt(evt,$(this).prop('id'))){
		$(this).addClass('redBorder');
		$(this).val(0);
		var entryId = $(this).prop('id');
		var myVar = setTimeout(function(){$('#'+entryId).removeClass('redBorder');clearTimeout(myVar);}, 3000);
		return;
	}

	if(!$(this).val() || isNaN($(this).val())){
		$(this).val(0);
	}
	
	var sptPerWeek = 0;
	
	$(".spotsbyweek").each(function(i,spt){
		sptPerWeek += parseInt(spt.value);
		spotsByDayOfWeek[$(this).prop('id')] = parseInt(spt.value);
	});

	editRotatorItems.spotsByday = 1;		
	
	//AFTER CHANGING THE SPOTS BY DAY IN THE ROTATOR
	if(!isNaN(sptPerWeek)){
		$("#schedule-spots").val(sptPerWeek);
		if (editRotator) {
	        editRotatorItems['spots'] = 1;
	        $('#sidebar-row-spots').css('background-color', '#c8ffc9');        
			$('#createLineByDayBtn').hide();
			$('#updateLineByDayBtn').show();
	    }
	}
});


//VERIFYING THE SPOTS ARE VALID FROM THE SIDE PANEL
$("#schedule-spots").on('keyup',function(){
	$.when(closeInlineEditPopup()).then(function(){
		if($('#daysOfWeekWrapper').is(':visible') && $(this).val()){
			if(!isNaN($(this).val()) && parseInt($(this).val()) > 0){
				allocateSpotsByDay();
			}
		}		
	});
	return false;
});

function closeInlineEditPopup(){
	if($("#dialog-spots-by-day").dialog( "isOpen" )===true){
		$("#dialog-spots-by-day").dialog( "destroy" );
		spotsByDayOfWeek = {};
	}	
	return true;
}


//SAVING THE NEW SPOTS VALUES TO THE DB
$('#saveSpotsByWeek').live('click',function(){

	var totalInWeek 		= 0;
	var apiData 			= {};
	var updatedSpots		= {};
		
	$('.spotsbyweekinline').each(function(i,val){
		totalInWeek = totalInWeek + parseInt($(this).val());
		updatedSpots[weekDaysObj[$(this).prop('id')]]	= parseInt($(this).val());
	});

	//UPDATING ROW VALUES
    var rows 				= datagridProposal.selectedRows();
	rows[0][dData.week] 	= String(totalInWeek);
    var stats 				= datagridProposal.calculateTotalsFromLine(rows[0]);
	rows[0].enddate 		= stats.enddate;
	rows[0].enddatetime 	= stats.enddatetime;
	rows[0].startdate 		= stats.startdate;
	rows[0].startdatetime	= stats.startdatetime;
	rows[0].total 			= stats.total;
	rows[0].spotsweek 		= stats.spotsweek;
	rows[0].spots 			= parseInt(stats.spots);
	rows[0].weeks 			= parseInt(stats.weeks);
	rows[0][dData.week] 	= String(totalInWeek);
	rows[0][dData.week.replace('w','s')] 	= updatedSpots;		
	
	
	datagridProposal.populateDataGridRender();
	datagridTotals.populateDataGridFromData();
			

	//UPDATING RECORDS IN DB
	apiData.spots 		= {};
	apiData.lineId 		= rows[0].id;
	apiData.weekId 		= dData.row.weekIdMapping[dData.week];
	apiData.week 		= dData.week.substr(5, 4)+'-'+dData.week.substr(1, 2)+'-'+dData.week.substr(3, 2);

	$(".spotsbyweekinline").each(function(i,spt){
		apiData.spots[weekDaysObj[$(this).prop('id')]] 	= parseInt($(this).val());
	});
	
    $.ajax({
        type:'post',
        url: apiUrl+"proposal/linebyday/editspots",
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        processData: false,
        contentType: 'application/json',
        data: JSON.stringify(apiData),
        success:function(resp){
            datagridProposal.updateRatingTotals(resp);
			spotsByDayOfWeek = {};//RESETS SPOTS BY DAY
            if(!resp){
				loadDialogWindow('editSpotbyDay','Updating Spots',400,200,1);
            }
        }
    });
	
	closeAllDialogs();
});	



$('#lineTypeInfo').on('click',function(e){
	dialogLineTypeInfo();
})


//ENABLING SELECTED ACTIVE DAYS OF THE WEEK
function allocateSpotsByDay(days){
	var tmpDays 	= [];
	var sDays;

	if(days){
		sDays = days;
	}
	else{
		sDays = arrayDays;
	}
	
	for(var i in weekDaysObj){
		$('#'+i+'.spotsbyweek').prop("disabled", false).removeClass('redBackground');
		$('#'+i+'.spotsbyweek').val(0);			
	}

	// CAST TO INTEGER 
	for(var x in sDays){
		if(!isNaN(parseInt(sDays[x]))){
			tmpDays.push(parseInt(sDays[x]));
		}
	}
	
	for(var i in weekDaysObj){
		if(tmpDays.indexOf(weekDaysObj[i]) === -1){
			$('#'+i+'.spotsbyweek').prop("disabled", true).addClass('redBackground');
		}
	}
	
	//DROPPING SPOTS IN EACH ACTIVE DAY
	var c 		= 0;
	var spt 	= $("#schedule-spots").val();
	var tmpval 	= 0;

	if(spt && !isNaN(spt)){
		spt = parseInt(spt);
	}
	else{
		spt = 0;
	}

	while(c < spt){
		$.each(weekDaysObj,function(i,val){
			
			
			if(c < spt && $('input#'+i+'.spotsbyweek').is(':enabled')){
				
				tmpval = $('input#'+i+'.spotsbyweek').val();
				
				if(isNaN(tmpval) || tmpval === ''){
					tmpval = 1;
				}
				else{
					tmpval = parseInt(tmpval)+1;
				}
								
				$('input#'+i+'.spotsbyweek').val(tmpval);
				c++;
			}
		});
	};
	
	$('#updateLineByDayBtn').hide();
	$('#createLineByDayBtn').show();
	
	var rw = datagridProposal.selectedRowsData();
	if(rw.length === 1){
		$('#createLineByDayBtn').hide();
		$('#updateLineByDayBtn').show();
	}

	return true;
}; 



function allocatingSpots(spots){
	var c 		= 0;
	var r 		= false;
	var tmpDays = [];
	var spt;
	
	if(spots){
		spt = spots;
	}
	else{
		spt = $("#schedule-spots").val();
	}
	
	// CAST TO INTEGER ALL DAYS OF WEEK
	for(var x in arrayDays){
		if(!isNaN(parseInt(arrayDays[x]))){
			tmpDays.push(parseInt(arrayDays[x]));
		}
	}
	
	$.each(weekDaysObj,function(i,val){
		spotsByDayOfWeek[i] = 0;
	});
	
	if(spt && !isNaN(spt)){
		
		while(c < spt){
	
			$.each(weekDaysObj,function(i,val){
	
				if(c < spt && tmpDays.indexOf(weekDaysObj[i]) !== -1){
					
					if(spotsByDayOfWeek[i]){
						spotsByDayOfWeek[i] = spotsByDayOfWeek[i]+1;
					}
					else{
						spotsByDayOfWeek[i] = 1;
					}
					c++;
				}
			});
	
		};
		r = spotsByDayOfWeek;	
	}
	return r;
};


function closeSpotsBydayModal(){
	closeAllDialogs();
	datagridProposal.populateDataGridRender();
}


function editSpotsInWeek(w,row){

	var nDay,tmpval;
	var tmpDays 	= [];
	var ii			= 0;
	var c 			= 0;
	var thisWk  	= w;
	var weekTotal 	= row[w];
	var sDateArr  	= row.startdatetime.split(/[^0-9]/);
	var eDateArr  	= row.enddatetime.split(/[^0-9]/);
	sD 				= new Date(parseInt(sDateArr[0]), parseInt(sDateArr[1])-1, parseInt(sDateArr[2])).getTime();
	eD 				= new Date(parseInt(eDateArr[0]), parseInt(eDateArr[1])-1, parseInt(eDateArr[2])).getTime();
		
	//CAST TO INTEGERS DAYS OF THE WEEK
	for(var x=0; x < row.day.length; x++){
		tmpDays.push(parseInt(row.day[x]));
	}
	
	//RESET FIELDS
	for(var i in weekDaysObj){
		$('#'+i+'.spotsbyweekinline').prop("disabled", false).removeClass('redBackground');
		$('#'+i+'.spotsbyweekinline').val(0);			
	}

	
	//DISABLING DAYS OUT OF THE RANGE
	for(var i in weekDaysObj){

		nDay = new Date(parseInt(thisWk.substr(5, 4)),parseInt(thisWk.substr(1, 2))-1,parseInt(thisWk.substr(3, 2)));
		nDay.add(ii).day();

		$('.'+i).html($('.'+i).text() +' '+ nDay.toString('M-d-yy'));
		
		if(tmpDays.indexOf(weekDaysObj[i]) === -1 || nDay.getTime() < sD  || nDay.getTime() > eD){

			$('#'+i+'.spotsbyweekinline').prop("disabled", true).addClass('redBackground');
		}
		
		ii++;
	}
	
	//SPREADING SPOTS
	$('.spotsbyweekinline').each(function(i,element){
		$(this).val(row[thisWk.replace('w', 's')][weekDaysObj[$(this).prop('id')]]);
	});
	
}


function spotsDistribution(spots){
	
	//ALLOCATING SPOTS BY DAY
	var tmpDays 		= parseDaysOfTheWeek();
	var tSpots 			= 0;
	var v 				= 0;
	var r 				= {};
	var updatedSpots 	= {};
	
	//COUNTING ACTIVE DAYS TO COMPARE WITH THE CACHE
	for(var ii in weekDaysObj){
		if($.inArray(weekDaysObj[ii], tmpDays) !== -1){
			v++;
		}
	}

	if($.isEmptyObject(spotsByDayOfWeek) || v !== tmpDays.length){
		spotsByDayOfWeek = allocatingSpots(spots);
	}
	//console.log(spotsByDayOfWeek);

	for(var ii in weekDaysObj){
		updatedSpots[weekDaysObj[ii]] = parseInt(spotsByDayOfWeek[ii]);	
	}

	for(var x in updatedSpots){
		tSpots += parseInt(updatedSpots[x]); 
	}

	if(tSpots === 0){
		updatedSpots = parseInt(params.schedulespots);
	}
	
	r.spotsTotal = tSpots;
	r.spots 	 = updatedSpots;
	
	return r;	
	
}


function toggleEditionOfSpots(isLineByDay,selectedLines){
	var r = false;
	
	
	if(selectedLines === 1){
		
		var tmpLineByDay = 0;

		if('__group' in datagridProposal.selectedRows()[0]){
			
			var rs = datagridProposal.selectedRows()[0].rows;
			selectedLines = rs.length;
			for(var x = 0; x < selectedLines; x++){
				
				if(parseInt(rs[x].lineType) === 4){
					tmpLineByDay ++;
					
					if(tmpLineByDay > 1){
						isLineByDay	= tmpLineByDay; 
						break;
					}
				}
			}
		}
		isLineByDay = tmpLineByDay;
	}
	if(isLineByDay>=1){
		$('#spotByDayButton').show();		
		$('#spotByDayButton').button( "enable" );
	}
	else{
		$('#spotByDayButton').hide();		
	}

	return r;
};



function toggleEditionLineOrder(isLineOrder,selectedLines){
	var r = false;
	
	if(selectedLines === 1){
		
		var tmpLineOrder = 0;

		if('__group' in datagridProposal.selectedRows()[0]){
			
			var rs = datagridProposal.selectedRows()[0].rows;
			selectedLines = rs.length;
			for(var x = 0; x < selectedLines; x++){
				
				if(parseInt(rs[x].lineType) === 5){
					tmpLineOrder ++;
					
					if(tmpLineOrder > 1){
						isLineOrder	= tmpLineOrder; 
						break;
					}
				}
			}
		}		
		isLineOrder = tmpLineOrder;
	}
	
	return r;
};


function toggleLineTypeSelector(selectedLinesLen){
	if(selectedLinesLen > 1){
		$( "#line-mode" ).buttonset( "disable" );
	}
	else{
		$( "#no,#yes" ).prop('disabled', false).button('refresh');
		$( "#line-mode" ).buttonset( "enable" );
	}
};


function updateSpotsByDay(row,updatedSpots){
	var wk,nDay;
	var tmpDays 		= parseDaysOfTheWeek();
	var sDateArr  		= row.startdatetime.split(/[^0-9]/);
	var eDateArr  		= row.enddatetime.split(/[^0-9]/);
	var sD 				= new Date(parseInt(sDateArr[0]), parseInt(sDateArr[1])-1, parseInt(sDateArr[2])).getTime();
	var eD 				= new Date(parseInt(eDateArr[0]), parseInt(eDateArr[1])-1, parseInt(eDateArr[2])).getTime();
	var i 				= 0;
	var allSpots		= 0;
	
	for(var z in row){
		
		if(z.substr(0, 1) === 's' && $.isNumeric(z.substr(1, 1))){
			i = 0;
			
			for(var ii in weekDaysObj){
				
				nDay = new Date(parseInt(z.substr(5, 4)),parseInt(z.substr(1, 2))-1,parseInt(z.substr(3, 2)));
				nDay.add(i).day();
				
				if(tmpDays.indexOf(weekDaysObj[ii]) === -1 || nDay.getTime() < sD  || nDay.getTime() > eD){
					row[z][weekDaysObj[ii]] = 0;
				}
				else{
					row[z][weekDaysObj[ii]] = updatedSpots[weekDaysObj[ii]];
				}
				
				i++;	
			}

			wk = 0;

			if(weeksdata.indexOf('w'+String(z).substr(1, 8)) === -1){
				for(var jj in row[z]){
					wk +=	parseInt(row[z][jj]);
				}
			}
			
			row['w'+String(z).substr(1, 8)] = wk;
			allSpots += parseInt(wk);
		}
	}
	
	row.spots 			= allSpots;
	row.timestamp 		= new Date();
	row.total 			= parseFloat(allSpots)*parseFloat(row.rate);	
};


function majorFequency(a){
	var frequency 	= {}; // array of frequency.
	var max 		= 0;  // holds the max frequency.
	var result;   		  // holds the max frequency element.
	for(var v in a) {
        frequency[a[v]]=(frequency[a[v]] || 0)+1; // increment frequency.
        if(frequency[a[v]] > max) { // is this frequency > max so far ?
                max = frequency[a[v]];  // update max.
                result = a[v];          // update result.
        }
	}
	return result;	 
};

 

