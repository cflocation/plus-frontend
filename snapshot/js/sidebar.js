//setup all the interfaces
$(document).ready(function() {

    //create the buttons and buttonsets
    $("#rcbutton, button, #more-marathons,#more-demographics,#btn-networks,#btn-daysofweek,#btn-premiere,#btn-genre,#movie-years-listing-mode,#label-bc-cal, #label-sc-cal").button();
    $("#bookend-mode, #avails-daypart-selector, #avails-detect, #avails-type, #searches-manager-archived, #avails-mode, #save-search-reminders, #grouping-btn").buttonset();
    $("#save-search-buttons, #proposal-buttons, #search-results-buttons, #proposal-manager-buttons, #menu-fixed-grouped, #proposal-bar, #line-mode").buttonset();
    $("#calendar-mode, #search-mode, #sports-mode, #showtype-mode1, #menu-items, #group-modes, #zones-rotators").buttonset();

    //timepickers
    $("#time-start,#time-end").timepicker({
        'timeFormat': 'h:i A'
    });

    //set the default times
    $('#time-start').timepicker('setTime', '06:00 AM');
    $('#time-end').timepicker('setTime', '11:59 PM');

    // tweaking width of side bar elements
	$('#more-filters label, #daypart-params label, #quarter-params label, #quater-params, #btn-quarters, #calendar-mode label, #sports-mode label, #premiere-genre label,  #btn-dayparts, #btn-premiere-label, #btn-genre-label').css({'width':'32%'});
	$('#daypart-params button').css({'width':'32.8%'});
	$('#showtype-mode1 label, #search-mode label, #btn-rotators button.sb-rotators').css({'width':'21.5%'});
	$('#btn-rotators button.sb-rotators').css({'width':'22%'});	 
	$('#search-mode label').css({'width':'32%'});
	$('#sb-nets label').css({'width':'39%'});
	$('#market-selector,#zone-selector,#dma-selector,#ratecard-selector').css({'width':'64.5%'});
	$('button.sb-reset').css({'width':'29.7%'});
	$('#more-marathons-label').css({'width':'29%','margin-left':'auto', 'margin-right':'auto'});
	$('#more-demographics-label').css({'width':'34%','margin-left':'auto', 'margin-right':'auto'});
	$('#search-mode .ui-button-text').css({'margin-left':'auto', 'margin-right':'auto', 'padding-right':'0px','padding-left':'0px'});
    
    //load the calendars
    setCalendars();
    schedulerCountWeeksFromDates();
    
	 
    //avails panel
    $("#sidebar-avails-result-view").css('display', 'none');
    
    //year listing for movies
	var currentYear = new Date().getFullYear();
	for(var i = currentYear; i>=1930; i--){
		$('#year-options').append($("<option></option>").attr("value", i).text(i));	
	}
});



/* Times */
var oldTime = $('#time-start').timepicker('getTime');


$("#time-start").change(function() {
    $('#time-start,#time-end').removeClass("baddates");

    if ($('#time-start').timepicker('getTime') > $('#time-end').timepicker('getTime')) {
        $('#time-start').addClass("baddates");
    }
    resetChecker();
    //availsTimeSelectors();
});


$("#time-end").change(function() {
    $('#time-start,#time-end').removeClass("baddates");

    if ($('#time-start').timepicker('getTime') > $('#time-end').timepicker('getTime')) {
        //loadDialogWindow('invalidtimes','ShowSeeker Plus', 450, 180, 1);
        $('#time-end').addClass("baddates");
    }
    resetChecker();
	//availsTimeSelectors();
});

/* End Times */


$("#search-days-edit").change(function() {
    daysEdit();
    $('#sidebar-row-days').css('background-color', '#c8ffc9');
});




/*
	===== Calendar Functions =====
*/

