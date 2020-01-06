//start the class file for the datagrid

/*
REMOVED FOR NOW BY MARKE

, {
		id: "grossttl",
		name: "Gross",
		field: "grossttl",
		sortable: true,
		width: 60,
		maxWidth: 65,
		formatter: Slick.Formatters.Money
	}

	*/

function DatagridProposalManager() {
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
	var columns = [{
		id: "name",
		sortable: true,
		name: "Name",
		field: "name",
		width: 120
	
	}, {
		id: "zone",
		name: "Zone",
		sortable: true,
		field: "zone",
		width: 120
	}, {
		id: "linesttl",
		name: "Events",
		field: "events",
		sortable: true,
		width: 70,
		maxWidth: 70
	}/*, {
		id: "spots",
		name: "Spots",
		field: "spots",
		sortable: true,
		width: 50,
		maxWidth: 50
	}, {
		id: "netttl",
		name: "Gross",
		field: "netttl",
		sortable: true,
		width: 50,
		maxWidth: 65,
		formatter: Slick.Formatters.Money
	}*/,{
		id: "fstart",
		name: "Starts",
		field: "fstart",
		sortable: true,
		width: 70,
		maxWidth: 72,
		formatter: Slick.Formatters.FormatDate
	},{
		id: "fend",
		name: "Ends",
		field: "fend",
		sortable: true,
		width: 70,
		maxWidth: 72,
		formatter: Slick.Formatters.FormatDate
	},{
		id: "created",
		name: "Created",
		field: "created",
		sortable: true,
		width: 70,
		maxWidth: 72,
		formatter: Slick.Formatters.FormatDate
	},{
		id: "updatedat",
		name: "Updated",
		field: "updatedat",
		sortable: true,
		width: 70,
		maxWidth: 72,
		formatter: Slick.Formatters.FormatDate
	}/*,
	{	id: "delCol", 
		name: "Delete", 
        id: "id",		
		sortable: false, 
		width: 50, 
		minWidth: 20, 
		maxWidth: 50, 
		formatter: Slick.Formatters.DeleteFromGrid
	}*/];

	

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
	grid = new Slick.Grid("#proposal-list", dataView, columns, options);
	//register plugins
	grid.registerPlugin(groupItemMetadataProvider);
	grid.setSelectionModel(new Slick.RowSelectionModel());
	grid.registerPlugin(checkboxSelector);
	
	

	grid.onClick.subscribe(function(e, args) {
		if(args.cell == 0){
			return;
		}
		/*if(args.cell == 8){
		proposalDeleteCheckedConfirmation();
			return;
		}*/
		var row = grid.getData().getItem(args.row);
		loadProposalFromServer(row.id);
		resetSorting();
		$('#download-proposal-list').val(row.id);
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
	
	/*this.addRow = function(newrow){		
		console.log(newrow)
		data.unshift(newrow);
		grid.updateRowCount();
		grid.render();
		
	}*/

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



	//selected rows
	this.getSelectedRows = function() {
		var selectedData = [];
		var selectedIndexes = grid.getSelectedRows();

		jQuery.each(selectedIndexes, function(index, value) {
			selectedData.push(grid.getData().getItem(value));
		});
		
		return selectedData;
	}

	

	//select row
	this.setSelectRow = function(row) {
		var pslId = parseInt(row)
		jQuery.each(data, function(index, value) {
			if(parseInt(value.id) == pslId){
				grid.setSelectedRows([index]);
				return;
			}
		});
	}



	//unselect rows
	this.unSelectAll = function() {
		grid.resetActiveCell();

	}

	//selected rows
	this.getProposalInfo = function(id){
		return  dataView.getItemById(id);
	};

	/*END*/
}