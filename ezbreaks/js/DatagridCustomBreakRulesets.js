//function build datagridDatagridCustomBreakRulesets.js
function DatagridCustomBreakRulesets() {
    var grid;
    var data = [];
    var dataView;
    var selectedRows = [];
    var dayparts = [];
    var groupby = "breaklabel";


    //set the columns
    var columns = [
    {
        id: "id",
        name: "",
        field: "id",
        width:30,
        minWidth: 30,
        formatter: Slick.Formatters.ViewCustomRuleItems
    },{
        id: "breaklabel",
        name: "Break Label",
        field: "breaklabel",
        width:150,
        minWidth: 150,
        formatter: Slick.Formatters.LongText
    },{
        id: "ruletype",
        name: "Rule Type",
        field: "ruletype",
        width:80,
        minWidth: 80,
        formatter: Slick.Formatters.Custombreakrulesettype
    },{
        id: "networkslist",
        name: "Networks",
        field: "networkslist",
        width:150,
        minWidth: 150,
        formatter: Slick.Formatters.LongText
    },{
        id: "instancecodeslist",
        name: "Instances",
        field: "instancecodeslist",
        width:170,
        minWidth: 200,
        formatter: Slick.Formatters.LongText
    },{
        id: "startdate",
        name: "Start Date",
        field: "startdate",
        width:90,
        minWidth: 90
    },{
        id: "enddate",
        name: "End Date",
        field: "enddate",
        width:90,
        minWidth: 90
    },{
        id: "starttime",
        name: "Start Time",
        field: "starttime",
        width:80,
        minWidth: 80,
        formatter: Slick.Formatters.Time
    },{
        id: "endtime",
        name: "End Time",
        field: "endtime",
        width:80,
        minWidth: 80,
        formatter: Slick.Formatters.Time
    },{
        id: "title",
        name: "Title",
        field: "title",
        width:200,
        minWidth: 200,
        formatter: Slick.Formatters.LongText
    },{
        id: "livesportsonly",
        name: "Only live sports",
        field: "livesportsonly",
        width:50,
        minWidth: 50
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

    grid = new Slick.Grid("#datagrid-custom-breaks-rulesets", dataView, columns, options);


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
    }

    this.collapseAllGroups = function() 
     {
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
        grid.invalidate();
        grid.render();
        dataView.endUpdate();
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