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
        "Tracker": CheckTrackerFormatter,
        "NetworkLogoSmall": NetworkLogoSmallFormatter,
        "NetworkCallsign": NetworkCallsignFormatter,
        "NetworkLogoUrlSmall": NetworkLogoUrlSmallFormatter,
        "DayOfWeek": DayOfWeekFormatter,
        "EPITitle": EPITitleFormatter,
        "StatusIcons": StatusIconsFormatter,
        "RowCount": RowCountFormatter,


		"TotalCost":TotalsCostFormatter,
		"SpotsWeek":SpotsWeekFormatter,
		"Rates":RatesFormatter,
		"CellCount":CellCountFormatter,

		"LineByDay":LineByDayFormatter,        
		"Callsign":CallsignFormatter,
        
        "ProposalSettings": ProposalSettingsFormatter,
        //"LineType": LineTypeFormatter,
        "Money": MoneyTypeFormatter,        
        "FormatDate": FormatDateFormatter,
        "FormatProposalDates": ProposalDatesFormatter,
        "ShortFormatDate": ShortFormatDateFormatter,
        
        "HiddenFormat": HiddenFormatter,
        "Ratecard": RatecardFormatter,
        "Escape": EscapeFormatter,
        "FormatTitle": FormatTitleFormatter,
        "FormatTime": FormatTimeFormatter,
        
        "ShortFormatTime": ShortFormatTimeFormatter,
        
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
        "FormatSpots": FormatSpots,
                
        "FormatEndTime": FormatEndTime,
        
        
        "ShortFormatEndTime": ShortFormatEndTime,
        "LineType": LineTypeFormatter,        
        
	    "TwoDigits": FormatTwoDigits,
	    "OneDigit": FormatOneDigit,
	    "OneDigitPercentage": FormatOneDigitPercentage,
	    "Ezratings":CheckRatingsFormatter,
	    "Ratings":RatingsFormat,
	    "RatingsSearch":RatingsSearchFormat,
		"NoDigits": FormatNoDigits,
		"Frequency": FormatFrequency,			    
		"CPP": FormatCPP,
		"Impressions": FormatImpressions,
		"ImpsSearch": FormatImpressionsSearch,
		"CustomPackageDownload": CustomPackageDownload,
		"CustomPackagePublisher": CustomPackagePublisher        
        
      }
    }
  });
  	
  	var rtgClass = {'rating':'ratings-allow','gRps':'','share':'', 'impressions':'ratings-allow'};
	

	function FormatCPP(row, cell, value, columnDef, dataContext){ 
		var r;
		var val = accounting.formatMoney(parseFloat(value).toFixed(2));

		if(dataContext.notInSurvey){
			r = '<span class=disabledRating>-</span>';
		}
		else{
			r = '<span class=hander>'+val+'</span>';
		}
		
		if(dataContext['minRepStd'+columnDef.demo] === false && !dataContext.notInSurvey){
			r += '<span class="meetMinRepStd">*</span>';					
		}			

		return r;
	};
	

	function FormatFrequency(row, cell, value, columnDef, dataContext){ 
		var r = '';
		
		if(dataContext.notInSurvey){
			r += '<span class=disabledRating>-</span>';
		}
		else{
			r += roundNumber(value,1);
		}
		
		if(dataContext['minRepStd'+columnDef.demo] === false && !dataContext.notInSurvey){
			r += '<span class="meetMinRepStd">*</span>';					
		}	
		return r;
	};	


	function CheckRatingsFormatter(row, cell, value, columnDef, dataContext){
		if(value){
			return "<div><span class=hander style='color:green;'><i class='fa fa-line-chart'></i></span></div>";
		}
		else{		  
			return "<div><span class=hander style='color:#ccc;'><i class='fa fa-line-chart'></i></span></div>";
		}
	};

	function FormatTwoDigits(row, cell, value, columnDef, dataContext){ 
		var r = 0;
		if(value || parseInt(value) > -1){
			r = roundNumber(value,2);
		}
		else{
			r = '<span class="disabledIconMinus">*</span>';
		}
		return r;
	};
	
	function FormatImpressions(row, cell, value, columnDef, dataContext){ 
		var demo 	= columnDef.demo;
		var val 	= roundNumber(value,0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		var r 		= '';		
		
		if(dataContext.notInSurvey){
			r += 	'<div class=disabledRating>-';
		}
		else if(parseInt(dataContext['customRating'+demo]) === 1 && columnDef.column === 'impressions'){
			r +=	'<div class=customRating>'+val;
		}
		else{
			r += 	'<div class='+rtgClass[columnDef.column]+'>'+val;
		}

		if(dataContext['minRepStd'+demo] === false && !dataContext.notInSurvey){
			r += 	'<span class="meetMinRepStd">*</span>';					
		}
		
		r += '</div>';
		
		return r;
	};

	function FormatImpressionsSearch(row, cell, value, columnDef, dataContext){ 
		var demo 	= columnDef.demo;
		var val 	= roundNumber(value,0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		var r 		= '';		
		
		if(dataContext.notInSurvey){
			r += 	'<div class=disabledRating>-';
		}
		else if(parseInt(dataContext['customRating'+demo]) === 1 && columnDef.column === 'impressions'){
			r +=	'<div class=customRating>'+val;
		}
		else{
			r += 	'<div>'+val;
		}

		if(dataContext['minRepStd'+demo] === false && !dataContext.notInSurvey){
			r += 	'<span class="meetMinRepStd">*</span>';					
		}
		
		r += '</div>';
		
		return r;
	};	
	
	function FormatOneDigit(row, cell, value, columnDef, dataContext){ 
		var r = 0;
		if(parseInt(value) < 0){
			r = '<span class="disabledIconMinus">*</span>';			
		}
		else if(value !== undefined){
			r = roundNumber(value,1);			
		}
		return r;
	};

	function FormatOneDigitPercentage(row, cell, value, columnDef, dataContext){ 
		var r = '';

		if(dataContext.notInSurvey){
			r += '<span class=disabledRating>-</span>';
		}
		else{
			r += '<span>'+roundNumber(value,1)+'%</span>';			
		}

		if(dataContext['minRepStd'+columnDef.demo] === false && !dataContext.notInSurvey){
			r += '<span class="meetMinRepStd">*</span>';					
		}	
		return r;
	};

	function FormatNoDigits(row, cell, value, columnDef, dataContext){ 
		var r = roundNumber(value,0);			
		if(dataContext['minRepStd'+columnDef.demo] === false){
			r += '<span class="meetMinRepStd">*</span>';					
		}	
		return r;
	};
	
	function RatingsFormat(row, cell, value, columnDef, dataContext){ 
		var demo 	= columnDef.demo;
		var val 	= roundNumber(value,rndDecimalPlaces);
		var r;
		
		if(dataContext.notInSurvey){
			r = '<div class="disabledRating">-';
		}
		else if(parseInt(dataContext['customRating'+demo]) === 1 && columnDef.column === 'rating'){
			r = '<div class="customRating">'+val;
		}
		else{
			r = '<div class="'+rtgClass[columnDef.column]+'">'+val;
		}
		
		
		if(dataContext['minRepStd'+demo] === false && !dataContext.notInSurvey){
			r += '<span class="meetMinRepStd">*</span>';					
		}			
		r += '</div>';
		
		return r;
	};


	function RatingsSearchFormat(row, cell, value, columnDef, dataContext){ 
		var r = roundNumber(value,rndDecimalPlaces);		
		if(dataContext['minRepStd'+columnDef.demo] === false){
			r += '<span class="meetMinRepStd">*</span>';					
		}	
		return r;
	};


	function LineByDayFormatter(row, cell, value, columnDef, dataContext){ 
		var r = 0;
		if(row.linetype === 'ByDay' && parseInt(value) !== 0){
			r = '<div class="cellByDay">'+value+'</div>';
		}
		else if(value){
			r = value;
		}
		return r;
	}

	//TIME FORMATTER
	function FormatEndTime(row, cell, value, columnDef, dataContext) {
		var re = Date.parse(value).toString("hh:mm tt").replace('12:00 AM','12:00 MID').replace('23:59 PM','12:00 MID');  
		return re;
	}

	function ShortFormatEndTime(row, cell, value, columnDef, dataContext) {		
		var dArray = value.split(/[^0-9]/);
		var d =  new Date(dArray[0],dArray[1],dArray[2],dArray[3],dArray[4]).toString("h:mm tt");
		return d.replace('M','').replace(':00','').replace('11:59 P','12 M');
	}

	function LineTypeFormatter(row, cell, value, columnDef, dataContext) {				
		var r = 'F';

		switch(value){
			case 2 :
				r = 'W';
				break;
			case 3 :
				r = 'A';
				break;
			case 4 :
				r = 'D';
				break;
			case 5 :
				r = 'L';
				break;
		}		
		
		return r;
	};

	function FormatRedFormatter(row, cell, value, columnDef, dataContext) {
		var r = value;
		if(parseInt(value) == 0){
			r = '<span style="color:red;">'+value+'</span>';
		}
		return r;
	};

	function FormatSpots(row, cell, value, columnDef, dataContext){
		var r = value;
		if(parseInt(value) === 0){
			r = '<span class="ratematch">'+value+'</span>';
		}
		return '<div class="fixed-allow">'+r+'</div>';
	};

	function DolastFormatter(row, cell, value, columnDef, dataContext) {
		if(value == 'zzzzzzzzzzTotal'){
			return 'Total';
		}
		return value;
	};

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
		}
		return 'No';
	}

	function EmailFormatter(row, cell, value, columnDef, dataContext) {
		return '<i class="icon-envelope"></i>';
	}
		
	function FormatTitleFormatter(row, cell, value, columnDef, dataContext) {
		var z = replaceall(value,'^','*');
		return z;
	}

	function EmailFormatterAvails(row, cell, value, columnDef, dataContext) {
		return '<i class="icon-envelope"></i>';
	}

	function replaceall(str,replace,with_this){
		var str_hasil ="";
		var temp;
	
		for(var i=0;i<str.length;i++){ // not need to be equal. it causes the last change: undefined..
	
			if (str[i] == replace){
				temp = with_this;
			}else{
				temp = str[i];
			}
			str_hasil += temp;
		}
	
		return str_hasil;
	}

	function EyeFormatter(row, cell, value, columnDef, dataContext) {
		if(value == 0){
			return '<i class="fa fa-envelope-o"></i>';
		}
		return '';
	}

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
		return '<center><i class="fa fa fa-trash-o fa-lg hander" style="color:red"></i></center>';
	}
	
	//FORMAT REMOVE
	function FormatRemoveFormatter(row, cell, value, columnDef, dataContext) {
		return '<div class="deletex"><img src="i/x.png" border=0 onClick="removeRowFromEzSearch('+row+')"></div>';
	}
 
	//FIND THE KEYWORD
	function FormatGetKeywordFormatter(row, cell, value, columnDef, dataContext) {
			
		var str = value.toLowerCase();
		var re = '';
		var x,n;
		
		$.each(searchKeywordsArray, function(i, value) {
			x = value.title.toLowerCase();
			n = str.search(x);
		
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
		var dArray = value.split(/[^0-9]/);					
		return new Date(dArray[0],dArray[1]-1,dArray[2]).toString("MM/dd/yy");
	}

	//SHORT DATE FORMAT
	function ShortFormatDateFormatter(row, cell, value, columnDef, dataContext) {
		var dArray = value.split(/[^0-9]/);
		return new Date(dArray[0],dArray[1]-1,dArray[2]).toString("MM/dd/yy");
	}
	
	function ProposalDatesFormatter(row, cell, value, columnDef, dataContext) {
		if(value == "" || value == null || value == "0000-00-00 00:00:00"){
			return;
		}
		var dArray = value.split(/[^0-9]/);					
		return new Date(dArray[0],dArray[1]-1,dArray[2]).toString("MM/dd/yyyy");
	}
  	
	//TIME FORMATTER
	function FormatTimeFormatter(row, cell, value, columnDef, dataContext) {
		var dArray = value.split(/[^0-9]/);
		return new Date(dArray[0],dArray[1],dArray[2],dArray[3],dArray[4]).toString("hh:mm tt");
	}
  	
	//TIME FORMATTER
	function ShortFormatTimeFormatter(row, cell, value, columnDef, dataContext) {
		var dArray = value.split(/[^0-9]/);
		var d =  new Date(dArray[0],dArray[1],dArray[2],dArray[3],dArray[4]).toString("h:mm tt");
		return d.replace('M','').replace(':00','');
	}

	function MoneyTypeFormatter(row, cell, value, columnDef, dataContext) {
		num = value;	
		num = isNaN(num) || num === '' || num === null ? 0.00 : num;
	
		if(columnDef.id == 'rate'){
			return '<div class="fixed-allow">'+'$' + accounting.formatMoney(parseFloat(num).toFixed(2)) +'</div>';
		}
	
		return accounting.formatMoney(parseFloat(num).toFixed(2));
	}
	
	function YesNoFormatter(row, cell, value, columnDef, dataContext) {
		return value ? "Yes" : "No";
	}
	
	function CheckmarkFormatter(row, cell, value, columnDef, dataContext) {
		return value ? "<img src='slickgrids/images/tick.png'>" : "";
	}
  
	function CheckTrackerFormatter(row, cell, value, columnDef, dataContext){
		if(String(value) ===  '1'){
			return "<div class=tracker><span class=inTracker style='text-decoration:underline; color:green; cursor:pointer;'>Yes</span></div>";		  
		}
		else{		  
			return "<div class=tracker style='color:#ccc;'><span class=inTracker><i class='fa fa-link fa-lg clickableImage'></i></span></div>";	  	
		}
	}  
  
	function NetworkLogoSmallFormatter(row, cell, value, columnDef, dataContext) {
		if(value != ""){
			//return "<img width='25' src='"+value.replace('https://showseeker.s3.amazonaws.com/images/netwroklogo/75','https://showseeker.s3.amazonaws.com/images/networklogos/png/25')+"'>";
			return "<img width='25' src='"+value+"'>";
		}
		return;
	}
 
	//set the count to 0 if null
	function RatecardFormatter(row, cell, value, columnDef, dataContext) { 
		return '<span class=ratecard>'+accounting.formatMoney(value)+'</span>';
	}

	//set the count to 0 if null
	function RowCountFormatter(row, cell, value, columnDef, dataContext) { 
		var r = value;
		
		switch(columnDef.field){
			case "total":
				r = accounting.formatMoney(value);
				break;
			case "spotsweek":
				r = '<div class="fixed-allow">'+value+'</div>';
				break;
		
			case "rate":
				r = '<div class="fixed-allow">'+accounting.formatMoney(value)+'</div>';
			break;

			default:
				var inRange = validateSpotsInFligtDates(dataContext,columnDef.id);

				if(value === undefined || !inRange){
					r = '<div class="weekOut"> - </div>';
				}
				else if(inRange){
					switch(dataContext.lineType){
						case 1:
							r = '<div class="fixed-allow">'+value+'</div>';
							break;
						case 4:
							r = '<div class="cellByDay">'+value+'</div>';
							break;
						case 5:
							r = '<div class="cellLineOrder"><span class="innerLO">'+value+'</span></div>';
							break;
					}
				}
		}
		return r;
	}

	function TotalsCostFormatter(row, cell, value, columnDef, dataContext) { 
		return accounting.formatMoney(value);
	}
	
	function SpotsWeekFormatter(row, cell, value, columnDef, dataContext) { 
		return '<div class="fixed-allow">'+value+'</div>';
	}
	
	function RatesFormatter(row, cell, value, columnDef, dataContext) { 
		return '<div class="fixed-allow">'+accounting.formatMoney(value)+'</div>';
	}  	
	
	//set the count to 0 if null
	function CellCountFormatter(row, cell, value, columnDef, dataContext) { 
		return '<div class="fixed-allow">'+value+'</div>';
	}  
  
	//get the small network logo for the grids etc
	function NetworkLogoUrlSmallFormatter(row, cell, value, columnDef, dataContext) {
		return "<img width='25' src='https://ww2.showseeker.com/logos/thumbnail/"+value+"'>";
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

	function NetworkCallsignFormatter(row, cell, value, columnDef, dataContext){
		var network = value.split("|"); 
		return '<span title="'+network[1]+'">'+network[0]+'</span>';
	}

	function CallsignFormatter(row, cell, value, columnDef, dataContext){
		return '<span class="'+dataContext.linetype+dataContext.lineType+'">'+dataContext.callSign+'</span>';			
	}
  	
	//GEAR FORMATIING FOR SETTINGS
	function ProposalSettingsFormatter(row, cell, value, columnDef, dataContext) {
		return '<div onclick="openProposalSettings();" class="ui-icon ui-icon-gear"></div>';
	}
	
	function CustomPackagePublisher(row, cell, value, columnDef, dataContext) {
  return '<a href="mailto:'+value.email+'">'+value.firstName+ ' '+ value.lastName+'</a> ('+value.officeName+')';
}

function CustomPackageDownload(row, cell, value, columnDef, dataContext) {
  return '<a href="#" onclick="customPackageNetworkCheck('+value+');"><i class="fa fa-lg fa-arrow-circle-down" aria-hidden="true"></i></a>';
}
	
  
})(jQuery);
