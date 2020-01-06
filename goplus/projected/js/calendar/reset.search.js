try {jQuery(function($) {
	// CLEARABLE INPUT
	function tog(v){return v?'addClass':'removeClass';} 

	$(document).on('input', '.clearable', function(){
			$(this)[tog(this.value)]('x');
		}).on('mousemove', '.x', function( e ){
				$(this)[tog(this.offsetWidth-18 < e.clientX-this.getBoundingClientRect().left)]('onX');   
		}).on('touchstart click', '.onX', function( ev ){
				ev.preventDefault();
				$(this).removeClass('x onX').val('').change();

				resetSearchShow();
	
	});
});
} catch (error) { throw error; }


$('#resetegrid').click(function(){
	resetCalendar();
});


function resetCalendar(){
	var r = true;
	$('#nets').val(0);
	$('#searchShow').val('');
	$('#sTime').val('6:00');
	$('#eTime').val('23:59');
	r = filterPrograms();
	r = resetTabsState();
	r = removeTabHighlight();
	r = resetTabNavigator();
	r = highlightMonthTab();
	r = resetEpisodes();
	r = updateCellHeight();
	r = selectorState();
	r =	higlightFilteredTab();
	return true;
}


function resetEpisodes(){
	
	$('.plus').show();
	$('.minus').hide();			
	$('.allgamedetails').css({'display':'none'});
}


function resetSearchShow(){
	r = filterPrograms();
	r = selectorState();
	return true;
}


function resetTabNavigator(){
	$('#boxBody div.parent').css({'display':'none'}); 
	$('#boxBody div.parent:eq(0)').css({'display':'block'}); 
	$('.tabNavigator:eq(0)').closest('div').css({'backgroundColor':'blue','color':'white'});		
	return true;
}