function addFixedStandardRowToTotals(totals,row,monthArray,columns){
	var dupe 	= checkDupeZone(row.zoneid,totals);
	if(dupe == 'no'){
		var temp 		= buildInitalTotalGrid(monthArray,row.zoneid,row.zone);
		temp.total 		= parseFloat(row.total);
		temp.nettotal 	= parseFloat(row.total);
		temp.spots 		= parseInt(row.spots);
		temp.rate 		= parseFloat(row.rate);
		
		proposalTotalInfoZones.push(row.zone);


		for(var i = 0; i < columns.length; i++) {
			var wk			= getBroadcastWeek(columns[i].dateFull);			
			if(typeof row[wk] != "undefined"){
				var mtotals 		= getSpotsbyWeekDay(row.day, wk,row[wk],row.rate,row.ratevalue,row.linetype,row.startdate,row.enddate);
				$.each(mtotals,function(i,value){
					
					var m 			= value['month'];
					var linetotal 	= parseFloat(value['rate']);
					var ratetotal 	= parseFloat(value['ratevalue']);

					temp.ratecard  += ratetotal;
					temp[m]		   += linetotal;
					temp[wk]	   	= row[wk];

				});
			}
			else{
				temp[wk] = 0;
			}
		}

		totals.push(temp);

	}
	else{
		var temp = totals[dupe];

		temp.total+=parseFloat(row.total);
		temp.nettotal+=parseFloat(row.total);
		temp.spots+=parseInt(row.spots);

		for(var i = 0; i < columns.length; i++) {
			var wk			= getBroadcastWeek(columns[i].dateFull);
			if(typeof row[wk] != "undefined"){
				var mtotals 		= getSpotsbyWeekDay(row.day, wk,row[wk],row.rate,row.ratevalue,row.linetype,row.startdate,row.enddate);

				$.each(mtotals,function(i,value){

					var m 			= value['month'];
					var linetotal 	= parseFloat(value['rate']);
					var ratetotal 	= parseFloat(value['ratevalue']);

					temp.ratecard  += ratetotal;
					temp[m]		   += linetotal;
					temp[wk]	   	= row[wk];
				});

			}
		}
	}

}


function buildStandardMonths(start,end){
 
  var starts 		= new Date.parse(Date.parse(start).toString("yyyy/MM/01"));
  var ends 			= new Date.parse(Date.parse(end).toString("yyyy/MM/01"));
  var monthCount 	= months_between(starts,ends);
  var dates 		= {};
  
  for(i=0; i<=monthCount; i++){
    var stdmonth 	= getStandardMonth(starts);
    var column 		= Date.parse(stdmonth).toString("MMddyyyy");
    var row 		= {};
    row.date 		= stdmonth;
    row.column 		= column;
    dates[stdmonth] = row;
    starts 			= new Date(starts).add(1).month().toString("yyyy/MM/dd 00:00");
  }
  
    return dates;
}



function buildStandardTotals(xdata){

	var proposalStartDate	= xdata.sort(startDate)[0].startdatetime;
	var proposalEndDate 	= xdata.sort(endDate)[0].enddatetime;
	var monthArray 			= buildStandardMonths(proposalStartDate,proposalEndDate);
	//var weekArray			= buildStandardWeeks(datagridProposal.getProposalStartDate(),datagridProposal.getProposalEndDate());
	var weekArray			= buildBroadcastWeeks(datagridProposal.getProposalStartDate(),datagridProposal.getProposalEndDate());
	var totals 				= [];
	
	proposalTotalInfoZones  = [];
	
	for(var i = 0; i < xdata.length; i++) {
	   	if(xdata[i].lineactive ==1)		
		addFixedStandardRowToTotals(totals,xdata[i],monthArray,weekArray);
	}

	mathSet(totals);
	
	buildTotalsRowFromTotals(totals);
	
	
	return totals;
}


function buildStandardWeeks(start,end){

	var starts 	= Date.parse(start).toString("yyyy/MM/dd 00:00");
	var ends 	= Date.parse(end).toString("yyyy/MM/dd 00:00");
	var weeks 	= [];
	
	while(starts <= ends){
	
		var date 	= Date.parse(starts).toString("MM/dd/yy");
		var dateFull= Date.parse(starts).toString("yyyy/MM/dd");
		var dateISO = Date.parse(starts).toString("yyyy-MM-dd");
		var column 	= Date.parse(starts).toString("MMddyyyy");
		var row 		= {};
		
		row.date 	= date;
		row.column 	= column;
		row.dateFull= dateFull;
		row.dateISO = dateISO;
		row.name 	= "w"+column;

		weeks.push(row);
		
		starts 		= new Date(starts).add(7).days().toString("yyyy/MM/dd 00:00");
	
	}

	return weeks;
}


function buildMonthFromSmallDate(d){
  return Date.parse(d).toString("MMddyyyy");
}


function getBroadcastWeek(start){
	var starts 	= Date.parse(start).toString("yyyy/MM/dd 00:00");
	var daynum 	= new Date(starts).getDay();

	if(daynum != 1){
		starts = new Date(starts).last().monday().toString("yyyy/MM/dd 00:00");
	}	

	return "w"+Date.parse(starts).toString("MMddyyyy");
}


function getStandardMonth(d){
  var thisDate = Date.parse(d); 
  var re = new Date(thisDate).toString("yyyy/MM/01");
  return re;
}



