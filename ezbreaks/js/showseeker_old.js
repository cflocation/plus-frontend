//SHOWSEEKER RATECARDS
var datagridDayparts = new DatagridDayparts('datagrid-dayparts', false);
var datagridDaypartSelected = new DatagridDaypartsSelected('datagrid-dayparts-selected');
var datagridRatecards = new DatagridRatecards();
var datagridPricing = new DatagridPricing();
var datagridPricingBroadcast = new DatagridPricingBroadcast();
var datagridHotProgramming = new DatagridHotProgramming();



//var datagridRatecardPricing = new datagridRatecardPricing();
//var datagridDayparts = new datagridDayparts();
//var datagridShowtitles = new datagridShowtitles();
//var datagridHotProgramming = new datagridHotProgramming();

var ratecardid = 0;
var zoneid = 0;
var marketsid = 0;
var showtitlesloaded = false;
var firstLoad = true;
var processAction;
var hotEditMode = 'add';
var daypartEditMode = false;
var daypartEditId = 0;
var tab = 0;
var issaving = false;
var published = 0;
var isbroadcast = 0;
var selectedmarketid = 0;
var desiredItem;
var broadcastTitleRow;
var copyHotProgrammingType;


$(document).ready(function() {
    menuSelect('tab-1', 'menu-1');
    getDayparts();
    getMarkets();
});


$.ajaxSetup({
    cache: false
});




//SESSION
var sessionTimer = setInterval(function() {
    getSession()
}, 600000);

function getSession() {
    $.getJSON("services/session.php", function(data) {
        if (data != null) {
            var session = checkSession(data);
        }
    });
}


// MASTER GET DAYPARTS
function getDayparts() {
    $.getJSON("services/dayparts.php?eventtype=listdayparts", function(data) {
        $('#ratecard-add-daypart').find('option').remove().end();

        datagridDayparts.populateDatagrid(data.data);
        $.each(data.data, function(i, value) {
            var starttime = processTime(value.starttime);
            var endtime = processTime(value.endtime);
            var days = processDays(value.days);
            var lbl = days + " : " + starttime + " - " + endtime;

            $('#ratecard-add-daypart').append($("<option></option>").attr("value", value.id).text(lbl));
        });
    });
}




//MASTER GET MARKET LISTS
function getMarkets() {
    $.getJSON("services/markets.php?eventtype=list", function(data) {
        $('#ratecard-market').append($("<option></option>").attr("value", 0).text('Select Market'));
        //$('#ratecard-market').append($("<option></option>").attr("value", 'all').text('All Markets'));

        $('#markets-id').append($("<option></option>").attr("value", 0).text('Select Market'));
        //$('#markets-id').append($("<option></option>").attr("value", 'all').text('All Markets'));


        $.each(data.data, function(i, value) {
            $('#markets-id').append($("<option></option>").attr("value", value.id).text(value.name));
            $('#ratecard-market').append($("<option></option>").attr("value", value.id).text(value.name));
        })
    });
}






//COPY DAYPARTS
function copyMarketDaypartsEvent(ids, rows) {
    var rows = JSON.stringify(rows);

    $.post("services/dayparts.php", {
        eventtype: "copydayparts",
        ids: ids,
        rows: rows
    }).done(function(data) {
        console.log(data);
    });
}
//END COPY DAYPARTS






//LIST THE MARKET DAYPARTS
function getMarketDayparts(marketid) {
    $.getJSON("services/markets.php?eventtype=marketdayparts&marketid=" + marketid, function(data) {
        var json = jQuery.parseJSON(data.data);
        if (json.length != 0)
            datagridDaypartSelected.populateDatagrid(json);
    });
}







//UPDATE THE MARKET DAYPARTS
function updateMarketDayparts() {
    var daypartsData = datagridDaypartSelected.dataRows();
    var marketid = $('#markets-id :selected').val();
    var dayparts = JSON.stringify(daypartsData);

    $.post("services/markets.php", {
        eventtype: "updatedayparts",
        marketid: marketid,
        dayparts: dayparts
    }).done(function(data) {});

}









//ADD DAYPARTS TO THE MARKET
$('#button-add-daypart-to-market').on('click', function() {
    var daypartid = $('#ratecard-add-daypart').val();

    $.getJSON("services/markets.php?eventtype=getselecteddaypart&daypartid=" + daypartid, function(data) {
        datagridDaypartSelected.addRows(data.data);
        updateMarketDayparts();
    });

});





