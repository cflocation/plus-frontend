	//RESET STATE OF THE CELLS IN THE GRID
	function resetCells(){
	
			$('.show input:checked').each(function(e){
								
				if($(this).closest('.show').css('background-color') === 'rgb(238, 238, 238)'){
			        	$(this).closest('div').css('background', '#eee');						
				}
				else{
			        	$(this).closest('div').css('background', '#fff');
				}	

			});
			
			$('.show input:checkbox').removeAttr('checked');
			
			return false;
	}
