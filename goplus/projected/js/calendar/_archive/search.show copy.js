$('#searchShow').on("keyup change select", function(){
	
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
		
		$('.programTitle').each(function () {
			
			t = filterSTime($(this).siblings('.schedule').children('.starttimeclass').text(),stime,etime);
			show = $(this).parent().parent().prop('id')+$(this).siblings('.callsign').text()+$(this).siblings('.schedule').children('.starttimeclass').text()+$(this).text();
			
			if($(this).parent().attr('class').replace('calendar-program ', '') == 'pLive'){
				
				if($.inArray(show,liveEvents) == -1 && t == 1 && String($(this).text()).trim().toLowerCase().indexOf(programname) != -1)
			        $(this).closest('.Live,.MoviePremiere,.SeriesPremiere,.SeriesFinale,.SeasonPremiere,.SeasonFinale,.pNew,.projected,.pLive,.premiereprojected').show();
			    else
			        $(this).closest('.Live,.MoviePremiere,.SeriesPremiere,.SeriesFinale,.SeasonPremiere,.SeasonFinale,.pNew,.projected,.pLive,.premiereprojected').hide();
			}
			else{
				if (String($(this).text()).trim().toLowerCase().indexOf(programname) != -1 && t ==1 ) 
			        $(this).closest('.Live,.MoviePremiere,.SeriesPremiere,.SeriesFinale,.SeasonPremiere,.SeasonFinale,.pNew,.projected,.pLive,.premiereprojected').show();
			    else
			        $(this).closest('.Live,.MoviePremiere,.SeriesPremiere,.SeriesFinale,.SeasonPremiere,.SeasonFinale,.pNew,.projected,.pLive,.premiereprojected').hide();
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