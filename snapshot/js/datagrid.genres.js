//start the class file for the datagrid
function DatagridGenres() {
	//setup all the basic varibles for the datagrid	
	var grid;
	var data = [];
	var dataView;
	var selectedRows = [];
	var selectedGenres = {};
	var sortcol = "genre";
	var selectedGenreRows = {};

	//set the columns
	var columns = [{
		id: "genre",
		name: "Genre",
		field: "genre",
		width: 205,
		minWidth: 205,
		maxWidth: 205,
		sortable: true
	}];


	//set the options for the columns
	var options = {
		enableCellNavigation: true,
		editable: true,
		forceFitColumns: true,
		enableColumnReorder: false,
		rowHeight: 30
	};


	//set the dataview to the grid
	dataView = new Slick.Data.DataView({
		inlineFilters: true
	});
	//create the datagrid
	grid = new Slick.Grid("#datagrid-genre", dataView, columns, options);

	//register plugins
	grid.setSelectionModel(new Slick.RowSelectionModel());


	grid.onSort.subscribe(function(e, args) {
			sortdir = args.sortAsc ? 1 : -1;
			sortcol = args.sortCol.field;
			dataView.sort(comparer, args.sortAsc);
         grid.invalidate();
         grid.render();
	});


	grid.onSelectedRowsChanged.subscribe(function() {
		updateSelectedGenres();
	});



	this.setGenreRows = function(selected){
		var selectedlen = Object.keys(selected).length;
		if(selectedlen > 0){
			selectedGenreRows = selected;
		}
	}


	this.getGenreRows = function(){
		return selectedGenreRows;
	}



	//method to populate the datagrid
	this.populateDataGrid = function(xdata) {
		this.emptyGrid();

		var cnt = 1;
		data[0] = {
			id: '0',
			genre: 'All Genres'
		};

		//loop over the json return so I have all the needed values
		for (var key in xdata) {
			if(key > ""){
			data[cnt] = {
				id: cnt,
				genre: key
			};
			cnt++;
			}
			
		}

		//do the dataview stuff
		dataView.beginUpdate();
		dataView.setItems(data);
		dataView.endUpdate();
		dataView.sort(comparer, true);
		grid.invalidate();
		grid.render();

		var sel = [];
		var selectedlen = Object.keys(selectedGenreRows).length;
		var selectedTemp = {};


		if(selectedlen == 0){
			grid.setSelectedRows([0]);
		}else{



			//loop over the selected genres
			for (var key in selectedGenreRows) {
				var item = key;
				
				var avail = this.findInObject(item);

				if(avail != -1){
					selectedTemp[item] = item;
					sel.push(avail);
				}
				
			}

			selectedGenreRows = selectedTemp;
			grid.setSelectedRows(sel);
			grid.scrollRowToTop(0);
			//end genre loop
		}
	};




	this.getSelectedItems = function(){

		var rows 			= grid.getSelectedRows();
		var all 				= false;
		var selectedTemp 	= {};

		if(jQuery.inArray(0,rows) != -1 && rows.length > 1){
			selectedGenres = {};
			grid.setSelectedRows([0]);
			return selectedGenres;
		}

		$.each(rows, function(i, value) {
			selectedTemp[data[value].genre] = data[value].genre;
		});

		selectedGenres = selectedTemp;

		return selectedGenres;
	}





	this.findInObject = function(obj){
		for (var x = 0; x < data.length; x++){
			var genre = data[x].genre;
			if(obj == genre){
				return x;
			}
		}
		return -1;
	}







	//select items
	this.selectRowsFromData = function(selected){

		var rows = [];


		for (var key in selected) {
   			var obj = selected[key];

   			$.each(data, function(x, value) {
				if(value.genre == obj){
					rows.push(x);
				}
			});
		}

		if(rows.length == 0){
			grid.setSelectedRows([0]);
			return;
		}

		grid.setSelectedRows(rows);
	}








	//empty grid
	this.emptyGrid = function() {
		dataView.beginUpdate();
		dataView.getItems().length = 0;
		dataView.endUpdate();
	};

	//sort
	function comparer(a, b) {
		var x = a[sortcol],
			y = b[sortcol];
		return(x === y ? 0 : (x > y ? 1 : -1));
	}

	
	//select first column
	this.reset = function(){
		selectedGenreRows = {};
		data[0] = {
			id: '0',
			genre: 'All Genres'
		};

		grid.setSelectedRows([]);
	}
}