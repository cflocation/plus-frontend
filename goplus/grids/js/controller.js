	document.domain 	= "showseeker.com";	
	var sswin 			= window.opener;
	var apiUrl 	  		= sswin.apiUrl;
	var availableNet	= false;
	var currentContainer,posX, posY,selectedWeek,gridHeight,globalHeight,schedules,hourDiff,$blackout,timeFrame,timeSpan,tz;
	var logo 			= "https://d2k1589u5uya8b.cloudfront.net/images/networklogos/png/25/";
 	var params 			= sswin.solrSearchParamaters();
	var sdate 			= formatDates(params.startdate);
	var edate			= formatDates(params.enddate);
	var boxItems		= '';
	var cellHeight    	= 88;
	var d 				= {};
	var itemsTmp		= '';
	var printDate		= '';
	var proposallines 	= {};	
	var skedulesList 	= '';
	var scheduledPrograms='';
	var schedulesToDelete='';
	var selectedTab		= '0';
	var tdIds 			= '';
	var tilesGrayed 	= '';	
	var apiKey 			= sswin.apiKey;
	var station 		= params.networks[0].id;	
	var sTime 			= params.starttime;
	var eTime 			= params.endtime;
	var selectedNetwork = 0;
	var ratingsEnabled	= sswin.myEzRating;
	var xhrDescription 	= false;
	var currentDivId 	= 0;
	var container 		= '';
	var currentNew 		= '';
	var currentLive 	= '';
	var currentColor 	= '';
	var zmax 			= 100000;
	var x_ 				= 20;
	var y_ 				= 25;
	var ratingsOn 		= false;
	var showCardHeight	= 396;
	var hideShowCard	= sswin.userSettings.hideShowCards;
	var isZoneUpdate 	= false;
	var tmpStation		= 0;


	//GETTING PROGRAMMING
	searchProgramming();

	//SET TIME LIST
	setTimeList();

	//SETUP REGION
	loadmarkets();
	
	//SETUP DMAS
	setDmas();

	//SETUP ZONES
	setZones();

	//SET NETWORKS LIST	
	setNetworksList();
		
	//LOAD PROPOSALS
	getUserProposals();

	//SETUP CALENDAR
	setCalendars();

	//SHOWCARD SETTINGS
	showCardSettings();



	function checkShow($thisShow){
		if(!$thisShow.hasClass('fa-check-square')){
			$thisShow.removeClass('fa-square-o').addClass('fa-check-square');
			$thisShow.parents().eq(0).addClass('selectedShow');
		}//REMOVE SHOWS
		else{
			$thisShow.removeClass('fa-check-square').addClass('fa-square-o');
			$thisShow.parents().eq(0).removeClass('selectedShow');
		}		
	};


	// CLOSE SHOWCARD	
	function closeShowCard(){
		$('.programCell').removeClass('highlightedCell');
		try{$('#showcard').dialog('close');}catch(e){};
	};

	
	function resetCard(){
		$('.selectedEpisode').removeClass('selectedEpisode');
		$('.cellText').removeClass('highlightedCell');	
		$('.viewDescription').remove();
		$('.programTitle').removeClass('selectedEpisode');
	};	
	
	function resetEpisodeBoxes(){
		$('.showCardSmall').remove();
	}
	


	//CLOSE SETTINGS PANEL
	function closeSidePanel(){	
		$('div.top2').prepend($('#panel'));
		$('#panel').toggle();
		return false;
	};



	function getUserProposals(){
		var usr = sswin.userid;
		$.ajax({
			type:'get',
			url: apiUrl+"proposal/list",
			dataType:"json",
			headers:{"Api-Key":apiKey,"User":usr},
			success:function(data){
				var list = $("#proposalList");
				$.each(data, function(i, item){;
					list.append(new Option(item.name, item.id));
				});
				
				//  AUTO SELECT PROPOSAL IF THERE IS ONE  FROM SS+   // 
				if(parseInt(sswin.proposalid) !== 0){
					$("#proposalList").val(sswin.proposalid);
				}
			}
		});
	};
	

	
	 //CREATES INITIAL VIEW & VERIFIES WHETHER THERE IS A SELECTED PROPOSAL FROM SS+    //
	function initialState(proposallines){		 
		if(proposallines.length > 0){
			spotsLoad(proposallines);
			tabnum 			= 0;
			tabid  			= 'wkNavigation0';
			updateBackground(tabid,tabnum);
			setWeekNum(tabnum);
			selectedTab 	= tabnum;
			highlightTab();
		}
	};
	

	//POPULATES SELECTOR OF SPECIAL PROGRAMMING
	function populateSpecials(){
		$('#showTypes').html('');
		var options = [];
		var option;
		options.push({"id":"mvPremiere", "name":"Movie Premiere", "lblClass":""});
		options.push({"id":"onlyNew",	 "name":"New", "lblClass":"New"});
		options.push({"id":"seaPremiere","name":"Season Premiere", "lblClass":""});
		options.push({"id":"seaFinale",	 "name":"Season Finale", "lblClass":""});
		options.push({"id":"serPremiere","name":"Series Premiere", "lblClass":""});
		options.push({"id":"serFinale",	 "name":"Series Finale", "lblClass":""});
		options.push({"id":"sportsLive", "name":"Sports Live", "lblClass":"flagLive"});

		for(var i=0; i< options.length; i++){
			option = '<div><label class="'+options[i].lblClass+'" for='+options[i].id+'>';
			option +='<input type=checkbox id='+options[i].id+' class="multiSelect">';
			option +=options[i].name+'</label></div>';

			$('#showTypes').append(option);
		}
	};	


	// GET SOLR DATA
	function searchProgramming(){
		d.startDate = sdate.toString("yyyy-MM-dd");
		d.endDate 	= edate.toString("yyyy-MM-dd");
		d.startTime = sTime;
		d.endTime 	= eTime;
		d.networkId = station;
		d.timezone 	= sswin.timezone;
		d.includeProjected = false;
		timeFrame 	= getTimes(d.startTime,d.endTime,15);
		tz			= sswin.timezone;
		
		$.ajax({
	      type:'post',
			url: apiUrl+'solr/ezgrids',
			dataType:"json",
			headers:{"Api-Key":sswin.apiKey,"User":sswin.userid},
			contentType: 'application/json',
			data: JSON.stringify(d),
			success:function(r){
				tmpStation = station;
				schedules = r;
				processWeeks(schedules,d);
			}
		});	
	};


	 //  START AND END DATE CALENDARS
	function setCalendars(){	

		var dateToday  = new Date();
    		
			
		$( "#startDate" ).datepicker({
			defaultDate: "+0",
			changeMonth: false,
			numberOfMonths: 3,
			minDate: dateToday,			
			maxDate: "+56",		
			firstDay:1,	
			showTrailingWeek: false,
			showOtherMonths: true,
			selectOtherMonths:true,
			onSelect: function( selectedDate ) {
				setTimeout(function(){
   	        	$("#endDate").datepicker("show");
      	  	}, 16);  
			}
		});
		
		$( "#endDate" ).datepicker({
			defaultDate: "+56",
			changeMonth: false,
			numberOfMonths: 3,
			minDate: dateToday,	
			maxDate: "+61",
			firstDay:1,	
			showTrailingWeek: false,
			showOtherMonths: true,
			selectOtherMonths:true,
			onSelect: function( selectedDate ){
			}
		});			
		
		//populate the dates in the selectors
		autoSelectDates();
	};
	
	
	
	
	
	function setScroller(){
		var containerHeight = window.innerHeight - 250;
		$('div#innerbody').enscroll({showOnHover: true,verticalTrackClass: 'track3',verticalHandleClass: 'handle3'});
	};	
		

	function setWeekNum(panelNum){
		selectedPanel = "outerContainer"+String(panelNum);
		panelNumber = Number(panelNum);
	};
	



	//INITIAL PARAMETERS TAKEN FROM SS+   //
	function startUpGrids(){ 	
		$('.programTitle').nextAll('.flagLive,.episodeLive,br').remove();
		sswin = window.opener;  		 
		var	pslIdCheck; 	
		var userparams 			= sswin.solrSearchParamaters();	
		var initialZone 		= sswin.zoneid;	
		var zone 				= $('#zones').val();	
		var listOfNet 			= sswin.datagridNetworks.dataSet();
		selectedNetwork 		= d.networkId;

		proposallines 			= sswin.datagridProposal.dataSet();
		var loadedProposalId 	= sswin.proposalid;
		var selectedstation 	= new Array();
		var tabid  				= 'wkNavigation0';
		var tabnum				= 0;		
		
		try {
			$("#tabs,.topBorder,#tab-showcard").tabs("destroy").tabs();
		} catch (exception) {
			$("#tabs,.topBorder,#tab-showcard").tabs(); 
		}


		//AUTO POPULATE START AND END TIMES	
		$('#sTime').val(String(d.startTime).substr(0,5));
		$('#eTime').val(String(d.endTime).substr(0,5));	


		//TIME FRAME
		var sT 		= d.startTime.split(/[^0-9]/);
		var eT 		= d.endTime.split(/[^0-9]/);
		var sTime 	= new Date(2020,0,1,parseInt(sT[0]),parseInt(sT[1]),parseInt(sT[2]));
        var eTime 	= new Date(2020,0,1,parseInt(eT[0]),parseInt(eT[1]),parseInt(eT[2]));
		hourDiff	= Math.abs(eTime - sTime)/3600000;


		sswin.ezgridsOpen 	= true;
		selectedstation.push({'id': d.networkId});
		sswin.datagridNetworks['selectRowsFromData'];

		//AUTOPOPULATE NETWORK
		autoSlectnetwork(d.networkId);

			
		
		//HIGHLIGHTS WEEK TABS
		initialState(proposallines);
		sswin.datagridNetworks.selectRowsFromData(selectedstation);
	
	
		
		
		// HIDE EMPTY WEEKS
		$('.innerContainer').each(function(){			
			if($(this).text().length <=10){
				var week = $(this).closest('.parent').prop('id');
				week 		= week.replace('wk', 'wkNavigation');
				$('#'+week).hide();
			}
		});
	

		//HEIGH OF THE SCHEDULE CONTAINER
		resizeGrid();
		
		$('div#innerbody,.cellContainer').css({'height':gridHeight+'px'});					
		$("#tabs").css({'height':(gridHeight+160)+'px'});
		$("#tabs").css({'background-color':'#ffffff'});
		
		var divId;
		$('.Livesportsevent').each(function(i,val){
			divId = $(this).parent('div.programCell').prop('id');
			$(this).append('<br><span class="flagLive"> (Live)</span><br/><br/> <span class="episodeLive">'+$('#'+divId).data().epititle+'</span>');
		});
		
		//FIX LIVE NON SPORT EVENTS
		$('.Livesportsnon-event,.Livespecial,.Liveundefined').each(function(i,val){
			divId = $(this).parent('div.programCell').prop('id');
			$(this).append('<br><span class="flagLive"> (Live)</span>');
		});
	
	
		$('#lbCell,#callsigncorner').html($('#station option:selected').text());
	
	
		/// SHOWCARD SCROLLER
		/*var $scrollingDiv = $("#showcard");
		$(window).scroll(function(){$scrollingDiv.stop().animate({"marginTop":($(window).scrollTop())},"fast");});*/
	
		//LAZO FUNTION
		$("div#selectable").selectable({distance: 30});
		$('.timeCell').css({'height':cellHeight/2+'px','line-height':cellHeight/2+'px'});

		
		//SPECIAL SHOWS
		populateSpecials();
		
		//REMOVE LOADING OVERLAY
		$('#overlay').hide();

		$('#monthlyView').button();
		$('#btnErrorMsg').button();
		
		//BROKEN DOWN BY WEEK
		if(!$('#monthlyView').is(':checked')){
			collapseMonths();
		}

	};

	function showCardSettings(){

		$('#showcardOn').prop('checked',false).change();
		$('#showcardOff').prop('checked',true).change();
		
		if(!hideShowCard){
			$('#showcardOn').prop('checked',true).change();
			$('#showcardOff').prop('checked',false).change();
		}				
		$('#displayShowcard').buttonset();
		$('.ui-checkboxradio-icon').remove();
	};


	///SUBMIT REQUEST TO UPDATE THE GRID VIEW
	function updategrid(){
		$('#noprogramming').hide();
		if(parseInt($('#sTime').val().replace(':','')) >= parseInt($('#eTime').val().replace(':',''))){
			alert("Please check that the Start Time is lower than the End Time");
		}
		else{
			//closeSidePanel();
			//$('body').append($('#showcard').hide().html(''));
			$('#ezgridSchedules').empty().html('');
			closeShowCard();
			station = parseInt($('#station').val());
			var callsign = $("#station option:selected" ).text();		
			sTime 	= $('#sTime').val()+':00';
			eTime 	= $('#eTime').val()+':00';
			sdate	= new Date.parse($('#startDate').val()).toString('yyyy-MM-dd');
			edate	= new Date.parse($('#endDate').val()).toString('yyyy-MM-dd');			
			sswin.mixTrack("Grids - Update Grid",{"networkId":station, "callsign":callsign,"startTime":sTime,"endTime":eTime,"startDate":sdate,"endDate":edate,"zoneId":sswin.zoneid});

			$('#overlay').show();
			searchProgramming();
		}
		return false;
	}; 
	
	
	//RESET STATE OF THE CELLS IN THE GRID
	function resetCells(){
		$('.innerContainer input:checked').each(function(e){
			
			programDetail = String($(this).attr("name")).split("|");
			
			if(programDetail[3] =='(LIVE)' && programDetail[2] == 'Sports event'){
				$(this).closest('.programCell').css({'backgroundColor':'#ffffaa'});
			}
			else{
				$(this).closest('.programCell').css({'backgroundColor':'#ffffff'});							
			}
		});
		
		$('.innerContainer input:checkbox').removeAttr('checked');
	}
	
	
	 //  LOADS THE SPOTS IN THE GRID, GRAYING OUT CELLS  //
	function spotsLoad(proposallines){
		var $thisShow;
		for(i=0; i< proposallines.length; i++){
			$('#'+proposallines[i].solrId+'-'+proposallines[i].zoneId).find('.cellText').addClass('selectedShow');
			$('#'+proposallines[i].solrId+'-'+proposallines[i].zoneId).find('.cellText').find('.addShow').removeClass('fa-square-o').addClass('fa-check-square');
		}
		checkAllState();
	}
	
	
	
	//SETTING UP TIME SELECTORS
	function setTimeList(){
		$('#sTime,#eTime').html('');		
		var tL = getTimes('00:30','23:59');
				
		$('#sTime').append('<option value="00:00">12:00 A</option>');
		for(var t=0; t<tL.length; t++){
			$('#sTime,#eTime').append('<option value="'+tL[t].militar+'">'+tL[t].meridian+'</option>');
		}
		$('#eTime').append('<option value="23:59">11:59 P</option>');		
	};
	
	//SETTING UP NETWORKS SELECTORS	
	function setNetworksList(){
		$('#station').html('');
		var listOfNet 	= sswin.datagridNetworks.dataSet();
		//LOAD NETS IN SELECTOR
		$.each(listOfNet,function(i,net){
			if(i>0){
				$('#station').append($("<option></option>").attr("value", net.id).text(net.callsign));
			}
		});

		if(isZoneUpdate === true){//ZONE CHANGED FROM GRIDS
			autoSlectnetwork(station);
		}
	};
	
	function clearGrids(){
		$('#ezgridSchedules,#showcard').empty().html('');
	};
	

	
	//SELECT NETWORK AFTER GETTING GRID
	function autoSlectnetwork(id){
		var netId 		= parseInt(id);
		availableNet	= false;
		
		$('#station > option').each(function(i,net){
			if(parseInt(net.value) === netId){
				availableNet = true;
				return;
			}
		});
		
		if(availableNet){
			$('#station').val(netId);
			$('#notFound,#builder').hide();
			$('#boxBody,.wkNavigator').show();
			station = netId;
		}
		else{
			station = 0;
			$('#boxBody,#builder,.wkNavigator').hide();
		}	
		
		if(isZoneUpdate){	//ZONE CHANGED FROM GRIDS
			clearGrids();
			if(availableNet && station !== 0){
				updategrid();
			}
			else{
				alertMsg('Please select a network and click Update Grid.');
			}
			isZoneUpdate = false;
		}
	};


	//POPULATE DATES IN THE SELECTORS
	function autoSelectDates(){
		$("#startDate").datepicker("setDate", sdate);
		$("#endDate").datepicker("setDate", edate);
	};
	
	function formatDates(dDate){
		var starts = dDate.split(/[^0-9]/);
		return new Date(parseInt(starts[0]),parseInt(starts[1])-1,parseInt(starts[2]));
	};
	
	
	function resizeGrid(){
		var h;
		
		if(d){
			h = getTimes(d.startTime,d.endTime);
		}
		else{
			h = getTimes('06:00','23:59');
		}
			
		gridHeight 		= h.length*(cellHeight/2);
		globalHeight	= gridHeight;
		var windowH 	= window.innerHeight - 250;
		
		if(gridHeight > windowH){
			gridHeight = windowH;			
		}
		
		$('div#innerbody,.cellContainer').css({'height':gridHeight+'px'});					
		$("#tabs").css({'height':(gridHeight+160)+'px'});
		$("#tabs").css({'background-color':'#ffffff'});
	}
	
	function alertMsg(msg){
		var opt 		= {};
		opt.width 		= 380;
		opt.height		= 150;
		opt.resizable	= false;
		opt.modal 		= false;
		opt.draggable	= true;
		opt.title		= "ShowSeeker Plus Grids";
		opt.dialogClass = "pepper";
		opt.open 		= function(){$('#gridsMsg').text(msg)};
		$('#gridsMessages').dialog(opt);
	}
	
	function closeDialog(){
		$('#gridsMessages').dialog('destroy');
	}