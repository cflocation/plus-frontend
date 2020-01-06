//function build datagrid
function DatagridChanges() {
    var grid;
    var data = [];
    var dataView;
    var selectedRows = [];
    var dayparts = [];
    var groupby = "date";
    var keys = {};
    var avails = {};
    var sortcol = "date";
    var sortdir = 1;


    //set the columns
    var columns = [
    {
        id: "id", 
        name: "",
        field: "id", 
        width: 30,
        minWidth: 30,
        maxWidth: 30,
        formatter: Slick.Formatters.Viewrow2
    },{
        id: "indate", 
        name: "Date",
        field: "indate", 
        width: 50,
        maxWidth: 50,
        formatter: Slick.Formatters.Datetime2
    },{
        id: "emailfrom", 
        name: "From",
        field: "emailfrom", 
        width: 50,
        maxWidth: 50
    },{
        id: "subject", 
        name: "Subject",
        field: "subject",
        width: 100
    },{
        id: "finished", 
        name: "Complete",
        field: "finished",
        width: 100,
        minWidth: 100,
        maxWidth: 100,
    }];

    
    

    //set the options for the columns
    var options = {
        enableCellNavigation: true,
        editable: true,
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
    dataView.getItemMetadata = row_metadata(dataView.getItemMetadata);

    grid = new Slick.Grid("#datagrid-changes", dataView, columns, options);


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



    grid.onSort.subscribe(function(e, args) {
        gridSorter(args.sortCols, dataView);
    });





    //get the show information
    grid.onSelectedRowsChanged.subscribe(function(e, args) {
        var id = grid.getSelectedRows();
        var row = grid.getData().getItem(id);
        console.log(row);
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


    //metadata items for coloring
    function row_metadata(old_metadata_provider) {

        return function(row) {

            var item = this.getItem(row),
            ret = old_metadata_provider(row);


            if(item && item.groupid == 1) {
                ret = ret || {};
                ret.cssClasses = (ret.cssClasses || '') + ' showgroup';
            }


            return ret;
        };
    }




    this.groupByColumn = function(col) {
        groupby = col;

        if(col == 'off'){
            dataView.groupBy(null);
            return;
        }

        dataView.groupBy(col,
            function (g) {
                var availcount = avails[g.groupingKey].count;
                var weekday = Date.parse(g.value + " 05:00:00 ").toString("dddd - MM/dd/yy");
                return "" + weekday + " - <span style='color:#32639a;font-weight: bold;'>("  + availcount + " avails)</span>";
            },
            function (a, b) {
                return a.value - b.value;
            }
        );

        this.collapseAllGroups();
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
        dataView.endUpdate();
        grid.invalidateAllRows();
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
    
        console.log(selectedIndexes);

        jQuery.each(selectedIndexes, function (index, value) {
            selectedData.push(grid.getData().getItem(value));
        });

        return selectedData;
    }

    this.selectedRowIds = function() {
        var selectedData = [];
        var selectedIndexes = grid.getSelectedRows();
    
        jQuery.each(selectedIndexes, function (index, value) {
            selectedData.push(grid.getData().getItem(value).id);
        });

        return selectedData;
    }



    this.allRowIds = function() {
        var selectedRows = [];

        for (var i=0; i < data.length; i++){
            var x = data[i].id;
            selectedRows.push(x);
        }
        return selectedRows;
    }




    //unselect rows
    this.unSelectAll = function() {
        grid.getSelectionModel().setSelectedRanges([]);
        grid.invalidate();
        grid.render();
        grid.resetActiveCell();
    }



    //unselect rows
    this.selectAll = function() {
        console.log('ff');

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