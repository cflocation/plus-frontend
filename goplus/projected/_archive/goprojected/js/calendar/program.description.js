		//Togle Episode Description (Shows games details individually)
		$('.programTitle').click(function(e){
			$(this).siblings('.allgamedetails').toggle();
			
			week = $(this).closest('.weekrow');
			
			$('.show', week).css({'height':'auto'});
			
			h1=0;
			
			$('.show', week).each(function(indx){
				thish = parseInt(String($(this).css('height')).replace('px',''));
				
					if(thish >parseInt(h1)){
						h1=thish;				
					}
				});
				
			$('.show', week).css({'height':h1});
			$(this).closest('.weekrow').css({'height':h1});			
			
		});