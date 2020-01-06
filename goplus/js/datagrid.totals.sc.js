//start the class file for the datagrid
function DatagridStandardTotals() {

	//setup all the basic varibles for the datagrid	
	var data 			= [];
	var dataView;
	var grid;
	var selectedRows 	= [];
	var sortcol 		= "zone";
	var sortdir 		= 1;
	var totalsDB 		= [];
	var ratingsTotals;
		
	//set the columns
	var columns = buildColumnsForTotals();
	
	//set the options for the columns
	var options = { 
			        editable: false,
			        enableCellNavigation: true,
			        enableColumnReorder: false,
			        forceFitColumns: true,
					rowHeight: 30
					};
  	  	
  	//set the dataview to the grid
	dataView 	= new Slick.Data.DataView({ inlineFilters: true });

	//create the datagrid
	grid 		= new Slick.Grid('#total-fixed-datagrid', dataView, columns, options);



	this.buildColumnsGoPlus = function(xdata,proposalStartDate,proposalEndDate){
		try{
			columns 		= buildColumnsForTotals();
		    var monthArray 	= buildStandardMonths(proposalStartDate,proposalEndDate);
			var obj, monthFormat, monthHeader,col;	    	
			for (var key in monthArray) {
				obj 			= monthArray[key];
				monthFormat 	= 'm'+obj.column;
				monthHeader 	= obj.date;//.toString("MMM-yyyy");
				col 			= {};
				col.id 			= monthFormat;
				col.sortable	= false;
				col.name		= monthHeader.toString("MMM")+'<br>'+monthHeader.toString("yyyy"); 
				col.field		= monthFormat;
				col.width		= 78;
				col.minWidth	= 78;
				col.maxWidth	= 78;
				col.dynamic		= 1;
		        col.cssClass 	= "dynamicRight";
		        col.headerCssClass = 'centerHeader';
				col.formatter	= Slick.Formatters.Money;
				columns.push(col);	
			}
		    grid.setColumns(columns);
		}		
		catch(e){}
	    return true;
	};
	
	
	this.buildColumns = function(xdata){
		try{
			columns 				= buildColumnsForTotals();
			var proposalStartDate 	= xdata.sort(startDate)[0].startdatetime;
			var proposalEndDate 	= xdata.sort(endDate)[0].enddatetime;
			var	monthArray 			= buildStandardMonths(proposalStartDate,proposalEndDate);
			var obj,monthFormat,monthHeader;
			for(var key in monthArray) {
				obj 		= monthArray[key];
				monthFormat = 'm'+obj.column;
				monthHeader = new Date(obj.date).toString("MMM-yyyy");
				columns.push({
				    		dynamic:	1,
				    		field: 		monthFormat,
				    		formatter: 	Slick.Formatters.Money,
				    		id:			monthFormat, 
				    		maxWidth:	78,
				    		minWidth:	78,
				    		name: 		monthHeader, 
				    		sortable: 	false,
				    		width:		78});
			}
			grid.setColumns(columns);
		}
		catch(e){}
		return true;
	}


    //this returns all the weeks in the proposal. When building grids we make all the columns dyamic
    this.getDynamicColumnsByObj = function() {
        var re = [];
		var row;       
        $.each(columns, function(i, value) {

            if(value.dynamic === 1) {
                row = {};
                row.date = value.name;
                row.name = value.id;
                re.push(row);
            }
        });
        return re;
    };


	this.populateDataGridGoPlus = function(xdata,proposalStartDate,proposalEndDate,rtgTotals) {
		var totalCols 	= this.buildColumnsGoPlus(xdata,proposalStartDate,proposalEndDate);
		
		totalsDB  	  	= buildStandardTotals(xdata,proposalStartDate,proposalEndDate,rtgTotals);
		var t, r, percent;				
		$.each(totalsDB, function(i, value) {
			t = parseInt(value.total);
			r = parseInt(value.ratecard);
			percent = (t/r)*100;
		
			if(isNaN(percent) || value.ratecard == 0){
				value.ratepercent = 0;
			}
			else{
				value.ratepercent = Math.round(percent);
			}
		});
		if(rtgTotals){
			this.populateRatings(rtgTotals,totalsDB);
		}
		dataView.beginUpdate();
		dataView.setItems(totalsDB);
		dataView.sort(comparer, true);
		dataView.endUpdate();
		dataView.setFilter(multiZone);
		grid.invalidate();
		grid.render();

	};
	


	this.populateDataGrid = function(xdata) {
		data = xdata;
		this.buildColumns(data);
		totalsDB = buildStandardTotals(data);

        $.each(totalsDB, function(i, value) {
            var t = parseInt(value.total);
            var r = parseInt(value.ratecard);
            var percent = (t/r)*100;
            
            if(isNaN(percent) || value.ratecard == 0){
                value.ratepercent = 0;
            }else{
                value.ratepercent = Math.round(percent);
            }            
         });

        
		dataView.beginUpdate();
		dataView.setItems(totalsDB);
		dataView.sort(comparer, true);
		dataView.endUpdate();
		grid.invalidate();
		grid.render();
	}

	this.populateDataGridFromData = function(flag) {
        if(data.length == 0){
            return;
        }
        
        
		this.buildColumns(data);
		totalsDB = buildStandardTotals(data);		

        $.each(totalsDB, function(i, value) {
            if(value.id == '' && value.total == 0 && flag !='overlay'){
				loadDialogWindow('norates','ShowSeeker Plus', 450, 180, 1);		
				return;
            }
         });

		
		$.each(totalsDB, function(i, value) {
			var t = parseInt(value.total);
			var r = parseInt(value.ratecard);
			var percent = (t/r)*100;
			
			if(isNaN(percent) || value.ratecard == 0){
				value.ratepercent = 0;
			}else{
				value.ratepercent = Math.round(percent);
			}
		});

		dataView.beginUpdate();
		dataView.setItems(totalsDB);
		dataView.sort(comparer, true);
		dataView.endUpdate();
		grid.invalidate();
		grid.render();
	}


	this.populateData = function(x){
		data = x;
		dataView.beginUpdate();
	  	dataView.setItems(data);
        dataView.sort(comparer, true);
		dataView.endUpdate();
		grid.invalidate();
		grid.render();			
	};

	this.populateRatings = function(rtgTotals,totalsDB){
		
		var d,i,c,z,demo,zoneDemos;
		var zoneRatings;
		var byZone 			= rtgTotals.ratingsZonesTotals;
		var grandTotal 		= rtgTotals.ratingsTotals;
		var zoneTotals 		= [];		
		var greatGrandTotal = {};
		
		var cols 	= ['avgRating','gRps','gImps','CPP','CPM','reach','freq','demoPop','demoSampleSize'];		
		var r 		= {};
					
		for(z=0; z<byZone.length; z++){
			zoneDemos 			= byZone[z].zoneTotals;
			zoneRatings 		= {};
			zoneRatings.zoneId 	= byZone[z].zoneId;
			
			for(d = 0; d < zoneDemos.length; d++){
				
				demo = zoneDemos[d].demo;
				
				for(c=0; c<cols.length; c++){
					zoneRatings[cols[c]+demo] = zoneDemos[d][cols[c]];
				}
				zoneRatings['demoPop'+demo] = rtgTotals.demoPop[demo];
				zoneRatings['demoSampleSize'+demo] = rtgTotals.demoSampleSize[demo];
			}
			zoneTotals.push(zoneRatings);
		}

		var samplesize = 100;

		for(i=0; i<grandTotal.length; i++){
			demo = grandTotal[i].demo;			
			for(n=0; n<cols.length; n++){
				greatGrandTotal[cols[n]+demo] = grandTotal[i][cols[n]];
			}
			greatGrandTotal['demoPop'+demo] = rtgTotals.demoPop[demo];
			greatGrandTotal['demoSampleSize'+demo] = rtgTotals.demoSampleSize[demo];

			if(rtgTotals.demoSampleSize[demo] < 50){
				samplesize = rtgTotals.demoSampleSize[demo];
			}
		}
		
		displayRtgUserMessage(samplesize);		

		for(i=0; i < totalsDB.length; i++){
			for(z=0;z<zoneTotals.length;z++){
				if(parseInt(totalsDB[i].zoneid) === parseInt(zoneTotals[z].zoneId)){
					objConcat(totalsDB[i], zoneTotals[z]);
				}
			}
		}

		for(i=0; i < totalsDB.length;i++){
			if(parseInt(totalsDB[i].zoneid) === 0){
				objConcat(totalsDB[i], greatGrandTotal);
			}
		}
		
	};


	//custom grid scaler render
	this.renderGrid = function() {
		grid.resizeCanvas();
	};


    //sort
    function comparer(a, b) {
        var x = a[sortcol],
            y = b[sortcol];
        return(x === y ? 0 : (x > y ? 1 : -1));
    }


	//empty grid
	this.emptyGrid = function() {
        data = [];
		var tempcols = buildColumnsForTotals();
		grid.setColumns(tempcols);
		dataView.beginUpdate();
		dataView.getItems().length = 0;
		dataView.endUpdate();
		grid.invalidate();
		grid.render();
	};
	

	this.dataSet = function() {
		return totalsDB;
	}
	
	this.getRatings = function(rtgTotals){
		return ratingsTotals;
	}

	this.getData = function() {
		return dataView.getItems();
	};

	
	this.getCols = function(){
		return grid.getColumns();
	}
	
	this.set = function(item,value){
		this[item] = value;
	}	
	
	this.setRatings = function(rtgTotals){
		ratingsTotals = rtgTotals;
	}	
	
	this.updateColumns = function(c){
		grid.setColumns(c);
	}	



	function buildColumnsForTotals(){
		var cols = [];
		
		cols.push({
	    		id: "zone", 
	    		name: "Zone <br> <span id='surveyName'></span>",
	    		sortable: false,
	    		field: "zone", 
	    		width:150, 
	    		minWidth:150, 
	    		maxWidth:150,
	            formatter: Slick.Formatters.Dolast});
	            
		cols.push({
	    		id: "spots", 
	    		name: "Spots", 
	    		field: "spots", 
	    		width:60, 
	    		minWidth:60, 
	    		maxWidth:60,
				cssClass: "dynamicRight",
	    		headerCssClass:'centerHeader'});
	    		
		cols.push({
	    		id: "total", 
	    		name: "Gross Totals", 
	    		field: "total", 
	    		width:80, 
	    		minWidth:80, 
	    		maxWidth:80,
				cssClass: "dynamicRight",
	    		headerCssClass:'centerHeader',
	    		formatter: Slick.Formatters.Money});
	    	
		if(discountpackage !== 0){
			cols.push({
		    		id: "pkgdisc", 
		    		name: "Pkg Disc", 
		    		field: "pkgdisc", 
		    		width:70, 
		    		minWidth:70, 
		    		maxWidth:70,
					cssClass: "dynamicRight",
		    		headerCssClass:'centerHeader',
		    		formatter: Slick.Formatters.Money});
		}

		if(discountagency !== 0){			
			cols.push({
		    		id: "agcydisc", 
		    		name: "Agcy Disc", 
		    		field: "agcydisc", 
		    		width:70, 
		    		minWidth:70, 
		    		maxWidth:70,
					cssClass: "dynamicRight",
		    		headerCssClass:'centerHeader',
		    		formatter: Slick.Formatters.Money});
		}
		cols.push({
	    		id: "nettotal", 
	    		name: "Net Total", 
	    		field: "nettotal", 
	    		width:78, 
	    		minWidth:78, 
	    		maxWidth:78,
				cssClass: "dynamicRight",
	    		headerCssClass:'centerHeader',
	    		formatter: Slick.Formatters.Money});
	    		
		cols.push({
	    		id: "ratecard", 
	    		name: "Rate Card", 
	    		field: "ratecard", 
	    		width:80, 
	    		minWidth:80, 
	    		maxWidth:80,
				cssClass: "dynamicRight",
	    		headerCssClass:'centerHeader',
	            formatter: Slick.Formatters.Money});
	            
	
		if(myEzRating){
			var ratingsCols = formatDemos();
			var demoCols  	= buildDemoColumns(ratingsCols);
			for(var ii=0; ii < demoCols.length; ii++){
				cols.push(demoCols[ii]);
			}
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
	 
	 
	 function multiZone(item){	
		return true;
	 }


}