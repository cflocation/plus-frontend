//function build datagrid
function DatagridDownloadSchedule() {
    var grid;
    var data = [];
    var dataView;
    var selectedRows = [];
    var dayparts = [];
    var groupby = "date";


    //set the columns
    var columns = [
    {
        id: "days",
        name: "Day(s) of Week",
        field: "daysstr",
        width:150,
        minWidth: 150,
        maxWidth:150
    },{
        id: "time",
        name: "Time",
        field: "tztime",
        width:200,
        minWidth: 100,
        maxWidth: 100,
        formatter: Slick.Formatters.Time
    },{
        id: "timezone",
        name: "Timezone",
        field: "tzabbr",
        width:20,
        minWidth: 20,
        maxWidth: 100
    },{
        id: "filename",
        name: "Label",
        field: "filename",
        width:20,
        minWidth: 20,
        maxWidth: 100
        
    },{
        id: "networks",
        name: "Networks",
        field: "networksstr",
        width:100,
        minWidth: 100
        
    },{
        id: "emails",
        name: "Emails",
        field: "emailsstr",
        width:100,
        minWidth: 100
    }];

    
    

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
    dataView.getItemMetadata = row_metadata(dataView.getItemMetadata);

    grid = new Slick.Grid("#datagrid-download-scheduler", dataView, columns, options);


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
                return "" + g.value + " - <span style='color:#32639a;font-weight: bold;'>(" + g.count + " items)</span>";
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

    this.populateDatagrid = function(xdata){
        data = xdata;

        /*jQuery.each(data, function (index, value) {
            //var d = Date.parse(value.date + " 06:00:00").toString("dddd");
            //value.weekday = d;

            var groupmain = Date.parse(value.date + " " + value.starttime).toString("yyyyMMddHHmm");
            value.groupmain = groupmain;


            var groupdate = Date.parse(value.date + " " + value.starttime).toString("MM/dd/yyyy");
            var grouptime = Date.parse(value.date + " " + value.starttime).toString("hh:mm tt");

            grouptitle = groupdate + " <b>at</b> " + grouptime + " - " + value.title;
            value.grouptitle = grouptitle;

        });*/

        dataView.beginUpdate();
        dataView.setItems(data);
        grid.invalidate();
        grid.render();
        dataView.endUpdate();
        this.groupByColumn(null);
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