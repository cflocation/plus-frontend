			////////////////////////////////
		  //	DELETES THE SELECTED SHOW //
		 ////////////////////////////////
		$('#deleteshows').click(function(){
		
			if(showid.length == 0){
				alert("Select a show to remove")
				return;
			}
			
			myvar 				= confirm("The selected Show Will be deleted, \n Do you Want to continue?");
			cleanshowid 		= new Array();
			
			
			if(myvar){
				
				for(j=0; j<showid.length; j++){
					$('#'+showid[j]).closest('div').css({'display':'none','height':'0px'});
					$('#'+showid[j]).removeAttr('checked');

					thisid = String(showid[j]).split('-');
					allclean = thisid[0].substr(5,thisid[0].length-5)
					cleanshowid.push(allclean);
				}
				
				
				showid_temp = showid.slice(0);
				
				
				$('#undo').css({'display':'inline'});

				updateCellHeight();


		 		$.getJSON("cfc/projected.cfc?method=deleteshows&shows="+cleanshowid.join(), 
		 			function(data) {

						$('#tabcontainer').append('<div class=responsestatusmessage style=float:right; id=confirmationmsg><span>Record deleted successfully</span><div>');
						msg = self.setInterval(
							function(){
										$('#confirmationmsg').remove();
										window.clearInterval(msg);
							},3000);		

						
					return false;});

				return;
			}
			else{
				return;
			}
		
		});