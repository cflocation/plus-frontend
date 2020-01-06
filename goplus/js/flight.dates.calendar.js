function flightUpdate() { 

		if($('#showWeeksOff').is(':checked')){
			userSettings.showWeeksOff = true;
		}
		else{
			userSettings.showWeeksOff = false;
		}
		
		var wksState 	= flightWeeksStatus();	
		var colsState 	= updateWeekColumnsState(wksState);     
	    var fulldataset = datagridProposal.getDataSet();
	    
		datagridProposal.buildEmptyGrid();    
		var d 			= {};	
	    d.proposalId 	= proposalid;
	    d.activeWeeks 	= colsState.activeWeeks;
	    d.inactiveWeeks = colsState.inactiveWeeks;
	    
	    
	    $.ajax({
	        type:'post',
	        url: apiUrl+"proposal/calendar",
	        dataType:"json",
	        headers:{"Api-Key":apiKey,"User":userid},
	        processData: false,
	        contentType: 'application/json',
	        data: JSON.stringify(d),
	        success:function(resp){
				activeWeeks 	= [];
				inactiveWeeks 	= [];
				flightLabel();
				datagridProposalManager.updateSelectedProposalRow(resp);
				datagridProposal.updateRatingTotals(resp);				
				populateRatingsTotals(resp);
				closeAllDialogs();
	        }
	    });
	
		if(fulldataset.length === 0){
	        datagridTotals.emptyGrid();
	        resetTotals();
	        datagridProposal.populateDataGridNew([]);
	        $("#dialog-flight").dialog("destroy");
	        displayColumns();
	        return;
	    }
	
		datagridProposal.populateProposalDataGrid(fulldataset);
		datagridTotals.populateDataGrid(fulldataset);
	    needSaving = true;
	
	    $("#dialog-flight").dialog("destroy");
	    
		var bad = datagridProposal.spotCount();		
		
		if(bad !== 0) {
			loadDialogWindow('zero-weeks','ShowSeeker Plus', 450, 180, 1);
			$('#flight-calendar-btn').addClass('highlightCalendar');
		}

}



function flightWeeksStatus(){
	var pos 		= -1;
	var deactivate 	= [];
	var reactivate 	= [];
	var r 			= {};
	$('.proposalWeek:checkbox').each(function(i,w){
		if( $(this).is(':checked') && weeksdata.indexOf( $(this).prop('id') ) !== -1 ){
			reactivate.push($(this).prop('id'));
		}
		else if(! $(this).is(':checked') && weeksdata.indexOf( $(this).prop('id') ) === -1 ){
			deactivate.push($(this).prop('id'));
		}		
	});
	
	r.deactivate = deactivate;
	r.reactivate = reactivate;
	
	return r;
}



function flightLabel() {
    if (weeksdata.length > 0) {
        var title = '(' + weeksdata.length + ')';
		$('#flight-calendar-btn').addClass('highlight');
    } else {
        var title = '';
		$('#flight-calendar-btn').removeClass('highlight');
    }
    $('#calcount').html(title);
    return false;
}





function updateWeekColumnsState(proposalWeeksCalendar) {

	var i;
	var id;
	var tmpCols	  	= deepClone(datagridProposal.getGridsColumns());
	var allCols 	= [];
	var active 		= proposalWeeksCalendar.reactivate;
	var hide		= proposalWeeksCalendar.deactivate;
	var pos			= -1;
	var r			= {};
	r.activeWeeks	= [];
	r.inactiveWeeks	= [];
	
    if(active.length > 0) {
	    
        for(i=0; i<active.length; i++) {
			id 	= active[i];
	        pos = weeksdata.indexOf(id);
			weeksdata.splice(pos, 1);
			r.activeWeeks.push(id.substr(5, 4) +'-'+ id.substr(1, 2) +'-'+ id.substr(3, 2));
            
            for(var x=0; x<tmpCols.length; x++){
                allCols.push(tmpCols[x].id);
			}
			
            if(allCols.indexOf(id) === -1){
				var newCol 		= {};
				newCol.field 	= id;
				newCol.id 	 	= id;
				newCol.dynamic 	= 1;
				tmpCols.push(newCol);  
				datagridProposal.setGridColumns(tmpCols);
			}
			
			datagridProposal.proposalUpdateLinesFromWeeks(id, 'add');		
		}
    } 
    
    if(hide.length > 0){
	    for(var w=0; w<hide.length;w++){
		    id = hide[w];
	        weeksdata.push(id);
			r.inactiveWeeks.push(id.substr(5, 4) +'-'+ id.substr(1, 2) +'-'+ id.substr(3, 2));
			datagridProposal.proposalUpdateLinesFromWeeks(id, 'remove');
		}
    }
	return r;
}




function populateFlightCalendar() {
    //getDynamicColumns
    $("#flightdates").html('');

    //get the start date of the proposal
    var proposalweeks = datagridProposal.getStartDate();

    //get the monday for the loaded proposal
    var mon          = 'w' + proposalweeks[0].column;
    var startWeekNum = getStartProposalWeek(mon);
    var weeks1       = buildBroadcastCal();
	var pslWks 		 = datagridProposal.getStartDate();	
    var cnt 		 = 0;
    var proposalweek = 1;

    for (var i = startWeekNum; i < weeks1.length; i++){
	        		
		for(var j = 0; j<pslWks.length; j++){
			if(weeks1[i].dateShort === pslWks[j].date){
					
		        //flightdates
		        var x = '<div class="flightwrapper">';
		        x	+=	'<input checked="checked"  id="' + weeks1[i].column + '" type="checkbox" class="proposalWeek">';
		        x 	+= 	'<span class="flightdatesweek">' + proposalweek + '</span> <span class="flightdatebroadcastweeks"> ' + weeks1[i].week + '</span>';
		        x	+= 	'<label for="' + weeks1[i].column + '" class=hander> ' + weeks1[i].date + '</label></div>';
		       
		        $("#flightdates").append(x);        
		        //flightcheck
		        if (weeks1[i].week == '52') {
		            $("#flightdates").append('<div class="clearboth"><br><hr><br></div>');
		        }
		        cnt++;
		        proposalweek++;
				break;
			}
		}

        if (cnt === pslWks.length){
            break;
        }
    }

    for (var i = 0; i < weeksdata.length; i++) {
        $('#' + weeksdata[i]).prop("checked", false);
    }
};