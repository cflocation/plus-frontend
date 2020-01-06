//function build datagrid
function DatagridHotTitlesSelected() {
    var self = this;
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
        id: "title",
        name: "Selected Titles",
        field: "title",
        width: 322,
        minWidth: 322
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
    grid = new Slick.Grid("#datagrid-hot-titles-selected", dataView, columns, options);


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


    this.populateDatagrid = function(xdata) {
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


    //rows
    this.selectedRows = function() {
        var selectedData = [];
        var selectedIndexes = grid.getSelectedRows();

        jQuery.each(selectedIndexes, function(index, value) {
            selectedData.push(grid.getData().getItem(value));
        });

        return selectedData;
    }


        //rows
    this.gridData = function() {
        return data;
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
        //grid.setData(data);
        //grid.render();
    };




    this.addRows = function(rows) {

        jQuery.each(rows, function(index, value) {
            var key = value.id;
            var dupe =  self.findKey(data,key);

            if(dupe == false){
                data.push(value);
            }
        });

        grid.resizeCanvas();
        self.populateDatagrid(data);
    }


    this.findKey = function(data,id){
        for(var i = 0; i < data.length; i++) {
            if(data[i]. id== id){
                return true;
            }
        }
        return false;
    }



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