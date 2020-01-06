var callAfterRatingsUpdate = null;

function activateAddCustomDemo(){
	var dm = currentDemoSelection();
	var demoData 	= dm.demo;
	var newFavDemo	= dm.fav;

	// AVOID DUPES
	if(verifyDemoParams() && countSavedDemos() < 10 && r.indexOf(newFavDemo) === -1){
		$('#add-custom-demos').removeClass('disabledIcon').addClass('hander').prop('disabled', false).button('refresh');
	}
	else{
		$('#add-custom-demos').addClass('disabledIcon').removeClass('hander').prop('disabled', true).button('refresh');	
	}
	return false;
};

function addDemo(){
	if(!$('#demoWizard2').is(':visible')){
		$('#demoWizard2,#btn-del-demo').show();
		return;
	}
	if(!$('#demoWizard3').is(':visible')){
		$('#demoWizard3').show();
	}
	
};

function addCustomDemo(name){
	var dm 			= currentDemoSelection();
	var demoData 	= dm.demo;
	var newFavDemo	= dm.fav;
	var thisURL		= apiUrl+"ezratings/customdemographic";
	
	// AVOID DUPES
	if(r.indexOf(newFavDemo) === -1){
		
		$.ajax({
	        type: 	'post',
	        url: 	thisURL,
	        dataType: "json",
	        headers: {"Api-Key":apiKey,"User":userid},
	        processData: false,
	        contentType: 'application/json',
		    data: JSON.stringify(demoData),
	        success:function(resp){
				$('#dialog-window').dialog('destroy');
				showCustomDemos(resp.result);
				additionalDemoStatus();	
				activateAddCustomDemo();
	        },
	        error:function(resp){
				errorLog('Add Custom Demographic','',resp,thisURL);
	        }
	    });
	}
};

function currentDemoSelection(){
	var demoData 	= {};
	var demoGroup 	= parseInt($('#demoGroup').val());
	var fixedGroups = [4,5,6];
	var demoRef  	= {'1':'P','2':'F','3':'M','4':'MHH','5':'HH','6':'WW','7':'C'};
	var newFavDemo;
	var result = {};
	
	demoData.name 			= '';
	demoData.demoGroupId 	= demoGroup;

	var r = readFavDemos();

	if(fixedGroups.indexOf(demoGroup) === -1){
		demoData.ageFrom = $('#demoAgeFrom').val();
		demoData.ageTo 	 = $('#demoAgeTo').val();
		newFavDemo = demoRef[$('#demoGroup').val()]+","+demoData.ageFrom+","+demoData.ageTo;
	}
	else{
		demoData.ageFrom = 0;
		demoData.ageTo   = 0;		
		newFavDemo = demoRef[$('#demoGroup').val()];
	}	
	result.demo = demoData;
	result.fav =  newFavDemo;
	return result;
};




function addDayPart(){
	if(! $('#addDayPart').is(':visible')){
		$('#addDayPart').toggle();
	}
};



function additionalDemoStatus(){
	var selectedDemos = myEzRating.getRatings('demos');
	
	if(selectedDemos.length >=3){//DISABLE ADDITIONAL DEMOS
		$('#addDemo').prop('disabled',true).removeClass('hander btn-green').addClass('btn-light-grey');
		
		$('.favDemo').each(function(i,item){
			if(!$(item).is(':checked')){
				$(item).prop('disabled',true).removeClass('hander');
				$(item).parent().siblings('label.demoName').removeClass('hander').addClass('disabledDiv');
			}

		});
	}
	else{
		$('#addDemo').prop('disabled',false).addClass('hander btn-green').removeClass('btn-light-grey');
		$('.favDemo').prop('disabled',false).addClass('hander');
		$('.favDemo').parent().siblings('label.demoName').removeClass('disabledDiv').addClass('hander');
		$('.demoRow').prop('disabled',false);
	}
	return true;
};


function applyStyle(){
	$('#btn-add-demo,#btn-del-demo,#btn-add-daypart,#btn-edit-daypart,#btn-del-daypart,#addDemoBtn').button();
	$('#dialog-ratings').css('overflow','hidden');	
};



function autoSelectBook(bks){
	var bookIds 	= [];
	var Ls 			= {1: 'LO', 2:'LS', 3:'L1', 4:'L3', 5:'L7'};
	
	for(var b=0; b<bks.length;b++){
		bookIds.push(bks[b].id);
	}

	if(bks.length > 0){
		var multiRow 		= {};		
		multiRow.type		= bks[0].type;
		multiRow.live		= Ls[bks[0].kind];
		multiRow.service	= bks[0].service;		
		datagridSurveys.set('multiRow',multiRow);
	}

	datagridSurveys.setSelectRowByIds(bookIds);	
	datagridSurveys.resizeCanvas();	
	return false;
};


function buildRatingsPopup(){
	var thisURL = apiUrl+"ezratings/lists";
    $.ajax({
        type:'get',
        url: thisURL,
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        processData: false,
        contentType: 'application/json',
        success:function(resp){
	        
			var lbl 	= '';
			var mId 	= 0;
			var books 	= [];			
		    demoGroups 	= resp.demoGroups;
	        
	        //CUSTOM DEMOS
			myEzRating.set('userDemos',resp.customDemos.length);     
			showCustomDemos(resp.customDemos);
			loadDemoGroups();
			loadDemos(); 	   
			
	        //INITIAL RATINGS OPTION
			loadRatingOptions(myEzRating.get('ratingsData'));

			//LOAD RATINGS PARAMS IF THEY ARE  ALREADY SET
			if(myEzRating.getRatingsSettings('marketId') !== undefined){
				mId 	= myEzRating.getRatingsSettings('marketId');
			}

			if(myEzRating.getRatingsSettings('books') !== undefined){
				books 	= myEzRating.getRatingsSettings('books');				
			}

			if(mId !== 0 && books.length > 0){
				loadMarket(mId,books);
			}
			else{
		        ratingsZoneSynch($('#zone-selector').val());
	        }
        },
        error:function(resp){
			errorLog('Ratings Lists','',resp,thisURL);
        }
    });
};



function clearDemos(){
	myEzRating.setRatings('demos',[]);
};


function closeRatingsWindow(){
	$('#dialog-ratings').close();	
};


function countSavedDemos(){
	return $('.demoRow').length;
};

function deleteDayPart(){
	$('#addDayPart').toggle();
};


function dupeDemo(sDemo){
	var re = false;
		
	var d = myEzRating.ratingsData.demos;
	if(d){
		for(var j =0; j<d.length; j++){
			if('ageFrom' in d[j] && 'ageFrom' in sDemo){
				if(d[j].name === sDemo.name && d[j].ageFrom === String(sDemo.ageFrom) && d[j].ageTo === String(sDemo.ageTo)){
					re = true;
					break;
				}
			}
			else if(String(d[j].name) === String(sDemo.name) && !('ageFrom' in d[j]) && !('ageFrom' in sDemo)){
				re = true;
				break;					
			}
		}	
	}
	
	return re;
};


function editDayPart(){
	$('#addDayPart').toggle();
};


function findBook(bookId){
	var r = 0;
	$('#booksList > option').each(function(){
		if($(this).value === bookId){
			r = 1;
			return false;
		}
	});
	return r;
};

function fixDemos(d){
	var c = [];
	var demo, ages;

	if(d){
		for(i = 0; i < d.length; i++){
			cols 		= {};
			demo = d[i].split(' ');
			
			cols.name = demo[0];
			
			if(demo.length > 1){
				ages = demo[1].split('-');
				cols.ageFrom = ages[0];
				cols.ageTo   = ages[1];
			}
			
			c.push(cols);
		}
	}
	return c;
};

