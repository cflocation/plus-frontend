function processWeeks(apiData,gridData,updateHeight){
	var add_days, cday, y, programs, w,dw,h,x;
	var $programCell,$cellText,$parent,$header,$footer,$innerbody,$airTimes;
	var $lTimeCol, $rTimeCol;
	var dynamicClass;
	var schedules 	= apiData.programming;
	var months 		= apiData.months;
	var montArray,weeksCount;
	cellHeight    	= 88;
	x = 0;
	y = 0;
	if(sswin.myEzRating.getRatings('saved') === 1){
		cellHeight    = 176;
	}

	//-- BUILDING SCHEDULES BY WEEK
	var tColum 		= timeRuler();
	var allweeks 	= 0;
	w  = 0;
	var mon;
	
	months.sort(function(a, b) {
	    return parseFloat(a.id) - parseFloat(b.id);
	});	

	//-- MONTHS AS TABS -->
	var $monthNav =  $("<ul>", {id: "monthNav", style:"margin-left:38px"});
	$('#ezgridSchedules').append($monthNav);

	if(months[0] === undefined){
		$('#overlay').hide();
		$('#noprogramming').show();
		startUpGrids();
		return;
	}
	$('#noprogramming').hide();
	printDate =months[0].weeks[0];
	
	
	for(var j=0; j<months.length; j++){
		
		weeksCount = 0;
	
		//MONTLY NAVIGATOR
		wks 			= months[j].weeks;
		
		$tabContainer 	= $("<div>", {id: "tab-"+months[j].id, "class":"weeksNav"});
		$navItem 		= $("<li>");
		$navAnchor		= $("<a>", {href: "#tab-"+months[j].id, "class":"mTab"});
		
		$navAnchor.html(months[j].fmt);
		$navItem.append($navAnchor);
		//$monthNav.append($navItem);
		
		//WEEKLY NAVIGATOR
		$programming 	= $("<div>", {id:'boxBody'+j, "class": "tabs topBorder"});
		$wkNavContainer = $("<ul>", {id:"month"+j, "class": "wkNavigator"});			
		$programming.append($wkNavContainer);
					
		for(var wk=0; wk < wks.length; wk++){
			if(schedules[wks[wk]].length === 0){
				continue;
			}
			
			weeksCount++;
			mon = String(wks[wk]);
			x = 0;	
			$parent = $("<div>", {id: wks[wk], "class": "parent m"+j});
			$programming.append($parent);
			$wkNavItem 			= $("<li >", {"class": "m"+j});
			$wkNavAnchor		= $("<a>", {id:'w'+w, href: "#"+wks[wk], onclick:"javascript:printDate='"+mon+"'"});
			montArray 			= wks[wk].split(/[^0-9]/);
			$wkNavAnchor.html(mapMonth(parseInt(montArray[1]))+' '+wks[wk].substr(8,2)+'');
			$wkNavItem.append($wkNavAnchor);
			$wkNavContainer.append($wkNavItem);
		

			//<!-- HEADER (DAYS OF THE WEEK) -->
			currentWeekDays = daysOfWeek(w,gridData);
			$header = $("<div>", {id: "header"});
			$header.append('<div><div class="lCell" id="callsigncorner"></div>');
			$header.append(currentWeekDays);		
			$header.append('<div class="rCell netLogoBackground" style="background:url('+logo+gridData.networkId+'.png)"></div></div>');
			$parent.append($header);
	
	
			//<!-- GRID CONTENTS  -->
			$innerbody = $("<div>", {id: "innerbody", "class":"clear scheduleGrid"});
			$parent.append($innerbody);
			
			//<!-- LEFT HAND SIDE TIME COLUMN  -->
			$lTimeCol = $("<div>", {id: "lTime", "class":"timeRuler"});
			$innerbody.append($lTimeCol);
	
			//<!-- SCHEDULES -->
			var $divOuter = $("<div>", {id: "outerContainer"+w, "class": "cellContainer"});
			$innerbody.append($divOuter);
	
			var $divInner = $("<div>", {id:"selectable"+w,"class": "innerContainer"});		
			$divOuter.append($divInner);
		
			var episode;

			for(dw=0; dw<schedules[wks[wk]].length; dw++){
				
				add_days = 7*(w)+dw;
				y		 = 0;	
				
				//EMPTY DAYS FILL UP WITH BLANK CELLS
				if(schedules[wks[wk]][dw].length  === 0){
					y = 0;
					h = cellHeight;
					trg = getTimes(d.startTime,d.endTime,60);
					for(var hr=0; hr<trg.length; hr++){	
						$programCell = $("<div>", {id:0, "class": "blankCell", "style":"left:"+x+"px; top:"+y+"px;  height:"+h+"px;"});
						$cellText = $("<div>", {"class": "cellText"});
						$programCell.append($cellText);
						$divInner.append($programCell)
						y += h;	
					}
				}
				else{
					episode =schedules[wks[wk]][dw];
					
					for(hr=0; hr<episode.length; hr++){
						
						
						if(episode[hr].projected === 1 ){
							y = mapTimes(episode[hr]['start_'+tz],episode[hr].title);
						}
						
						premieres = episode[hr].premierefinale;
	
						if(premieres === 'Premiere'){
							premieres	= 'MoviePremiere'
						}
					
						h = Math.floor(cellHeight*episode[hr].duration/60);
	
						dynamicClass = String(episode[hr].live+episode[hr].genre1).replace(/\s/g,'');
						dynamicClass+=' '+premieres+' '+episode[hr].isnew;
	
						$programCell = $("<div>", {id:episode[hr].id+'-'+sswin.zoneid, "class": "programCell", "style":"left:"+x+"px; top:"+y+"px;  height:"+h+"px;"});
						$programCell.data(episode[hr]);
						$cellText = $("<div>", {"class": "cellText "+dynamicClass});
						$cellText.html('<i class="fa fa-square-o fa-2x checkbox addShow"></i> <span class="programTitle">'+ episode[hr].title+'</span>');
		
						$programCell.append($cellText);
						$programCell.append($airTimes);
						$divInner.append($programCell)
						y += h;
					}
				}
				
				x = x + 142;
			}

					
			//<!-- RIGHT HAND SIDE TIME COLUMN -->
			$rTimeCol = $("<div>", {id: "rTime", "class":"timeRuler"});
			$innerbody.append($rTimeCol);
	
			//<!-- FOOTER (DAYS OF THE WEEK) -->
			$footer = $("<div>", {id: "gridfooter", "class":"clear"});
			$footer.append('<div><div class="lbCell" id="callsigncorner"></div>');
			$footer.append(currentWeekDays);
			$footer.append('<div class="rbCell netLogoBackground" style="background:url('+logo+gridData.networkId+'.png)"></div></div>');
			$parent.append($footer);
			
			w++;
		}
		
		//CREATE MONTH TAB IF THERE ARE DATA IN CORRESPONDING WEEKS
		if(weeksCount > 0){
			$monthNav.append($navItem);			
		}
		
		$tabContainer.prepend($programming);	
		$('div.programmingArea').append($tabContainer);
	}
	
	
	$('#lTime,#rTime').append(tColum);
	$('#gridNetLogo').html('<img src='+logo+gridData.networkId+'.png>');


	//	WHEN IT IS A REGULAR GRIDS UPDATE


	//INITIALIZE GRIDS
	startUpGrids();
			
	//GETTING RATINGS
	callRatings();
	
	
	mapTimes();
	
}



