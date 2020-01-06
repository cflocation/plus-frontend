

var typingTimer;                //timer identifier
var doneTypingInterval = 300;  //time in ms, 5 second for example
var $input = $('#myInput');

$('#searchShow').on('keyup', function () {
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping, doneTypingInterval);
});

//on keydown, clear the countdown 
$('#searchShow').on('keydown', function () {
  clearTimeout(typingTimer);
});

//user is "finished typing," do something
function doneTyping () {
  filterByKeyword();
}


$('#searchShow').on("change", function(){
	
	filterByKeyword();

});


function filterByKeyword(){

	var stime 	  	= $('#sTime').val();
	var etime 	  	= $('#eTime').val();
	
	var refTime 	= stime.split(/[^0-9]/);
	stime 			= new Date(2000,0,1,parseInt(refTime[0]),parseInt(refTime[1])); 

	var refTime2 	= etime.split(/[^0-9]/);
	etime 			= new Date(2000,0,1,parseInt(refTime2[0]),parseInt(refTime2[1])-1); 
	
	var programname = $('#searchShow').val().toLowerCase();
	var show 		= '';
	if(liveEvents.length == 0){
		
		$('.Live a.programTitle').each(function(){
			liveEvents.push($(this).parent().parent().prop('id')+$(this).siblings('.callsign').text()+$(this).siblings('.schedule').children('.starttimeclass').text()+$(this).text());
		});
	}

	
	if(programname.length >= 2){

		resetToShowAll();

		$('.calendar-program').hide();		
		
		$('.programTitle').each(function () {
			
			t       = filterSTime($(this).siblings('.schedule').children('.starttimeclass').text(),stime,etime);
			show    = $(this).parent().parent().prop('id')+$(this).siblings('.callsign').text()+$(this).siblings('.schedule').children('.starttimeclass').text()+$(this).text();
			
			if($(this).parent().attr('class').replace('calendar-program ', '') != 'pLive'){

				if (String($(this).text()).trim().toLowerCase().indexOf(programname) != -1 && t ==1 ) 
			        $(this).closest('.calendar-program').show();				
			}
			else{
				if($.inArray(show,liveEvents) == -1 && t == 1 && String($(this).text()).trim().toLowerCase().indexOf(programname) != -1)
			        $(this).closest('.calendar-program').show();
			}
		});
	}
	else{
		filterPrograms();
	}

	updateCellHeight();
	selectorState();
	higlightFilteredTab();	
	
}