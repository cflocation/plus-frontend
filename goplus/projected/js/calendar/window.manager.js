  /////////////////////////////////////////////////////////
 ///  RESIZES THE APP ACCORDINGLY TO THE WINDOW SIZE  ///
//////////////////////////////////////////////////////		
function windowManager(){
	//get the height and width of the content
	var winWidth  = Math.round($(window).width());

		var cellW 		= parseInt(winWidth/7);
				
		if(winWidth > 900){
		
		
		//SETS CELL WIDTH
			$('#boxBody').css('width', winWidth);
			$('.show').css('width',cellW-13);
			$('.dayofWeek').css('width',cellW-13)

		}
		else{
			$('#boxBody').css('width', '900px');
			$('.show').css('width', '100px');				
			$('.dayofWeek').css('width','100px')
		}		

}
