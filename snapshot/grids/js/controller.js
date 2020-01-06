
var isZoneUpdate 	= false;
$(document).ready(function() { 
	
	  //////////////////////////////////////////
	 //  INITIAL PARAMETERS TAKEN FROM SS+   //
	//////////////////////////////////////////

	sswin = window.opener;  		 
	
	var userparams 			= sswin.solrSearchParamaters();	
	var initialZone 		= sswin.zoneid;	
	var zone				= $('#zones').val();	
 	var params 				= sswin.solrSearchParamaters();	
	var proposallines 		= sswin.datagridProposal.dataSet();	
	var loadedProposalId 	= sswin.proposalid;
	var pslIdCheck; 
	var tabid  				= 'wkNavigation0';
	var tabnum				= 0;
	//station		 			= params.networks[0].id;	

	sswin.ezgridsOpen 		= true;


	var mynetworks = 	sswin.datagridNetworks['selectRowsFromData'];	


	$('#callsigncorner,#callsigncornerb').text($('#station option:selected').text());
	$('.Livesportsevent').closest('.programCell').css({'background-color':'#FFFFAA'});
	$('.Livesportsevent').css({'font-weight':'700','color':'#5801AF'});	
	$('.Livesportsnon-event').css({'font-weight':'700','color':'#000000'});
	

	//LOAD PROPOSALS
	getUserProposals();

	//SETUP REGION
	loadmarkets();
	
	//SETUP DMAS
	setDmas();
	
	//SETUP ZONES
	setZones();		
	
	setNetworksList(station);

	//--- SETS DATE RANGE
	sdate=$('#startDate').val();
	edate=$('#endDate').val();
	
	setCalendars();
	
	//--- HIGHLIGHTS WEEK TABS
	initialState(proposallines);
	selectFirstTab();	
	
	sswin.datagridNetworks.selectRowsFromData([{'id':station}]);


	 //   WEEK NAVIGATOR EFFECTS   ///
	$('.wkNavigator div').click(function(){         
		$('#boxBody div.parent').slideUp(0);                 
		$('#boxBody div.parent:eq(' + $('.wkNavigator > div').index(this) + ')').slideDown(0);
	 })
	
	$('#boxBody div.parent').slideUp(0);
	$('#boxBody div.parent:eq(0)').slideDown(0);
	


	// HIDE EMPTY WEEKS
	$('.innerContainer').each(function(){			
		if($(this).text().length <=13){
			var week  = $(this).closest('.parent').prop('id');
			week = week.replace('wk', 'wkNavigation');
			$('#'+week).hide();
		}
	});

	 //  TRIGGER OF A GRID UPDATE  ///	
	$('#updategrid').click(
		function(){
			updategrid()
		}
	);	



	 //  UPDATES TAB NAVIGATOR BACKGROUND   //
	$('.weekTab').click(function(e){
		tabnum = $('.wkNavigator > div').index(this);
		tabid  = $(this).attr('id');
		updateBackground(tabid,tabnum);
		setWeekNum(tabnum);
		selectedTab = tabnum;
		highlightTab();
	})
	
	
	 //   DOWNLOAD PDF GRID   //
	$('#printPdfGrid').click(function(){printGrid()});
	

	
	

	 //   ADD SHOWS TO THE PROPOSAL   //
	$('.showseekerprogram').change(function(e){
		if($('#proposalList').val() != 0 && $('#proposalList').val() != null){
		
			showid 		= new Array();
			showid[0] 	= $(this).attr('id');
			zoneid		= $('#zones').val();
			zonename		= $('#zones option:selected').text();



			if($(this).attr('checked') == 'checked'){
				//ADD SHOWS
				sswin.externalAddLineToProposal(showid,zonename,zoneid);
				$(this).closest('.programCell').css({'backgroundColor':'#ccc'});
			}
			else{
				//REMOVE SHOWS
				programDetail = String($(this).attr('name')).split("|");
				
				if(programDetail[3] =='Live' && programDetail[2] == 'sports event'){
					$(this).closest('.programCell').css({'backgroundColor':'#ffffaa'});
				}
				else{
					$(this).closest('.programCell').css({'backgroundColor':'#ffffff'});						
				}
				
				try{
					sswin.externalDeleteLineFromProposal(showid,zoneid);
				}
				catch(e){}
			}

		}
		else{
				$(this).prop('checked', false);
				alert("Please Select or Create a Proposal in order to complete this operation.");
				return;					
		}
	});
	



	 //   CREATES A NEW PROPOSAL   //
	$('#createproposal').click(function(e){
		
		newproposalname = $('#proposal').val();
	
		if(newproposalname != ''){
			//creates the proposal in SS+
			$('#createproposal').hide();
			$('#waitingmsg').show();
			$('proposal').val('');
						
			sswin.datagridProposal.emptyGrid();
			sswin.proposalCreateNew(newproposalname);
			
			resetCells();
			
			pslIdCheck = self.setInterval(function(){
				if(loadedProposalId != sswin.proposalid){
					loadedProposalId = sswin.proposalid;
					window.clearInterval(pslIdCheck);
					
				   $('#proposalList').append($('<option>', { 
				        value: loadedProposalId,
				        text : newproposalname
				    }));																	

					$('#proposalList').val(loadedProposalId).change();
					
					$('#waitingmsg').text('Ready !');
					$('#proposal').val('');
					
					setTimeout(function(){
						$('#waitingmsg').hide();
						$('#createproposal').show();
					}, 1500);
					

				}
			},1500);
		}
		else{
			alert('Proposal Name is required');
		}
	
	});
	


	 //  AUTO SELECT PROPOSAL IF THERE IS ONE  FROM SS+   // 
	$("#proposalList").attr("value", sswin.proposalid);


	$("#proposalList").change(function(e){
		if($(this).val() != 0){
			sswin.loadProposalFromServer($(this).val());

			var zoneid	= $('#zones').val();			
			window.opener.blur();
			resetCells();

			pslIdCheck = self.setInterval(function(){

				if(sswin.datagridProposal.dataSet().length != proposallines.length){
					window.opener.blur();
					window.clearInterval(pslIdCheck);
					loadedProposalId = sswin.proposalid;
					proposallines 		= sswin.datagridProposal.dataSet();
		
					if(proposallines[0] != undefined){
						if(proposallines[0].zoneid != zoneid){//IF SELECTED PROPOSAL INCLUDES DIFFERENT ZONES THAN THE ONE OBSERVED
							$.when(autoSelectMarketAndZone(proposallines[0].zoneid)).then($('#ezgridsform').submit());
						}
					}
					spotsLoad(proposallines);
					updateBackground(tabid,tabnum);
					highlightTab();	
					window.opener.blur();
				}
			},1000);			
			
		}
	});
		
			
});
	
	


