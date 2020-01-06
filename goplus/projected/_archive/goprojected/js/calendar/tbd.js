function tbd(){
		$('.tbd').each(function(){
			if(parseFloat($(this).text()) > 17){
				$(this).siblings('span.starttimeclass').hide();			
				$(this).css({'display':'inline'});
				$(this).addClass('tbd_on');
				$(this).text('TBD');
			}
		});
}