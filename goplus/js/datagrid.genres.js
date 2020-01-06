//start the class file for the datagrid
function DatagridGenres(container,header,removable) {
	//setup all the basic varibles for the datagrid	
	var grid,dataView;
	var data 					= [];
	var selectedRows 			= [];
	//var selectedGenres 		= [];
	var sortcol 				= "genre";
	var selectedGenreRows		= [];
	var searchString 			= '';

	//set the columns
	var columns 				= [];
	var col 						= {};
	var self 					= this;
	
	col.id						= "genre";
	col.name 					= header;
	col.field					= "genre";
	col.sortable				= true;
	columns.push(col);

	if(removable) {	
		col 					= {};
		col.id					= "del";
		col.name 				= ' ';
		col.field				= "del";
		col.width				= 40;
		col.minWidth			= 40;
		col.maxWidth			= 40;
		col.sortable			= false;
		col.formatter			= Slick.Formatters.DeleteFromGrid;
		columns.push(col);
	}




	//set the options for the columns
	var options = {};
	options.enableCellNavigation	= true;
	options.editable 				= true;
	options.forceFitColumns 		= true;
	options.enableColumnReorder 	= false;
	options.rowHeight 				= 30;


	//set the dataview to the grid
	dataView = new Slick.Data.DataView({
		inlineFilters: true
	});

	//set the dataview to the grid
	var groupItemMetadataProvider = new Slick.Data.GroupItemMetadataProvider();

	//create the datagrid
	grid = new Slick.Grid(container, dataView, columns, options);


	//register plugins
	grid.registerPlugin(groupItemMetadataProvider);
	grid.setSelectionModel(new Slick.RowSelectionModel());


	grid.onSort.subscribe(function(e, args) {
		sortdir = args.sortAsc ? 1 : -1;
		sortcol = args.sortCol.field;
		dataView.sort(comparer, args.sortAsc);
		grid.invalidate();
		grid.render();
	});


	grid.onSelectedRowsChanged.subscribe(function(){
		updateSelectedGenres();
	});


	// wire up model events to drive the grid
	dataView.onRowCountChanged.subscribe(function (e, args) {
		grid.updateRowCount();
		grid.render();
	});


	grid.onDragInit.subscribe(function(e, dd){
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

		if(Slick.GlobalEditorLock.isActive()){
			return;
		}

		e.stopImmediatePropagation();
		dd.mode = "genre";

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
		}).text("Drag the selected genres into your list").appendTo("body");

		dd.helper = proxy;
		return proxy;
	});


	grid.onDrag.subscribe(function(e, dd) {
		if(dd.mode !== "genre") {
			return;
		}
		e.stopImmediatePropagation();
		dd.helper.css({
			top: e.pageY + 5,
			left: e.pageX + 5
		});
	});


	grid.onDragEnd.subscribe(function(e, dd) {
		if(dd.mode !== "genre") {
			return;
		}
		e.stopImmediatePropagation();
		dd.helper.remove();
	});

	$.drop({
		mode: "mouse"
	});


	//ok click see id the delet column was pressed if so lets remove the row
	grid.onClick.subscribe(function(e, args) {
		var cell = args.cell;
		if(cell === 1) {
			var row = args.row;
			self.deleteGenreRows(dataView.getItem(row).id);
		}
	});



	dataView.onRowsChanged.subscribe(function (e, args) {
		grid.invalidateRows(args.rows);
		grid.render();
	});

	dataView.syncGridSelection(grid, true);



	//delete rows
	this.deleteGenreRows = function(genre){
		for(var dv =0; dv < dataView.getLength(); dv++){
			if(genre === dataView.getItem(dv).id){
				dataView.deleteItem(genre);
				dataView.refresh();			
				break;
			}
		}
		for(var d = 0; d < data.length; d++){
			if(genre === data[d].genre){
				data.splice(d,1);
				grid.setData(data);
				break;
			}
		}
		grid.invalidate();
		grid.render();	
		updateSelectedGenres();
	};	


	//empty grid
	this.emptyGrid = function() {
		dataView.beginUpdate();
		dataView.getItems().length = 0;
		dataView.endUpdate();
	};


	this.filter = function(item, args){
		var r = true;
		if (args.searchString !== "" && item.genre.toLowerCase().indexOf(args.searchString) === -1){
			r = false;
		}
		return r;
	};


	this.findInObject = function(obj){
		var genre;
		r = -1;
		for (var x = 0; x < data.length; x++){
			genre = data[x].genre;
			if(obj === genre){
				r = x;
				break;
			}
		}
		return r;
	};


		
	this.genreFinder = function(){
		dataView.beginUpdate();
		dataView.setItems(data);
		dataView.setFilterArgs({
			searchString: searchString
		});
		dataView.refresh();			
		dataView.setFilter(this.filter);
		dataView.endUpdate();	
		
		var cnt = dataView.getLength();
		if(dataView.getItem(0)){
			if(dataView.getItem(0).id === 0){
				cnt--;
				grid.setSelectedRows([0]);
			}
		}
		updateGenreCount(cnt);
	};


	this.getGenreRows = function(){
		var genres = {};
		var item;
		for(var g = 0; g < selectedGenreRows.length; g++){
			genres[selectedGenreRows[g]] = selectedGenreRows[g];
		}			
		return genres;		
	};

	this.getFilteredGenres = function(){
		var genres = {};
		//console.log(data);
		
		for(var dv =0; dv < data.length; dv++){
			genres[data[dv].id] = data[dv].genre;
		}		
		
		/*for(var dv =0; dv < dataView.getLength(); dv++){
			genres[dataView.getItem(dv).id] = dataView.getItem(dv).genre;
		}*/
		return genres;		
	};


	this.getRowsLength = function(){
		return data.length;
	};
	


	this.getSelectedItems = function(){
		var rows = grid.getSelectedRows();
		var r 	 = selectedGenreRows;
		if(rows.length > 0){
			var all 				= false;
			var selectedGenres		= [];
			var selectedItem;
			for(var r =0; r<rows.length; r++){
				selectedItem = dataView.getItem(rows[r]);
				if(!selectedItem) continue;
				if(selectedItem.id !== 0){
					selectedGenres.push(selectedItem.id);
				}
			}
			r =  selectedGenres;
		}
		return r;
	};
	

	//method to populate the datagrid
	this.populateDataGrid = function(xdata) {
		this.emptyGrid();
		data 			= [];
		var sel 		= [];
		var genres 		= {};
		genres.id 		= 0;
		genres.genre 	= 'All Genres';
		genres.del 		= '1';
		data[0] 			= genres;

		//loop over the json return so I have all the needed values
		for (var key in xdata) {
			if(key > ""){
				genres 			= {};
				genres.id		= key;
				genres.genre	= key;
				data.push(genres);
			}
		}

		//do the dataview stuff
		dataView.beginUpdate();
		dataView.setItems(data);
		dataView.endUpdate();
		dataView.sort(comparer, true);
		grid.invalidate();
		grid.render();


		var cnt = dataView.getLength();
		//AUTO POPULATE GENRES
		if(selectedGenreRows.length > 0){
			var r;
			sel = [];
			for(var dv =0; dv < dataView.getLength(); dv++){
				if(selectedGenreRows.indexOf(dataView.getItem(dv).id) !== -1){
					sel.push(dv);
				}
			}
		}
		
		if(dataView.getItem(0).id === 0 && searchString === '' && selectedGenreRows.length === 0){
			sel  = [0];
			cnt--;
		}

		grid.scrollRowToTop(0);
		grid.setSelectedRows(sel);
		
		updateGenreCount(cnt);	
		return false;	
	};


	//method to populate the datagrid
	this.populateSelected = function(newdata) {
		var r;

		for(var key in newdata){
			if(checkDupe(key, data) === 0) {
				r 			= {};
				r.id 		= newdata[key];
				r.genre 	= newdata[key];
				r.del 	= '1';
				data.push(r);
			}
		}
		//do the dataview stuff
		dataView.beginUpdate();
		dataView.setItems(data);
		dataView.endUpdate();
		dataView.sort(comparer, true);
		grid.getSelectionModel().setSelectedRanges([]);
		grid.invalidate();
		grid.render();
		updateSelectedGenres();
	};

	this.resetFilter = function(){
		searchString 		= '';
		self.unselectRows();
		datagridGenres.updateFilter(searchString);
		updateSelectedGenres();
		grid.setSelectedRows([0]);
	};

	//select first column
	this.reset = function(){
		selectedGenreRows = [];
		data[0] = {
			id: '0',
			genre: 'All Genres'
		};
		grid.setSelectedRows([]);
	};
	

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

		if(rows.length === 0){
			grid.setSelectedRows([0]);
			return;
		}

		grid.setSelectedRows(rows);
	};


	this.setGenreRows = function(selected){
		selectedGenreRows = selected;
	};


	this.updatFromKeyword = function(e, val) {
		grid.resetActiveCell();	
		Slick.GlobalEditorLock.cancelCurrentEdit();
	
		if (e.which == 27){//clear on Esc
			this.value = "";
		}
	
		datagridGenres.updateFilter(String(val).toLowerCase());
	
		return false;
	};		


	this.updateFilter = function(searchedGenre){
		searchString = searchedGenre;		
		self.genreFinder();		
	};


	this.unselectRows = function(){
		grid.getSelectionModel().setSelectedRanges([]);
		return;
	};


	//sort
	function comparer(a, b) {
		var x = a[sortcol],
			y = b[sortcol];
		return(x === y ? 0 : (x > y ? 1 : -1));
	}
	

};


$("#genre-filter").on('keyup change search input',function(e){
	datagridGenres.updatFromKeyword(e, this.value);	
});


function updateGenreCount(cnt){
	$('#genresCount').text('('+cnt+')');
	var title = 'Select Genres (' + cnt + ')';
	setDialogMessage('dialog-genre', title);
};