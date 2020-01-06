
	
		  ///////////////////////////////////
		 //   ADD SHOWS TO THE PROPOSAL   //
		///////////////////////////////////
		
		$('.ssevent:checkbox').change(function(e){
		
				var $input = $( this );
		
					
				if($input.is( ":checked" )){
				
					//ADD SHOWS
					$input.closest('div').css({'backgroundColor':'#ccc'});
					
					for(i=0; i<showid.length; i++){
						if(showid[i] == $input.attr('id')){
							showid.splice(i,1);
						}
					}
	
					showid.push($input.attr('id'));					
				}
	
				else{
	
					//REMOVE SHOWS
						
					//Turns the cell into its original color 
					
					if($input.closest('div').attr('class') == 'pLive'){
				        $input.closest('div').css('background', '#E4E4F7');		
					}
					else{
						if($input.closest('.show').css('background-color') === 'rgb(238, 238, 238)'){
				        	$input.closest('div').css('background', '#eee');						
						}
						else{
				        	$input.closest('div').css('background', '#fff');
						}
					}
					
					for(i=0; i<showid.length; i++){
						if(showid[i] == $input.attr('id')){
							showid.splice(i,1);
						}
					}					
					
				}
				
			selectorState();
			
		});
	