function removeArrayElement(row,type){
return;
	if(type == 'title'){
		arrayTitles.splice(row,1); 
		datagridTitlesSelected.populateDataGridFromArray(arrayTitles);
		return;
	}
}


//reset sorting
function resetSorting(){
	$('#download-sort-1').val('startdate');
	$('#download-sort-2').val('starttime');
	$('#download-sort-3').val('network');
	$('#marathon-sorting-text').css('display', 'none');
}



//check the dulicate of records in a array
function checkDupe(z, data) {
  if(z === undefined) {
    return 1;
  }
  var re = 0;
  $.each(data, function(i, value) {
    if(value.id === z) {
      re = 1;
    }
  });
  return re;
}


//blank inoput
function isBlank(val){
  if(val.trim().length == 0){
    return false;
  }
  return true;
}






Object.find = function(arr,obj) {
    return (arr.indexOf(obj) != -1);
}



function dofind(arr,obj) {
    return (arr.indexOf(obj) != -1);
}


Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};



function startDate(a,b) {
	var aa = a.startdatetime.replace(/\D/g,'');
	var bb = b.startdatetime.replace(/\D/g,'');
	
	
	if (aa < bb)
		 return -1;
		 
	if (aa > bb)
		return 1;
		
	return 0;
}


function endDate(a,b) {
	var aa = a.enddatetime.replace(/\D/g,'');
	var bb = b.enddatetime.replace(/\D/g,'');
	
	if (aa > bb)
		return -1;

	if (aa < bb)
		return 1;

	return 0;
}


function validate(evt) {
  
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;

  if(key == 8){
    theEvent.returnValue = true;
    return;
  }

  key = String.fromCharCode(key);
  var regex = /[0-9]|\./;

  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}



function IsEmail(email) {
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	return regex.test(email);
}



function isNumberKey(evt,val){
	var charCode	= (evt.which) ? evt.which : evt.keyCode;
	var valCount	= 0;
	
	if (val != undefined)
		valCount  = (val.match(/\./g) || []).length;
	if((charCode != 46 && charCode > 31  && (charCode < 48 || charCode > 57)) || (valCount == 1	&& charCode == 46) )
		return false;
	
	 return true;
}


function isNumber(val){
 return ! isNaN(val) && isFinite(val);

}


function isValidSpot(evt) {
	n = $("#edit-line-spots").val();
	
	if(evt == null){
		if( isNaN(n)  && !isInt(n) || n < 1){
			$("#edit-line-spots").css({'background-color':'yellow'});
			setTimeout(function(){
					$("#edit-line-spots").css({'background-color':'white'});}
					,800);
			return false;
		}
	}
		
	else{
		var charCode	= (evt.which) ? evt.which : evt.keyCode;
		if((charCode == 46) || (charCode != 8  && charCode < 48 || charCode > 57))
			return false;
	}
	
  	return true;
  	
}

function isValidNumberOnKeyUp(evt,id) {

	n = $("#"+id).val();	
	var charCode	= (evt.which) ? evt.which : evt.keyCode;
	var r = true;
	if((charCode == 46) || (charCode == 190) || (charCode != 8  && charCode < 48 || charCode > 57) || isNaN(parseInt(n)) || n < 1){
		r = false;
	}
	
	return r;
}

function isValidNumberOnKeyUpAlt(evt,id) {

	n = $("#"+id).val();	
	var charCode	= (evt.which) ? evt.which : evt.keyCode;
	var r = true;
	if((charCode == 46) || (charCode == 190) || (charCode != 8  && charCode < 48 || charCode > 57) || isNaN(parseInt(n)) || n < 0){
		r = false;
	}
	
	return r;
}

function isValidNumberOnKeyPress(evt,val){
	var charCode	= (evt.which) ? evt.which : evt.keyCode;
	var valCount	= 0;
	
	if (val != undefined)
		valCount  = (val.match(/\./g) || []).length;
	
	if((charCode == 46 && valCount <=1) || (charCode != 46 && charCode > 31  && (charCode < 48 || charCode > 57)) || (valCount == 1	&& charCode == 46) )
		return false;
	
	return true;	
}



function isValidNonZeroNumberOnKeyPress(evt,val){
	var charCode	= (evt.which) ? evt.which : evt.keyCode;
	var valCount	= 0;
	
	if (val != undefined)
		valCount  = (val.match(/\./g) || []).length;
	//if((charCode == 46 && valCount <=1) || (charCode != 46 && charCode > 31  && (charCode < 48 || charCode > 57)) || (valCount == 1	&& charCode == 46) || charCode == 48){
	if((charCode == 46 && valCount <=1) || (charCode != 46 && charCode > 31  && (charCode < 48 || charCode > 57)) || (valCount == 1	&& charCode == 46)){
		return false;
	}
	
	return true;	
}