//set the calendars
function setCalendars() {
    var dateToday = new Date();
    
    var sDate = new Date();
    sDate.setDate(dateToday.getDate()-90);   

    var minDate = dateToday;
    minDate.setDate(dateToday.getDate()-1096); 
    
    var eDate = new Date();    
    eDate.setDate(dateToday.getDate()+1);  
   
    $("#date-start").datepicker({
        defaultDate: sDate,
        changeMonth: false,
        numberOfMonths: 3,
        minDate: minDate,
        maxDate: new Date(),         
        beforeShow: function(selectedDate) {
            $('#date-start').css('background-color', '#ffff99');
        },
        onClose: function(selectedDate) {
            $('#date-start').css('background-color', '#ffffff');
        },
        onSelect: function(selectedDate) {
			$('#date-start,#date-end').removeClass('baddates');
		    var sdate = new Date($('#date-start').val());
		    var edate = new Date($('#date-end').val());
		    
			if(sdate > edate){
				$('#date-start').addClass('baddates');
		        return;
			}			
        }

    });
    $("#date-end").datepicker({
        defaultDate: "+2",
        changeMonth: false,
        numberOfMonths: 3,
        minDate: minDate, 
        maxDate: new Date(),        
        beforeShow: function(selectedDate) {
            $('#date-end').css('background-color', '#ffff99');
        },
        onClose: function(selectedDate) {
            $('#date-end').css('background-color', '#ffffff');
        },
        onSelect: function(selectedDate) {
			$('#date-start,#date-end').removeClass('baddates');
		    var sdate = new Date($('#date-start').val());
		    var edate = new Date($('#date-end').val());
			if(sdate > edate){
				$('#date-end').addClass('baddates');
		        return;
			}
        }
    });

    //populate the dates in the selectors
    $("#date-start").datepicker("setDate", sDate);
    $("#date-end").datepicker("setDate", eDate);

    //setup the calendar 
    setCalendarType();
    buildQuatersList(minDate);

}


function autoSelectDaysOfWeek(sdate,edate){
	var datediff = (edate.getTime()-sdate.getTime())/86400000;
	if( datediff< 7){
		var sDays = [];
		for (var d = sdate; d <= edate; d.setDate(d.getDate() + 1)) {
		    sDays.push(d.getDay()+1);
		}
		sDays.sort();

		$("#search-days").val('');
		
		if(checkArrays(sDays,[1,7])){$("#search-days").val('ss')}
		else if(checkArrays(sDays,[2,3,4,5,6])){$("#search-days").val('mf')}
		else{
			$.each(sDays,function(i,val){
				$("#search-days option[value='" + val + "']").prop("selected", true);
			});
		}
		$("#search-days").trigger("chosen:updated");
		btnUpdateDaysOfWeek(false);		
	}
	else{
		$("#search-days").val('ms');
		btnUpdateDaysOfWeek();
	}

}

//setup the type of calendar
function setCalendarType() {
    var type = $('input:radio[name=calendar-mode-selector]:checked').val();

    if (type == "broadcast") {
        $("#date-start").datepicker("option", "firstDay", 1);
        $("#date-start").datepicker("option", "showTrailingWeek", false);
        $("#date-start").datepicker("option", "showOtherMonths", true);
        $("#date-start").datepicker("option", "selectOtherMonths", true);

        $("#date-end").datepicker("option", "firstDay", 1);
        $("#date-end").datepicker("option", "showTrailingWeek", false);
        $("#date-end").datepicker("option", "showOtherMonths", true);
        $("#date-end").datepicker("option", "selectOtherMonths", true);

    } else {
        $("#date-start").datepicker("option", "firstDay", 0);
        $("#date-start").datepicker("option", "showTrailingWeek", true);
        $("#date-start").datepicker("option", "showOtherMonths", false);
        $("#date-start").datepicker("option", "selectOtherMonths", false);

        $("#date-end").datepicker("option", "firstDay", 0);
        $("#date-end").datepicker("option", "showTrailingWeek", true);
        $("#date-end").datepicker("option", "showOtherMonths", false);
        $("#date-end").datepicker("option", "selectOtherMonths", false);

    }
}

//auto select all the text in the dtae start and date end box
$("#date-start").click(function() {
    this.select();
    $("#dialog-networks").dialog("close");
    $("#date-start").datepicker("show");
});

$("#date-end").click(function() {
    this.select();
    $("#dialog-networks").dialog("close");
    $("#date-end").datepicker("show");
});

/*
	===== End Calendar Functions =====-edit
*/


//MARKETS
function marketSelected(id) {
    getZonesByMarketId(id);
}



