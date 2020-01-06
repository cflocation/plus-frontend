
// Add new proposal
	var xhrCnn = false;
	
	function createProposal(){

		if(window.XMLHttpRequest){
			xhrCnn = new XMLHttpRequest();
		}
		else{
			if(window.ActiveXObject){
				try{
					xhrCnn = new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch(e){}
			}
		}
		

		var newProposal = document.getElementById('proposalName').value;
		if(newProposal != ""){
			if(xhrCnn){
				xhrCnn.onreadystatechange = populateProposals;
				xhrCnn.open("GET","cfc/proposals.cfc?method=createProposal&proposalName="+newProposal+"&linkToScheduler=1",true)
				xhrCnn.send(null);
			}
			else{
				alert("There was an error on the HTTPRequest")
			}
		}
		else{
			alert("Please, type the name of the Proposal");
		}
	
	}
	
	
	function populateProposals(){
		if(xhrCnn.readyState == 4){
			if(xhrCnn.status == 200){
				if(xhrCnn.responseXML){
					var proposals = xhrCnn.responseXML.getElementsByTagName("proposal");
					document.getElementById("proposalList").options.length = 0;
					for(var i=0; i<proposals.length; i++){
						addOptionsToList(proposals[i].getElementsByTagName("name")[0].firstChild.nodeValue, proposals[i].getElementsByTagName("id")[0].firstChild.nodeValue);
					}
					document.getElementById("proposalList").selectedIndex = 0;
					document.getElementById('proposalName').value = "";
							
				}
			}
			else{
				alert("There was an error while displaying the Proposal list");
			}
		}
	}
	
	
	 function addOptionsToList(Text,Value){
        var opt = document.createElement("option");
        document.getElementById("proposalList").options.add(opt);
        opt.text = Text;
        opt.value = Value;

    }