function fixSummary(event, ui){
	$('#row-demos,#row-survey').removeClass('activeTab');
	
	switch(ui.index){
		case 0:
			$('#row-survey').addClass('activeTab');
			$('#submitRatings,#saveRatingsParams,#summaryBack').hide();
			$('.demoPipe').removeClass('demoSeparator');
			$('#summaryNext').show();
			break;
		case 1:
			$('#submitRatings,#saveRatingsParams').prop('disabled',true);
			$('#summaryBack').show()
			$('#submitRatings').show();
			$('#saveRatingsParams').show();
			$('#summaryNext').hide();
			$('#row-demos').addClass('activeTab');			
			$('.demoPipe').addClass('demoSeparator');			
			break;
		case 2:		
			$('#submitRatings,#saveRatingsParams').prop('disabled',true);
			$('#summaryBack').show()
			$('#submitRatings').show();
			$('#saveRatingsParams').hide();
			$('#summaryNext').hide();
			$('#row-demos').addClass('activeTab');			
			$('.demoPipe').addClass('demoSeparator');			
			break;
	}
	toggleSubmitRatings();
	setTimeout(function(){datagridSurveys.resizeCanvas()}, 15);
	
};


function toggleSubmitRatings(){
	var allSet = validateRatingsParams();
	if(allSet.valid){
		$('#submitRatings,#saveRatingsParams').prop('disabled',false);
	}
	else{
		$('#submitRatings,#saveRatingsParams').prop('disabled',true);		
	}
	
	$('#submitRatings,#saveRatingsParams').button('refresh');	
};


function formatDemos(demos){
	var d;
	
	if(demos){
		d =  demos;
	}
	else{
		d =  myEzRating.getRatings('demos');		
	}

	var c = [];	
	if(d){
		if(d){
			for(i=0; i< d.length; i++){
				cols 		= {};
				demo 		= d[i].name;
				cols.name 	= demo;
				
				if('ageFrom' in d[i]){
					demo = demo+' '+ d[i].ageFrom+'-'+d[i].ageTo;			
					cols.name = demo;
				}
		
				cols.id 	= demo
				cols.field 	= demo;
				c.push(cols);	
			}	
		}
	}	
	return c;
};

function getProposalLineRatings(){
	
	var searchedLines 	= datagridProposal.selectedRows();
	var thisURL			= apiUrl+"proposal/line/resetmanualrating";
	var ratingLines 	= [];
	
	if(searchedLines.length > 0){
		for(i = 0; i < searchedLines.length; i++){
			ratingLines.push(searchedLines[i].id);
		}
		
		var data = {};
		
		data.lineIds 	= ratingLines;
	
		$('#gettingRatingsFlag').show();
		$('#ratingsIcon').hide();
		
	    $.ajax({
	        type:'post',
	        url: thisURL,
	        dataType:"json",
	        headers:{"Api-Key":apiKey,"User":userid},
	        processData: false,
	        contentType: 'application/json',
		    data: JSON.stringify(data),
	        success:function(resp){
		       datagridProposal.updateRatingTotals(resp);
				$('#gettingRatingsFlag').hide();
				$('#ratingsIcon').show();
	        },
	        error:function(resp){
				$('#gettingRatingsFlag').hide();
				$('#ratingsIcon').show();
				errorLog('Reset Manual Rating',data,resp,thisURL);
	        }
	    });
	}
	else{
		return false;
	}
};


