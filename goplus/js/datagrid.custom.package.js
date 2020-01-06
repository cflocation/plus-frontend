function DatagridCustomPackage() {
    var grid;
    var data = [];
    var dataView;
    var selectedRows = [];
    var sortcol = "name";
    var sortdir = 1;
    var loadProposalOnEvent = '';
    var editmode = false;

    var columns = [{id: "id",
    	name:"",
    	field:"id",
    	width:25,
    	maxWidth:25,
    	formatter: Slick.Formatters.CustomPackageDownload
	}, {
        id: "name",
        sortable: true,
        name: "Package",
        field: "name",
        maxWidth: 200        
    }, {
        id: "createdAt",
        name: "Created",
        sortable: true,
        field: "createdAt",
        width: 70,
        maxWidth: 72,
    	formatter: Slick.Formatters.FormatDate
    }, {
        id: "updatedAt",
        name: "Updated",
        field: "updatedAt",
        sortable: true,
        width: 70,
        maxWidth: 72,
    	formatter: Slick.Formatters.FormatDate
    }, {
        id: "userId",
        name: "Publisher",
        field: "userInfo",
        sortable: true,
        formatter: Slick.Formatters.CustomPackagePublisher,
        maxWidth: 150
    }, {
        id: "callSigns",
        name: "Network",
        field: "callSigns",
        sortable: true,
        maxWidth: 100
    }, {
        id: "comments",
        name: "Comments",
        field: "comments",
        sortable: true,
    }];

    var options = {
		enableCellNavigation: true,
		editable: false,
		forceFitColumns: true,
		enableColumnReorder: false,
		rowHeight: 30
	};

	var groupItemMetadataProvider = new Slick.Data.GroupItemMetadataProvider();

	dataView = new Slick.Data.DataView({
		groupItemMetadataProvider: groupItemMetadataProvider,
		inlineFilters: true
	});

	dataView.getItemMetadata = row_metadata(dataView.getItemMetadata);

	//create the datagrid
	grid = new Slick.Grid("#datagrid-custom-packages", dataView, columns, options);
	//register plugins
	grid.registerPlugin(groupItemMetadataProvider);
	//grid.registerPlugin(new Slick.AutoTooltips());
	grid.setSelectionModel(new Slick.RowSelectionModel());



	//do the sort thing
	grid.onSort.subscribe(function(e, args) {
		sortdir = args.sortAsc ? 1 : -1;
		sortcol = args.sortCol.field;
		dataView.sort(comparer, args.sortAsc);
	});

	grid.onDblClick.subscribe(function(e, args) {
		if(args.cell == 0){
			return true;
		}
		var row = grid.getData().getItem(args.row);
		customPackageNetworkCheck(row.id);
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

		dataView.setFilterArgs({searchString: ''});
  		dataView.setFilter(myFilter);



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

	this.getItemById = function(id){
        return dataView.getItemById(id);
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

	this.updateFilter = function(searchString) {
    	dataView.setFilterArgs({searchString: searchString });
    	dataView.refresh();
	}


	function myFilter(item, args) {
		if (args.searchString != ""
			&& item["name"].toLowerCase().indexOf(args.searchString) == -1 
			&& item["comments"].toLowerCase().indexOf(args.searchString) == -1 
			&& item["callSigns"].join(' ').toLowerCase().indexOf(args.searchString) == -1 ){
    		return false;
  		}
  		return true;
	}

   function row_metadata(oldMetaDataProvider) {
        return function(row) {
            var item = this.getItem(row);
            var ret = oldMetaDataProvider(row);
            
            if (item && item.sharedUsers.length==1 && item.sharedUsers[0]==item.userId) {
                ret = ret || {};
                ret.cssClasses = (ret.cssClasses || '') + ' publisher-only';
            }
            return ret;
        }
    }
}
