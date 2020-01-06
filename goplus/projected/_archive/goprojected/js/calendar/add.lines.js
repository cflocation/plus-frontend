  ///////////////////////////////////
 //   ADD SHOWS TO THE PROPOSAL   //
///////////////////////////////////
		
$('.ssevent:checkbox').change(function(e){
	
	var callsign 	= $(this).parent().siblings("span.callsign").text();
	
	if($('#proposalList').val() != 0 && $('#proposalList').val() != null){
		showid 		= new Array();
		showid[0] 	= $(this).attr('id');
		zoneid		= $('#zones').val();
		zonename	= $('#zones option:selected').text();
		var mixData = {};
		mixData.showId 		= showid;
		mixData.callsign	= callsign;
		mixData.proposalId	= sswin.proposalid;
		
		if($(this).is( ":checked" )){
			//ADD SHOWS
			sswin.externalAddLineToProposal(showid,zonename,zoneid);	
			$(this).closest('div').css({'backgroundColor':'#ccc'});
			sswin.mixTrack("Projected - Line Add",mixData);
		}

		else{

			//REMOVE SHOWS
				
			//Turns the cell into its original color "#eee"
			if($(this).closest('div').attr('class') == 'pLive'){
		      $(this).closest('div').css('background', '#E4E4F7');
			}			
			else {
				if($(this).closest('.show').css('background-color') === 'rgb(238, 238, 238)'){
		        	$(this).closest('div').css('background', '#eee');						
				}
				else{
		        	$(this).closest('div').css('background', '#fff');
				}
			}
			
			sswin.externalDeleteLineFromProposal(showid,zoneid);
		}
		
	}
	else{
			$(this).prop('checked', false);
			alert("Please Select or Create a Proposal that You wish to add to");
			return;
	}

	selectorState();
	
});