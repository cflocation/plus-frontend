function highlightTab(){
	var x;
	
	for(var j=0; j<53; j++){

		if($('#outerContainer'+j) !== null && j === selectedTab){
			$('#w'+j).removeClass("activeWeek");			
		}
		else{
			if($('#outerContainer'+j) !== null && j !== selectedTab){

				x = $('#outerContainer'+j+' .innerContainer .programCell .cellText').find('.fa-check-square');
				
				if(x.length > 0 && !($('#w'+j).parent('li').hasClass('ui-tabs-active'))){
					$('#w'+j).addClass("activeWeek");
				}
				else if(j != selectedTab){
					$('#w'+j).removeClass("activeWeek");
				}
			}
		}
	}
	//highlightMonthTab();
	return false;
};
	
	
	
function highlightMonthTab(){
	var cont,wk;
	$('.mTab').each(function(i,m){
		cont = $(this).context.hash.replace('#','');
		wk = $('#'+cont).find('a.ui-tabs-anchor').hasClass('activeWeek');
		if(wk){
			$(this).addClass('activeWeek');
		}
		else{
			$(this).removeClass('activeWeek');			
		}
	});
	return false;
};