//function build datagrid
function DatagridSchedulerNetChoice() {

    var self = this;
    var grid;
    var data = [];
    var dataView;
    var selectedRows = [];
    var dayparts = [];
    var groupby = "tzname";
    var sortcol = "callsign";
    var sortdir = 1;


    //set the columns
    var columns = [{
        id: "filename",
        name: "",
        field: "filename",
        width: 30,
        minWidth: 30,
        maxWidth: 30,
        formatter: Slick.Formatters.NetworkLogoSmall
    },{
        id: "name",
        name: "Name",
        field: "name",
        minWidth: 150,
        sortable: true
    },{
        id: "instancecode",
        name: "Network/Instance Code",
        field: "instancecode",
        width: 150,
        sortable: true
    },{
        id: "tz",
        name: "Timezone",
        field: "tz",
        width: 150,
        sortable: true
    }];

    //set the options for the columns breaktypename
    var options = {
        enableCellNavigation: true,
        editable: false,
        forceFitColumns: true,
        enableColumnReorder: false,
        multiColumnSort: true,
        rowHeight: 30
    };


    var groupItemMetadataProvider = new Slick.Data.GroupItemMetadataProvider();

    dataView = new Slick.Data.DataView({
        groupItemMetadataProvider: groupItemMetadataProvider,
        inlineFilters: true
    });


    //create the datagrod
    grid = new Slick.Grid("#datagrid-scheduler-network-choice", dataView, columns, options);


    //register plugins
    grid.registerPlugin(groupItemMetadataProvider);
    grid.setSelectionModel(new Slick.RowSelectionModel());


    // wire up model events to drive the grid
    dataView.onRowCountChanged.subscribe(function(e, args) {
        grid.updateRowCount();
        grid.render();
    });

    dataView.onRowsChanged.subscribe(function(e, args) {
        grid.invalidateRows(args.rows);
        grid.render();
    });




    grid.onSort.subscribe(function(e, args) {
        gridSorter(args.sortCols, dataView);
    });



    function gridSorter(sortCols, dataview) {
        dataview.sort(function(row1, row2) {
            for (var i = 0, l = sortCols.length; i < l; i++) {
                var field = sortCols[i].sortCol.field;
                var sign = sortCols[i].sortAsc ? 1 : -1;
                var x = row1[field],
                    y = row2[field];
                var result = (x < y ? -1 : (x > y ? 1 : 0)) * sign;
                if (result != 0) {
                    return result;
                }
            }
            return 0;
        }, true);
    }




    this.groupByColumn = function(col) {
        groupby = col;

        if (col == 'off') {
            dataView.groupBy(null);
            return;
        }

        dataView.groupBy(col,
            function(g) {
                return "<span style='color:#32639a;font-weight: bold;'>" + g.value + "</span> - <span style='color:#32639a;font-weight: bold;'>(" + g.count + " items)</span>";
            },
            function(a, b) {
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



    this.expandSelectedGroup = function(group) {
        dataView.expandGroup(group);
    }



    this.populateDatagrid = function(xdata) {
        data = xdata;
       dataView.beginUpdate();
        dataView.setItems(data);
        dataView.reSort();
        dataView.endUpdate();

        grid.invalidate();
        grid.render();
    }




    //custom grid scaler render
    this.renderGrid = function() {
        grid.resizeCanvas();
    };


    //ids
    this.selectedIds = function() {
        var selectedData = [];
        var selectedIndexes = grid.getSelectedRows();

        jQuery.each(selectedIndexes, function(index, value) {
            selectedData.push(grid.getData().getItem(value).id);
        });

        return selectedData;
    }


    this.selectedRows = function() {
        var selectedData = [];
        var selectedIndexes = grid.getSelectedRows();

        jQuery.each(selectedIndexes, function(index, value) {
            selectedData.push(grid.getData().getItem(value));
        });

        return selectedData;
    }

    //empty grid
    this.emptyGrid = function() {
        data = [];
        dataView.beginUpdate();
        dataView.setItems(data);
        dataView.endUpdate();
        grid.resetActiveCell();
        grid.invalidateAllRows();
        grid.render();
    };


    //unselect rows
    this.unSelectAll = function() {
        grid.getSelectionModel().setSelectedRanges([]);
        grid.invalidate();
        grid.render();
        grid.resetActiveCell();
    }

    //select all rows
    this.selectAll = function() {
        var selectedRows = [];

        for (var i=0; i < data.length; i++){
            selectedRows.push(i);
        }

        return selectedRows;

        //grid.setSelectedRows(selectedRows); 
        //grid.invalidate();
        //grid.render();
        //grid.resetActiveCell();
    }
    //end main function
}