
//datagrids
var datagridRatecardPricing = new datagridRatecardPricing();
var datagridDayparts = new datagridDayparts();
var datagridRatecards = new datagridRatecards();
var datagridShowtitles = new datagridShowtitles();
var datagridHotProgramming = new datagridHotProgramming();

var ratecardid = 0;
var zoneid = 0;
var showtitlesloaded = false;

var firstLoad = true;
var processAction;
var hotEditMode = 'add';

var daypartEditMode = false;
var daypartEditId = 0;
var tab = 0;
var issaving = false;

$( document ).ready(function() {
	menuSelect('tab-1','menu-1');
	$('button').button();
	$('.btngroup').buttonset();
  $('#fixed-toggle').buttonset();
  $('#rate-mode-toggle').buttonset();
	windowManager();
  getDayparts('init');
  getDaypartGroups();
  getZones();
  getRatecards();
  getRules();
  //getShowTitles();

  //setInterval(saveChangesEvent, 80000);
});

$.ajaxSetup({ cache: false });

function saveChangesEvent(){
  if(tab == 2 && issaving == false){
    saveRatecard();
  }

  if(tab == 7 && issaving == false){
    saveHotProgramming();
  }

}















$(function() {
  $("#zone-form-start,#zone-form-end,#form-start-date-edit,#form-end-date-edit").datepicker(
    {numberOfMonths: 1}
  );

  $("#form-start-date-edit,#zone-form-start,#form-end-date-edit,#zone-form-end").datepicker("option", "firstDay", 1 );
  $("#form-start-date-edit,#zone-form-start,#form-end-date-edit,#zone-form-end").datepicker( "option", "showTrailingWeek", false );
  $("#form-start-date-edit,#zone-form-start,#form-end-date-edit,#zone-form-end").datepicker( "option", "showOtherMonths", true );
  $("#form-start-date-edit,#zone-form-start,#form-end-date-edit,#zone-form-end").datepicker( "option", "selectOtherMonths", true );
    


  $("#zone-form-start").datepicker("setDate",'01/01/2014');
  $("#zone-form-end").datepicker("setDate",'12/31/2014');

  $("#form-start-date-edit").datepicker("setDate",'01/01/2014');
  $("#form-end-date-edit").datepicker("setDate",'12/31/2014');
});




function loadRatecardByID(id,swaptab){
  
  
  
  $.getJSON("services/getRatecardByID.php?id="+id, function(data){


    datagridRatecardPricing.buildGrid(data);
  
    var hot = jQuery.parseJSON(data['hot']);
    datagridHotProgramming.populateDatagrid(hot);


    if(swaptab != false){
      menuSelect('tab-2','menu-2');
    }
    
    ratecardid = id;
    zoneid = data['info'].zoneid;
    var name = data.data[0].ratecard;
    var syscode = data.data[0].syscode;
    var zone = data.data[0].zone;
    var title = name + ' - Zone: ' + zone +  ' - Syscode: ' + syscode;
    var titlehot = 'Hot Programming - Zone: ' + zone;


    $('#label-ratecard-pricing').html(title);
    $('#label-grid-hot-programming').html(titlehot);


    var sdate = data.info.sdate;
    var edate = data.info.edate;

    sdate = Date.parse(sdate + " 00:00:00").toString("MM/dd/yyyy");
    edate = Date.parse(edate + " 00:00:00").toString("MM/dd/yyyy");
    //$('#form-start-date-edit').val(sdate);
    //$('#form-end-date-edit').val(edate);
    $("#form-start-date-edit").datepicker("setDate",sdate);
    $("#form-end-date-edit").datepicker("setDate",edate);
    $("#form-name-edit").val(data.info.ratecard);
  });
}



$('#rate-rate,#rate-fixed').keypress(function(e) {
     if (e.which > 31 && (e.which < 48 || e.which > 57)) {
        e.preventDefault();
     }
});


$('.num').keypress(function(e) {
     if (e.which > 31 && (e.which < 48 || e.which > 57)) {
        e.preventDefault();
     }
});




// dayparts
function daypartEdit(){
  var cols = datagridDayparts.selectedRows();

  if(cols.length == 0 || cols.length > 1 || cols[0].__group == true){
    loadMessage('singleshow');
    return;
  }
    
  var name = cols[0].daypart;
  var starttime = cols[0].starttime;
  var endtime = cols[0].endtime;
  var days = cols[0].days;

  daypartEditId = cols[0].id;

  
  $("#daypart-form-days").find("option").attr("selected", false);
  $('#daypart-form-name').val(name);
  $('#daypart-form-start').val(starttime);
  $('#daypart-form-end').val(endtime);
  $('#daypart-group-wrapper').css('display', 'none');

  for (var i=0;i<days.length;i++){
    $('#daypart-form-days[name="daypart-form-days"]').find('option[value="'+days[i]+'"]').attr("selected",true);
  }


  daypartEditMode = true;
  dialogDayparts();
}






