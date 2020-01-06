function deleteSavedSearches() {
    var rows = [];
    var eventrows = datagridSavedSearches.getSelectedRows();

    if (eventrows.length == 0) {
			loadDialogWindow('onlyone', 'ShowSeeker Plus', 450, 180, 1);
        return;
    }


    $.each(eventrows, function(index, value) {
        if (typeof value.id != "undefined") {
            rows.push(value.id);
        }
    });

    $.ajax({
        type:'post',
        url: apiUrl+"template/delete/fixed",
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        processData: false,
        contentType: 'application/json',
        data: JSON.stringify({"hash":rows}),
        success:function(resp){
            loadSavedSearches();
        }
    }); 
}



function loadSavedSearches() {
    $.ajax({
        type:'get',
        url: apiUrl+"template/savedsearches",
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        success:function(data){
            //data['search'] = JSON.stringify(data['search'])
            datagridSavedSearches.populateDataGrid(data['result']);
        }
    });


    //url for the webservice
    /*var url = '/services/1.0/search.load.php';
    $.when(buildToken(url)).done(function(token){
        url = token['url'];
        
        //get the url for the json result
        $.getJSON(url, function(data) {
           console.log(JSON.stringify(data));
            datagridSavedSearches.populateDataGrid(data);
        });
    });*/
}



//saved searches
function saveSearch() {
    var params     = solrSearchParamaters();
    var saveparams = jQuery.extend({}, params);
    var filters    = $('input:radio[name=save-search-selector]:checked').val();

    if (filters == 'premiere') {
        saveparams.premiere = ["Premiere", "Season Premiere", "Season Finale", "Series Premiere", "Series Finale"];
    }

    if (filters == 'new') {
        saveparams.showtype = 'new';
    }

    if (filters == 'live') {
        saveparams.showtype = 'live';
    }

    var data = {
            "user"      : userid,
            "saveparams": saveparams,
            "filters"   : filters,
            "type"      : params.searchType,
            "name"      : $("#save-search-name").val(),
            "notes"     : $("#save-search-notes").val()
        };

    $.ajax({
        type:'post',
        url: apiUrl+"template/savesearch",
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        processData: false,
        contentType: 'application/json',
        data: JSON.stringify(data),
        success:function(resp){
            $("#save-search-notes").val("");
            $('input:radio[name=save-search-selector][value=off]').click();
            $('input:radio[name=save-search-reminder][value=0]').click();
            $("#dialog-save-search").dialog("destroy");
            loadSavedSearches();
            closeAllDialogs();
        }
    });



    /*

        var zone     = {"id": parseInt(params.zoneid), "isdma": "NO", "marketId": parseInt(params.marketid), "name": params.zone };
        var date     = {"startTime": params.starttime,"endTime": params.endtime,"startDate": params.startdate.split("T")[0],"endDate": params.enddate.split("T")[0]};
        var networks = [];

        $.each(params.networks,function(i,n){ if(n.id > 0)  networks.push(n.id); });

        
        var saveparams = jQuery.extend({}, params);
        var filters = $('input:radio[name=save-search-selector]:checked').val();
        var reminder = $('#save-search-reminder').val();

        if (filters == 'premiere') {
            saveparams.premiere = ["Premiere", "Season Premiere", "Season Finale", "Series Premiere", "Series Finale"];
        }

        if (filters == 'new') {
            saveparams.showtype = 'new';
        }

        if (filters == 'live') {
            saveparams.showtype = 'live';
        }

        var data = {
                "user": userid,
                "zone": zone,
                "date": date,
                "day": params.days,
                "star": [],
                "network": networks,
                "networkCount": networks.length,
                "premiere": params.premiere,
                "filter": [],
                "query": "",
                "genre": [],
                "stype": [],
                "extra": "",
                "name": "",
                "tags": ""
             };
        $.ajax({
            type:'post',
            url: apiUrl+"search/hash",
            dataType:"json",
            headers:{"Api-Key":apiKey,"User":userid},
            processData: false,
            contentType: 'application/json',
            data: JSON.stringify({"data":data}),
            success:function(resp){
                var name = $("#save-search-name").val();
                var notes = $("#save-search-notes").val();
                $.ajax({
                    type:'post',
                    url: apiUrl+"template/fixed",
                    dataType:"json",
                    headers:{"Api-Key":apiKey,"User":userid},
                    processData: false,
                    contentType: 'application/json',
                    data: JSON.stringify({"User":userid,"hashFilter":resp.hashKey,"name":name,"tag":notes}),
                    success:function(resp){
                        $("#save-search-notes").val("");
                        $('input:radio[name=save-search-selector][value=off]').click();
                        $('input:radio[name=save-search-reminder][value=0]').click();
                        $("#dialog-save-search").dialog("destroy");
                        loadSavedSearches();
                        closeAllDialogs();
                    }
                });
            }
        });
    */

    /*
        var saveparams = jQuery.extend({}, params);

        var filters = $('input:radio[name=save-search-selector]:checked').val();
        var reminder = $('#save-search-reminder').val();

        if (filters == 'premiere') {
            saveparams.premiere = ["Premiere", "Season Premiere", "Season Finale", "Series Premiere", "Series Finale"];
        }

        if (filters == 'new') {
            saveparams.showtype = 'new';
        }

        if (filters == 'live') {
            saveparams.showtype = 'live';
        }

        var name = $("#save-search-name").val();
        var notes = $("#save-search-notes").val();
        var search = JSON.stringify(saveparams);
    	 var url = '/services/1.0/searches.save.php';
    	 
        $.when(buildToken(url)).done(function(token){
    			url 		= token['url']; 
    			tokenid 	= token['key'];
    			userid 	= token['userid'];			
    			$.post(url, {
    					tokenid: tokenid,
    					userid: userid,					
    					search: search,
    					name: escape(name),
    					notes: escape(notes),
    					filters: filters,
    					reminder: reminder,
    					user: userid,
    					type: escape(params.searchType)
    		    }, function(data) {
    		        $("#save-search-notes").val("");
    		        $('input:radio[name=save-search-selector][value=off]').click();
    		        $('input:radio[name=save-search-reminder][value=0]').click();
    		        $("#dialog-save-search").dialog("destroy");
    		        loadSavedSearches();
    				closeAllDialogs();	
    		    });
         });
    */
}



