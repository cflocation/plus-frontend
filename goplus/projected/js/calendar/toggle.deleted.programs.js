
			///////////////////////////////////////
			// SHOW / HIDE DEAUTHORIZED SHOWS	//	
			/////////////////////////////////////
		
		$('#hidden_shows').click(function(){
			if($(this).is(':checked') ){
				$('.deauthorizedRecord:contains("Removed")').closest('.pNew,.SeriesPremiere,.SeasonPremiere,pLive').show();
	
				$('.pNew,.SeriesPremiere,.SeasonPremiere,.MoviePremiere,.pLive').each(function(){
				
					if($(this).css("border-color") == 'rgb(221, 221, 221)'){
						$(this).show();
					}
				});
				
			}
			else{
				//$('.deauthorizedRecord:contains("Removed")').closest('.pNew,.SeriesPremiere,.SeasonPremiere,.pLive').hide();
				$('.pNew,.SeriesPremiere,.SeasonPremiere,.MoviePremiere,.pLive').each(function(){
				
					if($(this).css("border-color") == 'rgb(221, 221, 221)'){
						$(this).hide();
					}	
			});
			updateCellHeight();
		}});