/* NETWORK FUNCTIONS */
/* populate the network list based on the selected zone */
function populateNetworkList(zoneId) {
    if(zoneId == 0){
        return;
    }

    arrayNetworks = [];
    var url = apiUrl + 'zone/load/'+zoneId;


    //make the call
    $.ajax({
        type:'get',
        url: url,
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        success:function(data){
            setTimezoneDayparts(data.info.timezone);
            timezone = data.info.timezone.toLowerCase();
            datagridNetworks.emptyGrid();
            datagridNetworks.populateDataGrid(data.networks);
        }
    });

    return;
}

function setTimezoneDayparts(tz) {
    $('#select-dayparts')[0].options.length = 0;
    $('#select-dayparts').append($("<option></option>").attr("value", '00:00:00|06:00:00').text('Overnight – 12a – 6a'));
    $('#select-dayparts').append($("<option></option>").attr("value", '06:00:00|09:00:00').text('Morning – 6a – 9a'));
    $('#select-dayparts').append($("<option></option>").attr("value", '09:00:00|16:00:00').text('Daytime – 9a – 4p'));
    $('#select-dayparts').append($("<option></option>").attr("value", '16:00:00|19:00:00').text('Early Fringe – 4p – 7p'));
    $('#select-dayparts').append($("<option></option>").attr("value", '19:00:00|22:00:00').text('Prime – 7p – 10p'));
    $('#select-dayparts').append($("<option></option>").attr("value", '22:00:00|23:59:00').text('Late Fringe – 10p – 12a'));
    return;
}



/* DEMOS*/
$( "#demographics-options,#more-demographics" ).change(function() {
        	
    $( "#demographics-options option:selected" ).each(function() {
      if($( this ).val() == 0){
		$('#more-demographics').prop('checked',false);
		$( "#demographics-options" ).val('0');
        $('#more-demographics').button("refresh");
      }
      else{
		$('#more-demographics').prop('checked',true);
		$('#more-demographics').button("refresh");
      }
    });
  }).trigger( "change" );
/*END OF DEMOS*/




function interfaceSplitLines(val){
    if(val == 1){
        $('#menu-item-split-lines').css("display", "none");
        userSettings.autoSplitLines = false;
    }else{
        $('#menu-item-split-lines').css("display", "inline");
        userSettings.autoSplitLines = true;
    }
    
}





/* if the dialog is closed we need to send a update to the button */
$('#dialog-networks').bind('dialogclose', function(event) {
    updateSelectedNetworks();
});






/* DAYS OF THE WEEK FUNCTIONS */
/* if the dialog is closed we need to send a update to the button */
$('#dialog-daysofweek').bind('dialogclose', function(event) {
    btnUpdateDaysOfWeek(false);
});


//on the change event we call teh function to hanel the button states
$('#search-days').change(function() {
    btnUpdateDaysOfWeek(true);
});


//main button funtions to hightlight and sorts
function btnUpdateDaysOfWeek(update) {

    //get the selected values from the days select box
    var selectedValues = $('#search-days').val();


    //if all the days selected then we swap the label and uncheck the boxes
    if (selectedValues[0] == 'ms') {
        arrayDays = [1, 2, 3, 4, 5, 6, 7];
        $('#btn-daysofweek-label .ui-button-text').text('Select Days');
        $('input[name=btnDaysofweek]').prop("checked", false);
        $('#btn-daysofweek').button("refresh");

        if (update) {
            resetChecker();
        }
        return;
    }

    $('input[name=btnDaysofweek]').prop("checked", true);
    $('#btn-daysofweek').button("refresh");

    //if sat sun selected then set the vales and change button 
    if (selectedValues[0] == 'ss') {
        arrayDays = [7, 1];
        $('#btn-daysofweek-label .ui-button-text').text('Sa-Su');
        if (update) {
            resetChecker();
        }
        return;
    }

    //if m-f selected set days and change button
    if (selectedValues[0] == 'mf') {
        arrayDays = [2, 3, 4, 5, 6];
        $('#btn-daysofweek-label .ui-button-text').text('M-F');
        if (update) {
            resetChecker();
        }
        return;
    }

    //if no predefined days selected loop over the days and setup the label 
    var tempArr = [];
    var displayList = [];
    for (var i = 0; i < selectedValues.length; i++) {
        var v = parseInt(selectedValues[i]);
        //var dayname = formatterDays(v);
        tempArr.push(v);
        //displayList.push(dayname);
    }


	var labeltext = schedulerDaysOfWeek(tempArr);


    //create a list out of the displayList array
    //var label = displayList.join(",");
    //set the button label
    $('#btn-daysofweek-label .ui-button-text').text(labeltext);
    //push the temp days to the arrayDays
    arrayDays = tempArr;

    if (update) {
        resetChecker();
    }
}

