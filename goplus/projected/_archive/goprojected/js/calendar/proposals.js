  ///////////////////////////////////////////////////////		
 //  AUTO SELECT PROPOSAL IF THERE IS ONE  FROM SS+   // 
///////////////////////////////////////////////////////	

$("#proposalList").change(function(e){
	resetTabsState();
	if($(this).val() != 0){
		try{
			sswin.mixTrack("Projected - Proposal Select",{"proposalId":$(this).val()});
		}
		catch(e){}
		
		sswin.loadProposalFromServer($(this).val());
		resetCells();
		var zoneid = $('#zones').val();
		pslIdCheck = self.setInterval(function(){
		
			if(sswin.datagridProposal.dataSet().length != proposallines.length){
				window.clearInterval(pslIdCheck);
				loadedProposalId 	= sswin.proposalid;
				proposallines 		= sswin.datagridProposal.dataSet();
				if(proposallines[0] != undefined){
					if(parseInt(proposallines[0].zoneid) !== parseInt(zoneid)){//IF SELECTED PROPOSAL INCLUDES DIFFERENT ZONES THAN THE ONE OBSERVED
						$.each(sswin.marketzones,function(i,zone){
							if(parseInt(zone.id) === parseInt($('#zones').val())){
								$('#tz').val(zone.abbreviation);
							}
						});						
						$.when(autoSelectMarketAndZone(proposallines[0].zoneid)).then($('#premiefinales').submit());
						}
				}
				spotsLoad(proposallines);
				higlightFilteredTab();
				return;
			}
				
		},1500);
	}
	$.when(resetCells()).then(higlightFilteredTab());
});