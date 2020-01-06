var proposalTotalInfoSpots;
var proposalTotalInfoGross;
var proposalTotalInfoNet;
var proposalTotalInfoAgencyDisc;
var proposalTotalInfoPackageDisc;
var proposalTotalInfoZones  = [];
var ratingsTotals;

function buildTotalsGoPlus(xdata,proposalStartDate,proposalEndDate){

	var totals	= [];
	try{
		var monthArray 			= buildBroadcastMonthsGoPlus(proposalStartDate,proposalEndDate);
		proposalTotalInfoZones  = [];
		
		for(var i = 0; i < xdata.length; i++) {
			if(parseInt(xdata[i].lineactive) === 1){
				addFixedRowToTotals(totals,xdata[i],monthArray);
			}
		}
		mathSet(totals);
		buildTotalsRowFromTotals(totals);
	}
	catch(e){}

	return totals;
}


function buildTotals(xdata){
	var totals				= [];
	try{
		var proposalStartDate 	= xdata.sort(startDate)[0].startdatetime;
		var proposalEndDate 	= xdata.sort(endDate)[0].enddatetime;
		var monthArray 			= buildBroadcastMonths(proposalStartDate,proposalEndDate);
	
		proposalTotalInfoZones  = [];
	
		for(var i = 0; i < xdata.length; i++) {
			if(xdata[i].lineactive ==1){
				addFixedRowToTotals(totals,xdata[i],monthArray);
			}
		}
	
		mathSet(totals);
	
		buildTotalsRowFromTotals(totals);
	}
	catch(e){}
	
	return totals;
}



//get the columns
//loop over the columns setting the col name as the new col name
//if new take the colval*rate for the total in the lines
//if old loop over thte columns and get the current value then apply colval*rate
//set the new vaue for the line


function addFixedRowToTotals(totals,row,monthArray){

	var dupe 			= checkDupeZone(row.zoneid,totals);
	var columns 		= datagridProposal.getDynamicColumnsByObj();
	var ratingsCols 	= buildDemoColumns(formatDemos());
	ratingsTotals 		= resetRatingsTotals(ratingsCols);

	if(dupe == 'no'){

		var temp 		= buildInitalTotalGrid(monthArray,row.zoneid,row.zone);
		temp.total 		= parseFloat(row.total);
		temp.nettotal 	= parseFloat(row.total);
		temp.spots 		= parseInt(row.spots);
		temp.rate 		= parseFloat(row.rate);
		
		proposalTotalInfoZones.push(row.zone);


		for(var i = 0; i < columns.length; i++) {

			if(columns[i].name in row){

				var m 			= "m"+buildBroadcastMonthFromSmallDate(getBroadcastMon(columns[i].date));
				var linetotal 	= parseFloat(row[columns[i].name]) * parseFloat(row.rate);
				var ratetotal 	= parseFloat(row[columns[i].name]) * parseFloat(row.ratevalue);
				temp.ratecard  += ratetotal;
				temp[m]		   += linetotal;
				temp[columns[i].name] = parseFloat(row[columns[i].name]);

				for(var c in ratingsTotals){
					temp[c] 	= 0;
				}

				
			}
			else{		
				temp[columns[i].name] = 0;
			}
		}
		totals.push(temp);

	}
	else{
		var temp = totals[dupe];

		temp.total		+=	parseFloat(row.total);
		temp.nettotal	+=	parseFloat(row.total);
		temp.spots		+=	parseInt(row.spots);

		for(var i = 0; i < columns.length; i++) {
			if(typeof row[columns[i].name] != "undefined"){
				var m 			= "m"+buildBroadcastMonthFromSmallDate(getBroadcastMon(columns[i].date));
				var linetotal 	= parseFloat(row[columns[i].name]) * parseFloat(row.rate);
				var ratetotal 	= parseFloat(row[columns[i].name]) * parseFloat(row.ratevalue);
				temp.ratecard  += ratetotal;
				temp[m]		   += linetotal;
				temp[columns[i].name]+=parseFloat(row[columns[i].name]);
				
				for(var c in ratingsTotals){
					temp[c] += 0;
				}				

			}
		}
	}
	return totals;
}







function buildInitalTotalGrid(monthArray,zoneid,zone){
		var totalrow = {};
		totalrow.id = zoneid;
		totalrow.spots = 0;
		totalrow.zoneid = zoneid;
		totalrow.zone = zone;
		totalrow.total = 0;
		totalrow.nettotal = 0;
		totalrow.agcydisc = 0;
		totalrow.pkgdisc = 0;
		totalrow.ratecard = 0;
		var obj,z;
		
		for (var key in monthArray) {
   			obj = monthArray[key];
   			z 	= 'm'+obj.column;
   			totalrow[z] = 0;
		}

		return totalrow;
}