/* END DAYS OF THE WEEK FUNCTIONS */





/* AVAILS BUTTONS */

$('#avails-mode-daytime').change(function() {
    $('#avails-mode-titletime').removeAttr('checked');
    $('#avails-mode-titletime').button("refresh");
    resetChecker();
    processAvailsViewClick();
});

$('#avails-mode-titletime').change(function() {
    $('#avails-mode-daytime').removeAttr('checked');
    $('#avails-mode-daytime').button("refresh");
    resetChecker();
    processAvailsViewClick();
});


function processAvailsViewClick() {
    var type = $('input[name=avails-mode-selector]:checked').val();

    if (type == 'daytime') {
        //daypart-avails
        //$("#sidebar-avails-group").css('display','none');
        groupByResultsDatagrid('availsDay');
        datagridSearchResults.sortByColumn('availsDaySort');
        return;
    }

    if (type == 'titletime') {
        //$("#sidebar-avails-group").css('display','none');
        groupByResultsDatagrid('availsShow');
        datagridSearchResults.sortByColumn('availsShowSort');
        return;
    }

    //$("#sidebar-avails-group").css('display','inline');
    availsOff();
}


/* END AVAILS BUTTONS */



/* HANDLE THE HIGHLIGHTS FOR THE SIDE MENU */

$('#schedule-spots').keyup(function(e) {
	
	if(! isValidNumberOnKeyUp(e,'schedule-spots')){
		return;
	}
	
    if (editRotator) {
        if (this.value > 0) {
            editRotatorItems['spots'] = 1;
            $('#sidebar-row-spots').css('background-color', '#c8ffc9');
        } else {
            editRotatorItems['spots'] = 0;
            $('#sidebar-row-spots').css('background-color', '#f1f1f1');
        }
    }
});


$('#schedule-rate').keyup(function() {
    if (editRotator) {
        if (this.value > -1) {
            editRotatorItems['rate'] = 1;
            $('#sidebar-row-rate').css('background-color', '#c8ffc9');
        } else {
            editRotatorItems['rate'] = 0;
            $('#sidebar-row-rate').css('background-color', '#f1f1f1');
        }
    }
});

$('#schedule-weeks').keyup(function(e) {

	if(! isValidNumberOnKeyUp(e,'schedule-weeks')){
		return;
	}
	
    if($(this).val() > 75){
	   $(this).val(9);
    }	
	
    if (editRotator) {
        if (this.value > 0) {
            editRotatorItems['weeks'] = 1;
            editRotatorItems['dates'] = 0;
            $('#sidebar-row-weeks').css('background-color', '#c8ffc9');
            $('#sidebar-row-dates').css('background-color', '#f1f1f1');
        } else {
            editRotatorItems['weeks'] = 0;
            $('#sidebar-row-weeks').css('background-color', '#f1f1f1');
        }
    }

	schedulerCountWeeksFromInput();
    
});






/* PREMIERE FUNCTIONS */

//on close uncjeck if needed
$('#dialog-premiere').bind('dialogclose', function(event) {
    btnUpdatePremiere();
});

//on the change event we call teh function to hanel the button states
$('#search-premiere').change(function() {
    btnUpdatePremiere();
});




//set the data for the premiere selections
function btnUpdatePremiere() {
    //get the selected values from the days select box
    var selectedValues = $('#search-premiere').val();

    //if all is selected reset array and return
    if (selectedValues[0] == 0) {
        $('#btn-premiere-label .ui-button-text').text('Prem/Fin');
        $('input[name=btnPremiere]').prop("checked", false);
        $('#btn-premiere').button("refresh");
        arrayPremiere = [];
        resetChecker();
        return;
    }

    //set the button as true
    $('input[name=btnPremiere]').prop("checked", true);
    $('#btn-premiere').button("refresh");

    //set temp array
    var tempArr = [];

    //lopp over selections and add them to array
    for (var i = 0; i < selectedValues.length; i++) {
        var v = selectedValues[i];
        tempArr.push(v);
    }

    //set the master atrray as the temp array
    arrayPremiere = tempArr;

    //set the label name 
    var label = 'Prem/Fin(' + selectedValues.length + ')';
    $('#btn-premiere-label .ui-button-text').text(label);
    resetChecker();
}
/* END PREMIERE FUNCTIONS */





