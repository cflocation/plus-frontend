//setup all the interfaces
$(document).ready(function() {

    //create the buttons and buttonsets
    $("#rcbutton, button, #more-marathons,#more-demographics,#btn-networks,#btn-daysofweek,#btn-premiere,#btn-genre,#btn-tvr,#movie-years-listing-mode,#label-bc-cal, #label-sc-cal").button();
    $("#bookend-mode, #avails-daypart-selector, #avails-detect, #avails-type, #searches-manager-archived, #avails-mode, #save-search-reminders,#rtgDmaContainer").buttonset();
    $("#save-search-buttons, #proposal-buttons, #searchResultsBar, #proposal-manager-buttons, #menu-fixed-grouped, #proposal-bar, #line-mode, #line-order").buttonset();
    $("#calendar-mode, #search-mode, #sports-mode, #showtype-mode, #menu-items, #group-modes, #zones-rotators").buttonset();

    //timepickers
    $("#time-start,#time-end").timepicker({
        'timeFormat': 'h:i A'
    });

    //set the default times
    $('#time-start').timepicker('setTime', '06:00 AM');
    $('#time-end').timepicker('setTime', '11:59 PM');

    // tweaking width of side bar elements
	 $('#more-filters label, #daypart-params label, #calendar-mode label, #sports-mode label, #premiere-genre label,  #btn-dayparts').css({'width':'32%'});
	 $('#daypart-params button').css({'width':'32.8%'});
	 $('#showtype-mode label, #search-mode label, #btn-rotators button.sb-rotators').css({'width':'21.5%'});
	 $('#lblyes').find('span').css({'padding-left':'6px','padding-right':'6px'});
	 $('#btn-rotators button.sb-rotators').css({'width':'22%'});	 
	 $('#sb-nets label').css({'width':'39%'});
	 $('#market-selector,#zone-selector,#dma-selector,#ratecard-selector').css({'width':'64.5%'});
	 $('button.sb-reset').css({'width':'29.7%'});
	 $('#more-marathons-label').css({'width':'29%','margin-left':'auto', 'margin-right':'auto'});
	 $('#more-demographics-label').css({'width':'34%','margin-left':'auto', 'margin-right':'auto'});
	 $('#search-mode .ui-button-text').css({'margin-left':'auto', 'margin-right':'auto', 'padding-right':'0px','padding-left':'0px'});
	 $('#btn-add-rotator').css({'width':'21%'});
    


    //load the calendars

    setCalendars();
    schedulerCountWeeksFromDates();
	
	 //demographics
	 demographics();
	 

    //avails panel
    $("#sidebar-avails-result-view").css('display', 'none');
    
    //year listing for movies
	var currentYear = new Date().getFullYear();
	for(var i = currentYear; i>=1930; i--){
		$('#year-options').append($("<option></option>").attr("value", i).text(i));	
	}    
    
});

// Year listing for movie filter
$('#movie-years-listing-mode').change(function(){
	  cb = $(this);
     cb.val(cb.prop('checked'));

    if (cb.val() == 'true'){
        $("#movie-years-listing-mode-label  .ui-button-text").text('Movies by Year');
		$('#decade-options').val('1930 TO 2019');
        $("#year-options option").prop("selected", false);
        $("#moviesbydecade").show();
        $("#moviesbyyear").hide();
        $("#ui-dialog-title-dialog-decades").text('Movies by Decade');
        
    } else {
        $("#movie-years-listing-mode-label  .ui-button-text").text('Movies by Decade');    
        $("#year-options").val("all");
        $("#decade-options option").prop("selected", false);
        $("#moviesbydecade").hide();
        $("#moviesbyyear").show();
        $("#ui-dialog-title-dialog-decades").text('Movies By Year'); 
    }
    
    $("#movie-years-listing-mode").button();
});



$('#ratecard-selector').change(function() {
    loadDialogWindow('loading', 'ShowSeeker Plus', 450, 180, 1);
    var id = $(this).val();
	var zoneid = $('#zone-selector').val();
    var lbl = encodeURIComponent($('#ratecard-selector option:selected').html());
    ratecardGroup = lbl;
    getRateCard(zoneid, id)
});




$('#rcbutton').change(function() {
    if ($(this).is(":checked")) {
        $("#ratecard-block").css('display', 'inline');
        return;
    }
    $("#ratecard-block").css('display', 'none');
});



//$( ".selector" ).datepicker( "show" );



