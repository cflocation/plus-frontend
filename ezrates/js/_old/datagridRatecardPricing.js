//function build datagrid
function datagridRatecardPricing() {

	var grid;
	var data = [];
	var datatemp;
	var dataView;
	var selectedRows = [];
	var dayparts = [];
	var togglerate = true;
	var tagglefixedrate = true; 
	var tagglefixedratepercent = false; 
	var undos = [];


	//set the columns
	var columns = [	{
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
		name: "",
		field: "network",
		width:100, 
		minWidth:100,
		maxWidth:100,
		selectable: false,
	}];


	//set the options for the columns
	var options = {
		enableCellNavigation: true,
		editable: true,
		forceFitColumns: false,
		enableColumnReorder: false,
		multiColumnSort: false,
		rowHeight: 30,
		autoEdit: false
	};



	  var undoRedoBuffer = {
	      commandQueue : [],
	      commandCtr : 0,

	      queueAndExecuteCommand : function(editCommand) {
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
	    clipboardCommandHandler: function(editCommand){ undoRedoBuffer.queueAndExecuteCommand.call(undoRedoBuffer,editCommand); },
	    includeHeaderWhenCopying : false
	  };



	var groupItemMetadataProvider = new Slick.Data.GroupItemMetadataProvider();

	dataView = new Slick.Data.DataView({
		groupItemMetadataProvider: groupItemMetadataProvider,
		inlineFilters: true
	});


	grid = new Slick.Grid("#grid-ratecard-pricing", dataView, columns, options);


	//register plugins
	grid.setSelectionModel(new Slick.CellSelectionModel());


    // set keyboard focus on the grid
    grid.getCanvasNode().focus();
    grid.registerPlugin(new Slick.CellExternalCopyManager(pluginOptions));


    //before you destroy the cell edit reset the edit mode to false
    grid.onCellChange.subscribe(function(e, args) {
    	var x = args.cell;
    	var i = args.row;
    	var col = columns[x].field;
    	var items = datagridRatecardPricing.colItem(col);
    	var type = $('input[name=rate-mode-toggle]:checked').val();
    	
    	
		//if this is a daypart lets go ahead and abd set the rate. We have other functions to set percents 
		if(items.daypartname == 'daypart'){
			var rate = args.item[items.daypart];

			//if the type is pct update the percent of the fixed rate based on the new rate
			if(type == 'pct' && data[i][items.daypartfixed] > 0){
				var frate = data[i][items.daypartfixed];
				var fixed2pct = (frate/rate * 100) - 100;
				data[i][items.daypartfixedpct] = parseInt(fixed2pct);
			}

			//if the tyoe is fixed we want to set the fixed rate based on the main rate and percentage
			if(type == 'fixed' && data[i][items.daypartfixed] > 0){
				var pct = data[i][items.daypartfixedpct];
				var boost = (rate * pct) / 100;
				var pct2fixed = parseInt(rate) + parseInt(boost);
				data[i][items.daypartfixed] = parseInt(pct2fixed);
			}
		}
	


		//if this is a daypart lets go ahead and abd set the rate. We have other functions to set percents 
		if(items.daypartname == 'daypartfixed'){
			var rate = args.item[items.daypartfixed];
			var frate = data[i][items.daypart];
			var fixed2pct = (rate/frate * 100) - 100;
			data[i][items.daypartfixedpct] = parseInt(fixed2pct);
			data[i][items.daypartfixed] = rate;
		}
		



		//update based on pct
		if(items.daypartname == 'daypartfixedpct'){
			var rate = args.item[items.daypartfixedpct];
			var pct = rate;
			var rateval = data[i][items.daypart];
			var boost = (rateval * pct) / 100;
			var pct2fixed = parseInt(rateval) + parseInt(boost);
			data[i][items.daypartfixed] = parseInt(pct2fixed);
			data[i][items.daypartfixedpct] = parseInt(rate);
		}


		dataView.beginUpdate();
        dataView.setItems(data);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
   
    });





	grid.onHeaderClick.subscribe(function(e, args) {
	    var columnID = args.column.id;
	});

	//call the json service to get the data
    this.buildGrid = function(data,type){
    	var z = [];
    	this.buildColumns();
    	dayparts = data.dayparts;
    	data = data.data;

    	for (var i = 0; i <= data.length; i++){
    		if (typeof data[i] !== "undefined") {
            	data[i].id = i;
        	}
    	}
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
    			$('#sidebar-fixed').css('display', 'none');
    		}else{
    			tagglefixedrate = true;
    			$('#sidebar-fixed').css('display', 'inline');
    		}
    	}


    	if(type == "fixedpct"){
    		if(tagglefixedratepercent){
    			tagglefixedratepercent = false;
    			$('#sidebar-fixed-pct').css('display', 'none');
    		}else{
    			tagglefixedratepercent = true;
    			$('#sidebar-fixed-pct').css('display', 'inline');
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
                        coltype:'rate',
                        formatter: Slick.Formatters.Amt,
                        minWidth:70,
						maxWidth:70
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
                        coltype:'rate',
                        formatter: Slick.Formatters.Amt,
                       	minWidth:70,
						maxWidth:70
                    }
                );
             }


            if(tagglefixedratepercent){
            	columns.push(
                    {
                        id: dayparts[i].daypartfixedpct, 
                        name:"Fixed<br>%", 
                        field: dayparts[i].daypartfixedpct,
                        editor: Slick.Editors.Text,
                        cssClass: 'centertext ratefixedpct',
                        headerCssClass: 'hiddenweek',
                        coltype:'rate',
                        formatter: Slick.Formatters.Amtpct,
                        minWidth:70,
						maxWidth:70
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


    this.getData = function(){
    	return data;
    }

    this.getSelectedColumns = function(){
    	var coldata = grid.getSelectionModel().getSelectedRanges();
    	return coldata;
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
	},{
		id: "network",
		name: "",
		field: "network",
		width:100, 
		minWidth:100,
		maxWidth:100,
		selectable: false,
	}];

    }


    //custom grid scaler render
    this.renderGrid = function(){
        grid.resizeCanvas();
    };


    //custom grid scaler render
    this.undo = function(){
        undoRedoBuffer.undo()
    };


    //SET THE MAIN RATE
    this.setRate = function(rate,type){

    	var coldata = grid.getSelectionModel().getSelectedRanges();
    	var fromRow = coldata[0].fromRow;
    	var toRow = coldata[0].toRow;

    	var fromCell = coldata[0].fromCell;
    	var toCell = coldata[0].toCell;

		for (var i = fromRow; i <= toRow; i++){
			for (var x = fromCell; x <= toCell; x++){

				//what is the name of the column
				var col = columns[x].field;

				//call the items function to get a list of everything we are going to need
				var items = this.colItem(col);


				//if this is a daypart lets go ahead and abd set the rate. We have other functions to set percents 
				if(items.daypartname == 'daypart'){
					data[i][col] = rate;
					

					//if the type is pct update the percent of the fixed rate based on the new rate
					if(type == 'pct' && data[i][items.daypartfixed] > 0){
						var frate = data[i][items.daypartfixed];
						var fixed2pct = (frate/rate * 100) - 100;
						data[i][items.daypartfixedpct] = parseInt(fixed2pct);
					}



					//if the tyoe is fixed we want to set the fixed rate based on the main rate and percentage
					if(type == 'fixed' && data[i][items.daypartfixed] > 0){
						var pct = data[i][items.daypartfixedpct];
						var boost = (rate * pct) / 100;
						var pct2fixed = parseInt(rate) + parseInt(boost);
						data[i][items.daypartfixed] = parseInt(pct2fixed);
					}
				}
			}
		}
		dataView.beginUpdate();
        dataView.setItems(data);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
    }
    //END THE SET RATE FUNCTION




    //RATE FIXED
    this.setRateFixed = function(rate, type){

    	var coldata = grid.getSelectionModel().getSelectedRanges();

    	var fromRow = coldata[0].fromRow;
    	var toRow = coldata[0].toRow;

    	var fromCell = coldata[0].fromCell;
    	var toCell = coldata[0].toCell;

		for (var i = fromRow; i <= toRow; i++){
			for (var x = fromCell; x <= toCell; x++){

				//what is the name of the column
				var col = columns[x].field;

				//call the items function to get a list of everything we are going to need
				var items = this.colItem(col);

				//if this is a daypart lets go ahead and abd set the rate. We have other functions to set percents 
				if(items.daypartname == 'daypartfixed' && type == 'fixed'){
					var frate = data[i][items.daypart];
					var fixed2pct = (rate/frate * 100) - 100;
					data[i][items.daypartfixedpct] = parseInt(fixed2pct);
					data[i][items.daypartfixed] = rate;
				}

				//update based on pct
				if(items.daypartname == 'daypartfixedpct' && type == 'pct'){
					var pct = rate;
					var rateval = data[i][items.daypart];
					var boost = (rateval * pct) / 100;
					var pct2fixed = parseInt(rateval) + parseInt(boost);
					data[i][items.daypartfixed] = parseInt(pct2fixed);
					data[i][items.daypartfixedpct] = parseInt(rate);
				}


			}
		}

		dataView.beginUpdate();
        dataView.setItems(data);
        dataView.endUpdate();
        grid.invalidate();
        grid.render();
    }





    this.colItem = function(col){
    	//make the daypart into an array 
		var ratearr = col.split("_");

		//set the daypart id
		var daypartid = ratearr[1];

		//set name
		var daypartname =  ratearr[0];

		//get the normal rate column for this line
		var daypart = 'daypart_'+daypartid;
		var daypartfixed = 'daypartfixed_'+daypartid;
		var daypartfixedpct = 'daypartfixedpct_'+daypartid;

		var re = new Object();
		re.daypartid = daypartid;
		re.daypartname = daypartname;
		re.daypart = daypart;
		re.daypartfixed = daypartfixed;
		re.daypartfixedpct = daypartfixedpct;

		return re;
    }



//end main function
}










