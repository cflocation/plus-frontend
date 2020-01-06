//function build datagrid
function datagridHotProgramming() {

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
            width:35, 
            minWidth:35,
            maxWidth:35,
            editor: Slick.Editors.MoneyPercentSelect,
            formatter: Slick.Formatters.Hottype
        },
        {
            id: "boost",
            name: "Daypart +",
            field: "boost",
            width:60, 
            minWidth:60,
            maxWidth:60,
            editor: Slick.Editors.Text
        },
        {
            id: "premieretype",
            name: "",
            field: "premieretype",
            width:25, 
            minWidth:25,
            maxWidth:25,
            editor: Slick.Editors.MoneyPercentSelect,
            formatter: Slick.Formatters.Hottype
            
        },
        {
            id: "premiere",
            name: "Premiere",
            field: "premiere",
            width:60, 
            minWidth:60,
            maxWidth:60,
            editor: Slick.Editors.Text
        },
        {
            id: "finaletype",
            name: "",
            field: "finaletype",
            width:25, 
            minWidth:25,
            maxWidth:25,
            editor: Slick.Editors.MoneyPercentSelect,
            formatter: Slick.Formatters.Hottype
            
        },
        {
            id: "finale",
            name: "Finale",
            field: "finale",
            width:55, 
            minWidth:55,
            maxWidth:55,
            editor: Slick.Editors.Text
        },
        {
            id: "newtype",
            name: "",
            field: "newtype",
            width:25, 
            minWidth:25,
            maxWidth:25,
            editor: Slick.Editors.MoneyPercentSelect,
            formatter: Slick.Formatters.Hottype
            
        },
        {
            id: "isnew",
            name: "New",
            field: "isnew",
            width:50, 
            minWidth:50,
            maxWidth:50,
            editor: Slick.Editors.Text
        },
        {
            id: "livetype",
            name: "",
            field: "livetype",
            width:25, 
            minWidth:25,
            maxWidth:25,
            editor: Slick.Editors.MoneyPercentSelect,
            formatter: Slick.Formatters.Hottype
            
        },
        {
            id: "live",
            name: "Live",
            field: "live",
            width:50, 
            minWidth:50,
            maxWidth:50,
            editor: Slick.Editors.Text
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
        autoEdit: false
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
        var items = datagridRatecardPricing.colItem(col);
        var type = $('input[name=rate-mode-toggle]:checked').val();
        


        //if this is a daypart lets go ahead and abd set the rate. We have other functions to set percents 
        if(items.daypartname == 'daypart'){
        }
    
        dataView.beginUpdate();
        dataView.setItems(data);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
   
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
    this.addShows = function(shows){


        //console.log(shows);

        jQuery.each(shows, function (index, value) {

            var f = false;

            for(var i = 0; i < data.length; i++) {
                if(data[i].id == value['id']){
                    f = true;
                }
            }
        

            if(f == false){
                var row = {};
                row.id = value['id'];
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
    };



    this.setRate = function(rate,type){
        var rows = datagridHotProgramming.selectedRows();

        $.each(rows, function(i, value) {
            value[type] = rate;
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




