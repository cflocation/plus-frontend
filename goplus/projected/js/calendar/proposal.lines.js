	  ///////////////////////////////////////////////////////////////////////////////////////		
	 //  CREATES INITIAL VIEW & VERIFIES WHETHER THERE IS A SELECTED PROPOSAL FROM SS+    //
	///////////////////////////////////////////////////////////////////////////////////////	
	function initialState(proposallines){		 
		if(proposallines.length > 0){
			spotsLoad(proposallines);
		}
	}	
	
	
	
	
	//////////////////////////////////////////////////////		
	 //  LOADS THE SPOTS IN THE GRID, GRAYING OUT CELLS  //
	//////////////////////////////////////////////////////
			
	function spotsLoad(proposallines){
		for(i=0; i< proposallines.length; i++){
			$('#'+proposallines[i].solrId+'-'+proposallines[i].zoneId).prop('checked', true);
			$('#'+proposallines[i].solrId+'-'+proposallines[i].zoneId).closest('div').css({'backgroundColor':'#ccc'});
		}
		//highlight month tabs from the calendar
		highlightMonthTab();	
		
		
		//SYNCHING ZONES
		setTimeout(function(){
			//CHECKING IF THE EVENTS DISPLAYED ARE PART OF THE PROPOSAL			
			r = selectorState();
			r = zoneSynch();
		},500);
	}
	
	
	
	
		//HIGHLIGTS THE TAB THAT CONTAIN SHOWS THAT ARE PART OF THE CURRECT SELECTED PROPOSAL
/*	function highlightMonthTab(){
		$('.parent').each(function(indx){

		if($('input.ssevent[type=checkbox]:checked', this).length > 0){
					$('.tabNavigator:eq('+indx+')').closest('div').css({'backgroundColor':'#F75B22','color':'#fff'});	
				}			
			});
}*/