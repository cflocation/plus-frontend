  //////////////////////////
 //   NAVIGATOR EFFECT	 //
//////////////////////////

$(document).on('click','.top, .topprojected',function(){  
	calendarMonth 		= this;
	selectCalendarMonth(this);
	
 });
	

// TURNING pLive THE LIVES UNDER THE PROJECTED AREA
 $('.topprojected').click(function(){
 	$('.Live:visible').removeClass("Live").addClass("pLive");
 });


function selectCalendarMonth(sTab){
	var r = true;
	
	$('#boxBody div.parent').css({'display':'none'}); 
	                
	$('#boxBody div.parent:eq(' + $('.top, .topprojected').index(sTab) + ')').css({'display':'block'}); 
		
	r = selectorState();

	r = updateCellHeight();

	r = showProjectedDisclaimer();
	
	r = higlightFilteredTab();	
}