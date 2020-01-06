//function build datagrid
function DatagridDayparts(div) {

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
        id: "starttime",
        name: "Start Time",
        field: "starttime",
        width:100, 
        minWidth:100,
        formatter: Slick.Formatters.Time,
        sortable: true
    },{
        id: "endtime",
        name: "End Time",
        field: "endtime",
        width:100, 
        minWidth:100,
        formatter: Slick.Formatters.Time,
        sortable: true
    },{
        id: "days",
        name: "Days",
        field: "days",
        minWidth:150,
        formatter: Slick.Formatters.Days,
        sortable: true
    },{
        id: "diff",
        name: "Duration",
        field: "diff",
        minWidth:50,
        sortable: true
    },{
        id: "createdat",
        name: "Created",
        field: "createdat",
        minWidth:150,
        sortable: false
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



    grid.onSelectedRowsChanged.subscribe(function(e, args) {



    var selectedIndexes = grid.getSelectedRows();
        if(selectedIndexes.length == 0){
            return;
        }

    var i = selectedIndexes.length -1;
    var row = grid.getData().getItem(selectedIndexes[i]);

    if(row.__group)
        return;

    var sdate =  Date.parse("01/01/1980 " + row.starttime).toString("hh:mm tt");
    var edate =  Date.parse("01/01/1980 " + row.endtime).toString("hh:mm tt");

    if(row.starttime == '00:00:00'){ 
        sdate = '12:00 AM';
    }

    $('#daypart-group').val(row.daypartid);
    $('#daypart-name').val(row.name);
    $('#daypart-start-time').val(sdate);
    $('#daypart-end-time').val(edate);




    //handel days
    var d = row.days.split(',');
    var group = false;

    if(d.length == 7){
        $('#daypart-days').val('1,2,3,4,5,6,7');
        group = true;
    }

    if(row.days == '1,7'){
        $('#daypart-days').val('1,7');
        group = true;
    }

    if(row.days == '2,3,4,5,6'){
        $('#daypart-days').val('2,3,4,5,6');
        group = true;
    }

    if(!group){
        $('#daypart-days').val(d);
    }


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

        jQuery.each(xdata, function (index, value) {
            //create date format          
            var timeStart = new Date("01/01/2007 " + value.starttime).getHours();
            var timeEnd = new Date("01/01/2007 " + value.endtime).getHours();
            var hourDiff = timeEnd - timeStart; 
            if(value.endtime == "23:59:00"){
                hourDiff = hourDiff + 1;
            }
            value.diff = hourDiff; 
        });

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