$('#button-update-daypart-to-market').on('click', function() {
    var starttime = $('#daypart-start-time').val();
    var endtime = $('#daypart-end-time').val();
    var days = $('#daypart-days').val();


    starttime = new Date("01/01/2007 " + starttime).toString("HH:mm:ss");
    endtime = new Date("01/01/2007 " + endtime).toString("HH:mm:ss");

    datagridDaypartSelected.updateDaypart(starttime, endtime, days);
    datagridDaypartSelected.unSelectAll();
    //panelEditMarketDayparts(0);
    updateMarketDayparts();
});









//SIDEBAR MARKETS SELECTOR FROM Markets DAYPARTS
$('#markets-id').on('change', function() {
    datagridDaypartSelected.emptyGrid();
    selectedmarketid = this.value;

    $("#ratecard-market").val(selectedmarketid).trigger("change");

    if (selectedmarketid == 0) {
        $('#sidebar-tab-5-sub').css('display', 'none');
    } else {
        getMarketDayparts(selectedmarketid);
        $('#sidebar-tab-5-sub').css('display', 'inline');
    }
});




//SIDEBAR MARKETS SELECTOR FROM Ratecard
$('#ratecard-market').on('change', function() {
    datagridRatecards.emptyGrid();
    selectedmarketid = this.value;
    getMarketZones(selectedmarketid);
});


function reloadMarketZones() {
    getMarketZones(selectedmarketid);
}


function getMarketZones(marketid) {
    marketsid = marketid;


    //sidebar-tab-1-sub-2
    if (marketid == 0) {
        $('#label-market-title').html('');
        $('#sidebar-tab-1-sub').css('display', 'none');
        $('#top-bar-ratecard-options').css('display', 'none');
        $('#sidebar-tab-1-sub-2').css('display', 'none');
        return;
    } else {
        $('#top-bar-ratecard-options').css('display', 'inline');
    }

    $.post("services/markets.php", {
        eventtype: "getzonesformarket",
        marketid: marketid
    }).done(function(data) {

        var json = jQuery.parseJSON(data);
        var session = checkSession(json);

        $('#ratecard-zone').find('option').remove().end();




        if (data == 0) {
            $('#sidebar-tab-1-sub').css('display', 'none');
            $('#sidebar-tab-1-sub-2').css('display', 'inline');
            datagridRatecards.emptyGrid();
            return;
        }

        getCardsForMarket(marketid, true);
        $('#sidebar-tab-1-sub').css('display', 'inline');
        $('#sidebar-tab-1-sub-2').css('display', 'none');

        $('#ratecard-zone').append($("<option></option>").attr("value", '').text('Select Zone'));
        var json = jQuery.parseJSON(data);


        $.each(json, function(i, value) {
            $('#ratecard-zone').append($("<option></option>").attr("value", value.id).text(value.zone));
        });

    });
}








function getCardsForMarket(id, group) {
    $('#label-market-title').html('<b>' + $('#ratecard-market option:selected').text() + '</b>');

    $.post("services/ratecards.php", {
        eventtype: "listbymarket",
        marketid: id
    }).done(function(data) {

        var json = jQuery.parseJSON(data);
        var session = checkSession(json);

        if (data != 0) {
            var json = jQuery.parseJSON(data);
            datagridRatecards.populateDatagrid(json, group);
            //datagridRatecards.collapseAllGroups();
        } else {
            datagridRatecards.emptyGrid();
        }
    });
}







function checkSession(data) {
    if (data.data == 'login') {
        location.href = "/login";
        return false;
    }
    return true;
}





//Load ratecard from ID
function isPublished(type) {
    published = type;

    if (published == 1) {
        $('#sidebar-tab-2-controls').css('display', 'none');
        $('#sidebar-tab-2-broadcast-controls').css('display', 'none');
        $('#sidebar-tab-2-error-published').css('display', 'inline');
        $('#sidebar-tab-2-error-broadcast-published').css('display', 'inline');
        $('.submenu').css('display', 'none');
    } else {
        $('#sidebar-tab-2-controls').css('display', 'inline');
        $('#sidebar-tab-2-broadcast-controls').css('display', 'inline');
        $('#sidebar-tab-2-error-published').css('display', 'none');
        $('#sidebar-tab-2-error-broadcast-published').css('display', 'none');
        $('.submenu').css('display', 'inline')
    }
}



