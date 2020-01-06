//start the class file for the datagrid

function DatagridSearchResults() {

    //setup all the basic varibles for the datagrid	
    var grid;
    var data 				= [];
    var dataView;
    var selectedRows 		= [];
    var sortcol 			= "sortingStartDate";
    var sortdir 			= 1;
    var groupby 			= "title";
    var allGroupsExpanded 	= true;

    //set the columns
    var columns = [{
        id: "stationnum",
        name: "Net",
        field: "callsign",
        sortable: true,
        width: 60,
        minWidth: 60,
        maxWidth: 60
    }, {
        id: "titleFormat",
        sortable: true,
        name: "Program Title",
        field: "titleFormat",
        width: 150,
        minWidth: 120,
        formatter: Slick.Formatters.EPITitle
    },{
        id: "statusFormat",
        name: "Status",
        sortable: true,
        field: "statusFormat",
        width: 60,
        minWidth: 60,
        maxWidth: 100,
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
        field: "startdate",
        sortable: true,
        width: 80,
        minWidth: 80,
        maxWidth: 80
    }, {
        id: "starttime",
        name: "Start Time",
        field: "starttime",
        sortable: true,
        width: 80,
        minWidth: 80,
        maxWidth: 80

    }, {
        id: "endtime",
        name: "End Time",
        field: "endtime",
        sortable: false,
        width: 80,
        minWidth: 80,
        maxWidth: 80
    }];


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

    //create the datagrid
    grid = new Slick.Grid("#search-results", dataView, columns, options);
    
    //register plugins
    grid.registerPlugin(groupItemMetadataProvider);
    
    grid.setSelectionModel(new Slick.RowSelectionModel());

    grid.onSort.subscribe(function(e, args) {
        sortdir = args.sortAsc ? 1 : -1;
        sortcol = args.sortCol.field;
        
        switch(sortcol){
	        case 'titleFormat':
		        sortcol = 'title'
		        break;	
		    case 'day':
					sortcol = 'dayFormat'
					break;	
        }
        
        dataView.sort(comparer, args.sortAsc);
        grid.invalidate();
        grid.render();
    });
   
    grid.onDragInit.subscribe(function(e, dd) {
        // prevent the grid from cancelling drag'n'drop by default
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
    
    //get the show information
    grid.onSelectedRowsChanged.subscribe(function(e, args) {

        $.each(dataView.getGroups(), function(i, value) {
            value._dirty = false;
        });
        
        var id 		= grid.getSelectedRows();
        var row 		= grid.getData().getItem(id);

        //grab the most current information from the line selected load the show information
        var lastid 	= id.slice(-1)[0];
        var lastrow 	= grid.getData().getItem(lastid);
		  var rows;
		
        $.each(args.rows, function(i, value) {
            rows = grid.getData().getItem(value);
            rows._dirty = true;
        });

        grid.invalidate();
        grid.render();

        //if there is no row return
        if (row == undefined) {
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
        dd.mode 	= "ssresults";
        dd.rows 	= selectedRows;
        dd.count 	= selectedRows.length;
        
        var proxy = $("<span></span>").css({
            position: "absolute",
            display: "inline-block",
            padding: "4px 10px",
            background: "#e0e0e0",
            border: "1px solid gray",
            "z-index": 99999,
            "-moz-border-radius": "8px",
            "-moz-box-shadow": "2px 2px 6px silver"
        }).text("Drag the selected lines into your SnapShot").appendTo("body");

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



	this.collapseAllGroups = function() {
        allGroupsExpanded 	= false;
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
        
        var row;

        for (i = 0; i < id.length; i++){
            row = grid.getData().getItem(id[i]);
            
            //if the row is a group then lets delete them all
            if (row.__group == true){
	            
                $.each(row.rows, function(i, rid) {
                    $.each(data, function(i, value) {
                        if (value.id == rid.id) {
                            data.splice(i, 1);
                            return false;
                        }
                    });
                });
            }
            else {
                $.each(data, function(i, value) {
                    if (value.id == row.id) {
                        data.splice(i, 1);
                        return false;
                    }
                });
            }
        }
		$("#label-count").html('Last Search: '+data.length);
        dataView.setItems(data);
        dataView.sort(comparer, true);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
        grid.resetActiveCell();
    };


    //empty grid
    this.emptyGrid = function() {
        dataView.beginUpdate();
        dataView.getItems().length = 0;
        dataView.endUpdate();
    };


    this.expandAllGroups = function() {
        allGroupsExpanded 	= true;
        dataView.beginUpdate();
        
        for (var i = 0; i < dataView.getGroups().length; i++){
            dataView.expandGroup(dataView.getGroups()[i].value);
        }
        
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
    };


    this.expandSelectedGroup = function(group) {
        dataView.expandGroup(group);
    };  



    this.groupByColumn = function(col) {

		  var availdays        
        groupby = col;

        if(col === 'off'){
            dataView.groupBy(null);
            return;
        }
        
        dataView.groupBy(col,
            function(g){
                availdays = getAvailDays(g.rows);
                return "" + g.value + " - <span style='color:#32639a;font-weight: bold;'>(" + availdays + ")</span>  <span style='color:#32639a;font-weight: bold;'>(" + g.count + " items)</span>";
            },
            function(a, b) {
                return a.value - b.value;
            }
        );
        
		  sortcol = col
        dataView.sort(comparer, 1);
        grid.invalidate();
        grid.render();

        this.collapseAllGroups();
    };


    this.getGroupByColumn = function() {
        return groupby;
    };
    

    //method to populate the datagrid
    this.populateDataGrid = function(x) {
        data = x;
        dataView.beginUpdate();
        dataView.setItems(x);
        dataView.sort(comparer, true);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
        grid.resetActiveCell();
        this.collapseAllGroups();
    };


    //custom grid scaler render
    this.renderGrid = function() {
        grid.resizeCanvas();
    };


   //rows
    this.selectedRows = function() {
        var selectedData = [];
        var selectedIndexes = grid.getSelectedRows();

        $.each(selectedIndexes, function(index, value){
            selectedData.push(grid.getData().getItem(value));
        });

        return selectedData;
    };
    


    this.sortByColumn = function(col) {
        sortcol = col;
        dataView.sort(comparer, true);
    };    
 
    
	this.toggleGroupsExpandCollapse = function() {
		if(allGroupsExpanded){
			this.collapseAllGroups();
		}
		else{
			this.expandAllGroups();
		}
	};    

    //sorting
    function comparer(a, b) {
		 // return comparerA(a, b) || comparer_dates(a, b)  || comparer_times(a, b);
		  return comparerA(a, b) || comparer_dates(a, b);		  
    };

    //sorting
    function comparerA(a, b) {
        var x = a[sortcol],
            y = b[sortcol];
        return (x == y ? 0 : (x > y ? 1 : -1));
    };


    //sorting date
    function comparer_dates(a, b) {
        var startArrA = a['startdatetime'].split(/[^0-9]/);
        var startArrB = b['startdatetime'].split(/[^0-9]/);

        var x = new Date(parseInt(startArrA[0]),parseInt(startArrA[1])-1,parseInt(startArrA[2]),parseInt(startArrA[3]),parseInt(startArrA[4]));
        var y = new Date(parseInt(startArrB[0]),parseInt(startArrB[1])-1,parseInt(startArrB[2]),parseInt(startArrB[3]),parseInt(startArrB[4]));
        
       return (x == y ? 0 : (x > y ? 1 : -1));
    };

    
    //sorting date
    function comparer_times(a, b) {
        var x = Date.parse(a['starttime']),
            y = Date.parse(b['starttime']);
        return (x == y ? 0 : (x > y ? 1 : -1));
    };


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
    };


    //delete rows this is for a quick download option
    $("#search-results").keyup(function(e) {
        if (e.keyCode == 46) {
            loadMessage('deleteconfirmsresults')
            return;
        }
    });


    //end file
}






