function buildEmptyDays(x){
	
	var y = 0;
	var h = cellHeight/4;
		
	for(var hr=0; hr<timeFrame.length; hr++){	
		$programCell = $("<div>", {id:schedules[wks[wk]][d][hr].id, "class": "programCell", "style":"left:"+x+"px; top:"+y+"px;  height:"+h+"px;"});
		$cellText = $("<div>", {"class": "cellText"});
		$programCell.append($cellText);
		$programCell.append($airTimes);
		$divInner.append($programCell)
		y += h;	
	}
}




function timeRuler(){
	var hours;
	
	if(d){
		hours = getTimes(d.startTime,d.endTime);
	}
	else{
		hours = getTimes('06:00','23:59');
	}
	
	var timeCol = '';
	for(var i = 0; i< hours.length; i++){
		timeCol +=  '<div class="timeCell">'+hours[i].shortForm+'</div>';
	} 
	return timeCol;	
};


function getTimes(sTime,eTime,increment){
	var startA 	= String(sTime).split(':');
	var endA 	= String(eTime).split(':');
    var s 		= new Date(parseInt(2000),parseInt(0),parseInt(1),parseInt(startA[0]),parseInt(startA[1]));
    var e 		= new Date(parseInt(2000),parseInt(0),parseInt(1),parseInt(endA[0]),parseInt(endA[1]));
    var delta	= 30;
	var tList 	= [];
	var times;
	if(increment){
		delta = increment;
	}
	
	do {
		times = {};
		times.shortForm = s.toString("h:mm");
		times.meridian  = s.toString("hh:mm t");
		times.militar 	= s.toString("HH:mm");
		times.full 		= s.toString("HH:mm:ss");
		tList.push(times);
		s.setMinutes(s.getMinutes() + delta);
	} while (e > s);	
	
	return tList;
};


