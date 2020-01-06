  ////////////////////////////////////////
 /// PROJECTED PROGRAMMING DISCLAIMER  //
////////////////////////////////////////
  
function showProjectedDisclaimer(){

	if($('.pNew:visible').length > 0 ||	$('.projected:visible').length > 0  || $('.premiereprojected:visible').length > 0){
		$('#disclaimer').closest('div').css({'background-color':'maroon', 'color':'white'});			
		$('#disclaimer').show();				
	}			
	else{
		$('#disclaimer').closest('div').css({'background-color':'white'});
		$('#disclaimer').hide();				
	}

	return true;
}


  /////////////////////////
 //	CLOSE DISCLAIMER  //
/////////////////////////


$('.closedisclaimer').click(function(e){
	$('#disclaimer').css({'height':'0px', 'width':'0px','left':'-1500px'});
	$('#disclaimer').text('');
	$('#closedisclaimer').css({'height':'0px', 'width':'0px'});

});