/* SPORTS BUTTONS */

$('#sports-all').change(function() {
    $('#sports-live').removeAttr('checked').button("refresh");
    $('#showtype-movies').removeAttr('checked').button("refresh");    
    resetChecker();
	resetMoviesFilter();
});

$('#sports-live').change(function() {
    $('#sports-all').removeAttr('checked').button("refresh");
    $('#showtype-movies').removeAttr('checked').button("refresh");
    resetChecker();
	resetMoviesFilter();
});

/* END SPORTS BUTTONS */





/* SHOWTYPE SELECTOR */

$('#showtype-movies').change(function() {
    $('#showtype-live').removeAttr('checked').button("refresh");
    $('#showtype-new').removeAttr('checked').button("refresh");
    $('#sports-live,#sports-all').removeAttr('checked').button("refresh");
    resetChecker();
});

$('#showtype-live').change(function() {
    $('#showtype-movies').removeAttr('checked').button("refresh");
    $('#showtype-new').removeAttr('checked').button("refresh");
    resetChecker();
	resetMoviesFilter();
});


$('#showtype-new').change(function() {
    $('#showtype-movies').removeAttr('checked').button("refresh");
    $('#showtype-live').removeAttr('checked').button("refresh");
    resetChecker();
	resetMoviesFilter();
});

/* END SHOWTYPE SELECTOR */



/* DAYPARTS SELECTOR */
function selectDaypart() {

    var selected 	= $('#select-dayparts').val();
    var times 		= selected[0].split('|');

    $('#time-start').timepicker('setTime', times[0]);
    $('#time-end').timepicker('setTime', times[1]);

}

/* END DAYPARTS SELECTOR */




//AVAILS
$('#avails-type').change(function() {
    var type = $('input[name=avails-type-selector]:checked').val();

    if (type == 'result') {
        $("#sidebar-avails-result-view").css('display', 'inline');
        $("#sidebar-avails-group").css('display', 'none');
    } else {
        $("#sidebar-avails-result-view").css('display', 'none');
        $("#sidebar-avails-group").css('display', 'inline');
        //SPORTS
        $('#avails-mode-daytime').removeAttr('checked');
        $('#avails-mode-daytime').button("refresh");
        $('#avails-mode-titletime').removeAttr('checked');
        $('#avails-mode-titletime').button("refresh");
        availsOff();
    }


});



/* RESET SHOWSEEKER */

function reset() {
    isresetting = true;


    //no zone no reset needed
    if (zoneid == 0) {
        loadMessage('no-zone-selected');
        return;
    }

	 setSearchCountLabel(0);    

    loadModalMessage();
    searchType = 'all';
    //closeAllDialogs();

    //ROTATORS
    $('#schedule-weeks').val('');
    $('#schedule-spots').val('');
    $('#schedule-rate').val('');

    //PREMIERE
    $('#search-premiere').val(0);
    $('#btn-premiere-label .ui-button-text').text("Prem/Fin");
    $('input[name=btn-premiere]').prop("checked", false);
    $('#btn-premiere').button("refresh");



    //DAYS OF WEEK
    $('#search-days').val("ms");

    resetDaysButton();
    btnUpdateDaysOfWeek(true);


    $('input:radio[name=calendar-mode-selector][value=broadcast]').click();



    //NETWORK LIST
    zoneid = $('#zone-selector').val();
    populateNetworkList(zoneid);


    //SET DEFAULT TIMES
    resetTimes();

    //$('#time-start option[value="SEL1"]')

    var dateToday = new Date();
    var sDate = dateToday;
    sDate.setDate(dateToday.getDate()-90);   

    //SET DEFAULT DATES
    $("#date-start").datepicker("setDate", sDate);
    $("#date-end").datepicker("setDate", -1);

	//DATE & TIME
	$('#date-start,#date-end,#time-start,#time-end').removeClass('baddates');

	 schedulerCountWeeksFromDates();

    //solrResultDataGrid.emptyGrid();
    $('#label-count').html("");


    $('input:radio[name=line-mode-selector][value=no]').click();


    datagridProposal.resetGrid();
    datagridProposal.doSort("sortingStartDate");

    resetfilters();
    titlesResetList();
    //keywordsResetList();
}



