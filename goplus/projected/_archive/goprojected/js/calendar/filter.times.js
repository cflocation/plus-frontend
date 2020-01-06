
// FILTERING BY TIME FRAME
$('#sTime,#eTime').change(function(){
	//filterTimes();
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

	var r = 0;
	if(t.indexOf(':') == -1){
		if(t.indexOf('12M') === -1){
			t = t.replace('P', ':00 PM').replace('A', ':00 AM');
		}		
		else{
			t = '12:00 AM';
		}
	}
	else{
		if(t.indexOf('P') == -1){
			t = t.replace('A', ' AM')
		}
		else{
			t = t.replace('P', ' PM');
		}
	}
		
	var hours = Number(t.match(/^(\d+)/)[1]);
	var minutes = Number(t.match(/:(\d+)/)[1]);
	var AMPM = t.match(/\s(.*)$/)[1];
	if(AMPM == "PM" && hours<12) hours = hours+12;
	if(AMPM == "AM" && hours==12) hours = hours-12;
	var sHours = hours.toString();
	var sMinutes = minutes.toString();
	if(hours<10) sHours = "0" + sHours;
	if(minutes<10) sMinutes = "0" + sMinutes;
	

	var x 	= new Date(); 
	st 		= new Date(x.getFullYear(),x.getMonth(),x.getDate(),sHours,sMinutes);	

	if(st >= stime && st <= etime){
		r =  1;
	}

	return r;
		
		
}