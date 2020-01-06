//start the class file for the datagrid
function DatagridProposal() {

    //setup all the basic varibles for the datagrid 
    var grid;
    var data = [];
    var filteredData = [];
    var dataView;
    var selectedRows = [];
    var sortcol 	= "sortingStartDate";
    var sortdir 	= 1;
    var datecols 	= [];
    var groupby 	= "zone";
    //var columns 	= [];
    var titleEdit 	= false;
    var filterString= false;
    var loading 	= false;
    var obj 	= {};
    var self 	= this;
	var mapdays = {'1':7,'2':1,'3':2,'4':3,'5':4,'6':5,'7':6,'8':7};
    var columns;
    
    
	this.setGridColumns = function(x) {
		columns = x;
	};

	this.setColumnsAlt = function(){
		var c = self.getGridsColumns();
		grid.setColumns(c);
	}	
	
	this.getGridsColumns = function(){
		return columns;
	}    
    
	self.setGridColumns(getBasicProposalColumns());

    //set the options for the columns
    var options = {
        enableCellNavigation: true,
        editable: true,
        enableAddRow: false,
        forceFitColumns: true,
        enableColumnReorder: false,
        autoEdit: true,
        rowHeight: 30,
        frozenColumn: 2,
        multiColumnSort: false,
		editCommandHandler: queueAndExecuteCommand   
    };

    //metadata provider
    var groupItemMetadataProvider = new Slick.Data.GroupItemMetadataProvider();

    dataView = new Slick.Data.DataView({
        groupItemMetadataProvider: groupItemMetadataProvider,
        inlineFilters: true
    });
    
    dataView.getItemMetadata = row_metadata(dataView.getItemMetadata);

    //create the datagrid && register plugins
    grid = new Slick.Grid("#proposal-build-grid", dataView, self.getGridsColumns(), options);
    grid.registerPlugin(groupItemMetadataProvider);
    grid.setSelectionModel(new Slick.RowSelectionModel());
    grid.setSortColumn("startdate",true);

	var columnpicker = new Slick.Controls.ColumnPicker(self.getGridsColumns(), grid, options);

    grid.onSort.subscribe(function (e, args) {  
        sortdir = args.sortAsc ? 1 : -1;
        sortcol = args.sortCol.field;
        if('isNumeric' in args.sortCol){
			dataView.sort(numericComparer, args.sortAsc);	        
	    }
	    else{
			switch(sortcol){
				case 'titleFormat':
					sortcol = 'title';				
					break;
				case 'callsignFormat':
					sortcol = 'callsign';
					break;
				case 'dayFormat':
					sortcol = 'day';			  
					break;
				}
			
			if(sortcol === 'day'){
				dataView.sort(comparerDays, args.sortAsc); 
			}
			else if(sortcol === 'startdatetime' || sortcol === 'enddatetime'){
				dataView.sort(comparerTimes, args.sortAsc);
			}     
			else{
				dataView.sort(comparer, args.sortAsc);
			}
        }
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
    });


	dataView.syncGridSelection(grid, true);
	
    //sorting
    function comparer(a, b) {
		  return comparerA(a, b) || comparerStartDateTime(a, b);
    }
    //sorting
    function numericComparer(a, b) {
        var x = parseFloat(a[sortcol]), y = parseFloat(b[sortcol]);
        return (x == y ? 0 : (x > y ? 1 : -1));
    }

    //sorting time
    function comparerDays(a, b) {
		return comparerByDay(a, b) ||comparerStartDateTime(a, b);	    
    };


    //sorting main column
    function comparerA(a, b){
        var x = a[sortcol], y = b[sortcol];
        return (x == y ? 0 : (x > y ? 1 : -1));
    };
    

    //sorting time
    function comparerByDay(a, b){
	   
	    var d1,d2;
	    
	    if($.isArray(a.day)){
			d1 = mapdays[a.day[0]];
			if(a.day.indexOf("1") === 0 && a.day.length > 1){
				d1 = mapdays[a.day[1]]
			}
			d1 += '-'+a.day.length;			
	    }
	    else{
		    d1 = mapdays[a.day];
	    }

	    if($.isArray(b.day)){
			d2 = mapdays[b.day[0]];
			if(b.day.indexOf("1") === 0 && b.day.length > 1){
				d2 = mapdays[b.day[1]]+'-'+b.day.length;
			}
			d2 += '-'+b.day.length;						
	    }
	    else{
		    d2 = mapdays[b.day];
	    }
	    
        var x = a.callsign+'-'+d1;
		var	y = b.callsign+'-'+d2;	
        
        return (x == y ? 0 : (x > y ? 1 : -1));
    };


    //sorting date
    function comparerDates(a, b) {
        var startArrA = a[sortcol].split(/[^0-9]/);
        var startArrB = b[sortcol].split(/[^0-9]/);
        var x = new Date(parseInt(startArrA[0]),parseInt(startArrA[1])-1,parseInt(startArrA[2]),parseInt(startArrA[3]),parseInt(startArrA[4])); 
        var y = new Date(parseInt(startArrB[0]),parseInt(startArrB[1])-1,parseInt(startArrB[2]),parseInt(startArrB[3]),parseInt(startArrB[4])); 
       return (x == y ? 0 : (x > y ? 1 : -1));
    };
        
    function comparerStartDate(a, b) {
        var startArrA = a['startdate'].split(/[^0-9]/);
        var startArrB = b['startdate'].split(/[^0-9]/);
        var x = new Date(parseInt(startArrA[0]),parseInt(startArrA[1])-1,parseInt(startArrA[2]),parseInt(startArrA[3]),parseInt(startArrA[4])); 
        var y = new Date(parseInt(startArrB[0]),parseInt(startArrB[1])-1,parseInt(startArrB[2]),parseInt(startArrB[3]),parseInt(startArrB[4])); 
       return (x == y ? 0 : (x > y ? 1 : -1));
    }

    //sorting time
    function comparerStartTime(a, b) {
        var arrA = a['starttime'].split(/[^0-9]/);
        var arrB = b['starttime'].split(/[^0-9]/);
        var x    = new Date(2000,0,1,parseInt(arrA[0]),parseInt(arrA[1]),parseInt(arrA[2]));
        var y    = new Date(2000,0,1,parseInt(arrB[0]),parseInt(arrB[1]),parseInt(arrB[2]));
        return (x == y ? 0 : (x > y ? 1 : -1));
    }

    //sorting time
    function comparerStartDateTime(a, b) {
        var startArrA = a.startdatetime.split(/[^0-9]/);
        var startArrB = b.startdatetime.split(/[^0-9]/);
        var x 	= new Date(parseInt(startArrA[0]),parseInt(startArrA[1])-1,parseInt(startArrA[2]),parseInt(startArrA[3]),parseInt(startArrA[4]),parseInt(startArrA[5]));
        var y 	= new Date(parseInt(startArrB[0]),parseInt(startArrB[1])-1,parseInt(startArrB[2]),parseInt(startArrB[3]),parseInt(startArrB[4]),parseInt(startArrB[5]));
        return (x == y ? 0 : (x > y ? 1 : -1));
    }

    //sorting start time
    function comparerTimes(a, b) {
        var startArrA = a[sortcol].split(/[^0-9]/);
        var startArrB = b[sortcol].split(/[^0-9]/);
        var x   = new Date(2000,0,1,parseInt(startArrA[3]),parseInt(startArrA[4]),parseInt(startArrA[5]));
        var y   = new Date(2000,0,1,parseInt(startArrB[3]),parseInt(startArrB[4]),parseInt(startArrB[5]));
        var x1 	= new Date(parseInt(startArrA[0]),parseInt(startArrA[1])-1,parseInt(startArrA[2]),parseInt(startArrA[3]),parseInt(startArrA[4]));
        var y1 	= new Date(parseInt(startArrB[0]),parseInt(startArrB[1])-1,parseInt(startArrB[2]),parseInt(startArrB[3]),parseInt(startArrB[4]));
        x += '_' + x1.toString('MMddyyyy');
        y += '_' + y1.toString('MMddyyyy');
        return (x == y ? 0 : (x > y ? 1 : -1));
    };


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
		
        if(rows.length == 0){
            rzoneid = zoneid;
        }
        else{
            rzoneid = grid.getData().getItem(rows[0]).zoneid;
        }

        for(var i = 0; i < groups.length; i++) {
            groups[i]._dirty = false;
        }
        
        var isLineByDay = 0;
        var isLineOrder = 0;        

		if(rows.length > 1){
			resetStation();
		}
		
		toggleLineTypeSelector(rows.length);
        
        for(var i = 0; i < rows.length; i++) {
            var row = grid.getData().getItem(rows[i]);
            
            if(row.lineType === 4){
		        isLineByDay++;
            }
			else if(row.lineType === 5){
				isLineOrder++;
			}
				
            if(toggleEditionOfSpots(isLineByDay,rows.length)){
		        return false;
            }
            
            if(toggleEditionLineOrder(isLineOrder,rows.length)){
		        return false;
            }
            
			if(row.__group === true) {
				isgroup = true;
				row._dirty = true;
				rzoneid = row.rows[0].zoneid;
			}
        }

        //select the zone from the sidebar if it does not match the selected line
        if(parseInt(rzoneid) !== parseInt(zoneid)){
			if($("#zone-selector option[value="+rzoneid+"]").length === 0){//check to see if the zone is included in the list
				autoSelectMarketAndZone(rzoneid);
			}
			
            $('#zone-selector').val(rzoneid);
            $.when(zoneSelected()).then(function(){
				if(rows.length === 1 && !('__group' in row)){
					if(row.linetype !== 'Fixed'){
						setParamsinSidePanel(row);
					}
				}
            });
        }
        else{
	        if(rows.length === 1 && !('__group' in row)){
		        if(row.linetype !== 'Fixed'){
			        setParamsinSidePanel(row);
			      }
	        }   	        
        }

        if(isgroup) {
            grid.invalidate();
            grid.render();
			clearRotatorItems();
        }
        
	    if(row && row.linetype == "Fixed"){
            loadShowcard(row);	        
	    }
	    else if(row && row.linetype == "Rotator" && row.linetype2 != 'Avail'){
	        swapSettingsPanel('editrotator',true);
	    }
	    else if(rows.length === 0 && $('#update-line-span').is(':visible')){
			$(".header-rotator-edit").hide();
			$(".header-rotator-create").show();
			setHeaderLabel('createRotator');
	    }
	    else{
	        swapSettingsPanel('search',false); 
	    }
	    
    });
    


	grid.onClick.subscribe(function(e, args) {
		closeInlineEditPopup();
		var r = false;
	  	row = dataView.getItem(args.row);
	  	var col  = grid.getColumns()[grid.getCellFromEvent(e).cell];
		resetLineOrder();
		var weekInfo;
		if( row.lineType === 4 && col.dynamic === 1 && weeksdata.indexOf(col.id) === -1 && isInFlightDate(row,col.id) ){
			weekInfo 		= {};
			weekInfo.week 	= col.id;
			weekInfo.row 	= row;
			dialogEditSpotInWeek(weekInfo);
			r = true;
		}
		return r;
	});


    //set the edit mode to true when double clicking. this is in place to override the default auto edits on the weeks column
    grid.onDblClick.subscribe(function(row, col) {
        titleEdit = true;
    });


    //before you destroy the cell edit reset the edit mode to false
    grid.onBeforeCellEditorDestroy.subscribe(function(e, args) {
        titleEdit = false;
    });


    grid.onBeforeEditCell.subscribe(function(e, col){
        var row 	= col.item;
        var cellid 	= columns[col.cell].id;
        var isvalue = 0;
		var r = true;

        if (typeof row[cellid] !== "undefined") {
            isvalue = 1;
        }
		
	   	if(row.notInSurvey && (/(rating|impressions|displayCpp|CPM)/g.test(cellid))){
			r = false;
		}
		else if(row.linetype === "Rotator"){
			if(row.lineType === 5){//Line Order
				if( findDynamicColumn(cellid).dynamic === true || cellid === 'spotsweek' || isvalue == 0 || (cellid === 'titleFormat' && titleEdit == false)){
					r = false;
				}
			}
			else if(cellid == 'titleFormat' && titleEdit == false){
				r = false;
			}
			else if(parseInt(col.item.weeks) === 0 || parseInt(col.item.spotsweek) === 0){
				r = false;
			}		
		}
		else if(row.linetype === 'Fixed'){
	        if(!(/(rate|spotsweek|rating|impressions|displayCpp|CPM)/g.test(cellid)) && row.weekId !== cellid ){
				r =  false;
            }
        }
        return r;
    });


    
    //handel all the cell changes at bottom of page
    grid.onCellChange.subscribe(function(e, args) {
	    
        //grid.removeCellCssStyles("matchrate");
       
        var row 		= args.item;		
        var cellid 		= columns[args.cell].id;
        var cellDetails = findDynamicColumn(cellid); 
        var dynamic 	= cellDetails.dynamic;
        var cellPosition= cellDetails.position;
        var r,val;
        obj 		= {};
        needSaving 	= true;
		
		if(row.linetype === 'Fixed'){
	        if(dynamic == true){
	            proposalUpdateSpotsInWeek(row,cellid,cellPosition);
	            return;
	        }
	        if(cellid == 'spotsweek'){
	           	r = runInt(row['spotsweek']);
	            row['spotsweek'] = r;
	            val = proposalUpdateSpotsInSpotsPerWeek(row,'reload');
	        }else if(cellid == 'rate'){
	            r = run(row['rate'])
	            row['rate'] = r;
	            val = proposalUpdateRate(row,'reload',args.row);
	        }
	        else if(cellid.substr(0, 6) === 'rating'){
	            r = fixRatingVal(row[cellid]);
	            row[cellid] = r;
	            proposalUpdateRatings(row,cellid,r);
	        }
	        //else if(cellid.substr(0, 6) === 'person'){
	        else if(cellid.substr(0, 11) === 'impressions'){
	            r = fixRatingVal(row[cellid]);
	            row[cellid] = r;
	            proposalUpdateImpressions(row,cellid,r);
	        }
				else if(cellid.substr(0, 10) === 'displayCpp'){
	            r = fixRatingVal(row[cellid]);
	            row[cellid] = r;
	            proposalUpdateCPP(row,cellid,r);
	        }
				else if(cellid.substr(0, 3) === 'CPM'){
	            r = fixRatingVal(row[cellid]);
	            row[cellid] = r;
	            proposalUpdateCPM(row,cellid,r);
	        }	        
		}
        else if(row.linetype === 'Rotator'){
	        if(dynamic == true){
	            val = proposalUpdateSpotsInWeek(row,cellid,cellPosition);
	            return;
	        }
	        else{
				if(cellid.substr(0, 6) === 'rating'){
	            	var r = fixRatingVal(row[cellid]);
					row[cellid] = r;
					proposalUpdateRatings(row,cellid,r);
				}
				//else if(cellid.substr(0, 6) === 'person'){
				else if(cellid.substr(0, 11) === 'impressions'){
	            	var r = fixRatingVal(row[cellid]);
					row[cellid] = r;
					proposalUpdateImpressions(row,cellid,r);
				}
				else if(cellid.substr(0, 10) === 'displayCpp'){
	            	var r = fixRatingVal(row[cellid]);
					row[cellid] = r;
					proposalUpdateCPP(row,cellid,r);
				}
				else if(cellid.substr(0, 3) === 'CPM'){
	            	var r = fixRatingVal(row[cellid]);
					row[cellid] = r;
					proposalUpdateCPM(row,cellid,r);
				}
				else{
			        switch(cellid){
				        case 'spotsweek':
			           		var r = runInt(row['spotsweek']);
				            row['spotsweek'] = r;
							proposalUpdateSpotsInSpotsPerWeek(row,'rotator');				            
					        break;
				        case 'rate':
				            var r = run(row['rate'])
				            row['rate'] = r;
				            val = proposalUpdateRate(row,'reload',args.row);		     
				     	   break;
				        case 'weeks':
				           	row.spotsweek = runInt(row.spotsweek);
				            proposalUpdateWeeksFromCell(row,'reload');
							break;
				        case 'titleFormat':
				            proposalUpdateTitle(row,'reload');
				            datagridTotals.populateDataGrid(data);
							break;
			        }
			    }
	        }
        }
        
    });



	function run(val) {    
	    var regex = /\d*\.?\d\d?/g;
		return String(regex.exec(val));
	};

	function runInt(val){
	    var regex = /\d\d?/g;		
		return String(regex.exec(val));
	};


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
            }
        });

        if(type == 'reload'){
            grid.invalidate();
            grid.render();
        }
    };


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
        if(args.filterString !== false && item.linetype === "Fixed") {
            return false;
        }

        if(item.linetype == "Fixed" && item.lineactive == 0){
            return false;
        }
        return true;
    };


    this.groupByColumn = function(col) {
        dataView.groupBy(col,
            function (g) {
                 var r = '<span style="cursor:pointer;">' + g.value + '  <span style="color:green">(' + g.count + ' items)</span></span>';
                 return r;
            },
            function (a, b) {
                return a.value - b.value;
            });
    };


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
			for(var i = 0; i < dataView.getGroups().length; i++){
				dataView.collapseGroup(dataView.getGroups()[i].value);
			}
		}
		
		dataView.sort(comparer, true);
		dataView.endUpdate();
	};


    //grap proposal with weeks excluded
    this.proposalLinesExcludeWeeks = function(weeks) {
        var row, week;
        for(var i = 0; i < data.length; i++) {
            row = data[i];
            row.lineactive = 1;

            for(var x = 0; x < weeks.length; x++) {
                week = weeks[x];
                if(row.linetype == "Fixed" && week == row.weekId){
                    row.lineactive = 0;   
                }
            }
        }
        return data;
    };


    this.proposalUpdateLinesFromWeeks = function(id,action){
       
        if(action === 'add'){

            //loop over the data
            for(var i = 0; i < data.length; i++) {
                
                //if it is fixed and action is add
                if(data[i].linetype === "Fixed" && data[i].weekId == id){
                    data[i].lineactive 	= 1;
                    data[i].active 		= 1;
                    data[i].weeks 		= 1; 
                    data[i][id] 		= data[i][id+'hide'];
                    data[i].spotsweek 	= data[i][id+'hide'];
                    data[i].spots 		= data[i][id+'hide'];
                    delete data[i][id+'hide'];
                }

                //if it is fixed and action is add
                if(data[i].linetype == "Rotator"){

                    if (typeof data[i][id] != 'undefined') {
	                    
                        var saved = id+'hide';
                        var val = data[i][saved];
						delete data[i][saved];                        
                        data[i][id] = val;

						if(data[i].lineType !== 5){	
	                        var stats = proposalGetTotalSpotsFromWeeks(data[i]);
	                        data[i].total = stats.total;
	                        data[i].spotsweek = stats.spotsweek;
	                        data[i].spots = stats.spots;
	                        data[i].weeks = stats.weeks;
	                    }
	                    else{
	                        proposalUpdateDatesInLineLine(data[i].startdate,data[i].enddate,data[i],data[i].spots);		                    
	                    }

                    }
                }
            }
        }
        else{
            //loop over the data
            for(var i = 0; i < data.length; i++) {
                //if it is fixed and action is delete
                if(data[i].linetype === "Fixed" && data[i].weekId === id){
                    data[i].lineactive 	= 0;
                    data[i][id+'hide'] 	= data[i][id];
					data[i][id]			= 0;
                }

                //if it is fixed and action is add
                if(data[i].linetype === "Rotator"){
                    if (id in data[i]){
						var saved 		= id+'hide';
						data[i][saved] 	= data[i][id];
						data[i][id] 	= 0;
						if(data[i].lineType !== 5){
							var stats 		= proposalGetTotalSpotsFromWeeks(data[i]);
							data[i].total 	= stats.total;
							data[i].spotsweek = stats.spotsweek;
							data[i].spots 	= stats.spots;
							data[i].weeks 	= stats.weeks;
						}
						else{
	                        proposalUpdateDatesInLineLine(data[i].startdate,data[i].enddate,data[i],data[i].spots);
						}
                    }
                }
            }
        }
    };


    this.setDataSet = function(x){
        data = x;
    }


    this.getDataSet = function(){
        return data;
    }


    this.filteredDataSetGoPlus = function(gridData,proposalStartDate,proposalEndDate){
        var temp = [];
        var weeks = buildBroadcastWeeks(proposalStartDate,proposalEndDate);
        var weekcnt, sdate, sdate, dateArr, thisDate, thisWeek;
        	        
        for(var i = 0; i < gridData.length; i++) {

            if(parseInt(gridData[i].lineactive) !== 0){	        
	            
		        weekcnt = weeksCount(gridData[i]['startdate'], data[i]['enddate']);
		        sdate 	= getBroadcastWeekGoPlus(gridData[i]['startdate']);
		        edate 	= getBroadcastWeekGoPlus(gridData[i]['enddate']);
		        
				for(j=0;j<weeks.length;j++){
	                dateArr  = weeks[j]['dateFull'].split(/[^0-9]/)
	                thisDate = new Date(parseInt(dateArr[0]), parseInt(dateArr[1])-1,parseInt(dateArr[2]));
					thisWeek = 'w'+weeks[j]['column'];
	
					if(($.inArray(thisWeek,weeksdata) != -1 || gridData[i][thisWeek] == 0 ) && (thisDate >= sdate && thisDate <= edate)){
						weekcnt--;
					}
				}
				
		        gridData[i]['weeks'] = weekcnt;
                temp.push(gridData[i]);
            }
        }
        return temp;
    }


    this.filterInactive = function(item, args){
	    
	    if(parseInt(item.lineactive) !== 0){
		    return true;
	    }
        return false;
    }

    this.filteredDataSet = function(){
        var temp = [];

        var proposalStartDate = data.sort(startDate)[0].startdatetime;
        var proposalEndDate = data.sort(endDate)[0].enddatetime;
        var weeks = buildBroadcastWeeks(proposalStartDate,proposalEndDate);
        
        	        
        for(var i = 0; i < data.length; i++) {
	        
	        var weekcnt = weeksCount(data[i]['startdate'], data[i]['enddate']);
			var sd 		= String(getBroadcastWeek(data[i]['startdate'])).replace('w', '');
			var ed 		= String(getBroadcastWeek(data[i]['enddate'])).replace('w', '');
            var sdate 	= new Date(parseInt(sd.substr(4, 4)), parseInt(sd.substr(0, 2))-1, parseInt(sd.substr(2, 2)));
	        var edate 	= new Date(parseInt(ed.substr(4, 4)), parseInt(ed.substr(0, 2))-1, parseInt(ed.substr(2, 2)));
	        
			for(j=0;j<weeks.length;j++){
                var dateArr  = weeks[j]['dateFull'].split(/[^0-9]/)
                var thisDate = new Date(parseInt(dateArr[0]), parseInt(dateArr[1])-1,parseInt(dateArr[2]));
				var thisWeek = 'w'+weeks[j]['column'];

				if( ($.inArray(thisWeek,weeksdata) != -1 || data[i][thisWeek] == 0 ) && (thisDate >= sdate && thisDate <= edate) ){
					weekcnt--;
				}	
			}
	        data[i]['weeks'] = weekcnt;
	        
	        
            if(data[i].lineactive != 0){
                temp.push(data[i]);
            }
        }

        return temp;
    }

    //method to populate the datagrid
    this.populateDataGridNew = function(temp) {
        dataView.beginUpdate();
        dataView.setItems(temp);
        dataView.setFilterArgs({filterString: ''});
        dataView.setFilter(this.lineFilter);
        dataView.sort(comparer, true);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
		displayColumns();
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
		 displayColumns();//hide or show columns in funciton of user settings
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
        dataView.setFilterArgs({filterString: ''});
        dataView.setFilter(this.lineFilter);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
		displayColumns();//hide or show columns in funciton of user settings
    };



    //method to populate the datagrid
    this.populateProposalDataGrid = function(x) {
        data = x;
        dataView.beginUpdate();
        dataView.setItems(x);
        dataView.sort(comparer, true);
		  dataView.setFilter(this.filterInactive);
        dataView.endUpdate();
        //grid.invalidate();
        //grid.render();
		displayColumns();//hide or show columns in funciton of user settings
    };


    //method to populate the datagrid2
    this.populateProposalDataGrid2 = function(x) {
	    for(var n=0; n<x.length; n++){
		    data.push(x[n]);
	    }
        dataView.beginUpdate();
        dataView.setItems(data);
        dataView.sort(comparer, true);
		dataView.setFilter(this.filterInactive);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
		displayColumns();//hide or show columns in funciton of user settings
    };


    this.findParent = function(parents,zone,zoneid){

        for(var i = 0; i < parents.length; i++) {
            if(parents[i].zoneid == zoneid){
                return;
            }
        }
        var row 	= {};
        row.zone 	= zone;
        row.zoneid 	= zoneid;
        var re 		= parents.push(row);
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

    this.delOvernightLines = function(xmllines, goDownload){
	    
		this.selectRowsFromData(xmllines);    
        var id = grid.getSelectedRows();
        var deleteLineIds = [];
        
        id.sort(function(a,b){return b-a});

        for(var i = 0; i < id.length; i++) {
            var row = grid.getData().getItem(id[i]);            
	        $.each(data, function(i, value){
	            if(value.id == row.id){
	                data.splice(i, 1);
		             deleteLineIds.push(value.id);
	                return false;
	            }
	        })
        }
        
        $.ajax({
            type:'post',
            url: apiUrl+"proposal/deleteline",
            dataType:"json",
            headers:{"Api-Key":apiKey,"User":userid},
            processData: false,
            contentType: 'application/json',
            data: JSON.stringify({"lineIds":deleteLineIds}),
            success:function(resp){}
        });        
        
        grid.setSelectedRows([]);
        dataView.beginUpdate();
        dataView.setItems(data);
        dataView.sort(comparer, true);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();

        if(data.length == 0){
            weeksdata = [];
            datagridTotals.emptyGrid();
            resetTotals();
            flightLabel();
        }
        else{
	        setTimeout(function(){
            datagridProposal.removeUnusedWeeks();		        
	        }, 500);
        }
        if(goDownload == '1'){
	        XMLdownload();
	        fastSave();
        }
        else{
	        returnToProposalFromXML();
        }

    };


    this.buildEmptyGrid = function(){
		var tmpCols = getBasicProposalColumns();
		self.setGridColumns(tmpCols);
		grid.setColumns(tmpCols);
    };


    this.buildGrid = function(){
        buildGridForProposal();
    };	



    //get the proposal start date
    this.getStartDate = function(){
	    if(data.length < 1){
		    return false;
	    }
        var proposalStartDate = data.sort(startDate)[0].startdatetime;
        var proposalEndDate = data.sort(endDate)[0].enddatetime;
        var weeks = buildBroadcastWeeks(proposalStartDate,proposalEndDate);
        return weeks;
    };


    //Get the start and end dates for the total proposal
    this.getProposalStartDate = function(){
        var d = data.sort(startDate)[0].startdatetime;
        return d;
    };

    this.getProposalEndDate = function(){
        var d = data.sort(endDate)[0].enddatetime;
        return d;
    };


    //find the week in the proposal
    this.getWeekFromProposal = function(){
        var proposal = data;
        var ttl = 0;

        for (var i = 0; i < weeks.length; i++){ 
        }
    };


    this.rebuildGrid = function(){
        this.buildSimpleGrid(data);
    };



    //build the datagrid
    this.buildSimpleGrid = function(){
        buildGridForProposal();
    };
    

    //build the datagrid
    this.buildInitialGrid = function(proposalStartDate,proposalEndDate,demos){
	    
		var colname, newColumn, ratingsCols, weeks;
		var tmpCols = clone(self.getGridsColumns());

		if(proposalStartDate && proposalEndDate){
			weeks 		= buildBroadcastWeeks(proposalStartDate,proposalEndDate);
		}
		else if(data.length > 0){
			var sDate 	= datagridProposal.getProposalStartDate();
			var eDate 	= datagridProposal.getProposalEndDate();
			weeks 		= buildBroadcastWeeks(sDate,eDate);
		}
		else{
			weeks	= [];
		}
		
		if(demos){
			ratingsCols 	= formatDemos(demos);
		}
		else{
			ratingsCols 	= formatDemos();
		}
		
		datagridProposal.buildDemoColumns(ratingsCols);
        
        flightLabel();
        self.setGridColumns(tmpCols);
        grid.setColumns(tmpCols);
        return true;
    };


   //build the datagrid
    function buildGridForProposal(){
        var proposalStartDate 	= data.sort(startDate)[0].startdatetime;
        var proposalEndDate 	= data.sort(endDate)[0].enddatetime;
        var weeks 				= buildBroadcastWeeks(proposalStartDate,proposalEndDate);
		var colname;
		var newColumn;
		
        var tmpCols = getBasicProposalColumns();

        for (var i = 0; i < weeks.length; i++){
			newColumn = {};
            colname = "w"+weeks[i].column;

	        newColumn.id 		= colname;
	        newColumn.sortable 	= true;
	        newColumn.name		= weeks[i].date;
	        newColumn.field		= colname;
	        newColumn.width		= 70;
	        newColumn.minWidth	= 70;
	        newColumn.maxWidth	= 70;
	        newColumn.dynamic	= 1;
	        newColumn.cssClass  = "dynamicRight";
                        
            if($.inArray(colname, weeksdata) != -1){ //if is not a hidden week
                newColumn.formatter = Slick.Formatters.HiddenFormat;
                newColumn.cssClass	= 'hiddenweek';
                newColumn.headerCssClass = 'hiddenweek';
            }
            else{
                newColumn.formatter = Slick.Formatters.RowCount;
                newColumn.editor	= Slick.Editors.Integer;
            }
			tmpCols.push(newColumn);
        }
        
        flightLabel();
        self.setGridColumns(tmpCols);
        grid.setColumns(tmpCols);
        return true;
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


    this.isRowInHiddenWeek = function() {
        var current = ishidden;
        ishidden = false;
        return current;
    }


    // add row
    this.addRowToProposal = function(row) {
        

        for(var i = 0; i < data.length; i++) {
            if(data[i].id == row.id){
                return false;
            }
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


        if(ratecard){
            var rate = ratecardType(rateType,row,ratecardData,ratecardHotPrograms);
            row.ratevalue = rate;
        }
        data.push(row);
    };




    //split the lines
    this.splitProposalLine = function(row){
        var sArr  = row.startdate.split(/[^0-9]/);
        var eArr  = row.enddate.split(/[^0-9]/);
        var sdate = new Date(parseInt(sArr[0]),parseInt(sArr[1])-1,parseInt(sArr[2])).toString("yyyyMMdd");
        var edate = new Date(parseInt(eArr[0]),parseInt(eArr[1])-1,parseInt(eArr[2])).toString("yyyyMMdd");
        var uuid = GUID();


        if(sdate == edate){
            var re = [];
            if(ratecard){
                var rate = ratecardType(rateType,row,ratecardData,ratecardHotPrograms);
                row.ratevalue = rate;
            }
            re.push(row);
            return re;
        }


   

        var msSinceMidnight= new Date.parse(row.enddatetime)-new Date.parse(row.enddatetime).setHours(0,0,0,0);

        if(msSinceMidnight < 900000){
            var enddatetime = new Date.parse(row.startdatetime).toString("yyyy/MM/dd 00:00");
            var enddate = new Date.parse(row.startdatetime).toString("MM/dd/yyyy");
            var endtime = "12:00 AM";
            row.enddate = enddate;
            row.enddatetime = enddatetime;
            row.endtime = endtime;

            var re = [];
            if(ratecard){
                var rate = ratecardType(rateType,row,ratecardData,ratecardHotPrograms);
                row.ratevalue = rate;
            }
            re.push(row);
            return re;
        }


        var rowa = jQuery.extend({}, row);
        var enddatetime = new Date.parse(row.startdatetime).toString("yyyy/MM/dd 00:00");
        var enddate = new Date.parse(row.startdatetime).toString("MM/dd/yyyy");
        var endtime = "12:00 AM";
        rowa.enddate = enddate;
        rowa.enddatetime = enddatetime;
        rowa.endtime = endtime;
        rowa.split = 1;
        //rowa.search = 'Split Lines Pt. 1';
        
        if(rowa.search == 'Package'){
            rowa.search = 'Package | Split Lines Pt. 1';
        }else{
            rowa.search = 'Split Line Pt. 1';
        }

		var formatStartDateTimeClean = Date.parse(rowa.startdatetime).toString("yyyyMMddHHmm");
		formatStartDateTimeClean = formatStartDateTimeClean.replace(/[^a-zA-Z0-9 ]/g, '');
		
        rowa.sortingStartDate = rowa.zone + formatStartDateTimeClean+ rowa.callsign + rowa.title;
        
        var rowb = jQuery.extend({}, row);
        var startdatetime = new Date.parse(row.enddatetime).toString("yyyy/MM/dd 00:00");
        var startdate = new Date.parse(row.enddatetime).toString("MM/dd/yyyy");

        if(rowb.day == 7){
            rowb.day = "1";
        }else{
            var n = parseInt(rowb.day) + 1;
            rowb.day = n.toString();
        }

        var d = formatterDayOfWeek(rowb.day.toString());
        rowb.dayFormat = d;

        var starttime = "12:00 AM";
        
        rowb.startdatetime = startdatetime;
        rowb.startdate = startdate;
        rowb.starttime = starttime;
        rowb.split = 1;
        rowb.id = row.id+'-b';


		var formatStartDateTimeClean = Date.parse(startdatetime).toString("yyyyMMddHHmm");
		formatStartDateTimeClean = formatStartDateTimeClean.replace(/[^a-zA-Z0-9 ]/g, '');


        //fix for the split sorting (YAY). Add aminute to the start so the items stay grouped
        var startdatetimeSorting = new Date.parse(row.startdatetime).addMinutes(1).toString("yyyy/MM/dd HH:mm");
        rowb.sortingStartDate = rowb.zone + formatStartDateTimeClean + rowb.callsign + rowb.title;
        

        if(rowb.search == 'Package'){
            rowb.search = 'Package | Split Lines Pt. 2';
        }else{
            rowb.search = 'Split Line Pt. 2';
        }

        delete rowb[rowb.weekId];

        var mon = getMondayFromDate(startdatetime);
        rowb[mon] = 1;
        rowb.weekId = mon;


        if(ratecard){
            var ratea = ratecardType(rateType,rowa,ratecardData,ratecardHotPrograms);
            rowa.ratevalue = ratea;
        }

        if(ratecard){
            var rateb = ratecardType(rateType,rowb,ratecardData,ratecardHotPrograms);
            rowb.ratevalue = rateb;
        }

        var re = [];
        re.push(rowa);
        re.push(rowb);
        return re;        
    }





    //apply the ratecard
    this.applyRateCard = function(disc){
       var lines = [];
       var idList= [];
       var ids   = grid.getSelectedRows();

        if(ids.length < 1){
	        return false;
        }

       $.each(ids, function (index, value) {
            var line = grid.getData().getItem(value);
            if(line.__group != true){
                var cardvalue = parseFloat(line.ratevalue);
                var discttl = (cardvalue * (disc/100));
                var nrate = cardvalue - discttl;
                line.rate = nrate;
                line.total = nrate * line.spots;
                if(idList.indexOf(line.id) === -1){
	                lines.push({"lineId":line.id,"rate":nrate});
	                idList.push(line.id);
                }
            } 
            else{
				var rows  = self.selectedRows();
				for(var i = 0; i < rows.length; i++) {
					if(rows[i].__group === true){
						var xrow, x;
						var group = rows[i].rows;
						for(x = 0; x < group.length; x++) {
							xrow = group[x];
							xrow.rate = xrow.ratevalue;
							xrow.total = xrow.ratevalue * xrow.spots;
							if(idList.indexOf(xrow.id) === -1){
								lines.push({"lineId":xrow.id,"rate":xrow.ratevalue});
								idList.push(xrow.id);
							}
						}
					}
				}
            }
       });

       grid.invalidate();
       grid.render();
       datagridTotals.populateDataGrid(data);
       

       $.ajax({
            type:'post',
            url: apiUrl+"proposal/applyrates",
            dataType:"json",
            headers:{"Api-Key":apiKey,"User":userid},
            processData: false,
            contentType: 'application/json',
            data: JSON.stringify({"lines":lines}),
            success:function(resp){
				var proposalStartDate = data.sort(startDate)[0].startdatetime;
			    var proposalEndDate = data.sort(endDate)[0].enddatetime;
				datagridProposal.updateRatingTotals(resp);
				datagridTotals.populateDataGridGoPlus(datagridProposal.dataSet(),proposalStartDate,proposalEndDate,resp.totals)
				closeApiCallFlag();
            },
            error:function(){
				closeApiCallFlag();
            }
        });

    };




    //add rotator
    this.addRotatorToProposal = function(row) {
		if(weeksdata.length < 1){
	        for(var i = 0; i < data.length; i++) {
	            if(data[i].id == row.id){
	                return false;
	            }
	        }			
		}
		else{ //updating number of weeks if there are hidden 

	        var proposalStartDate = data.sort(startDate)[0].startdatetime;
	        var proposalEndDate = data.sort(endDate)[0].enddatetime;
	        var weeks = buildBroadcastWeeks(proposalStartDate,proposalEndDate);
	        
	        for(var i = 0; i < data.length; i++) {
		        var weekcnt = weeksCount(data[i]['startdate'], data[i]['enddate']);
				var sd 		= String(getBroadcastWeek(data[i]['startdate'])).replace('w', '');
				var ed 		= String(getBroadcastWeek(data[i]['enddate'])).replace('w', '');	
		        var sdate 	= new Date(parseInt(sd.substr(4, 4)), parseInt(sd.substr(0, 2))-1, parseInt(sd.substr(2, 2)));
		        var edate 	= new Date(parseInt(ed.substr(4, 4)), parseInt(ed.substr(0, 2))-1, parseInt(ed.substr(2, 2)));
		        
				for(j=0;j<weeks.length;j++){
					
                    var dateArr  = weeks[j]['dateFull'].split(/[^0-9]/);
                    var thisDate = new Date(parseInt(dateArr[0]), parseInt(dateArr[1])-1,parseInt(dateArr[2]));
                    var thisWeek = 'w'+weeks[j]['column'];
					if( ($.inArray(thisWeek,weeksdata) != -1 || data[i][thisWeek] == 0 ) && (thisDate >= sdate && thisDate <= edate) ){
						weekcnt = weekcnt-1;
					}	
				}

		        data[i]['weeks'] = weekcnt;
		        
	            if(data[i].id == row.id){
	                return false;
	            }
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
		//displayColumns(); 
    };


    this.selectedRows = function() {
        var selectedData = [];
        var selectedIndexes = grid.getSelectedRows();
    
        $.each(selectedIndexes, function (index, value) {
            selectedData.push(grid.getData().getItem(value));
        });
        return selectedData;
    }


    this.selectedRowsByType = function() {
	    
        var selectedIndexes = grid.getSelectedRows();
        var items 		= {};
        var row;
        items.Fixed 		= 0;
        items.Rotator 	= 0;
        items.Avail 		= 0;
        items.ByDay 		= 0;

        $.each(selectedIndexes, function (index, value){
	        
            row 	= grid.getData().getItem(value);
            items[row['linetype']]	+= 1;
            
            if(row['linetype2'] === 'Avail'){
	            items[row['linetype2']] += 1;
	        }

            if(row.lineType === 4){
				items.ByDay ++;
	        }
        });

        return items;
    };


    this.dataSet = function() {
        return data;
    };


	this.mapLines = function(lineIds){
	    var foundIds = [];
	    var i,j;
	    
        for(i = 0; i < data.length; i++){
	        
			for(j = 0; j<lineIds.length; j++){
			
				if( lineIds[j] === data[i].solrId+'-'+data[i].zoneId ){
					foundIds.push(data[i].id);
				}
			};			
        };	
        	
        return foundIds;
	};

	this.quickLineSearch = function(id){
	    var foundIds = [];
        for(var i = 0; i < data.length; i++){			
			if( id === data[i].solrId+'-'+data[i].zoneId ){
				foundIds.push(data[i]);
				break;
			}
        };	        	
        return foundIds;
	};

    this.deleteLineFromProposal = function(lineIds){

	    var deleteIds = [];
	    var linePos = [];
	    var i,j,n;
	    
        for(i = 0; i < data.length; i++){
			for(j = 0; j <lineIds.length; j++){
				if(String(lineIds[j]) === String(data[i].solrId+'-'+data[i].zoneId)){
					deleteIds.push(data[i].id);
					linePos.push(i);
				}
			};			
        };


		linePos.sort(function(a, b){return b-a});        
        
		if(deleteIds.length > 0){
			$.ajax({
	            type:'post',
	            url: apiUrl+"proposal/deleteline",
	            dataType:"json",
	            headers:{"Api-Key":apiKey,"User":userid},
	            processData: false,
	            contentType: 'application/json',
	            data: JSON.stringify({"lineIds":deleteIds}),
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
	
	        datagridTotals.populateDataGrid(data);
	
	        if(data.length == 0){
				weeksdata = [];
				datagridTotals.emptyGrid();
				resetTotals();
				flightLabel();
	        }
	        else {
	            self.removeUnusedWeeks();
	        }
        }
    };


    this.deleteConfirmed = function(){    
        var id        = grid.getSelectedRows();
        var deleteIds = [];
        
        id.sort(function(a,b){return b-a});
        for(var i = 0; i < id.length; i++) {
            var row = grid.getData().getItem(id[i]);

            //if the row is a group then lets delete them all
            if(row.__group == true){
                $.each(row.rows, function(i, rid){
                    $.each(data, function(i, value){
                        if(value.id == rid.id){
                            deleteIds.push(rid.id);
                            data.splice(i, 1);
                            return false;
                        }
                    })
                });
            } else {
                $.each(data, function(i, value){
                    if(value.id == row.id){
                        deleteIds.push(row.id);
                        data.splice(i, 1);
                        return false;
                    }
                });
            }
            
            if(data.length === 0){
				//reset();
				datagridProposal.populateDataGrid();
            }
        }

        $.ajax({
            type:'post',
            url: apiUrl+"proposal/deleteline",
            dataType:"json",
            headers:{"Api-Key":apiKey,"User":userid},
            processData: false,
            contentType: 'application/json',
            data: JSON.stringify({"lineIds":deleteIds}),
            success:function(resp){
				datagridProposalManager.updateSelectedProposalRow(resp);
				datagridTotals.populateDataGridGoPlus(data,resp.dates.startDate,resp.dates.endDate,resp.totals);
            }
        });
        
		  editRotator = false;
		  editRotatorItems = {};
        
        grid.resetActiveCell();
        closeAllDialogs();

        if(data.length === 0){
            weeksdata = [];
            datagridTotals.emptyGrid();
            var tmpCols = getBasicProposalColumns();
            self.setGridColumns(tmpCols);
			grid.setColumns(tmpCols);
            resetTotals();
            flightLabel();
			displayColumns();
        } else {
            self.removeUnusedWeeks();
        }
    };


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



    this.createBonusLines = function(){
        $("#dialog-edit-lines").dialog("destroy");


        var rows = this.selectedRows();

        $.each(rows, function(i, row){

            if(row.linetype == 'Fixed'){
                var temprow = jQuery.extend({}, row);
                var uuid = GUID();

                temprow.id = uuid;
                temprow.spotsweek = 1;
                temprow[row.weekId] = 1;
                temprow.spots = 1
                temprow.rate = 0;
                temprow.total = 0;
                temprow.search = 'BONUS';

                data.push(temprow);
            }
        })

            dataView.beginUpdate();
            dataView.setItems(data);
            dataView.sort(comparer, true);
            dataView.endUpdate();
            grid.invalidate();
            grid.render();
            grid.resetActiveCell();
            datagridTotals.populateDataGridFromData();
    }



  this.updateLinesFromEditOverlay = function(mode){
        var newspotsweek = $("#edit-line-spots").val();
        var newrate = $("#edit-line-rate").val();
        var rows = this.selectedRows();
        



        for(var i = 0; i < rows.length; i++) {

	        if(rows[i].__group == true){
	            var group = rows[i].rows;
	
	
	            for(var x = 0; x < group.length; x++) {
	                var xrow = group[x];
	
	                if(mode == 'spot' && newspotsweek > 0){
	                    xrow.spotsweek = newspotsweek;
	                    proposalUpdateSpotsInSpotsPerWeek(xrow,'none');
	                }
	                
	                if(mode == 'rate'){
	                    xrow.rate = newrate;
	                    proposalUpdateRate(xrow,'none');
	                }	                
	            }
	
	        }else{
	            if(mode == 'spot' && newspotsweek > 0){
	                rows[i].spotsweek = newspotsweek;
	                proposalUpdateSpotsInSpotsPerWeek(rows[i],'none');
	                $("#edit-line-spots").val(1);
	            }
	
	            if(mode == 'rate'){
	                rows[i].rate = newrate;
	                proposalUpdateRate(rows[i],'none');
	                $("#edit-line-rate").val(0);
	            }
	        }
        

        }

        grid.invalidate();
        grid.render();

        datagridTotals.populateDataGridFromData('overlay');

        if(close == 1){
            closeAllDialogs();
        }

    }



	this.updateRateFromOverlay = function(mode){
        var newrate = parseFloat($("#edit-line-rate").val());
        if(!isNumber(newrate)){
            $("#edit-line-rate").css({'background-color':'yellow'});
            setTimeout(function(){$("#edit-line-rate").css({'background-color':'white'});},500);
            closeApiCallFlag();
            return false;
        }

        var pdata  	= {"rate":newrate,"lineIds":[]};
        var rows  	= this.selectedRows();

        if(rows.length < 1){
	        return false;
        }

        for(var i = 0; i < rows.length; i++) {
            if(rows[i].__group === true){
	            var xrow, x;
                var group = rows[i].rows;
                for(x = 0; x < group.length; x++) {
					xrow = group[x];
	                if(pdata.lineIds.indexOf(xrow.id) === -1){
						pdata.lineIds.push(xrow.id);
						xrow.rate 	= newrate;
						xrow.total 	= newrate * xrow.spots;
					}
                }
            } 
            else {
				if(pdata.lineIds.indexOf(rows[i]) === -1){
                	pdata.lineIds.push(rows[i].id);
					rows[i].rate 	= newrate;
					rows[i].total = newrate * rows[i].spots;
				}
            }
            $("#edit-line-rate").val(0);
        }
        grid.invalidate();
        grid.render();

        $.ajax({
            type:'post',
            url: apiUrl+"proposal/applyrate",
            dataType:"json",
            headers:{"Api-Key":apiKey,"User":userid},
            processData: false,
            contentType: 'application/json',
            data: JSON.stringify(pdata),
            success:function(resp){
				var proposalStartDate = data.sort(startDate)[0].startdatetime;
				var proposalEndDate = data.sort(endDate)[0].enddatetime;
				datagridProposal.updateRatingTotals(resp);				
				datagridTotals.populateDataGridGoPlus(datagridProposal.dataSet(),proposalStartDate,proposalEndDate,resp.totals);
	            closeApiCallFlag();
            },
            error:function(){
	            closeApiCallFlag();
            }
        });
        
    };


	this.updateSpotsFromOverlay = function(){
		
		if(!isValidSpot()){
            closeApiCallFlag();
			return false;
		}
        
        var newspotsweek 	= parseInt($("#edit-line-spots").val());
        var data  			= {};
        data.lineIds		= [];
        data.spots 			= newspotsweek;
        
        var rows 			= this.selectedRows();

        if(rows.length < 1){
	        return false;
        }

		var spotsUpdate,group, val;
		

        for(var i = 0; i < rows.length; i++){

			//LINES GROUPED
            if(rows[i].__group === true){
	            
                group = rows[i].rows;
                var xrow;
                
                for(var x = 0; x < group.length; x++) {
                    xrow = group[x];

                    if(data.lineIds.indexOf(xrow.id) === -1){
	                    data.lineIds.push(xrow.id);
						xrow.spotsweek = newspotsweek;
						val = calculateEffectiveTotals(xrow, xrow.startdate, xrow.enddate); 
						if(xrow.lineType === 4){
		                	spotsUpdate 	= spotsDistribution(newspotsweek);
							data.spots 		= spotsUpdate.spots;
							updateSpotsByDay(xrow,spotsUpdate.spots);
						}
					}
                }
            }//INDIVIDUAL LINES
            else {

				if(data.lineIds.indexOf(rows[i].id) === -1){	            
                	data.lineIds.push(rows[i].id);
					rows[i].spotsweek = newspotsweek;
					val = calculateEffectiveTotals(rows[i], rows[i].startdate, rows[i].enddate);
                
					if(rows[i].lineType === 4){
	                	spotsUpdate 	= spotsDistribution(newspotsweek);
						data.spots 		= spotsUpdate.spots;
						updateSpotsByDay(rows[i],spotsUpdate.spots);
                	}
                }
            }
            
            $("#edit-line-spots").val(1);
        }

        grid.invalidate();
        grid.render();
        datagridTotals.populateDataGridFromData('overlay');
        $.ajax({
            type:'post',
            url: apiUrl+"proposal/applyspots",
            dataType:"json",
            headers:{"Api-Key":apiKey,"User":userid},
            processData: false,
            contentType: 'application/json',
            data: JSON.stringify(data),
            success:function(resp){
				var proposalStartDate = datagridProposal.getProposalStartDate();
			    var proposalEndDate = datagridProposal.getProposalEndDate();
				datagridProposal.updateRatingTotals(resp);
				datagridTotals.populateDataGridGoPlus(datagridProposal.dataSet(),proposalStartDate,proposalEndDate,resp.totals);
	            closeApiCallFlag();
            },
            error:function(){
	            closeApiCallFlag();
            }
        });
    }



    //THIS IS WHERE WE UPDATE THE LINE TITLES
    this.updateLineTitlesFromSelection = function(title){
		
		if(!isValidTitle())
			return false;

        var title   = $('#edit-line-title').val(); //NEW TITLE
		var rows    = this.selectedRows(); //GET THE SELECTED ROWS
        var data    = {"title":title, lineIds:[]};

        $.each(rows, function(i, row){
	        if(!('__group' in row)){
	            if(row.linetype !== 'Fixed'){
	                data.lineIds.push(row.id);
	                var sDateArr  = row.startdatetime.split(/[^0-9]/);
	                var eDateArr  = row.enddatetime.split(/[^0-9]/);
	
	                var formatStartDateTimeClean = new Date(parseInt(sDateArr[0]),parseInt(sDateArr[1])-1,parseInt(sDateArr[2]),parseInt(sDateArr[3]),parseInt(sDateArr[4])).toString("yyyyMMddHHmm"); 
	                var formatEndDateTimeClean   = new Date(parseInt(eDateArr[0]),parseInt(eDateArr[1])-1,parseInt(eDateArr[2]),parseInt(eDateArr[3]),parseInt(eDateArr[4])).toString("yyyyMMddHHmm");
	                row.title = title;
	                row.titleFormat = title;
	                row.sortingStartDate = row.zone + formatStartDateTimeClean + row.callsign + row.title;
	                row.availsShow = row.title + ' - ' + row.callsign + ' - ' + row.starttime + ' - ' + row.endtime;
	                row.showLine = row.callsign + ' - ' + row.title + ' - ' + row.starttime + ' - ' + row.endtime;
	                row.sortingMarathons = row.callsign + formatStartDateTimeClean + row.title;
	            }
            }
        });

        grid.invalidate();
        grid.render();
        closeAllDialogs();
        $('#edit-line-title').val('');  

        $.ajax({
            type:'post',
            url: apiUrl+"proposal/line/edittitle",
            dataType:"json",
            headers:{"Api-Key":apiKey,"User":userid},
            processData: false,
            contentType: 'application/json',
            data: JSON.stringify(data),
            success:function(resp){
                //loadProposalFromServerSilent(proposalid,"");
            }
        });
            
    }


	this.confirmEdit = function(){
		var lByDayCnt = 0;		
		var selectedRows =this.selectedRows();
		for(var i=0; i<selectedRows.length;i++){
			if(selectedRows[i].lineType === 4){//LINE ORDER
				lByDayCnt++
			}
		}		
		
		if(lByDayCnt > 1){
			loadDialogWindow('massedit', 'ShowSeeker Plus', 450, 180, 1, 0);			
		}
		else{
			this.updateSelectedRotators(1);
		}
		return false;		
	};

    this.updateSelectedRotators = function(close){

		var params    = solrSearchParamaters();        
		var sDateArr  = params.startdate.split(/[^0-9]/);
		var startDate = new Date(parseInt(sDateArr[0]), parseInt(sDateArr[1])-1, parseInt(sDateArr[2])).toString("yyyy-MM-dd");
		var eDateArr  = params.enddate.split(/[^0-9]/);
		var endDate   = new Date(parseInt(eDateArr[0]), parseInt(eDateArr[1])-1, parseInt(eDateArr[2])).toString("yyyy-MM-dd");
		var sTimeArr  = params.starttime.split(/[^0-9]/);
		var startTime = new Date(2000,0,1,parseInt(sTimeArr[0]),parseInt(sTimeArr[1])).toString("HH:mm:ss");
		var eTimeArr  = params.endtime.split(/[^0-9]/);
		var endTime   = new Date(2000,0,1,parseInt(eTimeArr[0]),parseInt(eTimeArr[1])).toString("HH:mm:ss");
		
		var networks  = [];
		var lineId    = [];
		var spotsUpdate;
		
		$.each(params.networks,function(i,net){
			networks.push(parseInt(net.id));
		});
		
		$.each(this.selectedRows(),function(i,r){
			if('__group' in r){
				return true;
			}	        

			if(r.linetype !== 'Fixed'){
				lineId.push(r.id); 
			}
		});
		
        
		var rows	 = this.selectedRows(); 
		var type   	 = editRotatorItems['dates'] + editRotatorItems['times'];
		var changes  = {};	    
	    
	    
		if(dateTimeValidator() != 0){
			return;
		}

		if(editRotatorItems.rate === 1){
			changes.rate 	= params.schedulerate;
		}

		//if(editRotatorItems.spots === 1 || (editRotatorItems.days === 1 && rows[0].lineType === 4)){
		if(editRotatorItems.spots === 1 || (editRotatorItems.days === 1)){
			changes.spots 	= parseInt(params.schedulespots);			
			
			
			//if(rows[0].lineType === 4){//SPOTS BY DAY
				spotsUpdate 	= spotsDistribution();
				changes.spots 	= spotsUpdate.spots;
			//}	
		}

		if(editRotatorItems.days === 1){
			changes.day		= params.days;
		}

		if(editRotatorItems.times === 1){
			changes.startTime	= startTime;
			changes.endTime 	= endTime;
		}
		
		if(editRotatorItems.dates === 1 || editRotatorItems.weeks === 1){
			changes.startDate	= startDate;
			changes.endDate 	= endDate;
		}

		if(editRotatorItems.network === 1 && rows.length === 1){
		
			if(networks[0] === 0){
				loadDialogWindow('singlenetwork', 'ShowSeeker Plus', 450, 180, 1, 0);
				return;
			}
			changes.networkId 	= networks[0];
		}
        
		var rotatorData 		= {};
		rotatorData.proposalId	= proposalid;
		rotatorData.lineId		= lineId;
		rotatorData.changes		= changes;        

        
		if($.isEmptyObject(changes)){
			closeAllDialogs();
			return;
		}

		
		loadDialogWindow('createrotators', 'ShowSeeker Plus', 450, 180, 1);

		var rateCardVal;
		var rate, val;
		var rcs   = [];
		
		$.each(rows, function(i, row){
		
			if('__group' in row){
				return true;
			}
			
         if(row.linetype === 'Rotator' || row.linetype === 'Avail'){

            if(editRotatorItems.rate === 1){
                row.rate = parseFloat(params.schedulerate) ;
                lineNewRates(row);
            }

			if(editRotatorItems.spots === 1){
			
				row.spotsweek 	= parseInt(params.schedulespots);
				if(row.lineType === 5){//LINE ORDER
					val = proposalUpdateDatesInLineLine(row.startdate,row.enddate,row)
				}
				else{
					val = calculateEffectiveTotals(row, row.startDate, row.endDate);
			
					if(row.lineType === 4){
						updateSpotsByDay(row,spotsUpdate.spots);
					}
				}   
			}

            if(editRotatorItems.days === 1){
					var days 		= params.days;
					var daysformat = schedulerDaysOfWeek(days);
					row.day 		= days;
					row.dayFormat 	= daysformat;
					
					val = calculateEffectiveTotals(row, row.startDate, row.endDate,'dates');
					
					if(row.lineType === 4){
						updateSpotsByDay(row,spotsUpdate.spots);
					}
            }

            if(editRotatorItems.times === 1){
					rateCardVal = {};
					val =  proposalBuildStartEndTimes(params.starttime,params.endtime,row.startdate,row.enddate);
					row.starttime = val.starttime;
					row.endtime = val.endtime;
					row.startdatetime = val.startdatetime;
					row.enddatetime = val.enddatetime;
					rate = ratecardType(rateType,row,ratecardData);
					row.ratevalue = rate;
					rateCardVal.lineId = row.id;
					rateCardVal.rateValue = rate;
					rcs.push(rateCardVal);
            }
            if(editRotatorItems.dates === 1 || editRotatorItems.weeks === 1){
                proposalUpdateDatesInLine(params.startdate,params.enddate,row);
            }

            if(editRotatorItems.network === 1 && rows.length === 1){
                proposalUpdateNetwork(arrayNetworks,row);
            }    
         }
            
		});
		
		
		rotatorData.ratecards = rcs;
		rotatorData.manualSpotAllocation = manualSpotAllocation;
		rotatorData.ratecardId = params.ratecardId;
		
		if(weeksdata.length > 0){
			rotatorData.inactiveWeeks = getInactiveWeeks();
		}
		
        $.ajax({
            type:'post',
            url: apiUrl+"proposal/editlinebyday",
            dataType:"json",
            headers:{"Api-Key":apiKey,"User":userid},
            processData: false,
            contentType: 'application/json',
            data: JSON.stringify(rotatorData),
            error:function(){
	            manualSpotAllocation = false;
            },
            success:function(resp){
				manualSpotAllocation = false;
				var newCols 	= self.deleteDynamicColumns();
				var dynaCols 	= self.buildDynamicColumns(resp.dates.startDate, resp.dates.endDate);//calculateDynamicWeeks\
				var c 			= newCols.concat(dynaCols);
				self.setGridColumns(c);
				grid.setColumns(c);

				if(editRotatorItems.dates === 1 || editRotatorItems.weeks === 1|| editRotatorItems.spots === 1 || editRotatorItems.days === 1){
					var dynamicWeek;
					var dynamicCol;
					var i = 0;
				
					$.each(resp.lines,function(i,line){
						if(line.lineType !== 4){
							return true;
						}
	
						$.each(rows, function(i, r){						
							if(r.__group === true){
								return true;
							}
							if(r.lineType !== 4){
				                return true;
							}
			            if(r.id === line.id){//FIND DISCREPANCIES IN THE SPOTS AND ADJUSTS

				            $.each(line.weeks,function(n,w){
										
								dynamicWeek = w.week.split('-');
								dynamicCol = dynamicWeek[1]+dynamicWeek[2]+dynamicWeek[0];
								
								if('w'+dynamicCol+'hide' in r){
									r['w'+dynamicCol+'hide'] = w.spot;
									r['w'+dynamicCol] = 0;
								}
								else{
									r['w'+dynamicCol] = w.spot;
								}
							
								r['s'+dynamicCol] = w.spots;

								val = proposalUpdateDatesInLine(params.startdate,params.enddate,r);
								
								for(i =0; i<data.length; i++){
									if(data[i].id === r.id){
										data[i] = r;
									}
								}
				            });
			            }
						});
	
					});
	            }
	            else if(editRotatorItems.network === 1 && rows.length === 1){
					var rowIdx 	= dataView.getRowById(row.id);
					row.notInSurvey = resp.lines[0].notInSurvey;
					grid.invalidateRow(rowIdx);
					grid.render();
	            }
				
				closeAllDialogs();  
				editRotator 		= true;		
				spotsByDayOfWeek 	= {};
				datagridProposal.updateRatingTotals(resp);	
				$("#dialog-window").dialog("destroy");
				resetEditRotatorItems();
				loadProposalTitles(resp.lines);
            }
        });


      }
        
        
        
    function loadProposalTitles(lines) {	
		if(lines.length === 0){
			return;
		}	
		var rowIdx;
		var title;
		var item;
		var rows =[];
		for(var i=0; i<lines.length; i++){
			title 				= lines[i].title;
			rowIdx 	= dataView.getRowById(lines[i].id);
			item 	= dataView.getItem(rowIdx);
			item.titleFormat 	= title;
			item.title 		= title;
			rows.push(rowIdx);
		}
		grid.invalidateRow(rows)
        grid.invalidate();
	}


    function proposalUpdateDatesInLine(startdate,enddate,row){
        if(row.lineType === 5 ){//LINE ORDER
            proposalUpdateDatesInLineLine(startdate,enddate,row)
        }else if(row.linetype === 'Rotator' || row.linetype ==='Avail'){
            proposalUpdateDatesInLineRotator(startdate,enddate,row);
        }
    }



    //edit lines
	function proposalUpdateDatesInLineLine(startdate,enddate,row,lineSpots){
		var m,d,y,cDate,thisWk,currentdate,nonvalid;
		var sd  		= getBroadcastWeekGoPlus(startdate);
		var ed  		= getBroadcastWeekGoPlus(enddate);
		var tmpCols 	= clone(self.getGridsColumns());
		var firstWk 	= 0;
		var lastWk 		= 0;
				
		$.each(tmpCols, function(i, value) {
			if(value.dynamic === 1) {
				m = parseInt(String(tmpCols[i].id).substr(1, 2));
				d = parseInt(String(tmpCols[i].id).substr(3, 2));
				y = parseInt(String(tmpCols[i].id).substr(5, 4));
				cDate = getBroadcastWeekGoPlus(y+'-'+m+'-'+d);;
				if( cDate.getTime() < sd.getTime() || ed.getTime() < cDate.getTime()){
					delete row[tmpCols[i].id];
					delete row[tmpCols[i].id.replace('w', 's')];
					delete row.weekIdMapping[tmpCols[i].id];
				}
			}
		});
		
		//break out the time from the dates
		var sDateArr  			= startdate.split(/[^0-9]/);
		var eDateArr  			= enddate.split(/[^0-9]/);

		//parse the dates as the new ones
		var sDateTime 			= new Date(parseInt(sDateArr[0]), parseInt(sDateArr[1])-1, parseInt(sDateArr[2]),parseInt(sDateArr[3]),parseInt(sDateArr[4]));
		var eDateTime   		= new Date(parseInt(eDateArr[0]), parseInt(eDateArr[1])-1, parseInt(eDateArr[2]),parseInt(eDateArr[3]),parseInt(eDateArr[4]));
		var weeks     			= buildBroadcastWeeksGoPlus(startdate, enddate);
		var activeWeekCount 	= 0;//weekcnt;
		var inactiveWeeks 		= getInactiveWeeks();
		var spotcount 			= 0;


		//days of the week to count effective totals
		var dayofwk 	= effectiveLineDays(row.day);
		var uniques 	= dayofwk.unique();
		
		for(var k = weeks.length-1; k>=0; k--){
			nonvalid	 	= 0;
			$.each(uniques,function(n,v){
				thisWk = weeks[k].column;
				currentdate = new Date(parseInt(thisWk.substr(4, 4)), parseInt(thisWk.substr(0, 2))-1, parseInt(thisWk.substr(2, 2)),parseInt(sDateArr[3]),parseInt(sDateArr[4]));
				currentdate.setDate(currentdate.getDate() + parseInt(v));
				ctime = currentdate.getTime();
				if(ctime >= sDateTime.getTime() && ctime <= eDateTime.getTime()){
					if(k === weeks.length-1){
						lastWk++;
					}
					else if(k ===0){
						firstWk++;
					}
				}
				else{
					nonvalid++;
				}
			});

			if(nonvalid < uniques.length && inactiveWeeks.indexOf(weeks[k].dateISO) === -1){
				activeWeekCount++;
			}	
			else{
				weeks.splice(k, 1);
				if(k === weeks.length-1){
					lastWk = 0;
				}
				else if(k ===0){
					firstWk = 0;
				}
			}
		}
	
		var startweekdaycount,endweekdaycount;
		var weekcnt   		= weeks.length;	

        //get how many days of the week are left. 
                
		startweekdaycount 	= firstWk/uniques.length;
		endweekdaycount 	= lastWk/uniques.length;        
		
        if(firstWk === uniques.length || firstWk === 0){
		  	startweekdaycount = 1;
        }

		if(lastWk === uniques.length || lastWk === 0){
			endweekdaycount = 1;
		}

        //get the spots to be scheduled over the weeks and divide by the total weeks to get an avg for all the weeks
        var totalspots		= $('#schedule-spots').val();

        if(lineSpots){
	         totalspots 	= lineSpots;
        }

        var avgperweek 		= Math.floor(totalspots/activeWeekCount);

        //multiply the average per week by the week count to the get total used spots then subtract them ftom the total spots this 
        //gives you the remaining spots to publish over the weeks
        var totalusedfromavg 		= avgperweek * activeWeekCount;
        var totalspotsleftfromavg 	= totalspots - totalusedfromavg;


        //take the total days in the start week and end week then divide by 7 and multiply by the average per week
        //this gives us the percentage of spots needed for the weeks. Use math.ceil to round up so there is always at least 1 spot in a week
        var avgfromfullweekstart 	= Math.ceil(startweekdaycount*avgperweek);
        var avgfromfullweekend 		= Math.ceil(endweekdaycount*avgperweek);
     
        
        //now get the reamining spots carried over from the start and end weeks
        var weekstartleftover 		= avgperweek - avgfromfullweekstart;
        var weekendleftover 		= avgperweek - avgfromfullweekend;
        
        
        //now add the remaining spots to the unused spots pool so we no what number is left
        var totalunusedspots 		= totalspotsleftfromavg + weekstartleftover + weekendleftover;

		//loop over the middle weeks and set the avg
		for (var i = 0; i < weekcnt; i++){
			if(inactiveWeeks.indexOf(String(weeks[i].dateFull).replace(/\//ig, '-')) === -1){
				weeks[i].count = avgperweek;
			}
		}

        //set count in the first and last week
        if(weekcnt > 0){        
	        weeks[0].count	 			= avgfromfullweekstart;
	        weeks[weekcnt-1].count	 	= avgfromfullweekend;
        }

        //set the loop to reset the weeks for count addition
        var weekloop = 0;

		while(totalunusedspots > 0){
			for(var weekloop = 0; weekloop < weekcnt; weekloop++){
				
				if(inactiveWeeks.indexOf(String(weeks[weekloop].dateFull).replace(/\//ig, '-')) === -1){
					weeks[weekloop].count = weeks[weekloop].count + 1;
					totalunusedspots--;
					if(totalunusedspots === 0){
						break;
					}
				}
			}
		} 

		var z;
		
		for (var i = 0; i < weeks.length; i++){
			z 			=  'w'+weeks[i].column;
			spotcount   += weeks[i].count;
			row[z] 		=  parseInt(weeks[i].count);
		}
		
		var st				= row.startdatetime.split(/[^0-9]/);
		var et 				= row.enddatetime.split(/[^0-9]/);
		
		var rowEndDate		= eDateTime.toString("yyyy-MM-dd")+' '+et[3]+ ':'+et[4]+ ':00';
		var rowStartDate 	= sDateTime.toString("yyyy-MM-dd")+' '+st[3]+ ':'+st[4]+ ':00';
		row.weeks 			= weekcnt;
		row.endDate			= rowEndDate;
		row.enddate			= rowEndDate;
		row.startDate		= rowStartDate;
		row.startdate		= rowStartDate;
		rowEndDate			= eDateTime.toString("yyyy/MM/dd")+' '+et[3]+ ':'+et[4]+ ':00';
		rowStartDate 		= sDateTime.toString("yyyy/MM/dd")+' '+st[3]+ ':'+st[4]+ ':00';		
		row.enddatetime		= rowEndDate;
		row.startdatetime	= rowStartDate;
		row.spots			= totalspots;
		row.spotsweek 		= parseInt(totalspots/weekcnt);
		if(row.spotsweek < 1){
			row.spotsweek 		= 1;
		}
		row.timestamp 		= new Date();
		row.total 			= parseFloat(totalspots)*parseFloat(row.rate);
		row.weeks 			= weekcnt;

		return false;
	}


    //edit rotators
	 function proposalUpdateDatesInLineRotator(startdate,enddate,row){
		var spotsperweek = row.spotsweek;
		var m 		= "";
		var d 		= "";
		var y 		= "";
		var sd  	= getBroadcastWeekGoPlus(startdate);
		var ed  	= getBroadcastWeekGoPlus(enddate);		
		var cDate;
		var tmpCols = clone(self.getGridsColumns());

        $.each(columns, function(i, value){
            if(value.dynamic === 1){
	            
				m = parseInt(String(tmpCols[i].id).substr(1, 2));
				d = parseInt(String(tmpCols[i].id).substr(3, 2));
				y = parseInt(String(tmpCols[i].id).substr(5, 4));
				
				cDate = getBroadcastWeekGoPlus(y+'-'+m+'-'+d);;
				
				if( cDate.getTime() < sd.getTime() || ed.getTime() < cDate.getTime()){

	                delete row[tmpCols[i].id];
	                delete row[tmpCols[i].id.replace('w', 's')];
					delete row.weekIdMapping[tmpCols[i].id];					
				}
            }
        });
        
        //CALCULATES EFFECTIVE TOTALS 
        row1 = calculateEffectiveTotals(row,startdate,enddate,'dates');  

		//break out the time from the dates
		var sDateArr  			= startdate.split(/[^0-9]/);
		var eDateArr  			= enddate.split(/[^0-9]/);
		var sDateArr2  			= row.startdatetime.split(/[^0-9]/);
		var eDateArr2  			= row.enddatetime.split(/[^0-9]/);
		var edate 				= new Date(parseInt(eDateArr[0]), parseInt(eDateArr[1])-1, parseInt(eDateArr[2]));
		var sdate 				= new Date(parseInt(sDateArr[0]), parseInt(sDateArr[1])-1, parseInt(sDateArr[2]));        
		var startdate2 			= new Date(parseInt(sDateArr2[0]), parseInt(sDateArr2[1])-1, parseInt(sDateArr2[2]),parseInt(sDateArr2[3]),parseInt(sDateArr2[4]));
		var enddate2   			= new Date(parseInt(eDateArr2[0]), parseInt(eDateArr2[1])-1, parseInt(eDateArr2[2]),parseInt(eDateArr2[3]),parseInt(eDateArr2[4]));
		var starttime 			= startdate2.toString("HH:mm:ss");
		var endtime   			= enddate2.toString("HH:mm:ss");
		
		var formatStartDateTime = new Date(parseInt(sDateArr[0]), parseInt(sDateArr[1])-1, parseInt(sDateArr[2]),parseInt(sDateArr2[3]),parseInt(sDateArr2[4]));
		var formatEndDateTime   = new Date(parseInt(eDateArr[0]), parseInt(eDateArr[1])-1, parseInt(eDateArr[2]),parseInt(eDateArr2[3]),parseInt(eDateArr2[4]));
        
        
		row.endDate 		= formatEndDateTime.toString("yyyy-MM-dd HH:mm:ss");
		row.endDateFmt    	= formatEndDateTime.toString("MM/dd/yyyy");
		row.enddate 		= formatEndDateTime.toString("yyyy-MM-dd HH:mm:ss");
		row.enddatetime 	= formatEndDateTime.toString("yyyy-MM-dd HH:mm:ss");
		row.endtime 		= endtime;
		row.startDate 		= formatStartDateTime.toString("yyyy-MM-dd HH:mm:ss");
		row.startDateFmt	= formatStartDateTime.toString("MM/dd/yyyy");
		row.startdate 		= formatStartDateTime.toString("yyyy/MM/dd HH:mm:ss");
		row.startdatetime 	= formatStartDateTime.toString("yyyy-MM-dd HH:mm:ss");
		row.starttime 		= starttime;        
		//grid.invalidate();
        //grid.render();   
        return row;
    }
    
    

	function calculateEffectiveTotals(row,startdate,enddate,option){
		
        var sDateArr  = startdate.split(/[^0-9]/);
        var eDateArr  = enddate.split(/[^0-9]/);  
        var startdate = new Date(parseInt(sDateArr[0]), parseInt(sDateArr[1])-1, parseInt(sDateArr[2])).toString("yyyy/MM/dd");
        var enddate   = new Date(parseInt(eDateArr[0]), parseInt(eDateArr[1])-1, parseInt(eDateArr[2])).toString("yyyy/MM/dd");
		var dayofwk;
		var z,s;
		
    	if(editRotatorItems.days === 1){
			dayofwk 	= effectiveDays();
		}
        else{
    		dayofwk 	= effectiveLineDays(row.day);
    	}
		var uniques 	= dayofwk.unique();
		var sd 			= new Date(parseInt(sDateArr[0]), parseInt(sDateArr[1])-1, parseInt(sDateArr[2])).getTime();
		var ed 			= new Date(parseInt(eDateArr[0]), parseInt(eDateArr[1])-1, parseInt(eDateArr[2])).getTime();
		var spots		= row.spotsweek;
		var rate		= row.rate;
        var weeks		= buildBroadcastWeeks(startdate,enddate);
        var	weekcnt		= weeks.length;
        var allspots 	= 0;
		var updatedSpots;
		var dDate,thisdate,z,ishidden,saved,currentdate,wkSpots,nDay,j;
		var cloneRow;
		for (var i = 0; i < weeks.length; i++){
			dDate		= String(weeks[i].column);
			thisdate 	= dDate.substr(0, 2)+'/'+dDate.substr(2, 2)+'/'+dDate.substr(4, 4);
			z 			= 'w'+dDate;
			s 			= 's'+dDate;
			ishidden 	= Object.find(weeksdata,z);
			if(ishidden === true){
				saved 		= z+'hide';
				if(!(saved in row)){
					row[saved] 	= parseInt(spots);
				}
				row[z] 	= 0;
				weekcnt--;				
			}
			else{
				weekcnt--;
				$.each(uniques,function(n,v){
					currentdate = new Date(parseInt(dDate.substr(4, 4)), parseInt(dDate.substr(0, 2))-1, parseInt(dDate.substr(2, 2)));		
					currentdate.setDate(currentdate.getDate() + parseInt(v));
					ctime = currentdate.getTime();
					
					if(ctime >= sd && ctime <= ed){

						if(option === 'dates'){

							//APPLYING SPOTS BY DAY
							if(row.lineType === 4 && !(s in row)){

								wkSpots 	= 0;
								j 			= 0;
								updatedSpots={};								
								spotsByDayOfWeek = allocatingSpots();
								
								for(var ii in weekDaysObj){
									nDay 	= new Date(parseInt(dDate.substr(4, 4)), parseInt(dDate.substr(0, 2))-1, parseInt(dDate.substr(2, 2)));		
									nDay.add(j).day();
									if(nDay.getTime() >= sd  && nDay.getTime() <= ed){
										updatedSpots[weekDaysObj[ii]] = parseInt(spotsByDayOfWeek[ii]);
										wkSpots += parseInt(spotsByDayOfWeek[ii]);
									}
									else{
										updatedSpots[weekDaysObj[ii]] = 0;
									}
									j++;
								}
								row[z] 		= wkSpots;
								allspots 	= allspots + parseInt(row[z]);
								row[s] 		= updatedSpots;
								weekcnt ++;
							}
							//APPLYING SPOTS BY WEEK
							else{
								if(z in row){// IT IS AN EXISTING ACTIVE WEEK SO THE SPOTS REMAIN UNTOUCHED
									allspots = allspots + parseInt(row[z]);
									if('out'+z in row){ //it was excluded because it did not fall in the flight dates
										row[z] = parseInt(spots);
										allspots 	= allspots + parseInt(spots);
										delete row['out'+z];
										//self.restoreWeeks(row,z);
									}
									
									if(parseInt(row[z]) !== 0){
										weekcnt++;
									}
								}
								else if(!(z in row) && validateSpotsInFligtDates(row,z,startdate,enddate) === true){
									row[z] 		= parseInt(spots);
									allspots 	= allspots + parseInt(spots);
									weekcnt++;
								}
							}							
						}
						else{
							//if(parseInt(row[z]) !== 0){
								row[z] = parseInt(spots);
								allspots = allspots + parseInt(spots);
								weekcnt++;
							//}
						}
						return false;
					}
				});
			}
		}

		row.spots 			= allspots;
		row.spotsweek 		= parseInt(spots);
		row.timestamp 		= new Date();
		row.total 			= parseFloat(allspots)*parseFloat(rate);				
		row.weeks 			= weekcnt;
		return row;
	}


	function proposalUpdateNetwork(newNet, row){

		if(newNet[0]['id'] != row['stationnum']){
			$.each(data,function(i,value){
				if(value['id'] == row['id']){
					
					data[i].callsign 		= newNet[0].callsign;
					data[i].callSign 		= newNet[0].callsign;
					data[i].callsignFormat 	= newNet[0].callsign+'|'+newNet[0].name;	
					data[i].stationname 	= newNet[0].name;
					data[i].stationnum 		= newNet[0].id;
					data[i].title 			= "Various";
					data[i].titleFormat 	= "Various";
					var newRC 				= ratecardType(rateType,row,ratecardData);
					data[i].ratevalue 		= newRC;

					dataView.beginUpdate();
					dataView.setItems(data);
					dataView.sort(comparer, true);
					dataView.endUpdate();
					grid.invalidate();
					grid.render();
					grid.resetActiveCell();
				}
			});
		}
	}


    //zone list
    this.getZoneList = function(){
        var re = {};

        $.each(data, function(i, value) {
            var row = {};
            row.id = value.zoneid;
            row.name = value.zone;
            re[value.zoneid] = row;
        });

        return re;
    }


    //this returns all the weeks in the proposal. When building grids we make all the columns dyamic
    this.getDynamicColumns = function() {
        var re = [];
        var tmpCols = clone(self.getGridsColumns());
        $.each(tmpCols, function(i, value) {
            if(value.dynamic === 1) {
                re.push(value.name);
            }
        });
        return re;
    };


    //this returns all the weeks in the proposal. When building grids we make all the columns dyamic
    this.getDynamicColumnsByObj = function() {
        var re = [];
        var newDate;
        var tmpCols = clone(self.getGridsColumns());        
        $.each(tmpCols, function(i, value) {

            if(value.dynamic === 1) {
	            newDate = String(value.id);
                var row = {};
                row.date = newDate.substr(1, 2)+'/'+newDate.substr(3, 2)+'/'+newDate.substr(5, 5);
                row.name = value.id;
                re.push(row);
            }
        });

        return re;
    };


	this.calculateTotalsFromLine = function(row){
		return proposalGetTotalSpotsFromWeeks(row);
	};


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
            }
            else{
                obj[row] = {rate:""};
                grid.setCellCssStyles("matchrate",obj);
            }

            if(item.__group != true && item.hot == true){
                obj[row] = {ratevalue:"hotrate"};
            }
            if(item.__group != true && item.extra.indexOf('Hot') !== -1){
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

            if(item && item.linetype === 'Rotator') {
                ret = ret || {};
                ret.cssClasses = (ret.cssClasses || '') + ' rotator-bg';
            }

            if(item && item._dirty === true && item.__group === true) {
                ret = ret || {};
                ret.cssClasses = (ret.cssClasses || '') + ' dirty';
            }

            return ret;
        };
    }



	//keeping all the MATH lines updates and such below
    function findDynamicColumn(id){
	    var tmpCols = clone(self.getGridsColumns());
	    var r = {};
	    r.dynamic = false;
		r.position = 0;
        for(var i = 0; i < tmpCols.length; i++) {
            if(String(tmpCols[i].id) === id && parseInt(tmpCols[i].dynamic) === 1){
				r.dynamic = true;
				r.position = i;
				break;
            }
        }
        return r;
    }


	function proposalGetTotalSpotsFromWeeks(row,cellId){
		var sDateArr  = row.startdatetime.split(/[^0-9]/);
        var eDateArr  = row.enddatetime.split(/[^0-9]/);  
		var tmpCols	  = deepClone(self.getGridsColumns());

        var diff 		  = 0;
		var diffstart 	  = 0;
		var lineEndDate	  = new Date(parseInt(eDateArr[0]), parseInt(eDateArr[1])-1, parseInt(eDateArr[2]));
		var lineStartDate = new Date(parseInt(sDateArr[0]), parseInt(sDateArr[1])-1, parseInt(sDateArr[2]));
		var enddate 	  = lineEndDate.toString("yyyy/MM/dd");
		var enddatetime   = new Date(parseInt(eDateArr[0]), parseInt(eDateArr[1])-1, parseInt(eDateArr[2]), parseInt(eDateArr[3]), parseInt(eDateArr[4])).toString("yyyy/MM/dd HH:mm"); 
		var startdate 	  = lineStartDate.toString("yyyy/MM/dd");
		var startdatetime = new Date(parseInt(sDateArr[0]), parseInt(sDateArr[1])-1, parseInt(sDateArr[2]), parseInt(sDateArr[3]), parseInt(sDateArr[4])).toString("yyyy/MM/dd HH:mm");
        var rate 		  = parseFloat(row.rate);
		var thisdate 	  = '';
		var tmpdate 	  = '';
        var total 		  = 0;
        var weeks 		  = 0;
		var weekStartDate,weekEndDate;
		var x;
		
		var dayofwk 	 = effectiveLineDays(row.day);
		var uniques 	 = dayofwk.unique().sort();
		var uniquesR 	 = [uniques[0]]; 

		if(uniques.length > 1){
			uniquesR.push(uniques[uniques.length-1]);	
		}
		
        for(var i = 0; i < tmpCols.length; i++) {

            if(parseInt(tmpCols[i].dynamic) === 1 && tmpCols[i].id in row){
		
	            if(parseInt(row[tmpCols[i].id]) > 0 && $.inArray(tmpCols[i].id,weeksdata) === -1){
					x 				= parseInt(row[tmpCols[i].id]);
					thisdate		= String(tmpCols[i].id);
					tmpdate 		= thisdate.substr(5, 4)+'/'+thisdate.substr(1, 2)+'/'+thisdate.substr(3, 2);
					weekStartDate 	= new Date(parseInt(thisdate.substr(5, 4)), parseInt(thisdate.substr(1, 2))-1, parseInt(thisdate.substr(3, 2)));
					weekStartDate.setDate(weekStartDate.getDate() + parseInt(uniquesR[0]));
					weekEndDate 	= new Date(parseInt(thisdate.substr(5, 4)), parseInt(thisdate.substr(1, 2))-1, parseInt(thisdate.substr(3, 2)));
					weekEndDate.setDate(weekEndDate.getDate() + parseInt(uniquesR[uniquesR.length-1]));
					diffstart 		= (lineStartDate - weekStartDate)/1000/60/60/24;					
					diffend			= (lineEndDate - weekEndDate)/1000/60/60/24;
					
					if(diffstart > 0){
						startdate 	= tmpdate.toString("yyyy/MM/dd");
						startdatetime = startdate+startdatetime.substr(10, 6);
					}
					if(diffend < 0){
						var weekSundayDate 	= new Date(parseInt(thisdate.substr(5, 4)), parseInt(thisdate.substr(1, 2))-1, parseInt(thisdate.substr(3, 2))).next().sunday()				
						enddate 	= weekSundayDate.toString("yyyy/MM/dd");
						enddatetime = enddate+enddatetime.substr(10, 6);
					}
					
					weeks ++;
					total += x;
				}
            }
        }

        var usableweeks = weeks;
		var spweek 		= 1;
		
        if(usableweeks > 0){
			spweek = parseInt(total/usableweeks);
	    }
		
        if(row.linetype === "Fixed"){
            spweek = parseInt(total);
        }

        var stats 			= {};
		stats.enddate 		= enddate;
		stats.enddatetime 	= enddatetime; 
		stats.startdate 	= startdate;
		stats.startdatetime = startdatetime;
		stats.spots 		= parseInt(total);
		stats.weeks 		= parseInt(weeks);
		stats.spotsweek 	= spweek;
		stats.total 		= rate * parseFloat(total);
        return stats;
    };
    
    

	function calculateDynamicWeeks(sD,eD){
		var wkdata 	= buildBroadcastWeeksGoPlus(sD,eD);
		var tmpCols		= [];
					
		for(i = 0; i < wkdata.length; i++){
			w 	= 'w'+wkdata[i].column;
			
			if($.inArray(w,weeksdata) === -1){ //only Active weeks
				newCol 		 	= {};
				newCol.field 	= w;
				newCol.id 	 	= w;
				newCol.dynamic = 1;
				tmpCols.push(newCol);  
			}
		}			
		return tmpCols;
	};

	this.deleteDynamicColumns = function(){
		var col = deepClone(self.getGridsColumns());
		for(var i = col.length - 1; i>=0 ; i--){
			if('dynamic' in col[i]){
				if(col[i].dynamic === 1){
					col.splice(i, 1);
				}
			}
		}
		return col;
	};


    function proposalUpdateWeeksFromCell(row,type){
		var weeks 		= parseInt(row.weeks);
		var spots 		= parseInt(row.spotsweek);
		var newspots 	= 0;
		var last 		= '';
		var startdate 	= row.startdate;
		var sDateArr    = startdate.split(/[^0-9]/);
		var tW			= [];
		var x;
		var sD 			= getBroadcastWeekGoPlus(row.startdatetime);
		var diff 		= weeks*10080 - 10080;
		var eD 			= new Date(sD.getTime() + diff*60000).sunday();
		var weekdata 	= buildBroadcastWeeks(sD,eD);
		var data       	= {};        
		var w,newCol,i;        
		var tmpCols		= deepClone(self.getGridsColumns());
		var newColumn	= false;

        row.enddate 		= eD.toString("yyyy-MM-dd")+' '+row.endtime;
        row.enddatetime 	= eD.toString("yyyy-MM-dd")+' '+row.endtime;
		row.endDate         = eD.toString("yyyy-MM-dd")+' '+row.endtime;

        data.proposalId = proposalid;
        data.lineId		= row.id;
        data.zoneId		= row.zoneId;
        data.startDate	= sD.toString("yyyy-MM-dd");
        data.endDate	= eD.toString("yyyy-MM-dd");
        data.startTime	= row.startdate.split(" ")[1];
        data.endTime	= row.enddatetime.split(" ")[1];
        data.days		= row.day;
        data.rate		= row.rate;        
        
        $.ajax({
            type:'post',
            url: apiUrl+"proposal/editrotator",
            dataType:"json",
            headers:{"Api-Key":apiKey,"User":userid},
            processData: false,
            contentType: 'application/json',
            data: JSON.stringify(data),
            success:function(resp){
				datagridProposal.updateRatingTotals(resp,'inline');
            }
        });
		
        for(i = 0; i < weekdata.length; i++) {
            last 	= weekdata[i];
            w 		= 'w'+weekdata[i].column;
            tW.push(w);
            
            if($.inArray(w,weeksdata) === -1){ //only Active weeks
    	        if(w in row){  	      
	    	        if(parseInt(row[w]) === 0){
		            	row[w] 		= spots;
						newspots 	+=parseInt(spots);
	    	        }
	    	        else{
						newspots 	+= parseInt(row[w]);
	    	        }    	        
    	        }
    	        else{
	    	        newspots 		+= spots;   //adding a new colum to the grid
		            row[w] 		 	= spots; 
		            newCol 		 	= {};
		            newCol.field 	= w;
		            newCol.id 	 	= w;
		            newCol.dynamic = 1;
		            tmpCols.push(newCol); 
		            newColumn = true;
    	        }
            }
            else{
        	    //row[w] = 0;
        	}
        }     

		//UPDATE COLUMNS
        self.setGridColumns(tmpCols);
        
        //Remove spots ouf of weeks range
		for (var key in row) {
			if(key.substr(0, 1) === 'w' && key.substr(1, 1) !== 'e'){
				if(tW.indexOf(key) === -1){
					row[key] = 0;
				}			    
			}
		} 

        row.spots 			= newspots;
        var stats 			= proposalGetTotalSpotsFromWeeks(row);
        row.total 			= stats.total;

		var cols = recalculateProposalFightDates();

		/*var rowIdx 	= dataView.getRowById(row.id);				
		grid.invalidateRow(rowIdx);
		grid.render();*/
		
		if(newColumn){
			datagridProposal.buildEmptyGrid(); 
			displayColumns();
		}
    }



    function proposalBuildStartEndTimes(starttime,endtime,startdate,enddate){
		var sTimeArr  	= starttime.split(/[^0-9]/);
		var eTimeArr 	 	= endtime.split(/[^0-9]/);      
		var sDateArr  	= startdate.split(/[^0-9]/);
		var eDateArr  	= enddate.split(/[^0-9]/);      
		var newstart 		= new Date(2000,0,1,parseInt(sTimeArr[0]),parseInt(sTimeArr[1])).toString("hh:mm tt");
		var newend   		= new Date(2000,0,1,parseInt(eTimeArr[0]),parseInt(eTimeArr[1])).toString("hh:mm tt");
		var newstart24 	= new Date(2000,0,1,parseInt(sTimeArr[0]),parseInt(sTimeArr[1])).toString("HH:mm");
		var newend24   	= new Date(2000,0,1,parseInt(eTimeArr[0]),parseInt(eTimeArr[1])).toString("HH:mm");
		var startdateonly = new Date(parseInt(sDateArr[0]), parseInt(sDateArr[1])-1, parseInt(sDateArr[2])).toString("yyyy/MM/dd");
		var enddateonly   = new Date(parseInt(eDateArr[0]), parseInt(eDateArr[1])-1, parseInt(eDateArr[2])).toString("yyyy/MM/dd");
		var newstartformat= startdateonly + " " + newstart24;
		var newendformat  = enddateonly + " " + newend24;
		var re = {};
		re.starttime = newstart;
		re.endtime = newend;
		re.startdatetime = newstartformat;
		re.enddatetime = newendformat;
		
		return re;
    };



    function proposalUpdateSpotsInWeek(row,cellid,cellPosition){
	    var rowIdx 		= dataView.getRowById(row.id);
		var thisWeek 	= String(cellid);
		var wk 		 	= thisWeek.substr(5, 4)+'-'+thisWeek.substr(1, 2)+'-'+thisWeek.substr(3, 2);
		var weekId	 	= "";

		if(row.weekIdMapping[cellid] !== null && row.weekIdMapping[cellid] !== undefined){
			weekId	 	= row.weekIdMapping[cellid];
		}
		
		var stats 		= proposalGetTotalSpotsFromWeeks(row,cellid);
		row.enddate 	= stats.enddate;
		row.enddatetime = stats.enddatetime;
		row.startdate 	= stats.startdate;
		row.startdatetime = stats.startdatetime;
		row.total 		= stats.total;
		row.spotsweek 	= stats.spotsweek;
		row.spots 		= parseInt(stats.spots);
		row.weeks 		= parseInt(stats.weeks);
		
		var d 			= {};
		d.spots			= row[cellid];
		d.weekId 		= weekId;
		d.week			= wk;
		d.lineId		= row.id;
		
		var node;
		var flds = ['weeks','spotsweek','spots','total'];
		var tmpCols = deepClone(self.getGridsColumns());
		for(var i=0;i<tmpCols.length;i++){
			if(flds.indexOf(tmpCols[i].id) !== -1 ){
				node = grid.getCellNode(rowIdx, i)
				var format 	=  'formatter' in tmpCols[i] ? tmpCols[i].formatter(rowIdx,i,row[tmpCols[i].id],tmpCols[i], row) : row[tmpCols[i].id];
				$(node).html(format);						
			}
		}


		$.ajax({
			type:'post',
			url: apiUrl+"proposal/line/editspots",
			dataType:"json",
			headers:{"Api-Key":apiKey,"User":userid},
			processData: false,
			contentType: 'application/json',
			data: JSON.stringify(d),
			success:function(resp){
                datagridProposal.updateRatingTotals(resp,'inline');
			}
		});
		
		return false;
    };



    function proposalUpdateSpotsInSpotsPerWeek(row,type){

		var l =	 deepClone(calculateEffectiveTotals(row,row.startdatetime,row.enddatetime,''));
        var newspots 	= parseInt(row.spotsweek);
		var rowIdx 		= dataView.getRowById(row.id);

        if(row.spotsweek == ""){
            newspots = 0;
            row.spotsweek = "0";
        }

        $.ajax({
            type:'post',
            url: apiUrl+"proposal/applyspots",
            dataType:"json",
            headers:{"Api-Key":apiKey,"User":userid},
            processData: false,
            contentType: 'application/json',
            data: JSON.stringify({"lineIds":[row.id], "spots":newspots}),
            success:function(resp){
                datagridProposal.updateRatingTotals(resp,'inline');
            }
        });
		
		grid.invalidateRow(rowIdx);	
		grid.render();


    }
    
    
    //Update baes on the LINE not a Rotator
    function proposalUpdateSpotsInSpotsPerWeekLine(row,type){
        var newspots = parseInt(row.spotsweek);
        var weeks = getWeeklySpotsFromNumber(row,newspots);
        var avgperweek = parseInt(newspots/weeks.length);


        for (var i = 0; i < weeks.length; i++){
            var z =  'w'+weeks[i].column;
            row[z] = parseInt(weeks[i].count);
        }

        row.spotsweek = parseInt(avgperweek);
        row.spots = parseInt(newspots);
        row.total = parseFloat(row.spots)*parseFloat(row.rate);
    }


    function getWeeklySpotsFromNumber(row,spots){
        //get the weeks for the selected dates
        var weeks = buildBroadcastWeeks(row.startdate,row.enddate);
        weekcnt = weeks.length;

        //break out the time from the dates
        var sDateArr  = row.startdate.split(/[^0-9]/);
        var eDateArr  = row.enddate.split(/[^0-9]/);
        var startdate = new Date(parseInt(sDateArr[0]), parseInt(sDateArr[1])-1, parseInt(sDateArr[2])).toString("yyyy/MM/dd");
        var enddate   = new Date(parseInt(eDateArr[0]), parseInt(eDateArr[1])-1, parseInt(eDateArr[2])).toString("yyyy/MM/dd");

        //get start and end weeks and subtract 7 to get how many days of the week are left. Mon = 7 Sun = 1
        var startweekdaycount = 7- fixjsdayofweek(new Date(parseInt(sDateArr[0]), parseInt(sDateArr[1])-1, parseInt(sDateArr[2])).getDay());
        var endweekdaycount = 7- fixjsdayofweekEnd(new Date(parseInt(eDateArr[0]), parseInt(eDateArr[1])-1, parseInt(eDateArr[2])).getDay());

        //get the spots to be scheduled over the weeks and divide by the total weeks to get an avg for all the weeks
        var totalspots = parseInt(spots);
        var avgperweek = parseInt(totalspots/weekcnt);

        //multiply the average per week by the week count to the get total used spots then subtract them ftom the total spots this 
        //gives you the remaining spots to publish over the weeks
        var totalusedfromavg = avgperweek * weekcnt;
        var totalspotsleftfromavg = totalspots - totalusedfromavg;

        //take the total days in the start week and end week then divide by 7 and multiply by the average per week
        //this gives us the percentage of spots needed for the weeks. Use math.ceil to round up so there is always at least 1 spot in a week
        var avgfromfullweekstart = Math.ceil(startweekdaycount/7*avgperweek);
        var avgfromfullweekend = Math.ceil(endweekdaycount/7*avgperweek);

        //now get the reamining spots carried over from the start and end weeks
        var weekstartleftover = avgperweek - avgfromfullweekstart;
        var weekendleftover = avgperweek - avgfromfullweekend;

        //now add the remaining spots to the unused spots pool so we no what number is left
        var totalunusedspots = totalspotsleftfromavg + weekstartleftover + weekendleftover;


        //set count in the first and last week
        weeks[0]['count'] = avgfromfullweekstart;
        weeks[weekcnt-1]['count'] = avgfromfullweekend;


        //loop over the middle weeks and set the avg
        for (var i = 1; i < weekcnt-1; i++){
            weeks[i]['count'] = avgperweek;
        }


        //set the loop to reset the weeks for count addition
        var weekloop = 0;

        for (var i = 0; i < totalunusedspots; i++){
            weeks[weekloop]['count'] = weeks[weekloop]['count'] + 1;
            weekloop ++;
            if(weekloop == weekcnt){
                weekloop = 0;
            }    
        }

        return weeks;
    }



    function proposalUpdateTimesOfLine(row,type){}

    function lineNewRates(row,type,rowIndex){ 

        var stats = proposalGetTotalSpotsFromWeeks(row);
        row.total = stats.total;
        
		/*var rowIdx 	= dataView.getRowById(row.id);				
		grid.invalidateRow(rowIdx);
		grid.render();*/

    };


    function proposalUpdateRate(row,type,rowIndex){       

        $.ajax({
            type:'post',
            url: apiUrl+"proposal/applyrate",
            dataType:"json",
            headers:{"Api-Key":apiKey,"User":userid},
            processData: false,
            contentType: 'application/json',
            data: JSON.stringify({"lineIds":[row.id], "rate":row.rate}),
            success:function(resp){
				datagridProposal.updateRatingTotals(resp,'inline');
            }
        });

        var stats = proposalGetTotalSpotsFromWeeks(row);
        row.total = stats.total;
        
		var rowIdx 	= dataView.getRowById(row.id);				
		grid.invalidateRow(rowIdx);
		grid.render();

    };
    
    
    this.updateRatingTotals = function(resp, inline){

		var l,r,demo,item,rowIdx;
		var rL = resp.lines;
		var tmpLineRatings;
		
		datagridProposalManager.updateSelectedProposalRow(resp);
		
		if(resp.lines.length >0){
			
			if('ratings' in resp.lines[0]){
				for(l=0; l < rL.length; l++){
					for(r=0; r < rL[l].ratings.length; r++){
						rowIdx 	= dataView.getRowById(rL[l].line);
						item 	= dataView.getItem(rowIdx);
						if(item !== undefined){
							item.notInSurvey 		= rL[l].notInSurvey;
							demo 	= rL[l].ratings[r].demo;
							item['displayCpp'+demo] = rL[l].ratings[r].displayCpp;
							item['CPM'+demo] 		= rL[l].ratings[r].CPM;
							item['freq'+demo] 		= rL[l].ratings[r].freq;
							item['gRps'+demo] 		= rL[l].ratings[r].gRps;
							item['gImps'+demo] 		= rL[l].ratings[r].gImps;
							item['rating'+demo] 	= rL[l].ratings[r].rating;
							item['reach'+demo] 		= rL[l].ratings[r].reach;
							item['impressions'+demo] 	= rL[l].ratings[r].impressions;
							item['share'+demo] 		= rL[l].ratings[r].share;
							item['customRating'+demo] = rL[l].ratings[r].customRating;
							item['minRepStd'+demo] 	= rL[l].ratings[r].meetsMinReportStandard;
						
							if('rate' in rL[l]){
								item['rate'] 	= rL[l].rate;
								item['total'] 	= item['spots'] * rL[l].rate;
							}
						}
					}
				}
			}
			else if('rating' in resp.lines[0]){

				for(l=0; l < rL.length; l++){
					for(r=0; r < rL[l].rating.length; r++){
						rowIdx 	= dataView.getRowById(rL[l].id);
						item 	= dataView.getItem(rowIdx);
						if(item !== undefined){
							demo 	= rL[l].rating[r].demo;
							item.notInSurvey 		= rL[l].notInSurvey;
							item['displayCpp'+demo] = rL[l].rating[r].displayCpp;
							item['CPM'+demo] 		= rL[l].rating[r].CPM;
							item['freq'+demo] 		= rL[l].rating[r].freq;
							item['gRps'+demo] 		= rL[l].rating[r].gRps;
							item['gImps'+demo] 		= rL[l].rating[r].gImps;
							item['rating'+demo] 	= rL[l].rating[r].rating;
							item['reach'+demo] 		= rL[l].rating[r].reach;
							item['impressions'+demo] 	= rL[l].rating[r].impressions;
							item['share'+demo] 		= rL[l].rating[r].share;
							item['customRating'+demo] 	= rL[l].rating[r].customRating;
							item['minRepStd'+demo] 	= rL[l].rating[r].meetsMinReportStandard;

							if('rate' in rL[l]){
								item['rate'] 	= rL[l].rate;
								item['total'] 	= item['spots'] * rL[l].rate;
							}
						}
					}
				}
			}
	
			if(rL.length < 2){
				if(item !== undefined){
					var node;
					var tmpCols = deepClone(self.getGridsColumns());
					for(var i=0;i<tmpCols.length;i++){
						if('demo' in tmpCols[i] || tmpCols[i].id === 'rate' || tmpCols[i].id === 'total'){
							node = grid.getCellNode(rowIdx, i)
							var format 	= tmpCols[i].formatter(rowIdx,i,item[tmpCols[i].id],tmpCols[i], item);
							$(node).html(format);
						}
					}
				}
			}
			else if(inline === undefined){
				grid.invalidate();	
				grid.render();				
			}


			if(callAfterRatingsUpdate !== null){
				updateSearchResults();
				closeAllDialogs();
				callAfterRatingsUpdate = null;
			}
		}
		if(data.length > 0 && resp){	
			var proposalStartDate = data.sort(startDate)[0].startdatetime;
			var proposalEndDate = data.sort(endDate)[0].enddatetime;
			datagridTotals.populateDataGridGoPlus(data,proposalStartDate,proposalEndDate,resp.totals);
		}
		
    };

    //reset teh datagrid
    this.resetGrid = function(){
        grid.invalidate();
        grid.render();
        grid.resetActiveCell();
    }



    //if there is a row with 0 spots return
    this.spotCount = function(){
        var cnt = 0;
        
        if(data.length == 0){
        	return -1;
        }

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
    };


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
    };


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
	}; 



	
	//mid call to get the processed rows and pass it over
	this.duplicateSelectedLines = function(){
		
	    var rows  = this.selectedRows();
	    var spotId;
	    var zones = $('#duplicate-zone-selector').val();
	    if(rows == 0){
			closeApiCallFlag();		    
	        return;
	    }
	
	    var data  = {"zones":zones,"lineIds":[], "sendNewLines":true};
	    for(var i = 0; i < rows.length; i++) {
	        if(rows[i].__group == true){
	            $.each(rows[i].rows, function(i, rid){
	                data.lineIds.push(rid.id);
	            });        
	        } else {
	            data.lineIds.push(rows[i].id);
	        }
	    }
	
	    $.ajax({
	        type:'post',
	        url: apiUrl+"proposal/duplicateline",
	        dataType:"json",
	        headers:{"Api-Key":apiKey,"User":userid},
	        processData: false,
	        contentType: 'application/json',
	        data: JSON.stringify(data),
	        error:function(){closeApiCallFlag();},
	        success:function(resp){
	            if(resp.lines.length>0){
						datagridProposalManager.updateSelectedProposalRow(resp);
		         	var lineActiveWk;
					var tempDataGridDataSet = [];
					var t;
	                $.each(resp.lines,function(i,l){
	                    t = formatProposalLine(l);
	                    tempDataGridDataSet.push(t);
	                });
					
					var newLines = datagridProposal.dataSet().concat(tempDataGridDataSet);
	

					datagridProposal.buildEmptyGrid();						
					$.when(datagridProposal.populateProposalDataGrid(newLines))
					.then(function(){
						datagridTotals.populateDataGridGoPlus(newLines,resp.dates.startDate,resp.dates.endDate,resp.totals);
						closeApiCallFlag();
						closeAllDialogs();
						if($('#standard').is(':checked')){
							toggleTotalsView(true,'std');
						}
					});	
						
	            }
	            else{
					closeAllDialogs();
					loadDialogWindow('zeroduplicates','ShowSeeker Plus', 465, 180, 1, 0); 
	            }
	        }
	    });
	};


	this.unselectAllRows = function(){
		grid.getSelectionModel().setSelectedRanges([]);
		return false;
	};
	
	
	this.unselectCell = function(){
		grid.resetActiveCell();
		return false;
	};
	
	//set the count
	var cnt = 0;


	this.freezeByColumn = function(col){
	    grid.setOptions({ 'frozenColumn': col });
	};
	
	this.getProposalColumns = function(){
		return self.getGridsColumns();	
	};

	function removePassedColumn(columnid){
		var tmpCols = deepClone(self.getGridsColumns());
		
		$.each(tmpCols, function(i){
			if(tmpCols[i].id === columnid){
				tmpCols.splice(i,1);
				self.setGridColumns(tmpCols);
				grid.setColumns(tmpCols);
				return false;
			}
		});
		
		return false;
	};

	this.toggleColumns1 = function(showColumn, columnid, position, params){
		var tmpCols = self.getGridsColumns();
		if(showColumn){
			tmpCols.splice(position, 0, params);
			grid.setColumns(tmpCols);
		}
		else{
			removePassedColumn(columnid);
		}
	};


	this.toggleColumns = function(proposalCols){
		var tmpCols = clone(self.getGridsColumns());		
		for(i=0;i<tmpCols.length;i++){
			if(tmpCols[i].dynamic === 1){
				proposalCols.push(tmpCols[i]);
			}
		}
		self.setGridColumns(proposalCols);
		grid.setColumns(proposalCols);
		return false;
	};



    function getBasicProposalColumns(){
	    return setProposalColumns();
    };
    
    this.buildDemoColumns = function(c){
		var tmpCols = clone(self.getGridsColumns());
		
		
		if(myEzRating.getRatings('saved') === 1){
			var colId;
			var newColumn;
			var pairCol = [];
			var imp 	= myEzRating.getRatings('impressions');
			var rtg 	= myEzRating.getRatings('ratings');
			var bkGnd = ['dynamicRight_Demo1','dynamicRight_Demo2','dynamicRight_Demo3'];
			
			if(rtg){
				pairCol.push({'initials':'rating','header':'Rtg','formatter':Slick.Formatters.Ratings,'editor':Slick.Editors.Float});
			}
			
			pairCol.push({'initials':'share','header':'Share','formatter':Slick.Formatters.Ratings});
						
			if(imp){
				pairCol.push({'initials':'impressions','header':'Imp','formatter':Slick.Formatters.Impressions,'editor':Slick.Editors.Integer});
			}		

			if(rtg){
				pairCol.push({'initials':'gRps','header':'GRPs','formatter':Slick.Formatters.Ratings});					
			}
			
			if(imp){
				pairCol.push({'initials':'gImps','header':'GIMPs','formatter':Slick.Formatters.Impressions});			
			}
			
			if(rtg){
				pairCol.push({'initials':'displayCpp','header':'CPP','formatter':Slick.Formatters.CPP,'editor':Slick.Editors.Float});
			}
		
			if(imp){
				pairCol.push({'initials':'CPM','header':'CPM','formatter':Slick.Formatters.CPP,'editor':Slick.Editors.Float});
			}
				
			pairCol.push({'initials':'reach','header':'Reach%','formatter':Slick.Formatters.OneDigitPercentage});
			pairCol.push({'initials':'freq','header':'Freq','formatter':Slick.Formatters.Frequency});
			
			var headClass = ['headerRatings','headerRatingsSecond','headerRatingsThird'];
			var colW = 60;
			
			for (var i = 0; i < c.length; i++){
				for(var j=0; j < pairCol.length; j++){
					colW = 60;
					if(pairCol[j].header === 'CPP' || pairCol[j].header === 'GIMPs'){
						colW += 7;
					}
					newColumn 				= {};
					colId 					= pairCol[j].initials+String(c[i].name);
					newColumn.id 			= colId;
					newColumn.sortable	= true;
					newColumn.name			= '<center>'+pairCol[j].header+'<br>'+c[i].name+'</center>';
					newColumn.field		= colId;
					newColumn.width		= colW;
					newColumn.minWidth	= colW;
					newColumn.maxWidth	= colW;
					newColumn.demo			= c[i].name;
					newColumn.column		= pairCol[j].initials;
					newColumn.editor		= pairCol[j].editor;
					newColumn.formatter 	= pairCol[j].formatter;
					newColumn.cssClass 	= bkGnd[i];
					newColumn.headerCssClass = headClass[i];
					newColumn.isNumeric 	= '1';
					
					tmpCols.push(newColumn);
				}
			}
		}
		return tmpCols;
    };
    
    
    this.buildDynamicColumns = function(proposalStartDate,proposalEndDate){
		var cols = [];
		var newColumn,colname,weeks;
		
		if(proposalStartDate && proposalEndDate){
			weeks 		= buildBroadcastWeeks(proposalStartDate,proposalEndDate);
		}
		else if(data.length > 0){
			var sDate 	= datagridProposal.getProposalStartDate();
			var eDate 	= datagridProposal.getProposalEndDate();
			weeks 		= buildBroadcastWeeks(sDate,eDate);
		}
		else{
			weeks	= [];
		}	    
		
		for (var i = 0; i < weeks.length; i++){
			newColumn				= {};
			colname				= "w"+weeks[i].column;
			if(!userSettings.showWeeksOff && weeksdata.indexOf(colname) !== -1){
				continue;
			}
	        newColumn.id 		= colname;
	        newColumn.sortable	= true;
	        newColumn.name		= '<center>'+String(weeks[i].date).substr(0,5)+'<br>20'+String(weeks[i].date).substr(6,2)+'</center>';
	        newColumn.field		= colname;
	        newColumn.width		= 50;
	        newColumn.minWidth	= 50;
	        newColumn.maxWidth	= 50;
	        newColumn.dynamic	= 1;
	        newColumn.cssClass = "dynamicRight";
                        
            if($.inArray(colname, weeksdata) != -1){ //if is not a hidden week
				newColumn.formatter = Slick.Formatters.HiddenFormat;
				newColumn.cssClass	= 'hiddenweek';
				newColumn.headerCssClass = 'hiddenweek';
            }
            else{
				newColumn.formatter = Slick.Formatters.RowCount;
				newColumn.editor	= Slick.Editors.Integer;
            }
			cols.push(newColumn);
       }		
		
		return cols;
    };
    
	 this.refreshGrid = function(){
		dataView.setItems(data);
		dataView.sort(comparer, true);
		dataView.endUpdate();
		grid.invalidate();
		grid.render();
		grid.resetActiveCell();
	 };
	 	     
	  
	this.updateSpotLen = function(id,spotLen){
		var rowIdx = dataView.getRowById(id);
		var item = dataView.getItem(rowIdx);		
		item.spotLength = spotLen;
		grid.invalidateRow(rowIdx);		
		grid.render();	
		return true;	
	};

	this.updateCol = function(id,col,val){
		var rowIdx = dataView.getRowById(id);
		var item = dataView.getItem(rowIdx);		
		item[col] = val;
		grid.invalidateRow(rowIdx);		
		grid.render();	
		return true;	
	};	    

	this.invalidateWeeks = function(){
		var tmpCols = clone(self.getGridsColumns());
		for(var d=0; d<data.length; d++){
			for(w in tmpCols){
				if(tmpCols[w].dynamic === 1){
					if(!(tmpCols[w] in weeksdata)){
						if(validateSpotsInFligtDates(data[d],tmpCols[w].id) === false){
							data[d]['out'+tmpCols[w].id]=data[d][tmpCols[w].id];
							data[d][tmpCols[w].id]=0;
						}
					}
				}
			}
		}	
		return false;	
	};

	this.restoreWeeks = function(row,w){
		var changes = {};
		var col = {};
		var rowIdx = dataView.getRowById(row.id);
		grid.removeCellCssStyles(w+rowIdx);
		grid.invalidateRow(rowIdx);
		grid.render();
	};	
	
	this.getProposalColumns = function(){
		return self.getGridsColumns();
	}
	 

    /*
    END
*/
}