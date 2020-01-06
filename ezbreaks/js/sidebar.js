
//set the number of months
$("#network-start-date,#network-end-date,#sidebar-custom-breaks-start-date,#sidebar-custom-breaks-end-date,#sidebar-custom-titles-start-date,#sidebar-custom-titles-end-date").datepicker(
  {numberOfMonths: 1}
);

//make the calendar a broadcast one
$("#network-start-date,#network-end-date,#sidebar-custom-breaks-start-date,#sidebar-custom-breaks-end-date,#sidebar-custom-titles-start-date,#sidebar-custom-titles-end-date").datepicker("option", "firstDay", 1 );
$("#network-start-date,#network-end-date,#sidebar-custom-breaks-start-date,#sidebar-custom-breaks-end-date,#sidebar-custom-titles-start-date,#sidebar-custom-titles-end-date").datepicker( "option", "showTrailingWeek", true );
$("#network-start-date,#network-end-date,#sidebar-custom-breaks-start-date,#sidebar-custom-breaks-end-date,#sidebar-custom-titles-start-date,#sidebar-custom-titles-end-date").datepicker( "option", "showOtherMonths", true );
$("#network-start-date,#network-end-date,#sidebar-custom-breaks-start-date,#sidebar-custom-breaks-end-date,#sidebar-custom-titles-start-date,#sidebar-custom-titles-end-date").datepicker( "option", "selectOtherMonths", true );

//$("#network-start-date").datepicker("setDate",'01/01/2014');
//$("#network-end-date").datepicker("setDate",'12/25/2014');


//SET DEFAULT DATES
$("#network-start-date,#sidebar-custom-breaks-start-date,#sidebar-custom-titles-start-date,#sidebar-custom-titles-end-date").datepicker("setDate", +1);
$("#network-end-date,#sidebar-custom-breaks-end-date").datepicker("setDate", +40);


$('#sidebar-custom-breaks-start-date').datepicker("option",'onClose',function( selectedDate ) {
    $( "#sidebar-custom-breaks-end-date" ).datepicker( "option", "minDate", selectedDate );
});

$('#sidebar-custom-breaks-end-date').datepicker("option",'onClose',function( selectedDate ) {
    $( "#sidebar-custom-breaks-start-date" ).datepicker( "option", "maxDate", selectedDate );
});

$( "#sidebar-custom-breaks-end-date" ).datepicker( "option", "minDate",  new Date() );
$( "#sidebar-custom-breaks-start-date" ).datepicker( "option", "maxDate",   +40 );










$('#sidebar-custom-breaks-start-time, #sidebar-custom-breaks-end-time, #sidebar-custom-titles-start-time, #sidebar-custom-titles-end-time').timepicker({
	timeFormat: 'HH:mm z',
	timezone: 'ET',
	timezoneList: [ 
			{ value: 'ET', label: 'Eastern'}, 
			{ value: 'CT', label: 'Central' }, 
			{ value: 'MT', label: 'Mountain' }, 
			{ value: 'PT', label: 'Pacific' } 
		]
});







$('#sidebar-scheduler-time, #sidebar-update-scheduler-time').timepicker({
	timeFormat: 'HH:mm z',
	timezone: 'ET',
	stepMinute: 30,
	timezoneList: [ 
			{ value: 'ET', label: 'Eastern'}, 
			{ value: 'CT', label: 'Central' }, 
			{ value: 'MT', label: 'Mountain' }, 
			{ value: 'PT', label: 'Pacific' }
		]
});

$('#sidebar-custom-breaks-start-time, #sidebar-custom-titles-start-time,#sidebar-scheduler-time').datetimepicker('setDate', (new Date("Jan 01, 2014 00:00:00")) );
$('#sidebar-custom-breaks-end-time, #sidebar-custom-titles-end-time').datetimepicker('setDate', (new Date("Jan 01, 2014 23:59:59")) );


