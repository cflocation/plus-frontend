
  ////////////////////////////////////////////////////////////////////////////
 // GETS THE HIGHTEST CELL IN THE ROW AND MAKE THE OTHER THE SAME HEIGHT   //
////////////////////////////////////////////////////////////////////////////
function updateCellHeight(){
	
	$('.parent .weekrow').each(function (e) {

		week 	= $(this);
		var h	=	0;  
		
		$('.show', week).each(function(indx){

		   dayOfWeek = $(this);
		   
		   $(dayOfWeek).css({'height':'auto'});						

			thisheight = parseInt(String($(dayOfWeek).css('height')).replace('px',''));

		   if (thisheight > parseInt(h) && thisheight != 0 && thisheight != 15) {
				h		=	$(dayOfWeek).outerHeight();
		    }

		});
		
		if (h != 0 && h != 15)
			$('.show', week).css({'height':h});
	}); 
	
	return true;
}	