//start the class file for the datagrid

function DatagridProposal() {

    //setup all the basic varibles for the datagrid 
    var grid;
    var data 			= [];
    var filteredData 	= [];
    var dataView;
    var selectedRows 	= [];
    var sortcol 		= "sortingStartDate";
    var sortdir 		= 1;
    var datecols		= [];
    var groupby 		= "zone";
    var titleEdit 		= false;
    var filterString 	= false;
    var loading 		= false;
    var obj 			= {};
    var self 			= this;

    //set the columns
    var columns = [{
        id: "callsignFormat",
        name: "Net",
        field: "callsignFormat",
        sortable: true,
        width: 60,
        minWidth: 60,
        maxWidth: 60,
        formatter: Slick.Formatters.NetworkCallsign
    }, {
        id: "titleFormat",
        sortable: true,
        name: "Program Title",
        field: "titleFormat",
        width: 150,
        minWidth: 120,
        formatter: Slick.Formatters.EPITitle
    }, {
        id: "search",
        name: "Search Criteria",
        sortable: true,
        field: "search",
        width: 70,
        minWidth: 70,
        resizable: true,
        cssClass: "searchCriteria",
        headerCssClass: 'searchCriteria'
    }, {
        id: "statusFormat",
        name: "Status",
        sortable: true,
        field: "statusFormat",
        width: 60,
        minWidth: 60,
        maxWidth: 100,
        formatter: Slick.Formatters.StatusIcons,
        cssClass: "statusFormat",
        headerCssClass: 'statusFormat'
    }, {
        id: "day",
        name: "Day",
        field: "day",
        sortable: true,
        width: 50,
        minWidth: 50,
        maxWidth: 50,
        formatter: Slick.Formatters.DayOfWeek
    }, {
        id: "startdate",
        name: "Start Date",
        field: "startdatetime",
        sortable: true,
        width: 80,
        minWidth: 80,
        maxWidth: 80,
        formatter: Slick.Formatters.FormatDate
    }, {
        id: "starttime",
        name: "Start Time",
        field: "startdatetime",
        sortable: true,
        width: 80,
        minWidth: 80,
        maxWidth: 80,
        formatter: Slick.Formatters.FormatTime

    }, {
        id: "endtime",
        name: "End Time",
        field: "enddatetime",
        sortable: false,
        width: 80,
        minWidth: 80,
        maxWidth: 80,
        formatter: Slick.Formatters.FormatEndTime
    }];




    //set the options for the columns
    var options = {
        enableCellNavigation: true,
        editable: true,
        enableAddRow: false,
        forceFitColumns: true,
        enableColumnReorder: false,
        autoEdit: true,
        rowHeight: 30,
        frozenColumn: 1,
        multiColumnSort: false
    };

    //metadata provider
    var groupItemMetadataProvider = new Slick.Data.GroupItemMetadataProvider();

    dataView = new Slick.Data.DataView({
        groupItemMetadataProvider: groupItemMetadataProvider,
        inlineFilters: true
    });
    dataView.getItemMetadata = row_metadata(dataView.getItemMetadata);

    //create the datagrid, register plugins
    grid = new Slick.Grid("#proposal-build-grid", dataView, columns, options);
    grid.registerPlugin(groupItemMetadataProvider);
    grid.setSelectionModel(new Slick.RowSelectionModel());
    grid.setSortColumn("startdate",true);



    grid.onSort.subscribe(function (e, args) {
        sortdir = args.sortAsc ? 1 : -1;
        sortcol = args.sortCol.field;

       if (sortcol === "titleFormat") {
            sortcol = 'title';
        }
        dataView.beginUpdate();
        dataView.sort(comparer, args.sortAsc);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
    });


    //sorting
    function comparer(a, b) {
		  return comparerA(a, b) || comparer_dates(a, b)  || comparer_times(a, b);
    }

    //sorting
    function comparerA(a, b) {
        var x = a[sortcol],
            y = b[sortcol];
        return (x == y ? 0 : (x > y ? 1 : -1));
    }


    //sorting date
    function comparer_dates(a, b) {
        var x = Date.parse(a['startdatetime']),
            y = Date.parse(b['startdatetime']);
        return (x == y ? 0 : (x > y ? 1 : -1));
    }

    
    //sorting date
    function comparer_times(a, b) {
        var x = Date.parse(a['startdatetime']),
            y = Date.parse(b['startdatetime']);
        return (x == y ? 0 : (x > y ? 1 : -1));
    }



    this.doSort = function(col){
        sortcol = col;
        dataView.beginUpdate();
        dataView.sort(comparer, true);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
    }



     // prevent the grid from cancelling drag'n'drop by default
    grid.onDragInit.subscribe(function(e, dd) {
        e.stopImmediatePropagation();
    });



    //row change functions
    grid.onSelectedRowsChanged.subscribe(function(e, args) {
        titleEdit 	= false;
        var isgroup = false;
        var groups 	= dataView.getGroups();
        var rows 	= grid.getSelectedRows();
        var rzoneid = 0;
        var i;

        if(rows.length === 0){
            rzoneid = zoneid;
        }else{
            rzoneid = grid.getData().getItem(rows[0]).zoneid;
        }

        for(i = 0; i < groups.length; i++) {
            groups[i]._dirty = false;
        }
        
        for(i = 0; i < rows.length; i++) {
            var row = grid.getData().getItem(rows[i]);

			if(row.__group === true) {
				isgroup = true;
				row._dirty = true;
				rzoneid = row.rows[0].zoneid;
			}
        }

        //select the zone from the sidebar if it does not match the selected line
        if(parseInt(rzoneid) !== parseInt(zoneid)){
            $('#zone-selector').val(rzoneid);
            zoneSelected();
        }


        if(isgroup) {
            grid.invalidate();
            grid.render();
			//clearRotatorItems();
        }
    });
    



    
    //set the edit mode to true when double clicking. this is in place to override the default auto edits on the weeks column
    grid.onDblClick.subscribe(function(row, col) {
        titleEdit = true;
    });

    //before you destroy the cell edit reset the edit mode to false
    grid.onBeforeCellEditorDestroy.subscribe(function(e, args) {
        titleEdit = false;
    });


    grid.onBeforeEditCell.subscribe(function(e, col) {
        var row = col.item;
        var cellid = columns[col.cell].id;
        var isvalue = 0;

        if (typeof row[cellid] !== "undefined") {
            isvalue = 1;
        }


        if(row.linetype == "Rotator" && cellid == 'titleFormat' && titleEdit == false) {
            return false;
        }

        if(row.linetype == "Rotator") {
	        if(col['item']['weeks'] == 0)
				return false;
				
            return true;
        }

        if(row.linetype == 'Fixed' && cellid == 'spotsweek'){
            return true;
        }

        if(row.linetype == 'Fixed' && cellid == 'rate'){
            return true;
        }

        if(row.linetype == 'Fixed' && row.weekId == cellid){
            return true;
        }

        return false;
    });


    
    //handel all the cell changes at bottom of page
    grid.onCellChange.subscribe(function(e, args) {

        obj = {};
        grid.removeCellCssStyles("matchrate");

        var row = args.item;
        var cellid = columns[args.cell].id;

        var dynamic = findDynamicColumn(cellid);

        needSaving = true;

        if(row.linetype == 'Fixed' && dynamic == true){
            var val = proposalUpdateSpotsInWeek(row,'reload',cellid);
            //datagridTotals.populateDataGrid(data);
            return;
        }

        if(row.linetype == 'Fixed' && cellid == 'spotsweek'){
           	var r = runInt(row['spotsweek']);
            row['spotsweek'] = r;
            var val = proposalUpdateSpotsInSpotsPerWeek(row,'reload');
            //datagridTotals.populateDataGrid(data);
            return;
        }

        
        if(row.linetype == 'Fixed' && cellid == 'rate'){
            var r = run(row['rate'])
            row['rate'] = r;
            var val = proposalUpdateRate(row,'reload');
            //datagridTotals.populateDataGrid(data);
            return;
        }


        if(row.linetype == 'Rotator' && dynamic == true){
            var val = proposalUpdateSpotsInWeek(row,'reload',cellid);
            //datagridTotals.populateDataGrid(data);
            return;
        }

        if(row.linetype == 'Rotator' && cellid == 'spotsweek'){
           	var r = runInt(row['spotsweek']);
            row['spotsweek'] = r;
            var val = proposalUpdateSpotsInSpotsPerWeek(row,'rotator');
            var val = calculateEffectiveTotals(row, row.startdate, row.enddate)
            //datagridTotals.populateDataGrid(data);
            return;
        }

        if(row.linetype == 'Rotator' && cellid == 'rate'){
            var r = run(row['rate'])
            row['rate'] = r;
            var val = proposalUpdateRate(row,'reload');
            //datagridTotals.populateDataGrid(data);
            return;
        }


        if(row.linetype == 'Line' && cellid == 'rate'){
            var r = run(row['rate'])
            row['rate'] = r;
            var val = proposalUpdateRate(row,'reload');
            //datagridTotals.populateDataGrid(data);
            return;
        }


        if(row.linetype == 'Line' && dynamic == true){
            var val = proposalUpdateSpotsInWeek(row,'reload',cellid);
            //datagridTotals.populateDataGrid(data);
            return;
        }


        if(row.linetype == 'Rotator' && cellid == 'weeks'){
           	var r = runInt(row['spotsweek']);
            row['spotsweek'] = r;
            var val = proposalUpdateWeeksFromCell(row,'reload');
            //datagridTotals.populateDataGrid(data);
            return;
        }


        if(row.linetype == 'Rotator' && cellid == 'titleFormat'){
            var val = proposalUpdateTitle(row,'reload');
            //datagridTotals.populateDataGrid(data);
            return;
        }

        
    });



	function run(val) {    
	    var regex = /\d*\.?\d\d?/g;
		return String(regex.exec(val));
	}

	function runInt(val){
	    var regex = /\d\d?/g;		
		return String(regex.exec(val));
	}


    function proposalUpdateTitle(row,type){
        var titles 	= row['titleFormat'].split("|");
        row.title 	= titles[0];

        $.ajax({
            type:'post',
            url: apiUrl+"proposal/line/edittitle",
            dataType:"json",
            headers:{"Api-Key":apiKey,"User":userid},
            processData: false,
            contentType: 'application/json',
            data: JSON.stringify({"lineIds":[row.id], "title":titles[0]}),
            success:function(resp){
                //console.log(resp);
            }
        });

        if(type == 'reload'){
            grid.invalidate();
            grid.render();
        }
    }


    // wire up model events to drive the grid
    dataView.onRowCountChanged.subscribe(function (e, args) {
        grid.updateRowCount();
        grid.render();
    });


    dataView.onRowsChanged.subscribe(function (e, args) {
        grid.invalidateRows(args.rows);
        grid.render();
    });



    //filter the lines based on rotators and fixed
    this.lineFilter = function(item, args) {
        if(args.filterString != false && item.linetype == "Fixed") {
            return false;
        }

        if(item.linetype == "Fixed" && item.lineactive == 0){
            return false;
        }
        return true;
    }



    this.groupByColumn = function(col) {
        dataView.groupBy(col,
            function (g) {
                return "" + g.value + "  <span style='color:green'>(" + g.count + " items)</span>";
            },
            function (a, b) {
                return a.value - b.value;
            }
        );
    }



    this.collapseAllGroups = function() {
        dataView.beginUpdate();
        for(var i = 0; i < dataView.getGroups().length; i++) {
            dataView.collapseGroup(dataView.getGroups()[i].value);
        }
        dataView.sort(comparer, true);
        dataView.endUpdate();

    };
    
	this.expandAllGroups = function() {
		dataView.beginUpdate();
		for (var i = 0; i < dataView.getGroups().length; i++) {
			dataView.expandGroup(dataView.getGroups()[i].value);
		}
		dataView.sort(comparer, true);
		dataView.endUpdate();
	};
	
	this.expandCollapseAllGroups = function(){
		var expanded = $('.slick-group-toggle.expanded').is(":visible");
		var collapsed= $('.slick-group-toggle.collapsed').is(":visible");
	
		dataView.beginUpdate();
	
		if(collapsed){
			$('.fa.fa-compress').css({'display':'inline'});
			$('.fa.fa-expand').hide();
			for (var i = 0; i < dataView.getGroups().length; i++) {
				dataView.expandGroup(dataView.getGroups()[i].value);
			}            
		}
	
		if(expanded){
			$('.fa.fa-expand').css({'display':'inline'});
			$('.fa.fa-compress').hide();        
			for(var i = 0; i < dataView.getGroups().length; i++) {
				dataView.collapseGroup(dataView.getGroups()[i].value);
			}
		}
		
		dataView.sort(comparer, true);
		dataView.endUpdate();
	};

    this.setDataSet = function(x){
        data = x;
    };

    this.getDataSet = function(){
        return data;
    };



    //method to populate the datagrid
    this.populateDataGridNew = function(temp) {
        dataView.beginUpdate();
        dataView.setItems(temp);
        
        dataView.setFilterArgs({
            filterString: ''
        });
        dataView.setFilter(this.lineFilter);
        
        dataView.sort(comparer, true);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
     
    };
    

    //method to populate the datagrid
    this.populateDataGrid = function() {
        dataView.beginUpdate();
        dataView.setItems(data);
        dataView.sort(comparer, true);
        dataView.endUpdate();

        grid.setSortColumn("startdate",true);
        grid.invalidate();
        grid.render();
		//displayColumns();//ivan added on 08-13
    };




    //method to populate the datagrid
    this.populateDataGridRender = function() {
        grid.invalidate();
        grid.render();
    };



    //method to populate the datagrid
    this.populateDataGridFromArray = function(x) {
        
        data = x;
        dataView.beginUpdate();
        dataView.setItems(data);
        dataView.sort(comparer, true);
        dataView.setFilterArgs({
            filterString: ''
        });
        dataView.setFilter(this.lineFilter);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
    };

    this.deleteLineFromProposal = function(lineIds){

	    var deleteIds = [];
	    var linePos = [];
	    var i,j,n;
	    
        for(i = 0; i < data.length; i++){
			for(j = 0; j <lineIds.length; j++){
				if(String(lineIds[j]) === String(data[i].id)){
					deleteIds.push(data[i].id);
					linePos.push(i);
				}
			};			
        };


		linePos.sort(function(a, b){return b-a}); 
        
		if(deleteIds.length > 0){
	        var jData	  = {};
	        jData.snapshotId = proposalid;
	        jData.lineIds = deleteIds;	
			
			$.ajax({
	            type:'post',
	            url: apiUrl+"snapshot/deletelines",
	            dataType:"json",
	            headers:{"Api-Key":apiKey,"User":userid},
	            processData: false,
	            contentType: 'application/json',
	            data: JSON.stringify(jData),
	            success:function(resp){
	            }
	        });
	
			for(n=0; n < linePos.length; n++){
		        data.splice(linePos[n], 1);
	        }        
	    
	        dataView.beginUpdate();
	        dataView.setItems(data);		        
	        dataView.endUpdate();
	        grid.invalidate();
	        grid.render();
	        grid.resetActiveCell();
	        closeAllDialogs();
        }
    };

    this.findParent = function(parents,zone,zoneid){

        for(var i = 0; i < parents.length; i++) {
            if(parents[i].zoneid == zoneid){
                return;
            }
        }
        
        var row = {};
        row.zone = zone;
        row.zoneid = zoneid;

        var re = parents.push(row);

        return re;
    }



    this.scrollRowIntoViewPort = function(id){
        var x = dataView.mapIdsToRows([id]);
        grid.scrollRowIntoView(x, false);
        grid.invalidate();
        grid.render(); 

    }


    this.filterFixedLines = function(type,rows){

        var filter = type;
        var selected = [];

        for(var i = 0; i < rows.length; i++) {
            if(rows[i].linetype == "Rotator" || rows[i].linetype == "Line"){
                selected.push(rows[i].id);
            }
        }

        dataView.setFilterArgs({
            filterString: filter
        });
        dataView.setFilter(this.lineFilter);
        

        var x = dataView.mapIdsToRows(selected);

        dataView.sort(comparer, true);

        grid.setSelectedRows(x);
        grid.invalidate();
        grid.render(); 
    }

	//select items
	this.selectRowsFromData = function(lines){
		var rows = [];
		$.each(lines, function(i, value) {
			$.each(data, function(x, line) {
				if(value.id == line.id){
					rows.push(dataView.getRowById(line.id));
				}
			});
			
		});
		grid.setSelectedRows(rows);
		return true;
	} 

  


    this.buildEmptyGrid = function(){
        buildColumnsForProposal();
        grid.setColumns(columns);
    }


    this.buildGrid = function(){
        buildGridForProposal();
    }	



    //get the proposal start date
    this.getStartDate = function(){
        var proposalStartDate = data.sort(startDate)[0].startdatetime;
        var proposalEndDate = data.sort(endDate)[0].enddatetime;
        var weeks = buildBroadcastWeeks(proposalStartDate,proposalEndDate);
        return weeks;
    }


    //Get the start and end dates for the total proposal
    this.getProposalStartDate = function(){
        var d = data.sort(startDate)[0].startdatetime;
        return d;
    }

    this.getProposalEndDate = function(){
        var d = data.sort(endDate)[0].enddatetime;
        return d;
    }



    //find the week in the proposal
    this.getWeekFromProposal = function(){
        var proposal = data;
        var ttl = 0;

        for (var i = 0; i < weeks.length; i++){ 
        }
    }


    this.rebuildGrid = function(){
        this.buildSimpleGrid(data);
    }


    //build the datagrid
    this.buildSimpleGrid = function(){
        var proposalStartDate 	= data.sort(startDate)[0].startdatetime;
        var proposalEndDate 	= data.sort(endDate)[0].enddatetime;
        var weeks = buildBroadcastWeeks(proposalStartDate,proposalEndDate);
        var c = buildColumnsForProposal();
        grid.setColumns(columns);
    }



    //custom grid scaler render
    this.renderGrid = function() {
        grid.resizeCanvas();
    };

    //grap proposal
    this.proposalLines = function() {
        return data;
    };


    this.getCount = function(){
        return data.length;
    }



    // add row
    this.addRowToProposal = function(row) {

        for(var i = 0; i < data.length; i++) {
            if(data[i].id == row.id){
                return false;
            }
        }

        var mon = getMondayFromDate(row.startdatetime);
        row[mon] = '1';
        row.weekId = mon;

        //look for hidden weeks if there then do not add the record
        var z = Object.find(weeksdata,mon);

        if(z == true){
            ishidden = true;
            return;
        }


        if(userSettings.autoSplitLines == true){
            if(row.linetype == "Fixed"){
                var rows = this.splitProposalLine(row);

                for(var i = 0; i < rows.length; i++) {
                    data.push(rows[i]);
                }
                return;
            }
        }

        data.push(row);
    };




    //empty grid
    this.emptyGrid = function() {
        data = [];
        dataView.beginUpdate();
        dataView.getItems().length = 0;
        dataView.endUpdate();
    };


    this.selectedRows = function() {
        var selectedData = [];
        var selectedIndexes = grid.getSelectedRows();
    
        jQuery.each(selectedIndexes, function (index, value) {
            selectedData.push(grid.getData().getItem(value));
        });

        return selectedData;
    }


    this.selectedRowsByType = function() {
        var selectedIndexes = grid.getSelectedRows();
        var items = {};
        items.Fixed = 0;
        items.Rotator = 0;
        items.Avail = 0;

        jQuery.each(selectedIndexes, function (index, value) {
            var row = grid.getData().getItem(value);
            var linetype = row['linetype'];
            items[linetype] += 1;
            if(row['linetype2'] == 'Avail')
	            items[row['linetype2']] += 1;
        });

        return items;
    }

    this.dataSet = function() {
        return data;
    }






    //remove the unused weeks from the weeksdata flight
    this.removeUnusedWeeks = function(){
        
        var proposalStartDate = data.sort(startDate)[0].startdatetime;
        var proposalEndDate = data.sort(endDate)[0].enddatetime;
        var weeks = buildBroadcastWeeks(proposalStartDate,proposalEndDate);
        var dlength = weeksdata.length;
        var nweeks = [];

        var lookup = [];
        for (var i = 0; i < weeks.length; i++){
            var colname = "w"+weeks[i].column;
            lookup.push(colname); 
        }
    
        for (var i = 0; i < dlength; i++){
            var weekid = weeksdata[i];
            var finds = Object.find(lookup,weekid);
            if(finds == true){
                nweeks.push(weekid);
            }
        }

        weeksdata = nweeks;
        flightUpdate();
        flightLabel();
    }




    //zone list
    this.getZoneList = function(){
        var re = {};

        $.each(data, function(i, value) {
            var row = {};
            row.zoneid = value.zoneid;
            row.zone = value.zone;
            re[value.zoneid] = row;
        });

        return re;
    }



	this.deleteConfirmed = function(){    
        var id        = grid.getSelectedRows();
        var jData	  = {};
        var row;
        jData.snapshotId = proposalid;
        jData.lineIds = [];
                
        id.sort(function(a,b){return b-a});
        for(var i = 0; i < id.length; i++) {
            
            row = grid.getData().getItem(id[i]);

            if(row.__group == true){
                $.each(row.rows, function(i, rid){
                    $.each(data, function(i, value){
                        if(value.id == rid.id){
                            jData.lineIds.push(rid.id);
                            data.splice(i, 1);
                            return false;
                        }
                    })
                });
            } else {
                $.each(data, function(i, value){
                    if(value.id == row.id){
                        jData.lineIds.push(row.id);
                        data.splice(i, 1);
                        return false;
                    }
                })
            }
        }

        $.ajax({
            type:'post',
            url: apiUrl+"snapshot/deletelines",
            dataType:"json",
            headers:{"Api-Key":apiKey,"User":userid},
            processData: false,
            contentType: 'application/json',
            data: JSON.stringify(jData),
            success:function(resp){
            }
        });
    
        dataView.beginUpdate();
        dataView.setItems(data);        
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
        grid.resetActiveCell();
        closeAllDialogs();

    }



    function findtitlesByNetwork(row){
        var params = solrSearchParamaters();
        

        var network = {};
        network.id = row.stationnum;
        network.name = row.stationname;
        network.callsign = row.callsign;

        params.networks = [network];
        var url = solrSearchGroup(params,'full');
        var titles = 'Various';


        $.getJSON(url, function(titledata) {
            var titledata = titledata.grouped.sort.groups;

            if(titledata.length < 30){
                titles = gettoptitles(titledata);
            }

            row.title = titles;
            row.titleFormat = titles + '|';
            row.titlenetworkFormat = row.callsign + " - " + titles;
            row.zonetitle = row.zone + " - " + titles;
            row.networktitle = row.callsign + " - " + titles;
            row.zonenetworktitle = row.zone + " - " + row.callsign + " - " + titles;
        
            return row;
        });
    }



    $("#proposal-build-grid").keyup(function(e){
        if(e.keyCode == 46){
            dialogDeleteLines();
            return;
        }
    })
    
    

    //metadata items for coloring
    function row_metadata(old_metadata_provider) {

        return function(row) {

            var item = this.getItem(row),
            ret = old_metadata_provider(row);

            if(item.__group != true && item.ratevalue == item.rate){
                    obj[row] = {rate:"ratecard"};
                    grid.setCellCssStyles("matchrate",obj);
            }else{
                    obj[row] = {rate:""};
                    grid.setCellCssStyles("matchrate",obj);
            }


            if(item.__group != true && item.hot == true){
                    obj[row] = {ratevalue:"hotrate"};
            }


            if(item && item.__group === true) {
                ret = ret || {};
                ret.cssClasses = (ret.cssClasses || '') + ' maingroup';
            }


            if(item && item.linetype === 'Fixed') {
                ret = ret || {};
                ret.cssClasses = (ret.cssClasses || '') + ' fixed';
            }


/*
            if(item && item.spotsweek === "0") {
                ret = ret || {};
                ret.cssClasses = (ret.cssClasses || '') + ' error-bg';
            }
*/
            if(item && item._dirty === true && item.__group === true) {
                ret = ret || {};
                ret.cssClasses = (ret.cssClasses || '') + ' dirty';
            }

            return ret;
        };
    }



//keeping all the MATH lines updates and such below

    function findDynamicColumn(id){
        for(var i = 0; i < columns.length; i++) {
            if(columns[i].id == id && columns[i].dynamic == 1){
                return true;
            }
        }
        return false;
    }




    function proposalBuildStartEndTimes(starttime,endtime,startdate,enddate){
      var newstart = Date.parse('01/01/2000 ' + starttime).toString("hh:mm tt");
      var newend = Date.parse('01/01/2000 ' + endtime).toString("hh:mm tt");

      var newstart24 = Date.parse('01/01/2000 ' + starttime).toString("HH:mm");
      var newend24 = Date.parse('01/01/2000 ' + endtime).toString("HH:mm");

      var startdateonly = Date.parse(startdate).toString("yyyy/MM/dd");
      var enddateonly = Date.parse(enddate).toString("yyyy/MM/dd");

      var newstartformat = startdateonly + " " + newstart24;
      var newendformat = enddateonly + " " + newend24;

      var re = {};
      re.starttime = newstart;
      re.endtime = newend;
      re.startdatetime = newstartformat;
      re.enddatetime = newendformat;

      return re;
    }




    //reset teh datagrid
    this.resetGrid = function(){
        grid.invalidate();
        grid.render();
        grid.resetActiveCell();
    }



    //if there is a row with 0 spots return
    this.spotCount = function(){
        var cnt = 0;
        
        if(data.length == 0)
        	return -1;
        

        for (var i = 0; i < data.length; i++){
            var row  = data[i];
            var spotsweek 	= parseInt(data[i].spotsweek);
            var weeks 		= parseInt(data[i].weeks);
            if((data[i].lineactive ==1 ) && (spotsweek == 0 || weeks < 1 )){
                cnt++;
				return cnt;
            }
        }
        return cnt;
    }


    //is there fixed lines
    this.fixedLinePresent = function(){

        for (var i = 0; i < data.length; i++){
            var linetype = data[i].linetype;
            if(linetype == "Fixed")
            {
                return true;
            }
        }
        return false;
    }




	this.selectedRowsData = function() {
	    var selectedrows = [];
	    var selectedIndexes = grid.getSelectedRows();
	    var cnt = 0;
	    var isgroup = false;
	
	
	    $.each(selectedIndexes, function (index, value) {
	        var row = grid.getData().getItem(value);
	        if(row.__group == true){
	            cnt ++;
	            isgroup = true;
	            selectedrows = row.rows;
	        }
	    });
	
	    if(cnt > 1){
	        return 0;
	    }
	
	    if(isgroup){
	        return selectedrows;
	    }
	
	    $.each(selectedIndexes, function (index, value) {
	        var row = grid.getData().getItem(value);
	        selectedrows.push(row);
	    });
	    
	    return selectedrows;
	} 


	this.freezeByColumn = function(col){
	    grid.setOptions({ 'frozenColumn': col });
	}




	function removePassedColumn(columnid){
		$.each(columns, function(i){
			if(columns[i].id === columnid) {
				columns.splice(i,1);
				grid.setColumns(columns);
				return false;
			}
		});
	}


	this.toggleColumns1 = function(showColumn, columnid, position, params){
		if(showColumn){
			
			columns.splice(position, 0, params);

			grid.setColumns(columns);
		}
		else{
			removePassedColumn(columnid);
		}
	}


	this.toggleColumns = function(proposalCols){
		
		for(i=0;i<columns.length;i++){
			if(columns[i]['dynamic'] === 1){
				proposalCols.push(columns[i]);
			}
		}
		columns.length = 0;
		columns = proposalCols;

		grid.setColumns(columns);

		return false;
	}



    function buildColumnsForProposal(){
    //set the columns
    columns = [
            {
            id: "callsignFormat", 
            name: "Net", 
            field: "callsignFormat", 
            sortable: true,
            width:60, 
            minWidth:60, 
            maxWidth:60,
            dynamic:0,
            formatter: Slick.Formatters.NetworkCallsign
        },   
        {
            id: "titleFormat", 
            sortable: true,
            name: "Program Title", 
            field: "titleFormat",
            width:275, 
            minWidth:275,
            dynamic:0,
            formatter: Slick.Formatters.EPITitle,
            editor: Slick.Editors.LongText
        },
        {
            id: "search", 
            name: "Search Criteria", 
            sortable: true,
            field: "search", 
            width:140,
            minWidth:140,
            maxWidth:140,
            resizable: true
        },
        {
            id: "statusFormat", 
            name: "Status", 
            sortable: true,
            field: "statusFormat", 
            width:60, 
            minWidth:60, 
            maxWidth:100,
            formatter: Slick.Formatters.StatusIcons
        },
        {
            id: "day", 
            name: "Day", 
            field: "dayFormat", 
            sortable: true,
            width:50, 
            minWidth:50,
            dynamic:0
        },

        {
            id: "startdate", 
            name: "Start Date", 
            field: "startdatetime", 
            sortable: true,
            width:100, 
            minWidth:100, 
            maxWidth:100,
            dynamic:0,
            formatter: Slick.Formatters.FormatDate
            
        },
        {
            id: "enddate", 
            name: "End Date", 
            field: "enddatetime", 
            sortable: false,
            width:100, 
            minWidth:100, 
            maxWidth:100,
            dynamic:0,
            formatter: Slick.Formatters.FormatDate
        },
        {
            id: "starttime", 
            name: "Start Time", 
            field: "startdatetime", 
            sortable: true,
            width:100, 
            minWidth:100,
            maxWidth:100,
            dynamic:0,
            formatter: Slick.Formatters.FormatTime
        },
        {
            id: "endtime", 
            name: "End Time", 
            field: "enddatetime", 
            sortable: false,
            width:100, 
            minWidth:100, 
            maxWidth:100,
            dynamic:0,
            formatter: Slick.Formatters.FormatEndTime
        }
        ];
    }

    /* END */
}