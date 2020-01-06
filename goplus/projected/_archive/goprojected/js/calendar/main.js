//document.domain = "showseeker.com";

var 	allShows 			= 0;
var 	filtering 			= new Array();
var		firstload			= true;
var 	liveEvents 			= [];
var 	loadedProposalId 	= 0;
var 	otherSportsLive		= 0;
var 	proposallines 		= [];
var		showid 				=  new Array();
var 	selectedsport 		= '';
var 	selectedOptions		= '';
var 	sportLiveEvents 	= ["NFL Football","NBA Basketball","NFL","NASCAR Racing","MLB Baseball","MLS Soccer","College Basketball","College Football","College Baseball","PGA Tour Golf"];
var 	mktzones			= [];
	
	
$(document).ready(function(){
	
	 //INITIAL PARAMETERS TAKEN FROM SS+ 	
	sswin = window.opener;  		 
	

	var initialZone 		= sswin.zoneid;	
	var loadedProposalId 	= sswin.proposalid;	
	var proposallines 		= sswin.datagridProposal.dataSet();	
	var	pslIdCheck;
	var showid_temp;
	var userparams 			= sswin.solrSearchParamaters();	
	var zone				= $('#zones').val();	
	    //mktzones			= sswin.marketzones;
	    mktzones			= sswin.allMarkets;
	
		
	sdate = String($('#startDate').val()).replace(/-/g,'/');
	edate = String($('#endDate').val()).replace(/-/g,'/');

	userproposals(sswin.guserid, loadedProposalId);
	
	initialState(proposallines);		
		
	setCalendars();			
	
	tbd();	
	
	loadmarkets();
	
	//GETTING PROPOSALS 

	getUserProposals();
	
	
	
	//GETTING  WIDTH PARAMETER FOR CELLS BASED ON THE SCREEN SIZE
	var cellW 		= parseInt($(window).width()/7);
	
	
	//SETS CELL WIDTH
	$('.show,.dayofWeek').css('width',cellW-13);
	

	//EVERYOTHER EFFECT IN GRID & SETS CELLS x COORDINATE IN THE GRID
	$('.show').each(function(idx){
		if(parseInt(idx/7)%2 != 0)
			$(this).css({'backgroundColor':'#eee'});

		if($('a', this).text() == '')
			$(this).css({'height':'15'});
	});
	
		
	// LIST OF FILTER OPTIONS
	$('.s1').dropdownchecklist();


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
	$('.SeasonFinale .schedule,.SeasonPremiere .schedule,.SeriesFinale .schedule,.SeriesPremiere .schedule').css({'color':'red'});
	

	$('.SeasonFinale .premiereflag,.SeasonPremiere .premiereflag,.SeriesFinale .premiereflag,.SeriesPremiere .premiereflag').each(function(){

		var thisProgram = $(this);
		if(String(thisProgram.text()).indexOf('1') != -1){
			//thisProgram.closest('.SeasonFinale,.SeasonPremiere,.SeriesFinale,.SeriesPremiere');
			thisProgram.closest('.SeasonFinale,.SeasonPremiere,.SeriesFinale,.SeriesPremiere').removeClass("SeasonFinale SeasonPremiere SeriesFinale SeriesPremiere").addClass("premiereprojected");
		}
		
		tmptxt = String(thisProgram.text()).replace('0','').replace('1','');
		$(this).text(tmptxt);
	})



	//SETS PROJECTED LABEL Events
	$('.pNew .externalprojected,.premiereprojected .externalprojected').prepend('<span class=projectedlabel>Projected</span>');

	$('.pNew .schedule').css({'color':'green'});

	$('.pNew .premiereflag').css({'color':'green'}).text('New Projected');

	$('.projected .programTitle, .projected .callsign, .projected .schedule, .projected .premiereflag').css({'color':'#333333'});

	$('.projected .premiereflag').text('');

	$('.pLive .schedule').css({'color':'#5801AF'});
	
	$('.pLive .premiereflag').css({'color':'#5801AF','font-weight':'700'}).text(' Projected');

	// GETS THE HIGHTEST CELL IN THE ROW AND MAKE THE OTHER THE SAME HEIGHT
	//updateCellHeight();


	

	// INITIAL PROGRAMMING VIEW   //
	var createdAT,updatedAT,d,txt;

	$('.createdRecord').each(function(){
		if($(this).text().trim() !== ''){
			createdAT = $(this).text().split(/[^0-9]/);
			txt = createdAT[6]+'-'+createdAT[7]+'-'+String(createdAT[5]).substr(2, 2);
						
			//d 	= new Date(createdAT[5],parseInt(createdAT[6])-1,createdAT[7]);
			//txt = addzero(d.getMonth()+1)+'-'+addzero(d.getDate())+'-'+String(d.getFullYear()).substr(2, 2);
			$(this).siblings('.updatedRecord').hide();
			$(this).text('Added: '+txt)
		}			
	});		
	
	$('.updatedRecord').each(function(){
		if($(this).text() != ''){
			updatedAT = $(this).text().split(/[^0-9]/);
			//d 	= new Date(updatedAT[5],parseInt(updatedAT[6])-1,updatedAT[7]);
			//txt = addzero(d.getMonth()+1)+'-'+addzero(d.getDate())+'-'+String(d.getFullYear()).substr(2, 2);
			txt = updatedAT[6]+'-'+updatedAT[7]+'-'+String(updatedAT[5]).substr(2, 2);			
			$(this).siblings('.createdRecord').hide();
			$(this).show();
			$(this).text('Updated: '+txt)
		}
	});	
	

	selectedfiler = $('input#t').val();
	
	
	switch (selectedfiler) {
	    case 'projected':		    		
			$('.pNew, .premiereprojected').show();
	    		break;
	    case 'premieres':
				$('.SeriesPremiere,.SeriesFinale,.SeasonFinale,.SeasonPremiere,.MoviePremiere').show();
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
	};
	
	/*
	$('.Live a.programTitle,.pLive a.programTitle').each(function(){
		liveEvents.push($(this).parent().parent().prop('id')+$(this).siblings('.callsign').text()+$(this).siblings('.schedule').children('.starttimeclass').text()+$(this).text());
	});	*/
	


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

});
	
		 	


	function getUserProposals() {
		var apiUrl = "https://plusapi.showseeker.com/";
		var usr = $('#userid').val();
		$.ajax({
			type:'get',
			url: apiUrl+"proposal/list",
			dataType:"json",
			headers:{"Api-Key":apiKey,"User":usr},
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
		
		