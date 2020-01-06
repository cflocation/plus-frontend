	
function main(){
	
	 //INITIAL PARAMETERS TAKEN FROM SS+ 	
	sswin = window.opener;  		 
	

	var initialZone 		= sswin.zoneid;	
	var loadedProposalId 	= sswin.proposalid;		
	var	pslIdCheck;
	var showid_temp;
	var userparams 			= sswin.solrSearchParamaters();	
	var zone				= $('#zones').val();	
	    mktzones			= sswin.marketzones;
	
		
	sdate = String($('#startDate').val()).replace(/-/g,'/');
	edate = String($('#endDate').val()).replace(/-/g,'/');
	
	insertProgramFilterList(t);
	zonenetworks();
	userproposals(sswin.guserid, loadedProposalId);
	setCalendars();			
	tbd();	
	zoneFilter();
	loadmarkets();
	
	//GETTING PROPOSALS 
	showTime();
	getUserProposals();
	
	$('.s1').dropdownchecklist();
	
	bcmonths();
	loadGrid();
	
	//GETTING  WIDTH PARAMETER FOR CELLS BASED ON THE SCREEN SIZE
	updateGrid();

};
	
		 	
	function getUserProposals() {
		var apiUrl = sswin.apiUrl;
		//var usr = $('#userid').val();
		$.ajax({
			type:'get',
			url: apiUrl+"proposal/list",
			dataType:"json",
			headers:{"Api-Key":apiKey,"User":userid},
			success:function(data){
				var list = $("#proposalList");
				$.each(data, function(i, item) {
					list.append(new Option(item.name, item.id));
				});

				$("#proposalList").val(sswin.proposalid);
			}
		});
	}
	

	  ////////////////////////////////////////////////////////////////////////////////////////////
	 // VERYFIES  IF ALL THE VISIBLE SHOWS ARE CHECKED TO MARK THE SELECT ALL CHECKBOX CONTROL //
	////////////////////////////////////////////////////////////////////////////////////////////
	
	
	function selectorState(){

		if($('input.ssevent[type=checkbox]:visible').length  ==  $('input.ssevent[type=checkbox]:visible:checked').length && $('input.ssevent[type=checkbox]:visible').length != 0){
	    	$('#select_all').prop('checked', true);
	    }
	    else{
	    	$('#select_all').prop('checked', false);
	    }
	    return true;
	}
	


	function debouncer(func, timeout) {
		var timeoutID = timeout || 200;
		return function() {
			var scope 	= this,
			args 	= arguments;
			clearTimeout(timeoutID);
			timeoutID = setTimeout(function() {
					func.apply(scope, Array.prototype.slice.call(args));
					}, timeout);
		};
	}	
		
		
		
	function addzero(n){
		return n<10? '0'+n:''+n;
	}
		
		
	function updateGrid(){
		var cellW 			= parseInt($(window).width()/7);	
		var thisProgram 	= '';			
		
		//SETS CELL WIDTH
		$('.show,.dayofWeek').css('width',cellW-13);
		
		//EVERYOTHER EFFECT IN GRID & SETS CELLS x COORDINATE IN THE GRID
		$('.show').each(function(idx){
		
			if(parseInt(idx/7)%2 != 0){
				$(this).css({'backgroundColor':'#eee'});
			}
		
			if($('a', this).text() == ''){
				$(this).css({'height':'15'});
			}
		});
	
	
		//SHOWS FIRST MONTH AT THE BEGINING & HIGHLIGTHS FIRST TAB
	
		$('#boxBody div.parent').hide();   		
	
		$('#boxBody div.parent:first').css({'display':'block'});		
		 
		$('.top:first').css({'backgroundColor':'blue'}); 		
	
		$('#waitingmsg').hide();		
	
		$('#boxBody').css({'visibility':'visible'});
	
	
		firstload = false;
	

		// COLOR CODES //
	
		//SETS COLOR PURPLE FOR Live Sports Events
		$('.Live .schedule').css('color','#5801AF');

	
		//SETS COLOR RED FOR PREMIRE Events
		$('.MoviePremiere .schedule,.SeasonFinale .schedule,.SeasonPremiere .schedule,.SeriesFinale .schedule,.SeriesPremiere .schedule').css({'color':'red'});
	
		$('.SeasonFinale .premiereflag,.SeasonPremiere .premiereflag,.SeriesFinale .premiereflag,.SeriesPremiere .premiereflag').each(function(){
	
			thisProgram = $(this);
			
			if(String(thisProgram.text()).indexOf('1') != -1){
				thisProgram.closest('.SeasonFinale,.SeasonPremiere,.SeriesFinale,.SeriesPremiere').removeClass("SeasonFinale SeasonPremiere SeriesFinale SeriesPremiere").addClass("premiereprojected");
			}
			
			tmptxt = String(thisProgram.text()).replace('0','').replace('1','');
			$(this).text(tmptxt);
		});



		//SETS PROJECTED LABEL Events
		$('.pNew .externalprojected,.premiereprojected .externalprojected,.MoviePremiere .externalprojected').prepend('<span class=projectedlabel>Projected</span>');
	
		$('.pNew .schedule').css({'color':'green'});
	
		$('.pNew .premiereflag').css({'color':'green'}).text('New Projected');
	
		$('.projected .programTitle, .projected .callsign, .projected .schedule, .projected .premiereflag').css({'color':'#333333'});
	
		$('.projected .premiereflag').text('');
	
		$('.pLive .schedule').css({'color':'#5801AF'});
		
		$('.pLive .premiereflag').css({'color':'#5801AF','font-weight':'700'}).text(' Projected');

		// GETS THE HIGHTEST CELL IN THE ROW AND MAKE THE OTHER THE SAME HEIGHT
		//updateCellHeight();


	

		// INITIAL PROGRAMMING VIEW   //
	
		$('.createdRecord').each(function(){
			if($(this).text().trim() != ''){
				var d 	= new Date.parse($(this).text());
				var txt = addzero(d.getMonth()+1)+'-'+addzero(d.getDate())+'-'+String(d.getFullYear()).substr(2, 2);
				$(this).siblings('.updatedRecord').hide();
				$(this).text('Added: '+txt)
			}			
		});		
		
		$('.updatedRecord').each(function(){
			if($(this).text().trim() != ''){
				var d 	= new Date.parse($(this).text());
				var txt = addzero(d.getMonth()+1)+'-'+addzero(d.getDate())+'-'+String(d.getFullYear()).substr(2, 2);
				$(this).siblings('.createdRecord').hide();
				$(this).show();
				$(this).text('Updated: '+txt)
			}
		});	
	
	
		switch (t) {
			case 'projected':		    		
				$('.pNew, .premiereprojected, .MoviePremiere').show();
				break;
			case 'premieres':
				$('.SeriesPremiere,.SeriesFinale,.SeasonFinale,.SeasonPremiere').show();
				$('.premiereprojected').hide();
				break;
			case 'packages':
				$('.packageflag').each(function(){
				if($(this).text() != "0")
					$(this).closest('.pLive').show();
				});
				break;
			case 'live':
				$('.Live').show();
				break;
			case 'all':
				$('.calendar-program').show();
				break;
			default:
				filterPrograms();
		};
	

	
		//CHECKING IF THE EVENTS DISPLAYED ARE PART OF THE PROPOSAL
		selectorState();

	
		//Toggle Episode Description (Game Details)
		$('.minus').hide();
		$('.minusPsl').hide();


		//resize window stuff
		$(window).resize(debouncer(function(e){
	
		  if(!firstload){
		    windowManager();
		  }
		}));


		//UPDATED ROW HEIGHTS
		updateCellHeight();

		higlightFilteredTab();
		
		//ON ZONE CHANGE SELECT THE MONTH IN VIEW
		if(!$.isEmptyObject(calendarMonth)){
			selectCalendarMonth(calendarMonth);
		}
		
	}