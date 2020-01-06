//function build datagrid
function DatagridCustomrulewizard() {
    var self = this;
    var grid;
    var data = [];
    var dataView;
    var selectedRows = [];
    var dayparts = [];
    var groupby = "off";
    var sortcol = "breakclocktime";
    var sortdir = 1;
    var ddx;

    //set the columns
    var columns = [
        {
            id: "breakid",
            name: "Break#",
            field: "breakid",
            sortable: true,
            width: 120,
            minWidth: 120
        }, /*{
            id: "breakshowtime",
            name: "Break Time",
            field: "breakshowtime",
            width: 250,
            minWidth: 250
        },*/ {
            id: "breakclocktime",
            name: "Break Time",
            field: "breakclocktime",
            sortable: true,
            width: 250,
            minWidth: 250
        }, {
            id: "breaklength",
            name: "Break Length",
            field: "breaklength",
            sortable: true,
            width: 250,
            minWidth: 250
        }
    ];

    //set the options for the columns
    var options = {
        enableCellNavigation: true,
        forceFitColumns: false,
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
    grid = new Slick.Grid("#custom-breakrule-wizard-breakitems-grid", dataView, columns, options);

    //register plugins
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

    this.addNewRow = function(breakshowtime, breakclocktime, breaklength)
    {
        var nextBreakId = (dataView.getLength() > 0)?dataView.getLength()+1: 1;
        var newRow = {"id":nextBreakId,"breakid":nextBreakId,"breakshowtime":breakshowtime,"breakclocktime":breakclocktime,"breaklength":breaklength};
        dataView.addItem(newRow);
        dataView.refresh();
        grid.invalidateAllRows();
        grid.render();
        this.refreshSorting();
    }

    this.updateRow = function(idx, breakshowtime, breakclocktime, breaklength)
    {
        var item            = dataView.getItem(idx);
        item.breakshowtime  = breakshowtime;
        item.breakclocktime = breakclocktime;
        item.breaklength    = breaklength;

        dataView.updateItem(item.id, item);
        this.refreshSorting();
        
    }

    this.deleteRows = function(idxs)
    {
        jQuery.each(idxs, function(i, idx) {
            dataView.deleteItem(dataView.getItem(idx).id);
        });

        this.unSelectAll();
        this.refreshSorting();        
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

     //ids
    this.selectedIndexes = function() {
        return grid.getSelectedRows();
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
        //grid.setData(data);
        //grid.render();
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

    this.refreshSorting = function()
    {
        dataView.sort(function(row1, row2) {
            x= row1['breakclocktime'];
            y= row2['breakclocktime'];
            return Date.parse(x)-Date.parse(y);
        }, true);

        var newData = [];
        for(var i=0; i<dataView.getLength(); i++)
        {
            var r = dataView.getItem(i);
            r.id = i+1;
            r.breakid = i+1;
            newData.push(r);
            //dataView.updateItem(oldid, r);
        }
        console.log(newData);
        this.populateDatagrid(newData);
        this.renderGrid();
    }

    this.getAllRows = function() {
        return data;
    }

    //end main function
}