function reloadRatecardByID() {
    loadRatecardByID(ratecardid, 0);
}


function loadRatecardByID(id, type) {
    loadDialogWindowAlt('load', 'Loading Please Wait', 380, 160);


    $.getJSON("services/ratecards.php?eventtype=loadratecard&id=" + id + "&type=" + type, function(data) {

        var session = checkSession(data);

        var dayparts = data.dayparts;
        var ratecarddata = data.data;
        var info = data.info;
        var hot = data.hot;


        ratecardid = info.id;
        isbroadcast = info.broadcast;
        zoneid = info.zoneid;
        $('#sidebar-tab-3-error-save').css('display', 'none');


        isPublished(type);
        $('#label-pricing-for').html('<b>' + info.zone + '</b> - <b>' + info.name + '</b>');
        $('#label-pricing-for-broadcast').html('<b>' + info.zone + '</b> - <b>' + info.name + '</b>');
        $('#label-hot-programming-zone').html('<b>' + info.zone + '</b>');


        if (info.broadcast == 1) {
            $('#pricing-broadcast').css('display', 'inline');
            $('#sidebar-tab-2-broadcast').css('display', 'inline');
            $('#pricing-cable').css('display', 'none');
            $('#sidebar-tab-2').css('display', 'none');
            var json = jQuery.parseJSON(ratecarddata);
            datagridPricingBroadcast.populateDatagrid(json);
            datagridHotProgramming.populateDatagrid(hot);
        } else {
            $('#pricing-broadcast').css('display', 'none');
            $('#sidebar-tab-2-broadcast').css('display', 'none');
            $('#pricing-cable').css('display', 'inline');
            $('#sidebar-tab-2').css('display', 'inline');
            datagridPricing.buildGridCols(dayparts);
            datagridPricing.populateDatagrid(ratecarddata);
            datagridHotProgramming.populateDatagrid(hot);
        }

        menuSelect('tab-2', 'menu-2');

        $.getJSON("services/zones.php?eventtype=zonenetworks&zoneid=" + zoneid, function(data) {
            $('#hot-network-list')[0].options.length = 0;
            $('#hot-network-list').append($("<option></option>").attr("value", 0).text('All Networks'));
            $.each(data.data, function(i, value) {
                var x = value.callsign;
                $('#hot-network-list').append($("<option></option>").attr("value", value.id).text(x));
            });

            setTimeout(function() {
                closeAllAltDialogs()
            }, 500);
        });

    });
}




//Save changes to ratecard
function saveRatecardChanges(evt) {
    loadDialogWindowAlt('saving-changes', 'Saving Changes', 380, 160);

    if (isbroadcast == 1) {
        var data = datagridPricingBroadcast.prepareSave();
        var json = JSON.stringify(data);
    } else {
        var data = datagridPricing.prepareSave();
        var json = JSON.stringify(data);
    }



    $.post("services/ratecards.php", {
        eventtype: "saveratecard",
        json: json,
        ratecardid: ratecardid,
        published: published
    }).done(function(data) {
        setTimeout(function() {
            closeAllAltDialogs()
        }, 1000);
        $('#sidebar-tab-3-error-save').css('display', 'none');
        if (evt == "download") {
            downloadExcel();
        }

        if (evt == "publish") {
            loadDialogWindow('publish-ratecard', 'Publish Ratecard', 380, 200);
        }

    });
}









// ADD NEW DAYPART TO MARKET AND MASTER LIST
$('#button-add-new-daypart-to-market').on('click', function() {
    var starttime = $('#daypart-start-time').val();
    var endtime = $('#daypart-end-time').val();
    var days = $('#daypart-days').val();

    if (starttime == '12:00 AM') {
        starttime = '00:00:00';
    } else {
        starttime = Date.parse("01/01/1970 " + starttime).toString("HH:mm:ss");
    }


    if (endtime == '12:00 AM') {
        endtime = '23:59:00';
    } else {
        endtime = Date.parse("01/01/1970 " + endtime).toString("HH:mm:ss");
    }

    //starttime = Date.parse("01/01/1970 " + starttime).toString("HH:mm:ss");
    //endtime = Date.parse("01/01/1970 " + endtime).toString("HH:mm:ss");

    $.post("services/dayparts.php", {
        eventtype: "createdaypart",
        starttime: starttime,
        endtime: endtime,
        days: days
    }).done(function(data) {
        var json = jQuery.parseJSON(data);



        getDayparts();
        datagridDaypartSelected.addRows(json);
        updateMarketDayparts();
    });

});





