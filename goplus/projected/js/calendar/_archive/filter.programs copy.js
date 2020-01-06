// FILTERING SELECTED SPORT TRIGGER

$('#filterSportType').change(function(e){		
	filterPrograms();	
});



function filterPrograms(){

//selectedsport 	 		= String($('#filterSportType').val()); // SELECTED OPTION LIST BASE
	selectedOptions 		= [];//String(selectedsport).split(","); //SELECTED OPTION CONVERTED IN ARRAY
	
	$('div.ui-dropdownchecklist-item').find(':checkbox:checked').each(function(){
		selectedOptions.push($(this).val());
	});

	selectedsport = selectedOptions.join(",");
	
	var r 					= true;			
	var stime 				= Date.parse($('#sTime').val());
	var etime 				= Date.parse($('#eTime').val());

	while(filtering.length > 0){
	   filtering.pop();
	}
	
	//if($('#filterSportType').val() != null){
		var n = 0;

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
	//}
	

	//checking status of ALL option from the filter
	r = optionAllPrograms();

	//checking status of Other Sports option from the filter
	r = optionOtherSportsLive();

	$('.calendar-program').hide();

	//console.log(filtering);

	// -> ALL SHOWS VISIBLE
	if(filtering[0] == 'All'){

		//$('.Live,.MoviePremiere,.SeriesPremiere,.SeriesFinale,.SeasonPremiere,.SeasonFinale,.pNew,.projected,.pLive,.premiereprojected').show();
		
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
					//if(filtering.toString().indexOf($(this).text()) != -1){
					if($.inArray($(this).text(),filtering) != -1){	
						t = filterSTime($(this).siblings('.schedule').children('.starttimeclass').text(),stime,etime);
						if(t == 1)
							$(this).closest('.Live').show();
					}
				});
			}
			else{
				$('.Live a.programTitle').each(function(){
					//if(filtering.toString().indexOf($(this).text()) != -1){
					if($.inArray($(this).text(),filtering) != -1){	
						t = filterSTime($(this).siblings('.schedule').children('.starttimeclass').text(),stime,etime);
						if(t == 1)
							$(this).closest('.Live').show();
					}
				});
			}
						
			// PROJECTED LIVE
			$('.pLive a.programTitle').each(function(){
				
				if($.inArray( $(this).text(), filtering ) != -1){
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
	
	//ADJUST CELL HEIGHT			
	//r = updateCellHeight();
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

