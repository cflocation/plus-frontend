var hackedtitle = false;

/* Title Search */
// wire up the search textbox to apply the filter to the model
$("#searchinput").keyup(function(e) {
    datagridTitles.updatFromKeyword(e, this.value);
});



//lets handle the drop into the title search datagrid
$("#titles-selected").bind("dropstart", function(e, dd) {
    //$(this).css("background", "yellow");
}).bind("drop", function(e, dd) {
    addSelectedTitlesToDatagrid();
});



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




function doSearchPopulation(data, params) {
    menuSelect('proposal-build');
    
    var re = datasourceBuildGrid(data.response.docs);
    
    $('#download-sort-1').val('network');
    $('#download-sort-2').val('startdate');
    $('#download-sort-3').val('title');
	 $("#dialog-window").dialog("destroy");
	
 	 togglesearchpanels();
    
    
    //marathon-sorting-text
    $('#marathon-sorting-text').css('display', 'inline');

}



// wire up the search textbox to apply the filter to the model
$("#searchinput-actors").keyup(function(e) {
    datagridActors.updatFromKeyword(e, this.value);
});


//lets handle the drop into the title search datagrid
$("#actors-selected").bind("dropstart", function(e, dd) {}).bind("drop", function(e, dd) {
    addSelectedActorsToDatagrid();
});





//populate the genre datagrid
function searchGenres() {
    var arr = {};
    var params = solrSearchParamaters();
    var searchurl = solrSearchGroup(params, 'genre');

    if (params.timezone === "") {
        return;
    }

	$.ajax({
		type:'post',
		url: apiUrl+'snapshot/search',
		dataType:"json",
		headers:{"Api-Key":apiKey,"User":userid},
		processData: false,
		contentType: 'application/json',
		data: JSON.stringify({"url":searchurl}),
		success:function(re){
			var data = re.grouped.showid.groups; //UPDATED ON JUN 01 2016
			for (var i = 0; i < data.length; i++) {
			var row = data[i].doclist.docs[0];
			arr[row.genre1] = row.genre1;
			arr[row.genre2] = row.genre2;
		}	
		var ttl = Object.keys(arr).length;
		var title = 'Select Genres (' + ttl + ')';		

		try{
			setDialogMessage('dialog-genre', title);
		}catch(e){}
		
		datagridGenres.populateDataGrid(arr);
	}});


	/*$.ajax({
		type:'post',
		url: apiUrl+'/proxy',
		dataType:"json",
		headers:{"Api-Key":apiKey,"User":userid},
		processData: false,
		contentType: 'application/json',
		data: JSON.stringify({"url":searchurl}),
		success:function(re){
			var data = re.grouped.showid.groups; //UPDATED ON JUN 01 2016
			for (var i = 0; i < data.length; i++) {
			var row = data[i].doclist.docs[0];
			arr[row.genre1] = row.genre1;
			arr[row.genre2] = row.genre2;
		}	
		var ttl = Object.keys(arr).length;
		var title = 'Select Genres (' + ttl + ')';		

		try{
			setDialogMessage('dialog-genre', title);
		}catch(e){}
		
		datagridGenres.populateDataGrid(arr);
	}});*/



    /*$.ajax({
        type:'get',
        url: searchurl,
        dataType:"jsonp",
        jsonpCallback: 'callback',
        success:function(re){
	        
		    var data = re.grouped.showid.groups; //UPDATED ON JUN 01 2016
	        for (var i = 0; i < data.length; i++) {
	            var row = data[i].doclist.docs[0];
	            arr[row.genre1] = row.genre1;
	            arr[row.genre2] = row.genre2;
	        }
	
	        var ttl = Object.keys(arr).length;
	        var title = 'Select Genres (' + ttl + ')';
	
	        try{
	        setDialogMessage('dialog-genre', title);
	        }catch(e){}
	        
	        datagridGenres.populateDataGrid(arr);
	    }
    });*/
}