function chooseCustomBreakType(type)
{
	if(type=='Yes')
	{
		$('#sidebar-tab-3-length').css('display', 'inline');
		$('#sidebar-custom-breaks-to-label, #sidebar-custom-breaks-enddate-label, #sidebar-tab-3-template').css('display', 'none');
		$('#sidebar-custom-breaks-break-hidden').val('1');
	} else if(type == 'No')
	{
		$('#sidebar-tab-3-length, #sidebar-tab-3-template').css('display', 'none');
		$('#sidebar-custom-breaks-to-label, #sidebar-custom-breaks-enddate-label').css('display', 'inline');
		$('#sidebar-custom-breaks-break-hidden').val('0');
	} else if(type == 'Template')
	{
		$('#sidebar-tab-3-length').css('display', 'none');
		$('#sidebar-custom-breaks-to-label, #sidebar-custom-breaks-enddate-label, #sidebar-tab-3-template').css('display', 'inline');
		$('#sidebar-custom-breaks-break-hidden').val('2');
	} else
	{
		$('#sidebar-tab-3-length, #sidebar-tab-3-template').css('display', 'none');
		$('#sidebar-custom-breaks-to-label, #sidebar-custom-breaks-enddate-label').css('display', 'inline');
		$('#sidebar-custom-breaks-break-hidden').val('0');
	}
	return;
}

$('#sidebar-weekday-choice-list input[type=checkbox]').change(function(){

	var selectedDays = [];
	$('#sidebar-weekday-choice-list input[type=checkbox]:checked').each(function(){
		selectedDays.push($(this).val());
	});

	if(selectedDays.length == 0)
	{
		$('#sidebar-scheduler-week-days').html('Select Day(s)');
		return;
	}

	if(selectedDays.length == 1)
	{
		var weekday=new Array(8);
		weekday[1]="Monday";
		weekday[2]="Tuesday";
		weekday[3]="Wednesday";
		weekday[4]="Thursday";
		weekday[5]="Friday";
		weekday[6]="Saturday";
		weekday[7]="Sunday";
		$('#sidebar-scheduler-week-days').html(weekday[selectedDays[0]]);
		return;
	}

	var weekday=new Array(8);
	weekday[1]="Mon";
	weekday[2]="Tue";
	weekday[3]="Wed";
	weekday[4]="Thu";
	weekday[5]="Fri";
	weekday[6]="Sat";
	weekday[7]="Sun";

	var selectedDayStr = '';
	 $.each(selectedDays, function(i, value) {
	 	selectedDayStr += weekday[value]+", ";
    });

	selectedDayStr = selectedDayStr.substr(0,selectedDayStr.length-2);
	$('#sidebar-scheduler-week-days').html(selectedDayStr);
});



/** Break Netowrk Groups for scheduler.... 
* Asif
* 16th June 2014
*/
$('#sidebar-groups-choice-list').on('change','input',function(){
	if($(this).val() == 'ALL')
	{
		if($(this).is(":checked"))
		{
			$('#sidebar-groups-choice-list input[type=checkbox]').not('#sidebar-scheduler-groups-selectall').each(function(){
				$(this).attr("checked","checked").trigger("change");
			});
		} else
		{
			$('#sidebar-groups-choice-list input[type=checkbox]').not('#sidebar-scheduler-groups-selectall').each(function(){
				$(this).removeAttr("checked").trigger("change");
			});
		}
	} else
	{
		if($(this).is(":checked"))
		{
			if($('#sidebar-groups-choice-list input[type=checkbox]').not(':checked').not('#sidebar-scheduler-groups-selectall').length == 0)
			{
				$('#sidebar-scheduler-groups-selectall').attr("checked","checked");
			}

		} else
		{
			$('#sidebar-scheduler-groups-selectall').removeAttr("checked");
		}
	}

	var numSelectedGroups = $('#sidebar-groups-choice-list input[type=checkbox]:checked').not('#sidebar-scheduler-groups-selectall').length;

	if(numSelectedGroups ==0)
		$('#sidebar-scheduler-groups').html('Select Group(s)');
	else
		$('#sidebar-scheduler-groups').html(numSelectedGroups+' Group(s) Selected');

	updateShedulerNetworkList($(this).val(), (($(this).is(":checked"))?'ADD':'REMOVE'));

});




