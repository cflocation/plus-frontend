//function build datagrid
function DatagridQueue(div) {

	var grid;
	var data = [];
	var dataView;
	var selectedRows = [];
	var dayparts = [];
    var groupby = "starttime";
    var sortcol = "starttime";
    var sortdir = 1;


	//set the columns
	var columns = [
    {
        id: "fullname",
        name: "Owner",
        field: "fullname",
        width:100, 
        minWidth:100,
        sortable: true
    },{
        id: "email",
        name: "Email",
        field: "email",
        width:140, 
        minWidth:100,
        sortable: true
    },{
        id: "status",
        name: "Status",
        field: "status",
        minWidth:50,
        sortable: true,
        formatter: Slick.Formatters.Queuestatus
    },{
        id: "networksprocessed",
        name: "Processed",
        field: "networksprocessed",
        minWidth:50,
        sortable: true
    },{
        id: "emailed",
        name: "Emailed",
        field: "emailed",
        minWidth:50,
        sortable: true,
        formatter: Slick.Formatters.Yesno
    },{
        id: "filename",
        name: "Filename",
        field: "filename",
        minWidth:100,
        sortable: true,
        formatter: Slick.Formatters.Download
    },{
        id: "createdat",
        name: "Date",
        field: "createdat",
        minWidth:100,
        sortable: true
    }


    ];


	//set the options for the columns YesNo Download
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


	grid = new Slick.Grid("#"+div, dataView, columns, options);


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



    grid.onSort.subscribe(function (e, args) {
        sortdir = args.sortAsc ? 1 : -1;
        sortcol = args.sortCol.field;
        dataView.beginUpdate();
        dataView.sort(comparer, args.sortAsc);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
    });




//2,3,4,5,6

    //sorting
    function comparer(a,b) {
        var x = a[sortcol], y = b[sortcol];
        return (x == y ? 0 : (x > y ? 1 : -1));
    }



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
        //dataView.sort(comparer, true);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
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