/* Times */
var oldTime = $('#time-start').timepicker('getTime');


$("#time-start").change(function() {
    if (editRotator) {
        $('#sidebar-row-times').css('background-color', '#c8ffc9');
        editRotatorItems['times'] = 1;
    }

    $('#time-start,#time-end').removeClass("baddates");

    if ($('#time-start').timepicker('getTime') > $('#time-end').timepicker('getTime')) {
        //loadDialogWindow('invalidtimes','ShowSeeker Plus', 450, 180, 1);
        $('#time-start').addClass("baddates");
    }
    resetChecker();
    resetDayParts();
    availsTimeSelectors();
});


$("#time-end").change(function() {
    if (editRotator) {
        $('#sidebar-row-times').css('background-color', '#c8ffc9');
        editRotatorItems['times'] = 1;
    }
    $('#time-start,#time-end').removeClass("baddates");

    if ($('#time-start').timepicker('getTime') > $('#time-end').timepicker('getTime')) {
        //loadDialogWindow('invalidtimes','ShowSeeker Plus', 450, 180, 1);
        $('#time-end').addClass("baddates");
    }
    resetChecker();
    resetDayParts();    
	availsTimeSelectors();
});

/* End Times */





$("#search-days-edit").change(function() {
    daysEdit();
    $('#sidebar-row-days').css('background-color', '#c8ffc9');
});





//change avails radio button
function selectAvailsDaypartsType(type) {




    return;
    $('#avails-dayparts-dayparts').css('display', 'none');
    $('#avails-dayparts-dayparts-60').css('display', 'none');

    $('#avails-dayparts-' + type).css('display', 'inline');
}



function demographics(){
    $('#demographics-options').append($("<option selected=selected ></option>").attr("value", "0").text("All Demographics"));
    $.each(demographicsList, function(i, value){
        $('#demographics-options').append($("<option></option>").attr("value", value.id).text(value.name)); 
    });
    $('#demographics-options').addClass('dialog-selector')
}





function checkMarathons() {
    var s = $("#more-marathons").prop('checked');
    var params = solrSearchParamaters();

    var nets = params.networks.length;
    var keywords = params.searchKeywordsArray.length;

    /*if (s == true && nets > 4 && keywords == 0) {
        loadDialogWindow('marathonsettings', 'ShowSeeker Plus', 450, 180, 1, 0);
        dialogNetworkList();
    }*/

}




/*
	===== Calendar Functions =====
*/

