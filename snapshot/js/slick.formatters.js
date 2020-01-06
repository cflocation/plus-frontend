/***
 * Contains basic SlickGrid formatters.
 * @module Formatters
 * @namespace Slick
 */

(function ($) {
  // register namespace
  $.extend(true, window, {
    "Slick": {
      "Formatters":{
        "YesNo": YesNoFormatter,
        "Checkmark": CheckmarkFormatter,
        "NetworkLogoSmall": NetworkLogoSmallFormatter,
        "NetworkCallsign": NetworkCallsignFormatter,
        "NetworkLogoUrlSmall": NetworkLogoUrlSmallFormatter,
        "DayOfWeek": DayOfWeekFormatter,
        "EPITitle": EPITitleFormatter,
        "StatusIcons": StatusIconsFormatter,
        "RowCount": RowCountFormatter,
        "ProposalSettings": ProposalSettingsFormatter,
        "LineType": LineTypeFormatter,
        "Money": MoneyTypeFormatter,
        "FormatDate": FormatDateFormatter,
        "HiddenFormat": HiddenFormatter,
        "Ratecard": RatecardFormatter,
        "Escape": EscapeFormatter,
        "FormatTitle": FormatTitleFormatter,
        "FormatTime": FormatTimeFormatter,
        "FormatGetKeyword": FormatGetKeywordFormatter,
        "FormatRemove": FormatRemoveFormatter,
        "FormatDateTime": FormatDateTimeFormatter,
        "DeleteFromGrid": DeleteFromGridFormatter,
        "DeleteFromGridTitle": DeleteFromGridTitleFormatter,
        "Heart": HeartFormatter,
        "Eye": EyeFormatter,
        "Reminder": ReminderFormatter,
        "Dolast": DolastFormatter,
        "ReminderSend": ReminderSendFormatter,
        "RatecardPercentage": RatecardPercentageFormatter,
        "FormatRed": FormatRedFormatter,
        "FormatEndTime": FormatEndTime    
      }
    }
  });



//TIME FORMATTER
function FormatEndTime(row, cell, value, columnDef, dataContext) {
  var re = Date.parse(value).toString("hh:mm tt").replace('12:00 AM','12:00 MID').replace('23:59 PM','12:00 MID');  
	//console.log(re);
	return re;
}


function FormatRedFormatter(row, cell, value, columnDef, dataContext) {
  if(parseInt(value) == 0){
    return '<span style="color:red;">'+value+'</span>';
  }
  return value;
}


function DolastFormatter(row, cell, value, columnDef, dataContext) {
  if(value == 'zzzzzzzzzzTotal'){
    return 'Total';
  }
  return value;
}


function RatecardPercentageFormatter(row, cell, value, columnDef, dataContext) {

    if(value == 0){
      return '-';
    }

    if(value >= 80){
      return '<span style="color:green;">%'+value+'</span>';
    }

    if(value > 60){
      return '<span style="color:orange;">%'+value+'</span>';
    }

    if(value <= 60){
      return '<span style="color:red;">%'+value+'</span>';
    }

  return '<span>%'+value+'</span>';
}






function ReminderSendFormatter(row, cell, value, columnDef, dataContext) {
  return '<span onclick="reminderSend('+value+')"><i class="icon-envelope"></i> Send</span>';
}



function ReminderFormatter(row, cell, value, columnDef, dataContext) {
  if(value == 1){
    return '<span style="color:green;">Yes</span>';
  }else{
    return 'No';
  }
}




function EmailFormatter(row, cell, value, columnDef, dataContext) {
  return '<i class="icon-envelope"></i>';
}


function FormatTitleFormatter(row, cell, value, columnDef, dataContext) {
    //console.log(value);

    var z = replaceall(value,'^','*');
    return z;
}



function EmailFormatterAvails(row, cell, value, columnDef, dataContext) {
  return '<i class="icon-envelope"></i>';
}



function replaceall(str,replace,with_this)
{
    var str_hasil ="";
    var temp;

    for(var i=0;i<str.length;i++) // not need to be equal. it causes the last change: undefined..
    {
        if (str[i] == replace)
        {
            temp = with_this;
        }
        else
        {
                temp = str[i];
        }

        str_hasil += temp;
    }

    return str_hasil;
}





function EyeFormatter(row, cell, value, columnDef, dataContext) {
  if(value == 0){
    return '<i class="fa fa-envelope-o"></i>';
  }else{
    return '';
  }
}

//icon-eye-open


function HeartFormatter(row, cell, value, columnDef, dataContext) {
  return '<center><i onclick="toggletag(\''+value+'\');" class="icon-heart"></i></center>';
}


    //set the count to 0 if null
  function HiddenFormatter(row, cell, value, columnDef, dataContext) { 
      if(value > 0){
        return value;
      }
      return 0;
  }



function EscapeFormatter(row, cell, value, columnDef, dataContext) {
  return unescape(value);
}

function DeleteFromGridTitleFormatter(row, cell, value, columnDef, dataContext) {
  return '<div class="deletex"><img src="i/x.png" border=0 onClick=removeArrayElement('+row+',"title")></div>';
}
 


//FORMAT DELETE ROW BLANK COL
function DeleteFromGridFormatter(row, cell, value, columnDef, dataContext) {
  return '<center><i class="fa fa fa-trash-o fa-lg" style="color:red; cursor: pointer;"></i></center>';
}
 

 
//FORMAT REMOVE
function FormatRemoveFormatter(row, cell, value, columnDef, dataContext) {
	return '<div class="deletex"><img src="i/x.png" border=0 onClick="removeRowFromEzSearch('+row+')"></div>';
}
 
 
//FIND THE KEYWORD
function FormatGetKeywordFormatter(row, cell, value, columnDef, dataContext) {
	
	var str = value.toLowerCase();
	var re = '';
	
	
	$.each(searchKeywordsArray, function(i, value) {
		var x = value.title.toLowerCase();
	
		var n = str.search(x);
			
		if(parseInt(n) != -1){
			re = value.title;
		}
		
	});

	return re;
}
 
//FORMAT THE DATE TIME
function FormatDateTimeFormatter(row, cell, value, columnDef, dataContext) {
	var re = new Date(value).toString("MM/dd/yyyy hh:mm tt");
	return re;
}
  	
 
 
//FORMAT TEH DATE IN THE PROPOER ORDER. THIS IS HERE FOR SORTING ON THE OTEHR DATES YEAR NEEDS TO BE FIRST
function FormatDateFormatter(row, cell, value, columnDef, dataContext) {
  if(value == "" || value == null || value == "0000-00-00 00:00:00"){
    return;
  }
  var re = Date.parse(value).toString("MM/dd/yyyy");
	return re;
}
  	
//TIME FORMATTER
function FormatTimeFormatter(row, cell, value, columnDef, dataContext) {
  var re = Date.parse(value).toString("hh:mm tt");
  return re;
}
  	

  function MoneyTypeFormatter(row, cell, value, columnDef, dataContext) {
  	num = value;
  	    
  	num = isNaN(num) || num === '' || num === null ? 0.00 : num;
        
    if(columnDef.id == 'rate'){
    	return '<div class="fixed-allow">'+'$' + parseFloat(num).toFixed(2)+'</div>';;
    }
    
    return '$' + parseFloat(num).toFixed(2);
  }


  function YesNoFormatter(row, cell, value, columnDef, dataContext) {
    return value ? "Yes" : "No";
  }

  function CheckmarkFormatter(row, cell, value, columnDef, dataContext) {
    return value ? "<img src='slickgrids/images/tick.png'>" : "";
  }
  
  function NetworkLogoSmallFormatter(row, cell, value, columnDef, dataContext) {
  	if(value != ""){
  		return "<img width='25' src='"+value+"'>";
  	}
  	else{
  		return;
  	}
  }
 
 


    //set the count to 0 if null
  function RatecardFormatter(row, cell, value, columnDef, dataContext) { 
    return '<span class=ratecard>'+accounting.formatMoney(value)+'</span>';
  }




  	//set the count to 0 if null
	function RowCountFormatter(row, cell, value, columnDef, dataContext) { 

  

      if(columnDef.field == "total"){
        return accounting.formatMoney(value);
      }

  		
      if(columnDef.field == "spotsweek"){
        return '<div class="fixed-allow">'+value+'</div>';;
      }
      
      if(columnDef.field == "rate"){
        //console.log('spweek');
        return '<div class="fixed-allow">'+accounting.formatMoney(value)+'</div>';
      }

      if(value > 0){
        return '<div class="fixed-allow">'+value+'</div>';;
      }

      return 0;
  	}
  	

  
  
  //get the small network logo for the grids etc
  function NetworkLogoUrlSmallFormatter(row, cell, value, columnDef, dataContext) {
  	return "<img width='25' src='http://ww2.showseeker.com/logos/thumbnail/"+value+"'>";
  }
  

  
//FORMAT TITLE AND EPI TITLE
function EPITitleFormatter(row, cell, value, columnDef, dataContext) {

      if( Object.prototype.toString.call(value) === '[object Array]' ) {
        return "<b>"+value+"</b>";
      }

      var row = value.split("|"); 
  		if(row[1] > ""){
  			return "<b>"+row[0]+"</b> | <span class='smgrid'>"+row[1]+"</span>";
  		}else{
  			return "<b>"+row[0]+"</b>";
  		}				
}



//get the small network logo for the grids etc
function StatusIconsFormatter(row, cell, value, columnDef, dataContext) {
	if(value == 'z9'){
		return '';
	}
	
	switch(value) {
		case "Premiere":
			return '<span style="color:#990000;"><b>Mov Pre</b></span>';
			break;
		case "Season Premiere":
			return '<span style="color:#990000;"><b>Sea Pre</b></span>';
			break;
		case "Series Premiere":
			return '<span style="color:#990000;"><b>Ser Pre</b></span>';
			break;
		case "Season Finale":
			return '<span style="color:#990000;"><b>Sea Fin</b></span>';
			break;
		case "Series Finale":
			return '<span style="color:#990000;"><b>Ser Fin</b></span>';
			break;
		case "Live":
			return '<span style="color:#660066;"><b>Live</b></span>';
			break;				
		case "Delay":
			return '<span style="color:blue;"><b>Delay</b></span>';
			break;
		case "New":
			return '<span style="color:#006633;"><b>New</b></span>';
			break;
	}		
	return '';
}
  
  
  
 	//format day
	function DayOfWeekFormatter(row, cell, value, columnDef, dataContext) {
    var re = formatterDayOfWeek(value);		
		return re;
  }
  
 	function NetworkCallsignFormatter(row, cell, value, columnDef, dataContext) {
 		var network = value.split("|"); 
 		return '<span title="'+network[1]+'">'+network[0]+'</span>';
  	}
  	
  	
	//GEAR FORMATIING FOR SETTINGS
   	function ProposalSettingsFormatter(row, cell, value, columnDef, dataContext) {
 		return '<div onclick="openProposalSettings();" class="ui-icon ui-icon-gear"></div>';
  	}
  
     function LineTypeFormatter(row, cell, value, columnDef, dataContext) {
 		return 'F';
  	}
  
  
})(jQuery);