function buildTotalsRowFromTotals(totals){
	var columns = datagridTotals.getDynamicColumnsByObj();
	
	var totalrow = {};
	totalrow.id = '';
	totalrow.spots = 0;
	totalrow.zoneid = '0';
	totalrow.zone = 'zzzzzzzzzzTotal';
	totalrow.total = 0;
	totalrow.nettotal = 0;
	totalrow.agcydisc = 0;
	totalrow.pkgdisc = 0;
	totalrow.ratecard = 0;
	
	for(var i = 0; i < columns.length; i++) {
		var col = columns[i].name;
		totalrow[col] = 0;

		for(var x = 0; x < totals.length; x++) {
			totalrow[col]+=totals[x][col];
		}
	}
	
	for(var x = 0; x < totals.length; x++) {
		totalrow.spots += totals[x].spots;
		totalrow.total += parseFloat(totals[x].total);
		totalrow.pkgdisc += parseFloat(totals[x].pkgdisc);
		totalrow.agcydisc += parseFloat(totals[x].agcydisc);
		totalrow.nettotal += parseFloat(totals[x].nettotal);
		totalrow.ratecard += parseFloat(totals[x].ratecard);

		for(var c in ratingsTotals){
			totalrow[c] = 0;
		}
		
	}


	totals.push(totalrow);

	displayNetTotal(totalrow);//updates totals in yellow bar

	proposalTotalInfoSpots = totalrow.spots;
	proposalTotalInfoGross = totalrow.total;
	proposalTotalInfoNet = totalrow.nettotal;
	proposalTotalInfoAgencyDisc = totalrow.agcydisc;
	proposalTotalInfoPackageDisc = totalrow.pkgdisc;
	
	toggleDiscounts(totalrow.total);
}


function displayNetTotal(totalrow){
	var agcyDisc = '';
	var packDisc = '';

	$("#totals-spots").html('Spots: '+totalrow.spots + ' | ');
	$("#totals-gross").html('Gross: '+accounting.formatMoney(totalrow.total) + ' | ');
	
	if(discountpackage !== 0){
		packDisc = 'Pkg Disc: '+ accounting.formatMoney(totalrow.pkgdisc) + ' | ';
	}
	if(discountagency !== 0){
		agcyDisc = 'Agcy Disc: '+ accounting.formatMoney(totalrow.agcydisc) + ' | ';	
	}
	
	$("#totals-net").html('Net: '+ accounting.formatMoney(totalrow.nettotal) + '&nbsp;&nbsp;&nbsp;');
	$("#totals-package").html(packDisc);
	$("#totals-agency").html(agcyDisc);
	
	return false;
};





function mathAgency(totals){
	//agcydisc
	if(discountagency == 1){
		$.each(totals, function(i, value){
			var discttl = parseInt(value.nettotal)-(parseInt(value.nettotal)*.15);
			var disc = parseInt(value.nettotal) - discttl;
			value.agcydisc = disc;
			//value.nettotal = discttl;
		})
	}
}


function mathSet(totals){

	var zonecnt = totals.length;
	var zonetotal = discountpackage/zonecnt;

	if(discountpackagetype == 1){
		$.each(totals, function(i, value){
			
			var tmp = pad(discountpackage);
			var pct = '.'+tmp;
			var disc = parseFloat(value.total)*pct;

			value.pkgdisc = disc;
			value.nettotal = value.total - disc;

			if(discountagency == 1){
			var discttl2 = parseFloat(value.nettotal)-(parseFloat(value.nettotal)*.15);
			var discs2 = parseFloat(value.nettotal) - discttl2;
			
			value.agcydisc = discs2;
			value.nettotal-=discs2;
			}
		})
	}else{
		$.each(totals, function(i, value){
    		value.pkgdisc = zonetotal;
    		value.nettotal = value.total - zonetotal;



    		if(discountagency == 1){
				var discttl2 = parseFloat(value.nettotal)-(parseFloat(value.nettotal)*.15);
				var discs2 = parseFloat(value.nettotal) - discttl2;
				value.agcydisc = discs2;
				value.nettotal-=discs2;
			}
    	})
	}

}




function pad(n) {
    return (n < 10) ? ("0" + n) : n;
}



function discountAgency(){
	var checked =  $('#discount-agency').is(':checked');
	var cols = datagridTotals.getCols();

	if(checked){
		var pos = (discountpackage<0)?4:3;
		discountagency = 1;
		cols.splice(pos, 0, agencyColumn()); 
		
	}else{
		for(var x=0; x<cols.length; x++){
			if(cols[x].id === 'agcydisc'){
				cols.splice(x, 1);
				break;
			}
		}
		discountagency = 0;
	}

	
	datagridTotals.set('columns',cols);
	datagridTotals.updateColumns(cols);
	var newTotals = totalDiscounts();
	

	datagridTotals.populateData(newTotals);
	var znsTtl = newTotals.length;
	
	if(znsTtl>1){
		displayNetTotal(newTotals[znsTtl-1]);	
	}
	
	updateDiscounts();
}

function updateDiscounts(){
	$.ajax({
        type:'post',
        url: apiUrl+"proposal/updatediscount",
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        processData: false,
        contentType: 'application/json',
        data: JSON.stringify({"proposalId":proposalid, "agencyDiscount":discountagency,"discountType":parseInt(discountpackagetype),"discount":parseFloat(discountpackage)}),
        success:function(resp){
        }
    });
}