function getRatings(lns){
	var ratingLines 	= [];
	var demos 			= [];
	var data 			= {};
	var area, demo, i, lines, searchedLines;

	searchRatingsComplete = false;
	
	if(!lns){
		loadDialogWindow('loading', 'ShowSeeker Plus', 450, 180, 1);
		searchedLines = datagridProposal.selectedRows();
	}
	else{
		searchedLines = lns;
	}
	
	var l = searchedLines.length;
	var d = myEzRating.ratingsData.demos;


	for(i = 0; i < l; i++){
		lines 				= {};
		lines.id 			= searchedLines[i].ssid;
		lines.networkId 	= searchedLines[i].stationnum;
		lines.startDateTime = String(searchedLines[i].startdatetime).replace(/\//g, '-');
		lines.endDateTime 	= String(searchedLines[i].enddatetime).replace(/\//g, '-');
		ratingLines.push(lines);
	}

	for(i=0; i< d.length; i++){
		demo = d[i].name;
		if(d[i].ageFrom){
			demo += ' '+ d[i].ageFrom+'-'+d[i].ageTo;			
		}
		demos.push(demo);
	}

	
	data.area = 'DMA';
	if(myEzRating.ratingsData.dma !== true){
		data.area = 'CDMA';
	}
	
	data.demographicArea = [];

	if(myEzRating.ratingsData.dma){
		data.demographicArea.push('1');
	}
	else if(myEzRating.ratingsData.cdma){
		data.demographicArea.push('2');
	}	
	
	data.lines 			= ratingLines;
	data.books 			= myEzRating.ratingsData.books;
	data.demos  		= demos;
	data.projection  	= myEzRating.ratingsData.project;
	data.average  		= myEzRating.ratingsData.average;
	data.demographics	= demos;
	data.impressions	= myEzRating.ratingsData.impressions;
	data.market			= myEzRating.ratingsData.marketId;
	data.ratings		= myEzRating.ratingsData.ratings;
	data.rounded		= myEzRating.ratingsData.rounded;
	

	var proposalSettings				= deepClone(myEzRating.get('ratingsData'));		
	data.ratingsSettings 				= {};
	data.ratingsSettings.average 		= proposalSettings.average;
	data.ratingsSettings.books 			= proposalSettings.books;
	data.ratingsSettings.demographics 	= demos;
	data.ratingsSettings.impressions 	= proposalSettings.impressions;
	data.ratingsSettings.market			= proposalSettings.marketId;
	data.ratingsSettings.projection		= proposalSettings.project;
	data.ratingsSettings.ratings 		= proposalSettings.ratings;
	data.ratingsSettings.rounded		= parseInt(proposalSettings.rounded);
	rndDecimalPlaces					= parseInt(proposalSettings.rounded);

	data.ratingsSettings.demographicArea = [];

	if(proposalSettings.dma){
		data.ratingsSettings.demographicArea.push('1');
	}	

	if(proposalSettings.cdma){
		data.ratingsSettings.demographicArea.push('2');
	}
	
	$('#gettingRatingsFlag').show();
	$('#ratingsIcon').hide();
	
	var thisURL = apiUrl+"ezratings/getratings";
	
    $.ajax({
        type:'post',
        url: thisURL,
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        processData: false,
        contentType: 'application/json',
		data: JSON.stringify(data),
        success:function(resp){
	        if($.isArray(resp)){
				for(var r = 0; r< searchedLines.length; r++){
					for(var idx = 0; idx < resp.length; idx++){
						
						if(resp[idx].line === searchedLines[r].ssid){
						
							for(var j =0; j < resp[idx].ratings.length ;j++){
								colName = String(resp[idx].ratings[j].demo);							
								searchedLines[r]['ratings'+colName] 	= resp[idx].ratings[j].rating;
								searchedLines[r]['share'+colName] 		= resp[idx].ratings[j].share;
								searchedLines[r]['impressions'+colName] = resp[idx].ratings[j].impressions;
								searchedLines[r]['demo'] 				= colName;
								searchedLines[r]['gRps'+colName] 		= resp[idx].ratings[j].gRps;
								searchedLines[r]['cume'+colName] 		= resp[idx].ratings[j].cume;
								searchedLines[r]['CPP'+colName] 		= resp[idx].ratings[j].CPP;
								searchedLines[r]['CPM'+colName] 		= resp[idx].ratings[j].CPM;
								searchedLines[r]['gImps'+colName] 		= resp[idx].ratings[j].gImps;
								searchedLines[r]['freq'+colName] 		= resp[idx].ratings[j].freq;
								searchedLines[r]['reach'+colName] 		= resp[idx].ratings[j].reach;
								searchedLines[r]['customRating'+colName]= resp[idx].ratings[j].customRating;
								searchedLines[r]['minRepStd'+colName] 	= resp[idx].ratings[j].meetsMinReportStandard;
							}
							break;						
						}
					}
				}
				
				datagridSearchResults.refreshGrid();
				
				$('#gettingRatingsFlag').hide();
				$('#ratingsIcon').show();
				closeAllDialogs();
				
				if(!searchRatingsComplete){
					datagridSearchResults.getRatingsRecursivelly();			
				}
				readLowReportStandard();
			}
			else{
				$('#gettingRatingsFlag').hide();
				$('#ratingsIcon').show();
				searchRatingsComplete = true;
				errorLog('Get Ratings Search Results',data,resp,thisURL);
				loadDialogWindow('ratings-error', 'ShowSeeker Plus', 450, 180, 1);
			}
        },
        error:function(resp){
			$('#gettingRatingsFlag').hide();
			$('#ratingsIcon').show();
			searchRatingsComplete = true;
			errorLog('Get Ratings Search Results',data,resp,thisURL);
			loadDialogWindow('ratings-error', 'ShowSeeker Plus', 450, 180, 1);
        }
    });
};


function loadCustomDemos(){
	var thisURL = apiUrl+"ezratings/customdemographic";	
    $.ajax({
        type: 	'get',
        url: 	thisURL,
        dataType: "json",
        headers: {"Api-Key":apiKey,"User":userid},
        processData: false,
        contentType: 'application/json',
        success: function(resp){
			showCustomDemos(resp.customDemos);
        },
        error: function(resp){
			errorLog('Load Custom Demos','',resp,thisURL);
        }
    });
};


function loadBooksByMarket(id,sbooks){
	var booksHtml,bookNames;
	var marketId 	= id;
	var marketName 	= $('#dma-selector option:selected').text();
	var m 			= ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];
	var M 			= {1:'A',2:'B',3:'',4:'C',5:'D',6:'E',7:'F',8:'G',9:'H',10:'I',11:'J',12:'K'};
	var Ls 			= {1: 'LO', 2:'LS', 3:'L1', 4:'L3', 5:'L7'};
	var LS 			= {'LO':1 , 'LS':2, 'L7':3, 'L3':4 ,'L1':5};
	var monthNames 	= [];
	var bookTypes 	= [];
	var thisURL		= apiUrl+"ezratings/books/"+marketId;
	
	if(marketId === 0){
		marketName = '';
	}

    $.ajax({
        type:'get',
        url: thisURL,
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        processData: false,
        contentType: 'application/json',
        success:function(resp){
			var dataFeed	= [];	
			var difKey		= [];
			var cols,keys;
			
	        $.each(resp.books,function(i,val){
		        bookNames = val.surveyDescription.split(' - ');
		        monthNames.push(bookNames[1].substr(0, bookNames[1].length-4));
		        bookTypes.push(bookNames[1].substr(bookNames[1].length-4,4).replace(/[{()}]/g, ''));
				cols 			= bookNames[1].split(' ');
				keys 			= {};
				keys.market 	= val.marketName;
				keys.monthName 	= m[val.month-1];
				keys.month 		= val.month;
				keys.year 		= val.year;
				keys.serviceName= val.serviceName;
				keys.service	= val.service;
				keys.live 		= Ls[val.kind];
				keys.kind 		= val.kind;
				keys.type 		= val.bookType;
				keys.id 		= val.id;
				keys.marketCode	= val.marketCode;
				keys.sortField 	= val.year+'-'+val.month+'-'+val.bookType+'-'+LS[Ls[val.kind]]+'-'+val.serviceName;
				dataFeed.push(keys);
	        });
			
			datagridSurveys.populateDataGrid(dataFeed);
			datagridSurveys.resizeCanvas();
			
			if(sbooks !== undefined){
				if(sbooks.length > 0){
					autoSelectBook(sbooks);
				}
			}	  
        },
        error: function(resp){
			errorLog('Load Books By Market','',resp,thisURL);
        }
    });
};


function loadDemos(){
	$('#demoAgeFrom,#demoAgeTo') .find('option').remove().end();
	var mw = [2,3];
	var agesFrom,agesTo;

	if(mw.indexOf(parseInt($('#demoGroup').val())) === -1){
		agesFrom	= ["2","6","12","15","18","21","25","35","50","55","65"];
		agesTo		= ["5","11","15","17","20","24","34","49","54","64","+"];		
	}
	else{
		agesFrom	= ["12","15","18","21","25","35","50","55","65"];
		agesTo		= ["15","17","20","24","34","49","54","64","+"];
	}

	$.each(agesFrom,function(id,val){
		$('#demoAgeFrom').append($("<option></option>").attr("value", val).text(val));
	});
	
	$.each(agesTo,function(id,val){
		$('#demoAgeTo').append($("<option></option>").attr("value", val).text(val));
	});
	
	$('#demoAgeFrom').val("25");	
	$('#demoAgeTo').val("54");

	return true;
};


function loadDemoGroups(){
	$.each(demoGroups,function(id,val){		
		$('#demoGroup').append($("<option></option>").attr("value", val.id).text(val.name +' ( '+val.shortCode+' )'));
	});
};



//load the ezrating
function loadEzRatings(){
    myEzRating.getEzRating();    
};



function loadRatingOptions(rtgData){
	
	$('#rtgAverage,#rtgProject').prop('checked', false);
	deactivateSurveysMultiSelect();		
			
	//RATINGS ENABLED
	if('ratingsEnabled' in rtgData){
		$('#ratingsEnabled').val(rtgData.ratingsEnabled).change();
	}
	else{
		$('#ratingsEnabled').val(1).change();		
	}
	
	//AVERAGE
	if(rtgData.average === 1){
		$('#rtgAverage').prop('checked',true);
		activateSurveysMultiSelect('average');		
	}

	//PROJECTION
	if(rtgData.project === 1){
		$('#rtgProject').prop('checked',true);
		activateSurveysMultiSelect('project');
	}

    //RATINGS ROUNDED
    if(rtgData.rounded){
		$('#roundDecimal').val(rtgData.rounded).change();
	}
	else{
		resetRoudedDP();
	}

	$('#ezrating-rounded').buttonset().trigger("refresh");
	$('#rtgAverage, #rtgProject').button('refresh').trigger('refresh');
	
	return false;
};



//LOAD RATINGS SETTINGS FROM PROPOSAL
function loadProposalRatingsSettings(ratingsSettings){
	if(ratingsSettings){
		
		$('input.favDemo').prop('checked',false);

		var dataSettings= deepClone(myEzRating.get('ratingsData'));
		var d 			= formatDemos();
		var favDemos 	= $('input[name="ageRange"]');
		var dmaId 		= $('#dma-selector').val();
		var books 		= ratingsSettings.books;

		rndDecimalPlaces= dataSettings.rounded;
		
		if(ratingsSettings.saved === 1){
			dmaId 		= ratingsSettings.marketId;
			books 		= ratingsSettings.books;
		}

		//LOAD OPTIONS
		loadRatingOptions(dataSettings);
		
		//AUTOSELECT DEMOS
		tagDemos(d);
		
		//TURN THE RATINGS BUTTON ON
		ratingsCtrlButton(true);	

		//SELET MARKET	
		if(datagridSurveys.getSelectedData().length === 0){
			loadMarket(dmaId,books);
		}
		else if(parseInt(dmaId) !== parseInt(ratingsSettings.marketId) && parseInt(ratingsSettings.marketId) !== 0 && myEzRating.getRatings('saved') === 1){
			$('#dma-selector').val(parseInt(ratingsSettings.marketId)).change();		
		}				
		else{
			autoSelectBook(books);			
		}
		
		additionalDemoStatus();
		showRatingsInfo();
	}	
};


function loadMarket(dmaId,books){
	loadBooksByMarket(dmaId,books);
}

function mapAreas(areas){
	var r 	= {};
	r.dma 	= false;
	r.cdma 	= false;
	if(areas){
		for(var i=0; i<areas.length; i++){
			if(parseInt(areas[i]) === 1){
				r.dma = true;
			}
			if(parseInt(areas[i]) === 2){
				r.cdma = true;
			}
		}
	}
	return r;
}


function mapToBoolean(v){
	var r = false;
	if(parseInt(v) === 1){
		r = true;
	}
	return r;
}

//SET IN TEMPORAL MEMORY THE SELECTED SETTINGS
function populateEzRatingsSettings(settings){
	var r  = {};
	if(settings){
		var demos 		= fixDemos(settings.demographics);
		var dmaAreas 	= mapAreas(settings.demographicArea);
		var impressions = settings.impressions;
		var ratings 	= settings.ratings;
		var rounded 	= parseInt(settings.rounded);
		
		myEzRating.setRatings('average', 	settings.average);
		myEzRating.setRatings('project', 	settings.projection);
		myEzRating.setRatings('demos', 		demos);
		myEzRating.setRatings('marketId', 	settings.market);
		myEzRating.setRatings('cdma',		dmaAreas.cdma);
		myEzRating.setRatings('dma',		dmaAreas.dma);
		myEzRating.setRatings('impressions',impressions);
		myEzRating.setRatings('ratings', 	ratings);
		myEzRating.setRatings('rounded', 	rounded);
		myEzRating.setRatings('survey', 	settings.survey);
		myEzRating.setRatings('books', 		settings.books);
		myEzRating.setRatings('marketName',	settings.surveyMarket);
		r = myEzRating.get('ratingsData');
	}
	return r;
}


function readFavDemos(){
	r = [];
	$('.favDemo').each(function(i,item){
		r.push(String($(this).val()).replace(' - ',',').replace(' ',','));
	});
	return r;
};


function ratingsCtrlButton(){

	var saved 	= myEzRating.getRatingsSettings('saved');
	$('#ezratings-search-results-btn').prop('disabled', true).button('refresh');
		
	if( saved === 1){
		$('#ezratings-search-results-btn').prop('disabled', false).button('refresh');
	}
}


function removeAllDemos(){
	$('.tagDemos').each(function(i,item){
		removeSelectedDemo($(this).prop('id'));
	})
};


function removeDemo(){
	if($('#demoWizard3').is(':visible')){
		$('#demoWizard3').hide();
		return;
	}
	if($('#demoWizard2').is(':visible')){
		$('#demoWizard2,#btn-del-demo').hide();
	}
};



function removeDemosOptions(){
	$("#demoAgeFrom > option, #demoAgeTo > option").each(function(i,demoOpt) {
		$(demoOpt).remove();
	});	
};


function removeSelectedDemo(demoId){
	var d 		 = myEzRating.getRatings('demos');
	var thisDemo = demoId.split(',');
	
	if(thisDemo.length > 1){
		for(var i = d.length -1; i >= 0; i--){
			if(d[i].name === thisDemo[0] && d[i].ageFrom === thisDemo[1] && d[i].ageTo === thisDemo[2]){			
				d.splice(i, 1);
				myEzRating.setRatings('demos',d);
			}
		}
	}
	else{
		var tmpDemo;
		for(var i = d.length -1; i >= 0; i--){

			tmpDemo = d[i].name
			
			if('ageFrom' in d[i]){
				tmpDemo += '-'+d[i].ageFrom+'-'+d[i].ageTo;
				$('input.favDemo:checkbox[value='+d[i].name +' '+d[i].ageFrom+' - '+d[i].ageTo+']').prop('checked',false);
			}

			if(tmpDemo === thisDemo[0]){
				d.splice(i, 1);
				myEzRating.setRatings('demos',d);
			}
		}
	}

	$('.favDemo').each(function(i,item){
		if(item.value.replace(/-/g,',') === demoId.replace(/-/g,',')){
			$(this).prop('checked',false);
		}
	});

	tagDemos(formatDemos());
	resetDemos();
	additionalDemoStatus();
	toggleSubmitRatings();	
};



function requiredField(fieldId){
	$('#'+fieldId).parent('div.row').addClass('redBorder');		

	setTimeout(function(){
		$('#'+fieldId).parent('div.row').removeClass('redBorder');
	}, 3000);
	
	return;
}


function resetAreas(){
	$('#ratingsDMA').prop('checked',true).button('refresh');
	$('#ratingsCDMA').prop('checked',false).button('refresh');
	$("#rtgDmaContainer").buttonset('refresh');
	return false;
};


function resetCustomDemoForm(){
	$('#customDemo1').val('');
	$('#group1').val(0);
	return false;
};


function resetDemos(){
	removeDemosOptions();
	//$('.demoGroup,.stdDemos').prop('checked',false).button('refresh');
	//$('.stdDemos').prop('disabled',true);
	loadDemos();
	$('#demoGroup').val(0);
	$('#demoAgeFrom').val('25').prop('disabled',true).siblings('label').addClass('disabledText');
	$('#demoAgeTo').val('54').prop('disabled',true).siblings('label').addClass('disabledText');
};

function resetRoudedDP(){
	rndDecimalPlaces = 2;		
	$('#roundDecimal').val(2).change();
};


function resetEzratingsPopUp(close){
	var rtgSttings = deepClone(myEzRating.get('savedRatingsSettings'));
	
	var rtgData ={"ratings":"1","impressions":"0","rounded":"2"};
	$('.savedSettings').prop('checked', false);
	
	$('#reset-ratings-flag').show();
	$('#demoGroup').val(0).trigger('change');
	$('#rtgAreas,#surveyName,#rtgInfoPipe,#rtgSummarySurvey').text('');
	$('#months-selector').val(0);

	loadRatingOptions(rtgData);
	resetAreas();
	resetRoudedDP();
	unselectSavedParameters();	
	displayRtgUserMessage(100);	
	
	if(parseInt(proposalRattingsOn) > 0){
		
		var mkParams = getMarketParams();
		var line = datagridProposal.getDataSet()[0];
		if(parseInt(myEzRating.getRatingsSettings('marketId')) !== parseInt(mkParams.id) && line !== undefined){
			r = autoSelectMarketAndZone(line.zoneid);
		}	
		r = myEzRating.ini();
		myEzRating.setRatingsData();
		loadProposalRatingsSettings(rtgSttings);		
	}
	else{
		myEzRating.ini();
		removeAllDemos();
		resetCustomDemos();
		resetBooks();
		myEzRating.ini();
		showSummary();
	}
	
	if(close){
		$("#dialog-ratings").dialog("destroy");
	}	
	return false;
};

function discardRatingsSettings(){
	var settigns = deepClone(myEzRating.get('savedRatingsSettings'));
	if(!$.isEmptyObject(settigns)){
		myEzRating.set('ratingsData',settigns);
		loadProposalRatingsSettings(settigns);
	}	
	$("#dialog-ratings").dialog("destroy");
	return false;
};

function resetCustomDemos(){
	$('input[type=checkbox].favDemo').prop('checked',false);
};

function resetBooks(){
	/*$('input[name=ratingsBooks].marketBooks').prop('checked',false).button("refresh");
	$('#months-selector').val(0).trigger("change");
	$('#booksList option:selected').prop("selected", false);*/
	myEzRating.setRatings('books',[]);
}



function resetSavedSettings(){
	$('.savedSettings').prop('checked',false);
};

function setBook(){
	var mkt		= myEzRating.getRatings('marketId');
	var books	= myEzRating.getRatings('books');
	

	if(datagridSurveys.getSelectedData.length > 0){
		autoSelectBook(books);
	}
	else{
		$('#dma-selector').val(mkt);
	}
};



//BUILDING POP UP
function setUpRatingsPopUp(){
	$("#ezrating-demo-buttons,#ezrating-demos,#ezrating-default").buttonset();
	$('#delDaypart,#addDaypart,#submitRatings,#rtgProject,#rtgAverage,#rtgResetBooks').button();
	$('#rtgResetBooks').addClass('sb-reset');
		
	setSelectedmarket();
	applyStyle();

	if(!myEzRating.isEmpty('savedRatingsSettings')){ 
		myEzRating.setRatingsData();
	}
	
	loadProposalRatingsSettings(myEzRating.get('ratingsData'));

	updateTab();	
	
	return false;
};


function setSelectedmarket(){
	$('#rtgSummaryMkt').html($('#dma-selector option:selected').text());
}

function showCustomDemos(demos){
	demos.sort(function(a, b){
	    var keyA =a.demoGroupCode+a.ageFrom+a.ageTo,
    	    keyB = b.demoGroupCode+b.ageFrom+b.ageTo;
		if(keyA < keyB) return -1;
		if(keyA > keyB) return 1;
		return 0;
	});
	
	var opt,demoGroup;
	var fixedGroups = ['HH','MHH','WW'];
	$('div.demoRow').remove();
		
	if(demos.length > 0){
		
	    $.each(demos,function(i,val){
			demoGroup = val.demoGroupCode;
			
		   opt = '<div class="demoRow">';
		    
			if(fixedGroups.indexOf(demoGroup) === -1){
				opt += '<span class="inlineSpan demoCheck">';
				opt += '<input id="favDemo'+val.id+'" type="checkbox" class="favDemo hander" value="'+val.demoGroupCode+' '+val.ageFrom+' - '+val.ageTo+'">';
				opt += '</span>';
			    opt += '<label id="'+val.id+'" name="ageRange" class="hander inlineSpan demoName" for="favDemo'+val.id+'">';
			    opt += val.demoGroupCode+' '+val.ageFrom+' - '+val.ageTo+'</label>  <span class="inlineSpan demoCheck">';
			    opt += '<i class="fa fa-trash hander  delFavDemo" title="Delete this demo"></i></span></div>';
			}
		    else{
				opt += '<span class="inlineSpan demoCheck">';
				opt += '<input id="favDemo'+val.id+'" type="checkbox" class="favDemo hander" value="'+val.demoGroupCode+'">';
				opt += '</span>';
			    opt += '<label value="'+val.demoGroupCode+'" id="'+val.id+'" name="ageRange" class="hander inlineSpan demoName" for="favDemo'+val.id+'">';
			    opt += val.demoGroupCode+'</label> ';
			    opt += '<span class="inlineSpan demoCheck"><i class="fa fa-trash hander delFavDemo" title="Delete this demo"></i></span></div>';
		    }
		    
			$('#custom-demos-list').append(opt);
	
	    });	
		activateAddCustomDemo();	    
	}

};



function showSummary(){};



function splitDemos(c){
	var pslDemos = [];
		
	if(c){
		var d;
		for(i=0; i< c.length; i++){
			d 			= c[i].split(/[.\-_]/);
			cols 		= {};
			cols.name 	= d[0];

			if( d.length > 1){
				cols.ageFrom	= d[1];
				cols.ageto		= d[2];
			}

			pslDemos.push(cols);
		}
	}
	return pslDemos;
};



function tagDemos(d){;
	var demosHtml = '';

	for (var i = 0; i < d.length; i++){
	
		// AUTO SELECT FAV DEMO OPTIONS
		$('.favDemo:checkbox').each(function(item,val){
			if($(this).val() === d[i].id.replace('-', ' - ')){
				$(this).prop('checked',true);
			}
		});
		demosHtml += '<i class="fa fa-times-circle hander disabledText delSelectedDemo" style="color:#362b36"';
		demosHtml += 'id="'+String(d[i].name).replace(' ','-')+'"></i> ';
		demosHtml += d[i].id.replace('-', ' - ');

		if(i>=0 && i<d.length-1){
			demosHtml += ' <span class="demoPipe">'+ '|' +'</span> ';
		}

	}
	
	
	$('#rtgSummaryDemo').html(demosHtml);

	
	return true;
};


function toggleCustomDemosForm(opt){
	if(opt === 'on'){
		$('#list-custom-demographics,#lbl-custom-demo,#del-custom-demos,#add-custom-demos,#edit-custom-demos').hide();
		$('#add-custom-demographics,#lbl-add-custom-demo,#close-custom-demos').show();
	}
	else{
		$('#list-custom-demographics,#lbl-custom-demo,#del-custom-demos,#add-custom-demos,#edit-custom-demos').show();
		$('#add-custom-demographics,#lbl-add-custom-demo,#close-custom-demos').hide();
	}	
};



function updateTab(page){
	
	if(myEzRating.getRatings('saved') === 0 && page === undefined){
		myEzRating.set('pagerPage',0);
	}
	if(page){
		myEzRating.set('pagerPage',page);
	}

	$("#tab-ezrating").tabs({
		selected:	myEzRating.pagerPage, 
		select: 	function(event, ui){
										myEzRating.pagerPage = ui.index;
										fixSummary(event, ui);
										if(!validateMultiSelect(event)){
											event.preventDefault();
										}
										
										},
		activate: 	function(event, ui){}
		});
};


function validateMultiSelect(){
	var selected = $("#tab-ezrating").tabs("option", "selected");
	var r = true;
	if(selected === 0 && (myEzRating.getRatings('project') === 1) && datagridSurveys.getSelectedRows().length < 2){		
		loadDialogWindow('ratingsprojectsurveys', 'ShowSeeker', 450, 180, 1);
		r = false;
	}
	else if(selected === 0 && (myEzRating.getRatings('average') === 1)  && datagridSurveys.getSelectedRows().length < 2){
		loadDialogWindow('ratingsaveragesurveys', 'ShowSeeker', 450, 180, 1);
		r = false;
	}
	return r;
};


function verifyDemoParams(){
	var r = false;
	var userDemos = myEzRating.get('userDemos');
	if(userDemos < 11){

		var g,a,ages,i,cols,re;
		var sDemo 		= {};
		var fixedGroups = [4,5,6];
		var groupId 	= parseInt( $('#demoGroup').val() );
		sDemo.id 		= groupId;

		var demoName	= String($("#demoGroup option:selected").text());
		var pos 		= demoName.indexOf('(') + 1;
		sDemo.name  	= demoName.substr(pos, demoName.length-1-pos).trim()
		
		if(fixedGroups.indexOf(groupId) === -1){
			sDemo.ageFrom = $('#demoAgeFrom').val();
			sDemo.ageTo   = $('#demoAgeTo').val();
		}
		
		if(sDemo.id === 0){//NO DEMO SELECTED
			r = false;
		}
		else if( parseInt(sDemo.id) < 4  && !('ageFrom' in sDemo) ){ //EXCLUDING MEN,WOMEN & ADULTS IF NO AGES ARE PASSED
			r = false;
		}
		else{
			r = true;
		}
	}
	
	return r;
};


function validateRatingsParams(){
	var r 		= true;
	var error 	= 0;
	var re		= {};
	var msg		= '';
	var d 		= myEzRating.getRatings('demos');
	var book 	= myEzRating.getRatings('books');
	var dma		= myEzRating.getRatings('dma');
	var cdma 	= myEzRating.getRatings('cdma');
	var mkParams =	getMarketParams();
	

	if($.isEmptyObject(myEzRating.ratingsData) && $.isEmptyObject(myEzRating.savedRatingsSettings)){
		msg 	= 'No Ratings Access';
		error = 1;
	}
	else if(parseInt(mkParams.id) !== parseInt(myEzRating.getRatingsSettings('marketId')) && myEzRating.getRatingsSettings('saved') === 1){
		msg 	= 'Market Discrepancy';		
		error = 2;
	}
	else if(d.length < 1){
		msg 	= 'No Demos Selected';
		error = 3;
	}
	else if(book.length < 1){
		msg 	= 'No Book Selected';
		error = 4;
	}
	else if(!cdma && !dma){
		msg 	= 'No Area Defined';
		error = 5;
	}
	else if(datagridSurveys.getSelectedRows().length === 0){
		msg 	= 'No Survey';
		error = 6;
	}


	
	if(msg.length > 0){
		r = false;
	}
	re.valid 	= r;
	re.message 	= msg;
	re.error	= error;
	return re;
};


function toggleRatingsImpressions(){
	var r = [];
	var imp = myEzRating.getRatings('impressions');
	var rtg = myEzRating.getRatings('ratings');

	if(rtg){
		r.push({'initials':'ratings','header':'Rtg','formatter':Slick.Formatters.RatingsSearch});
	}
	
	r.push({'initials':'share','header':'Share','formatter':Slick.Formatters.Ratings});
		
	if(imp){
		r.push({'initials':'impressions','header':'Imp','formatter':Slick.Formatters.ImpsSearch});
	}
	
	return r;
};


function saveProposalParams(){
	
	var params 		= validateRatingsParams();
	var mkParams 	= getMarketParams();
	
	if(params.valid){
		datagridProposal.buildEmptyGrid();
		myEzRating.setRatings('saved',1);
		myEzRating.setRatings('marketId',mkParams.id);
		myEzRating.setRatings('marketName',mkParams.name);
		myEzRating.setRatings('rounded',parseInt($('#roundDecimal').val()));
		//myEzRating.setRatings('ratingsEnabled',parseInt($('#ratingsEnabled').val()));
		myEzRating.saveTempParams();
		closeAllDialogs();
		if(datagridProposal.getDataSet().length > 0){
			loadDialogWindow('loadingRatings', 'ShowSeeker Plus', 450, 180, 1);
		}
		else{
			$("#dialog-ratings").dialog("option","modal",true).dialog("close").dialog("open");			
		}
		
		var d = formatDemos();	
		datagridSearchResults.buildDemoColumns(d);
		//$('#dialog-ratings').dialog('destroy');
		ratingsCtrlButton();
		updateProposalParams();
		showRatingsInfo();
		callAfterRatingsUpdate = updateSearchResults;		
		displayColumns();
	}
	else{
		showSummary();
	}
};

function updateProposalParams(){

	if(proposalid !== 0){//IF THERE IS A PROPOSAL LOADED
		var data 			= {};			
		var demos 			= [];
		var proposalSettings= deepClone(myEzRating.get('ratingsData'));		
	    var tmpDemos 		= formatDemos(proposalSettings.demos);
	    
	    for(var i = 0; i < tmpDemos.length; i++){
		    demos.push(tmpDemos[i].id);
	    }
	
		data.ratingsSettings 				= {};
		data.ratingsSettings.ratingsEnabled = proposalSettings.ratingsEnabled;
		data.ratingsSettings.average 		= proposalSettings.average;
		data.ratingsSettings.books 			= proposalSettings.books;
		data.ratingsSettings.demographics 	= demos;
		data.ratingsSettings.impressions 	= proposalSettings.impressions;
		data.ratingsSettings.market			= proposalSettings.marketId;
		data.ratingsSettings.projection		= proposalSettings.project;
		data.ratingsSettings.ratings 		= proposalSettings.ratings;
		data.ratingsSettings.rounded		= parseInt(proposalSettings.rounded);
		rndDecimalPlaces					= parseInt(proposalSettings.rounded);

		data.ratingsSettings.demographicArea = [];
	
		if(proposalSettings.dma){
			data.ratingsSettings.demographicArea.push('1');
		}	
	
		if(proposalSettings.cdma){
			data.ratingsSettings.demographicArea.push('2');
		}

		myEzRating.ratingsData.demographics = demos;
		var thisURL = apiUrl+'ezratings/updatesettings/'+proposalid;
		$.ajax({
	        type: 'post',
	        url: thisURL, 
	        dataType: "json",
	        headers: {"Api-Key":apiKey,"User":userid},
	        processData: false,
	        contentType: 'application/json',
		    data: JSON.stringify(data),
	        success:function(resp){
		        if(resp.error === false){
			        datagridProposalManager.updateRow(proposalid,'ezratings',1);
			        if(proposalid > 0){
				        for(var key in myEzRating.ratingsData){
					        myEzRating.savedRatingsSettings[key] = myEzRating.ratingsData[key];
						}
						proposalRattingsOn = 1;
						setCtrlColumnsButtonState();
					}
					if(resp.lines.length > 0){
						datagridProposal.updateRatingTotals(resp);
						updateProposalList(proposalid);
					}
					else{
						if(callAfterRatingsUpdate !== null){
							updateSearchResults();
							callAfterRatingsUpdate = null;
						}
					}
					$('#dialog-ratings').dialog('destroy');					
					closeAllDialogs();
					$('#surveyName').html($('#rtgSummarySurvey').text());
				}
				else{
					closeAllDialogs();
					$('#gettingRatingsFlag').hide();
					$('#ratingsIcon').show();
					$('#ratingsErrorArea').show();
					$('#ratingsSummaryArea').hide();
					loadDialogWindow('ratings-error', 'ShowSeeker Plus', 450, 180, 1);					
					errorLog('Update Proposal Ratings',data,resp,thisURL);
				}
	        },
	        error:function(resp){
				closeAllDialogs();					
				$('#gettingRatingsFlag').hide();
				$('#ratingsIcon').show();
				loadDialogWindow('ratings-error', 'ShowSeeker Plus', 450, 180, 1);
				errorLog('Update Proposal Ratings',data,resp,thisURL);
	        }
	    });
	}
	else{
		$('#dialog-ratings').dialog('destroy');	
		loadDialogWindow('ratingsNoProposal', 'ShowSeeker Plus', 450, 180, 1);
	}
	updateSearchResults();	
};


function deleteDemos(favDemoId){
	
	var ids 	= [favDemoId];
	var data 	= {};
	var thisURL = apiUrl+"ezratings/deletecustomdemographic";
	if(ids.length > 0){
		
		data.id = ids;
	    
	   	$.ajax({
	        type: 'post',
	        url: thisURL,
	        dataType: "json",
	        headers: {"Api-Key":apiKey,"User":userid},
	        processData: false,
	        contentType: 'application/json',
		    data: JSON.stringify(data),
	        success:function(resp){
		        loadCustomDemos();
	        },
	        error:function(resp){
				errorLog('Delete Demos',data,resp,thisURL);
	        }
    	}); 
	    
	};
}


function setProposalRatings(){
	if(myEzRating.getRatingsSettings('saved') === 1){
		
		var rDemos 	= [];
		var rArea 	= [];
		var rInfo 	= deepClone(myEzRating.get('ratingsData'));
		var r = {};	
		
		d = formatDemos(rInfo.demos);
		
		for(var n = 0; n < d.length; n++){
			rDemos.push(d[n].name);
		}
		
		if(rInfo.dma){
			rArea.push(1);
		}	
		
		if(rInfo.cdma){
			rArea.push(2);			
		}	

		r.average			= rInfo.average;
		r.books				= rInfo.books;
		r.demographics 		= rDemos;
		r.impressions 		= rInfo.impressions ? 1 : 0;
		r.market			= rInfo.marketId;
		r.projection		= rInfo.project;
		r.ratings			= rInfo.ratings ? 1 : 0;
		r.rounded			= rInfo.rounded ? rInfo.rounded : 2;
		r.demographicArea 	= rArea;
	}
	return r;
};


function getRatingsParameters(){
	var proposalSettings;
	var demos 		= [];
	var tmpDemos	= [];
	var data 		= {};
	var mkParams 	= getMarketParams();	
	
	proposalSettings = deepClone(myEzRating.get('ratingsData'));
	data.ratingsSettings 				= {};
	data.ratingsSettings.average 		= proposalSettings.average;
	data.ratingsSettings.books 			= proposalSettings.books;
	
    tmpDemos = formatDemos(proposalSettings.demos);
    
    for(var i = 0; i < tmpDemos.length; i++){
	    demos.push(tmpDemos[i].id);
    }

	//data.ratingsSettings.market		= proposalSettings.marketId;    
	data.ratingsSettings.demographics 	= demos;
	data.ratingsSettings.impressions 	= proposalSettings.impressions;
	data.ratingsSettings.market			= mkParams.id;
	data.ratingsSettings.projection		= proposalSettings.project;
	data.ratingsSettings.ratings		= proposalSettings.ratings;
	data.ratingsSettings.rounded		= proposalSettings.rounded;
	data.ratingsSettings.demographicArea= [];

	if(proposalSettings.dma){
		data.ratingsSettings.demographicArea.push('1');
	}	

	if(proposalSettings.cdma){
		data.ratingsSettings.demographicArea.push('2');
	}

	return data;
}



function populateRatingsTotals(resp){
   //Get the start and end dates for the total proposal
    var sDate = datagridProposal.getProposalStartDate();
    var eDate = datagridProposal.getProposalEndDate();
    var lines = datagridProposal.getDataSet();    
	datagridTotals.populateDataGridGoPlus(lines,sDate,eDate,resp.totals);
}


function saveRatigsParams(){
	var paramsName = $('#paramsName').val().trim(); 
	var userParams = getRatingsParameters();
	var thisURL    = apiUrl+"ezratings/savesettings";
	var data 	   = {};
	data.name = paramsName;
	data.ratingsSettings = userParams.ratingsSettings;	

	if(paramsName.length > 0 && paramsName.length <= 40){
		$('#inProgress').show();		
		var params = validateRatingsParams();
		if(params.valid){
		   	$.ajax({
		        type: 'post',
		        url: thisURL,
		        dataType: "json",
		        headers: {"Api-Key":apiKey,"User":userid},
		        processData: false,
		        contentType: 'application/json',
			    data: JSON.stringify(data),
		        success:function(resp){
						getRatigsParamsList();
						setTimeout(function(){
							$('#dialog-window').dialog('destroy');
							updateTab(3);
					}, 1500);
		        },
		        error:function(resp){
				  	$('.spinner').hide();
				  	errorLog('Save Ratigs Params',data,resp,thisURL);
		        }
	    	});
		}
	}
	if(paramsName.length > 40){
		$('#saveRtgMsg').show();
		setTimeout(function(){$('#saveRtgMsg').hide()}, 4000);
	}
	if(paramsName.length === 0){
		$('#saveNoNameMsg').show();
		setTimeout(function(){$('#saveNoNameMsg').hide()}, 4000);
	}
	
}



function getRatigsParamsList(){	
	var thisURL = apiUrl+"ezratings/getsettings";
   	$.ajax({
        type:'get',
        url: thisURL,
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        processData: false,
        contentType: 'application/json',
        success:function(resp){
	      	buildListOfUserRatings(resp);
			myEzRating.savedParams = resp;
        },
        error:function(resp){
			errorLog('Get Settings','',resp,thisURL);
        }
	});
}

function buildListOfUserRatings(userSettings){
	var settingsRow, areas, opts;
	var Ls 			= {1: 'LO', 2:'LS', 3:'L1', 4:'L3', 5:'L7'};
	var mMap = ['','JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];
	var live ={};
		
	$('#savedRatingsList').empty().html();	
	var svyName,svyArray;
	var bookList = '';
	
	$.each(userSettings,function(m,mkt){
		
		settingsRow = '<tr class="headerRtgSettings maingroup hander">';
		settingsRow +='<td colspan="5" valign="middle" height="26" style="border:solid 1px #eee;">';
		settingsRow +='<span class="slick-group-toggle expanded"></span>'+mkt.market+'</td></tr>';
		$('#savedRatingsList').append(settingsRow);	
		
		$.each(mkt.settings,function(i,val){
			opts = '';
	
			live[val.ratingsSettings.books[0].kind] = val.ratingsSettings.books[0].kind;	
	
			settingsRow = '<tr class="tr_usrRatings">';
			settingsRow +='<td class="borderBottom td_usrRatings td20">';
			settingsRow +='<input type="checkbox" class="delFavRtgSetting hander" id="favRtgSet'+val.id+'"></td>';
			settingsRow +='<td class="td120 borderBottom td_usrRatings favSettingsName">';
			settingsRow +='<label id="rtgSettings'+val.id+'" class="savedSettings hander">'+val.name+'</label></td>';
			settingsRow +='<td class="td150 borderBottom td_usrRatings">';
			$.each(val.ratingsSettings.demographics,function(j,d){
				settingsRow +='<span>'+d+'</span>';
				if(j < val.ratingsSettings.demographics.length-1){
					settingsRow +=', ';	
				}
			});
			settingsRow +='</td>';
			
			bookList = '';
			var bLen = val.ratingsSettings.books.length;
			if(bLen > 2){
				bookList += 'Avg '+ bLen+'Bk ';
				bookList += '('+mMap[val.ratingsSettings.books[0].month]+''+ String(val.ratingsSettings.books[0].year).substr(2,2) + ' - ';
				bookList += mMap[val.ratingsSettings.books[bLen-1].month]+' '+ String(val.ratingsSettings.books[bLen-1].year).substr(2,2)+')';
			}
			else{
				if(val.ratingsSettings.projection === 1){
					bookList += 'Proj (';
				}
				for(var b=0; b<val.ratingsSettings.books.length; b++){
					bookList += mMap[val.ratingsSettings.books[b].month]+''+ String(val.ratingsSettings.books[b].year).substr(2,2)+', ';
				}	
				bookList = bookList.substr(0, bookList.length-2)
				if(val.ratingsSettings.projection === 1){
					bookList += ')';
				}
			}
						
			svyArray = val.ratingsSettings.survey.split(' - ');
			
			if(svyArray.length > 1){
				svyName = svyArray[1].replace(/NIELSEN/ig,'').replace('20','');
			}
			else{
				svyName = val.ratingsSettings.survey.replace(/NIELSEN/ig,'').replace('20','');
			}
			
			settingsRow +='<td class="td100 borderBottom"><smaller>'+bookList+'</smaller></td>';
			
			settingsRow +='<td class="td60 borderBottom">';		
			areas = mapAreas(val.ratingsSettings.demographicArea);
			
			if(val.ratingsSettings.demographicArea.indexOf("1") !== -1){
				opts +='DMA ';
			}
			if(val.ratingsSettings.demographicArea.indexOf("2") !== -1){
				opts +='CDMA';
			}
			
			opts += ' - ' + Ls[live[Object.keys(live)[0]]];
	
			settingsRow +=opts.replace(/(^,)|(,$)/g, "");
			settingsRow +='</td></tr>';
	
			$('#savedRatingsList').append(settingsRow);		
			
		});
		
	});
	
	$('.demoTag').button();
}

function deletedSavedRatings(){
	var selectSettings = $('input[type="checkbox"].delFavRtgSetting:checked');
	if(selectSettings.length > 0){
		loadDialogWindow('delete-ratings', 'ShowSeeker', 480, 200, 1, 1);
	}
}

function confirmDeleteSavedSettings(){
	var ids = [];
	$('input[type="checkbox"].delFavRtgSetting:checked').each(function(i,val){
		ids.push($(this).prop('id').replace('favRtgSet', ''));
	});
	var data 		= {};
	var thisURL 	= apiUrl+"ezratings/deletesettings";
	data.settings 	= ids;
   	$.ajax({
        type: 	'post',
        url: 	thisURL,
        dataType: "json",
        headers: {"Api-Key":apiKey,"User":userid},
        processData: false,
        contentType: 'application/json',
	    data: 	JSON.stringify(data),
        success:function(resp){
	        getRatigsParamsList();
        },
        error:function(resp){
			errorLog('Delete Settings',data,resp,thisURL);
        }
	});
}

function loadSelectedSettings(params){
	var rtgsSettings = populateEzRatingsSettings(params);
	myEzRating.setRatings('saved',0);					
	loadProposalRatingsSettings(rtgsSettings);
	return false;
};

function updateProposalList(id){
	return true;	
};

function updateSearchResults(){
	if(datagridSearchResults.dataCount() > 0){
		datagridSearchResults.triggerRatingsUpdate();
	}
};

function validateRangeSelection(){
	var a1 = parseInt($('#demoAgeFrom').val());
	var a2;

	if($('#demoAgeTo').val() === '+'){
		a2 = 99;
	}
	else{
		a2 = parseInt($('#demoAgeTo').val());
	}
	
	$('#addDemo,#add-custom-demos').prop('disabled',true).removeClass('disabledIcon').addClass('hander').prop('disabled', false).button('refresh');
	
	if(a1 < a2 ){
		activateAddCustomDemo();
	}
	else{

		$('#addDemo,#add-custom-demos').prop('disabled',true).addClass('disabledIcon').removeClass('hander').prop('disabled', true).button('refresh');
	}
};



function getSurvey(survey){
	var sVy = '';

	if(survey){
		sVy = survey.split(' - ');
	}
	else if(!$.isEmptyObject(myEzRating.ratingsData)){
		sVy = myEzRating.ratingsData.survey.split(' - ');		
	}
	
	if(Array.isArray(sVy)){
		if(sVy[1]){
			sVy = sVy[1];
		}
		else{
			sVy = sVy[0];
		}
	}
	return sVy;
};


function proposalUpdateRatings(row,cellid,rating){       
	
	var demo 	= cellid.substr(6, cellid.length);
	var d 		= {};
	var thisURL	= apiUrl+"proposal/line/editrating";
	d.lineId 	= row.id;
	d.rating 	= rating;
	d.demo 		= demo;	
	$.ajax({
        type:		"post",
		url: 		thisURL,
		dataType:	"json",
		headers:	{"Api-Key":apiKey,"User":userid},
		processData:false,
		contentType:'application/json',
		data: 		JSON.stringify(d),
		success:	function(resp){
        	if(resp.error === false){
				datagridProposal.updateRatingTotals(resp);
			}
			else{
				errorLog('Edit Line Rating',d,resp,thisURL);
				loadDialogWindow('inline-ratings-error', 'ShowSeeker Plus', 450, 180, 1);
				undo();
			}
		},
		error:		function(resp){			
			errorLog('Edit Line Rating',d,resp,thisURL);
			loadDialogWindow('inline-ratings-error', 'ShowSeeker Plus', 450, 180, 1);
			undo();
    	}
	});
};


function proposalUpdateImpressions(row,cellid,impressions){       
	
	var demo 	= cellid.substr(11, cellid.length);
	var d 		= {};
	var thisURL	= apiUrl+"proposal/line/editimpressions";
	d.lineId 	= row.id;
	d.demo 		= demo;
	d.impressions = impressions;
	
	$.ajax({
        type:'post',
        url: thisURL,
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        processData: false,
        contentType: 'application/json',
        data: JSON.stringify(d),
        success:function(resp){
	        if(resp.error === false){
				datagridProposal.updateRatingTotals(resp);
			}
			else{
				errorLog('Edit Line Impression',d,resp,thisURL);
				loadDialogWindow('inline-ratings-error', 'ShowSeeker Plus', 450, 180, 1);
				undo();				
			}
        },
        error: function(resp){
			errorLog('Edit Line Impression',d,resp,thisURL);
			loadDialogWindow('inline-ratings-error', 'ShowSeeker Plus', 450, 180, 1);	        
			undo();
        }
    });
};


function proposalUpdateCPP(row,cellid,cpp){       
	
	var demo 	= cellid.substr(10, cellid.length);
	var d 		= {};
	var thisURL	= apiUrl+"proposal/line/editcpp";
	
	d.lineId 	= row.id;
	d.demo 		= demo;
	d.cpp 		= cpp;
	
	$.ajax({
        type:'post',
        url: thisURL,
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        processData: false,
        contentType: 'application/json',
        data: JSON.stringify(d),
        success:function(resp){
			datagridProposal.updateRatingTotals(resp);
        },
        error: function(resp){
			errorLog('Edit Cpp',d,resp,thisURL);
			loadDialogWindow('inline-ratings-error', 'ShowSeeker Plus', 450, 180, 1);	        
			undo();
        }        
    });
};


function proposalUpdateCPM(row,cellid,cpm){       
	
	var demo 	= cellid.substr(3, cellid.length);
	var d 		= {};
	var thisURL	= apiUrl+"proposal/line/editcpm";	
	d.lineId 	= row.id;
	d.demo 		= demo;
	d.cpm 		= cpm;
	
	$.ajax({
        type:'post',
        url: thisURL,
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        processData: false,
        contentType: 'application/json',
        data: JSON.stringify(d),
        success:function(resp){
			datagridProposal.updateRatingTotals(resp);
        },
        error: function(resp){
			errorLog('Edit Cpm',d,resp,thisURL);
			loadDialogWindow('inline-ratings-error', 'ShowSeeker Plus', 450, 180, 1);
			undo();
        }
    });
};


function showRatingsArea(){
	var cdma = myEzRating.getRatings('cdma');
	var dma = myEzRating.getRatings('dma');
	var r = '';
	if(cdma){
		r += 'CDMA';
	}

	if(dma){
		if(r !== ''){
			r += ', ';
		}
		r += 'DMA';
	}
	return r;
};

function showRatingsInfo(){
	if(myEzRating.getRatingsSettings('saved') === 1){}
	return false;
};


function filterBooks(months,bookTypes,textFilter){
	$("#booksList option").show();
	
	$("#booksList > option").each(function(){
		if(months){
			if($(this).text().indexOf(months) === -1){
				$(this).hide();
			}		
		}
		if(bookTypes){
			if(bookTypes.length > 0){
				var h = 1;
				for(var i=0; i<bookTypes.length; i++ ){
					if( $(this).text().toLowerCase().replace('nielsen','').indexOf(bookTypes[i].toLowerCase())  !== -1 ){
						h--;		
						break;
					}
				}
				if(h > 0){
					$(this).hide();
				}
			}
		}
		if(textFilter){
			if($(this).text().toLowerCase().indexOf(textFilter.toLowerCase()) === -1){
				$(this).hide();
			}			
		}
	});

};


function displayRtgUserMessage(sample){
	var nonMeetSearch = $('#search-results').find('.slick-cell').find('.meetMinRepStd');
	var nonMeetProposal = $('#proposal-build-grid').find('.slick-cell').find('.meetMinRepStd');
	if(sample < 50 || (nonMeetSearch.length+nonMeetProposal.length) > 0){
		$('#rtgs-usr-message').addClass('rtgFootNote').html("<span class='meetMinRepStd'>*</span> Estimate does not meet Nielsen Company's minimum sample size reporting standards.");
	}
	else{
		$('#rtgs-usr-message').removeClass('rtgFootNote').text('');		
	}	
	return false;
};


function getMarketParams(){
	var  r = {};
	r.name = $('#dma-selector option:selected').text();
	r.id = $('#dma-selector option:selected').val();
	return r;
};


function readLowReportStandard(){
	/*var nonMeet = $('#search-results').find('.slick-cell').find('.meetMinRepStd');
	if(nonMeet.length > 0 && $('#rtgs-usr-message').text().length < 1){
		displayRtgUserMessage(1);
	}*/
	
	var nonMeet = $('#search-results').find('.slick-cell').find('.meetMinRepStd');
	var pop 		= 100;
	if(nonMeet.length > 0){
		pop = 1;
	}
	displayRtgUserMessage(pop);	
	return false;
}

var commandQueue = [];

function queueAndExecuteCommand(item, column, editCommand) {

	commandQueue.push(editCommand);
	editCommand.execute();
}
  function undo() {
    var command = commandQueue.pop();
    if (command && Slick.GlobalEditorLock.cancelCurrentEdit()) {
      command.undo();
      //grid.gotoCell(command.row, command.cell, false);
    }
  }

