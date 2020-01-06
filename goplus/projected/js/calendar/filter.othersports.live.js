
function filterOtherSportsLive(){
	
		//OTHER EVENTS LIVE
		$('.Live,.pLive').show();

		//HIDES MAIN SPORT EVENTS
		for(j=0;j<sportLiveEvents.length;j++){
			$('.programTitle:contains("'+sportLiveEvents[j]+'")').closest('.Live,.pLive').hide();						
		}
}



function optionOtherSportsLive(){
	
		// MARKS "OTHER SPORTS LIVE" OPTION FROM THE FILTER LIST AND CLEARS THE REST								
		if(selectedsport.indexOf('Other') != -1 && selectedOptions.length > 1 && otherSportsLive == 0){
					
			$('#ddcl-filterSportType-ddw input:checkbox').prop('checked', false)

			$('#filterSportType option').prop('selected', false);

			$('#filterSportType option:last').prop('selected', true);
			
			$('#ddcl-filterSportType-ddw input:checkbox[value="Other"]').prop('checked', true);	

			$('span.ui-dropdownchecklist-text').text('Other Sports Live').attr('title','Other Sports Live');

			otherSportsLive = 1;

		}
		// REMOVES "OTHER SPORTS LIVE" OPTION FROM THE FILTER LIST	
				
		else if(selectedsport.indexOf('Other') != -1 && selectedOptions.length > 1 && otherSportsLive == 1){
			
			for(j=0;j<filtering.length;j++){
				if(filtering[j] == 'Other')
					filtering.splice(j, 1);
			}
			
			//CLEARING FIRST OTION FROM SELECT LIST
			$('#filterSportType option:last').prop('selected', false);	

			//CLEARING CHECKBOX
			$('#ddcl-filterSportType-ddw input:checkbox[value="Other"]').prop('checked', false)
							
			//DISPLAYING LIST OF SELECTED SHOWS
			$('span.ui-dropdownchecklist-text').text(filtering.toString()).attr('title',filtering.toString());
			
			otherSportsLive = 0;
		}
			
		return ;
}