//DELETE DAYPARTS FORM MARKET
function removeDaypartFromMarket() {
    loadDialogWindow('confirm-delete-daypart', 'Delete Daypart', 380, 240);
}

$("#checkbox-publish-ratecard").live("click", function() {
    var val = $(this).attr("checked");

    if (val == 'checked') {
        $("#button-publish-ratecard").css('display', 'inline');
    } else {
        $("#button-publish-ratecard").css('display', 'none');
    }
});


$("#checkbox-delete-market-daypart").live("click", function() {
    var val = $(this).attr("checked");

    if (val == 'checked') {
        $("#button-delete-market-daypart").removeClass("disabled");
    } else {
        $("#button-delete-market-daypart").addClass("disabled");
    }
});






//SELECT THE MARKET IN THE DAYPART SELECTOR
function changeDaypartToSelectedMarket() {
    var id = $('#ratecard-market').val();
    $("#markets-id").val(id).trigger("change");
}









function panelEditMarketDayparts(type) {
    if (type == 1) {
        $('#sidebar-group-create-daypart').css('display', 'none');
        $('#sidebar-group-edit-daypart').css('display', 'inline');
    } else {
        $('#sidebar-group-create-daypart').css('display', 'inline');
        $('#sidebar-group-edit-daypart').css('display', 'none');
        datagridDaypartSelected.unSelectAll();
    }
}


function panelEditMarketDaypartsButton(type) {
    return;
    if (type == 1) {
        $("#button-update-daypart-to-market").addClass("disabled");
        //disabled
        //$('#button-update-daypart-to-market').css('display', 'none');
    } else {
        $("#button-update-daypart-to-market").removeClass("disabled");
        //$('#button-update-daypart-to-market').css('display', 'inline');
    }
}







function publishRatecard() {
    saveRatecardChanges();
    loadDialogWindow('publish-ratecard', 'Publish Ratecard', 380, 200);
}


function publishRatecardEvent() {
    var notes = encodeURIComponent($('#form-publish-ratecard-notes').val());

    $.post("services/ratecards.php", {
        eventtype: "publish",
        ratecardid: ratecardid,
        notes: notes
    }).done(function(data) {
        closeAllDialogs();
        getCardsForMarket(selectedmarketid, false);
    });
}




function confirmRatecardDelete() {
    loadDialogWindow('confirm-delete-ratecards', 'Confirm Delete', 380, 150);
}





function eventRatecardDelete() {
    var ids = datagridRatecards.selectedIds();

    $.post("services/ratecards.php", {
        eventtype: "delete",
        ids: ids
    }).done(function(data) {
        closeAllDialogs();
        getCardsForMarket(selectedmarketid, false);
    });
}





//VALIDATION POST NEW RATECARD
$('#form-new-ratecard')
    .on('invalid', function() {
        var invalid_fields = $(this).find('[data-invalid]');
    })
    .on('valid', function() {

        var marketid = $('#ratecard-market').val();
        var zoneid = $('#ratecard-zone').val();
        var startdate = $('#ratecard-start-date').val();
        var enddate = $('#ratecard-end-date').val();
        var special = $('#ratecard-special').val();
        var name = $('#ratecard-name').val();

        startdate = Date.parse(startdate + " 00:00:00").toString("yyyy/MM/dd");
        enddate = Date.parse(enddate + " 00:00:00").toString("yyyy/MM/dd");

        $.post("services/ratecards.php", {
            eventtype: "create",
            marketid: marketid,
            zoneid: zoneid,
            startdate: startdate,
            enddate: enddate,
            name: name,
            special: special
        }).done(function(data) {
            getCardsForMarket(marketid);
            loadRatecardByID(data);
        });

    });








//HOT PROGRAMMING
function setHotRateEvent() {

    var rows = datagridHotProgramming.selectedRows();
    if (rows.length == 0) {
        loadDialogWindow('warning-no-rows-selected', 'Select Rows', 380, 150);
        return;
    }

    var daypart = $('#hot-daypart-rate').val();
    var premiere = $('#hot-premiere-rate').val();
    var finale = $('#hot-finale-rate').val();
    var isnew = $('#hot-new-rate').val();
    var live = $('#hot-live-rate').val();

    var rates = {};
    rates.daypart = daypart;
    rates.premiere = premiere;
    rates.finale = finale;
    rates.isnew = isnew;
    rates.live = live;

    datagridHotProgramming.setRate(rates);


    hotProgrammingReset();
}





