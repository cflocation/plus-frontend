$('#callRatings').on('click',function(){
	getRatings();	
});


$('#rtgDemoSelector').on('change',function(){
	var selectedDemo = $('#rtgDemoSelector').val();
	$('div.ratingsInfo').addClass('ratingsHide');
	$('.'+selectedDemo).removeClass('ratingsHide');
});

//CALL RATINGS
function callRatings(){
	resetSurveyName();
	if(sswin.myEzRating.getRatings('saved') === 1){
		ratingsOn = true;
		$('#ratings-status').show();
		showRatingsInfo();
		getEzRatings();
		populateDemos();
		//setScroller();
	}
	else{
	$('#surveyInfo').hide();		
	}	
};

function getAllShows(){
	$('input.showseekerprogram').each(function(){
		
	});
}

function getEzRatings(){
	
	var lines, lineData;
	var recordRanges = [];
	
	$('.innerContainer').each(function(){
		lines = [];
		$(this).children('.programCell').each(function(){
			lineData = {};
			cellData = $('#'+$(this).prop('id')).data();
			if(!$.isEmptyObject(cellData)){ 
				lineData.startDateTime 	= String(cellData['tz_start_'+sswin.timezone]).substr(0, 19).replace('T', ' ');
				lineData.endDateTime 	= String(cellData['tz_end_'+sswin.timezone]).substr(0, 19).replace('T', ' ');
				lineData.networkId 		= cellData.stationnum;
				lineData.id 			= cellData.id;
				lines.push(lineData);
			}
		});
		recordRanges.push(lines);
	});
	recordRanges.forEach(function(range){	
		//loop through record ranges, chain promises for each one
		sequence = sequence.then(function(){
			$('#ratings-status').show();
	        return getMyData(range); // return a new Promise
    	}).then(function(resp) {
        //do stuff with the data
	        publishRatings(resp);
		}).catch(function(error) {
        //something went wrong
			$('#ratings-status').hide();
        	console.log(error);
    	});
	});
	
	
	return;
};


var sequence = Promise.resolve(); //initialize to empty resolved promise

//this generates a promise
function getMyData(ids){
	
	var data 			= {};			
	var demos 			= [];
	var proposalSettings= sswin.deepClone(sswin.myEzRating.get('ratingsData'));		
    var tmpDemos 		= sswin.formatDemos(proposalSettings.demos);
    
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
	
	data.lines	= ids;
	
    return new Promise(function(resolve,reject){

	    $.ajax({
	        type:'post',
	        url: sswin.apiUrl+"ezratings/getratings",
	        dataType:"json",
	        headers:{"Api-Key":sswin.apiKey,"User":sswin.userid},
	        processData: false,
	        contentType: 'application/json',
		    data: JSON.stringify(data),
	        success: function(resp){
		        resolve(resp);
	        }
	    });  
    });
}




function publishRatings(resp){

	var divContainer;
	var roundingDecimals = sswin.myEzRating.getRatings('rounded')

	for(var idx = 0; idx < resp.length; idx++){
					
		for(var j =0; j < resp[idx].ratings.length ;j++){
			
			divContainer  = '<div class="ratingsInfo '+String(resp[idx].ratings[j].demo).replace(/[^A-Z0-9]/ig, "")+'">';
			divContainer += '<div class="rtgs">';
			divContainer += '<span class="rtgLabel">Rtg:</span> <span class="rtgValue">';
			divContainer += roundNumber(resp[idx].ratings[j].rating, roundingDecimals)+'</span></div>';
			divContainer += '<div class="imps"><span class="rtgLabel">Imp:</span> <span class="rtgValue">';
			divContainer += resp[idx].ratings[j].impressions.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");+'</span></div>';
			
			$('#'+resp[idx].line+'-'+sswin.zoneid).append(divContainer);

		}
	}
	
	var selectedDemo = $('#rtgDemoSelector').val();
	var imps, rtgs;
	
	rtgs = sswin.myEzRating.getRatings('ratings');
	imps = sswin.myEzRating.getRatings('impressions');

	if(!rtgs){
		$('.rtgs').hide();
	}
	
	if(!imps){
		$('.imps').hide();
	}
	
	var selectedDemo = $('#rtgDemoSelector').val();
	$('div.ratingsInfo').addClass('ratingsHide');
	$('div.'+selectedDemo).removeClass('ratingsHide');	
	$('#ratings-status').hide();
};



function getRatingsHiddenShows(){

	var line, area,i, demo,t;
	var d 			= sswin.myEzRating.ratingsData.demos;
	var lines;
	var demos 		= [];
	var data 		= {};
	var zoneId 		= $('#zones').val();

	$('.cellContainer:hidden').each(function(){
		lines 		= []
		data 		= {};
		$(this).children('.innerContainer').children('.programCell').each(function(){
			line = {};
			t  	= String($(this).children('.cellText').children('div.airTimes').text()).split('|');
			line.id 			= $(this).children('.cellText').children('input').val();
			line.networkId 		= $('#network').val();
			line.startDateTime	= t[0];
			line.endDateTime 	= t[1];
			lines.push(line);
		});

		data.area = 'DMA';
	
		if(sswin.myEzRating.getRatings('dma') !== true){
			data.area = 'CDMA';
		}
		
		for(i=0; i< d.length; i++){
			demo = d[i].name;
			if('ageFrom' in d[i]){
				demo += ' '+ d[i].ageFrom+'-'+d[i].ageTo;			
			}
			demos.push(demo);
		}			
		
		data.lines 	= lines;
		data.book 	= sswin.myEzRating.getRatings('bookId');
		data.demos  = demos;

	});

};


function getRatingsApi(data){
	var r;
	$.ajax({
        type:'post',
        url: sswin.apiUrl+"ezratings/getratings",
        dataType:"json",
        headers:{"Api-Key":sswin.apiKey,"User":sswin.userid},
        processData: false,
        contentType: 'application/json',
	    data: JSON.stringify(data),
        success:function(resp){
	        r = resp;
        },
        error:function(){
	        r = [];
    	}
	});
	return r;
}



function roundNumber(value, decimals) {
	return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
};

function populateDemos(){

	var demos = sswin.formatDemos(sswin.myEzRating.ratingsData.demos);	
	$("#rtgDemoSelector").html('');
	var list = $("#rtgDemoSelector");
	$.each(demos, function(i, item){;
		list.append(new Option(item.name, item.id.replace(/[^A-Z0-9]/ig, "")));
	});
	$("#demosCtrl").show();
	$("#rtgDemoSelector").css({'width':'70px'});
};

function resetSurveyName(){
	$('#rtgAreas,#surveyName,#rtgInfoPipe').text('');
};

function showRatingsInfo(){
	$('#surveyInfo').show();
	$('#surveyName').text($("#surveyName",opener.document).text());	
};


function rtgsStatus(){
	if(ratingsOn === false && sswin.myEzRating.getRatings('saved') === 1){
		ratingsOn = true;
	}
	else if(ratingsOn === true && sswin.myEzRating.getRatings('saved') !== 1){
		ratingsOn = false;					
	}	
};

