	var selectedPanel = "outerContainer0";
	var panelNumber   = 0;				
	var panelState = new Array(0,0,0,0,0,0,0,0,0); 
				

	$(".dropdown").on("click",".multiSelect",function(){
		if($(this).is(':checked')){
			selectAllCheckBoxes($(this).prop('id'));
		}
		else{
			unselectAllCheckBoxes($(this).prop('id'));			
		}
	});

	//SELECTS THE BOXES BY GROUP 
	function selectAllCheckBoxes(opt){
		if($('#proposalList').val() == 0 || $('#proposalList').val() == null || $('#proposalList').val() == ""){
			$('#selectAllBoxes').prop('checked',false);
			alertMsg("Please Create a Proposal before adding shows.");
		}
		else{			
			//var x 			= $('#'+selectedPanel+' .programCell');
			var x 			= $('.programCell');			
			var zoneid 		= $('#zones').val(); 
			var zonename 	= $('#zones option:selected').text();		
			var showid 		= [];
			var query, selector;
			switch(opt){
				case 'sportsLive':
					query 	= '.Livesportsevent';
					break;
				case 'seaFinale':	
					query 	= '.Season.Finale';
					break;
				case 'serFinale':
					query 	= '.Series.Finale';
					break;
				case 'mvPremiere':
					query 	= '.MoviePremiere';
					break;			
				case 'onlyNew':	
					query 	= '.New';
					break;
				case 'seaPremiere':
					query 	= '.Season.Premiere';
					break;
				case 'serPremiere':
					query 	= '.Series.Premiere'
					break;
				case 'all':
					query	= '.New,.Premiere,.Livesportsevent';
					break;
			}
				
			selector = $(x).children(query);
			selector.find('i').removeClass('fa-square-o').addClass('fa-check-square');
			$(x).children(query).addClass('selectedShow');

			for(var i=0; i<selector.length; i++){
				showid[i] = $(selector[i]).parents('.programCell').prop('id').split('-')[0];
			}
						
			try{
				if(showid.length > 0){
					sswin.mixTrack("Grids - Specials Add", {"type":opt,"showId":showid,"showsCount":showid.length,"proposalId":sswin.proposalid,"zoneName":zonename,"zoneId":zoneid});
					sswin.externalAddLineToProposal(showid,zonename,zoneid);
				}
			}
			catch(err){}
	
			panelState[panelNumber] = 1;
			
			highlightTab();
			checkAllState();
			
		}
		
	}

	function unselectAllCheckBoxes(opt){	
		var showid  	= [];
		var validIds	= [];
		var zoneid 		= $('#zones').val();
		var query, selector;
		switch(opt){
			case 'sportsLive':
				query 	= '.Livesportsevent';
				break;
			case 'seaFinale':	
				query 	= '.Season.Finale';
				break;
			case 'serFinale':
				query 	= '.Series.Finale';
				break;
			case 'mvPremiere':
				query 	= '.MoviePremiere';
				break;			
			case 'onlyNew':	
				query 	= '.New';
				break;
			case 'seaPremiere':
				query 	= '.Season.Premiere';
				break;
			case 'serPremiere':
				query 	= '.Series.Premiere'
				break;
			case 'all':
				query	= '.New,.Premiere,.Livesportsevent';
				break;
		}

		selector = $('.programCell').children(query);
		selector.find('i').removeClass('fa-check-square').addClass('fa-square-o');
		selector.removeClass('selectedShow');

		for(var i=0; i<selector.length; i++){
			showid[i] = $(selector[i]).parents('.programCell').prop('id');
		}

		for(var indx=0; indx<showid.length; indx++){
			if(showid[indx]){
				validIds.push(showid[indx]);
			}
		}
		
		try{
			if(validIds.length > 0){
				sswin.mixTrack("Grids - Specials Delete", {"type":opt,"showId":validIds,"showsCount":validIds.length,"proposalId":sswin.proposalid,"zoneId":zoneid});
				sswin.externalDeleteLineFromProposal(validIds);	
			}
		}
		catch(err){}
		
		panelState[panelNumber] = 0;

		highlightTab();		
			checkAllState();
	}



	function checkAllState(){
		var selector,selected;
		var specials = [];
		
		specials.push({'option':'sportsLive','classes':'.Livesportsevent'});
		specials.push({'option':'seaFinale','classes':'.Season.Finale'});
		specials.push({'option':'serFinale','classes':'.Series.Finale'});
		specials.push({'option':'mvPremiere','classes':'.MoviePremiere'});
		specials.push({'option':'onlyNew','classes':'.New'});
		specials.push({'option':'seaPremiere','classes':'.Season.Premiere'});
		specials.push({'option':'serPremiere','classes':'.Series.Premiere'});
		//specials.push({'all':'.New,.Premiere,.Livesportsevent'});	
		
		for(var i=0; i<specials.length;i++){
			selector = $('.programCell').children(specials[i].classes);
			sel = $('.programCell').children(specials[i].classes+'.selectedShow');
			if((selector.length === sel.length) && sel.length > 0){
				$('#'+specials[i].option).prop('checked', true);
			}
			else{
				$('#'+specials[i].option).prop('checked', false);
			}
		}
	};
				

	function updateCheckBoxState(){
		if(panelState[panelNumber] == 1){
			$('#selectAllBoxes').prop('checked',true);
		}
		else{
			$('#selectAllBoxes').prop('checked',false);					
		}
	};
	


	function bulkAdd(id,ctrl){
		
		if(sswin.proposalid !== 0){
			var addIds = [];
			var delIds = [];
			var arrays = [];
			var thisShow,showId;
			var zoneid 		= $('#zones').val();
			var zonename 	= $('#zones option:selected').text();	
			var toggle		= 'fa-square-o fa-check-square';		
			
			for(var i=0; i<id.length; i++){
				
				showId = id[i]+'-'+zoneid;
				
				if(!isInProposal(showId)){
					addIds.push(id[i]);
				}
				else{
					delIds.push(id[i]+'-'+zoneid);
				}
			}
			
			
			
			//TOGGLE STATE OF QUICK ADD BUTTONS			
			$('#'+ctrl).toggleClass('grayedoutButton');

			if(ctrl === 'showAll'){
				var allBtns = '#showPremieres,#showFinales,#showLive,#showNews';
				if($('#showAll').hasClass('grayedoutButton')){
					$(allBtns).addClass('grayedoutButton disabledButton');
				}
				else{
					$(allBtns).removeClass('grayedoutButton disabledButton');
				}
			}

			//ADD/REMOVE SHOWS
			if(addIds.length > 0){
				sswin.mixTrack("Grids - QuickAdd", {"type":ctrl,"showId":addIds,"showsCount":addIds.length,"proposalId":sswin.proposalid,"zoneName":zonename,"zoneId":zoneid});				
				sswin.externalAddLineToProposal(addIds,zonename,zoneid);
				arrays = addIds;
			}
			else{
				sswin.mixTrack("Grids - QuickDelete", {"type":ctrl,"showId":delIds,"showsCount":delIds.length,"proposalId":sswin.proposalid,"zoneId":zoneid});				
				sswin.externalDeleteLineFromProposal(delIds,zoneid);
				arrays = delIds;
			}
			
			//TOGGLE GRAYOUT
			for(var i=0; i<arrays.length; i++){
				showId = arrays[i].split('-');
				$thisShow 	= $('#'+showId[0]+'-'+zoneid).find('i.checkbox');
				$thisShow.toggleClass(toggle).parents().eq(0).toggleClass('selectedShow');
			}
			
			//HIGHLIGHT WEEKS
			highlightTab();

		}
		else{
			alertMsg('Please Create a Proposal before adding shows.');
		}
	};
	
	function isInProposal(id){
		var r 		= false;
		var line 	= sswin.datagridProposal.quickLineSearch(id);
		if(line.length>0){
			r = true;
		}
		return r;
	}