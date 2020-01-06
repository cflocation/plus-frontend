// Toggle
function toggleSidebar(){
  var sideloc = $('#sidebar2').css('left');
  if(sideloc == '0px'){
    sidebarClose();
  }else{
    sidebarOpen();
  }
  windowManager();
}

function sidebarClose(){
    $('#sidebar2').css('left', -300);
    $('#container').css('left', 0);
    $('#collapse-settings').html('<i class="fa fa-arrow-circle-right fa-lg"></i>');
}

function sidebarOpen(){
    $('#sidebar2').css('left', 0);
    $('#container').css('left', 291);
    $('#collapse-settings').html('<i class="fa fa-arrow-circle-left fa-lg"></i>');
}
//End Toggle









/*
  DATE PICKER
*/
function createDaypartGroup(name){
    $.post( "services/dayparts.php", {
    eventtype: 'createdaypartgroup', 
    name: name}).done(function(data){
      $("#dialog-window").dialog("destroy");
      //after the result comes back lets reload and select the new item
      getDaypartGroups(data);
    });
  
}

//auto select all the  content in a form box
$("#daypart-name").focus(function() {
    var $this = $(this);
    $this.select();

    // Work around Chrome's little problem
    $this.mouseup(function() {
        // Prevent further mouseup intervention
        $this.unbind("mouseup");
        return false;
    });
});

//set the number of months
$("#ratecard-start-date,#ratecard-end-date").datepicker(
  {numberOfMonths: 1}
);

//make the calendar a broadcast one
$("#ratecard-start-date,#ratecard-end-date").datepicker("option", "firstDay", 1 );
$("#ratecard-start-date,#ratecard-end-date").datepicker( "option", "showTrailingWeek", false );
$("#ratecard-start-date,#ratecard-end-date").datepicker( "option", "showOtherMonths", true );
$("#ratecard-start-date,#ratecard-end-date").datepicker( "option", "selectOtherMonths", true );

$("#ratecard-start-date").datepicker("setDate", new Date());
$("#ratecard-end-date").datepicker("setDate", +90);

/*
  END DATE PICKER
*/











/*
  TIME PICKER
*/
  $("#daypart-start-time,#daypart-end-time,#ratecard-broadcast-start-time,#ratecard-broadcast-end-time").timepicker({
    'timeFormat': 'h:i A'
  });

  //set the default times
  $('#daypart-start-time').timepicker('setTime', '06:00 AM');
  $('#daypart-end-time').timepicker('setTime', '11:59 PM');

  $('#ratecard-broadcast-start-time').timepicker('setTime', '06:00 AM');
  $('#ratecard-broadcast-end-time').timepicker('setTime', '11:59 PM');



/*

var oldTime = $('#daypart-start-time').timepicker('getTime');

$("#daypart-start-time").change(function() {
  $('#daypart-start-time').removeClass("error");
  $('#daypart-end-time').removeClass("error");
  
  if($('#daypart-end-time').timepicker('getTime') < $('#daypart-start-time').timepicker('getTime')) {
      loadMessage('invalid-times');
      $('#daypart-start-time').addClass("error");
  }else{
      $('#daypart-start-time').removeClass("error");
    }
    resetChecker();
});
*/
/*
  END TIME PICKER
*/
