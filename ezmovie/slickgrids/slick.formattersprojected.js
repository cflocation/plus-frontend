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
        "PremiereFinale": PremiereFinaleFormatter,
        "New": NewFormatter,
        

        "FormatTime": FormatTimeFormatter,
        "FormatGetKeyword": FormatGetKeywordFormatter,
        "FormatRemove": FormatRemoveFormatter,
        "FormatDateTime": FormatDateTimeFormatter,
        "DeleteFromGrid": DeleteFromGridFormatter,
        "DeleteFromGridTitle": DeleteFromGridTitleFormatter,
        "Heart": HeartFormatter,
        "Eye": EyeFormatter,
        "Reminder": ReminderFormatter,
        "ReminderSend": ReminderSendFormatter
        
      }
    }
  });





function PremiereFinaleFormatter(row, cell, value, columnDef, dataContext) {
  if(value == 'Season Premiere'){
    return '<span style="color:red;font-weight:bold;">Season Premiere</style>';
  }

  if(value == 'Series Premiere'){
    return '<span style="color:red;font-weight:bold;">Series Premiere</style>';
  }

  if(value == 'Season Fianle'){
    return '<span style="color:red;font-weight:bold;">Season Fianle</style>';
  }

  if(value == 'Series Fianle'){
    return '<span style="color:red;font-weight:bold;">Series Fianle</style>';
  }
}




function NewFormatter(row, cell, value, columnDef, dataContext) {
  if(value == 'new'){
    return '<span style="color:green;font-weight:bold;">New</style>';
  }
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
    return '<i class="icon-envelope"></i>';
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
  return '<img src="i/x.png" border=0>';
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
  		var row = value.split("|");
  		
  		if(row[1] > ""){
  			return "<b>"+row[0]+"</b> | <span class='smgrid'>"+row[1]+"</span>";
  		}else{
  			return "<b>"+row[0]+"</b>";
  		}				
}



  	//get the small network logo for the grids etc
  	function StatusIconsFormatter(row, cell, value, columnDef, dataContext) {
  		var row = value.split("|");

  		
  		if(row[0] == "Premiere"){
  			return '<span style="color:#990000;"><b>Mov Pre</b></span>';
  		}
  		
  		if(row[0] == "Season Premiere"){
  			return '<span style="color:#990000;"><b>Sea Pre</b></span>';
  		}
  		
  		if(row[0] == "Series Premiere"){
  			return '<span style="color:#990000;"><b>Ser Pre</b></span>';
  		}
  		
  		if(row[0] == "Season Finale"){
  			return '<span style="color:#990000;"><b>Sea Fin</b></span>';
  		}
  		
  		if(row[0] == "Series Finale"){
  			return '<span style="color:#990000;"><b>Ser Fin</b></span>';
  		}


  		if(row[1] != ""){
        if(row[1] == "Live"){
          return '<span style="color:#660066;"><b>Live</b></span>';
        }
        if(row[1] == "Delay"){
          return '<span style="color:blue;"><b>Delay</b></span>';
        }
  		}



  		if(row[2] != ""){
  			return '<span style="color:#006633;"><b>New</b></span>';
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