//start the class file for the datagrid

function DatagridSearchResults() {

	//setup all the basic varibles for the datagrid	
	var grid;
	var data 			= [];
	var dataView;
	var selectedRows 	= [];
	var sortcol 		= "sortingStartDate";
	var sortdir 		= 1;
	var groupby 		= "title";
	var rowsInView;
	
	this.iniColumns = function(removedCols) {
		var c = [];
	    var colElements =[{
	        id: "callsignFormat",
	        name: "Net",
	        field: "callsignFormat",
	        sortable: true,
	        width: 60,
	        minWidth: 60,
	        maxWidth: 60,
	        formatter: Slick.Formatters.NetworkCallsign
	    }, {
	        id: "titleFormat",
	        sortable: true,
	        name: "Program Title",
	        field: "titleFormat",
	        width: 150,
	        minWidth: 120,
	        formatter: Slick.Formatters.EPITitle
	    }, {
	        id: "search",
	        name: "Search Criteria",
	        sortable: true,
	        field: "search",
	        width: 70,
	        minWidth: 70,
	        maxWidth: 100,
	        resizable: true,
	        cssClass: "searchCriteria",
			headerCssClass: 'searchCriteria'
	    }, {
	        id: "statusFormat",
	        name: "Status",
	        sortable: true,
	        field: "statusFormat",
	        width: 60,
	        minWidth: 60,
	        maxWidth: 60,
	        formatter: Slick.Formatters.StatusIcons,
	        cssClass: "statusFormat",
			headerCssClass: 'statusFormat'
	    }, {
	        id: "day",
	        name: "Day",
	        field: "day",
	        sortable: true,
	        width: 50,
	        minWidth: 50,
	        maxWidth: 50,
	        formatter: Slick.Formatters.DayOfWeek
	    }, {
	        id: "startdate",
	        name: "Start Date",
	        field: "startdatetime",
	        sortable: true,
	        width: 80,
	        minWidth: 80,
	        maxWidth: 80,
	        formatter: Slick.Formatters.FormatDate
	    }, {
	        id: "starttime",
	        name: "Start Time",
	        field: "startdatetime",
	        sortable: true,
	        width: 80,
	        minWidth: 80,
	        maxWidth: 80,
	        formatter: Slick.Formatters.FormatTime
	
	    }, {
	        id: "endtime",
	        name: "End Time",
	        field: "enddatetime",
	        sortable: false,
	        width: 80,
	        minWidth: 80,
	        maxWidth: 80,
			formatter: Slick.Formatters.FormatEndTime
	    }];

			
		if(removedCols){
		    for(var k =0; k<colElements.length; k++){
				if(removedCols.indexOf(colElements[k].id) === -1){
					c.push(colElements[k]);	
				}    
		    }	    
		} 
		else{
			c = colElements;
		}
		  
		  return c;
		   
    };
    

    //set the columns
    var columns = this.iniColumns(['search']);


    //set the options for the columns
    var options = {
        editable: false,
        enableCellNavigation: true,
        enableColumnReorder: false,
        forceFitColumns: true,
        rowHeight: 30
    };
    
    

    //set the dataview to the grid
    var groupItemMetadataProvider = new Slick.Data.GroupItemMetadataProvider();

	dataView = new Slick.Data.DataView({
		groupItemMetadataProvider: groupItemMetadataProvider,
		inlineFilters: true
	});
	
    dataView.getItemMetadata = row_metadata(dataView.getItemMetadata);
    
    //create the datagrid and    register plugins
    grid = new Slick.Grid("#search-results", dataView, columns, options);
    
    grid.registerPlugin(groupItemMetadataProvider);
    
    grid.setSelectionModel(new Slick.RowSelectionModel());

	dataView.syncGridSelection(grid, true);
        
    // prevent the grid from cancelling drag'n'drop by default
    grid.onDragInit.subscribe(function(e, dd) {
        e.stopImmediatePropagation();
    });


    // wire up model events to drive the grid
    dataView.onRowCountChanged.subscribe(function(e, args) {
        grid.updateRowCount();
        grid.render();
    });

    dataView.onRowsChanged.subscribe(function(e, args) {
        grid.invalidateRows(args.rows);
        grid.render();

    });

	grid.onViewportChanged.subscribe(function(e, args){
		if(!$.isEmptyObject(myEzRating)){
			if(!$.isEmptyObject(myEzRating.ratingsData)){
				if(myEzRating.getRatings('demos').length > 0){
					rowsInView 	= args.grid.getViewport();
					datagridSearchResults.processLines(rowsInView);	
				}
			}
		}
	});
	


    //get the show information
    grid.onSelectedRowsChanged.subscribe(function(e, args) {

        $.each(dataView.getGroups(), function(i, value) {
            value._dirty = false;
        });


        var id = grid.getSelectedRows();
        var row = grid.getData().getItem(id);

        //grab the most current information from the line selected load the show information
        var lastid = id.slice(-1)[0];
        var lastrow = grid.getData().getItem(lastid);

		//Show Cards 
		if(lastrow){
			if(!('__group' in lastrow)){
	        	loadShowcard(lastrow);
	        }
		}
        $.each(args.rows, function(i, value) {
            var rows = grid.getData().getItem(value);
            rows._dirty = true;
        });


        grid.invalidate();
        grid.render();

        //if there is no row return
        if (row === undefined) {
            return;
        }

        //if the row is not a row with data return
        var x = row.__nonDataRow;
        if (x == true) {
            return;
        }
    });






    grid.onDragStart.subscribe(function(e, dd) {
        selectedRows = grid.getSelectedRows();

        if (selectedRows.length == 0) {
            return;
        }

        var cell = grid.getCellFromEvent(e);
        if (!cell) {
            return;
        }

        if (Slick.GlobalEditorLock.isActive()) {
            return;
        }

        e.stopImmediatePropagation();
        dd.mode = "ssresults";

        dd.rows = selectedRows;
        dd.count = selectedRows.length;


        var proxy = $("<span></span>").css({
            position: "absolute",
            display: "inline-block",
            padding: "4px 10px",
            background: "#e0e0e0",
            border: "1px solid gray",
            "z-index": 99999,
            "-moz-border-radius": "8px",
            "-moz-box-shadow": "2px 2px 6px silver"
        }).text("Drag the selected lines into your proposal").appendTo("body");

        dd.helper = proxy;

        if(builderpanel['panel2'] == false){
            setPanel('panel2');
        }

        return proxy;
    });





    // .text("Drag to porposal " + dd.count + " selected row(s)")
    grid.onDrag.subscribe(function(e, dd) {
        if (dd.mode !== "ssresults") {
            return;
        }


        e.stopImmediatePropagation();
        dd.helper.css({
            top: e.pageY + 5,
            left: e.pageX + 5
        });
    });

    grid.onDragEnd.subscribe(function(e, dd) {
        if (dd.mode !== "ssresults") {
            return;
        }
        e.stopImmediatePropagation();
        dd.helper.remove();
    });

    $.drop({
        mode: "mouse"
    });


    //delete rows this is for a quick download option
    $("#search-results").keyup(function(e) {
        if (e.keyCode == 46) {
            loadMessage('deleteconfirmsresults')
            return;
        }
    })




    this.buildDemoColumns = function(c){
		var colId, newColumn;

		var pairCol 	= toggleRatingsImpressions();							
		var headClass 	= ['headerRatings','headerRatingsSecond','headerRatingsThird'];
		var bkGnd 		= ['dynamicRight_Demo1','dynamicRight_Demo2','dynamicRight_Demo3'];
		var cols = this.toggleColumnManager();
		
        for (var i = 0; i < c.length; i++){
			for(var j=0; j < pairCol.length; j++){
				newColumn = {};
				colId = pairCol[j].initials+String(c[i].name);
		        newColumn.id 		= colId;
		        newColumn.sortable 	= true;
		        newColumn.name		= '<center>'+pairCol[j].header+'<br>'+c[i].name+'</center>';
		        newColumn.field		= colId;
		        newColumn.width		= 70;
		        newColumn.minWidth	= 70;
		        newColumn.maxWidth	= 70;
		        newColumn.demo		= c[i].name;
		        newColumn.column	= pairCol[j].initials;
				newColumn.cssClass 	= bkGnd[i];
		        newColumn.headerCssClass = headClass[i];
				newColumn.formatter = pairCol[j].formatter;
				columns.push(newColumn);
			}
        }
        this.set('columns',cols);
        grid.setColumns(cols);
        return true;
    };


    this.collapseAllGroups = function() {
        dataView.beginUpdate();
        for (var i = 0; i < dataView.getGroups().length; i++) {
            dataView.collapseGroup(dataView.getGroups()[i].value);
        }
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
    };



    this.deleteConfirmed = function() {
        var id = grid.getSelectedRows();
        id.sort(function(a, b) {
            return b - a
        });

        dataView.beginUpdate();

        for (var i = 0; i < id.length; i++) {

            var row = grid.getData().getItem(id[i]);

            //if the row is a group then lets delete them all
            if (row.__group == true) {
                $.each(row.rows, function(i, rid) {

                    $.each(data, function(i, value) {
                        if (value.id == rid.id) {
                            data.splice(i, 1);
                            return false;
                        }
                    })

                });
            } else {
                $.each(data, function(i, value) {
                    if (value.id == row.id) {
                        data.splice(i, 1);
                        return false;
                    }
                })
            }

        }

        dataView.setItems(data);
        dataView.sort(comparer, true);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
        grid.resetActiveCell();
    };
    




    //method to populate the datagrid
    this.populateDataGrid = function(x) {
        data = x;
        /*if(myEzRating.getRatings('saved') === 1){
	        this.buildDemoColumns(formatDemos());
	     }*/
        dataView.beginUpdate();
        dataView.setItems(x);
        dataView.sort(comparer, true);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
        grid.resetActiveCell();
        this.collapseAllGroups();
		var cols = datagridSearchResults.toggleColumnManager();   
        this.set('columns',cols);
        grid.setColumns(cols);	
    };


    //rows
    this.selectedRows = function() {
        var selectedData = [];
        var selectedIndexes = grid.getSelectedRows();

        jQuery.each(selectedIndexes, function(index, value) {
            selectedData.push(grid.getData().getItem(value));
        });

        return selectedData;
    }
    

    grid.onSort.subscribe(function (e, args) {  
        sortdir = args.sortAsc ? 1 : -1;
        sortcol = args.sortCol.field;

        if (sortcol == "titleFormat") {
            sortcol = 'title';
        }
        if(sortcol == "callsignFormat"){
            sortcol = 'callsign';        
        }
        dataView.beginUpdate();
        dataView.sort(comparer, args.sortAsc);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
    });


    //sorting
    function comparer(a, b) {
		  return comparerA(a, b) || comparer_dates(a, b);
    }

    //sorting
    function comparerA(a, b) {
        var x = a[sortcol],
            y = b[sortcol];
        return (x == y ? 0 : (x > y ? 1 : -1));
    }


    //sorting date
    function comparer_dates(a, b) {
        var startArrA = a['startdatetime'].split(/[^0-9]/);
        var startArrB = b['startdatetime'].split(/[^0-9]/);

        var x = new Date(parseInt(startArrA[0]),parseInt(startArrA[1])-1,parseInt(startArrA[2]),parseInt(startArrA[3]),parseInt(startArrA[4]));
        var y = new Date(parseInt(startArrB[0]),parseInt(startArrB[1])-1,parseInt(startArrB[2]),parseInt(startArrB[3]),parseInt(startArrB[4]));
        
       return (x == y ? 0 : (x > y ? 1 : -1));
    }

    function row_metadata(old_metadata_provider) {
        return function(row) {
            var item = this.getItem(row);
            var ret = old_metadata_provider(row);
            if (item && item._dirty == true && item.__group == true) {
                ret = ret || {};
                ret.cssClasses = (ret.cssClasses || '') + ' dirty';
            }
            return ret;
        };
    }
    


    //empty grid
    this.emptyGrid = function() {
        $('#filterGridShows').val('');
        filteringShowsList('');	        
        dataView.beginUpdate();
        dataView.getItems().length = 0;
        dataView.endUpdate();
    };


    this.groupByColumn = function(col) {
        groupby = col;

        if (col == 'off') {
            dataView.groupBy(null);
            return;
        }



        dataView.groupBy(col,
            function(g) {

                var availdays = getAvailDays(g.rows);
                return "" + g.value + " - <span style='color:#32639a;font-weight: bold;'>(" + availdays + ")</span>  <span style='color:#32639a;font-weight: bold;'>(" + g.count + " items)</span>";
            },
            function(a, b) {
                return a.value - b.value;
            }
        );

        this.collapseAllGroups();
    };



    this.sortByColumn = function(col) {
        sortcol = col;
        dataView.sort(comparer, true);
    };



    this.getGroupByColumn = function() {
        return groupby;
    };


    //custom grid scaler render
    this.renderGrid = function() {
        grid.resizeCanvas();
    };


	this.refreshGrid = function(){
		dataView.setItems(data);
		dataView.endUpdate();
		grid.invalidate();
		grid.render();
	};
	
	this.set = function(item,value){
		this[item] = value;
	};

    this.toggleColumnManager = function() {
	    var c = 0;
	    var removeCols = [];
	    var tmpCol = [];
	    var i;

		for(i=0; i< data.length;i ++){
			if(data[i].search.length > 0){
				c++;
				break;
			}
		}
		
		if(c === 0){
			removeCols.push('search');
			columns = this.iniColumns(removeCols);
		}
		else{
			columns = this.iniColumns();
		}

		return columns;
    };

	 this.getRange = function(){
		return grid.getRenderedRange();
	 };
	
	
	this.processLines = function(rowsInView){
		var lines 		= [];
		var newLines 	= [];
		var line, id ,tmpLow;


		if(searchRatingsComplete){

			if(rangeFinal > rowsInView.top){
				tmpLow = rowsInView.top - 30;
				if(tmpLow > 0){
					rangeInitial = tmpLow;				
				}
				else{
					rangeInitial = 0;
				}
			}
			else{
				rangeInitial	= rowsInView.top;
			}
			
			rangeFinal 		= rowsInView.bottom;

			if(rangeInitial + 50 > data.length ||  rangeFinal + 50 > data.length){
				rangeFinal = data.length;
			}
			else{
				rangeFinal = rangeFinal + 50;
			}
	
			for(var i = rangeInitial; i < rangeFinal; i++){
				line 	= grid.getData().getItem(i);
				id 		= line.id;
				if(!(id in searchSelectedLines)){ //only new lines
					searchSelectedLines[id] = line;
					newLines.push(line);
				}
			}
			
			if(newLines.length > 0){
				getRatings(newLines);		
			}
		}
	};

	this.getRatingsRecursivelly = function(){
		var lines 		= [];
		var newLines 	= [];
		var line, id ,tmpLow;

		var cnt = this.dataCount();
		//if(searchRatingsComplete){
			
			if(rangeInitial > cnt){
				searchRatingsComplete = true;
				rangeInitial = 0;
			}
			else{
				if(rangeInitial+500 <= cnt){
					rangeFinal = rangeInitial+500;
				}
				else{
					rangeFinal	= cnt;
				}
				
		
				for(var i = rangeInitial; i < rangeFinal; i++){
					//line 	= grid.getData().getItem(i);
					line 	= dataView.getItemByIdx(i);					
					id 		= line.id;	
					//if(!(id in searchSelectedLines)){ //only new lines
						searchSelectedLines[id] = line;
						newLines.push(line);
					//}
				}
				
				if(newLines.length > 0){
					getRatings(newLines);		
				}
				rangeInitial = rangeFinal;
			}
		//}
	};

/* ---------  -----------------  -----------------  */
	this.updateFilter = function(searchedProposal){
		searchString = searchedProposal;

		if(searchString == ""){
    		$('#search-results-filtered').hide();
		}
		else{
    		$('#search-results-filtered').show();    		
		}

		dataView.setFilterArgs({
			searchString: searchString
		});
		this.showFinder();
	};
	  
	this.showFinder = function(){
		dataView.beginUpdate();
		dataView.setItems(data);
		dataView.setFilterArgs({
			searchString: searchString
		});
		dataView.setFilter(this.filter);
		dataView.endUpdate();
		
		$('#filtered-count').text(dataView.getLength());
		
        setToggleResult();
	};


	this.filter = function(item, args){
		var r = true;
		if (args.searchString !== ""){
    		if((item["availsDay"]+'|'+item["titleFormat"]).toLowerCase().indexOf(args.searchString) === -1) {
    			r = false;
            }
		}
		return r;
	};
/* ---------  -----------------  -----------------  */	

	
	this.dataCount = function(){
		return data.length;
	};
	
	this.triggerRatingsUpdate = function(){
		var selectedData = [];
		searchSelectedLines = {};
		if(!$.isEmptyObject(myEzRating)){
			if(!$.isEmptyObject(myEzRating.ratingsData)){
				if(myEzRating.getRatings('demos').length > 0){
					rowsInView 	= grid.getViewport();
					//datagridSearchResults.processLines(rowsInView);	
					rangeInitial = 0;
					datagridSearchResults.getRatingsRecursivelly(rowsInView);
				}
			}
		}
	};	
	
    //end file
}



$("#filterGridShows").keyup(function(e) {
	Slick.GlobalEditorLock.cancelCurrentEdit();
	// clear on Esc
	if (e.which == 27) {
		this.value = "";
	}

	filteringShowsList(String(this.value).toLowerCase());
	return false;
});

function filteringShowsList(name){
	datagridSearchResults.updateFilter(name);	
	return false;
}

function clearShowsFilter(){
	$('#filterGridShows').val('');	
	datagridSearchResults.updateFilter('');	
};