function addFixedStandardRowToTotals(totals,row,monthArray,columns){
	var dupe 	= checkDupeZone(row.zoneid,totals);

	if(dupe === 'no'){
		var temp 		= buildInitalTotalGrid(monthArray,row.zoneid,row.zone);
		var mtotals, wk,m, linetotal, ratetotal;
		var sD,eD,sDate,eDate;
		temp.total 		= parseFloat(row.total);
		temp.nettotal 	= parseFloat(row.total);
		temp.spots 		= parseInt(row.spots);
		temp.rate 		= parseFloat(row.rate);
		
		proposalTotalInfoZones.push(row.zone);

		for(var i = 0; i < columns.length; i++) {
			wk = "w"+columns[i].column;
			if(wk in row){
		        sD 		= row.startDate.split(/[^0-9]/);
		        eD 		= row.endDate.split(/[^0-9]/);
		        
		        sDate 	= new Date(sD[0], parseInt(sD[1])-1,  sD[2], sD[3], sD[4]);
		        eDate 	= new Date(eD[0], parseInt(eD[1])-1,  eD[2], eD[3], eD[4]);
		        
				mtotals 		= getSpotsbyWeekDay(row.day, wk,row[wk],row.rate,row.ratevalue,row.linetype,sDate,eDate);

				$.each(mtotals,function(i,value){	
					m 				= value['month'];
					linetotal 		= parseFloat(value['rate']);
					ratetotal 		= parseFloat(value['ratevalue']);
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
			//wk				= getBroadcastWeek(columns[i].dateFull);
			wk = "w"+columns[i].column;
			if(wk in row){

		        sD 		= row.startDate.split(/[^0-9]/);
		        eD 		= row.endDate.split(/[^0-9]/);
		        
		        sDate 	= new Date(sD[0], parseInt(sD[1])-1,  sD[2], sD[3], sD[4]);
		        eDate 	= new Date(eD[0], parseInt(eD[1])-1,  eD[2], eD[3], eD[4]);
		        
				mtotals 	= getSpotsbyWeekDay(row.day, wk,row[wk],row.rate,row.ratevalue,row.linetype,sDate,eDate);			
				$.each(mtotals,function(i,value){
					m 				= value['month'];
					linetotal 		= parseFloat(value['rate']);
					ratetotal 		= parseFloat(value['ratevalue']);
					temp.ratecard  += ratetotal;
					temp[m]		   += linetotal;
					temp[wk]	   	= row[wk];
				});

			}
		}
	}

}


function buildStandardMonths(start,end){
	var s 				= start.split(/[^0-9]/);
	var e 				= end.split(/[^0-9]/);
	var sD 				= new Date(parseInt(s[0]),parseInt(s[1])-1,1);
	var eD 				= new Date(parseInt(e[0]),parseInt(e[1])-1,1);
	var starts			= sD.toString("yyyy/MM/dd");	
	var monthCount 		= months_between(sD,eD);
	var dates 			= {};
	var stdmonth,column,row;
	
	for(var i=0; i <= monthCount; i++){
		stdmonth 		= new Date(parseInt(s[0]),parseInt(s[1])-1,1).add(i).month();
		column 			= stdmonth.toString("MMddyyyy");
		row 			= {};
		row.date 		= stdmonth;
		row.column 		= column;
		dates['m'+i] 	= row;
	}
	return dates;
}



function buildStandardTotals(xdata){

	var proposalStartDate	= xdata.sort(startDate)[0].startdatetime;
	var proposalEndDate 	= xdata.sort(endDate)[0].enddatetime;
	var monthArray 			= buildStandardMonths(proposalStartDate,proposalEndDate);
	var weekArray			= buildBroadcastWeeksGoPlus(proposalStartDate,proposalEndDate);
	var totals 				= [];
	proposalTotalInfoZones  = [];
	
	for(var i = 0; i < xdata.length; i++) {
	   	if(parseInt(xdata[i].lineactive) === 1){
			addFixedStandardRowToTotals(totals,xdata[i],monthArray,weekArray);
		}
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
		var row 	= {};
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
	return d.toString("MMddyyyy");
}


function getBroadcastWeekGoPlus(start){
	var sDArray = start.split(/[^0-9]/);
	var sD 		= new Date(sDArray[0],parseInt(sDArray[1]-1),sDArray[2]);	
	if(parseInt(sD.getDay()) !== 1){
		sD = sD.last().monday();
	}	
	return sD;
}


function getStandardMonth(d){
	var mDate 		= d.split(/[^0-9]/);
	return new Date(parseInt(mDate[0]),parseInt(mDate[1])-1,1); 
}





function getSpotsbyWeekDay(weekDays,wk,spots,rate,rcvalue,linetype,start,end){
	//get the broacast week date
	var monday 	= new Date(wk.substr(5, 4), parseInt(wk.substr(1, 2)) - 1, wk.substr(3, 2), start.getHours(),start.getMinutes());
	var starts  = start;
	var ends  	= end;

	//when line is Fixed
	if(linetype === 'Fixed') {
		
		//when day is a sunday
		if(weekDays !== "1"){
			wday = new Date(monday).add(weekDays-2).days().toString("yyyy/MM/dd");
		}
		else{//any other day of the week
			wday = new Date(monday).add(6).days().toString("yyyy/MM/dd");		
		}
		
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

			if(val !== "1"){
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
			thisMonth 			= "m"+formatStdMonth(wday);
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
	/*if(stdcalendar === 0){
		return;
	}*/

	$('#label-bc-cal,#label-sc-cal').hide();
	$('#total-fixed-datagrid').html('');
	$('#header-total').removeClass('total total-standard');
	$('#totals-wrapper').removeClass('totalwrapper-standard totalwrapper');	
			
	var totalsData 	= clone(datagridTotals.dataSet());
	var totals 		= builtRatingTotals(totalsData);
	
	if(calendarformat === 'bc' && ischeked){
	   datagridTotals = new DatagridTotals();
	   $('#header-total').addClass('total');
	   $('#totals-wrapper').toggleClass('totalwrapper');
	   $('#label-total-name').text('Totals Broadcast Calendar');
		$('#label-bc-cal').show();
	}
	else{
	   datagridTotals = new DatagridStandardTotals();
	   $('#header-total').addClass('total-standard');
	   $('#totals-wrapper').toggleClass('totalwrapper-standard');
	   $('#label-total-name').text('Totals Standard Calendar');
		$('#label-sc-cal').show();
	}


	var jdata = datagridProposal.getDataSet();

	datagridTotals.emptyGrid();
	resetTotals();
	
	if(jdata.length !== 0){
		var proposalStartDate 	= jdata.sort(startDate)[0].startdatetime;
		var proposalEndDate 	= jdata.sort(endDate)[0].enddatetime;
		datagridTotals.populateDataGridGoPlus(jdata,proposalStartDate,proposalEndDate,totals);
	}
		
	//sizingTotalsBar();
}




function spotBalancer(proposalLines){
	$.each(proposalLines,function(idx){
		
		if(proposalLines[idx] != 'Fixed'){
			
			
		}
	});
};

function builtRatingTotals(data){
	var cols = ['avgRating','gRps','gImps','CPM','CPM','CPP','reach','freq','demoPop','demoSampleSize'];
	var demos = [];
	var i,j,k,d;
	var ratingsTotals = [];
	var ratingsZonesTotals = [];
	var rtg;
	var zoneTotal;
	var totals = {};
	var ratingsPop = {};
	var ratingsSampleSize = {};
	var demo;
	
	for(i = 0; i < data.length; i++){
		if(parseInt(data[i].zoneid) === 0){
			for(j in data[i]){
				if(j.indexOf('reach') !== -1){
					demo = j.replace('reach','');
					demos.push(demo);
				}
			}
		}
	}

	if(demos.length > 0){

		for(i = 0; i < data.length; i++){
			zoneTotal 				= {};
			zoneTotal.zoneId 		= data[i].zoneid;
			zoneTotal.zoneName 		= data[i].zonename;
			zoneTotal.zoneTotals 	= [];
							
			for(d = 0; d<demos.length; d++){
				rtg = {};

				rtg.demo = demos[d];
				
				ratingsPop[demos[d]] = data[i]['demoPop'+demos[d]];
				ratingsSampleSize[demos[d]] = data[i]['demoSampleSize'+demos[d]];
				totals.survey 		 = data[i]['survey'+demos[d]];
				
				for(k=0;k<cols.length;k++){
					rtg[cols[k]] = data[i][cols[k]+demos[d]];
				}
				if(parseInt(data[i].zoneid) !== 0){
					zoneTotal.zoneTotals.push(rtg);
				}
				else{
					ratingsTotals.push(rtg);				
				}
			}
			if(parseInt(data[i].zoneid) !== 0){
				ratingsZonesTotals.push(zoneTotal);
			}
		}
	}
	
	totals.ratingsZonesTotals 	= ratingsZonesTotals;
	totals.ratingsTotals 		= ratingsTotals;
	totals.demoPop				= ratingsPop;
	totals.demoSampleSize		= ratingsSampleSize;
	return totals;
}



//OUTDATED
function getBroadcastWeek_old(start){
	var starts 	= Date.parse(start).toString("yyyy/MM/dd 00:00");
	var daynum 	= new Date(starts).getDay();
	if(daynum != 1){
		starts = new Date(starts).last().monday().toString("yyyy/MM/dd 00:00");
	}	
	return "w"+Date.parse(starts).toString("MMddyyyy");
}