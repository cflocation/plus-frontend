//function build datagrid
function DatagridHotProgramming() {

	var grid;
	var data = [];
	var dataView;
	var selectedRows = [];
	var dayparts = [];
    var groupby = "name";
    var sortcol = "showtitle";
    var sortdir = 1;


	//set the columns
	var columns = [
        {
            id: "network",
            name: "Network",
            field: "network",
            width:125, 
            minWidth:125,
            maxWidth:125
        },{
            id: "showtitle",
            name: "Show",
            field: "showtitle",
            width:100, 
            minWidth:100,
            sortable: true
        },
        {
            id: "boosttype",
            name: "",
            field: "boosttype",
            width:50, 
            minWidth:50,
            maxWidth:50,
            editor: Slick.Editors.MoneyPercentSelect,
            formatter: Slick.Formatters.Hottype,
            cssClass: 'centertext rate'
        },
        {
            id: "boost",
            name: "Daypart +",
            field: "boost",
            width:75, 
            minWidth:75,
            maxWidth:75,
            editor: Slick.Editors.Text,
            cssClass: 'centertext rate'
        },
        {
            id: "premieretype",
            name: "",
            field: "premieretype",
            width:50, 
            minWidth:50,
            maxWidth:50,
            editor: Slick.Editors.MoneyPercentSelect,
            formatter: Slick.Formatters.Hottype,
            cssClass: 'centertext ratefixed'
            
        },
        {
            id: "premiere",
            name: "Premiere",
            field: "premiere",
            width:70, 
            minWidth:70,
            maxWidth:70,
            editor: Slick.Editors.Text,
            cssClass: 'centertext ratefixed'
        },
        {
            id: "finaletype",
            name: "",
            field: "finaletype",
            width:50, 
            minWidth:50,
            maxWidth:50,
            editor: Slick.Editors.MoneyPercentSelect,
            formatter: Slick.Formatters.Hottype,
            cssClass: 'centertext ratepct'
            
        },
        {
            id: "finale",
            name: "Finale",
            field: "finale",
            width:55, 
            minWidth:55,
            maxWidth:55,
            editor: Slick.Editors.Text,
            cssClass: 'centertext ratepct'
        },
        {
            id: "newtype",
            name: "",
            field: "newtype",
            width:50, 
            minWidth:50,
            maxWidth:50,
            editor: Slick.Editors.MoneyPercentSelect,
            formatter: Slick.Formatters.Hottype,
            cssClass: 'centertext gridcolor4'
            
        },
        {
            id: "isnew",
            name: "New",
            field: "isnew",
            width:50, 
            minWidth:50,
            maxWidth:50,
            editor: Slick.Editors.Text,
            cssClass: 'centertext gridcolor4'
        },
        {
            id: "livetype",
            name: "",
            field: "livetype",
            width:50, 
            minWidth:50,
            maxWidth:50,
            editor: Slick.Editors.MoneyPercentSelect,
            formatter: Slick.Formatters.Hottype,
            cssClass: 'centertext gridcolor5'
            
        },
        {
            id: "live",
            name: "Live",
            field: "live",
            width:50, 
            minWidth:50,
            maxWidth:50,
            editor: Slick.Editors.Text,
            cssClass: 'centertext gridcolor5'
        }
    ];




	//set the options for the columns
	var options = {
		enableCellNavigation: true,
		editable: true,
		forceFitColumns: true,
		enableColumnReorder: false,
		multiColumnSort: false,
		rowHeight: 30,
        autoEdit: true
	};


	var groupItemMetadataProvider = new Slick.Data.GroupItemMetadataProvider();

	dataView = new Slick.Data.DataView({
		groupItemMetadataProvider: groupItemMetadataProvider,
		inlineFilters: true
	});


	grid = new Slick.Grid("#datagrid-hot-programming", dataView, columns, options);


	//register plugins
    //grid.setSelectionModel(new Slick.CellSelectionModel());
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


    //sorting
    function comparer(a,b) {
        var x = a[sortcol], y = b[sortcol];
        return (x == y ? 0 : (x > y ? 1 : -1));
    }


    //before you destroy the cell edit reset the edit mode to false
    grid.onCellChange.subscribe(function(e, args) {
        var x = args.cell;
        var i = args.row;
        var col = columns[x].field;
        //var items = datagridRatecardPricing.colItem(col);
        var type = $('input[name=rate-mode-toggle]:checked').val();
        dataView.beginUpdate();
        dataView.setItems(data);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
        saveHotProgrammingSilent();
    });




    this.removeRows = function(){

        var id = grid.getSelectedRows();
        id.sort(function(a,b){return b-a});

        for(var i = 0; i < id.length; i++) {

            var row = grid.getData().getItem(id[i]);
            
            //if the row is a group then lets delete them all
            if(row.__group == true){
                $.each(row.rows, function(i, rid){
                    
                    $.each(data, function(i, value){
                        if(value.id == rid.id){
                            data.splice(i, 1);
                            return false;
                        }
                    })
                    
                });
            }else{
                $.each(data, function(i, value){
                    if(value.id == row.id){
                        data.splice(i, 1);
                        return false;
                    }
                })
            }
        }


        dataView.beginUpdate();
        dataView.setItems(data);
        dataView.sort(comparer, true);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
    }


    this.populateDatagrid = function(xdata){
    	data = xdata;
    	dataView.beginUpdate();
        dataView.setItems(data);
        dataView.sort(comparer, true);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
    }


    //get data
    this.getData = function(){
        return data;
    };



    //custom grid scaler render
    this.renderGrid = function(){
        grid.resizeCanvas();
    };


    //custom grid scaler render
    this.addShows = function(shows,networkid,network){
        var scrollto;
        jQuery.each(shows, function (index, value) {

            var f = false;
            var rowid = value['id']+'-'+networkid;
            scrollto = rowid;
            for(var i = 0; i < data.length; i++) {

                if(data[i].id == rowid){
                    f = true;
                }
            }

            if(f == false){
                var row = {};
                row.network = network;
                row.networkid = networkid;
                row.id = rowid;
                row.showtitle = value['title'];
                row.boosttype = false;
                row.boost = 0;
                row.premieretype = true;
                row.premiere = 0;
                row.finaletype = true;
                row.finale = 0;
                row.newtype = true;
                row.isnew = 0;
                row.livetype = true;
                row.live = 0;
                data.push(row);
            }

        });

        dataView.beginUpdate();
        dataView.setItems(data);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();

        grid.scrollRowIntoView(data.length);
    };



    this.setRate = function(rates){
        var rows = datagridHotProgramming.selectedRows();

        
        $.each(rows, function(i, value) {

            if(rates.daypart != ""){
                value['boost'] = rates.daypart;
            }
            
            if(rates.premiere != ""){
                value['premiere'] = rates.premiere;
            }

            if(rates.finale != ""){
                value['finale'] = rates.finale;
            }

            if(rates.isnew != ""){
                value['isnew'] = rates.isnew;
            }

            if(rates.live != ""){
                value['live'] = rates.live;
            }


            //value[type] = rate;
        });
        
        grid.invalidate();
        grid.render();
    }



    this.updateNetwork = function(network,networkid){
        var selectedIndexes = grid.getSelectedRows();

        if(selectedIndexes.length == 0){
            return;
        }

        jQuery.each(selectedIndexes, function (index, value) {
            data[value].network = network;
            data[value].networkid = networkid;
        });

        grid.invalidate();
        grid.render();
    }
    


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


//end main function
}