function daypartCreate(){
  $('#daypart-group-wrapper').css('display', 'inline');
  $("#daypart-form-days").find("option").attr("selected", false);

  var days = '1,2,3,4,5,6,7';

  for (var i=0;i<days.length;i++){
    $('#daypart-form-days[name="daypart-form-days"]').find('option[value="'+days[i]+'"]').attr("selected",true);
  }

  daypartEditId = 0;
  daypartEditMode = false;
  dialogDayparts();
}











//update event
function setHotRateEvent(rate,type){
  var rows = datagridHotProgramming.selectedRows();

  if(rows.length == 0){
    loadMessage('selectcolumns');
    return;
  }

  datagridHotProgramming.setRate(rate,type);
}










//update event
function setRateEvent(rate,type){
  var cols = datagridRatecardPricing.getSelectedColumns();

  if(cols.length == 0){
    loadMessage('selectcolumns');
    return;
  }

  //datagridRatecardPricing.setUndoData();
  datagridRatecardPricing.setRate(rate,type);
}



//set the rate cards event
function setRateEventFixed(rate,type){
  var cols = datagridRatecardPricing.getSelectedColumns();

  if(cols.length == 0){
    loadMessage('selectcolumns');
    return;
  }

  datagridRatecardPricing.setRateFixed(rate,type);
}




function getShowTitles(){
  /*
  if(showtitlesloaded == true){
    return;
  }
*/
  $.getJSON("/services/showtitles.php", function(data){
    var titles = data.data;
    showtitlesloaded = true;
    datagridShowtitles.populateDatagrid(titles);
  });
}



function deleteHotProgramming(){
  datagridHotProgramming.removeRows();
  datagridHotProgramming.unSelectAll();

  $("#dialog-message").dialog("destroy");
}




function downloadExcel(){
  $.post( "/download/index.php", {
      ratecardid: ratecardid
    }).done(function(data){
      location.href = "download/force.php?filename="+data;
  });
  //ratecardid
}




function importHotPrograms(){
  var importid = $('#zonesid').val();


  $.post( "services/eventHotProgramming.php", {
    eventtype: 'importhotprograms', 
    importid: importid,
    zoneid: zoneid
    }).done(function(data){
      
      
      if(data == 0){
        alert('There is no hot programming in this zone.');
      }else{
        $("#dialog-import-hot-programming").dialog("destroy");
        loadRatecardByID(ratecardid,false);
      }
      

  });
}


function saveHotProgramming(){
  var data = JSON.stringify(datagridHotProgramming.getData());
  $.post( "/services/eventHotProgramming.php", {
    eventtype: 'update2', 
    data: data,
    zoneid: zoneid
    }).done(function(data){
      $("#dialog-saving").dialog("destroy");
  });
}



function eventHotProgrammingAdd(){
  var title = datagridShowtitles.selectedRows();
  datagridHotProgramming.addShows(title);
}









function rescanNetworks(){

  var rows = datagridRatecards.selectedRows();
  if(rows.length != 1){
    loadMessage('singleshow');
    return;
  }

  var id = rows[0].id;

  $.post( "update.php", {
      id: id
    }).done(function(data){
      window.alert("Networks Updated");
  });
  

}





// wire up the search textbox to apply the filter to the model
$("#showtitleinput").keyup(function(e) {
  datagridShowtitles.updatFromKeyword(e, this.value);
});





if (!('indexOf' in Array.prototype)) {
    Array.prototype.indexOf= function(find, i /*opt*/) {
        if (i===undefined) i= 0;
        if (i<0) i+= this.length;
        if (i<0) i= 0;
        for (var n= this.length; i<n; i++)
            if (i in this && this[i]===find)
                return i;
        return -1;
    };
}





function GUID (){
    var S4 = function ()
    {
        return Math.floor(
                Math.random() * 0x10000 /* 65536 */
            ).toString(16);
    };
    return (
            S4() + S4() + "-" +
            S4() + "-" +
            S4() + "-" +
            S4() + "-" +
            S4() + S4() + S4()
        );
}


