//start the class file for the datagrid

function DatagridTitles(zid, titlename, removable) {

	//setup all the basic varibles for the datagrid a
	var grid;
	var data = [];
	var dataView;
	var selectedRows = [];
	var sortcol = "title";
	var sortdir = 1;
	var searchString = '';
	var ispressed = false;
	var titleGridId = String(zid).substr(1, zid.length);
	var self = this;

	//set the columns
	var columns = [{
		id: "title",
		name: titlename,
		field: "title",
		sortable: true,
		formatter: Slick.Formatters.FormatTitle
	}];

	if(removable) {
		columns.push({
			id: "del",
			name: '',
			field: "del",
			sortable: true,
			width: 40,
			minWidth: 40,
			maxWidth: 40,
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


	//set the dataview to the grid
	var groupItemMetadataProvider = new Slick.Data.GroupItemMetadataProvider();

	//setup the 
	dataView = new Slick.Data.DataView({
		groupItemMetadataProvider: groupItemMetadataProvider
	});


	//create the datagrid
	grid = new Slick.Grid(zid, dataView, columns, options);


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
		if(removable){
			return;
		}

		unbindProposalDatagrid();
		// prevent the grid from cancelling drag'n'drop by default
		e.stopImmediatePropagation();
	});


	grid.onDragStart.subscribe(function(e, dd) {
		var selectedRows = grid.getSelectedRows();

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
		dd.mode = "title-actor";

		dd.rows = selectedRows;
		dd.count = selectedRows.length;

		var proxy = $("<span class='rounded-corners'></span>").css({
			position: "absolute",
			display: "inline-block",
			padding: "4px 10px",
			background: "#D2E8F8",
			border: "1px solid #9EC4E6",
			"z-index": 99999,
			"-moz-border-radius": "8px",
			"-moz-box-shadow": "2px 2px 6px silver"
		}).text("Drag the selected lines into your list").appendTo("body");

		dd.helper = proxy;
		return proxy;
	});


	// .text("Drag to porposal " + dd.count + " selected row(s)")
	grid.onDrag.subscribe(function(e, dd) {
		if(dd.mode !== "title-actor") {
			return;
		}
		e.stopImmediatePropagation();
		dd.helper.css({
			top: e.pageY + 5,
			left: e.pageX + 5
		});
	});


	grid.onDragEnd.subscribe(function(e, dd) {
		if(dd.mode !== "title-actor") {
			return;
		}
		e.stopImmediatePropagation();
		dd.helper.remove();
	});


	$.drop({
		mode: "mouse"
	});

	//filter by the title typed int he above box
	function myFilter(item, args) {
		if(args.searchString !== "" && item.title.toLowerCase().indexOf(args.searchString.toLowerCase()) === -1) {
			return false;
		}
		return true;
	}


	//method to populate the datagrid
	this.populateDataGrid = function(rows) {
		this.emptyGrid();
		data = rows;

		dataView.beginUpdate();
		dataView.setItems(rows);
		dataView.setFilterArgs({searchString: searchString});
		dataView.setFilter(myFilter);
		dataView.endUpdate();
		dataView.sort(comparer, true);
		grid.invalidate();
		grid.render();
		
		if(titleGridId === 'titles-available'){
			self.updateTitleCount();
		}
		
		if(titleGridId === 'actors-available'){
			$('#actorsCount').html('( ' + data.length + ' )');
		}
	};

	this.updateTitleCount = function(){
		$('#titlesCount').html('( ' + grid.getDataLength()+ ' )');
		return false;
	};

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

		this.unSelectAll();
	};

	this.resetFilter = function() {
		searchString = '';
		updateFilter();
	}

	this.resetKeywordSearch = function() {
		datagridKeywords.empty();
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

	//once the term is set 

	function updateFilter() {
		dataView.setFilterArgs({
			searchString: searchString
		});
		dataView.refresh();
		grid.invalidate();
		grid.render();
		self.updateTitleCount();
	}

	//empty grid
	this.emptyGrid = function() {
		dataView.beginUpdate();
		dataView.getItems().length = 0;
		dataView.endUpdate();
		grid.invalidate();
		grid.render();
	};


	//loader
	this.loadLoader = function() {
		var row = {};
		//basic varibles
		row.id = 0;
		row.title = 'Loading Please Wait';
		row.cnt = 0;
		data.push(row);

		//do the dataview stuff
		dataView.beginUpdate();
		dataView.setItems(data);
		dataView.endUpdate();
		grid.invalidate();
		grid.render();
	}


	//loadNoResults
	this.loadNoResults = function() {
		data = [];
		var row = {};
		//basic varibles
		row.id = 0;
		row.title = 'No Records Found';
		row.cnt = 0;
		data.push(row);

		//do the dataview stuff
		dataView.beginUpdate();
		dataView.setItems(data);
		dataView.endUpdate();
		grid.invalidate();
		grid.render();
	}
	

	//get the selected rows
	this.selectedGetSelectedRows = function() {
		var selectedData = [];
		var selectedIndexes = grid.getSelectedRows();

		jQuery.each(selectedIndexes, function(index, value) {
			selectedData.push(grid.getData().getItem(value));
		});
		return selectedData;
	};



	//add selected item to teh selected item list
	this.addSelectedItems = function(rows) {

		for(var i = 0; i < rows.length; i++) {
			if(checkDupe(rows[i].id, data) == 0) {
				data.push(rows[i]);
			}
		}
		dataView.beginUpdate();
		dataView.setItems(data);
		dataView.sort(comparer, true);
		dataView.endUpdate();
		grid.invalidate();
		grid.render();
	}

	//return the selected data to the user
	this.getSelectedData = function() {
		return data;
	}


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
		if(cell == 1) {
			var item = dataView.getItem(row);
			dataView.deleteItem(item.id); //RowID is the actual ID of the row and not the row number
			grid.invalidate();
			grid.render();
		}
	});
}
