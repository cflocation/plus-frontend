//function build datagrid
function datagridShowtitles() {

	var grid;
	var data = [];
	var dataView;
	var selectedRows = [];
	var dayparts = [];
    var groupby = "name";
    var searchString = '';


	//set the columns
	var columns = [
    {
        id: "title",
        name: "Title",
        field: "title",
        width:180, 
        minWidth:180
    }

    ];


	//set the options for the columns
	var options = {
		enableCellNavigation: true,
		editable: false,
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


	grid = new Slick.Grid("#grid-showtitles", dataView, columns, options);


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


    //filter by the title typed int he above box

    function myFilter(item, args) {
        if(args.searchString !== "" && item.title !== "" && item.title.toLowerCase().indexOf(args.searchString.toLowerCase()) === -1) {
            return false;
        }
        return true;
    }
    

    this.updatFromKeyword = function(e, val) {
        grid.resetActiveCell();
        Slick.GlobalEditorLock.cancelCurrentEdit();
        // clear on Esc
        if(e.which === 27) {
            this.value = "";
        }

        searchString = val;
        updateFilter();
    }



    function updateFilter() {
        dataView.setFilterArgs({
            searchString: searchString
        });

        dataView.setFilter(myFilter);
        dataView.refresh();
        grid.invalidate();
        grid.render();
    }


    this.selectFirstItem = function(){
        grid.setSelectedRows([0]);
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




