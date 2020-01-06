

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

	var stime 		= Date.parse($('#sTime').val());
	var etime 		= Date.parse($('#eTime').val());
	var programname = $('#searchShow').val().toLowerCase();
	var show 		= '';
	if(liveEvents.length == 0){
		
		$('.Live a.programTitle').each(function(){
			liveEvents.push($(this).parent().parent().prop('id')+$(this).siblings('.callsign').text()+$(this).siblings('.schedule').children('.starttimeclass').text()+$(this).text());
		});
	}

	
	if(programname.length >= 2){

		// Commented by Asif on 09/30/2018 Trello #631, resetToShowAll();

		$('.calendar-program').hide();		
		
		$('.programTitle').each(function () {
			
			t = filterSTime($(this).siblings('.schedule').children('.starttimeclass').text(),stime,etime);
			show = $(this).parent().parent().prop('id')+$(this).siblings('.callsign').text()+$(this).siblings('.schedule').children('.starttimeclass').text()+$(this).text();
			
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
	/*
	Commented by Asif on 09/30/2018 Trello #631
	else{
		filterPrograms();
	}*/

	filterPrograms(); // Added by Asif on 09/30/2018 Trello #631

	updateCellHeight();
	selectorState();
	higlightFilteredTab();	
	
}