function resetTimes(){
    $('#time-start,#time-end').timepicker('remove');
    $("#time-start,#time-end").timepicker({'timeFormat': 'h:i A'});
    $('#time-start').timepicker('setTime', '06:00 AM');
    $('#time-end').timepicker('setTime', '11:59 PM');
}


function resetDaysButton(){
    $('#btn-daysofweek-label .ui-button-text').text("Select Days");
    $('input[name=btnDaysofweek]').prop("checked", false);
    $('#btn-daysofweek').button("refresh");
}


function resetAvails() {
    closeAllDialogs();

    //SET DEFAULT TIMES
    $('#time-start').timepicker('setTime', '06:00 AM');
    $('#time-end').timepicker('setTime', '11:59 PM');

	//DATE & TIME
	$('#date-start,#date-end,#time-start,#time-end').removeClass('baddates');

    //SET DEFAULT DATES
    $("#date-start").datepicker("setDate", new Date());
    $("#date-end").datepicker("setDate", +56);

    //NETWORK LIST
    zoneid = $('#zone-selector').val();
    populateNetworkList(zoneid);


    $('#quarter-selector,#avails-dayparts,#avails-dayparts-60,#avails-dayparts-30').val(0);

    //DAYS OF WEEK
    $('#search-days').val("ms");
    $('#btn-daysofweek-label .ui-button-text').text("Select Days");
    $('input[name=btn-daysofweek]').prop("checked", false);
    $('#btn-daysofweek').button("refresh");
    
    $('#avails-daypart-dayparts').prop('checked',true).button("refresh");
    $('#avails-daypart-30,#avails-daypart-60').prop('checked',false).button("refresh");;
    
    
}




function resetChecker() {
    if (!isresetting) {
        updateSettings();
    }
}


function resetfilters() {
    //no zone no reset needed
    if (zoneid == 0) {
        loadMessage('no-zone-selected');
        return;
    }

    closeAllDialogs();

    //EZ TYPE
    $('input:radio[name=search-mode-option][value=off]').click();
    $('#searchinput').val('');
    $('#searchinputkeywords').val('');
    datagridTitlesSelected.empty();
    datagridKeywords.empty();

    //PREMIERE
    $('#search-premiere').val(0);
    $('#btn-premiere-label .ui-button-text').text("Prem/Fin");
    $('input[name=btnPremiere]').prop("checked", false);
    $('#btn-premiere').button("refresh");

    //SPORTS
    $('#sports-all').removeAttr('checked').button("refresh");
    $('#sports-live').removeAttr('checked').button("refresh");



    //SHOW TYPEcalendar-mode
    $('#showtype-movies').prop("checked", false).button("refresh");

    $('#showtype-live').prop("checked", false);
    $('#showtype-live').button("refresh");

    $('#showtype-new').prop("checked", false);
    $('#showtype-new').button("refresh");

    //SET DEFAULT MARATHONS
    $('#more-marathons').prop("checked", false);
    $('#more-marathons').button("refresh");


    //NETWORKS BY DEMO
	 if($('#more-demographics').is(':checked')){
		 datagridNetworks.selectRowsFromArray([0]);	
	 }
	 resetDemos()
	
	 resetMoviesFilter();
	 

    arrayGenre = [];
    arrayPremiere = [];

    datagridGenres.reset();

    //resetChecker();
    //isresetting = false;
    $('#download-sort-1').val('startdate');
    $('#download-sort-2').val('starttime');
    $('#download-sort-3').val('network');
    $('#marathon-sorting-text').css('display', 'none');

    //resetEditRotatorItems();
}

function resetDemos(){
	$('#demonote').hide();	
	$('#more-demographics').removeAttr('checked').button("refresh");
	$('#demographics-options').val('0');
}



/* MINI RESET SHOWSEEKER */
function resetmini() {

   // $('input:radio[name=avails-type-selector][value=daypart]').click();

    $('#avails-dayparts').val(0);
    $('#quarter-selector').val(0);

    $("#sidebar-row-times").css({
        opacity: 1
    });
    $("#sidebar-row-dates").css({
        opacity: 1
    });

}


