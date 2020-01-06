//function build datagrid
function DatagridAccessNetworks() {

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
        id: "callsign",
        name: "Callsign",
        field: "callsign",
        width: 100,
        maxWidth: 100,
        sortable: true
    },{
        id: "name",
        name: "Name",
        field: "name",
        minWidth: 100,
        sortable: true
    },{
        id: "instancecode",
        name: "Network/Instance Code",
        field: "instancecode",
        width: 150,
        sortable: true
    },{
        id: "tzname",
        name: "Timezone",
        field: "tzname",
        width: 150,
        sortable: true
    }];



    var checkboxSelector = new Slick.CheckboxSelectColumn({
        cssClass: "slick-cell-checkboxsel"
    });

    columns.unshift(checkboxSelector.getColumnDefinition());

    //set the options for the columns breaktypename
    var options = {
        enableCellNavigation: true,
        enableColumnReorder: false,
        editable: false,
        forceFitColumns: true,
        asyncEditorLoading: false,
        multiColumnSort: true,
        rowHeight: 30
    };


    var groupItemMetadataProvider = new Slick.Data.GroupItemMetadataProvider();

    dataView = new Slick.Data.DataView({
        groupItemMetadataProvider: groupItemMetadataProvider,
        inlineFilters: true
    });


    //create the datagrod
    grid = new Slick.Grid("#datagrid-access", dataView, columns, options);



    //register plugins
    grid.registerPlugin(groupItemMetadataProvider);
    grid.setSelectionModel(new Slick.RowSelectionModel({selectActiveRow: false}));
    grid.registerPlugin(checkboxSelector);


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



    this.getCheckedNetworks = function(){
        var selectedData = [];
        var selectedIndexes = grid.getSelectedRows();
    
        jQuery.each(selectedIndexes, function (index, value) {
            selectedData.push(grid.getData().getItem(value).tmsid);
        });

        return selectedData;
    }

    this.getCheckedNetworkInstances = function(){
        var selectedData = [];
        var selectedIndexes = grid.getSelectedRows();
    
        jQuery.each(selectedIndexes, function (index, value) {
            selectedData.push(grid.getData().getItem(value).id);
        });

        return selectedData;
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
        dataView.reSort();
        dataView.endUpdate();

        grid.invalidate();
        grid.render();
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


    //custom grid scaler render
    this.renderGrid = function() {
        grid.resizeCanvas();
    };


    this.selectRowsWithNetworkInstances = function(instances)
    {
        var indexes = [];
        for(var i=0; i<grid.getDataLength(); i++)
        {
           if(instances.indexOf(grid.getData().getItem(i).id) != -1)
           {            
                indexes.push(i);
           }
        }
        grid.setSelectedRows(indexes);
    };

}



