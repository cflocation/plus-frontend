//start the class file for the datagrid

function DatagridZones() {

	//setup all the basic varibles for the datagrid a
	var grid;
	var data = [];
	var dataView;
	var selectedRows 	= [];
	var sortcol 		= "name";
	var sortdir 		= 1;
	var searchString 	= '';
	var self 			= this;
	var options 		= {};	
	var columnFilters 	= {};	

	//set the columns
	var columns = [{
		id: "name",
		name: "Zone",
		field: "name",
		sortable: true,
		formatter: Slick.Formatters.FormatTitle,
		width: 170,
		minWidth: 170
	},
	{
		id: "syscode",
		name: "SysCode",
		field: "syscode",
		sortable: true,
		formatter: Slick.Formatters.FormatTitle
	}];

	//set the options for the columns
	options.enableCellNavigation 	= true;
	options.editable				= false;
	options.forceFitColumns			= true;
	options.enableColumnReorder 	= false;
	options.rowHeight 				= 30;
	options.showHeaderRow 			= true;
	options.headerRowHeight 		= 30;
	options.explicitInitialization 	= true;
	options.multiSelect				= false;

	//set dataview to the grid
	var groupItemMetadataProvider 	= new Slick.Data.GroupItemMetadataProvider();

	dataView 	= new Slick.Data.DataView({groupItemMetadataProvider: groupItemMetadataProvider});
	grid 		= new Slick.Grid("#datagrid-zones", dataView, columns, options);

	//set grid
	dataView.onRowCountChanged.subscribe(function (e, args) {
		grid.updateRowCount();
		grid.render();
	});
	
	dataView.onRowsChanged.subscribe(function (e, args) {
		grid.invalidateRows(args.rows);
		grid.render();
	});
	
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

    grid.onHeaderRowCellRendered.subscribe(function(e, args){
        $(args.node).empty();
        $("<input type='search' class='rtgSvyFilter'>")
           .data("columnId", args.column.id)
           .val(columnFilters[args.column.id])
           .appendTo(args.node);
		   $(args.node).addClass('slick-header-no-border');
    });
    
	
	grid.onClick.subscribe(function(e, args){

	});
    
	grid.onSelectedRowsChanged.subscribe(function(e, args){
		var row = self.getSelectedRows();
		setZoneFromFilter(row);
	});

	$(grid.getHeaderRow()).delegate(":input", "change keyup search", function (e){
		var columnId = $(this).data("columnId");
		if(columnId != null){
			columnFilters[columnId] = $.trim($(this).val());
			dataView.refresh();
		}
	});

	grid.init();

	dataView.syncGridSelection(grid, true);

	//filter by the title typed int he above box
	function myFilter(item, args){

		for (var columnId in columnFilters) {
			if (columnId !== undefined && columnFilters[columnId] !== ""){
				
				var c = grid.getColumns()[grid.getColumnIndex(columnId)];
				 if (String(item[c.field]).toLowerCase().indexOf(columnFilters[columnId].toLowerCase()) === -1){
					return false;
				}
			}
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
	
	};

	//get the selected rows
	this.getSelectedRows = function() {
		var selectedData 	= [];
		var selectedIndexes = grid.getSelectedRows();
		for(var idx=0; idx < selectedIndexes.length;idx++){
			selectedData.push(grid.getDataItem(selectedIndexes[idx]));
		}
		return selectedData;
	};

	this.resetFilter = function() {
		searchString = '';
		updateFilter();
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

	this.filter = function(item){
		for (var columnId in columnFilters) {
			if (columnId !== undefined && columnFilters[columnId] !== "") {
				var c = grid.getColumns()[grid.getColumnIndex(columnId)];
				if (item[c.field] != columnFilters[columnId]){
					return false;
				}
			}
		}
		return true;
	};	


	function comparer(a, b) {
		var x = a[sortcol],
			y = b[sortcol];
		return(x === y ? 0 : (x > y ? 1 : -1));
	}

	//unselect rows
	this.unSelectAll = function() {
		grid.getSelectionModel().setSelectedRanges([]);
	}

	
	this.updateFilter = function(){
		dataView.setFilterArgs({
			searchString: searchString
		});
		dataView.refresh();
	};		
};


function setZoneFromFilter(row){
	var dmaZone = row[0];
	if(dmaZone !== undefined){
		zoneid = row[0].id;
		zone = row[0].name;
		$('#dma-selector').val(dmaZone.dmaId).change();
		//$.when($('#dma-selector').val(dmaZone.dmaId).change())
		//.then($('#zone-selector').val(row[0].id).change());
	}
}