function searchShowSeeker() {
    var params = solrSearchParamaters();

	//check that date and times are valid
	if(dateTimeValidator() != 0){
		return;
	}

    //close all the opened dialogs
    closeAllDialogs();

    //open the dialog for doing a search
    dialogSearching();

    //set the search count back to zero
    setSearchCountLabel(0);

    //empty the last search results from the datagrid
    datagridSearchResults.emptyGrid();

    //reset all the result stuff
    dataSourceResult 			= [];
    dataSourceResultCounter 	= 0;
    var searchurl 				= solrSearchString(params, searchType);

    $("#search-result-counter-total").html('0');
    $("#search-result-counter-current").html('0');
	var titleFilter = $('#searchinput').val();    

	$.ajax({
		type:'post',
		url: apiUrl+'snapshot/search',
		dataType:"json",
		headers:{"Api-Key":apiKey,"User":userid},
		processData: false,
		contentType: 'application/json',
		data: JSON.stringify({"url":searchurl}),
		success:function(data){
    		
						
			var genreArray = [];
			var titlesArray = [];
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
			
			var mixData = { "callsign":callsignArray.join(', '),
							"days":params.days.join(','),
							"movieDecades":params.decades,
							"endDateTime":params.enddate.substr(0,10)+' '+params.endtime,
							"genres":genreArray.join(', '),
							"titles":titlesArray.join(', '),
							"keywords":keywordsArray.join(', '),
							"networks":networksArray,
							"premieres":params.premiere.join(','),
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
			usrIp("SnapShot - Search Parameters",mixData);    		
    		
    		
			bindProposalDatagrid();	        
			datasourceBuildGrid(data.response.docs);
			menuSelect('proposal-build');
			togglesearchpanels();
		}});  

	/*$.ajax({
		type:'post',
		url: apiUrl+'/proxy',
		dataType:"json",
		headers:{"Api-Key":apiKey,"User":userid},
		processData: false,
		contentType: 'application/json',
		data: JSON.stringify({"url":searchurl}),
		success:function(data){
			bindProposalDatagrid();	        
			datasourceBuildGrid(data.response.docs);
			menuSelect('proposal-build');
			togglesearchpanels();
		}});*/ 

    /*$.ajax({
        type:'get',
        url: searchurl,
        dataType:"jsonp",
        jsonpCallback: 'callback',
        success:function(data){
            bindProposalDatagrid();	        
            datasourceBuildGrid(data.response.docs);
            menuSelect('proposal-build');
			togglesearchpanels();
        }
    });*/
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
    var searchurl = solrSearchGroup(params, 'full');    
    datagridTitles.emptyGrid();
    datagridTitles.loadLoader();

    if (params.networks.length == 0) {
        return;
    }

	$.ajax({
		type:'post',
		url: apiUrl+'/proxy',
		dataType:"json",
		headers:{"Api-Key":apiKey,"User":userid},
		processData: false,
		contentType: 'application/json',
		data: JSON.stringify({"url":searchurl}),
		success:function(re){

	        var data = re.grouped.sort.groups;
	
	        if (data.length == 0) {
	            datagridTitles.loadNoResults();
	        } else {
	            var row;
	            $.each(data, function(i, value) {
	                row 		= {};
	                row.id 		= value.doclist.docs[0].sort;
	                row.title 	= value.doclist.docs[0].sort;
	                row.showid 	= value.doclist.docs[0].showid;
	                row.cnt 	= value.doclist.numFound;
	                rows.push(row);
	            });
	            
	            datagridTitles.populateDataGrid(rows);
	        }

		}}); 


    /*$.ajax({
        type:'get',
        url: searchurl,
        dataType:"jsonp",
        jsonpCallback: 'callback',
        success:function(re){

        var data = re.grouped.sort.groups;

        if (data.length == 0) {
            datagridTitles.loadNoResults();
        } else {
            var row;
            $.each(data, function(i, value) {
                row 		= {};
                row.id 		= value.doclist.docs[0].sort;
                row.title 	= value.doclist.docs[0].sort;
                row.showid 	= value.doclist.docs[0].showid;
                row.cnt 	= value.doclist.numFound;
                rows.push(row);
            });
            
            datagridTitles.populateDataGrid(rows);
        }
        }
    });*/

}




//Labels
function setSearchCountLabel(val) {
    if (val == 0) {
        $('#label-count').html('');
    } else {
        $('#label-count').html('Last Search: ' + val);
    }
}


function setSearchLabelTitle(x) {
    $('#search-results-label').html(x);
}