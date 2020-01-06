 //CREATES NEW PROPOSAL
/* $('#createproposal').click(function(){
	createProposal()
}); */
 
$(document).on('click', '#createproposal', function (e) {	
	createProposal();	
});


  /////////////////////////////////
 //   CREATES A NEW PROPOSAL   //
/////////////////////////////////

function createProposal(){
	newproposalname = $('#proposalnew').val();

	if(newproposalname != ''){
		
		sswin.datagridProposal.emptyGrid();
		sswin.proposalCreateNew(newproposalname, true,'Projected');
		
		resetCells();
		
		pslIdCheck = self.setInterval(function(){
			if(loadedProposalId != sswin.proposalid){
				loadedProposalId = sswin.proposalid;
				window.clearInterval(pslIdCheck);
			
			   	$('#proposalList').append($('<option>', { 
			       value: loadedProposalId,
			       text : newproposalname
			   	}));							
			
				$('#proposalnew').val('');
				$('#proposalList').val(loadedProposalId).change();
			
				highlightMonthTab();
			
				updateCellHeight();
			
				showProjectedDisclaimer();
			
			}
			
		},1500);
	}
	else{
		alert('Proposal Name is required');
	}

};