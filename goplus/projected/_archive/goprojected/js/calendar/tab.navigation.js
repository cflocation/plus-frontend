  //////////////////////////
 //   NAVIGATOR EFFECT	 //
//////////////////////////

$('.top, .topprojected').click(function(){  
	
	var r = true;
	
	$('#boxBody div.parent').css({'display':'none'}); 
	                
	$('#boxBody div.parent:eq(' + $('.top, .topprojected').index(this) + ')').css({'display':'block'}); 
		
	r = selectorState();

	r = updateCellHeight();

	r = showProjectedDisclaimer();
	
	r = higlightFilteredTab();
	
 });
	

// TURNING pLive THE LIVES UNDER THE PROJECTED AREA
 $('.topprojected').click(function(){
 	$('.Live:visible').removeClass("Live").addClass("pLive");
 });