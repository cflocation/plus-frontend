
function buildQuatersList(minDate){
    var d   = new Date(),
    y       = d.getFullYear(),
    m       = d.getMonth(),
    quarters= ['0 2', '3 5', '6 8', '9 11'],
    options = [];
    opt     = [];
    
    for (var pastYear = y-3; pastYear < y; pastYear++) {
        quarters.forEach(q => options.push(q + ' ' + pastYear));
    }
    
    quarters.slice(0, parseInt(m / 3 + 1)).forEach(q => options.push(q + ' ' + y));
    var qtrSartDate = new Date();
    var qtrEndDate  = new Date();
    var quarter     = [];
    options.forEach(function(o){
        quarter = o.split(' ');
        qtrStartDate = getBroadcastWeek(new Date(quarter[2], quarter[0], 1).toString('yyyy/MM/dd')); //FIRST DATE OF THE QUARTER STANDAR 
        qtrEndDate = getBroadcastLastDay(new Date(quarter[2], quarter[1], 1).toString('yyyy/MM/dd')); //LAST DATE OF THE QUARTER
        if(qtrStartDate >= minDate || qtrEndDate >= minDate){
            if(qtrStartDate >= minDate){
                opt.push({'start':qtrStartDate, 'end':qtrEndDate});
            }
            else{
                opt.push({'start':minDate, 'end':qtrEndDate});
            }
        }
    });
    
    for(var q=opt.length-1; q >=0; q--){
        $('#quarter-selector').append($("<option></option>")
        .attr("value",opt[q].start.toString('yyyy/MM/dd')+'|'+opt[q].end.toString('yyyy/MM/dd'))
        .text('Q'+getQuarterOfTheYear(opt[q].end)+'-'+opt[q].start.toString('yy')+' '+opt[q].start.toString('MM/dd')+' to '+opt[q].end.toString('MM/dd'))); 
    }
  
}


function buildBroadcastCalebdar(){
	var re 			= [];
	var quatercnt 	= 1;
	var firstrday 	= new Date('2014-05-30'); //Date.january().first();
	var i,y,s,e,xrow;
	
	for(i = 0; i < 12; i++){
		tY 				= getMyBroadcastMonth(new Date(firstrday));
		y 				=  Date.parse(tY).toString("yy");		
		s 				=  new Date(tY).toString("yyyy/MM/dd");
		e 				=  new Date(tY).addMonths(3).toString("yyyy/MM/dd");
		xrow  			= {};
		xrow.quarter 	= quatercnt+'|'+y;
		xrow.starts 	= s;
		xrow.ends 		= e;
		re.push(xrow);
		quatercnt++;
		firstrday 		= new Date(tY).addMonths(3).add(1).days();
	}
	return re;
}



//get broadcast month from date
function getMyBroadcastMonth(d){
	var re 				= '';
	var thisDate 		= Date.parse(d);
	var thisDatOfWeek 	= new Date(thisDate).getDay();
	var inputDateMonth 	= new Date(thisDate).toString("yyyy/MM/01");
	var isSun 			= new Date(thisDate).moveToLastDayOfMonth().getDay();
	var lastSundayOfMonth;
	
	if(isSun == 0){
		lastSundayOfMonth = new Date(thisDate).moveToLastDayOfMonth();
	}
	else{
		lastSundayOfMonth = new Date(thisDate).moveToLastDayOfMonth().moveToDayOfWeek(0, -1);
	}
	
	if(thisDate > lastSundayOfMonth){
		var newmonth = new Date(thisDate).next().month().toString("yyyy/MM/01");
		re = newmonth;
	}
	else{
		re = inputDateMonth;
	}
	return re;
}


function updateAvailsQuarterSelector() {
    $('#date-start').removeClass('baddates');
    $('#date-end').removeClass('baddates');
    
    var sel = $('#quarter-selector').val();

    if (sel.length == 1 && sel == 0) {
        $("#sidebar-row-dates").css({
            opacity: 1
        });
        return;
    }

    var seldates = sel.split("|");
    var start 	 = Date.parse(seldates[0]).toString("MM/dd/yyyy");
    var end 	 = Date.parse(seldates[1]).toString("MM/dd/yyyy");

    $("#date-start").datepicker("setDate", start);
    $("#date-end").datepicker("setDate", end);


    var d1	= new Date.parse($('#date-start').val());
    var d2	= new Date.parse($('#date-end').val());

	
	if($("#dialog-title").dialog("isOpen")===true) {
		updateTitlesList();
	}
	
}