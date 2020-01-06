// FILTERING SELECTED SPORT TRIGGER

$(document).on('change', '#filterSportType', function (e) {	
	filterPrograms();	
});


function filterPrograms(){

	selectedOptions 		= []; //SELECTED OPTION(S)
	
	$('div.ui-dropdownchecklist-item').find(':checkbox:checked').each(function(){
		selectedOptions.push($(this).val());
	});

	selectedsport = selectedOptions.join(",");
	
	var r 		  	= true;			
	//var stime 	  = Date.parse($('#sTime').val());
	//var etime 	  = Date.parse($('#eTime').val());

	var stime 	  	= $('#sTime').val();
	var etime 	  	= $('#eTime').val();
	
	var refTime 	= stime.split(/[^0-9]/);
	stime 			= new Date(2000,0,1,parseInt(refTime[0]),parseInt(refTime[1])); 	

	var refTime2 	= etime.split(/[^0-9]/);
	etime 			= new Date(2000,0,1,parseInt(refTime2[0]),parseInt(refTime2[1])-1); 	
	
	
	var n 		  = 0;

	while(filtering.length > 0){
	   filtering.pop();
	}

	if(selectedOptions.length == 1 && selectedOptions[0] == 'All'){
		filtering[0] = 'All';
	}
	else{
		for(j=0; j<selectedOptions.length;j++){
			if(selectedOptions[j] != 'All'){
				filtering[n] = selectedOptions[j];
				n++;
			}
		}
	}
	

	//checking status of ALL option from the filter and then status of Other Sports option from the filter
	$.when(optionAllPrograms()).then(optionOtherSportsLive());

	//TO START WE HIDE ALL
	$('.calendar-program').hide();


	// -> ALL SHOWS VISIBLE
	if(filtering[0] == 'All'){
		
		$('.calendar-program').each(function(){
			t = filterSTime($(this).children('.schedule').children('.starttimeclass').text(),stime,etime);
			if(t == 1)
				$(this).show();
		});
		
		filterNetworks();
	}
	else{
		// -> MULTIPLE SHOW TYPES
		if(selectedsport.search('Other') != -1 && otherSportsLive == 1){			
			$.when(filterOtherSportsLive()).then(filterNetworks());
		}
		else{
				
			// PACKAGES						
			if(filtering.toString().indexOf('package') != -1){
				$('.packageflag:not(:empty)').each(function(){
					
					t = filterSTime($(this).parent().siblings('.schedule').children('.starttimeclass').text(),stime,etime);
					if(t == 1)
						$(this).closest('.pLive,.projected').show();
				});
			}

			//GRACENOTE LIVE SHOWS					
			if(liveEvents.length == 0){
				$('.Live a.programTitle').each(function(){
					liveEvents.push($(this).parent().parent().prop('id')+$(this).siblings('.callsign').text()+$(this).siblings('.schedule').children('.starttimeclass').text()+$(this).text());
					if($.inArray($(this).text(),filtering) != -1){	
						t = filterSTime($(this).siblings('.schedule').children('.starttimeclass').text(),stime,etime);
						if(t == 1)
							$(this).closest('.Live').show();
					}
				});
			}
			else{
				$('.Live a.programTitle').each(function(){
					if($.inArray($(this).text(),filtering) != -1){	
						t = filterSTime($(this).siblings('.schedule').children('.starttimeclass').text(),stime,etime);
						if(t == 1)
							$(this).closest('.Live').show();
					}
				});
			}
						
			// PROJECTED LIVE
			$('.pLive a.programTitle').each(function(){
				
				if($.inArray( $(this).text().trim(), filtering ) != -1){
					t = filterSTime($(this).siblings('.schedule').children('.starttimeclass').text(),stime,etime);
					if(t == 1 && $.inArray($(this).parent().parent().prop('id')+$(this).siblings('.callsign').text()+$(this).siblings('.schedule').children('.starttimeclass').text()+$(this).text(),liveEvents) == -1){
						$(this).closest('.pLive').show();
					}
				}
			});
					
			// TMS PREMIERES
			$('.SeriesPremiere,.MoviePremiere,.SeriesFinale,.SeasonPremiere,.SeasonFinale').each(function(){
				for(m=0;m<filtering.length;m++){
					if(String($(this).attr('class')).indexOf(filtering[m]) != -1){
						t = filterSTime($(this).find('.starttimeclass').text(),stime,etime);
						if(t == 1){
							$(this).show();
							break;
						}
					}
				}
			});		
						
			// PACKAGES
			$('.pLive').each(function(){
			if(selectedsport.indexOf(String($(this).attr('class'))) != -1){
					t = filterSTime($(this).find('.starttimeclass').text(),stime,etime);
					if(t == 1)
						$(this).show();
				}
			});
	
			// PREMIERE PROJECTED
			$('.premiereprojected').each(function(){
				for(m=0;m<filtering.length;m++){
					if(String($(this).attr('class')).indexOf(filtering[m]) != -1){
						t = filterSTime($(this).find('.starttimeclass').text(),stime,etime);
						if(t == 1){	
							$(this).show();
							break;
						}
					}
				}
			});

			// MOVIE PREMIERE PROJECTED
			$('.MoviePremiere').each(function(){
				for(m=0;m<filtering.length;m++){
					if(String($(this).attr('class')).indexOf(filtering[m]) != -1){
						t = filterSTime($(this).find('.starttimeclass').text(),stime,etime);
						if(t == 1){	
							$(this).show();
							break;
						}
					}
				}
			});

			
			// PROJECTED 	
			$('.projected').each(function(){
				if(selectedsport.indexOf('projected') != -1){
					t = filterSTime($(this).find('.starttimeclass').text(),stime,etime);
					if(t == 1)
						$(this).show();
				}
			});

			// PROJECTED NEW 	
			$('.pNew').each(function(){
				if(selectedsport.indexOf('pNew') != -1){
					t = filterSTime($(this).find('.starttimeclass').text(),stime,etime);
					
					if(t == 1)
						
						$(this).show();
				}
			});
								
			r = filterNetworks();
			
		}
	}
	

	try{
		var netFilter 		=  $('#nets').find(":selected").text();
		var mixData 		= {};
		mixData.filters 	= selectedOptions;
		mixData.startTime 	= $('#sTime').val();
		mixData.endTime 	= $('#eTime').val();
		mixData.callsign 	= netFilter;			
		sswin.mixTrack("Projected - Filter",mixData);
	}
	catch(e){}	
	showProjectedDisclaimer();
}