function getSpotsbyWeekDay(weekDays,wk,spots,rate,rcvalue,linetype,start,end){
	//get the broacast week date
	var monday 	= Date.parse( wk.substr(1, 2)+"/"+wk.substr(3, 2)+"/"+wk.substr(5, 4) );
	var starts  = Date.parse(start);
	var ends  	= Date.parse(end);

	//when line is Fixed
	if(linetype == 'Fixed') {
		
		//when day is a sunday
		if(weekDays != "1")
			wday = new Date(monday).add(weekDays-2).days().toString("yyyy/MM/dd");
		else//any other day of the week
			wday = new Date(monday).add(6).days().toString("yyyy/MM/dd");		
		
		//gets the corresponding standard month
		var thisMonth 			= "m"+buildMonthFromSmallDate(getStandardMonth(wday));
		var partialTotal 		= parseFloat(spots) * parseFloat(rate);
		var partialRCTotal 		= parseFloat(spots) * parseFloat(rcvalue);
		return ([{"month":thisMonth,"rate":partialTotal, "ratevalue":partialRCTotal, "week":wk}]);
			
	}//when line is a Rotator
	else{

		var results		= [];
		var spotsObj	= {};
		var wDays		= [];
		var thisValue	= '';
		$.each(weekDays,function(j,val){//collects days of the week and sorts M to Su

			if(val != "1"){
				wday = new Date(monday).add(val-2).days();
				thisValue =  val;
			}
			else{
				wday = new Date(monday).add(6).days();
				thisValue = '8';
			}

			if(wday >= starts && wday <= ends){
				wDays.push(thisValue)
			}			
		});
		
		wDays.sort(function(a, b){return a-b});
		
		var spotwk 		= parseInt(spots / wDays.length);//spots per day
		var spotr		= spots % wDays.length;// remaining spots
		
		//RECALCULATING OR BALANCING THE SPOTS IN A WEEK
		if(spotwk >= 1){
			//console.log('A');
			var remaining	= 0;
			spotsObj 		= {};
			$.each(wDays,function(j,val){					
				spotsObj[val] = spotwk;
			});
	

			if(spotr > 0 && spotr != 0){//the are remaining spots to be allocated

				while(remaining <= spotr){
					
					$.each(wDays,function(indx,myval){
						spotsObj[myval] = spotsObj[myval]+1;
						remaining++;
						
						if(remaining >= spotr){
							return false;
						}
					});

					if(remaining >= spotr){
						break;
					}
				}
						
			}
		}
		else if(spotwk < 1 && spots != 0){
			//console.log('B');
			var remaining	= 0;
			spotsObj 		= {};
			
			$.each(wDays,function(j,val){
				spotsObj[val] = 0;
			});
					
			while(remaining <= spotr){
					
				$.each(wDays,function(j,val){

					spotsObj[val] = spotsObj[val]+1;
					remaining++;
					
					if(remaining >= spotr){
						return false;
					}
				});

				if(remaining >= spotr){
					break;
				}
			}
		}
		else {
			//console.log('C');
			spotsObj 		= {};
			$.each(wDays,function(j,val){
				spotsObj[val] = 0;
			});
		}
			
		//LOOP OVER THE WEEK DAYS INCLUDED IN THE ROTATOR TO CALCULATE THE PRICE BY MONTH
		$.each(wDays,function(i,val){
							
			//when day is a sunday
			if(val != "1")
				wday = new Date(monday).add(val-2).days().toString("yyyy/MM/dd");
			else//any other day of the week
				wday = new Date(monday).add(6).days().toString("yyyy/MM/dd");

			//gets the corresponding standard month
			thisMonth 			= "m"+buildMonthFromSmallDate(getStandardMonth(wday));
			var partialTotal 	= parseFloat(spotsObj[val]) * parseFloat(rate);
			var partialRCTotal 	= parseFloat(spotsObj[val]) * parseFloat(rcvalue);

			results.push({"month":thisMonth,"rate":partialTotal, "ratevalue":partialRCTotal, "week":wk});
				
		});

		return results;
	}
		
}



function months_between(date1, date2) {
    return date2.getMonth() - date1.getMonth() + (12 * (date2.getFullYear() - date1.getFullYear()));
}




function toggleTotalsView(ischeked,calendarformat){
	if(stdcalendar == 0)
		return;

	$('#total-fixed-datagrid').html('');
			
	if(calendarformat == 'bc' && ischeked){
	   datagridTotals = new DatagridTotals();
	   $('#header-total').removeClass('total-standard').addClass('total');
	   $('#totals-wrapper').removeClass('totalwrapper-standard').addClass('totalwrapper');
	   $('#label-total-name').text('Totals Broadcast Calendar');
	   $('#label-bc-cal').show();
	   $('#label-sc-cal').hide();
	}
	else if(calendarformat == 'std' && ischeked){
	   datagridTotals = new DatagridStandardTotals();
	   $('#header-total').removeClass('total').addClass('total-standard');
	   $('#totals-wrapper').removeClass('totalwrapper').addClass('totalwrapper-standard');
	   $('#label-total-name').text('Totals Standard Calendar');
	   $('#label-bc-cal').hide();
	   $('#label-sc-cal').show();
	}

	var jdata = datagridProposal.getDataSet();
	datagridTotals.emptyGrid();
	resetTotals();
	
	if(jdata.length != 0)
		datagridTotals.populateDataGrid(jdata);	
		
	sizingTotalsBar();
}




function spotBalancer(proposalLines){
	$.each(proposalLines,function(idx){
		
		if(proposalLines[idx] != 'Fixed'){
			
			
		}
	});
	

}