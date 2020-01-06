	var selectedPanel = "outerContainer0";
	var panelNumber   = 0;				
	var panelState = new Array(0,0,0,0,0,0,0,0,0); 
				
	function setWeekNum(panelNum){
		selectedPanel = "outerContainer"+String(panelNum);
		panelNumber = Number(panelNum);
		updateCheckBoxState();
	}



	//SELECTS THE BOXES BY GROUP 
	function selectAllCheckBoxes(){
		if($('#proposalList').val() == 0 || $('#proposalList').val() == null || $('#proposalList').val() == ""){
			$('#selectAllBoxes').prop('checked',false);
			alert("Please Select or Create a Proposal that You wish to add to")
			return;
		}


		if(!$('#selectAllBoxes').is(':checked')){
			unselectAllCheckBoxes();
			return;
		}
		
		var x = $('#'+selectedPanel+' :checkbox')
		var zoneid = $('#zones').val(); 
		var zonename = $('#zones option:selected').text();		
		var showid = new Array();						



		switch($('#highlightThisType').val()){
				
			case 'Sports Events Live':
						for(var i=0; i<x.length; i++){
							programDetail = String(x[i].name).split("|");
							if(x[i].checked == false && programDetail[3] =='Live' && programDetail[2] == 'sports event'){
								x[i].checked = true;
								$(x[i]).closest('.programCell').css({'backgroundColor':'#ccc'});
								showid[i] = x[i].id;
							}
						}						
				break;
			
			case 'Sports NonEvents Live':
						for(var i=0; i<x.length; i++){
							programDetail = String(x[i].name).split("|");							
							if(x[i].checked == false && programDetail[3] =='Live' && programDetail[2] != 'sports event'){
								x[i].checked = true;
								$(x[i]).closest('.programCell').css({'backgroundColor':'#ccc'});										
								showid[i] = x[i].id;
							}
						}						
				break;
											
			case 'Finales':						
						for(var i=0; i<x.length; i++){
							programDetail = String(x[i].name).split("|");					
							if(x[i].checked == false && (programDetail[0] == 'Series Finale' || programDetail[0] == 'Season Finale')){
								x[i].checked = true;
								$(x[i]).closest('.programCell').css({'backgroundColor':'#ccc'});											
								showid[i] = x[i].id;
							}
						}							
				break;
			case 'Movies':					
						for(var i=0; i<x.length; i++){
							if(x[i].checked == false && (String(x[i].id).substr(5,2) == 'MV')){
								x[i].checked = true;
								$(x[i]).closest('.programCell').css({'backgroundColor':'#ccc'});									
								showid[i] = x[i].id;
							}
						}							
				break;
			case 'Movie Premieres':					
						for(var i=0; i<x.length; i++){
							programDetail = String(x[i].name).split("|");							
							if(x[i].checked == false && String(x[i].id).substr(5,2) == 'MV' && programDetail[0] == 'Premiere'){
								x[i].checked = true;
								$(x[i]).closest('.programCell').css({'backgroundColor':'#ccc'});											
								showid[i] = x[i].id;
							}
						}							
				break;							
			case 'Only New':
						for(var i=0; i<x.length; i++){
							programDetail = String(x[i].name).split("|");							
							if(x[i].checked == false && programDetail[1] =='New'){
								x[i].checked = true;
								$(x[i]).closest('.programCell').css({'backgroundColor':'#ccc'});
								showid[i] = x[i].id;
							}
						}							
				break;		
			case 'Premieres':						
						for(var i=0; i<x.length; i++){
							programDetail = String(x[i].name).split("|");							
							if(x[i].checked == false && (programDetail[0] == 'Series Premiere' || programDetail[0] == 'Season Premiere')){
								x[i].checked = true;
								$(x[i]).closest('.programCell').css({'backgroundColor':'#ccc'});
								showid[i] = x[i].id;
							}
						}							
				break;																																		
			case 'All Shows':
						for(var i=0; i<x.length; i++){
							programDetail = String(x[i].name).split("|");							
							if(x[i].checked == false){
								x[i].checked = true;
								$(x[i]).closest('.programCell').css({'backgroundColor':'#ccc'});											
								showid[i] = x[i].id;
							}
						}		
				break;
		}
		
		try{if(showid.length > 0){sswin.externalAddLineToProposal(showid,zonename,zoneid);}}
		catch(err){}

		panelState[panelNumber] = 1;
	}


			
	function unselectAllCheckBoxes(){
	
		var x = $('#'+selectedPanel+' :checkbox')
		
		var showid  = [];
		var zoneid 	= $('#zones').val(); 		
						
		switch(document.getElementById('highlightThisType').value){
			case 'Sports Events Live':
							for(var i=0; i<x.length; i++){
								programDetail = String(x[i].name).split("|");												
								if(x[i].checked == true && programDetail[3] =='Live' && programDetail[2] == 'sports event'){
									x[i].checked = false;
									$(x[i]).closest('.programCell').css({'backgroundColor':'#ffffaa'});
									showid[i] = x[i].id;
								}
							}
			break;
			case 'Sports NonEvents Live':
							for(var i=0; i<x.length; i++){
								programDetail = String(x[i].name).split("|");												
								if(x[i].checked == true && programDetail[3] =='Live' && programDetail[2] != 'sports event'){
									x[i].checked = false;
									$(x[i]).closest('.programCell').css({'backgroundColor':'#ffffff'});
									showid[i] = x[i].id;										
								}
							}
			break;						
			case 'Finales':
							for(var i=0; i<x.length; i++){
								programDetail = String(x[i].name).split("|");												
								if(x[i].checked == true && (programDetail[0] == 'Series Finale' || programDetail[0] == 'Season Finale')){
									x[i].checked = false;
									$(x[i]).closest('.programCell').css({'backgroundColor':'#ffffff'});												
									showid[i] = x[i].id;										
								}
							}																											
			break;
			case 'Movies':
							for(var i=0; i<x.length; i++){
								programDetail = String(x[i].name).split("|");												
								if(x[i].checked == true && (String(x[i].id).substr(5,2) == 'MV')){
									x[i].checked = false;
									$(x[i]).closest('.programCell').css({'backgroundColor':'#ffffff'});												
									showid[i] = x[i].id;
								}
							}										
			break;
			case 'Movie Premieres':
							for(var i=0; i<x.length; i++){
								programDetail = String(x[i].name).split("|");												
								if(x[i].checked == true && (String(x[i].id).substr(5,2) == 'MV' && programDetail[0] == 'Premiere')){
									x[i].checked = false;
									$(x[i]).closest('.programCell').css({'backgroundColor':'#ffffff'});												
									showid[i] = x[i].id;
								}
							}										
			break;						
			case 'Only New':
							for(var i=0; i<x.length; i++){
								programDetail = String(x[i].name).split("|");												
								if(x[i].checked == true && programDetail[1] =='New'){
									x[i].checked = false;
									$(x[i]).closest('.programCell').css({'backgroundColor':'#ffffff'});												
									showid[i] = x[i].id;								
								}
							}																											
			break;		
			case 'Premieres':
							for(var i=0; i<x.length; i++){
								programDetail = String(x[i].name).split("|");												
								if(x[i].checked == true && (programDetail[0] == 'Series Premiere' || programDetail[0] == 'Season Premiere')){
									x[i].checked = false;
									$(x[i]).closest('.programCell').css({'backgroundColor':'#ffffff'});												
									showid[i] = x[i].id;
								}
							}																	
			break;																																		
			case 'All Shows' :
							for(var i=0; i < x.length; i++){
								
								if(x[i].checked == true){
									x[i].checked = false;
									
									programDetail = String(x[i].name).split("|");
													
									if(programDetail[3] =='Live' && programDetail[2] == 'sports event'){
										$(x[i]).closest('.programCell').css({'backgroundColor':'#ffffaa'});
									}
									else{
										$(x[i]).closest('.programCell').css({'backgroundColor':'#ffffff'});																								
									}

									showid[i] = x[i].id;									
								}
							}
				break;
		}
		try{if(showid.length > 0){sswin.externalDeleteLineFromProposal(showid,zoneid);}}
		catch(err){}
		
		
		panelState[panelNumber] = 0;

	}

				
				

	function checkAllState(){
	
		var x = $('#'+selectedPanel+' :checkbox')
		var inputId;							

		switch(document.getElementById('highlightThisType').value){
			
			case 'Sports Events Live':
						var anyLive = 0;
						for(var i=0; i<x.length; i++){
							programDetail = String(x[i].name).split("|");		
							if(programDetail[3] =='Live' && programDetail[2] == 'sports event'){
								anyLive ++;
								if(x[i].checked == false){
									panelState[panelNumber] = 0;						
									$('#selectAllBoxes').prop('checked',false);	
									return;
								}
							}
						}											
						if(anyLive == 0){
							panelState[panelNumber] = 0;						
							$('#selectAllBoxes').prop('checked',false);	
							return;
						}
			break;
				
			case 'Finales':
							var anyFinale = 0;
							for(var i=0; i<x.length; i++){
								programDetail = String(x[i].name).split("|");											
								if(programDetail[0] == 'Series Finale' || programDetail[0] == 'Season Finale'){
									anyFinale++;
									if(x[i].checked == false){
										panelState[panelNumber] = 0;						
										$('#selectAllBoxes').prop('checked',false);	
										return;
									}
								}
							}	

							if(anyFinale == 0){
								panelState[panelNumber] = 0;						
								$('#selectAllBoxes').prop('checked',false);	
								return;
							}																	
				break;
				
			case 'Movies':
							var anyMovie = 0;						
							for(var i=0; i<x.length; i++){
								if(String(x[i].id).substr(5,2) == 'MV'){
									anyMovie ++;
									if(x[i].checked == false){											
										panelState[panelNumber] = 0;						
										$('#selectAllBoxes').prop('checked',false);	
										return;
									}
								}
							}

							if(anyMovie == 0){
								panelState[panelNumber] = 0;						
								$('#selectAllBoxes').prop('checked',false);	
								return;
							}

				break;
				
			case 'Movie Premieres':
							var anyMoviePremiere = 0;						
							for(var i=0; i<x.length; i++){
								programDetail = String(x[i].name).split("|");	
								if(String(x[i].id).substr(5,2) == 'MV' && programDetail[0] == 'Premiere'){
									anyMoviePremiere ++;
									if(x[i].checked == false){											
										panelState[panelNumber] = 0;						
										$('#selectAllBoxes').prop('checked',false);	
										return;
									}
								}
							}

							if(anyMoviePremiere == 0){
								panelState[panelNumber] = 0;						
								$('#selectAllBoxes').checked = false;	
								return;
							}
				break;							
				
			case 'Only New':
							var anyNew = 0;							
							for(var i=0; i<x.length; i++){
								programDetail = String(x[i].name).split("|");											
								if(programDetail[1] =='New'){
									anyNew++;
									if(x[i].checked == false){												
										panelState[panelNumber] = 0;						
										$('#selectAllBoxes').prop('checked',false);	
										return;
									}
								}
							}	

							if(anyNew == 0){
								panelState[panelNumber] = 0;						
								$('#selectAllBoxes').prop('checked',false);	
								return;
							}
			break;
					
			case 'Premieres':
							var anyPremiere = 0;
							for(var i=0; i<x.length; i++){
								programDetail = String(x[i].name).split("|");											
								if(programDetail[0] == 'Series Premiere' || programDetail[0] == 'Season Premiere'){
									anyPremiere++;
									if(x[i].checked == false){
										panelState[panelNumber] = 0;						
										$('#selectAllBoxes').prop('checked',false);	
										return;
									}
								}
							}		
							if(anyPremiere == 0){
								panelState[panelNumber] = 0;						
								$('#selectAllBoxes').prop('checked',false);	
								return;
							}																
			break;	
			
			case 'Sports NonEvents Live':
							var anySpNonEvent = 0;						
							for(var i=0; i<x.length; i++){
								programDetail = String(x[i].name).split("|");		
								if(programDetail[3] =='Live'  && programDetail[2] != 'sports event'){
									anySpNonEvent++;
									if(x[i].checked == false){
										panelState[panelNumber] = 0;						
										$('#selectAllBoxes').prop('checked',false);	
										return;
									}
								}
							}	
							if(anySpNonEvent == 0){
								panelState[panelNumber] = 0;						
								$('#selectAllBoxes').prop('checked',false);	
								return;
							}																
			break;	
																																						
			case 'All Shows':
							for(var i=0; i<x.length; i++){
								if(x[i].checked == false){
									panelState[panelNumber] = 0;						
									$('#selectAllBoxes').prop('checked',false);	
									return;
								}
							}		
			break;

			
		}

		$('#selectAllBoxes').prop('checked',true);		
		panelState[panelNumber] = 1;
		return;
	}				
				
				
				
		function updateCheckBoxState(){
			if(panelState[panelNumber] == 1){
				$('#selectAllBoxes').prop('checked',true);
			}
			else{
				$('#selectAllBoxes').prop('checked',false);					
			}
		}