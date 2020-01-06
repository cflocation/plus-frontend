function DatagridProposalManager(roles) {
	//setup all the basic varibles for the datagrid	
	var grid;
	var data = [];
	var dataView;
	var selectedRows = [];
	var sortcol = "created";
	var sortdir = 1;
	var loadProposalOnEvent = '';
	var editmode = false;
	var searchString = '';

	//set the columns
	var columns = setColumms(roles);

	

	var checkboxSelector = new Slick.CheckboxSelectColumn({
		cssClass: "slick-cell-checkboxsel"
	});

	columns.unshift(checkboxSelector.getColumnDefinition());


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


	//create the datagrid && register plugins
	grid = new Slick.Grid("#proposal-list", dataView, columns, options);
	grid.registerPlugin(groupItemMetadataProvider);
	grid.setSelectionModel(new Slick.RowSelectionModel({selectActiveRow: false}));
	grid.registerPlugin(checkboxSelector);
	dataView.syncGridSelection(grid, true);
	
	$("#inlineFilterPanel").appendTo(grid.getTopPanel()).show();
	
	grid.onClick.subscribe(function(e, args) {
		if(args.cell !== 0){
			var row = grid.getData().getItem(args.row);
			loadProposalFromServer(row.id);
			datagridProposalManager.setSelectRow(proposalid);	
			//clearProposalFilter();
			$('#download-proposal-list').val(row.id);
		}
	});


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
	};
	
	//delete rows
	this.deleteProposalRows = function(proposalIds){
		for(var i=0; i< proposalIds.length; i++){
			dataView.deleteItem(proposalIds[i]);
		}
		grid.invalidate();	
		grid.render();		
		updateProposalCount(dataView.getLength());
	};	
	
	//method to populate the datagrid
	this.populateDataGrid = function(x) {
		data = x;
		dataView.beginUpdate();
		dataView.setItems(x);
		dataView.sort(comparer, false);
		dataView.endUpdate();
		grid.invalidate();
		grid.render();
		grid.getSelectionModel().setSelectedRanges([]);
	};


	this.proposalFinder = function(){
		dataView.beginUpdate();
		dataView.setItems(data);
		dataView.setFilterArgs({
			searchString: searchString
		});
		dataView.setFilter(this.filter);
		dataView.endUpdate();	
	};


	//custom grid scaler render
	this.renderGrid = function(x) {
		grid.resizeCanvas();
	};



	//selected rows
	this.getSelectedRows = function() {
		var selectedData = [];
		var selectedIndexes = grid.getSelectedRows();

		$.each(selectedIndexes, function(index, value) {
			selectedData.push(grid.getData().getItem(value));	
		});
		
		return selectedData;
	};
	
	//selected rows
	this.getProposalInfo = function(id){
		return  dataView.getItemById(id);
	};

	
	
	//select row
	this.setSelectRow = function(id) {
		var rowIdx = dataView.getRowById(id);		
		if(rowIdx !== undefined){
			grid.setSelectedRows([rowIdx]);
		}
	};


	//unselect rows
	this.unSelectAll = function() {
		grid.resetActiveCell();

	};


	this.updateProposalTrack = function(){	
		dataView.beginUpdate();
		dataView.endUpdate();
		grid.invalidate();
		grid.render();
	};
	
	this.toggleFilterRow = function(){
		if(grid.getOptions().showTopPanel){
			clearProposalFilter();
		}
		grid.setTopPanelVisibility(!grid.getOptions().showTopPanel);
	};
	
	this.filtering = function(){
		dataView.setFilterArgs({
			searchString: searchString
		});
		dataView.refresh();		
	};
	
	this.filter = function(item, args){
		var r = true;
		if (args.searchString !== "" && item["name"].toLowerCase().indexOf(args.searchString) === -1) {
			r = false;
		}
		return r;
	};
	
	this.updateRow = function(id,field,val){
		var pslRow = dataView.getItemById(id);
		if(pslRow !== undefined){
			var i = dataView.getIdxById(id);
			pslRow[field] = val;
			grid.invalidateRow(i);
			grid.render();
		}
	}
	
	this.updateSelectedProposalRow = function(data){
		var totals	= data.totals;
		var rowIdx 	= dataView.getRowById(proposalid);
		var item 	= dataView.getItem(rowIdx);
		var keys	= {};
		keys.spot 	= 'spots';
		keys.gross	= 'netttl';
		keys.line	= 'linesttl';
		
		if(item !== undefined){
			
			for(k in keys){
				if(k in totals){
					item[keys[k]] = totals[k];
				}
			}

			if('dates' in data){
				item.fstart = data.dates.startDate;
				item.fend = data.dates.endDate;
			}
			
			if('zones' in data){				
				var znsName = [];
				for(var z=0; z<data.zones.length; z++){
					znsName.push(data.zones[z].name);
				}
				item.zone = znsName.join(',');
			}

			var todayIs	= getTimeStamp();
			item.updatedat = todayIs[0]+'-'+todayIs[1]+'-'+todayIs[2];
		}

		grid.invalidateRow(rowIdx);
		grid.render();
	};
	
	
	this.updateFilter = function(searchedProposal){
		searchString = searchedProposal;
		dataView.setFilterArgs({
			searchString: searchString
		});
		this.proposalFinder();
		updateProposalCount(dataView.getLength());
	};
	
	this.unselectProposal = function(){
		grid.getSelectionModel().setSelectedRanges([]);
		return false;
	};
	
	
	this.addNewProposal = function(id,name){
		var today 	= getTimeStamp();
		var todayIs	= today[0]+'-'+today[1]+'-'+today[2]+' '+today[3]+':'+today[4]+':00';
		var rtg 	= myEzRating.ratingsOn();
		var d		= {};
		d.id 		= id;
		d.name 		= name;
		d.spots		= 0;
		d.linesttl	= 0;
		d.fstart 	= todayIs;
		d.fend 		= todayIs;
		d.created 	= todayIs;
		d.updatedat = todayIs;
		d.zone		= '';
		d.ezratings	= rtg;
		
		dataView.insertItem(0,d);
		dataView.refresh();	
		
		this.setSelectRow(id);	

		updateProposalCount(dataView.getLength());
		return false;
	};
	
	this.addNewProposalData = function(pslData,name){
		var today 	= getTimeStamp();
		var todayIs	= today[0]+'-'+today[1]+'-'+today[2]+' '+today[3]+':'+today[4]+':00';
		var rtg;
		if('isRatingsEnabled' in pslData){
			if(pslData.isRatingsEnabled === 1){
				rtg = true;
			}
			else{
				rtg = false;
			}
		}
		else{
			rtg = myEzRating.ratingsOn();			
		}
		
		var d			= {};
		d.id 			= pslData.proposalId;
		d.name 			= name;
		d.spots			= pslData.totals.spot;
		d.linesttl		= pslData.totals.line;
		d.netttl		= pslData.totals.gross;
		d.fstart 		= pslData.dates.startDate+' 00:00:00';//fixProposalDates(pslData.dates.startDate);
		d.fend 			= pslData.dates.endDate+' 00:00:00';//fixProposalDates(pslData.dates.endDate);
		d.created 		= todayIs;
		d.updatedat 	= todayIs;
		d.zone			= fixProposalZones(pslData.zones);
		d.zoneMapping 	= pslData.zones;
		d.ezratings		= rtg;
		
		dataView.insertItem(0,d);
		dataView.refresh();	
		updateProposalCount(dataView.getLength());
		return false;
	};
		
	function fixProposalZones(zones){
		var r = '';
		for(var z=0; z<zones.length; z++){
			r += zones[z].name+", "
		}
		//return r.replace(/(^ ,)|( ,$)/g, "");
		return r.substr(0, r.length-2);
	};

	function fixProposalDates(dates){	
        var arr = dates.split(/[^0-9]/);
        return new Date(parseInt(arr[0]),parseInt(arr[1])-1,parseInt(arr[2])).toString('MM/dd/yyyy');
	};	
	
	function setColumms(roles){
		var cols = [];
	   if(roles.ezRatings === true){
		cols.push({	id: "ezratings", 
						name: "Rtg", 
						field: "ezratings", 
						sortable: false, 
						width: 30, 
						minWidth: 20, 
						maxWidth: 30, 
						formatter: Slick.Formatters.Ezratings});}
	
		cols.push({ id: "name",
						sortable: true,
						name: "Name",
						field: "name",
						width: 120}); 
	
		cols.push({ id: "zone",
						name: "Zone",
						sortable: true,
						field: "zone",
						width: 120});
	
		cols.push({	id: "linesttl",
						name: "Lines",
						field: "linesttl",
						sortable: true,
						width: 50,
						maxWidth: 50});
	
		cols.push({ id: "spots",
						name: "Spots",
						field: "spots",
						sortable: true,
						width: 50,
						maxWidth: 50});
	
		cols.push({ id: "netttl",
						name: "Gross",
						field: "netttl",
						sortable: true,
						width: 50,
						maxWidth: 65,
						formatter: Slick.Formatters.Money});

		cols.push({ id: "fstart",
						name: "Starts",
						field: "fstart",
						sortable: true,
						width: 70,
						maxWidth: 72,
						formatter: Slick.Formatters.FormatProposalDates});
	
		cols.push({ id: "fend",
						name: "Ends",
						field: "fend",
						sortable: true,
						width: 70,
						maxWidth: 72,
						formatter: Slick.Formatters.FormatProposalDates});
	
		cols.push({ id: "created",
						name: "Created",
						field: "created",
						sortable: true,
						width: 70,
						maxWidth: 72,
						formatter: Slick.Formatters.FormatProposalDates});

		cols.push({ id: "updatedat",
						name: "Updated",
						field: "updatedat",
						sortable: true,
						width: 70,
						maxWidth: 72,
						formatter: Slick.Formatters.FormatProposalDates});

		/*cols.push({		id: "imageCol", 
						name: "OMS", 
						field: "tracker", 
						sortable: false, 
						width: 40, 
						minWidth: 40, 
						maxWidth: 40, 
						formatter: Slick.Formatters.Tracker
						});*/

	return cols;
	};

	/*END*/
};



$("#txtSearch2").keyup(function(e) {
	Slick.GlobalEditorLock.cancelCurrentEdit();
	// clear on Esc
	if (e.which == 27) {
		this.value = "";
	}
	filteringProposalList(String(this.value).toLowerCase());
	return false;
});

function filteringProposalList(name){
	datagridProposalManager.updateFilter(name);	
	return false;
}

function clearProposalFilter(){
	$('#txtSearch2').val('');	
	datagridProposalManager.updateFilter('');	
};
 
function updateProposalCount(proposalCount){
	$('#proposalCount').text('Proposal Count: '+proposalCount);		
} 
