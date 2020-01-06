
//HIGHLIGTS THE TAB THAT CONTAIN SHOWS THAT ARE PART OF THE CURRECT SELECTED PROPOSAL
function highlightMonthTab(){

	$('.parent').each(function(indx){
	
		if($('input.ssevent[type=checkbox]:checked', this).length > 0 ){
			$('.tabNavigator:eq('+indx+')').closest('div').css({'backgroundColor':'#F75B22','color':'#fff'});	
		}			
	
	});
	
	return true;
}



function selectedTab(){

	$('.parent').each(function(indx){

		if( $(this).css('display') != 'none' ){

			var thisclass = String($('.tabNavigator:eq('+indx+')').closest('div').attr('class')).replace(' highlightedTab','');

			if(thisclass == 'topprojected'){
				
				$('.tabNavigator:eq('+indx+')').closest('div').css({'backgroundColor':'maroon','color':'white'});
			}
			else{
				$('.tabNavigator:eq('+indx+')').closest('div').css({'backgroundColor':'blue','color':'white'});			
			}					
								
		}

	});
	return true;
}



//HIGHLIGTS THE TAB THAT CONTAINS SEARCHED SHOWS FROMT HE ENTRY FIELD
function higlightFilteredTab(){

	resetTabsState();
	
	var selectednet = String($('#nets').find(":selected").text()).trim();
	var validnet	= selectednet.indexOf('-');
				
	$('.parent').each(function(indx){
		
		$(this).children('div.weekrow').children('div.show').children('div.dailyProgramming').children('div.calendar-program').each(function (){
			
			if( $(this).css('display') != 'none'){						

				$('.tabNavigator:eq('+indx+')').closest('div').addClass('highlightedTab');
				return false;
				
			}					
		});				
				
	});

	r = highlightMonthTab();		
	r = selectedTab();
}


function resetTabsState(){
	removeTabHighlight()
	$('.top').css({'backgroundColor':'#32639b'});
	$('.topprojected').css({'backgroundColor':'#E7F2F9','color':'#2277C2'});
	return true;
}



function removeTabHighlight(){
	
	$('.top').removeClass('highlightedTab');
	$('.topprojected').removeClass('highlightedTab');
	return true;
}