function isValidTitle() {

	var title = $('#edit-line-title').val();
	  
	if(title == null || title.length >= 250 || title == ''){
		$("#edit-line-title").css({'background-color':'yellow'});
		setTimeout(function(){
				$("#edit-line-title").css({'background-color':'white'});}
				,800);
		return false;
	}

  	return true;
}


function isInt(n) {
	return (typeof n === 'number')  && (n % 1 === 0);
}


function getMonday(d) {
  //d = new Date(d);
  d = new Date.parse(d);

  var day = d.getDay(),
      diff = d.getDate() - day + (day == 0 ? -6:1); // adjust when day is sunday
  return new Date(d.setDate(diff));
}

function getSunday(d) {
	
  d = getMonday(d);

  var diff = d.getDate() + 6;
  return new Date(d.setDate(diff));
}

function getMondayFromDateGoPlus(x){
  var dateArray	= x.split(/[^0-9]/);
  var d = new Date(dateArray[0],parseInt(dateArray[1])-1,dateArray[2]);
  var n = d.getDay();
  var re = '';
  
  
  if(parseInt(n) === 1){
    re = d.toString("MMddyyyy");
    re = "w"+re;
  }else{
    re = d.last().monday().toString("MMddyyyy");
    re = "w"+re;
    
  }
  return re;
}

function getMondayFromDate(x){
  var d = new Date.parse(x);
  var n = d.getDay();
  var re = '';
  
  
  if(n == 1){
    re = d.toString("MMddyyyy");
    re = "w"+re;
  }else{
    re = new Date.parse(x).last().monday().toString("MMddyyyy");
    re = "w"+re;
    
  }
  return re;
}





function buildBroadcastMonths(start,end){
  
  //var starts 	= new Date(Date.parse(start).toString("yyyy/MM/dd")).moveToDayOfWeek(1,-1);
  //var ends 		= new Date(Date.parse(end).toString("yyyy/MM/dd")).moveToDayOfWeek(1,-1);
  var starts 	= getMonday(start);
  var ends 		= getSunday(end);
  var dates   = {};

  while(starts <= ends){
    var bmonth 		= getBroadcastMonth(starts);
    var column 		= Date.parse(bmonth).toString("MMddyyyy");
    var row 		= {};
    row.date 		= bmonth;
    row.column 		= column;
    dates[bmonth] 	= row;
    starts 			= new Date(starts).add(7).days();
  }

  return dates;

}




//get broadcast month from date
function getBroadcastMonth(d){
  var re = '';
  var thisDate = Date.parse(d);
  var thisDatOfWeek = new Date(thisDate).getDay();
  
  var inputDateMonth = new Date(thisDate).toString("yyyy/MM/01");
  

  var isSun = new Date(thisDate).moveToLastDayOfMonth().getDay();

  if(isSun == 0){
    var lastSundayOfMonth = new Date(thisDate).moveToLastDayOfMonth();
  }else{
    var lastSundayOfMonth = new Date(thisDate).moveToLastDayOfMonth().moveToDayOfWeek(0, -1);
  }

  if(thisDate > lastSundayOfMonth){
    var newmonth = new Date(thisDate).next().month().toString("yyyy/MM/01");
    re = newmonth;
  }else{
    re = inputDateMonth;
  }
  
  return re;
}







function buildBroadcastWeeks(start,end){

	var starts 	= Date.parse(start).toString("yyyy/MM/dd 00:00");
	var ends 	= Date.parse(end).toString("yyyy/MM/dd 00:00");
	var daynum 	= new Date(starts).getDay();
	
	if(daynum != 1){
		starts = new Date(starts).last().monday().toString("yyyy/MM/dd 00:00");
	}
	
	var weeks = [];
	
	while(starts <= ends){
	
		var date 		= Date.parse(starts).toString("MM/dd/yy");
		var dateFull 	= Date.parse(starts).toString("yyyy/MM/dd");
		var dateISO 	= Date.parse(starts).toString("yyyy-MM-dd");
		var column 		= Date.parse(starts).toString("MMddyyyy");
		var row			= {};
		
		row.date 		= date;
		row.column 		= column;
		row.dateFull 	= dateFull;
		row.dateISO 	= dateISO;
		
		weeks.push(row);
		
		starts 			= new Date(starts).add(7).days().toString("yyyy/MM/dd 00:00");
	
	}
	
	return weeks;

}