//set the calendars
function setCalendars() {
    var dateToday = new Date();
    var eDate = new Date();    
    eDate.setDate(dateToday.getDate()+56);  
    
    $("#date-start").datepicker({
        defaultDate: "+0",
        changeMonth: false,
        numberOfMonths: 3,
        minDate: dateToday,
        beforeShow: function(selectedDate) {
            $('#date-start').css('background-color', '#ffff99');
	        //closeAllDialogs();
        },
        onClose: function(selectedDate) {
            $('#date-start').css('background-color', '#ffffff');
        },
        onSelect: function(selectedDate) {
			$('#date-start,#date-end').removeClass('baddates');
		    var sdate = new Date.parse($('#date-start').val());
		    var edate = new Date.parse($('#date-end').val());
		    
			if(sdate > edate){
				$('#date-start').addClass('baddates');
		        return;
			}			
            $("#date-start-filght").html($("#date-start").val());
            if($("#flight2").is(':checked')){
                $("#date-end").datepicker("show");
	            setTimeout(function() {
	                $("#date-end").datepicker("show");
	            }, 16)
	            return;
            }
			autoSelectDaysOfWeek(sdate,edate);
            schedulerCountWeeksFromDates();
            resetChecker();
            setTimeout(function() {
                $("#date-end").datepicker("show");
            }, 16);

            if(editRotator) {
	            //if selected date is different from line startDate	            
	            if(compareSelectedDates($('#date-start').val(),$('#date-end').val(),datagridProposal.selectedRows())){
	                $('#sidebar-row-dates').css('background-color', '#c8ffc9');
	                $('#sidebar-row-weeks').css('background-color', '#f1f1f1');
	                editRotatorItems.weeks = 0;
	                editRotatorItems.dates = 1;
                }
				else{
	                $('#sidebar-row-dates').css('background-color', '#f1f1f1');
	                editRotatorItems.dates = 0;
				}
            }
        }

    });
    
    var endDateInDays = 56;
	if(parseInt(userid) === 3709){
		endDateInDays = 28;
	}
    
    $("#date-end").datepicker({
        defaultDate: endDateInDays,
        changeMonth: false,
        numberOfMonths: 3,
        minDate: dateToday, 
        beforeShow: function(selectedDate) {
            $('#date-end').css('background-color', '#ffff99');
	        //closeAllDialogs();
        },
        onClose: function(selectedDate) {
            $('#date-end').css('background-color', '#ffffff');
        },
        onSelect: function(selectedDate) {
			$('#date-start,#date-end').removeClass('baddates');
		    var sdate = new Date.parse($('#date-start').val());
		    var edate = new Date.parse($('#date-end').val());
			if(sdate > edate){
				$('#date-end').addClass('baddates');
		        return;
			}
            if($("#flight2").is(':checked')){
	            return;
            }			
			autoSelectDaysOfWeek(sdate,edate);
            schedulerCountWeeksFromDates();
            resetChecker();
            
            if (editRotator) {
	            //if selected date is different from line endDate
	            if(compareSelectedDates($('#date-start').val(),$('#date-end').val(),datagridProposal.selectedRows())){
	                $('#sidebar-row-dates').css('background-color', '#c8ffc9');
	                $('#sidebar-row-weeks').css('background-color', '#f1f1f1');
	                editRotatorItems.dates = 1;
	                editRotatorItems.weeks = 0;
				}
				else{
	                $('#sidebar-row-dates').css('background-color', '#f1f1f1');
	                editRotatorItems.dates = 0;
				}
            }
        }
    });

    //populate the dates in the selectors
    $("#date-start").datepicker("setDate", new Date());
    $("#date-end").datepicker("setDate", +endDateInDays);

    //setup the calendar 
    setCalendarType();

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
		
		if(checkArrays(sDays,[1,7])){
			$("#search-days").val('ss')
		}
		else if(checkArrays(sDays,[2,3,4,5,6])){
			$("#search-days").val('mf')
		}
		else{
			$.each(sDays,function(i,val){
				$("#search-days option[value='" + val + "']").prop("selected", true);
			});
		}
		$("#search-days").trigger("chosen:updated");
		btnUpdateDaysOfWeek(false);		
	}
	else{
		//$("#search-days").val('ms');
		btnUpdateDaysOfWeek();
	}

}

//setup the type of calendar
function setCalendarType() {
    var type 	= $('input:radio[name="calendar-mode-selector"]:checked').val();
	var options = {};
		
    options.firstDay 			= 1;
    options.showTrailingWeek 	= false;
    options.showOtherMonths 	= true;
    options.selectOtherMonths 	= true;
	 buildQuatersList('broadcast');
   
    if (type !== "broadcast") {
		 buildQuatersList('normal');
        options.firstDay 			= 0;
        options.showTrailingWeek 	= true;
        options.showOtherMonths 	= false;
        options.selectOtherMonths 	= false;
    }
    
	$("#date-start,#date-end").datepicker("option",options);
    return false;
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
	try{
		var mixData = { "prevRegion":marketid,
						"currentRegion":id};
		usrIp("Plus Region Select",mixData);
	}catch(e){console.log(e);}
			
	if(parseInt(id) === 210){
		rateCardMode = 0;
	} else if(parseInt(id) === 211){
		rateCardMode = 1;		
	}
	else{
		rateCardMode = ratecardDefaultMode;
	}
    getZonesByMarketId(id);
}



/* NETWORK FUNCTIONS */
/* populate the network list based on the selected zone */
function populateNetworkList(zoneid) {
    if(zoneid === 0){
        return;
    }

    arrayNetworks = [];
	//datagridNetworks.emptyGrid();
    $.ajax({
        type:'get',
        //url: apiUrl+"zone/load/"+zoneid+'/'+marketid,
		url: apiUrl+"zone/load/"+zoneid+'/'+regionsid,
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        success:function(data){
            setTimezoneDayparts(data.dayparts);
            timezone = data.info.timezone.toLowerCase();
            datagridNetworks.populateDataGrid(data.networks);
            var broadcast = parseInt(data.info.broadcast);
            interfaceSplitLines(broadcast);

            if (ratecard == true) {
                getRateCard(zoneid);
            }

            resetChecker();
            isresetting = false;
            $("#dialog-modal-message").dialog("close");
            $("#dialog-window").dialog("close").dialog("destroy");
            check_packages(data.networks);
			if(ezgridsOpen){
				try{
				ezgrids.setNetworksList();
				}catch(e){};
			}

			/*var dmaZoneFilterOpt = datagridZones.getSelectedRows();
			if(dmaZoneFilterOpt.length > 0){
				console.log($('#zone-selector').val(),dmaZoneFilterOpt[0].id);
				if(parseInt(zoneid) !== parseInt(dmaZoneFilterOpt[0].id)){
					datagridZones.unSelectAll();
				}
			}*/

        }
    });
}

