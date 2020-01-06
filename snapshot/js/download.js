
function downloadExcel(){
	var x 	= buildDownloadURL();
	var url = 'https://godownload.showseeker.com/snapshot/xls.php/'+x;
	
	$.getJSON(url, function(data){
		$("#dialog-window").dialog("destroy");
		try{
			var pslInfo = datagridProposalManager.getProposalInfo(proposalid);
			var d 		= buildMixDownloadParams('Excel', pslInfo.name, data.filename);
			usrIp('SnapShot - Download',d);
		}catch(e){}
		
		window.location.href = '../goplus/services/fdownload.php?filename='+data.filename;		
	});

	return false;
}

function downloadPDF(){
	if(issaving){
		downloadtype = 'pdf';
		return;
	}

	var x = buildDownloadURL();
	var url = 'https://snapshotdownloads.showseeker.com/pdf'+x;
	url     = 'https://snapshotdownloads.showseeker.com/pdf'+x;
	return url;
}

function downloadStrata(){
	if(issaving){
		downloadtype = 'strata';
		return;
	}
	dialogDemos();
}


function downloadScx(){
	var x 	= buildDownloadURL();
	
	$('#download-scx,#scx-wait-msg,#scx-wait-img').toggle();
	x = x + '&group=Households';
	x = x + '&agefrom=0';
	x = x + '&ageto=99';
	x = x + '&campaign='
	x = x + '&product=';
	
	//var url 	= 'https://godownload.showseeker.com/snapshot/scx/'+x;
	var url 	= 'https://snapshotdownloads.showseeker.com/scx'+x;
	url 	    = 'https://snapshotdownloads.showseeker.com/scx'+x;
	var json = '../goplus/services/jsonbridge.php?url='+encodeURIComponent(url);
	
	$.getJSON(json, function(data) {

		var filename = data.filename;
		var link = '../goplus/services/force.download.php?filename='+filename;
		
		$("#dialog-window").dialog("destroy");

		window.location.href = link;
	});

}


function downloadWordNoRates(){
	if(issaving){
		downloadtype = 'word';
		return;
	}

	var x = buildDownloadURL();
	//var url = 'https://godownload.showseeker.com/snapshot/wordnorates/'+x;
	var url = 'https://snapshotdownloads.showseeker.com/wordnorates'+x;
	    url = 'https://snapshotdownloads.showseeker.com/wordnorates'+x;

	return url;
}


function buildDownloadURL(){
	
	var sort1 			= $("#download-sort-1").val();
	var sort2 			= $("#download-sort-2").val();
	var sort3 			= $("#download-sort-3").val();
	var logos 			= $("#download-include-logos").prop("checked");
	var description 	= $("#download-include-description").prop("checked");
	var includenew 		= $("#download-include-new").prop("checked");
	var includEpisode 	= $("#download-include-episode").prop("checked");
	

	var re = '?proposalid='+proposalid;
	re = re + '&userid='+userid;
	re = re + '&tokenid='+apiKey;
	re = re + '&sort1='+sort1;
	re = re + '&sort2='+sort2;
	re = re + '&sort3='+sort3;
	re = re + '&logos='+logos;
	re = re + '&description='+description;
	re = re + '&includenew='+includenew;
	re = re + '&hiderates=';
	re = re + '&showratecard=';
	re = re + '&onlyfixed=';
	re = re + '&includeEpisode='+includEpisode;
	re = re + '&stg=0';	
	return re;
}




function setupDownloadPage(){
	$("#download-proposal-list").val(proposalid).change();
}


function downloadSetProposal(){
	var selected = $("#download-proposal-list").val();

	if(selected != proposalid){
		loadProposalFromServer(selected,'download');
		setTimeout(function(){
			var bad = datagridProposal.spotCount();		
			if (bad != 0 && datagridProposal.proposalLines.length > 0) {
				loadDialogWindow('nospots','ShowSeeker Plus', 450, 180, 1);
				setTimeout(function(){
					menuSelect('proposal-build');				
				}, 1500);
			}
		}, 700);
	}
}


function downloadClientRemove(){
	$('#download-panel-client').html('No client loaded');
	$("#download-remove-client").css('display', 'none');
	$('#email-company').val('');
	$('#email-first-name').val('');
	$('#email-last-name').val('');
	$('#email-email1').val('');
	clientid = 0;
}


function downloadHeaderRemove(){
	$('#download-panel-header').html('No custom title');
	$("#download-remove-header").css('display', 'none');
	headerid = 0;
}



function downloadSortOne(){
	var val = $('#download-sort-1').val();

	if(val == 0){
		$("#download-sort-2").css('display', 'none');
		$("#download-sort-3").css('display', 'none');
	}else{
		$("#download-sort-2").css('display', 'inline');
		$("#download-sort-3").css('display', 'inline');
	}
}