function schedulerDaysOfWeek(days){

	var ndays 	= [];
	var re 		= '';	
	var cnt 	= days.length;
	
	if(cnt == 7){
		return 'M-Su';
	}
	
	if(days[0] == 'ms'){
		return 'M-Su';
	}
	
	if(days[0] == 'ss'){
		return 'Sa-Su';
	}
	
	if(days[0] == 'mf'){
		return 'M-F';
	}
	
	$.each(days, function(i, val){
		if(val == 1){
			ndays.push(7);
		}
		else{
			ndays.push(parseInt(val) - 1);
		} 
	});
	ndays.sort();
	
	//if one day pass it back bro
	if(days.length == 1){
		return daysAbbrSmallDayFix(ndays[0]);
	}
	
	var diff = ndays[ndays.length-1] - ndays[0];
	
	if(ndays.length - diff == 1){
		re = daysAbbrSmallDayFix(ndays[0]) + "-" + daysAbbrSmallDayFix(ndays[ndays.length-1]);
	}
	else{
		var daylist = [];
		$.each(ndays, function(i, val){
			daylist.push(daysAbbrSmallDayFix(val));
		});
		return daylist;
	}
	return re;
}




function GUID (){
    var S4 = function ()
    {
        return Math.floor(
                Math.random() * 0x10000 /* 65536 */
            ).toString(16);
    };
    return (
            S4() + S4() + "-" +
            S4() + "-" +
            S4() + "-" +
            S4() + "-" +
            S4() + S4() + S4()
        );
}


function schedulerCountWeeksFromDates(){

	var ttl 	= 0;
	var x 		= new Date($("#date-start").val());
	var date1 	= new Date(getProperMonday(x));
	var date2 	= new Date($("#date-end").val());
	
	for (var d = date1; d <= date2; d.setDate(d.getDate() + 7)) {
	  ttl++;
	}
	
	$("#schedule-weeks").val(ttl);
}


function getProperMonday(x){
  var d = new Date(x);
  var n = d.getDay();
  var re = '';
  
  
  if(n == 1){
    re = d.toString("MM/dd/yyyy");
  }else{
    re = new Date(x).last().monday().toString("MM/dd/yyyy");
    
  }
  return re;
}


function schedulerCountWeeksFromInput(){
  var weeks = $("#schedule-weeks").val();
  var date1 = new Date($("#date-start").val());
  if(date1.getDay() === 0){
  	weeks--;
  }
  var diff = weeks*10080 - 10080;
  
  var newdate = new Date(date1.getTime() + diff*60000);

  var ends = new Date($("#date-end").val());
  var sunday = new Date(newdate).sunday().toString("MM/dd/yyyy");
  
  $("#date-end").val(sunday);

}


Object.keys = Object.keys || function(o) { 
    var result = []; 
    for(var name in o) { 
        if (o.hasOwnProperty(name)) 
          result.push(name); 
    } 
    return result; 
};


if (!Array.prototype.filter){
  Array.prototype.filter = function(fun /*, thisp */)
  {
    "use strict";

    if (this === void 0 || this === null)
      throw new TypeError();

    var t = Object(this);
    var len = t.length >>> 0;
    if (typeof fun !== "function")
      throw new TypeError();

    var res = [];
    var thisp = arguments[1];
    for (var i = 0; i < len; i++)
    {
      if (i in t)
      {
        var val = t[i]; // in case fun mutates this
        if (fun.call(thisp, val, i, t))
          res.push(val);
      }
    }

    return res;
  };
}


if (!('indexOf' in Array.prototype)) {
    Array.prototype.indexOf= function(find, i /*opt*/) {
        if (i===undefined) i= 0;
        if (i<0) i+= this.length;
        if (i<0) i= 0;
        for (var n= this.length; i<n; i++)
            if (i in this && this[i]===find)
                return i;
        return -1;
    };
}



function sortbuilder(zone,starts,callsign){
  return zone+"|"+starts+"|"+callsign;
}



Array.prototype.getUnique = function(){
   var u = {}, a = [];
   for(var i = 0, l = this.length; i < l; ++i){
      if(u.hasOwnProperty(this[i])) {
         continue;
      }
      a.push(this[i]);
      u[this[i]] = 1;
   }
   return a;
}