function saveHotProgramming() {
    if (zoneid == 0) {
        return;
    }
    loadDialogWindowAlt('saving-changes', 'Saving Changes', 380, 160);
    var rows = datagridHotProgramming.getData();
    var programs = JSON.stringify(rows);

    $.post("services/zones.php", {
        eventtype: "savehotprogramming",
        zoneid: zoneid,
        programs: programs
    }).done(function(data) {
        setTimeout(function() {
            closeAllAltDialogs()
        }, 1000);
    });
}


function confirmHotprogramDelete() {
    loadDialogWindow('confirm-delete-hot-program', 'Confirm Delete', 380, 160);
}



function setHotNetworkEvent() {
    var name = $('#hot-network-list option:selected').text();
    var val = $('#hot-network-list').val();

    datagridHotProgramming.updateNetwork(name, val);

}


function hotProgrammingReset(){
    $('#hot-network-list').val(0);
    $('#hot-daypart-rate').val('');
    $('#hot-premiere-rate').val('');
    $('#hot-finale-rate').val('');
    $('#hot-new-rate').val('');
    $('#hot-live-rate').val('');
}


function copyHotProgramming(type){
    copyHotProgrammingType = type;
    loadDialogWindow('copy-hotprograms', 'Copy Hot Programs', 380, 300);
}




//END HOT PROGRAMMING







//BROADCAST FUNCTIONS

//add broadcast rate
function addBroadcastRate() {
    var rate = $('#pricing-daypart-broadcast').val();
    var fixed = $('#pricing-fixed-broadcast').val();
    var fname = $('#pricing-title-alt-selector-broadcast').val();

    if (fname.length == 0) {
        fname = $('#pricing-title-selector-broadcast option:selected').text();
    }

    var daysid = broadcastTitleRow.days.replace(/,/g, "");
    var id = broadcastTitleRow.networkid + starttimeID + endtimeID + daysid;

    var sort = daysid + starttimeID + endtimeID;

    if (isNaN(parseFloat(rate))) {
        rate = 0;
    }

    if (isNaN(parseFloat(fixed))) {
        fixed = 0;
    }

    var row = {};
    row.id = id;
    row.rate = parseFloat(rate);
    row.ratefixed = parseFloat(fixed);
    row.fname = fname;
    row.starts = broadcastTitleRow.starttime;
    row.stops = broadcastTitleRow.endtime;
    row.weekdays = broadcastTitleRow.days;
    row.callsign = broadcastTitleRow.callsign;;
    row.networkid = broadcastTitleRow.networkid;
    row.sort = sort;

    datagridPricingBroadcast.addRow(row);
    $('#pricing-title-alt-selector-broadcast').val('');
}






//edit line
function editBroadcastLine() {
    var rate = $('#pricing-daypart-broadcast').val();
    var fixed = $('#pricing-fixed-broadcast').val();
    var fname = $('#pricing-title-alt-selector-broadcast').val();
    var selectedname = $('#pricing-title-selector-broadcast').val();


    if (selectedname != 0) {
        fname = $('#pricing-title-selector-broadcast option:selected').text();
    }


    var daysid = broadcastTitleRow.days.replace(/,/g, "");
    var id = broadcastTitleRow.networkid + starttimeID + endtimeID + daysid;
    var sort = daysid + starttimeID + endtimeID;

    if (isNaN(parseFloat(rate))) {
        rate = 0;
    }

    if (isNaN(parseFloat(fixed))) {
        fixed = 0;
    }

    var row = {};
    row.id = id;
    row.rate = parseFloat(rate);
    row.ratefixed = parseFloat(fixed);
    row.fname = fname;
    row.starts = broadcastTitleRow.starttime;
    row.stops = broadcastTitleRow.endtime;
    row.weekdays = broadcastTitleRow.days;
    row.callsign = broadcastTitleRow.callsign;;
    row.networkid = broadcastTitleRow.networkid;
    row.sort = sort;

    var re = datagridPricingBroadcast.updateBroadcastLine(row);

    if (re != 0) {
        loadDialogWindow('line-conflict', 'Line Conflict', 380, 150);
    }

    //panelEditBroadcastLine(1);
}








