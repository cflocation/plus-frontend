//start the class file for the datagrid
function DatagridSavedSearches() {

	//setup all the basic varibles for the datagrid	
	var grid;
	var data = [];
	var dataView;
	var selectedRows = [];

	//set the columns
	var columns = [
	/*{
		id: "reminder",
		name: "Reminder",
		field: "reminder",
		width: 70,
		minWidth: 70,
		maxWidth: 70,
		formatter: Slick.Formatters.Reminder
	},*/
	{
		id: "name",
		name: "Name",
		field: "name",
		width: 200,
		formatter: Slick.Formatters.Escape
	},{
		id: "notes",
		name: "Notes",
		field: "notes",
		width: 100,
		formatter: Slick.Formatters.Escape
	},{
		id: "createdat",
		name: "Created",
		field: "createdat",
		width: 70,
		minWidth: 70,
		maxWidth: 70,
		formatter: Slick.Formatters.FormatDate
	},{
		id: "updatedat",
		name: "Updated",
		field: "updatedat",
		width: 70,
		minWidth: 70,
		maxWidth: 70,
		formatter: Slick.Formatters.FormatDate
	}];

	var checkboxSelector = new Slick.CheckboxSelectColumn({
		cssClass: "slick-cell-checkboxsel"
	});

	columns.unshift(checkboxSelector.getColumnDefinition());

	//set the options for the columns
	var options = {
		enableCellNavigation: true,
		editable: true,
		forceFitColumns: true,
		enableColumnReorder: false,
		multiColumnSort: true,
		rowHeight: 30
	};


	//set the dataview to the grid
	dataView = new Slick.Data.DataView({
		inlineFilters: true
	});
	//create the datagrid
	grid = new Slick.Grid("#saved-searches-datagrid", dataView, columns, options);

	//register plugins
	grid.setSelectionModel(new Slick.RowSelectionModel());
	grid.registerPlugin(checkboxSelector);
	
	grid.onSelectedRowsChanged.subscribe(function() {
		updateSelectedNetworks();
	});
	

	grid.onClick.subscribe(function(e, args) {
		var row = grid.getData().getItem(args.row);
		row = row.search;

		var jdata = jQuery.parseJSON(row);
		saveSearchLoad(jdata);
	});

	//method to populate the datagrid
	this.populateDataGrid = function(xdata) {
		grid.setSelectedRows([]);
		//this.emptyGrid();
		data = xdata;
		//do the dataview stuff
		dataView.beginUpdate();
		dataView.setItems(data);
		dataView.endUpdate();
		grid.invalidate();
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
	}

	//custom grid scaler render
	this.getRow = function() {
		return selectedRows;
	};

	//custom grid scaler render
	this.renderGrid = function() {
		grid.resizeCanvas();
	};

	//empty grid
	this.emptyGrid = function() {
		dataView.beginUpdate();
		dataView.getItems().length = 0;
		dataView.endUpdate();
	};


		//unselect rows
	this.unSelectAll = function() {
		grid.setSelectedRows([]);
		grid.resetActiveCell();
	}

}