//GETTING CORRESPONDING DEMOS FOR THE SELECTED ZONE
function getDemos(){
    if(zoneid == 0){
        return;
    }

    $.ajax({
        type:'get',
        url: apiUrl+"demo/networks/"+zoneid,
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        success:function(data){
            $("#dialog-modal-message").dialog("close");
            demographics = data['networks'];
            filterNetsByDemo();
        }
    });
}

//
function filterNetsByDemo(){

	var selectedDemos 	= $('#demographics-options').val();	

    if(selectedDemos === null || selectedDemos === undefined){
		setTimeout(function(){
			filterNetsByDemo();
		}, 100);
        return;
	}
	
	var selectednetworks = [];

	if($.inArray('0',selectedDemos) != -1){
		datagridNetworks.selectRowsFromArray([0]);
		return;
	}
	//get all the networks by demographic	
	for(i=0; i<selectedDemos.length; i++){
	
		for(var demo1 in demographics){
		
			for(var demoin in demographics[demo1]){
			
				if(selectedDemos[i] == demoin){
					$.each(demographics[demo1][demoin], function(k,value){
						selectednetworks.push(value['networkid']);
					});
				}
			};

		}
	}

	//gets a unique list of networks
	var unique=selectednetworks.filter(function(itm,i,selectednetworks){
	    return i==selectednetworks.indexOf(itm);
	});



	//autoselects the networks
	setTimeout(function(){
		datagridNetworks.resetCells();
		datagridNetworks.selectRowsFromArray(unique);			
	}, 1200)
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



//set the dayparts based on the timezone
function setTimezoneDayparts(userDayParts){
    $('#avails-dayparts').html('');
    $('#select-dayparts').html('');
	$('#avails-dayparts').append($("<option></option>").attr("value", 0).text('Select Dayparts'));		    
	for(var i=0; i<userDayParts.length;i++){
		dP = userDayParts[i];
		$('#select-dayparts').append($("<option></option>").attr("value",dP.starttime+'|'+dP.endtime).text(dP.name));
		$('#avails-dayparts').append($("<option></option>").attr("value",dP.starttime+'|'+dP.endtime).text(dP.name));
	}
	$("#avails-dayparts").val($("#avails-dayparts option:first").val());	
	return false;
};



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
	var selectedValues 	= $('#search-days').val();
	var userDays 		= $('#search-days').val();
	var c 				= 0;    
	var lineDays;
    
	//RESETS SPOTS BY DAY
	spotsByDayOfWeek = {};

    if (selectedValues[0] == 'ms') {
        arrayDays = ["1","2","3","4","5","6","7"];
        userDays = arrayDays;
    }else if(selectedValues[0] == 'ss') {
        arrayDays = ["7","1"];
        userDays = arrayDays;
    }else if(selectedValues[0] == 'mf') {
        arrayDays = ["2","3","4","5","6"];
        userDays = arrayDays;        
    }

   
    if (editRotator) {
	
	    if(datagridProposal.selectedRows().length >= 1){
		    
			lineDays 	= datagridProposal.selectedRows()[0].day;

		    if(lineDays){
		    
			    for(var i=0; i<lineDays.length; i++){
					if(userDays.indexOf(String(lineDays[i])) !== -1){
						c++;
					}    
			    }

			    if((c !== userDays.length || lineDays.length !== userDays.length) && $('#btn-daysofweek').is(':enabled')){
			        $('#sidebar-row-days').css('background-color', '#c8ffc9');
			        editRotatorItems['days'] = 1;
		        }
		        else{
			        $('#sidebar-row-days').css('background-color', '#f1f1f1');
			        editRotatorItems['days'] = 0;		        
		        }
	        }  
        }
    }
    
   if($("#dialog-spots-by-day").is(':visible')){
	   allocateSpotsByDay(userDays);
   }

    //if all the days selected then we swap the label and uncheck the boxes
    if (selectedValues[0] == 'ms') {
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
        $('#btn-daysofweek-label .ui-button-text').text('Sa-Su');
        if(update) {
            resetChecker();
        }
        return;
    }

    if (selectedValues[0] == 'mf') {
        $('#btn-daysofweek-label .ui-button-text').text('M-F');
        if(update) {
            resetChecker();
        }
        return;
    }

    //if no predefined days selected loop over the days and setup the label 
    var tempArr = [];
    var displayList = [];
    for(var i = 0; i < selectedValues.length; i++){
        var v = parseInt(selectedValues[i]);
        tempArr.push(v);
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




function clearRotatorItems() {

    $('#sidebar-row-networks').css('background-color', '#f1f1f1');
    $('#sidebar-row-dates').css('background-color', '#f1f1f1');            
    $('#sidebar-row-rate').css('background-color', '#f1f1f1');
    $('#sidebar-row-spots').css('background-color', '#f1f1f1');
	resetLineOrder();
}

function resetLineOrder(){
	$('#sidebar-row-weeks').css('background-color', '#f1f1f1');
}


/* HANDLE THE HIGHLIGHTS FOR THE SIDE MENU */

$('#schedule-spots').keyup(function(e) {
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

function highlightNetwork(network){
    if (editRotator){
        if (network['id'] != 0 && datagridProposal.selectedRows().length === 1){
            editRotatorItems.network = 1;
            $('#sidebar-row-networks').css('background-color', '#c8ffc9');
        } else{
            editRotatorItems.network = 0;
            $('#sidebar-row-networks').css('background-color', '#f1f1f1');
        }
    }
};

function returnFromEditMode(){
	datagridProposal.unselectAllRows();
	swapSettingsPanel('rotator',false);
	editRotator	= false;
	schedulerCountWeeksFromDates();
	resetmini();
	resetEditRotatorItems();
	$('#schedule-spots').val(1);
	//LINE ORDER
    $('input:radio[name=line-mode-selector][value=no]').click();
    $('#yes,#no').prop('disabled',false).button('refresh');
};

/* PREMIERE FUNCTIONS */

//on close uncjeck if needed
$('#dialog-premiere').bind('dialogclose', function(event) {
    btnUpdatePremiere();
});

//on the change event we call teh function to hanel the button states
$('#search-premiere').change(function() {
    btnUpdatePremiere();
});


/* TVR FUNCTIONS */

//on close uncjeck if needed
$('#dialog-tvr').bind('dialogclose', function(event) {
    btnUpdateTVR();
});

//on the change event we call teh function to hanel the button states
$('#search-tvr').change(function() {
    btnUpdateTVR();
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
    $('#showtype-live,#showtype-new').prop('checked',false).button("refresh");
    
    resetChecker();
}
/* END PREMIERE FUNCTIONS */



//set the data for the TV Ratings selections
function btnUpdateTVR() {
    //get the selected values from the days select box
    var selectedValues = $('#search-tvr').val();

    //if all is selected reset array and return
    if (selectedValues[0] == 0) {
        $('#btn-tvr-label .ui-button-text').text('TVR');
        $('input[name=btnTVR]').prop("checked", false);
		  $('#btn-tvr-label').children().addClass('nopaddingG');
        $('#btn-tvr').button("refresh");
        arrayTVR = [];
        resetChecker();
        return;
    }

    //set the button as true
    $('input[name=btnTVR]').prop("checked", true);
	 $('#btn-tvr-label').children().addClass('nopaddingG');
    $('#btn-tvr').button("refresh");

    //set temp array
    var tempArr = [];
	var v, i;
    //lopp over selections and add them to array
    for (i=0; i < selectedValues.length; i++) {
        v = selectedValues[i];
        tempArr.push(v);
    }

    //set the master atrray as the temp array
    arrayTVR = tempArr;

    //set the label name 
    var label = 'TVR(' + selectedValues.length + ')';
    $('#btn-tvr-label .ui-button-text').text(label);
    resetChecker();
}
/* END TV RATINGS FUNCTIONS */



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

    if(selected){
	    var times 	= selected[0].split('|');

	    $('#time-start').timepicker('setTime', times[0]);
	    $('#time-end').timepicker('setTime', times[1]);
	
	    if (editRotator) {
	        $('#sidebar-row-times').css('background-color', '#c8ffc9');
	        editRotatorItems['times'] = 1;
	    }
	}
};

$('#select-dayparts').change(function(){
	dayPartsState();
});

function dayPartsState(){
	if($('#create-rotator-btn').is(':visible')){
		var selectedValues = $('#select-dayparts').val();		
		//if all is selected reset array and return
		if(selectedValues){
			if (selectedValues.length < 2) {
				$('#btn-dayparts .ui-button-text').text('Dayparts');
				$('#btn-dayparts').removeClass('btnActive');
				$('#btn-dayparts').button("refresh");
			}
			else{
				//set the label name 
				var label = 'DayParts(' + selectedValues.length + ')';
				$('#btn-dayparts').addClass('btnActive');
				$('#btn-dayparts .ui-button-text').text(label);
				$('#btn-dayparts').button("refresh");
			}
		}
	}
	return;		
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
	editRotator = false;
	editRotatorItems = {};	
	arrayDays = ["1","2","3","4","5","6","7"];
	//proposalRattingsOn = 0;

	clearRotatorItems();

    //no zone no reset needed
    if (zoneid == 0) {
        loadMessage('no-zone-selected');
        return;
    }

    loadModalMessage();
    searchType = 'all';

    //ROTATORS
    $('#schedule-weeks').val('9');
    $('#schedule-spots').val('1');
    $('#schedule-rate').val('');
    $('#spotByDayButton').hide();

    //PREMIERE
    $('#search-premiere').val(0);
    $('#btn-premiere-label .ui-button-text').text("Prem/Fin");
    $('input[name=btn-premiere]').prop("checked", false);
    $('#btn-premiere').button("refresh");


	//DAYPARTS
	$('#select-dayparts option').prop('selected',false);
    $('#btn-dayparts .ui-button-text').text('Dayparts');
    $('#btn-dayparts').removeClass('btnActive');
    $('#btn-dayparts').button("refresh");


    //DAYS OF WEEK
    $('#search-days').val("ms");

    resetDaysButton();

	 //SPOTS BY DAY
	$('#btn-daysofweek,#btn-networks,#spotByDayButton').button( "enable" );
	$('#schedule-spots,#zone-selector').prop('disabled', false);
	$('#schedule-spots').removeClass('redBackground');

    $('input:radio[name=calendar-mode-selector][value=broadcast]').click();

	//LINE TYPE
	$('#weekly').prop('checked',true).button("refresh");

    //NETWORK LIST
    if(parseInt(zoneid) !== parseInt($('#zone-selector').val())){
	    zoneid = $('#zone-selector').val();
    };
    populateNetworkList(zoneid);    
    
	$('#sidebar-row-more').show();    
	$('#more-demographics').prop('disabled',false).button('refresh');		

    //SET DEFAULT TIMES
    resetTimes();


    //SET DEFAULT DATES
    $("#date-start").datepicker("setDate", new Date());
	if(parseInt(userid) === 3709){
	    $("#date-end").datepicker("setDate", +28);
	}
	else{
    	$("#date-end").datepicker("setDate", +56);		
	}


	//DATE & TIME
	$('#date-start,#date-end,#time-start,#time-end').removeClass('baddates');

	 schedulerCountWeeksFromDates();

    $('#label-count').html("");

	//LINE ORDER
    $('input:radio[name=line-mode-selector][value=no]').click();
    $('#yes,#no').prop('disabled',false).button('refresh');
    

	//GRID FILTERS
	$('#genre-filter').val('').change();
	
    datagridProposal.resetGrid();
    datagridProposal.doSort("sortingStartDate");

    resetfilters();
	resetEditRotatorItems();
    titlesResetList();
    keywordsResetList();
	displayRtgUserMessage(100);
};


function resetTimes(){
    $('#time-start,#time-end').timepicker('remove');
    $("#time-start,#time-end").timepicker({'timeFormat': 'h:i A'});
    $('#time-start').timepicker('setTime', '06:00 AM');
    $('#time-end').timepicker('setTime', '11:59 PM');
};


function resetDaysButton(){
    $('#btn-daysofweek-label .ui-button-text').text("Select Days");
    $('input[name=btnDaysofweek]').prop("checked", false);
    $('#btn-daysofweek').button("refresh");
};



function resetHiddenColumnsCtrl(){
	proposalHiddenColumns.isGlobal = true;
	proposalHiddenColumns.columns = [];
	$('#customcolumnsbtn').removeClass('highlight');
	$('#proposal-buttons').buttonset('refresh');
};


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
    $('#avails-daypart-30,#avails-daypart-60').prop('checked',false).button("refresh");
    
    //DETECT TITLES
	$('#avails-detect-off').prop('checked',false);    
	$('#avails-detect-on,').prop('checked',true);
    $('#avails-detect-off,#avails-detect-on').button("refresh");
    
    //BROADCAST CALENDAR
	$('#standard').prop('checked',false);    
	$('#broadcast,').prop('checked',true);
    $('#standard,#broadcast').button("refresh");
}


function resetChecker() {
    if (!isresetting) {
        updateSettings();
    }
}


function resetDayParts(){
	$('select#select-dayparts option:selected').prop("selected", false);
}

function resetfilters() {
    //no zone no reset needed
    if(zoneid == 0) {
        loadMessage('no-zone-selected');
        return;
    }

    closeAllDialogs();

    //EZ TYPE
    $('input:radio[name=search-mode-option][value=off]').click();
    $('#searchinput').val('');
    $('#searchinput-actors').val('');
    $('#searchinputkeywords').val('');
    $('#filterGridShows').val('');
    
    datagridTitlesSelected.empty();
    datagridKeywords.empty();
    datagridActorsSelected.empty();

    //PREMIERE
    $('#search-premiere').val(0);
    $('#btn-premiere-label .ui-button-text').text("Prem/Fin");
    $('input[name=btnPremiere]').prop("checked", false);
    $('#btn-premiere').button("refresh");

    //TVR
    $('#search-tvr').val(0);
    $('#btn-tvr-label .ui-button-text').text("TVR");
    $('input[name=btnTVR]').prop("checked", false);
    $('#btn-tvr').button("refresh");

    //SPORTS
    $('#sports-all').removeAttr('checked').button("refresh");
    $('#sports-live').removeAttr('checked').button("refresh");

    //SHOW TYPEcalendar-mode
    $('#showtype-movies').prop("checked", false).button("refresh");

    $('#showtype-live').prop("checked", false);
    $('#showtype-live').button("refresh");

	if(parseInt(userid) === 3709){
	    $('#showtype-new').prop("checked", true);
	    $('#showtype-new').button("refresh");
	}
	else{
	    $('#showtype-new').prop("checked", false);
	    $('#showtype-new').button("refresh");		
	}

    //SET DEFAULT MARATHONS
    $('#more-marathons').prop("checked", false);
    $('#more-marathons').button("refresh");

    //NETWORKS BY DEMO
	 if($('#more-demographics').is(':checked')){
		 datagridNetworks.selectRowsFromArray([0]);	
	 }
	 
	resetNetsByDemo()	
	resetMoviesFilter();

    arrayGenre = [];
    arrayPremiere = [];
    datagridGenres.reset();
    datagridGenresSelected.emptyGrid();
    genresResetList();
    filteringShowsList('');

    $('#download-sort-1').val('startdate');
    $('#download-sort-2').val('starttime');
    $('#download-sort-3').val('network');
    $('#marathon-sorting-text').css('display', 'none');
    datagridSavedSearches.unSelectAll();
}

function resetNetsByDemo(){
	$('#demonote').hide();	
	$('#more-demographics').removeAttr('checked').button("refresh");
	$('#demographics-options').val('0');
}

function resetDiscounts(){
	$('#discount-agency').prop('checked',false).change();
	$('#discount-percent').prop('checked',true).change();
	$('#proposal-discount-package').val(0);
	return false
};

function setHeaderLabel(mode){
	switch(mode){
		case 'createRotator' :
			$('#sidebar-type-header').html('<i class="icon-repeat"></i>&nbsp;Create Rotator<div class="hander" onclick="loadManager();" style="float:right;"><i class="fa fa-book"></i></div>');
		break;
		case 'avails' :
			$('#sidebar-type-header').html('<i class="icon-repeat"></i>&nbsp;Create Avails<div class="hander" onclick="loadManager();" style="float:right;"><i class="fa fa-book"></i></div>');
		break;
		case 'editRotator' :
			$('#sidebar-type-header').html('<i class="icon-repeat"></i>&nbsp;Edit Rotator<div class="hander" onclick="loadManager();" style="float:right;"><i class="fa fa-book"></i></div>');	
		break;
		default :
			$('#sidebar-type-header').html('<i class="icon-search"></i>&nbsp;Search Settings<div class=hander onclick=loadManager("Client"); style="float:right;"><i class="fa fa-book"></i></div>');
	}
}

/* MINI RESET SHOWSEEKER */
function resetmini() {
    $('#avails-dayparts').val(0);
    $('#quarter-selector').val(0);
    $("#sidebar-row-times").css({opacity: 1});
    $("#sidebar-row-dates").css({opacity: 1});
}


function resetMoviesFilter(){
	//DECADES FOR MOVIES
	 $('#decade-options,#year-options').val('');
     $('#showtype-movies').prop("checked", false).button("refresh");
	 $('#moviesbyyear').hide();
	 $('#moviesbydecade').show();
	 $("#dialog-decades" ).dialog("destroy");
}

function resetViewPosition(){
	rangeInitial = 0;
	rangeFinal = 0;	
};

//reset hidden and active weeks holders
function resetWeeks(){
	activeWeeks = [];
	inactiveWeeks = [];
}

function resetStation(){
    editRotatorItems.network = 0;
    $('#sidebar-row-networks').css('background-color', '#f1f1f1');	
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





function updateAvailsQuarterSelector() {
    var sel = $('#quarter-selector').val();

    if (sel.length == 1 && sel == 0) {
        $("#sidebar-row-dates").css({
            opacity: 1
        });
        return;
    }


    var seldates = sel[0].split("|");

    var start = Date.parse(seldates[0]).toString("MM/dd/yyyy");
    var end = Date.parse(seldates[1]).toString("MM/dd/yyyy");


    $("#date-start").datepicker("setDate", start);
    $("#date-end").datepicker("setDate", end);

    if (sel.length > 1) {
        $("#sidebar-row-dates").css({
            opacity: 0.3
        });
    } else {
        $("#sidebar-row-dates").css({
            opacity: 1
        });
    }
}




function updateAvailsDaypartSelector(id) {



    var sel = $('#' + id).val();
 
    
    if (String(sel) == "0"){
        $('#time-start').timepicker('setTime', '06:00:00');
        $('#time-end').timepicker('setTime', '23:59:00');
        return;
    }


    if (sel.length == 1 && sel == 0) {
        $("#sidebar-row-times").css({
            opacity: 1
        });
        return;
    }

    var seltimes = sel[0].split("|");

    if (seltimes[0] != 0){
        $('#time-start').timepicker('setTime', seltimes[0]);
        $('#time-end').timepicker('setTime', seltimes[1]);
    }

    if (sel.length > 1) {
		var tr = getAvailsTimeRange(id);
		
        $('#time-start').timepicker('setTime', tr[0]);
        $('#time-end').timepicker('setTime', tr[1]);
        		
        //$("#sidebar-row-times").css({opacity: 0.3});
    } else {
        $("#sidebar-row-times").css({
            opacity: 1
        });
    }
}



/* Genre */
function updateSelectedGenres() {
    var selectedGenre 	= datagridGenres.getSelectedItems();
	datagridGenres.setGenreRows(selectedGenre);
    var selected 		= datagridGenres.getGenreRows();
	var genreCount 		= selectedGenre.length;
	var genreCnt		= datagridGenresSelected.getRowsLength();
	    	
    if ((genreCount === 0 || '0' in selected) && genreCnt < 1){
        $('#btn-genre-label .ui-button-text').text("Genres");
        $('input[name=btnGenre]').prop("checked", false);
        $('#btn-genre').button("refresh");
        arrayGenre = [];
    }
    else{
		arrayGenre 		= selected;	    
		if(genreCnt > 0){
			genreCount = genreCnt;
			arrayGenre = datagridGenresSelected.getFilteredGenres();
		}

	    $('#btn-genre-label .ui-button-text').text("Genres (" + genreCount + ")");
	    $('input[name=btnGenre]').prop("checked", true);
	    $('#btn-genre').button("refresh");
    }
    return false;
}
/* End Genre */



/* updates the button and network selection array */
function updateSelectedNetworks() {

    //set the labl text and get the selected networks
    var text 		= datagridNetworks.getLabel();
    var selected 	= datagridNetworks.getSelectedItems();
	
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


function availsTimeSelectors(){
	if($('#avails-dayparts-30').closest('.ui-dialog').is(':visible')){
		selectTimeRangeForAvails('avails-dayparts-30');
	}
	if($('#avails-dayparts-60').closest('.ui-dialog').is(':visible')){
		selectTimeRangeForAvails('avails-dayparts-60');            
	}		
}

/* END NETWORK FUNCTIONS */