function findStationForDupe(arr,station){
  var re = 0;
  $.each(arr, function(i, value){
    var id = parseInt(value.id);
    if(id == station){
      re = id;
      return id;
    }
  });
  return re;
}





function checkDupeZone(z,data){
  var re = 'no';
  $.each(data, function(i, value) {
    if(value.zoneid == z){  
      re = i;
    }
  });
  return re;
}

function checkDupeMonth(z,data){
  var re = 'no';
  $.each(data, function(i, value) {
    if(value[z] == z){  
      re = i;
    }
  });
  return re;
}



function getAvailTimeByHour(t,type){
   var time = new Date('01/01/1999 ' + t).toString("HH");
   var temp = '01/01/1999 ' + time + ':00';

    if(type == 24){
      return new Date(temp).toString("HHmm");
    }else{
      return new Date(temp).toString("hh:mm tt");
    }
}




function availDaysOfWeekFormatter(days){

  var ndays = [];
  var re = '';
  
  
  if(days.length == 7){
    return 'M-Su';
  }

  if(days[0] == 1 && days[1] == 7){
    return 'Sa-Su';
  }

  $.each(days, function(i, val){
    if(val == 1){
      ndays.push(7);
    }else{
      ndays.push(val - 1);
    } 
  });
  
  //if one day pass it back bro
  if(days.length == 1){
    return daysAbbrSmallDayFix(ndays[0]);
  }
  
  

  var diff = ndays[ndays.length-1] - ndays[0];
  
  if(ndays.length - diff == 1){
    re = daysAbbrSmallDayFix(ndays[0]) + "-" + daysAbbrSmallDayFix(ndays[ndays.length-1]);
  }
  else{  
    var daylist = [];

    $.each(ndays, function(i, val){
      daylist.push(daysAbbrSmallDayFix(val));
    });


    if(daylist[0] == 'Su'){
      var su = daylist.splice(0,1); 
      daylist.push(su[0]);
    }

    return daylist;
  }

  return re;
}



function getAvailDays(rows){
  var re = [];

  for(var i = 0; i < rows.length; i++) {
    var day = rows[i].day;
    re.push(day);
  }

  var items = re.getUnique();
  var x = items.sort(function(a,b){return a-b});
 
  var z = availDaysOfWeekFormatter(x);

  return z;
}

function getAvailsTimeRange(id){
	
    var dayparts = $('#' + id).val();	
	
	var st = '23:59:59';
	var et = '00:00:00';
		
	$.each(dayparts, function(i, daypart){
		if(String(daypart) != 0 ){
			
			var daypartsArr = daypart.split('|');
			
			var starttime = daypartsArr[0];
			var endtime 	= daypartsArr[1];
			if(starttime < endtime){
				if(starttime < st)
					st = starttime;
		
				if(endtime > et)
					et = endtime;
			}
		}
	});
		
	r = [st,et];
	return r;
}


function selectTimeRangeForAvails(minOpt){
	
	$('#'+minOpt+' option').prop('selected',false);
	
	var starttime 		= new Date("01/01/2000 "+$("#time-start").val());
	var endtime 		= new Date("01/01/2000 "+$("#time-end").val());	

	var stime = starttime.toTimeString().substr(0,8);
    var etime = endtime.toTimeString().substr(0,8);
    var cnt = 0;
    var lastitem = 0;
	$('#'+minOpt+' option').each(function(){
		var thisopt = $(this).val();
		if(thisopt != 0){
			r = thisopt.split("|");
			st = r[0];
			et = r[1];
			/*console.log(st +' - '+stime);
			console.log(et +' - '+etime);
			console.log('...');*/			
			if(st>=stime && et<=etime){
				$(this).prop('selected',true);
				lastitem = thisopt;
			}
		}
		else{
			$(this).prop('selected',false);
		}
		cnt++;
	});
	
	$('#'+minOpt).scrollTop(lastitem);
}




if(typeof String.prototype.trim !== 'function') {
  String.prototype.trim = function() {
    return this.replace(/^\s+|\s+$/g, ''); 
  }
}



function sortByKey(array, key) {
    return array.sort(function(a, b) {
        var x = a[key]; var y = b[key];
        return ((x < y) ? -1 : ((x > y) ? 1 : 0));
    });
}

