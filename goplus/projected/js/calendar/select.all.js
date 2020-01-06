$('#select_all').change(function() {

	if($('#proposalList').val() != 0 && $('#proposalList').val() != null){
	
		showid 		= new Array();
		zoneid		= $('#zones').val();
		zonename		= $('#zones option:selected').text();
						
	    if($(this).is( ":checked" )) {

	      // Check all *visible* checkboxes
	      $(".show input:visible").prop('checked', true);
	      
	      $(".show input:visible").closest('div').css('background', '#ccc');
	      
			$(".show input:visible").each(function(indx){				
					showid.push($(this).attr('id'));
			});
			
			var n					=	1;
			var numberOfShows = 	showid.length;
			var tempShows		= 	[];					
			sswin.externalAddLineToProposal(showid,zonename,zoneid);
			
			try{
				var selectedOpt = []; //SELECTED OPTION(S)
				$('div.ui-dropdownchecklist-item').find(':checkbox:checked').each(function(){
					selectedOpt.push($(this).val());
				});
				sswin.mixTrack("Projected - Add All",{"zoneName":zonename, "zoneId":zoneid,"filters":selectedOpt});
			}
			catch(e){}			
			
			/*if(showid.length > 0){
			
				for(i=0; i<numberOfShows; i++){

					tempShows.push(showid[i]);

					if(numberOfShows-1 == i || 10*n == i){							
						sswin.externalAddLineToProposal(tempShows,zonename,zoneid);
						tempShows.length = 0;
						n++;						
					}
				
				}
			}*/					
			
	    } 
	    else {
	        // Uncheck all checkboxes
	        	$(".show input:visible").prop('checked', false);  	
				$(".show input:visible").each(function(indx){
				
					className = $(this).closest('.show').attr('class');
					if(className == "pLive"){
				        $(this).closest('div').css({'background-color':'#E4E4F7'});		
					}
					
					else{								
				
						//Turns the cell into its original color "#eee"
						if($(this).closest('.show').css('background-color') === 'rgb(238, 238, 238)'){
				        	$(".show input:visible").closest('div').css('background', '#eee');						
						}
						else{
				        	$(".show input:visible").closest('div').css('background', '#fff');
						}
					}
					showid[indx] 	= $(this).attr('id');
					});
					
				if(showid.length > 0){
					//sswin.externalDeleteLineFromProposal(showid,zoneid);
					setTimeout(function() { sswin.externalDeleteLineFromProposal(showid,zoneid) },100);
				}
	    }
   }
    
	else{
			$(this).prop('checked', false);
			alert("Please Select or Create a Proposal that You wish to add to");
			return;					
	}		    
    
});

