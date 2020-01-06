//start the class file for the datagrid

function DatagridTags(removable) {

	//setup all the basic varibles for the datagrid a
	var grid;
	var data = [];
	var dataView;
	var selectedRows = [];
	var sortcol = "title";
	var sortdir = 1;
	var searchString = '';

	//set the columns
	var columns = [{
		id: "title",
		name: "Title/Actor",
		field: "title",
		sortable: true
	},
	{
		id: "type",
		name: "Type",
		field: "type",
		sortable: true,
		width: 50,
		minWidth: 50,
		maxWidth: 50,
		//formatter: Slick.Formatters.Heart
		
	}];

	if(removable) {
		columns.push({
			id: "del",
			name: 'Remove',
			field: "del",
			sortable: true,
			width: 70,
			minWidth: 70,
			maxWidth: 70,
			formatter: Slick.Formatters.DeleteFromGrid
		});
	}

	//set the options for the columns
	var options = {
		enableCellNavigation: true,
		editable: false,
		forceFitColumns: true,
		enableColumnReorder: false,
		rowHeight: 30
	};

	var z = {};
	z.id = 1;
	z.type = 'Title';
	z.title = 'Pawn Stars';


	data.push(z);



	//set the dataview to the grid
	var groupItemMetadataProvider = new Slick.Data.GroupItemMetadataProvider();

	//setup the 
	dataView = new Slick.Data.DataView({
		groupItemMetadataProvider: groupItemMetadataProvider
	});


	//create the datagrid
	grid = new Slick.Grid("#tags-datagrid", dataView, columns, options);




	//register plugins
	grid.registerPlugin(groupItemMetadataProvider);
	grid.setSelectionModel(new Slick.RowSelectionModel());

	//sorting the datagrid
	grid.onSort.subscribe(function(e, args) {
		sortdir = args.sortAsc ? 1 : -1;
		sortcol = args.sortCol.field;
		dataView.sort(comparer, args.sortAsc);
		grid.invalidate();
		grid.render();
	});


	grid.onDragInit.subscribe(function(e, dd) {
		unbindProposalDatagrid();
		// prevent the grid from cancelling drag'n'drop by default
		e.stopImmediatePropagation();
	});


	grid.onDragStart.subscribe(function(e, dd) {


		var cell = grid.getCellFromEvent(e);
		if(!cell) {
			return;
		}

		dd.row = cell.row;

		if(!data[dd.row]) {
			return;
		}

		if(Slick.GlobalEditorLock.isActive()) {
			return;
		}

		e.stopImmediatePropagation();
		dd.mode = "recycle";

		var selectedRows = grid.getSelectedRows();



		if(!selectedRows.length || $.inArray(dd.row, selectedRows) === -1) {
			selectedRows = [dd.row];
			grid.setSelectedRows(selectedRows);
		}

		dd.rows = selectedRows;
		dd.count = selectedRows.length;

		var proxy = $("<span></span>").css({
			position: "absolute",
			display: "inline-block",
			padding: "4px 10px",
			background: "#e0e0e0",
			border: "1px solid gray",
			"z-index": 99999,
			"-moz-border-radius": "8px",
			"-moz-box-shadow": "2px 2px 6px silver"
		}).text("Drag the selected lines into your list").appendTo("body");

		dd.helper = proxy;



		return proxy;
	});


	// .text("Drag to porposal " + dd.count + " selected row(s)")
	grid.onDrag.subscribe(function(e, dd) {
		if(dd.mode !== "recycle") {
			return;
		}
		e.stopImmediatePropagation();
		dd.helper.css({
			top: e.pageY + 5,
			left: e.pageX + 5
		});
	});

	grid.onDragEnd.subscribe(function(e, dd) {
		if(dd.mode !== "recycle") {
			return;
		}
		//we need to unbind the bottom datagrid since it affect the title search datagrid
		//bindProposalDatagrid();
		e.stopImmediatePropagation();
		dd.helper.remove();
	});




	//method to populate the datagrid
	this.populateDataGridFromArray = function(xdata) {
		data = xdata;

		//do the dataview stuff
		dataView.beginUpdate();
		dataView.setItems(xdata);
		dataView.endUpdate();
		dataView.sort(comparer, true);
		grid.getSelectionModel().setSelectedRanges([]);
		grid.invalidate();
		grid.render();

		//this.unSelectAll();
	};


	this.populateDataGridFromArray(data);


	//get the selected rows
	this.selectedGetSelectedRows = function() {
		var selectedData = [];
		var selectedIndexes = grid.getSelectedRows();

		jQuery.each(selectedIndexes, function(index, value) {
			selectedData.push(grid.getData().getItem(value));
		});
		return selectedData;
	};

	//ok click see id the delet column was pressed if so lets remove the row
	grid.onClick.subscribe(function(e, args) {
		var cell = args.cell;
		if(cell === 1) {
			var row = args.row;
			removeArrayElement(row, 'title');
		}
	});


	function comparer(a, b) {
		var x = a[sortcol],
			y = b[sortcol];
		return(x === y ? 0 : (x > y ? 1 : -1));
	}

	//unselect rows
	this.unSelectAll = function() {
		grid.resetActiveCell();
	}

	this.empty = function(){
		dataView.beginUpdate();
 		dataView.getItems().length = 0;
		dataView.endUpdate();
		grid.invalidate();
		grid.render();
	}


	//ok click see id the delet column was pressed if so lets remove the row
	grid.onClick.subscribe(function(e, args) {
		var cell = args.cell;
		var row = args.row;



		if(cell == 2) {
			var item = dataView.getItem(row);
			dataView.deleteItem(item.id); //RowID is the actual ID of the row and not the row number
			grid.invalidate();
			grid.render();
		}
	});
}
