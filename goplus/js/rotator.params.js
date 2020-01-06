function setParamsinSidePanel(row){	
    //SET DATES	
	var dateformatted = new Date.parse(row['startdate']);
    $("#date-start").datepicker("setDate", dateformatted);
	dateformatted = new Date.parse(row['enddate']);
    $("#date-end").datepicker("setDate", dateformatted);

    //SET TIMES
    $('#time-start').timepicker('setTime', row['starttime']);
    $('#time-end').timepicker('setTime', row['endtime']);

    //SET WEEKS, SPOTS, RATE
    $('#schedule-weeks').val(row['weeks']);
    $('#schedule-spots').val(row['spotsweek']);
    $('#schedule-rate').val(row['rate']);
    
    //SET DAYS
	$('#search-days').val('');

    if(row['dayFormat'] == "M-Su"){
	    $('#search-days').val('ms');
    }else if(row['dayFormat'] == "M-F"){
	    $('#search-days').val('mf');
    }else if(row['dayFormat'][0] == "Su" && row['dayFormat'][1] == "Sa"){
	    $('#search-days').val('ss');
    }
    else{
	    $.each(row['day'],function(i,v){
		    $('#search-days option[value=' + v + ']').attr('selected', true);
	    });
    }
    //LINE TYPE
   resetLineOrder(); 

	switch(row.lineType){
		case 2:
		case 3:
			$('#daily,#yes').prop('disabled',true).button("refresh");
			$('#weekly').prop('checked',true).button("refresh");
			$('#spotByDayButton').hide();
			break;
		/*case 3:
			$('#weekly,#daily').prop('disabled',true).button("refresh");
			$('#yes').prop('checked',true).button("refresh");
			$('#spotByDayButton').hide();
			break;*/
		case 4:
			$('#weekly,#yes').prop('disabled',true).button("refresh");
			$('#daily').prop('checked',true).button("refresh");
			$('#spotByDayButton').show();
			var a =[];
			for(var wk in row){
				if(wk.substr(0, 1) === 'w' && !isNaN(wk.substr(1,1))){
					a.push(row[wk]);
				}
			}
			var spts = majorFequency(a);
			if(!isNaN(spts)){
				$('#schedule-spots').val(spts);
			}
			break;
		case 5:
			$('#weekly,#daily').prop('disabled',true).button("refresh");
			$('#yes').prop('checked',true).button("refresh");
			$('#spotByDayButton').hide();
			//$('#sidebar-row-weeks').css('background-color', '#fe7272');			
			var a =[];
			for(var wk in row){
				if(wk.substr(0, 1) === 'w' && !isNaN(wk.substr(1,1))){
					a.push(row[wk]);
				}
			}
			var spts = parseInt(row.spots);
			if(!isNaN(spts)){
				$('#schedule-spots').val(spts);
			}
			else{
				$('#schedule-spots').val(1);				
			}
			break;			
	}
 
    //SET NETWORK
    datagridNetworks.selectRowsFromArray([row['stationnum']]);
	btnUpdateDaysOfWeek();
}