//broadcast title search
function searchBroadcastTitles() {

    //set the start and end times also creating a time id
    var starttime = $('#ratecard-broadcast-start-time').val();
    starttime = Date.parse(starttime).toString("HH:mm:ss");
    starttimeID = Date.parse(starttime).toString("HHmmss");

    var endtime = $('#ratecard-broadcast-end-time').val();
    endtime = Date.parse(endtime).toString("HH:mm:ss");
    endtimeID = Date.parse(endtime).toString("HHmmss");

    //grab the days
    var days = $('#ratecard-broadcast-days').val();

    //Empty the selector and add updating for user feedback
    $('#pricing-title-selector-broadcast').find('option').remove().end();
    $('#pricing-title-selector-broadcast').append($("<option></option>").attr("value", 0).text("Updating"));

    //post the event
    $.post("services/ratecards.php", {
        eventtype: "addbroadcastrate",
        ratecardid: ratecardid,
        starttime: starttime,
        endtime: endtime,
        days: days
    }).done(function(data) {
        var json = jQuery.parseJSON(data);
        broadcastTitleRow = json;

        $('#pricing-title-selector-broadcast').find('option').remove().end();
        if (json.titles.length == 0) {
            $('#pricing-title-selector-broadcast').append($("<option></option>").attr("value", 0).text("No Titles Avaiable"));
        } else {
            $('#pricing-title-selector-broadcast').append($("<option></option>").attr("value", 0).text("Select Title"));
            $.each(json.titles, function(i, value) {
                $('#pricing-title-selector-broadcast').append($("<option></option>").attr("value", value).text(value));
            });
        }
    });
}


//if form elements in the sidebar change then lets update the titles
$("#ratecard-broadcast-start-time").change(function() {
    searchBroadcastTitles();
});

$("#ratecard-broadcast-end-time").change(function() {
    searchBroadcastTitles();
});

$("#ratecard-broadcast-days").change(function() {
    searchBroadcastTitles();
});

//confirm the rows to delete if the user desires to do so.
function confirmRowDeleteDelete() {
    loadDialogWindow('confirm-delete-rows', 'Delete Selected Rows', 380, 150);
}



//PANEL
function panelEditBroadcastLine(type) {
    var cnt = datagridPricingBroadcast.selectedRows();

    if (cnt.length > 1 && type == 0) {
        loadDialogWindow('single-row', 'Select Row', 380, 150);
        return;
    }


    if (type == 1) {
        datagridPricingBroadcast.setEditmode(false);
        $('#sidebar-group-price-broadcast').css('display', 'inline');
        $('#sidebar-group-edit-broadcast').css('display', 'none');
        $('.main').css('background-color', '#F2F5F7');
        $('.sidebar').css('background-color', '#F1F1F1');
    } else {
        datagridPricingBroadcast.setEditmode(true);
        $('#sidebar-group-price-broadcast').css('display', 'none');
        $('#sidebar-group-edit-broadcast').css('display', 'inline');
        $('.main').css('background-color', '#E2CFC8');
        $('.sidebar').css('background-color', '#E2CFC8');
        datagridPricingBroadcast.parseBroadcastLine();
    }
}





//END BROADCAST FUNCTIONS 









//COPY RATCARDS
function copySelectedRatecards() {
    var cnt = datagridRatecards.selectedRows();

    if (cnt.length == 0 || cnt.length > 1) {
        loadDialogWindow('single-row', 'Select Ratecard', 380, 150);
        return;
    }


    loadDialogWindow('copy-ratecards', 'Copy Selected Ratecard', 350, 440);
}

//END COPY RATECARDS






//EDIT RATECARD
function editSelectedRatecards() {
    var cnt = datagridRatecards.selectedRows();

    if (cnt.length == 0) {
        loadDialogWindow('warning-no-rows-selected', 'Select Ratecards', 380, 150);
        return;
    }


    loadDialogWindow('edit-ratecards', 'Edit Selected Ratecards', 350, 300);
}
//END EDIT RATECARDS








