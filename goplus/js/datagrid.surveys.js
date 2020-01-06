function DatagridSurvey(zid, removable) {

	//setup all the basic varibles for the datagrid a
	var grid,dataView;
	var data 				= [];
	var selectedRows 		= [];
	var sortcol 			= "sortField";
	var sortdir 			= 0;
	var searchString 		= '';
	var ispressed 			= false;
	var titleGridId 		= String(zid).substr(1, zid.length);
	var columns 			= [];
	var columnFilters 		= {};	
	var options 			= {};	
	var self 				= this;
	self.multiRow 			= {};
	self.multiRow.type 		= '';
	self.multiRow.live 		= '';
	self.multiRow.service 	= '';
		
    var checkboxSelector= new Slick.CheckboxSelectColumn({
		cssClass: "slick-cell-checkboxsel"
    });

	columns.push(checkboxSelector.getColumnDefinition());	

	columns.push({	id: "market",
					name: "Market",
					field: "market",
					sortable: true,
					width: 150});
	
	columns.push({	id: "type",
					name: "Type",
					field: "type",
					sortable: true,
					width: 50,
					minWidth:50,
					maxWidth: 50});
					
	columns.push({	id: "monthName",
					name: "Month",
					field: "monthName",
					sortable: true,
					width: 50,
					minWidth:50,
					maxWidth: 50});
					
	columns.push({  id: "year",
					name: "Year",
					field: "year",
					sortable: true,
					width: 50,
					minWidth:50,
					maxWidth: 50});
					
	columns.push({	id: "live",
					name: "Live+",
					field: "live",
					sortable: true,
					width: 45,
					minWidth:45,
					maxWidth: 45});					

	columns.push({	id: "serviceName",
					name: "Service",
					field: "serviceName",
					sortable: true,
					width: 70,
					minWidth:70,
					maxWidth: 70});
					
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

	dataView 	= new Slick.Data.DataView();
	grid 		= new SlickV2.Grid(zid, dataView, columns, options);

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
	grid.setSelectionModel(new Slick.RowSelectionModel({selectActiveRow: false}));
	grid.registerPlugin(checkboxSelector); 
	
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
        if(!$(args.node).hasClass('r0')){
	        $("<input type='search' class='rtgSvyFilter'>")
	           .data("columnId", args.column.id)
	           .val(columnFilters[args.column.id])
	           .appendTo(args.node);
        }
        else{
	        $("<span class='inlineSpan'>").appendTo(args.node);	        
        }
        $(args.node).addClass('slick-header-no-border');
    });
    
	
	grid.onClick.subscribe(function(e, args){
		var rows = grid.getSelectedRows();
		if( options.multiSelect && rows.length === 0 ||
			!options.multiSelect){
			var row = grid.getDataItem(args.row);
			self.setSelectRow(row.id);
			unselectSavedParameters();			
		}
	});    
    
	grid.onSelectedRowsChanged.subscribe(function(e, args){
		self.setSelectedRows();
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
	};
	
	//
	this.empty = function(){
		dataView.beginUpdate();
 		dataView.getItems().length = 0;
		dataView.endUpdate();
		grid.invalidate();
		grid.render();
	};

	//empty grid
	this.emptyGrid = function() {
		return this.empty();
	};
	
	this.get = function(v){
		return self[v];
	};
	
	//
	this.getTopPanel = function(){
		return grid.getTopPanel();
	};
	
	//return the selected data to the user
	this.getSelectedData = function() {
		return data;
	};
	
	//method to populate the datagrid
	this.populateDataGrid = function(rows) {
		this.emptyGrid();
		data = rows;
		dataView.beginUpdate();
		dataView.setItems(rows);
		dataView.setFilterArgs({
			searchString: searchString,
			multiRow: self.multiRow
		});
		dataView.setFilter(surveyFilterFunc);
		dataView.endUpdate();
		dataView.sort(comparer, true);
		grid.invalidate();
		grid.render();
	};

	//method to populate the datagrid
	this.populateDataGridFromArray = function(xdata) {
		data = xdata;
		dataView.beginUpdate();
		dataView.setItems(xdata);
		dataView.endUpdate();
		dataView.sort(comparer, true);
		grid.getSelectionModel().setSelectedRanges([]);
		grid.invalidate();
		grid.render();

		this.unSelectAll();
	};

	//
	this.resizeCanvas = function(){
		grid.resizeCanvas();
		return false;
	};

	//
	this.resetStringFilter = function(){
		searchString 		= '';
		self.updateFilter();
	};

	this.resetSurveyFilter = function(){
		self.multiRow.type 		= ''
		self.multiRow.live 		= '';
		self.multiRow.service	= '';
		self.updateFilter();
	};
	

	this.set = function(v,val){
		if(v !== 'multiRow'){
			self[v] = val;
		}
		else{
			self.multiRow.type 		= val.type;
			self.multiRow.live 		= val.live;
			self.multiRow.service	= val.service;
		}
	};
	
	//select row
	this.setSelectRow = function(id){
		grid.setSelectedRows([dataView.getRowById(id)]);
	};	
	
	this.setSelectedRows = function(){
		var rows 	= grid.getSelectedRows();

		if(rows.length > 0){
			var row = grid.getDataItem(rows[0]);
			self.multiRow.type		= row.type;
			self.multiRow.live		= row.live;
			self.multiRow.service	= row.service;	
		}
		else{
			self.multiRow.type		= '';
			self.multiRow.live		= '';
			self.multiRow.service	= '';				
		}
		
		if(rows.length >= 2 && myEzRating.getRatings('project') === 0 && myEzRating.getRatings('average') === 0){
			rows = rows.slice(-1);
			grid.setSelectedRows(rows);			
		}
		else if(myEzRating.getRatings('project') === 1 && rows.length > 2){
			$('input.ss-check-ctrl-head:checkbox').hide();
			rows = rows.slice(-1);
			grid.setSelectedRows(rows);
		}
		

		if(options.multiSelect){
			setTimeout(function(){self.updateFilter()}, 20);
		}
		
		rtgSelectSurvey(rows);		
	};	

		
	this.setSelectRowByIds = function(ids){
		var rowIdxs = [];
		for(var i=0; i<ids.length; i++){
			rowIdxs.push(dataView.getRowById(ids[i]));
		}
		grid.setSelectedRows(rowIdxs);
		return false;
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


	//unselect rows
	this.unSelectAll = function() {
		grid.setSelectedRows([]);
		grid.resetActiveCell();
	};
	
	
	this.multiselect = function(state){
		options.multiSelect = state;
		grid.setOptions(options);
		grid.invalidate();
	};
	
	this.updatFromKeyword = function(e, val){
		grid.resetActiveCell();
		Slick.GlobalEditorLock.cancelCurrentEdit();
		// clear on Esc
		if(e.which === 27){
			this.value = "";
		}

		searchString = val;
		self.updateFilter();
	};
	
	//once the term is set 
	this.updateFilter = function(){
		dataView.setFilterArgs({
			searchString: searchString,
			multiRow: self.multiRow
		});
		dataView.refresh();
	};

	
	function comparer(a, b) {
		var x = a[sortcol],
			y = b[sortcol];
		return(x === y ? 0 : (x > y ? -1 : 1));
	};
	
	//filter by the title typed int he above box
	function surveyFilterFunc(item,args) {
		var c;
		var projected	= myEzRating.getRatings('project');
		var average		= myEzRating.getRatings('average');
		
		for (var columnId in columnFilters) {
			if(columnId !== undefined){
				if(columnFilters[columnId] !== ""){
					c = grid.getColumns()[grid.getColumnIndex(columnId)];
				
					if(c.field === 'type'){
						if(String(item[c.field]).toLowerCase().substr(0, 1) !== String(columnFilters[columnId].toLowerCase()).substr(0,1)){
							return false;
						}
					}
					else if (String(item[c.field]).toLowerCase().indexOf(columnFilters[columnId].toLowerCase()) === -1){
						return false;
					}
				}
			}
		}

		if(projected > 0 || average >0){
			if(args.multiRow.live !== ''){
				if(item.live !== args.multiRow.live || 
					item.type !== args.multiRow.type || 
					item.service !== args.multiRow.service){
					return	false;
				}
			}
		}
		return true;
	};
};


function rtgSelectSurvey(rows){
	if(rows.length > 0){
		var surveys 	= datagridSurveys.getSelectedRows();
		var selected 	= [];
		var type 		= {};
		var live 		= {};
		var service 	= {};
		var bookDates 	= [];
		var books		= [];
		var book		= {};
		var dates 		= {};
		var mkt 		= $('#dma-selector option:selected').text();
		var projected	= myEzRating.getRatings('project');
		var average		= myEzRating.getRatings('average');
		var lblMonth	= [];
		var monthMap 	= ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];
		var sBooks		= ''; 
		var c = 0;
		
		$('#rtgSummaryMkt').html(mkt);	
		
		//DISPLAY SELECTION
		
		if(surveys.length === 0){
			//$('#rtgSummarySurvey,#surveyName').html('');
			$('#rtgSummarySurvey').html('');
		}
		else if(surveys.length > 0){
			var d;
			var idx = [];
	
			if(projected === 1 && surveys.length > 2){
				c = surveys.length - 2;
			}
	
			for(var s = c; s<surveys.length; s++){
				if(surveys[s] !== undefined){
					type[surveys[s].type] 		= surveys[s].type;
					live[surveys[s].live] 		= surveys[s].live;
					service[surveys[s].service] = surveys[s].service;
					d 			= surveys[s].month+String(surveys[s].year).substr(2,2);
					dates[d]	= new Date(surveys[s].year, monthMap.indexOf(surveys[s].monthName),1);
					bookDates.push(d);
					idx.push(surveys[s].id);
					
					if(s>0){
						if(surveys[s-1].live !== surveys[s].live || surveys[s-1].type !== surveys[s].type){
							projected	= 0;
						}
					}	
					
					book 			= {};
					book.kind 		= surveys[s].kind;
					book.id 		= surveys[s].id;
					book.month  	= surveys[s].month;
					book.year 		= surveys[s].year;
					book.type 		= surveys[s].type;
					book.marketCode = surveys[s].marketCode;
					book.service	= surveys[s].service;
					book.serviceName= surveys[s].serviceName;
					books.push(book);	
				}
			}
	
			myEzRating.setRatings('books',books);
	
			for(var key in dates){
				lblMonth.push([key, dates[key]]);
			}
	
			lblMonth.sort(function(a, b){
			    return a[1] - b[1];
			});
			
			// NAMING PROJECT 
			if(rows.length === 2 && projected === 1 ){
				var prjName = parseInt(lblMonth[0][1].toString('yy')) + 1;
				sBooks 		= "Proj "+lblMonth[0][1].toString('MMM') + prjName;
				
				// NAMING PROJECT 
				if(rows.length === 2 && projected === 1 ){
					sBooks += "&nbsp; ("+lblMonth[0][1].toString('MMMyy')+" , "+ lblMonth[lblMonth.length-1][1].toString('MMMyy')+")";
				}			
				
			}
			else if(average === 1 && lblMonth.length > 1){
	
				var allBooks= '';
				var wHeight = 160;
							
				for(var sB = 0; sB<lblMonth.length; sB++){
					allBooks += lblMonth[sB][1].toString('MMMyy')+' + ';
				}
	
				allBooks = allBooks.substr(0, allBooks.length-3);
				sBooks 	+= "Avg "+ lblMonth.length+ "Bk ";
				
				if(rows.length > 1 ){
					average 	= true;
					var avgName = " (";				
					avgName += lblMonth[0][1].toString('MMMyy')+' - '+lblMonth[lblMonth.length-1][1].toString('MMMyy');
					sBooks 	+= avgName +")";						
				}
			}
			else{
				sBooks += lblMonth[0][1].toString('MMMyy');
			}
	
			// DMA - CDMA
			if(surveys[0].type === 'CDMA'){
				myEzRating.setRatings('dma',false);
				myEzRating.setRatings('cdma',true);
				sBooks += '&nbsp; CDMA';			
			}
			else{
				myEzRating.setRatings('dma',true);
				myEzRating.setRatings('cdma',false);				
				sBooks += '&nbsp; DMA';	
			}		
	
			//SERVICE
			sBooks += '&nbsp; '+surveys[0].serviceName;
	
			//LIVE
			if(Object.keys(live).length < 2){
				sBooks += '&nbsp; '+live[Object.keys(live)[0]];
			}
			else{
				sBooks += '&nbsp; L+';
			}
	
			if(lblMonth.length > 2){
				if(lblMonth > 4){
					wHeight = wHeight*2;
				}
				sBooks += " <a href=javascript:dialogAvgBooks("+wHeight+") id=avgBooksMsg title='"+allBooks+"' style=text-decoration:none>";
				sBooks += "<span class='hander' style='font-size:12pt; color:#184a74'> +</span></a> ";
			}				
	
			$('#rtgSummarySurvey').html(sBooks);
		}
		
		if(myEzRating.getRatings('saved') === 1 && myEzRating.validateState()){
			$('#surveyName').html($('#rtgSummarySurvey').text());
		}
		
		toggleSubmitRatings();
		datagridSurveys.resizeCanvas();
	}
}
function unselectSavedParameters(){
	$('tr.tr_usrRatings').find('td').removeClass('savedSetSelected');
	$('input.delFavRtgSetting:checkbox').prop('checked',false);
}

function activateSurveysMultiSelect(option){
	datagridSurveys.multiselect(true);
	datagridSurveys.setSelectedRows();
}


function deactivateSurveysMultiSelect(){
	datagridSurveys.unSelectAll();
	datagridSurveys.multiselect(false);	
	datagridSurveys.resetSurveyFilter();
	$('input.ss-check-ctrl-head:checkbox').hide();
}

function isConsecutiveMonths(arr){
	var results	= [];
	var limit   = arr.length - 1;
	var r 		= true;
	var diff;
	
	for (var i = 0; i < limit; ++i){	
		diff = (arr[i+1][1].getTime() - arr[i][1].getTime())/(24*3600*1000);
		if(diff > 31) {
			r = false;
			break;
		}
	}
	return r;
}