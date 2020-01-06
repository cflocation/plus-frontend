/* Title Search */
// wire up the search textbox to apply the filter to the model
$("#searchinput").keyup(function(e) {
    datagridTitles.updatFromKeyword(e, this.value);
});


//lets handle the drop into the title search datagrid
$("#titles-selected").bind("dropstart", function(e, dd) {
}).bind("drop", function(e, dd) {
    addSelectedTitlesToDatagrid();
});


//lets handle the drop into the genre search datagrid
$("#genre-selected").bind("dropstart", function(e, dd) {
}).bind("drop", function(e, dd) {
    addSelectedGenresToDatagrid();
});


/* Add selected surveys to proposal*/
function addSelectedSurveysToDatagrid(){
	var rows = datagridSurveys.selectedGetSelectedRows();
	datagridSurveysSelected.addSelectedItems(rows);
	datagridSurveysSelected.resizeCanvas();
};

/* Add titles to datagrid for searching 10/17/2014 */
function addSelectedActorsToDatagrid(){
    var rows = datagridActors.selectedGetSelectedRows();
    datagridActorsSelected.addSelectedItems(rows);
}


/* Add titles to datagrid for searching 10/17/2014 */
function addSelectedTitlesToDatagrid(){
    var rows = datagridTitles.selectedGetSelectedRows();
    datagridTitlesSelected.addSelectedItems(rows);
}


/* Add titles to datagrid for searching 10/17/2014 */
function addSelectedGenresToDatagrid(){
    var rows = datagridGenres.getGenreRows();
    datagridGenresSelected.populateSelected(rows);
}


function doSearchPopulation(data, params) {
    menuSelect('proposal-build');
    if(data){
    	var re = datasourceBuildGrid(data.response);
	} 
    $('#download-sort-1').val('network');
    $('#download-sort-2').val('startdate');
    $('#download-sort-3').val('title');
	 $("#dialog-window").dialog("destroy");
	
 	 togglesearchpanels();
   
    //marathon-sorting-text
    $('#marathon-sorting-text').css('display', 'inline');

}




/* Keyword Search */
function keywordsAddWord() {
    var x = $("#searchinputkeywords").val();
    var words = x.split(',');
    var rows = [];

    $.each(words, function(i, value) {
        var nval = value.replace(/\*/g, '^');

        if (value.length > 1 || nval == '^' || nval == '^^') {
            var row = new Object();
            row.id = nval;
            row.title = nval;
            rows.push(row);
        }

    });

    datagridKeywords.addSelectedItems(rows);
    $("#searchinputkeywords").val('');
}

function keywordsResetList() {
    $("#searchinputkeywords").val("");	
    datagridKeywords.resetKeywordSearch();
};
/* End Keyword Search */




/* Actor Search */
function searchActors() {
    var rows = {};
    var params = solrSearchParamaters();    
    var searchurl = solrSearchGroup(params, 'actor');

    datagridActors.emptyGrid();
    datagridActors.loadLoader(); 

	$.ajax({
        type:'post',
		url: apiUrl+"solr/actors",
		dataType:"json",
		headers:{"Api-Key":apiKey,"User":userid},
		contentType: 'application/json',
		data: JSON.stringify(params),
		success:function(actors){
	        if(actors.length == 0){
	            datagridActors.loadNoResults();
	        } 
	        else{
	            datagridActors.populateDataGrid(actors);
	        }
		}
	});   
};

// wire up the search textbox to apply the filter to the model
$("#searchinput-actors").keyup(function(e) {
    datagridActors.updatFromKeyword(e, this.value);
});


//lets handle the drop into the title search datagrid
$("#actors-selected").bind("dropstart", function(e, dd) {
    //$(this).css("background", "yellow");
}).bind("drop", function(e, dd) {
    addSelectedActorsToDatagrid();
});





//populate the genre datagrid
function searchGenres() {
    var arr 		= {};
    var params 		= solrSearchParamaters();
    var searchurl 	= solrSearchGroup(params, 'genre');

    if (params.timezone == "") {
        return;
    }

	$.ajax({
        type:'post',
		url: apiUrl+"solr/genres",
		dataType:"json",
		headers:{"Api-Key":apiKey,"User":userid},
		contentType: 'application/json',
		data: JSON.stringify(params),
		success:function(arr){
	        var ttl = Object.keys(arr).length;
	        var title = 'Select Genres (' + ttl + ')';
	        try{
		        setDialogMessage('dialog-genre', title);
	        }catch(e){}
	        
	        datagridGenres.populateDataGrid(arr);
	        
		}
	});
}



