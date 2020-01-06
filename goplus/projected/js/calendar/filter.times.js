
// FILTERING BY TIME FRAME
/* $('#sTime,#eTime').change(function(){
	//filterTimes();
	filterPrograms();
}); */

$(document).on('change','#sTime,#eTime', function (e) {	
	filterPrograms();	
});

		
function filterTimes(){

		var stime = $('#sTime').val();
		var etime = $('#eTime').val();
		var t 		  = '';
		var validTime = '';
		var refSTime  = '';		
		
		$('.starttimeclass').each(function(){
			t = $(this).text();
			
			if(t.indexOf(':') == -1){
				t = t.replace('P', ':00 PM').replace('A', ':00 AM');
			}
			else{
				if(t.indexOf('P') == -1){
					t = t.replace('A', ' AM')
				}
				else{
					t = t.split('P', ' PM');
				}
			}
			st = Date.parse(t);
			refSTime = Date.parse(stime);
			refETime = Date.parse(etime);

			if(st >= refSTime && st <= refETime){
				$(this).closest('.calendar-program').show();
			}
			else{
				$(this).closest('.calendar-program').hide();
			}
		});		
		
		updateCellHeight();
}


function filterSTime(t,stime,etime){

			if(t.indexOf(':') === -1){
				t = t.replace('P', ':00 PM').replace('A', ':00 AM');
			}
			else{
				if(t.indexOf('P') === -1){
					t = t.replace('A', ' AM')
				}
				else{
					t = t.replace('P', ' PM');
				}
			}

			var refTime = convertTime12to24(t);
			var st = new Date(2000,0,1,parseInt(refTime.hours),parseInt(refTime.minutes)); 

			if(st >= stime && st <= etime){
				return 1;
			}
			else{
				return 0;
			}
		
}

function convertTime12to24(time12h){
	
	var time24 		= time12h.trim().split(' ');

	var timeX 		= time24[0];
	var modifier 	= time24[1];
	
	var timeSplit	= timeX.split(':')
	var hours		= timeSplit[0];
	var minutes 	= timeSplit[1];

	if (hours === '12') {
    	hours = '00';
	}

	if (modifier === 'PM') {
    	hours = parseInt(hours, 10) + 12;
	}
	r 				= {};
	r.hours			= hours;
	r.minutes		= minutes;
	
	return r;
}