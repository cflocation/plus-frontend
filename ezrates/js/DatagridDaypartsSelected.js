//function build datagrid
function DatagridDaypartsSelected(div) {
    var self = this;
	var grid;
	var data = [];
	var dataView;
	var selectedRows = [];
	var dayparts = [];
    var groupby = "starttime";
    var sortcol = "starttime";
    var sortdir = 1;
    var editmode = false;


	//set the columns
	var columns = [
     {
        id: "#",
        name: "",
        width: 25,
        maxWidth: 25,
        behavior: "selectAndMove",
        selectable: false,
        resizable: false,
        cssClass: "cell-reorder dnd"
    },{
        id: "id",
        name: "",
        field: "id",
        width:25, 
        minWidth:25,
        maxWidth:25,
        formatter: Slick.Formatters.Trash,
        sortable: false,
        selectable: false
    },{
        id: "starttime",
        name: "Start Time",
        field: "starttime",
        width:125, 
        minWidth:125,
        formatter: Slick.Formatters.Time,
        sortable: false
    },{
        id: "endtime",
        name: "End Time",
        field: "endtime",
        width:125, 
        minWidth:125,
        formatter: Slick.Formatters.Time,
        sortable: false
    },{
        id: "days",
        name: "Days",
        field: "days",
        minWidth:120,
        formatter: Slick.Formatters.Days,
        sortable: false
    },{
        id: "diff",
        name: "Length",
        field: "diff",
        minWidth:50,
        sortable: false
    },{
        id: "createdat",
        name: "Created",
        field: "createdat",
        width:500,
        minWidth:120,
        sortable: false
    }];





	//set the options for the columns
	var options = {
		enableCellNavigation: true,
		editable: true,
		forceFitColumns: true,
		enableColumnReorder: false,
		multiColumnSort: false,
		rowHeight: 30
	};


	var groupItemMetadataProvider = new Slick.Data.GroupItemMetadataProvider();

	dataView = new Slick.Data.DataView({
		groupItemMetadataProvider: groupItemMetadataProvider,
		inlineFilters: true
	});


	grid = new Slick.Grid("#"+div, dataView, columns, options);


	//register plugins
    grid.registerPlugin(groupItemMetadataProvider);
	grid.setSelectionModel(new Slick.RowSelectionModel());


    var moveRowsPlugin = new Slick.RowMoveManager({
        cancelEditOnDrag: true
    });


      moveRowsPlugin.onBeforeMoveRows.subscribe(function (e, data) {
        for (var i = 0; i < data.rows.length; i++) {
          // no point in moving before or after itself
          if (data.rows[i] == data.insertBefore || data.rows[i] == data.insertBefore - 1) {
            e.stopPropagation();
            return false;
          }
        }
        return true;
      });

      moveRowsPlugin.onMoveRows.subscribe(function (e, args) {
        var extractedRows = [], left, right;
        var rows = args.rows;
        var insertBefore = args.insertBefore;
        left = data.slice(0, insertBefore);
        right = data.slice(insertBefore, data.length);

        rows.sort(function(a,b) { return a-b; });

        for (var i = 0; i < rows.length; i++) {
          extractedRows.push(data[rows[i]]);
        }

        rows.reverse();

        for (var i = 0; i < rows.length; i++) {
          var row = rows[i];
          if (row < insertBefore) {
            left.splice(row, 1);
          } else {
            right.splice(row - insertBefore, 1);
          }
        }

        data = left.concat(extractedRows.concat(right));

        var selectedRows = [];
        for (var i = 0; i < rows.length; i++)
          selectedRows.push(left.length + i);

        grid.resetActiveCell();
        grid.setData(data);
        grid.setSelectedRows(selectedRows);
        grid.render();

        updateMarketDayparts();
      });

      grid.registerPlugin(moveRowsPlugin);

    // wire up model events to drive the grid
    dataView.onRowCountChanged.subscribe(function (e, args) {
        grid.updateRowCount();
        grid.render();
    });

    dataView.onRowsChanged.subscribe(function (e, args) {
        grid.invalidateRows(args.rows);
        grid.render();
    });



    grid.onSort.subscribe(function (e, args) {
        sortdir = args.sortAsc ? 1 : -1;
        sortcol = args.sortCol.field;
        dataView.beginUpdate();
        dataView.sort(comparer, args.sortAsc);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
    });










grid.onDblClick.subscribe(function(e, args) {
return;
    var selectedIndexes = grid.getSelectedRows();

    if(selectedIndexes.length == 0 || selectedIndexes.length > 1){
        panelEditMarketDaypartsButton(1);
        return;
    }
    
    panelEditMarketDaypartsButton(0);
    var i = selectedIndexes.length -1;
    var row = grid.getData()[selectedIndexes];

    var sdate =  Date.parse("01/01/1980 " + row.starttime).toString("hh:mm tt");
    var edate =  Date.parse("01/01/1980 " + row.endtime).toString("hh:mm tt");

    if(row.starttime == '00:00:00'){
    sdate = '12:00 AM';
    }

    $('#daypart-group').val(row.daypartid);
    $('#daypart-name').val(row.name);
    $('#daypart-start-time').val(sdate);
    $('#daypart-end-time').val(edate);


    //handel days
    var d = row.days.split(',');
    var group = false;

    if(d.length == 7){
    $('#daypart-days').val('1,2,3,4,5,6,7');
    group = true;
    }

    if(row.days == '1,7'){
    $('#daypart-days').val('1,7');
    group = true;
    }

    if(row.days == '2,3,4,5,6'){
    $('#daypart-days').val('2,3,4,5,6');
    group = true;
    }

    if(!group){
    $('#daypart-days').val(d);
    }

    panelEditMarketDayparts(1);
});












    grid.onSelectedRowsChanged.subscribe(function(e, args) {
      var cnt = self.selectedRows();

      if(cnt.length > 1 && editmode == true){
        loadDialogWindow('single-row','Select Row',380,150);
        return;
      }else{
        self.parseEditLine();
      }     
    });




    this.parseEditLine = function(row){
        if(editmode){
            var selectedIndexes = grid.getSelectedRows();

            if(selectedIndexes.length == 0 || selectedIndexes.length > 1){
                panelEditMarketDaypartsButton(1);
                return;
            }
            
            var i = selectedIndexes.length -1;
            var row = grid.getData()[selectedIndexes];

            var sdate =  Date.parse("01/01/1980 " + row.starttime).toString("hh:mm tt");
            var edate =  Date.parse("01/01/1980 " + row.endtime).toString("hh:mm tt");

            if(row.starttime == '00:00:00'){
            sdate = '12:00 AM';
            }

            $('#daypart-group').val(row.daypartid);
            $('#daypart-name').val(row.name);
            $('#daypart-start-time').val(sdate);
            $('#daypart-end-time').val(edate);


            //handel days
            var d = row.days.split(',');
            var group = false;

            if(d.length == 7){
            $('#daypart-days').val('1,2,3,4,5,6,7');
            group = true;
            }

            if(row.days == '1,7'){
            $('#daypart-days').val('1,7');
            group = true;
            }

            if(row.days == '2,3,4,5,6'){
            $('#daypart-days').val('2,3,4,5,6');
            group = true;
            }

            if(!group){
            $('#daypart-days').val(d);
            }

            updateMarketDayparts();
        }
    }











    //sorting
    function comparer(a,b) {
        var x = a[sortcol], y = b[sortcol];
        return (x == y ? 0 : (x > y ? 1 : -1));
    }



    this.groupByColumn = function(col) {
        groupby = col;

        dataView.groupBy(col,
            function (g) {
                return "" + g.value + " - <span style='color:#32639a;font-weight: bold;'>(" + g.count + " items)</span>";
            },
            function (a, b) {
                return a.value - b.value;
            }
        );
    }


    this.collapseAllGroups = function() {
        dataView.beginUpdate();
            for (var i = 0; i < dataView.getGroups().length; i++) {
                dataView.collapseGroup(dataView.getGroups()[i].value);
            }
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
    }



    this.populateDatagrid = function(xdata){
        jQuery.each(xdata, function (index, value) {
            //create date format          
            var timeStart = new Date("01/01/2007 " + value.starttime).getHours();
            var timeEnd = new Date("01/01/2007 " + value.endtime).getHours();
            var hourDiff = timeEnd - timeStart; 
            if(value.endtime == "23:59:00"){
                hourDiff = hourDiff + 1;
            }
            value.diff = hourDiff;

            //value.daysort = value.weekdays;
        });

        //console.log(xdata);

    	data = xdata;
        grid.setData(data);  	
        dataView.beginUpdate();
        dataView.setItems(data);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();

    }


    //custom grid scaler render
    this.renderGrid = function(){
        grid.resizeCanvas();
    };

    //rows

    this.deleteSelected = function() {
        var i = grid.getSelectedRows();
        data.splice(i, 1);
        grid.invalidate();
        grid.render();
    }

    this.selectedRows = function() {
        var selectedData = [];
        var selectedIndexes = grid.getSelectedRows();
    
        jQuery.each(selectedIndexes, function (index, value) {
            selectedData.push(grid.getData()[value]);
        });

        return selectedData;
    }

    //return grid snapshot to save into database
    this.dataRows = function() {
        return data;
    }


    this.selectedRowIds = function() {
        var selectedData = [];
        var selectedIndexes = grid.getSelectedRows();
    
        jQuery.each(selectedIndexes, function (index, value) {
            selectedData.push(grid.getData().getItem(value).id);
        });

        return selectedData;
    }



    this.setSelectedRows = function(rows) {
        var selectedData = [];
        jQuery.each(rows, function (index, value) {
            var z = grid.getData().getIdxById(value);
            selectedData.push(grid.getData().getItem(z));
        });
        return selectedData;
    }


    this.dataIds = function() {
        var selectedData = [];

        jQuery.each(data, function (index, value) {
            selectedData.push(value.id);
        });

        return selectedData;
    }


     this.generateUUID = function() {
        var d = new Date().getTime();
        var uuid = 'xxxxxxxxxxxx4xxxyxxxxxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = (d + Math.random()*16)%16 | 0;
            d = Math.floor(d/16);
            return (c=='x' ? r : (r&0x7|0x8)).toString(16);
        });
        return uuid;
    };


    this.addRows = function(row) {
        var key = this.processKey(row.starttime,row.endtime,row.days);
        row.id = key;
        var dupe =  this.findKey(data,key);

        if(dupe == false){
            var uuid = this.generateUUID();
            row.key = uuid;
            data.push(row);
        }else{
            loadDialogWindow('warning-daypart-exists','Warning',290,150);
        }

        grid.resizeCanvas();
        this.populateDatagrid(data);

    }



    this.findKey = function(data,id){
        for(var i = 0; i < data.length; i++) {
            if(data[i].id == id){
                return true;
            }
        }
        return false;
    }


    this.processKey = function(starttime,endtime,days){
        var timeStart = new Date("01/01/2007 " + starttime).toString("HHmm");
        var timeEnd = new Date("01/01/2007 " + endtime).toString("HHmm");
        var days = days.replace(/,/g, "");
        var key = days+timeStart+timeEnd;
        return key;
    }


    //updatedaypart
    this.updateDaypart = function(starttime,endtime,days){
        var selectedIndexes = grid.getSelectedRows();
        var days = days.join(",");
        var key = this.processKey(starttime,endtime,days);
        var dupe =  this.findKey(data,key);

        if(dupe == false){
            data[selectedIndexes].starttime = starttime;
            data[selectedIndexes].endtime = endtime;
            data[selectedIndexes].days = days
            data[selectedIndexes].id = key;
        }else{
            loadDialogWindow('warning-daypart-exists','Warning',290,150);
        }



        grid.invalidate();
        grid.render();
    };

    //set the edit mode
    this.setEditmode = function(mode){
        editmode = mode;
    }

    //empty grid
    this.emptyGrid = function() {
        data = [];
        grid.getSelectionModel().setSelectedRanges([]);
        grid.resetActiveCell();
        grid.setData(data);
        grid.render();
    };


    //unselect rows
    this.unSelectAll = function() {
        grid.getSelectionModel().setSelectedRanges([]);
        grid.invalidate();
        grid.render();
        grid.resetActiveCell();
    }

    this.formatTime = function(d){
    	var re;
    	var min =  Date.parse("01/01/1980 " + d).toString("mm");

    	if(min == "00"){
    		re = Date.parse("01/01/1980 " + d).toString("ht");
    	}else{
    		re = Date.parse("01/01/1980 " + d).toString("h:mmt");
    	}

    	

    	if(re == "0A"){
    		return "12M";
    	}

    	if(re == "11:59P"){
    		return "12A";
    	}
    	return re;
    }
//end main function
}