$('#sidebar-networks-choice-list').on('change','#sidebar-scheduler-network-selectall',function(){
	if($(this).is(':checked'))
		$('[id^="sidebar-scheduler-network-group-"]').each(function(){ $(this).attr('checked','checked').trigger('change'); });
	else
		$('[id^="sidebar-scheduler-network-group-"]').each(function(){ $(this).removeAttr('checked').trigger('change'); });
});

$('#sidebar-networks-choice-list').on('change','[id^="sidebar-scheduler-network-group-"]',function(){
	var id = $(this).val();
	if($(this).is(':checked'))
		$('.network-group-'+id).each(function(){ $(this).attr('checked','checked'); });
	else
		$('.network-group-'+id).each(function(){ $(this).removeAttr('checked'); });

	if($('[id^="sidebar-scheduler-network-group-"]').not(':checked').length == 0)
		$('#sidebar-scheduler-network-selectall').attr('checked','checked');
	else
		$('#sidebar-scheduler-network-selectall').removeAttr('checked');

	var selectNetsCount = $('[class^="network-group-"]:checked').length;
	if(selectNetsCount > 0)
	{
		$('#sidebar-scheduler-network').html(selectNetsCount+" Network(s) Selected");
	} else
	{
		$('#sidebar-scheduler-network').html("Select Network(s)");
	}
});

$('#sidebar-networks-choice-list').on('change','[class^="network-group-"]',function(){
	if($('.'+$(this).attr('class')).not(':checked').length ==0)
		$('#sidebar-scheduler-'+$(this).attr('class')).attr('checked','checked').trigger('change');
	else
	{
		$('#sidebar-scheduler-'+$(this).attr('class')+', #sidebar-scheduler-network-selectall').removeAttr('checked');
	}

	var selectNetsCount = $('[class^="network-group-"]:checked').length;
	if(selectNetsCount > 0)
	{
		$('#sidebar-scheduler-network').html(selectNetsCount+" Network(s) Selected");
	} else
	{
		$('#sidebar-scheduler-network').html("Select Network(s)");
	}
});

$('#sidebar-custom-breaks-instances-choice-list').on('change','.checkbox-custom-breaks',function(){
	refreshSideBarNetInstanceLabel('custom-breaks');
});

$('#sidebar-custom-titles-instances-choice-list').on('change','.checkbox-custom-titles',function(){
	refreshSideBarNetInstanceLabel('custom-titles');
});



//Programming update download sidebar-scheduler-network-selectall
$('#sidebar-update-scheduler-groups-choice-list').on('change','input',function(){
	if($(this).val() == 'ALL')
	{
		if($(this).is(":checked"))
		{
			$('#sidebar-update-scheduler-groups-choice-list input[type=checkbox]').not('#sidebar-update-scheduler-groups-selectall').each(function(){
				$(this).attr("checked","checked").trigger("change");
			});
		} else
		{
			$('#sidebar-update-scheduler-groups-choice-list input[type=checkbox]').not('#sidebar-update-scheduler-groups-selectall').each(function(){
				$(this).removeAttr("checked").trigger("change");
			});
		}
	} else
	{
		if($(this).is(":checked"))
		{
			if($('#sidebar-update-scheduler-groups-choice-list input[type=checkbox]').not(':checked').not('#sidebar-update-scheduler-groups-selectall').length == 0)
			{
				$('#sidebar-update-scheduler-groups-selectall').attr("checked","checked");
			}

		} else
		{
			$('#sidebar-update-scheduler-groups-selectall').removeAttr("checked");
		}
	}

	var numSelectedGroups = $('#sidebar-update-scheduler-groups-choice-list input[type=checkbox]:checked').not('#sidebar-update-scheduler-groups-selectall').length;

	if(numSelectedGroups ==0)
		$('#sidebar-update-scheduler-groups').html('Select Group(s)');
	else
		$('#sidebar-update-scheduler-groups').html(numSelectedGroups+' Group(s) Selected');

	updateChangeShedulerNetworkList($(this).val(), (($(this).is(":checked"))?'ADD':'REMOVE'));
});

