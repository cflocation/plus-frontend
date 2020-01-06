//start the class file for the datagrid

function DatagridBasic() {
	//setup all the basic varibles for the datagrid	
	var grid;
	var data = [];
	var dataView;
	var selectedRows = [];
	var editmode = false;

	//set the columns
	var columns = setProposalColumns();	

	//set the options for the columns
	var options = {
		enableCellNavigation: true,
		editable: false,
		forceFitColumns: true,
		enableColumnReorder: false,
		rowHeight: 30,
		frozenColumn: 1
	};


	


	//set the dataview to the grid
	var groupItemMetadataProvider 				= new Slick.Data.GroupItemMetadataProvider();
	var dataViewParams 							= {};
	dataViewParams.groupItemMetadataProvider 	= groupItemMetadataProvider;
	dataViewParams.inlineFilters 				= true;
	dataView 									= new Slick.Data.DataView(dataViewParams);

	//create the datagrid	//register plugins
	grid = new Slick.Grid("#container1", dataView, columns, options);
	grid.registerPlugin(groupItemMetadataProvider);
	grid.setSelectionModel(new Slick.RowSelectionModel());
	


	//method to populate the datagrid
	this.populateDataGrid = function(x) {
		dataView.beginUpdate();
		dataView.setItems(x);
		dataView.endUpdate();
		grid.invalidate();
	};

}