function resetMoviesFilter(){
	//DECADES FOR MOVIES
	 $('#decade-options,#year-options').val('');
     $('#showtype-movies').prop("checked", false).button("refresh");
	 $('#moviesbyyear').hide();
	 $('#moviesbydecade').show();
	 $("#dialog-decades" ).dialog("destroy");
}





//RESET HOVER
$('#reset-all').mouseover(function() {
    $('#sidebar-row-networks, #sidebar-row-calendar, #sidebar-row-dates, #sidebar-row-times, #sidebar-row-days, #sidebar-row-ezsearch').css('background-color', '#ffdada');
    filtersOver();
})

$('#reset-all').mouseout(function() {
    $('#sidebar-row-networks, #sidebar-row-calendar, #sidebar-row-dates, #sidebar-row-times, #sidebar-row-days').css('background-color', '#f1f1f1');
    filtersOut();
})


$('#reset-filters').mouseover(function() {
    filtersOver();
})


$('#reset-filters').mouseout(function() {
    filtersOut();
})

function filtersOver() {
    $('#sidebar-row-sports,#sidebar-row-select,#sidebar-row-filter,#sidebar-row-more, #sidebar-row-ezsearch').css('background-color', '#ffdada');
}


function filtersOut() {
    $('#sidebar-row-sports,#sidebar-row-select,#sidebar-row-filter,#sidebar-row-more').css('background-color', '#f1f1f1');
    $('#sidebar-row-ezsearch').css('background-color', '#e6e5e5');
}
/* END RESET SHOWSEEKER */








/* Genre */
function updateSelectedGenres() {
    var selected = datagridGenres.getSelectedItems();
    datagridGenres.setGenreRows(selected);
    var selected = datagridGenres.getGenreRows();
    var selectedlen = Object.keys(selected).length;


    if (selectedlen == 0 || selected['All Genres'] != undefined) {
        $('#btn-genre-label .ui-button-text').text("Genres");
        $('input[name=btnGenre]').prop("checked", false);
        $('#btn-genre').button("refresh");
        arrayGenre = [];
        //searchTitles();
        return;
    }

    arrayGenre = selected;
    $('#btn-genre-label .ui-button-text').text("Genres (" + selectedlen + ")");
    $('input[name=btnGenre]').prop("checked", true);
    $('#btn-genre').button("refresh");
    //searchTitles();
}
/* End Genre */



/* updates the button and network selection array */
function updateSelectedNetworks() {

    //set the labl text and get the selected networks
    var text = datagridNetworks.getLabel();
    var selected = datagridNetworks.getSelectedItems();
	
	if(selected != undefined){
	    //set the array of network to what is chosen in the datagrid
	    arrayNetworks = selected;
	
	    //decide how the button is to look baed on the selections
	    if (text == 'all') {
	        $('#btn-networks-label .ui-button-text').text("Select Networks");
	        $('input[name=btnNetworks]').prop("checked", false);
	        $('#btn-networks').button("refresh");
	    } else {
	        if (selected.length == 1 && launchgrids == true) {
	            openEZGrids();
	        }
	        $('#btn-networks .ui-button-text').text(text);
	        $('#btn-networks-label .ui-button-text').text(text);
	        $('input[name=btnNetworks]').prop("checked", true);
	        $('#btn-networks').button("refresh");
	    }
	    resetChecker();
	}	    
}



function dateTimeValidator(){
    var sdate = new Date($('#date-start').val());
    var edate = new Date($('#date-end').val());
    
    var error = 0;
    
	if(sdate > edate){
		$('#date-start,#date-end').addClass('baddates');
        loadDialogWindow('invaliddates','ShowSeeker Plus', 450, 180, 1);
		error = 1;
	}
	else{
		$('#date-start,#date-end').removeClass('baddates');
	}
	
    if ($('#time-end').timepicker('getTime') < $('#time-start').timepicker('getTime')) {
        loadDialogWindow('invalidtimes','ShowSeeker Plus', 450, 180, 1);
        $('#time-start,#time-end').addClass("baddates");
        error = 2;
    } else {
        $('#time-start,#time-end').removeClass("baddates");
    }	
	return error;
}

		/*$( "#datagrid-networks" ).on( "keydown", function( event ) {
		console.log( event.type + " .. " +  event.which );
		});*/


/* END NETWORK FUNCTIONS */