function daysOfWeek(w,gridData,isBottom){
	var daysBar = '';
	var hday;
	var days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
	var thisWeekDate;

	for(var j=0; j<7; j++){
		thisWeekDate = getMonday(gridData.startDate);
		hday = thisWeekDate.addDays((7*w)+j);
		daysBar += '<div class="headerCell">'+ days[hday.getDay()] + ' '+hday.getDate() +'</div>';
	}
	
	return daysBar;
};



function getMonday(d) {
    var starts 	= d.split(/[^0-9]/);
    var d 		= new Date(parseInt(starts[0]),parseInt(starts[1])-1,parseInt(starts[2]));	
	var day 	= d.getDay();	
	var diff 	= d.getDate() - day + (day == 0 ? -6:1); // adjust when day is sunday
	
	return new Date(d.setDate(diff));
};


function mapMonth(m){
	var r = ['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
	return r[m]
}


//UNGROUPED NAVIGATION BY WEEK
function collapseMonths(){    
	for(var i=1; i<=12; i++){    	
		$('#boxBody'+i+' .wkNavigator li').prop('tabindex',-1).removeClass('ui-tabs-active ui-state-active').attr('aria-selected',false).attr('aria-expanded',false).appendTo($('#boxBody0 .wkNavigator '));
		$('#boxBody'+i+' .parent').attr('aria-hidden',true).hide().appendTo($('#boxBody0'));
	}
	$('#monthNav').hide();
	$("#tabs,.topBorder,#tab-showcard").tabs("destroy").tabs(); 	
	return false;
};

//GROUPED BY MONTHS
function expandMonths(){
	for(var i=1; i<=12; i++){
		
		$('#boxBody0 .wkNavigator li.m'+i+'').prop('tabindex',-1).removeClass('ui-tabs-active ui-state-active').attr('aria-selected',false).attr('aria-expanded',false).appendTo($('#boxBody'+i+' .wkNavigator '));
		
		$('#boxBody0 .parent.m'+i).attr('aria-hidden',true).hide().appendTo($('#boxBody'+i));

		$('#boxBody'+i).first('div.parent').show();
	}

	$('#monthNav').show();
	$("#tabs,.topBorder,#tab-showcard").tabs("destroy").tabs(); 		
	
	
	//SHOW FIRST WEEK OF THE MONTH
	
	return false;
};



function mapTimes(episodeSTime,title){
	var t = timeFrame;
	var h = 0;
	var inc = cellHeight/4;
	for(var i=0; i<t.length; i++){
		if(t[i].full == episodeSTime){
			break;
		}
		h+=inc;
	}
	return h;
}