function optionAllPrograms(){

			// REMOVES "SHOW ALL" OPTION FROM THE FILTER LIST
			
			if(selectedsport.indexOf('All') != -1 && selectedOptions.length > 1 && allShows == 1){
				
				//CLEARING FIRST OTION FROM SELECT LIST
				$('#filterSportType option:first').removeAttr('selected');

				//CLEARING CHECKBOX
				$('#ddcl-filterSportType-ddw input:checkbox[value="All"]').prop('checked', false)
								
				//DISPLAYING LIST OF SELECTED SHOWS
				$('span.ui-dropdownchecklist-text').text(filtering.toString()).attr('title',filtering.toString());
				
				allShows = 0;
			}
			

			// MARKS "SHOW ALL" OPTION FROM THE FILTER LIST AND CLEARS THE REST						
			else if(selectedsport.indexOf('All') != -1 && selectedOptions.length > 1 && allShows == 0){


				while(filtering.length > 0){
			   	filtering.pop();
				}
		
				filtering[0] = selectedsport = 'All';
				
				//CLEARING ALL SELECT LIST and ALL CHECKBOXES
				$('#filterSportType option').removeAttr('selected');
				
				$('#ddcl-filterSportType-ddw input:checkbox').prop('checked', false)

				//SELECTING FIRST ELEMENT OF THE SELECT LIST AND CHECKBOX				
				$('#filterSportType option:first').attr('selected',true);
				
				$('#ddcl-filterSportType-ddw input:checkbox:first').prop('checked', true);

				$('span.ui-dropdownchecklist-text').text('All').attr('title','All');
				
				allShows = 1;
			}
			
			return false;

}

