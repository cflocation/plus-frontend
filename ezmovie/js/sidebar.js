
//set the number of months
$("#network-start-date,#network-end-date,#sidebar-custom-breaks-start-date,#sidebar-custom-breaks-end-date").datepicker(
  {numberOfMonths: 1}
);

//make the calendar a broadcast one
$("#network-start-date,#network-end-date,#sidebar-custom-breaks-start-date,#sidebar-custom-breaks-end-date").datepicker("option", "firstDay", 1 );
$("#network-start-date,#network-end-date,#sidebar-custom-breaks-start-date,#sidebar-custom-breaks-end-date").datepicker( "option", "showTrailingWeek", false );
$("#network-start-date,#network-end-date,#sidebar-custom-breaks-start-date,#sidebar-custom-breaks-end-date").datepicker( "option", "showOtherMonths", true );
$("#network-start-date,#network-end-date,#sidebar-custom-breaks-start-date,#sidebar-custom-breaks-end-date").datepicker( "option", "selectOtherMonths", true );

//$("#network-start-date").datepicker("setDate",'01/01/2014');
//$("#network-end-date").datepicker("setDate",'12/25/2014');


//SET DEFAULT DATES
$("#network-start-date,#sidebar-custom-breaks-start-date").datepicker("setDate", new Date());
$("#network-end-date,#sidebar-custom-breaks-end-date").datepicker("setDate", +40);

$('#sidebar-custom-breaks-start-time, #sidebar-custom-breaks-end-time').timepicker({
	timeFormat: 'HH:mm z',
	timezone: 'ET',
	timezoneList: [ 
			{ value: 'ET', label: 'Eastern'}, 
			{ value: 'CT', label: 'Central' }, 
			{ value: 'MT', label: 'Mountain' }, 
			{ value: 'PT', label: 'Pacific' } 
		]
});

$('#sidebar-custom-breaks-start-time').datetimepicker('setDate', (new Date("Jan 01, 2014 00:00:00")) );
$('#sidebar-custom-breaks-end-time').datetimepicker('setDate', (new Date("Jan 01, 2014 23:59:59")) );


function chooseCustomBreakType(type)
{
	if(type=='Yes')
	{
		$('#sidebar-tab-3-length').css('display', 'inline');
		$('#sidebar-tab-3-endtime-label, #sidebar-tab-3-endtime').css('display', 'none');
		$('#sidebar-custom-breaks-break-hidden').val('1');
	} else if(type == 'No')
	{
		$('#sidebar-tab-3-length').css('display', 'none');
		$('#sidebar-tab-3-endtime-label, #sidebar-tab-3-endtime').css('display', 'inline');
		$('#sidebar-custom-breaks-break-hidden').val('0');
	} else
	{
		$('#sidebar-tab-3-length').css('display', 'none');
		$('#sidebar-tab-3-endtime-label, #sidebar-tab-3-endtime').css('display', 'inline');
		$('#sidebar-custom-breaks-break-hidden').val('');
	}
	return;
}


/*
  TIME PICKER
*/
/*
  $("#sidebar-custom-breaks-start-time").timepicker({
    'timeFormat': 'h:i A'
  });

  //set the default times
  $('#sidebar-custom-breaks-start-time').timepicker('setTime', '06:00 AM');

  */