function getUserProposals() {
	var apiUrl = sswin.apiUrl;
	var usr = $('#userid').val();
	$.ajax({
		type:'get',
		url: apiUrl+"snapshot/list",
		dataType:"json",
		headers:{"Api-Key":apiKey,"User":usr},
		success:function(data){
			var list = $("#proposalList");
			$.each(data, function(i, item) {
				$('#proposalList').append($('<option>', {value:item.id, text:item.name}));
			});
			$("#proposalList").attr("value", sswin.proposalid);
		}
	});
}

	
	
	
	///SUBMIT REQUEST TO UPDATE THE GRID VIEW
	function updategrid(){
		if(parseInt($('#sTime').val().replace(':','')) >= parseInt($('#eTime').val().replace(':',''))){
			alert("Please check that the Start Time is lower than the End Time")
			return;
		}
		sswin.datagridNetworks.selectRowsFromData([{'id':$('#station').val()}]);
		$('#ezgridsform').submit();
	} 	 
	
	
	
	 //  CREATES INITIAL VIEW & VERIFIES WHETHER THERE IS A SELECTED PROPOSAL FROM SS+    //
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
	}	

	 //  START AND END DATE CALENDARS   //
	function setCalendars(){	

		var dateToday = new Date();
		var sDate = new Date();
		sDate.setDate(dateToday.getDate()-180);
		
        var minDate = dateToday;
        minDate.setDate(dateToday.getDate()-1096); 

		var eDate = new Date();    
		eDate.setDate(dateToday.getDate()-1);		
			
		$( "#startDate" ).datepicker({
			defaultDate: "+0",
			changeMonth: false,
			numberOfMonths: 3,
			minDate: minDate,
			maxDate: new Date(),
			onSelect: function( selectedDate ) {
				setTimeout(function(){
   	        	$("#endDate").datepicker("show");
      	  	}, 16);  
			}
		});
		
		
		$( "#endDate" ).datepicker({
			defaultDate: "+0",
			changeMonth: false,
			numberOfMonths: 3,
			minDate: minDate,	
			maxDate: new Date(),
			onSelect: function( selectedDate ){
			}
		});			
		
		//populate the dates in the selectors
		$("#startDate").datepicker("setDate", sdate);
		$("#endDate").datepicker("setDate", edate);
			
		
		setCalendarType();		
	}
	
	
	function setCalendarType(){
		var type = $('input:radio[name=calendar-mode-selector]:checked', sswin.document).val();
		
		if(type == "broadcast"){
			$("#startDate").datepicker("option", "firstDay", 1 );
			$("#startDate").datepicker( "option", "showTrailingWeek", false );
			$("#startDate").datepicker( "option", "showOtherMonths", true );
			$("#startDate").datepicker( "option", "selectOtherMonths", true );
			
			$("#endDate").datepicker("option", "firstDay", 1 );
			$("#endDate").datepicker( "option", "showTrailingWeek", false );
			$("#endDate").datepicker( "option", "showOtherMonths", true );
			$("#endDate").datepicker( "option", "selectOtherMonths", true );
			
		}else{
			$("#startDate").datepicker("option", "firstDay", 0 );
			$("#startDate").datepicker( "option", "showTrailingWeek", true );
			$("#startDate").datepicker( "option", "showOtherMonths", false );
			$("#startDate").datepicker( "option", "selectOtherMonths", false );
			
			$("#endDate").datepicker("option", "firstDay", 0 );
			$("#endDate").datepicker( "option", "showTrailingWeek", true );
			$("#endDate").datepicker( "option", "showOtherMonths", false );
			$("#endDate").datepicker( "option", "selectOtherMonths", false );
		}
	}
	
	
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
		var zn = $('#zones').val();
		for(i=0; i< proposallines.length; i++){
			$('#'+proposallines[i].id).prop('checked', true);
			$('#'+proposallines[i].id).closest('.programCell').css({'backgroundColor':'#ccc'});
		}
	}
	
	function zoneSynch(){
		zone		= $('#zones').val();	
		mktid		= $('#usrmarkets').val()
		//sswin.synchMarketAndZone(mktid,zone);
		sswin.zoneSelected('yes');
		return true;
	}		
	
	
	
	//SETTING UP NETWORKS SELECTORS	
	function setNetworksList(station){
		$('#station').html('');
		var listOfNet 	= sswin.datagridNetworks.dataSet();
		//LOAD NETS IN SELECTOR
		$.each(listOfNet,function(i,net){
			if(i>0){
				$('#station').append($("<option></option>").attr("value", net.id).text(net.callsign));
			}
		});
		
		//if(isZoneUpdate === true){//ZONE CHANGED FROM GRIDS
			autoSlectnetwork(station);
		//}
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
	
	
	
		if(!availableNet){
				alert('Please select a network and click Update Grid.');
			}
	
		if(isZoneUpdate){	//ZONE CHANGED FROM GRIDS
			clearGrids();
			if(availableNet){
				updategrid();
			}
			isZoneUpdate = false;
		}
		

	};	
	
	//CLOSE SETTINGS PANEL
	function closeSidePanel(){	
		$('div.top').prepend($('#panel'));
		$('#panel').toggle();
		return false;
	};	
	
	
	
	
	function alertMsg(msg){
		var opt 		= {};
		opt.width 		= 380;
		opt.height		= 150;
		opt.resizable	= false;
		opt.modal 		= false;
		opt.draggable	= true;
		opt.title		= "SnapShot Grids";
		opt.dialogClass = "pepper";
		opt.open 		= function(){$('#gridsMsg').text(msg)};
		$('#gridsMessages').dialog(opt);
	}