function weeksCount(start,end){
	
	var starts 	= Date.parse(start).toString("yyyy/MM/dd 00:00");
	var ends 	= Date.parse(end).toString("yyyy/MM/dd 00:00");
	var daynum 	= new Date(starts).getDay();
	var count 	= 0;
	
	if(daynum != 1){
		starts = new Date(starts).last().monday().toString("yyyy/MM/dd 00:00");
	}
	
	while(starts <= ends){
		count++;
		starts 			= new Date(starts).add(7).days().toString("yyyy/MM/dd 00:00");
	}
	return count;
}


function parseDaysOfTheWeek(){
	
	var tDaysOfWk = [];
	
	// PARSING DAYS OF THE WEEK
	for(var x in arrayDays){
		if(!isNaN(parseInt(arrayDays[x]))){
			tDaysOfWk.push(parseInt(arrayDays[x]));
		}
	}
	
	return tDaysOfWk;
}



function isInFlightDate(row,d){
	var r = false;
	var sDateArr  		= row.startdatetime.split(/[^0-9]/);
	var eDateArr  		= row.enddatetime.split(/[^0-9]/);
	
	var sD 				= getMonday(new Date(parseInt(sDateArr[0]), parseInt(sDateArr[1])-1, parseInt(sDateArr[2]))).getTime();
	var eD 				= getSunday(new Date(parseInt(eDateArr[0]), parseInt(eDateArr[1])-1, parseInt(eDateArr[2]))).getTime();
	var thisWeek 		= new Date(parseInt(d.substr(5, 4)),parseInt(d.substr(1, 2))-1,parseInt(d.substr(3, 2)));
	
	if(thisWeek >= sD && thisWeek <= eD){
		r = true;
	}
	return r;
}


function getInactiveWeeks(){
	var hiddenWeeksArray = [];
	if(weeksdata.length > 0){
		var tmpD;
		for(var k = 0; k < weeksdata.length; k++){
			tmpD = String(weeksdata[k]).substr(1, 8);
			hiddenWeeksArray.push(tmpD.substr(4,4) + '-' + tmpD.substr(0,2) + '-' + tmpD.substr(2,2));
		}
	}
	return hiddenWeeksArray;
}

function clone(obj) {
    if (null == obj || "object" != typeof obj) return obj;
    var copy = obj.constructor();
    for (var attr in obj) {
        if (obj.hasOwnProperty(attr)) copy[attr] = obj[attr];
    }
    return copy;
}

function deepClone(item) {
    if (!item) { return item; } // null, undefined values check

    var types = [ Number, String, Boolean ], 
        result;

    // normalizing primitives if someone did new String('aaa'), or new Number('444');
    types.forEach(function(type) {
        if (item instanceof type) {
            result = type( item );
        }
    });

    if (typeof result == "undefined") {
        if (Object.prototype.toString.call( item ) === "[object Array]") {
            result = [];
            item.forEach(function(child, index, array) { 
                result[index] = clone( child );
            });
        } else if (typeof item == "object") {
            // testing that this is DOM
            if (item.nodeType && typeof item.cloneNode == "function") {
                var result = item.cloneNode( true );    
            } else if (!item.prototype) { // check that this is a literal
                if (item instanceof Date) {
                    result = new Date(item);
                } else {
                    // it is an object literal
                    result = {};
                    for (var i in item) {
                        result[i] = clone( item[i] );
                    }
                }
            } else {
                // depending what you would like here,
                // just keep the reference, or create new object
                if (false && item.constructor) {
                    // would not advice to do that, reason? Read below
                    result = new item.constructor();
                } else {
                    result = item;
                }
            }
        } else {
            result = item;
        }
    }

    return result;
};


function objConcat(o1, o2) {
	for (var key in o2) {
		o1[key] = o2[key];
	}
	return o1;
};

function roundNumber(value, decimals) {
	var r = 0;
	if(value !== undefined){
		var v = String(value).replace(/[^0-9.]/, '');
		r = Number(Math.round(v+'e'+decimals)+'e-'+decimals).toFixed(decimals);
	}
	return r;
	
};

function isEmpty(obj) {
	for(var key in obj) {
		if(obj.hasOwnProperty(key)){
			return false;
		}
	}
    return true;
};