function saveSearchDefaultName(params) {
    var zone = params.zone;
    var starttime = params.starttime;
    var endtime = params.endtime;
    var marathons = params.marathons;
    var searchType = params.searchType;
    var showtype = params.showtype;
    var sports = params.sports;


    if (typeof marathons == "undefined") {
        marathons = '';
    } else {
        marathons = ' | marathons';
    }

    if (typeof showtype == "undefined") {
        showtype = '';
    } else {
        showtype = ' | ' + showtype;
    }

    if (typeof sports == "undefined") {
        sports = '';
    } else {
        sports = ' | Sports: ' + sports;
    }

    if (searchType == "all") {
        searchType = '';
    } else {
        searchType = ' | ' + searchType;
    }


    if (params.premiere.length == 0) {
        premiere = '';
    } else {
        premiere = ' | ' + params.premiere;
    }


    if (params.searchType == 'keyword') {
        var words = saveSearchParseWords(params.searchKeywordsArray);
    }

    if (params.searchType == 'title') {
        var words = saveSearchParseWords(params.searchTitlesArray);
    }

    if (params.searchType == 'actor') {
        var words = saveSearchParseWords(params.searchActorsArray);
    }

    if (params.searchType == 'all') {
        var words = 'All';
    }



    var name = words + ' | ' + zone + ' | ' + starttime + ' | ' + endtime + marathons + searchType + showtype + sports;
    return name;
}


function saveSearchLoad(search) {
    var currentzone = $("#zone-selector").val();

    if (search.zoneid != currentzone) {
        $("#zone-selector").val(search.zoneid).change();
    }

    loadedSearch = search;
    loadingSearch = true;

    saveSearchLoadParams();
}




function saveSearchLoadParams() {
    $("#dialog-title").dialog("close");
    $("#dialog-keyword").dialog("close");
    $("#dialog-actor").dialog("close");

    loadingSearch = false;



    arrayNetworks = loadedSearch.networks;
    arrayDays = loadedSearch.days;
    arrayPremiere = loadedSearch.premiere;
    //arrayGenre = loadedSearch.genre;

    //nets
    datagridNetworks.selectRowsFromData(arrayNetworks);

    //dates times
    var startdate = Date.parse(loadedSearch.startdate).toString("MM/dd/yyyy");
    var enddate   = Date.parse(loadedSearch.enddate).toString("MM/dd/yyyy");
    var starttime = Date.parse(loadedSearch.starttime).toString("hh:mm tt");
    var endtime   = Date.parse(loadedSearch.endtime).toString("hh:mm tt");

    //$("#date-start").val(startdate);
    //$("#date-end").val(enddate);
    $("#time-start").val(starttime);
    $("#time-end").val(endtime);


    if (loadedSearch.days.length == 7) {
        $("#search-days").val("ms");
    } else {
        $("#search-days").val(loadedSearch.days);
    }


    btnUpdateDaysOfWeek(false);


    //ez search
    var type = loadedSearch.searchType;
    $('input:radio[name=search-mode-option][value=off]').click();

    if (type == 'title') {
        $('input:radio[name=search-mode-option][value=title]').click();
    }

    if (type == 'keyword') {
        $('input:radio[name=search-mode-option][value=keyword]').click();
    }

    if (type == 'actor') {
        $('input:radio[name=search-mode-option][value=actors]').click();
    }

    datagridTitlesSelected.populateDataGridFromArray(loadedSearch.searchTitlesArray);
    datagridActorsSelected.populateDataGridFromArray(loadedSearch.searchActorsArray);
    datagridKeywords.populateDataGridFromArray(loadedSearch.searchKeywordsArray);

    //SPORTS
    $('#sports-all').removeAttr('checked');
    $('#sports-all').button("refresh");
    $('#sports-live').removeAttr('checked');
    $('#sports-live').button("refresh");

    if (loadedSearch.sports == 'live') {
        $('#sports-live').prop('checked', true);
        $('#sports-live').button("refresh");
    }

    if (loadedSearch.sports == 'all') {
        $('#sports-all').prop('checked', true);
        $('#sports-all').button("refresh");
    }

    //PREMIERE
    if (loadedSearch.premiere.length > 0) {
        $("#search-premiere").val(loadedSearch.premiere);
    } else {
        $("#search-premiere").val(0);
    }
    btnUpdatePremiere();


    //searchGenres();
    datagridGenres.selectRowsFromData(loadedSearch.genre);


    //type
    if (loadedSearch.showtype == 'live') {
        $('input:checkbox[name=showtype-mode-selector][value=live]').click();
    }

    if (loadedSearch.showtype == 'movies') {
        $('input:checkbox[name=showtype-mode-selector][value=movies]').click();
    }

    if (loadedSearch.showtype == 'new') {
        $('input:checkbox[name=showtype-mode-selector][value=new]').click();
    }

    //marathons
    if (loadedSearch.marathons == 'checked') {
        $('input:checkbox[name=more-selector][value=marathons]').click();
    }

}




function saveSearchParseWords(data) {
    var re = '';
    $.each(data, function(i, value) {
        var x = value.title;
        re += x + '|';
    });
    return re;
}
