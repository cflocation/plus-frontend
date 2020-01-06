		$('.minus').click(function(){
			$(this).toggle();
			$('.plus').toggle();
			$('.allgamedetails').css({'display':'none'});
			updateCellHeight();
		});
		
		
		$('.plus').click(function(){
			$(this).toggle();
			$('.minus').toggle();			
			$('.allgamedetails').css({'display':'block'});
			updateCellHeight();			
		});