$('#sidebar-update-scheduler-networks-choice-list').on('change','#sidebar-update-scheduler-network-selectall',function(){
	if($(this).is(':checked'))
		$('[id^="sidebar-update-scheduler-network-group-"]').each(function(){ $(this).attr('checked','checked').trigger('change'); });
	else
		$('[id^="sidebar-update-scheduler-network-group-"]').each(function(){ $(this).removeAttr('checked').trigger('change'); });
});

$('#sidebar-update-scheduler-networks-choice-list').on('change','[id^="sidebar-update-scheduler-network-group-"]',function(){
	var id = $(this).val();
	if($(this).is(':checked'))
		$('.update-scheduler-network-group-'+id).each(function(){ $(this).attr('checked','checked'); });
	else
		$('.update-scheduler-network-group-'+id).each(function(){ $(this).removeAttr('checked'); });

	if($('[id^="sidebar-update-scheduler-network-group-"]').not(':checked').length == 0)
		$('#sidebar-update-scheduler-network-selectall').attr('checked','checked');
	else
		$('#sidebar-update-scheduler-network-selectall').removeAttr('checked');

	var selectNetsCount = $('[class^="update-scheduler-network-group-"]:checked').length;
	if(selectNetsCount > 0)
	{
		$('#sidebar-update-scheduler-network').html(selectNetsCount+" Network(s) Selected");
	} else
	{
		$('#sidebar-update-scheduler-network').html("Select Network(s)");
	}
});

$('#sidebar-update-scheduler-networks-choice-list').on('change','[class^="update-scheduler-network-group-"]',function(){
	if($('.'+$(this).attr('class')).not(':checked').length ==0)
	{
		var gidAr = $(this).attr('class').split('-');
		var gid = gidAr[gidAr.length -1];
		$('#sidebar-update-scheduler-network-group-'+gid).attr('checked','checked').trigger('change');
	}
	else
	{
		var gidAr = $(this).attr('class').split('-');
		var gid = gidAr[gidAr.length -1];
		$('#sidebar-update-scheduler-network-group-'+gid+', #sidebar-update-scheduler-network-selectall').removeAttr('checked');
	}

	var selectNetsCount = $('[class^="update-scheduler-network-group-"]:checked').length;
	if(selectNetsCount > 0)
	{
		$('#sidebar-update-scheduler-network').html(selectNetsCount+" Network(s) Selected");
	} else
	{
		$('#sidebar-update-scheduler-network').html("Select Network(s)");
	}
});

$('#sidebar-update-scheduler-weekday-choice-list input[type=checkbox]').change(function(){

	var selectedDays = [];
	$('#sidebar-update-scheduler-weekday-choice-list input[type=checkbox]:checked').each(function(){
		selectedDays.push($(this).val());
	});

	if(selectedDays.length == 0)
	{
		$('#sidebar-update-scheduler-week-days').html('Select Day(s)');
		return;
	}

	if(selectedDays.length == 1)
	{
		var weekday=new Array(8);
		weekday[1]="Monday";
		weekday[2]="Tuesday";
		weekday[3]="Wednesday";
		weekday[4]="Thursday";
		weekday[5]="Friday";
		weekday[6]="Saturday";
		weekday[7]="Sunday";
		$('#sidebar-update-scheduler-week-days').html(weekday[selectedDays[0]]);
		return;
	}

	var weekday=new Array(8);
	weekday[1]="Mon";
	weekday[2]="Tue";
	weekday[3]="Wed";
	weekday[4]="Thu";
	weekday[5]="Fri";
	weekday[6]="Sat";
	weekday[7]="Sun";

	var selectedDayStr = '';
	 $.each(selectedDays, function(i, value) {
	 	selectedDayStr += weekday[value]+", ";
    });

	selectedDayStr = selectedDayStr.substr(0,selectedDayStr.length-2);
	$('#sidebar-update-scheduler-week-days').html(selectedDayStr);
});