function searchShowSeeker(){
    closeAllDialogs();
    resetViewPosition();
    var params = solrSearchParamaters();
    
	//check that date and times are valid
	if(dateTimeValidator() != 0){
		return;
	}

    //reset all the result stuff
    dataSourceResult = [];
    dataSourceResultCounter = 0;
    $("#search-result-counter-total").html('0');
    $("#search-result-counter-current").html('0');
    $('#marathon-sorting-text').css('display', 'none');

    var searchurl 	= solrSearchString(params, searchType);
	var titleFilter = $('#searchinput').val();    
    dialogSearching();
    setSearchCountLabel(0);
    
    datagridSearchResults.emptyGrid();

    //log event
    var logrequest = JSON.stringify(params);
    logUserEvent(5,logrequest,1);

    //hop to a marathon search 
    if (params.marathons == 'checked') {

        var keyLen = params.searchKeywordsArray.length;
        var netLen = params.networks.length;
        var titleLen = params.searchTitlesArray.length;
        var actorLen = params.searchActorsArray.length;

        if (netLen > 8 && keyLen < 1 && titleLen < 1 && actorLen < 1) {
            $("#dialog-window").dialog("destroy");
			dialogNetworkList();
			setTimeout(loadDialogWindow('marathonsettings', 'ShowSeeker Plus', 450, 180, 1, 0) , 800);
            
            return;
        }
  
        datagridSearchResults.sortByColumn('sortingMarathons');
        marathonsSearch(params);
        return;
    }

    datagridSearchResults.sortByColumn('sortingStartDate');

	$.ajax({
        type:'post',
		url: apiUrl+"solr/search",
		dataType:"json",
		headers:{"Api-Key":apiKey,"User":userid},
		contentType: 'application/json',
		data: JSON.stringify(params),
		success:function(data){
			
			if(!data.isPassSecure && localStorage.getItem("admin") !== '1'){
				loginMessage('NonSecurePwd');
				return false;
			}
			if(data.requireLogout && localStorage.getItem("admin") !== '1'){
				loginMessage('EmailUpdate');
				return false;
			}
						
			var genreArray = [];
			var titlesArray = [];
			var actorsArray = [];
			var keywordsArray = [];
			var networksArray = [];		
			var callsignArray = [];
			var sports =(params.sports)?params.sports:'';
					
			if(!$.isArray(params.genre)){
				for(var key in params.genre){
					genreArray.push(key);
				}
			}
			for(var i=0; i<params.searchTitlesArray.length;i++){
				titlesArray.push(params.searchTitlesArray[i].title);
			}
			for(var i=0; i<params.searchActorsArray.length;i++){
				actorsArray.push(params.searchActorsArray[i].title);
			}
			for(var i=0; i<params.searchKeywordsArray.length;i++){
				keywordsArray.push(params.searchKeywordsArray[i].title);
			}
			for(var i=0; i<params.networks.length;i++){
				if(params.networks[i].id !== "0"){
					networksArray.push(params.networks[i].id);
					callsignArray.push(params.networks[i].callsign);
				}
			}
			if(params.searchType === 'all' && genreArray.length > 0){
				params.searchType = 'Genre';
			}
			else if(params.searchType === 'all' && genreArray.length === 0 && params.premiere.length > 0){
				params.searchType = 'Premieres';
			}
			
			var mixData = { "actors":actorsArray.join(', '),
							"actorsArray":actorsArray,
							"callsign":callsignArray.join(', '),
							"days":params.days.join(','),
							"movieDecades":params.decades,
							"endDateTime":params.enddate.substr(0,10)+' '+params.endtime,
							"genres":genreArray.join(', '),
							"titles":titlesArray.join(', '),
							"keywords":keywordsArray.join(', '),
							"networks":networksArray,
							"premieres":params.premiere.join(','),
							"rateCardId":params.ratecardId,
							"roundedResults":params.roundedResults,
							"showtype":params.showtype,			
							"sports":sports,
							"solrRows": data.response.numFound,
							"searchRows": data.response.docs.length,
							"searchType":params.searchType,
							"startDateTime":params.startdate.substr(0, 10)+' '+params.starttime,
							"titleFilter":titleFilter,
							"userIp": "0",	
							"zoneId":params.zoneid,
							"zoneName":params.zone};
			usrIp("Search Parameters",mixData);
			
            menuSelect('proposal-build');
            bindProposalDatagrid();
            var searchnum = data.response.numFound;
            if (searchnum == 0) {
                loadDialogWindow('noresults', 'ShowSeeker Plus', 450, 180, 1, 0);
            }
            var re = datasourceBuildGrid(data.response.docs);
            $("#dialog-window").dialog("destroy");
            togglesearchpanels();
		}
	});
}


