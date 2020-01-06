		
		  ///////////////////////
		 // APPROVING SHOWS   //
		///////////////////////
		
		
		$('#validate').click(function(){

			if(showid.length < 1){
				alert('No Shows are selected for Approval');
				return;
			}

			var showsclean = new Array();


			for(i=0; i<showid.length; i++){
			
				$('#'+showid[i]).closest('div .authorized').text("1");
			
				$('#'+showid[i]).closest('div').css({'border':'solid 3px #33cc00'});						
				
				thisid = String(showid[i]).split('-');
				
				allclean = thisid[0].substr(5,thisid[0].length-5)
				showsclean.push(allclean);
			}
			


			$.getJSON("cfc/projected.cfc?method=authorize&showids="+showsclean.join(), 
		 			function(data) {

						$('#tabcontainer').append('<div class=responsestatusmessage style=float:right; id=confirmationmsg><span>Records approved successfully</span><div>');
						msg = self.setInterval(
							function(){
										$('#confirmationmsg').remove();
										window.clearInterval(msg);
							},3000);		

						
					return false;});			
			
		});