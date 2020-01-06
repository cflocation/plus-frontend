// Add new proposal
	var xhrProposal = false;	
	
	function selectedProposal(proposalId){

		if(window.XMLHttpRequest){
			xhrProposal = new XMLHttpRequest();
		}
		else{
			if(window.ActiveXObject){
				try{
					xhrProposal = new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch(e){}
			}
		}
		

		if(proposalId != 0){
			if(xhrProposal){
				xhrProposal.onreadystatechange = displayresult;
				xhrProposal.open("GET","cfc/proposals.cfc?method=selectedProposal&proposalId="+proposalId,true)
				xhrProposal.send(null);
			}
			else{
				alert("There was an error on the HTTPRequest to se the Proposal in Cache")
			}
		}
	
	}
	
	
	function displayresult(){
		if(xhrProposal.readyState == 4){
			if(xhrProposal.status == 200){
				window.status = "Selected";
			}
			else{
				alert("There was an error while saving the Proposal in the Cache");
			}
		}
	}