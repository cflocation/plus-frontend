//function build datagrid
function DatagridRatecards() {

    var grid;
    var data = [];
    var dataView;
    var selectedRows = [];
    var dayparts = [];
    var groupby = "name";
    var sortcol = "zone";
    var sortdir = 1;


    //set the columns
    var columns = [{
        id: "id",
        name: "",
        field: "id",
        width: 30,
        minWidth: 30,
        maxWidth: 30,
        formatter: Slick.Formatters.Item
    }, {
        id: "published",
        name: "",
        field: "published",
        width: 30,
        minWidth: 30,
        maxWidth: 30,
        formatter: Slick.Formatters.Published
    }, {
        id: "name",
        name: "Group Name",
        field: "name",
        width: 80,
        minWidth: 100,
        sortable: true
    }, {
        id: "zone",
        name: "Zone",
        field: "zone",
        width: 130,
        minWidth: 100,
        sortable: true
    }, {
        id: "special",
        name: "Priority",
        field: "special",
        width: 80,
        minWidth: 80,
        sortable: true,
        formatter: Slick.Formatters.Special
    }, {
        id: "broadcast",
        name: "Broadcast",
        field: "broadcast",
        width: 80,
        minWidth: 80,
        sortable: true,
        formatter: Slick.Formatters.Yesno
    }, {
        id: "syscode",
        name: "Syscode",
        field: "syscode",
        width: 100,
        minWidth: 75,
        sortable: true
    }, {
        id: "startdate",
        name: "Start Date",
        field: "startdate",
        width: 100,
        minWidth: 100,
        formatter: Slick.Formatters.Datetime,
        sortable: true
    }, {
        id: "enddate",
        name: "End Date",
        field: "enddate",
        width: 100,
        minWidth: 100,
        formatter: Slick.Formatters.Datetime,
        sortable: true
    }];

    //set the options for the columns
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
    grid = new Slick.Grid("#datagrid-ratecards", dataView, columns, options);


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

        //this.collapseAllGroups();
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


    this.populateDatagrid = function(xdata, group) {
        data = xdata;
        dataView.beginUpdate();
        dataView.setItems(data);
        //dataView.sort(comparer, true);
        dataView.endUpdate();
        grid.invalidateAllRows();
        grid.render();

        if (group == true) {
            this.groupByColumn('name');
            //this.collapseAllGroups();
        }
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






    //rows
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

    this.formatTime = function(d) {
        var re;
        var min = Date.parse("01/01/1980 " + d).toString("mm");

        if (min == "00") {
            re = Date.parse("01/01/1980 " + d).toString("ht");
        } else {
            re = Date.parse("01/01/1980 " + d).toString("h:mmt");
        }



        if (re == "0A") {
            return "12M";
        }

        if (re == "11:59P") {
            return "12A";
        }
        return re;
    }


    //end main function
}