//PANEL DAYPARTS
function panelEditDaypartLine(type) {
    var cnt = datagridDaypartSelected.selectedRows();

    if (cnt.length > 1 && type == 0) {
        loadDialogWindow('single-row', 'Select Row', 380, 150);
        return;
    }


    if (type == 1) {
        datagridDaypartSelected.setEditmode(false);
        $('#sidebar-group-create-daypart').css('display', 'inline');
        $('#sidebar-group-edit-daypart').css('display', 'none');
        $('.main').css('background-color', '#F2F5F7');
        $('.sidebar').css('background-color', '#F1F1F1');

        $('#sidebar-group-create-daypart-create').css('display', 'inline');
        $('#sidebar-tab-5-new-datpart').css('background-color', '#C8FEC9');
        $('#sidebar-tab-5-new-datpart-title').html('Create new Daypart');



    } else {
        datagridDaypartSelected.setEditmode(true);
        $('#sidebar-group-create-daypart').css('display', 'none');
        $('#sidebar-group-edit-daypart').css('display', 'inline');
        $('.main').css('background-color', '#E2CFC8');
        $('.sidebar').css('background-color', '#E2CFC8');

        $('#sidebar-group-create-daypart-create').css('display', 'none');
        $('#sidebar-tab-5-new-datpart').css('background-color', 'transparent');
        $('#sidebar-tab-5-new-datpart-title').html('Edit Selected Daypart');

        datagridDaypartSelected.parseEditLine();
    }
}









//EXCEL FUNCTIONS
function uploadExcel() {
    loadDialogWindow('upload-excel', 'Upload Excel', 380, 200);

}

function downloadExcel() {
    loadDialogWindow('download-excel', 'Downloading Excel', 380, 160);

    var cols = datagridPricing.getCols();

    $.getJSON("services/download.php?id=" + ratecardid + "&cols=" + cols, function(data) {
        var file = data.file;
        location.href = "services/getfile.php?file=" + file;
        closeAllDialogs();
    });
}


// FORMATTERS ETC
$('#datagrid-pricing-cable').keypress(function(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
});



function isNumberKey(evt) {
    try {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    } catch (err) {

    }
}




function processTime(t) {
    //if time is 12 AM pass the label
    if (t == '00:00:00') {
        return '12A';
    }

    //if time is midnight pass the label
    if (t == '23:59:00') {
        return '12M';
    }

    //get the min
    var min = Date.parse("01/01/1970 " + t).toString("m");
    var time = Date.parse("01/01/1970 " + t).toString("h:mmt");


    if (min == 0) {
        time = Date.parse("01/01/1970 " + t).toString("ht");
    }

    return time;
}



function processDays(d) {
    var ndays = [];
    var re = '';

    var days = d.split(',');
    var cnt = days.length;

    if (cnt == 7) {
        return 'M-Su';
    }

    $.each(days, function(i, val) {
        if (val == 1) {
            ndays.push(7);
        } else {
            ndays.push(val - 1);
        }
    });

    //if one day pass it back bro
    if (days.length == 1) {
        return daysAbbrSmallDayFix(ndays[0]);
    }

    var diff = ndays[ndays.length - 1] - ndays[0];

    if (ndays.length - diff == 1) {
        re = daysAbbrSmallDayFix(ndays[0]) + "-" + daysAbbrSmallDayFix(ndays[ndays.length - 1]);
    } else {
        var daylist = [];
        $.each(ndays, function(i, val) {
            daylist.push(daysAbbrSmallDayFix(val));
        });
        return daylist.join(",");
    }
    return re;
}



function generateNewUUID() {
    var d = new Date().getTime();
    var uuid = 'xxxxxxxxxxxx4xxxyxxxxxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = (d + Math.random() * 16) % 16 | 0;
        d = Math.floor(d / 16);
        return (c == 'x' ? r : (r & 0x7 | 0x8)).toString(16);
    });
    return uuid;
};


//switch for the days
function daysAbbrSmallDayFix(val) {
    switch (val) {
        case 1:
            x = "M";
            return x;
            break;
        case 2:
            x = "T";
            return x;
            break;
        case 3:
            x = "W";
            return x;
            break;
        case 4:
            x = "Th";
            return x;
            break;
        case 5:
            x = "F";
            return x;
            break;
        case 6:
            x = "Sa";
            return x;
            break;
        case 7:
            x = "Su";
            return x;
            break;
        case "ms":
            x = "M-SU";
            return x;
            break;
        case "ss":
            x = "S-SU";
            return x;
            break;
        case "mf":
            x = "M-F";
            return x;
            break;

    }
}




//