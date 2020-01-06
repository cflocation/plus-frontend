
// FILTERING BY NETS
$(document).on('change', '#nets', function (e) {	
	//var networkFilter =  $('#nets').find(":selected").text();	
	filterPrograms();	
});



// FILTERING BY STATIONS		
function filterNetworks(){
		var networkFilter =  $('#nets').find(":selected").text();
		var title =  String($('#searchShow').val().toLowerCase()).trim();
		var r = true;
		
		if(networkFilter.indexOf('-') == -1){
			$('.callsign').each(function(){
				if($(this).text().trim() != networkFilter){
					$(this).closest('.calendar-program').hide();
				}
			});
		}
		else{
			r = removeTabHighlight();			
		}

		//FILTERING BY TITLE
		if(title.length >= 2){
			filterTitles(title);
			return false;
		}
		
		higlightFilteredTab();
		updateCellHeight();
}
	
		
function resetToShowAll(){
	
	$('div.ui-dropdownchecklist-item').find(':checkbox:checked').each(function(){
		$(this).prop('checked',false);
	});
	
	$('.ui-state-default').children('span').text('Show All').attr('title','Show All');
	$('div.ui-dropdownchecklist-item').find(':checkbox').first('checkbox').prop('checked',true);
	$('#filterSportType').val('All');
	
	return true;
}