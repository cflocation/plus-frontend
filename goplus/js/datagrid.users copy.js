//start the class file for the datagrid

function DatagridUsers() {
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
			id: "firstname", 
			name: "First Name", 
			field: "firstname",
			width:75, 
    		minWidth:75, 
    		maxWidth:75,
    		sortable: true
	    },{
	    	id: "lastname", 
	    	name: "Last Name", 
	    	field: "lastname",
	    	width:75, 
    		minWidth:75, 
    		maxWidth:75,
    		sortable: true
    	},{
	    	id: "title", 
	    	name: "Title", 
	    	field: "title",
	    	width:125, 
    		minWidth:125, 
    		maxWidth:125,
    		sortable: true
	    },{
	    	id: "email", 
	    	name: "Email", 
	    	field: "email",
	    	sortable: true,
    		minWidth:120
	   	},{
	    	id: "office", 
	    	name: "Office", 
	    	field: "office",
	    	sortable: true,
    		minWidth:120
	    },{
	    	id: "market", 
	    	name: "Market", 
	    	field: "market",
	    	sortable: true,
    		minWidth:130
	    }
	];



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
	grid = new Slick.Grid("#share-user-list", dataView, columns, options);
	//register plugins
	grid.registerPlugin(groupItemMetadataProvider);
	grid.setSelectionModel(new Slick.RowSelectionModel());



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
		dataView.endUpdate();
		grid.resizeCanvas();
	};


	//custom grid scaler render
	this.renderGrid = function(x) {
		grid.resizeCanvas();
	};

	//select rows
	this.selectRows = function(rows){
		var re = [];
		var i = 0;

		$.each(data, function(index, value) {
			if(rows.indexOf(value.id.toString()) != -1){
				re.push(index);
				i = index;
			}
		});
		grid.setSelectedRows(re);
		grid.scrollRowIntoView(i);
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


	//selected rows
	this.getSelectedRowIDs = function() {
		var selectedData = [];
		var selectedIndexes = grid.getSelectedRows();

		jQuery.each(selectedIndexes, function(index, value) {
			selectedData.push(grid.getData().getItem(value).id);
		});
		
		return selectedData;
	}

	//unselect rows
	this.unSelectAll = function() {
		grid.setSelectedRows([]);
		grid.resetActiveCell();

	}

	//empty grid
	this.emptyGrid = function() {
		dataView.beginUpdate();
		dataView.getItems().length = 0;
		dataView.endUpdate();
	};


	//select first column
	this.reset = function(){
		grid.resetActiveCell();
	}


	/*END*/
}
















