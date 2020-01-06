//function build datagrid
function DatagridRatecard() {

	var grid;
	var data = [];
	var dataView;
	var selectedRows = [];
	var dayparts = [];
	var togglerate = true;
	var tagglefixedrate = false; 
	var tagglefixedratepercent = false; 

	var undoRedoBuffer = {
	      commandQueue : [],
	      commandCtr : 0,

	      queueAndExecuteCommand : function(editCommand) {
	      	//remove anything but numbers
	      	for (var i = 0; i < editCommand.clippedRange.length; i++){
	      		var items = editCommand.clippedRange[i];
	      		for (var x = 0; x < items.length; x++){
	      			var val = items[x];
	      			var num = val.replace( /^\D+/g,'');
	      			items[x] = num;	      			
	      		}
	      		
	      	}
	      	
	        this.commandQueue[this.commandCtr] = editCommand;
	        this.commandCtr++;
	        editCommand.execute();


	        
	      },

	      undo : function() {
	        if (this.commandCtr == 0)
	          return;

	        this.commandCtr--;
	        var command = this.commandQueue[this.commandCtr];

	        if (command && Slick.GlobalEditorLock.cancelCurrentEdit()) {
	          command.undo();
	        }
	      },
	      redo : function() {
	        if (this.commandCtr >= this.commandQueue.length)
	          return;
	        var command = this.commandQueue[this.commandCtr];
	        this.commandCtr++;
	        if (command && Slick.GlobalEditorLock.cancelCurrentEdit()) {
	          command.execute();
	        }
	      }
	  }

	  // undo shortcut
	  $(document).keydown(function(e)
	  {
	    if (e.which == 90 && (e.ctrlKey || e.metaKey)) {    // CTRL + (shift) + Z
	      if (e.shiftKey){
	        undoRedoBuffer.redo();
	      } else {
	        undoRedoBuffer.undo();
	      }
	    }
	  });

	var pluginOptions = {
    	clipboardCommandHandler: function(editCommand){ undoRedoBuffer.queueAndExecuteCommand.call(undoRedoBuffer,editCommand); }
  	};


	//set the columns
	var columns = [];


	//set the options for the columns
	var options = {
		enableCellNavigation: true,
		editable: true,
		forceFitColumns: true,
		enableColumnReorder: false,
		multiColumnSort: false,
		rowHeight: 30,
		autoEdit: false
	};


	var groupItemMetadataProvider = new Slick.Data.GroupItemMetadataProvider();

	dataView = new Slick.Data.DataView({
		groupItemMetadataProvider: groupItemMetadataProvider,
		inlineFilters: true
	});


	grid = new Slick.Grid("#ratecard-grid", dataView, columns, options);


	//register plugins
	grid.setSelectionModel(new Slick.CellSelectionModel());
	grid.registerPlugin(new Slick.CellExternalCopyManager(pluginOptions));



    //before you destroy the cell edit reset the edit mode to false
    grid.onCellChange.subscribe(function(e, args) {

    });


	grid.onHeaderClick.subscribe(function(e, args) {
	    var columnID = args.column.id;
	    console.log(columnID);
	});

	//call the json service to get the data
    this.buildGrid = function(data,type){
    	this.buildColumns();
    	dayparts = data.dayparts;
    	data = data.data;
    	this.buildDayparts(dayparts,data,type);
    }


    this.toggleGrid = function(type){
    	columns = [];

    	if(type == "rate"){
    		if(togglerate){
    			togglerate = false;
    		}else{
    			togglerate = true;
    		}
    	}

    	if(type == "fixed"){
    		if(tagglefixedrate){
    			tagglefixedrate = false;
    		}else{
    			tagglefixedrate = true;
    		}
    	}


    	if(type == "fixedpct"){
    		if(tagglefixedratepercent){
    			tagglefixedratepercent = false;
    		}else{
    			tagglefixedratepercent = true;
    		}
    	}
    	

    	this.buildDayparts(dayparts,data,type);
    }



    this.buildDayparts = function(dayparts,data,type){
    	this.buildColumns();
    	for (var i=0;i<dayparts.length;i++){
    		var st = this.formatTime(dayparts[i].starttime);
    		var ed = this.formatTime(dayparts[i].endtime);

    		if(togglerate){
                columns.push(
                    {
                        id: dayparts[i].daypart, 
                        name: dayparts[i].name+"<br>"+st+"-"+ed, 
                        field: dayparts[i].daypart,
                        editor: Slick.Editors.Text,
                        cssClass: 'centertext rate',
                        headerCssClass: 'hiddenweek',
                        coltype:'rate'
                    }
                );
            }

            if(tagglefixedrate){
                columns.push(
                    {
                        id: dayparts[i].daypartfixed, 
                        name:"Fixed<br>Rate", 
                        field: dayparts[i].daypartfixed,
                        editor: Slick.Editors.Text,
                        cssClass: 'centertext ratefixed',
                        headerCssClass: 'hiddenweek',
                        coltype:'rate'
                    }
                );
             }


            if(tagglefixedratepercent){
            	columns.push(
                    {
                        id: dayparts[i].daypartfixed+"_pct", 
                        name:"Fixed<br>%", 
                        field: dayparts[i].daypartfixed+"_pct",
                        editor: Slick.Editors.Text,
                        cssClass: 'centertext ratefixed',
                        headerCssClass: 'hiddenweek',
                        coltype:'rate'
                    }
            	);
           	 }


			
		}
		grid.setColumns(columns);
		this.populateDatagrid(data);
    }



    this.populateDatagrid = function(xdata){
    	data = xdata;
    	dataView.beginUpdate();
        dataView.setItems(data);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
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


    this.getPageText = function(){
    	var zone = data[0].zone;
    	var ratename =  data[0].ratecard;
    	var syscode =  data[0].syscode;
    	var startdate =  Date.parse(data[0].startdate).toString("MM/dd/yyyy");
    	var enddate =  Date.parse(data[0].enddate).toString("MM/dd/yyyy");
    	return [zone,ratename,syscode,startdate,enddate];
    }


    this.buildColumns = function(){
	    columns = [
	{
		id: "logo",
		name: "",
		field: "logo",
		width:35, 
		minWidth:35,
		maxWidth:35,
		selectable: false,
		formatter: Slick.Formatters.NetworkLogoSmall
	},
	{
				id: "network",
				name: "Net",
				field: "network",
				width:100, 
				minWidth:100,
				maxWidth:100,
				selectable: false,
	}];

    }

//end main function
}