function compareSelectedDates(startDateEval,endDateEval,rows){
    var sdate = new Date.parse(startDateEval);	
    var edate = new Date.parse(endDateEval);	
    var ed,sd;
    var dif = false;

    for(var j=0; j< rows.length; j++){
	    if('__group' in rows[j]){
            $.each(r[j].rows, function(i, rid){
                $.each(data, function(ii, value){
	                ed = value.enddatetime.split(/[^0-9]/);
	                ed = new Date(parseInt(ed[0]), parseInt(ed[1])-1, parseInt(ed[2]));
					sd = value.startdatetime.split(/[^0-9]/);
	                sd = new Date(parseInt(sd[0]), parseInt(sd[1])-1, parseInt(sd[2]));
                    if(edate.getTime() !== ed.getTime() || sdate.getTime() !== sd.getTime()){
	               		dif = true;
				   		return dif;
                    }
                });
            });
	    }
	    else{
            ed = rows[j].enddatetime.split(/[^0-9]/);
            ed = new Date(parseInt(ed[0]), parseInt(ed[1])-1, parseInt(ed[2]));      
            sd = rows[j].startdatetime.split(/[^0-9]/);
            sd = new Date(parseInt(sd[0]), parseInt(sd[1])-1, parseInt(sd[2]));      
            if(sdate.getTime() !== sd.getTime() || edate.getTime() !== ed.getTime()){
           		dif = true;
           		break;
            }            
	    }
    }
    return dif;
};

function validateSpotsInFligtDates(row,week,startD,endD){
		var dDate,thisdate,currentdate;
		
        var sDateArr  	= row.startdatetime.split(/[^0-9]/);
        var eDateArr  	= row.enddatetime.split(/[^0-9]/);
        if(startD && endD){
	        sDateArr  	= startD.split(/[^0-9]/);
	        eDateArr  	= endD.split(/[^0-9]/);	        
        }
        
		var dayofwk 	= effectiveLineDays(row.day);
		var uniques 	= dayofwk.unique().sort();
		var sd 			= new Date(parseInt(sDateArr[0]), parseInt(sDateArr[1])-1, parseInt(sDateArr[2])).getTime();
		var ed 			= new Date(parseInt(eDateArr[0]), parseInt(eDateArr[1])-1, parseInt(eDateArr[2])).getTime();
		var uniquesR 	= [uniques[0]]; 
		dDate			= String(week);
		var isValidWeek = false;
		
		
		if(uniques.length > 1){
			uniquesR.push(uniques[uniques.length-1]);	
		}
		
		$.each(uniquesR,function(n,v){
			currentdate = new Date(parseInt(dDate.substr(5, 4)), parseInt(dDate.substr(1, 2))-1, parseInt(dDate.substr(3, 2)));		
			currentdate.setDate(currentdate.getDate() + parseInt(v));
			ctime = currentdate.getTime();	
			if(ctime >= sd && ctime <= ed){
				isValidWeek = true;	
				return isValidWeek;
			}
		});
		return isValidWeek;
	};
	
	
function sortObjects(a, b){
	var nameA = a.dmaName.toUpperCase(); // ignore upper and lowercase
	var nameB = b.dmaName.toUpperCase(); // ignore upper and lowercase
	var r = 0;

	if (nameA < nameB) {
		r = -1;
	}
	else if (nameA > nameB) {
		r = 1;
	}
	// names must be equal
	return r;
};

function arrayToObject(a){
	var arr = a;
	arr.reduce(function(acc, cur, i) {
		acc[i] = cur;
		return acc;
	}, {});
	return arr;	
};

function fixRatingVal(val){
	var r =  val;	
	if(!isNaN(val)){
		r =  parseFloat(val).toFixed(rndDecimalPlaces);
	}
	return r;	
};
	
function userRating_Bad(val, decimals) {
    var objRegExp = /\d*\.?\d+/;
    return objRegExp.test(val);
};	


function userRating(val) {
	var objRegExp = /\d*\.?\d+/;
	
	if(objRegExp.test(val)){
		var splitVal = val.split(".");
		if (splitVal.length > 1){
			if( splitVal[0].length <= 2 ){
				if( splitVal[1].length <= rndDecimalPlaces ) {
					// Number has 2 decimals eg. 1.2.3
					return true;
				}
				else {
					// Number is incorrect eg. 1.2.3.4
					return false;
				}
			}
			else{
				return false;
			}
		}
		else{
			if(val.length <=2){
				return true;
			}
			else{
				return false;
			}
		}
	
	}
	else{
		return false;
	} 
};

function getTimeStamp(){
	var today 	= new Date();
	var m		= ("0" + (today.getMonth() + 1)).slice(-2);
	var day		= ("0" + today.getDate()).slice(-2);
	var y 		= today.getFullYear();
	var hr 		= today.getHours();
	var	mins	= today.getMinutes();
	
	return [y,m,day,hr,mins];	
};

