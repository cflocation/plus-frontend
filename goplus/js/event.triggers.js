$(document).ready(function(){		//CLEAR SEARCH RESULTS	$('#clear-search-results-btn').click(function(){		dialogClearSearch();	});	//EDIT SPOTS FROM OVERLAY	$('#edit-spots-btn').click(function(){		dialogEditSpots()	});		//EDIT TITLES FROM OVERLAY	$('#edit-line-title-btn').click(function(){		dialogEditTitle();	});		//EDIT RATES FROM OVERLAY	$('#edit-line-rate-btn').click(function(){		dialogEditRates();	});	//APPLY RATE CARD VALUES	$('#apply-rc-btn').click(function(){		datagridProposal.applyRateCard();		needSaving=true;	});	//DELETE PROPOSAL LINES	$('#delete-proposal-lines-btn').click(function(){		dialogDeleteLines();	});		//INPORT MESSAGES	$('#usr-messages').click(function(){		dialogMessages();	});		//SHARE SEARCHES	$('#share-searches-btn').click(function(){		dialogShareSearch();	});		//TITLES BY ZONE	$('#titles-byzone-btn').click(function(){		dialogTitle(3);	});			//ALL TITLES	$('#all-titles-btn').click(function(){		dialogTitle(3);	});				//SHOWSEEKER SEARCH BUTTON	$('#ShowSeeker,#search-decades-button').click(function(){		searchShowSeeker();	});				//DEMOGRAPHICS	$('#more-demographics,#more-demographics2').click(function(){		dialogDemographics();	});		// DECADES FOR MOVIES	$('#showtype-movies').change(function(){		if(!$(this).is(':checked')){			resetMoviesFilter();				}		else{			dialogDecades();					}	});		$('#reset-decades-button').click(function(){		if($('#decade-options').is(':visible')){			$('#decade-options').val('1930 TO 2019');			$("#year-options").val("");		}		else{			$("#year-options").val("all");			$("#decade-options").val("");		}	});			//NETWORKS BY DEMOGRAPHICS	$('#demographics-options').change(function(){		filterNetsByDemo();	});		$('#label-bc-cal').click(function(){		toggleTotalsView(true,'std');		$(this).hide();		$('#label-sc-cal').show();		$('#standard').prop('checked',true);		$('#broadcast').prop('checked',false);		$('#calendar-mode').buttonset("refresh");		$.when(setCalendarType()).then(sizingTotalsBar());	});		$('#label-sc-cal').click(function(){		toggleTotalsView(true,'bc');		$(this).hide();		$('#label-bc-cal').show();		$('#broadcast').prop('checked',true);		$('#standard').prop('checked',false);				$('#calendar-mode').buttonset("refresh");		$.when(setCalendarType()).then(sizingTotalsBar());	});		$('#reset-titles-filter').click(function(){		titlesResetList();		$('#dialog-title-search-btn').show();		$('#dialog-title-save-bt').show();		$('#reset-titles-filter').show();						datagridTitlesSelected.empty();	});	$('#reset-genres-filter').click(function(){		datagridGenresSelected.emptyGrid();		genresResetList();	});			$('#snapShotGrids').click(function(){		window.location = '/snapshot';	});		$('#dma-selector').change(function(){		filterZones();		setSelectedmarket();		try{			var mixData = { "prevDMA":userSettings.lastDMAId ,							"currentDMA":$('#dma-selector').val()};			usrIp("Plus DMA Select",mixData);		}catch(e){}	});	$('#customcolumnsbtn').click(function(){		dialogToggleColumns();	});		$('#zoneSearh').on('click',function(){		dialogZones();	});	    $('#filterGridShows').on('search',function(){        filteringShowsList('');    })	});