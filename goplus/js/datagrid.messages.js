//start the class file for the datagrid

function DatagridMessages() {
	//setup all the basic varibles for the datagrid	
	var grid;
	var data = [];
	var dataView;
	var selectedRows = [];
	var sortcol = "createdAt";
	var sortdir = 1;
	var loadProposalOnEvent = '';
	var editmode = false;

	//set the columns
	var columns = [
		{
			id: "hasread", 
			name: "", 
			field: "hasread",
			width:25, 
    		minWidth:25, 
    		maxWidth:25,
    		formatter: Slick.Formatters.Eye
    	},
		{
			id: "sentfrom", 
			name: "From", 
			field: "sentfrom",
            sortable: true,			
			width:100, 
    		minWidth:100, 
    		maxWidth:100
    	},
		{
			id: "subject", 
			name: "Title/Subject", 
			field: "subject",
            sortable: true,			
    		formatter: Slick.Formatters.Escape
    	},
    	{
			id: "type", 
			name: "Type", 
			field: "type",
            sortable: true,
			width:150, 
    		minWidth:150, 
    		maxWidth:150,
    		formatter: Slick.Formatters.Escape
    	},
	    {
	    	id: "createdat", 
	    	name: "Sent", 
	    	field: "createdat",
	    	width:125, 
    		minWidth:125, 
    		maxWidth:125,
            sortable: true,    		
    		formatter: Slick.Formatters.FormatDate
    	}
	];

	

	var checkboxSelector = new Slick.CheckboxSelectColumn({
		cssClass: "slick-cell-checkboxsel"
	});

	columns.unshift(checkboxSelector.getColumnDefinition());


	//set the options for the columns
	var options = {
		enableCellNavigation: true,
		editable: false,
		forceFitColumns: true,
		enableColumnReorder: false,
		rowHeight: 30
	};


	//set the dataview to the grid
	var groupItemMetadataProvider = new Slick.Data.GroupItemMetadataProvider();

	dataView = new Slick.Data.DataView({
		groupItemMetadataProvider: groupItemMetadataProvider,
		inlineFilters: true
	});


	//create the datagrid
	grid = new Slick.Grid("#datagrid-messages", dataView, columns, options);
	//register plugins
	grid.registerPlugin(groupItemMetadataProvider);
	grid.setSelectionModel(new Slick.RowSelectionModel());
	grid.registerPlugin(checkboxSelector);
	
	

	grid.onSelectedRowsChanged.subscribe(function(e, args) {
		if(args.cell == 0){
			return;
		}
		loadMessageContent();
	});


	//do the sort thing
	grid.onSort.subscribe(function(e, args) {
		sortdir = args.sortAsc ? 1 : -1;
		sortcol = args.sortCol.field;

		dataView.sort(comparer, args.sortAsc);
	});
	
	/*grid.onClick.subscribe(function(e, args) {
		var rowIdx = args.row;
	  	var row = dataView.getItem(rowIdx);
		row['hasread'] = 1;
		row['hasRead'] = 1;		
		grid.invalidateRow(rowIdx);		
		grid.render();
	});	*/
	

	// wire up model events to drive the grid
	dataView.onRowCountChanged.subscribe(function(e, args) {
		grid.updateRowCount();
		grid.render();
	});

	dataView.onRowsChanged.subscribe(function(e, args) {
		grid.invalidateRows(args.rows);
		grid.render();
	});



	//sorting
	function comparer(a, b) {
		var x = a[sortcol],
			y = b[sortcol];
		return(x === y ? 0 : (x > y ? 1 : -1));
	}

	//method to populate the datagrid
	this.populateDataGrid = function(x) {
		data = x;
		dataView.beginUpdate();
		dataView.setItems(x);
		dataView.sort(comparer, false);
		dataView.endUpdate();
		grid.invalidate();
		grid.render();

		grid.getSelectionModel().setSelectedRanges([]);
	};


	//custom grid scaler render
	this.renderGrid = function(x) {
		grid.resizeCanvas();
	};
	
	this.getSelectedIndexById = function(id){
		return dataView.getIdxById(id);
	};

	this.updateRowByIndex = function(rowIdx){
		grid.invalidateRow(rowIdx);		
		grid.render();		
	};

	//selected rows
	this.getSelectedRows = function() {
		var selectedData = [];
		var selectedIndexes = grid.getSelectedRows();

		jQuery.each(selectedIndexes, function(index, value) {
			selectedData.push(grid.getData().getItem(value));
		});
		
		return selectedData;
	};



	//unselect rows
	this.unSelectAll = function() {
		grid.resetActiveCell();

	};


	/*END*/
}