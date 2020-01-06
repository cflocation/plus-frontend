//start the class file for the datagrid

function DatagridHeaders() {
	//setup all the basic varibles for the datagrid	
	var grid;
	var data = [];
	var dataView;
	var selectedRows = [];
	var sortcol = "created";
	var sortdir = 1;
	var loadProposalOnEvent = '';
	var editmode = false;

	//set the columns
	var columns = [
    	{
			id: "header", 
			name: "Title", 
			field: "header",
			sortable: true
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
	grid = new Slick.Grid("#datagrid-headers", dataView, columns, options);
	//register plugins
	grid.registerPlugin(groupItemMetadataProvider);
	grid.setSelectionModel(new Slick.RowSelectionModel());
	grid.registerPlugin(checkboxSelector);
	
	

	grid.onSelectedRowsChanged.subscribe(function(e, args) {
		if(args.cell == 0){
			return;
		}
		loadHeaderContent();
	});


	//do the sort thing
	grid.onSort.subscribe(function(e, args) {
		sortdir = args.sortAsc ? 1 : -1;
		sortcol = args.sortCol.field;

		dataView.sort(comparer, args.sortAsc);
	});

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
		grid.resetActiveCell();
		grid.getSelectionModel().setSelectedRanges([]);
	};


	//custom grid scaler render
	this.renderGrid = function(x) {
		grid.resizeCanvas();
	};



	//selected rows
	this.getSelectedRows = function() {
		var selectedData = [];
		var selectedIndexes = grid.getSelectedRows();

		jQuery.each(selectedIndexes, function(index, value) {
			selectedData.push(grid.getData().getItem(value));
		});
		
		return selectedData;
	}



	//unselect rows
	this.unSelectAll = function() {
		grid.resetActiveCell();

	}

	//select rows
	this.selectRows = function(rows){
		var re = [];
		var i = 0;

		$.each(data, function(index, value) {
			if(rows.indexOf(value.id) != -1){
				re.push(index);
				i = index;
			}
		});
		grid.setSelectedRows(re);
		grid.scrollRowIntoView(i);
	};

	/*END*/
}