function renderSparkline(cellNode, row, dataContext, colDef){	return $(cellNode).html(dataContext.titleFormat);	}function formatLines(data){	var jdata = [];	var t, lineWeeks, wi, weekId, lineActiveWk, spotId;		$.each(data.lines,function(i,z){		$.each(z.lines,function(j,l){			t  = formatProposalLine(l);			jdata.push(t);		});					});	return jdata;};function formatProposalLine(l){	var t       		= $.extend({}, l);	var lineActiveWk	= 0;	var lineWeeks		= l.weeks;			t.id        		= l.id;	t.ssid      		= l.solrId;	t.showid    		= l.showId;	t.programid 		= l.episodeId;	t.epititle  		= l.epiTitle;	t.genre     		= l.genre1;	t.desc      		= l.description;	t.desc60    		= l.description;	t.isnew     		= l.new	t.byDay				= Math.floor(parseInt(l.lineType)/4);		if(l.lineType ===1){	   t.linetype  = "Fixed";	} else if(parseInt(l.lineType) > 1){	   t.linetype  = "Rotator";	} else if(l.lineType ===3){	   t.linetype  = "Avail";	}	t.stationnum    	= l.stationId;	t.callsign      	= l.callSign;	t.titleFormat   	= l.title+"|"+l.epiTitle;       	t.startdatetime 	= l.startDate;	t.enddatetime   	= l.endDate;	t.starttime     	= l.startDate.split(' ')[1];	t.endtime       	= l.endDate.split(' ')[1];	t.dayFormat     	= schedulerDaysOfWeek(t.day);	t.startdate     	= l.startDate;	t.enddate       	= l.endDate;	t.zone          	= l.zoneName;	t.zoneid        	= l.zoneId;	t.ratevalue     	= l.rateCardValue;	t.statusFormat  	= l.live || l.premiere || l.new;	t.lineactive    	= l.active;	t.search        	= l.extra;	t.packageId     	= l.packageId;	t.notInSurvey  		= l.notInSurvey;	t.spotLength	 	= l.spotLength;	if(l.lineType === 3){	   t.search  		= "Avail";	}	t["_dirty"] 		= true;	t.callsignFormat 	= l.callSign+"|"; //TODO_ASIF	t.callsignFormat 	= l.callSign+"|"; //TODO_ASIF	t.stationname    	= l.callSign; //TODO_ASIF	t.weekIdMapping  	= {};	t.weeks = 0;	t.spots = 0;		if('rating' in l){	 for(var ii =0; ii<l.rating.length; ii++){		lineDemo = l.rating[ii].demo;		t['rating'+lineDemo] 		= l.rating[ii].rating;		t['share'+lineDemo] 		= l.rating[ii].share;		t['impressions'+lineDemo]		= l.rating[ii].impressions;		t['freq'+lineDemo] 			= l.rating[ii].freq;		t['CPM'+lineDemo] 			= l.rating[ii].CPM;		t['displayCpp'+lineDemo] 	= l.rating[ii].displayCpp;		t['gImps'+lineDemo] 		= l.rating[ii].gImps;		t['gRps'+lineDemo] 			= l.rating[ii].gRps;		t['reach'+lineDemo] 		= l.rating[ii].reach;		t['customRating'+lineDemo] 	= l.rating[ii].customRating;		t['minRepStd'+lineDemo] 	= l.rating[ii].meetsMinReportStandard;	 }	}		$.each(lineWeeks,function(k,w){		wi        	= w.week.split("-");		weekId    	= "w"+wi[1]+wi[2]+wi[0];		spotId		= "s"+wi[1]+wi[2]+wi[0];		t[spotId] 	= w.spots;				if(w.spot >= 0 && w.active === 1){			t.spots  += w.spot;			t[weekId] = String(w.spot);			t.weekIdMapping[weekId] = w.id;			if(w.spot > 0){				lineActiveWk++;			}		}		else if(parseInt(w.active) === 0){			t[weekId+'hide'] = String(w.spot);			t.weekIdMapping[weekId] = w.id;			t[weekId] 		 = String(0);			if(weeksdata.indexOf(weekId) === -1){				weeksdata.push(weekId);			}			if(lineWeeks.length === 1){				t.lineactive    = 0;				t.active    = 0;			}		}	});	if(lineWeeks.length === 1){		t.weekId = weekId;	}		if(lineActiveWk > 0){		t.spotsweek = parseInt(t.spots/lineActiveWk);	}	else{		t.spotsweek  = 1;	}		t.weeks = lineActiveWk;		t.total  = t.spots*t.rate;		return t;}function setProposalColumns(){    //set the columns	var cols = [	         {	         id: "lineType", 	         name: "LT", 	         field: "lineType", 	         sortable: true,	         width:30, 	         minWidth:30, 	         maxWidth:30,	         dynamic:0,		    formatter: Slick.Formatters.LineType	     }, 	         {	         id: "callsignFormat", 	         name: "Net", 	         field: "callsignFormat", 	         sortable: true,	         width:60, 	         minWidth:60, 	         maxWidth:60,	         dynamic:0,		    formatter: Slick.Formatters.Callsign	     },   	     {	         id: "titleFormat", 	         sortable: true,	         name: "Program Title", 	         field: "titleFormat",	         width:150, 	         minWidth:150,	         maxWidth:275,	         dynamic:0,	         formatter: Slick.Formatters.EPITitle,	         editor: Slick.Editors.LongText			 	     },	     {	         id: "search", 	         name: "Search Criteria", 	         sortable: true,	         field: "search", 	         width:140,	         minWidth:140,	         maxWidth:140,	         resizable: true	     },	     {	         id: "statusFormat", 	         name: "Status", 	         sortable: true,	         field: "statusFormat", 	         width:60, 	         minWidth:60, 	         maxWidth:100,	         formatter: Slick.Formatters.StatusIcons	     },	     {	         id: "day", 	         name: "Day", 	         field: "dayFormat", 	         sortable: true,	         width:60, 	         minWidth:60,	         maxWidth:60,	         dynamic:0	     },		     {	         id: "startdate", 	         name: "<center>Start<br>Date</center>",	         field: "startDate", 	         sortable: true,	         width:60, 	         minWidth:60, 	         maxWidth:60,	         dynamic:0,	    	headerCssClass:'centerHeader',	         formatter: Slick.Formatters.ShortFormatDate	         	     },	     {	         id: "enddate", 	         name: "<center>End<br>Date</center>", 	         field: "endDate", 	         sortable: true,	         width:60, 	         minWidth:60, 	         maxWidth:60,	         dynamic:0,	    	headerCssClass:'centerHeader',	         formatter: Slick.Formatters.ShortFormatDate	     },	     {	         id: "starttime", 	         name: "<center>Start<br>Time</center>", 	         field: "startdatetime", 	         sortable: true,	         width:50, 	         minWidth:50,	         maxWidth:50,	         dynamic:0,	    	headerCssClass:'centerHeader',	         formatter: Slick.Formatters.ShortFormatTime	     },	     {	         id: "endtime", 	         name: "<center>End<br>Time</center>", 	         field: "enddatetime", 	         sortable: true,	         width:50, 	         minWidth:50, 	         maxWidth:50,	         dynamic:0,	    	headerCssClass:'centerHeader',	         formatter: Slick.Formatters.ShortFormatEndTime	     },	     {			id: "weeks", 			name: "Wks", 			field: "weeks", 			sortable: true,			width:40, 			minWidth:40, 			maxWidth:40,			dynamic:0,			cssClass: "dynamicRight",			editor: Slick.Editors.IntegerNonZero,			formatter: Slick.Formatters.FormatRed	     },	     {			id: "spotsweek",			name: "Sp/Wk", 			field: "spotsweek", 			sortable: true,			width:48, 			minWidth:48, 			maxWidth:48,			dynamic:0,			editor: Slick.Editors.IntegerNonZero,			cssClass: "dynamicRight",				formatter: Slick.Formatters.FormatSpots	     },		{			id: "spotLength", 			name: "Sp/Ln", 			field: "spotLength", 			sortable: true,			width:48, 			minWidth:48, 			maxWidth:48,			dynamic:0,			cssClass: "dynamicRight"	     },	     {			id: "ratevalue", 			name: "Card", 			field: "ratevalue", 			sortable: true,			width:60, 			minWidth:60, 			maxWidth:60,			dynamic:0,			cssClass: "dynamicRight",			formatter: Slick.Formatters.Ratecard	     },	     {			id: "rate", 			name: "Rate", 			field: "rate", 			sortable: true,			width:60, 			minWidth:60, 			maxWidth:60,			dynamic:0,			editor: Slick.Editors.Float,			cssClass: "dynamicRight",			formatter: Slick.Formatters.Rates	     },	     {			id: "spots", 			name: "Spots", 			field: "spots", 			sortable: true,			width:50, 			minWidth:50, 			maxWidth:50,			dynamic:0,			cssClass: "dynamicRight"	     }	     ,	     {			id: "total", 			name: "Cost", 			field: "total", 			sortable: true,			width:65,			minWidth:65,			maxWidth:65,			dynamic:0,			sortable: true,			cssClass: "dynamicRight",			formatter: Slick.Formatters.TotalCost	     }];		     //gray out the ratecard column if they dont have them	     if(!ratecard){	         cols[11].cssClass = "noratecards";	     }	     return cols;}function recalculateProposalFightDates(){	var sDate 		= datagridProposal.getProposalStartDate();	var eDate 		= datagridProposal.getProposalEndDate();		var newCols 	= datagridProposal.deleteDynamicColumns();	var dynaCols 	= datagridProposal.buildDynamicColumns(sDate,eDate);	var c 			= newCols.concat(dynaCols);	datagridProposal.setGridColumns(c);	datagridProposal.setColumnsAlt();	return c;}