//function build datagrid
function datagridMarkets() {

	var grid;
	var data = [];
	var dataView;
	var selectedRows = [];
	var dayparts = [];
    var groupby = "region";


	//set the columns
	var columns = [{
        id: "region",
        name: "Market",
        field: "region",
        width:100, 
        minWidth:100
    },
    {
        id: "daypart",
        name: "Name",
        field: "daypart",
        width:100, 
        minWidth:100
    },
    {
        id: "starttime",
        name: "Start Time",
        field: "starttime",
        width:100, 
        minWidth:100,
        formatter: Slick.Formatters.Time
    },
    {
        id: "endtime",
        name: "End Time",
        field: "endtime",
        width:100, 
        minWidth:100,
        formatter: Slick.Formatters.Time
    },
    {
        id: "days",
        name: "Days",
        field: "days",
        minWidth:150,
        formatter: Slick.Formatters.Days
    }



    ];


	//set the options for the columns
	var options = {
		enableCellNavigation: true,
		editable: true,
		forceFitColumns: true,
		enableColumnReorder: false,
		multiColumnSort: false,
		rowHeight: 30
	};


	var groupItemMetadataProvider = new Slick.Data.GroupItemMetadataProvider();

	dataView = new Slick.Data.DataView({
		groupItemMetadataProvider: groupItemMetadataProvider,
		inlineFilters: true
	});


	grid = new Slick.Grid("#market-list", dataView, columns, options);


	//register plugins
    grid.registerPlugin(groupItemMetadataProvider);
	grid.setSelectionModel(new Slick.RowSelectionModel());


    // wire up model events to drive the grid
    dataView.onRowCountChanged.subscribe(function (e, args) {
    grid.updateRowCount();
    grid.render();
    });

    dataView.onRowsChanged.subscribe(function (e, args) {
    grid.invalidateRows(args.rows);
    grid.render();
    });



    this.groupByColumn = function(col) {
        groupby = col;

        dataView.groupBy(col,
            function (g) {
                return "" + g.value + " - <span style='color:#32639a;font-weight: bold;'>(" + g.count + " items)</span>";
            },
            function (a, b) {
                return a.value - b.value;
            }
        );
    }


    this.collapseAllGroups = function() {
        dataView.beginUpdate();
            for (var i = 0; i < dataView.getGroups().length; i++) {
                dataView.collapseGroup(dataView.getGroups()[i].value);
            }
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
    }


    this.populateDatagrid = function(xdata){
    	data = xdata;
    	dataView.beginUpdate();
        dataView.setItems(data);
        grid.invalidate();
        grid.render();
        this.groupByColumn(groupby);
        dataView.endUpdate();
        this.collapseAllGroups();
    }


    //custom grid scaler render
    this.renderGrid = function(){
        grid.resizeCanvas();
    };



    //rows
    this.selectedRows = function() {
        var selectedData = [];
        var selectedIndexes = grid.getSelectedRows();
    
        jQuery.each(selectedIndexes, function (index, value) {
            selectedData.push(grid.getData().getItem(value));
        });

        return selectedData;
    }


    //unselect rows
    this.unSelectAll = function() {
        grid.getSelectionModel().setSelectedRanges([]);
        grid.invalidate();
        grid.render();
        grid.resetActiveCell();
    }

    this.formatTime = function(d){
    	var re;
    	var min =  Date.parse("01/01/1980 " + d).toString("mm");


    	if(min == "00"){
    		re = Date.parse("01/01/1980 " + d).toString("ht");
    	}else{
    		re = Date.parse("01/01/1980 " + d).toString("h:mmt");
    	}

    	

    	if(re == "0A"){
    		return "12M";
    	}

    	if(re == "11:59P"){
    		return "12A";
    	}
    	return re;
    }


//end main function
}