//do the marathon search
function searchShowSeekerMarathons(params) {

   var url 				= '/services/1.0/marathons.php';
   var datecols 		= solrGetDateColumns(params);
	var solrsearch 	= '?url='+solrSearchMarathon(params);

	$.when(buildToken(url)).done(function(token){
	url = token['url']+'&url='+solrSearchMarathon(params);

	   $.get(url, function(data) {
			var xrl = data + '&fl=id,epititle,genre1,genre2,descembed,showtype,stationnum,callsign,search,stars,isnew,tmsid,stationnum,title,new,live,stationname,duration,premierefinale,' + datecols;
			var zrl = 'services/searchpost.php';
	      
	        //post to the server so we can handel long urls
	        $.post(zrl, {
	                xrl: xrl
	            }, function(data) {
	                var d = jQuery.parseJSON(data);
	                doSearchPopulation(d, params);
	            });
	            
	        return;
	
	    });

	});

}



/* If the user does not drag the items over to the left grids lets add them 10/17/2014 */
function searchShowSeekerActorEvent(){
    addSelectedActorsToDatagrid();
    searchShowSeeker();
}

/* End Actor Search */



/* If the user does not drag the items over to the left grids lets add them 10/17/2014 */
function searchShowSeekerTitleEvent(){
    addSelectedTitlesToDatagrid();
    searchShowSeeker();
}






/* Title Search Search Titles Updated 10/16/2014*/
function searchTitles() {
    var rows = [];
    var params = solrSearchParamaters();
    var url = apiUrl+"solr/titles";
    var searchurl = solrSearchGroup(params, 'full');
	
	params.searchTitlesArray = [];
	params.rows = 7500;
    datagridTitles.empty();
    datagridTitles.loadLoader();

    if (params.networks.length !== 0) {

		$.ajax({
	        type:'post',
			url: url,
			dataType:"json",
			headers:{"Api-Key":apiKey,"User":userid},
			contentType: 'application/json',
			data: JSON.stringify(params),
			success:function(titles){
		        if (titles.length == 0) {
		            datagridTitles.loadNoResults();
		        } 
		        else{
		            datagridTitles.populateDataGrid(titles);
		        }
			}
		});
	}
	return true;
    
}




var hackedtitle = false;

function searchTitlesArchived(type) {
    searchType = 'title';
    datagridTitles.emptyGrid();
    datagridTitles.loadLoader();

    hackedtitle = true;
    $('input:radio[name=search-mode-option][value=title]').click();

    var rows = [];
    var params = solrSearchParamaters();
    var zoneid = $("#zone-selector").val();

    if (type == 2) {
        zoneid = 0;
    }

    var url = '/services/1.0/archived.titles.php';
        
    hackedtitle = false;
    
    
    $.when(buildToken(url)).done(function(token){ 
		url = token['url']+'&zoneid=' + zoneid;

        $.getJSON(url, function(data) {

            

        $.each(data.response.titles, function(i, value) {
            //basic varibles
            var row = {};
            row.id = i;
            row.title = value.title;
            row.showid = value.title;
            row.cnt = i;
            rows.push(row);
        });
        	
        datagridTitles.populateDataGrid(rows);

        
       });
    });
}




//Labels
function setSearchCountLabel(val) {
    if (val == 0) {
        $('#label-count').html('');
    } else {
        //$('#label-count').html('Last Search: ' + val);
        $('#label-count').html(val);
    }
}


function setSearchLabelTitle(x) {
    $('#search-results-label').html(x);
}