function discountSet(){	
	var type 	= $("#discount-mode input[type='radio']:checked").val();
	var amt 	= $('#proposal-discount-package').val();
	var gross 	= $('#totals-gross').text().replace('Gross: $','').replace(' | ','').replace(/[, ]+/g, "");	
	var cols 	= datagridTotals.getCols();
	//basic validation
	if(amt === undefined || String(amt).trim() === ''){
		return;
	}

	if(parseInt(type) === 2 && parseFloat(amt) > parseFloat(gross) || parseInt(amt) > 100 && parseInt(type) === 1){
		$('#proposal-discount-package').addClass('baddates');
		setTimeout(function(){
			$('#proposal-discount-package').removeClass('baddates').val(0);
		}, 2000);
		return;
	}


	for(var x=0; x<cols.length; x++){
		if(cols[x].id === 'pkgdisc'){
			cols.splice(x, 1);
			break;
		}
	}

	if(amt > 0){
		discountpackage 	= amt;
		discountpackagetype = type;
		cols.splice(3, 0, discountColumn());//removing discounts col
	}else{
		discountpackage 	= 0;
		discountpackagetype = 0;
	}

	datagridTotals.set('columns',cols);
	datagridTotals.updateColumns(cols);
	
	var newTotals = totalDiscounts();
	
	datagridTotals.populateData(newTotals);
	var znsTtl = newTotals.length;
	
	if(znsTtl>1){
		displayNetTotal(newTotals[znsTtl-1]);	
	}
	
	updateDiscounts();
}



function resetTotals(){
	$("#totals-spots").html('Spots: 0 | ');
	$("#totals-gross").html('Gross: 0 | ');
	$("#totals-package").html('Pkg Disc: 0 | ');
	$("#totals-agency").html('Agcy Disc: 0 | ');
	$("#totals-net").html('New: 0 &nbsp;&nbsp;&nbsp;');
}



function resetRatingsTotals(cols){
	var iniRatings = {};
	for(var c = 0; c < cols.length; c++){
		iniRatings[cols[c].id] = 0;
	}
	return iniRatings;
}


function toggleDiscounts(total){
	if(parseInt(total) > 0){
		$('#discount-agency,#discount-percent,#discount-amount,#proposal-discount-package').prop("disabled", false);
		$('#agcy-lbl,#pck-lbl').css({'color':'#000'});
	}
	else{
		$('#discount-agency,#discount-percent,#discount-amount,#proposal-discount-package').prop("disabled", true);		
		$('#agcy-lbl,#pck-lbl').css({'color':'#aaa'});
	}
}



function totalDiscounts(){
	var totals 		= datagridTotals.getData();
	var zonecnt 	= totals.length-1;
	var zonetotal 	= discountpackage/zonecnt;
	var tmp,pct,disc,discs2,discttl2;
	var ttlNet 		= 0;
	var ttlDisc 	= 0;
	var ttlAgcy		= 0;
	if(discountpackagetype == 1){
		
		$.each(totals, function(i, value){	
			if(value.id !== ''){
				pct 			= discountpackage*.01;
				disc 			= parseFloat(value.total)*pct;
				value.pkgdisc 	= disc;
				value.nettotal 	= value.total - disc;
	
				if(discountagency == 1){
					discttl2 		= parseFloat(value.nettotal)-(parseFloat(value.nettotal)*.15);
					discs2 			= parseFloat(value.nettotal) - discttl2;
					value.agcydisc 	= discs2;
					value.nettotal 	-=discs2;
					ttlAgcy			+=discs2 
				}
				ttlNet 	= parseFloat(ttlNet) + parseFloat(value.nettotal);
				ttlDisc = parseFloat(ttlDisc) + parseFloat(value.pkgdisc);
			}
			else{
				value.nettotal 	= ttlNet;
				value.pkgdisc 	= ttlDisc;
				if(ttlAgcy > 0){
					value.agcydisc = ttlAgcy;
				}
			}
		});
	}
	else{
		$.each(totals, function(i, value){
			if(value.id !== ''){
	    		value.pkgdisc 	= zonetotal;
	    		value.nettotal 	= value.total - zonetotal;
	
	    		if(discountagency == 1){
					discttl2 		= parseFloat(value.nettotal) - (parseFloat(value.nettotal)*.15);
					discs2 			= parseFloat(value.nettotal) - discttl2;
					value.agcydisc 	= discs2;
					value.nettotal	-=discs2;
					ttlAgcy			+=discs2 					
				}
				ttlNet = parseFloat(ttlNet) + parseFloat(value.nettotal);
			}
			else{
				value.nettotal 	= ttlNet;
				value.pkgdisc 	= discountpackage;
				if(ttlAgcy > 0){
					value.agcydisc = ttlAgcy;
				}
			}

    	});
	}
	
	return totals;

}



