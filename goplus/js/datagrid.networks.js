//start the class file for the datagrid
function DatagridNetworks() {

	//setup all the basic varibles for the datagrid	
	var grid;
	var data = [];
	var dataView;
	var selectedRows = [];

	//set the columns
	var columns = [{
		id: "logo",
		name: "",
		field: "logo",
		width: 35,
		minWidth: 35,
		maxWidth: 35,
		formatter: Slick.Formatters.NetworkLogoSmall
	}, {
		id: "networkarray",
		name: "Callsign",
		field: "networkarray",
		width: 170,
		minWidth: 170,
		formatter: Slick.Formatters.NetworkCallsign
	}];


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
	grid = new Slick.Grid("#datagrid-networks", dataView, columns, options);

	//register plugins
	grid.setSelectionModel(new Slick.RowSelectionModel());

	
	grid.onSelectedRowsChanged.subscribe(function() {
		updateSelectedNetworks();
	});

	grid.onClick.subscribe(function(e, args) {
		var row = grid.getData().getItem(args.row);
		highlightNetwork(row);
	});	
	
	
	this.getNetworkById = function(id){
		return dataView.getItemById(id);
	};
	

	//method to populate the datagrid
	this.populateDataGrid = function(xdata) {
		//this.emptyGrid();
		data = [];
		data[0] = {
			id: '0',
			logo: '',
			callsign: 'All Networks|All Networks',
			name: 'All Networks|All Networks',
			networkarray: 'All Networks|All Networks'
		};


		//loop over the json return so I have all the needed values
		$.each(xdata, function(i, value) {
			data[i + 1] = {
				id: value.id,
				logo: value.logourl,
				callsign: value.callSign,
				name: value.name,
				networkarray: value.callSign + "|" + value.name
			};
		});

		//do the dataview stuff
		dataView.beginUpdate();
		dataView.setItems(data);
		dataView.endUpdate();
		grid.invalidate();
		grid.render();

		grid.setSelectedRows([0]);
		grid.scrollRowToTop(0);
		
        setTimeout(function(){//THIS IS A HACK TO POPULATE THE NETWORKS FROM A SAVED SEARCH
	        if(loadingNets === true){
				saveSearchLoadParams();	 
				loadingNets = false;

			}			        
        }, 1000);
        		
	};

	//Selected Networks
	this.getSelectedItems = function() {
		var re = [];
		if(data.length == 0){
			return re;
		}
		var rows = grid.getSelectedRows();

		if($.inArray(0,rows) != -1 && rows.length > 1) {
			grid.setSelectedRows([0]);
			return re;
		}		
		
		if(rows.length == 1 && data[rows].id == 0) {
			$.each(data, function(i, value) {
				var row = {};
				row.id = data[i].id;
				row.name = data[i].name;
				row.callsign = data[i].callsign;
				re.push(row);
			});
			resetDemos();
		} else {
			$.each(rows, function(i, value) {
				var row = {};
				row.id = data[value].id;
				row.name = data[value].name;
				row.callsign = data[value].callsign;
				re.push(row);
			});
		}
		return re;
	};

	//Label Creator
	this.getLabel = function() {
		var re = '';

		if(data.length == 0){
			return re;
		}		
		var rows = grid.getSelectedRows();

		if(rows.length == 1 && data[rows].id == 0) {
			re = 'all';
			return re;
		} 

		if(rows.length == 1 && data[rows].id != 0) {
			re = data[rows].callsign;
			return re;
		} 

		if(rows.length > 1) {
			re = 'Selected ('+rows.length+')';
			return re;
		}
	return re;
	};


	//select items
	this.selectRowsFromData = function(nets){
		if(nets[0].id == 0){
			grid.setSelectedRows([0]);
			return;
		}

		var rows = [];

		$.each(nets, function(i, value) {
			$.each(data, function(x, net) {
				if(value.id == net.id){
					rows.push(x);
				}
			});
			
		});

		grid.setSelectedRows(rows);
	}

	//select items same as the avobe but it works with an array instead of an object
	this.selectRowsFromArray = function(nets){
		var rows = [];
			$.each(data, function(x, value) {
			if($.inArray(String(value.id),nets) !== -1){
				rows.push(x);
			}	
		});
		
		if(rows.length == 0){
			rows.push("0");
		}
		grid.setSelectedRows(rows);

	}


	this.getSelectedTrueRow = function() {
		var rows = grid.getSelectedRows();
		return rows;
	}

	//empty grid
	this.emptyGrid = function() {
		dataView.beginUpdate();
		dataView.getItems().length = 0;
		dataView.endUpdate();
		grid.invalidate();
		grid.render();
	};

   this.dataSet = function() {
        return data;
    }


	//select first column
	this.reset = function(){
		grid.resetActiveCell();
		grid.setSelectedRows([0]);
	}
	
	this.resetCells = function(){
		grid.resetActiveCell();
		//grid.setSelectedRows([0]);
	}

}