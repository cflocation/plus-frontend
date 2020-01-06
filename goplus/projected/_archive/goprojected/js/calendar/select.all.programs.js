  ////////////////////////////////////
 //   SELECT ALL VISIBLE PROGRAMS  //
////////////////////////////////////
$('#all_shows').change(function() {						
	    if(this.checked) {
	      // Check all *visible* checkboxes
	      $(".show input:visible").attr('checked', 'checked');
	      
	      $(".show input:visible").closest('div').css('background', '#ccc');
	      
			$(".show input:visible").each(function(indx){				
				showid[indx] 	= $(this).attr('id');
			});			
			
			if(showid.length > 0){
				//setTimeout(function() { sswin.externalAddLineToProposal(showid,zonename,zoneid) },100);
			}					
	    }	    
	    else {
	    
	        // Uncheck all checkboxes
	        	$(".show input:visible").removeAttr('checked');  
	        	
				showid = [];
	        	
	        		
				$(".show input:visible").each(function(indx){
				
					//Turns the cell into its original color "#eee"
					className = $(this).closest('div').attr('class');

					if(className == "pLive"){
				        $(this).closest('div').css({'background-color':'#E4E4F7'});		
					}
					
					else{								
								
						if($(this).closest('.show').css('background-color') === 'rgb(238, 238, 238)'){
				        	$(this).closest('div').closest('div').css('background', '#eee');						
						}
						else{
				        	$(this).closest('div').closest('div').css('background', '#fff');
						}	
					}
				
				});
					
				if(showid.length > 0){
					//setTimeout(function() { sswin.externalDeleteLineFromProposal(showid,zoneid) },100);
				}
	    }
   });	