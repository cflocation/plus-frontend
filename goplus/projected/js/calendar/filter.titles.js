// FILTERING BY TITLE
		
function filterTitles(title){

	$('.programTitle').each(function(){
		if(String($(this).text()).trim().toLowerCase().indexOf(title) == -1){
			$(this).closest('.calendar-program').hide();
		}
	});
	
	higlightFilteredTab();
}
		
		