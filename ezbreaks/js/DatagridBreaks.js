//function build datagrid
function DatagridBreaks() {

    var self = this;
    var grid;
    var data = [];
    var dataView;
    var selectedRows = [];
    var dayparts = [];
    var groupby = "weekday";
    var sortcol = "zone";
    var sortdir = 1;


    //set the columns
    var columns = [{
        id: "breaktime",
        name: "Break Time",
        field: "breaktime",
        width: 100,
        sortable: true
    },{
        id: "d1",
        name: "Mon",
        field: "d1",
        width: 100,
        sortable: true,
        formatter: Slick.Formatters.NoBreak
    },{
        id: "d2",
        name: "Tue",
        field: "d2",
        width: 100,
        sortable: true,
        formatter: Slick.Formatters.NoBreak
    },{
        id: "d3",
        name: "Wed",
        field: "d3",
        width: 100,
        sortable: true,
        formatter: Slick.Formatters.NoBreak
    },{
        id: "d4",
        name: "Thu",
        field: "d4",
        width: 100,
        sortable: true,
        formatter: Slick.Formatters.NoBreak
    },{
        id: "d5",
        name: "Fri",
        field: "d5",
        width: 100,
        sortable: true,
        formatter: Slick.Formatters.NoBreak
    },{
        id: "d6",
        name: "Sat",
        field: "d6",
        width: 100,
        sortable: true,
        formatter: Slick.Formatters.NoBreak
    },{
        id: "d7",
        name: "Sun",
        field: "d7",
        width: 100,
        sortable: true,
        formatter: Slick.Formatters.NoBreak
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
    grid = new Slick.Grid("#datagrid-breaks", dataView, columns, options);


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





    this.populateDatagrid = function(xdata, group) {
        data = xdata;
        dataView.beginUpdate();
        dataView.setItems(data);
        dataView.endUpdate();
        grid.invalidateAllRows();
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
        grid.setSelectedRows(selectedRows); 
        grid.invalidate();
        grid.render();
        grid.resetActiveCell();
    }
    //end main function
}