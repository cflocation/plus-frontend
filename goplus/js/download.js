//these are the extention boxes for zolo media
$("#repfirm-download-selectorlist").change(function() {
	repfirmid = $(this).val();
});

$("#client-download-selectorlist").change(function() {
	clientid1 = $(this).val();
});

$("#proposal-download-selector-1").change(function() {
	clientid2 = $(this).val();
});

$("#agency-download-selectorlist").change(function() {
	agencyid = $(this).val();
});

$("#proposal-download-selector-2").change(function() {
	agencyid2 = $(this).val();
});

$("#download-hide-rates").change(function() {
	if($(this).is(':checked')){
		$('#download-show-rates').prop('checked',false).attr("disabled", true);
	}
	else{
		$('#download-show-rates').removeAttr("disabled");	
	}
});


function quickPrint(){		
	quickSave();
};



function downloadadSails(){
	if(issaving){
		downloadtype = 'adsails';
		return;
	}
	
	var x = buildDownloadURL();
	var url = 'https://downloadsapi.showseeker.com/adsails'+x;
	return url;
};


function downloadEclipse(){
	
	var customer 	= $("#proposal-download-selector-1").val();
	var agency 		= $("#proposal-download-selector-2").val();
	var salesperson = $("#proposal-download-selector-3").val();
	var bookend 	=  $('input:radio[name=bookend-mode-option]:checked').val();
	var bookendmode;
	var breaklen;
	var revenuetype = encodeURIComponent('Regular Direct');	

	if(bookend == 'B30'){
		bookendmode = 'Y';
		breaklen = 30;
	}else{
		bookendmode = 'N';
		breaklen = bookend;
	}

	if(agency != ''){
		revenuetype = 'Agency'
	}
	else if(customer != ''){
		revenuetype = encodeURIComponent('Regular Direct');
	}
	
	var url = 'https://plus.showseeker.com/goplus/downloads/eclipse.php?userid='+userid+'&key='+apiKey+'&proposalid='+proposalid+'&customer='+customer+'&salesperson='+salesperson+'&agency='+agency+'&ucBookend='+bookendmode+'&ulLength='+breaklen+'&revenuetype='+revenuetype;



		$.ajax({
	        type:'get',
			url: url,
	        async:false,
	        success:function(data){		        		        
				window.location.href = 'services/fdownload.php?filename='+data;
				logUserEvent(54,'{"Eclipse":"'+url+'"}',data,proposalid);
				try{
					var pslInfo = datagridProposalManager.getProposalInfo(proposalid);
					var mixType	= 'Eclipse';
					var d 		= buildMixDownloadParams(mixType, pslInfo.name, 'https://godownload.showseeker.com/tmp/'+data);
					usrIp('Download',d);
				}catch(e){}	
	        }
		});
};

function downloadApi(){
	var x 	= buildDownloadURL();
	var url = 'https://godownload.showseeker.com/xls.php'+x;

	$.getJSON(url, function(data){
		$("#dialog-window").dialog("destroy");
		window.location.href = 'services/fdownload.php?filename='+data.filename;	
		
		try{
			var pslInfo = datagridProposalManager.getProposalInfo(proposalid);
			var mixType	= 'API';
			var d 		= buildMixDownloadParams(mixType, pslInfo.name, 'https://godownload.showseeker.com/tmp/'+data.filename);
			usrIp('Download',d);
		}catch(e){}	
					
	});

	return false;
};



function downloadExcel(){
	var x 	= buildDownloadURL();
	var url = 'https://godownload.showseeker.com/xls.php'+x;

		
	$.getJSON(url, function(data){
		$("#dialog-window").dialog("destroy");
		window.location.href = 'services/fdownload.php?filename='+data.filename;	
			
		logUserEvent(7,'Excel',1,proposalid);
		try{
			var pslInfo = datagridProposalManager.getProposalInfo(proposalid);
			var mixType	= 'Excel';
			var d 		= buildMixDownloadParams(mixType, pslInfo.name, 'https://godownload.showseeker.com/tmp/'+data.filename);
			usrIp('Download',d);
		}catch(e){}	
	});

	return false;
};


function downloadExcel2(){
	var x 	= buildDownloadURL();
	var url = 'https://godownload.showseeker.com/xlsreport.php'+x;

	$.getJSON(url, function(data){
		$("#dialog-window").dialog("destroy");
		window.location.href = 'services/fdownload.php?filename='+data.filename;
		logUserEvent(8,'Excel Report',1,proposalid);
		try{
			var pslInfo = datagridProposalManager.getProposalInfo(proposalid);
			var mixType	= 'Excel - Report';
			var d 		= buildMixDownloadParams(mixType, pslInfo.name, 'https://godownload.showseeker.com/tmp/'+data.filename);
			usrIp('Download',d);
		}catch(e){}		
		
	});

	return false;
};

function downloadExcelSpec(){
	var x 	= buildDownloadURL();
	var url = 'https://godownload.showseeker.com/xlsspec.php'+x;

	$.getJSON(url, function(data){
		$("#dialog-window").dialog("destroy");
		window.location.href = 'services/fdownload.php?filename='+data.filename;
		logUserEvent(62,'Excel-Spec',1,proposalid);		
		try{
			var pslInfo = datagridProposalManager.getProposalInfo(proposalid);
			var mixType	= 'Excel - Detail';
			var d 		= buildMixDownloadParams(mixType, pslInfo.name, 'https://godownload.showseeker.com/tmp/'+data.filename);
			usrIp('Download',d);
		}catch(e){}
		
	});

	return false;
};

function downloadAvails(){
	var x 	= buildDownloadURL();
	var url = 'https://godownload.showseeker.com/avails.php'+x;
	
	$.getJSON(url, function(data){
		$("#dialog-window").dialog("destroy");
		window.location.href = 'services/fdownload.php?filename='+data.filename;
		logUserEvent(55,'Avails',1,proposalid);
		try{
			var pslInfo = datagridProposalManager.getProposalInfo(proposalid);
			var mixType	= 'Avails';
			var d 		= buildMixDownloadParams(mixType, pslInfo.name, 'https://godownload.showseeker.com/tmp/'+data.filename);
			usrIp('Download',d);
		}catch(e){}	
		
	});

	return false;
};

function downloadNovar(){
	if(issaving){
		downloadtype = 'novar';
		return;
	}
	var x 	= buildDownloadURL();
	var url = 'https://downloadsapi.showseeker.com/novar'+x;
	return url;
}


function downloadPDF(t){
	if(issaving){
		downloadtype = 'pdf';
		return;
	}

	var x = buildDownloadURL();
	var pdfType = 'Download - PDF'
	var url;
	
	if(t === 'quickPdf'){
		pdfType = "Download - Quick Print"
	}
		
	if(proposalRattingsOn !== 0){
		url = 'https://downloadsapi.showseeker.com/pdf_ratings'+x;	
	}
	else{
		url = 'https://downloadsapi.showseeker.com/pdf'+x;
	}

	return url;
};

function quickPDF(){
	dialogDownloadFile('quickPdf');
	return false;
};

function downloadQuickPDF(){
	if(issaving){
		downloadtype = 'pdf';
		return;
	}

	var x 	= buildQuickDownloadURL();
	var url = 'https://downloadsapi.showseeker.com/pdf/'+x;

	return url;
};


function downloadPPT(){

	if(issaving){
		downloadtype = 'excelspec';
		return;
	}
		
	var url = token['url'];
	
    $.getJSON(url, function(userToken) {

		var x 	= downloadURL();
		var url = 'https://plus.showseeker.com/goplus/downloads/ppt.php'+x+'&tokenid='+userToken;
		
		$.post("services/download.eclipse.php", {url:url},function(data) {
		$("#dialog-window").dialog("destroy");
			window.location.href = 'services/fdownload.php?filename='+data;
		});
		
	});

	return false;		
}


function downloadStrata(){
	if(issaving){
		downloadtype = 'strata';
		return;
	}
		
	dialogDemos();
}

function downloadWord(){
	if(issaving){
		downloadtype = 'word';
		return;
	}

	var x 	= buildDownloadURL();
	var url = 'https://downloadsapi.showseeker.com/word'+x;

	return url;
}

function downloadWordNoRates(){
	if(issaving){
		downloadtype = 'word';
		return;
	}
	
	var x 	= buildDownloadURL();
	var url = 'https://downloadsapi.showseeker.com/wordnorates'+x;		

	return url;
}


function downloadProposalXML(){
	if(issaving){
		downloadtype = 'xml';
		return;
	}
	if(proposalid == 0){
		loadDialogWindow('noproposalloaded', 'ShowSeeker Plus', 450, 180, 1);
		return;
	}
	if(datagridProposal.getCount() == 0){
		loadDialogWindow('emptyproposal','ShowSeeker Plus', 450, 180, 1);		
		return;
	}	

	var bad = datagridProposal.spotCount();	

	if(bad > 0){
		loadDialogWindow('nospots', 'ShowSeeker Plus', 450, 180, 1);
		return;
	}
		
	checkXMLRules();
}


function buildDownloadURL(){
	
	var sort1 			= $("#download-sort-1").val();
	var sort2 			= $("#download-sort-2").val();
	var sort3 			= $("#download-sort-3").val();
	var logos 			= $("#download-include-logos").prop("checked");
	var description 	= $("#download-include-description").prop("checked");
	var episode 		= $("#download-show-episode").prop("checked");
	var includenew 		= $("#download-include-new").prop("checked");
	var hidwerates 		= $("#download-hide-rates").prop("checked");
	var showrates 		= $("#download-show-rates").prop("checked");
	var onlyfixed 		= $("#download-only-fixed").prop("checked");
	var addterms 		= $("#download-add-terms").prop("checked");
	var calendar 		= $("#standard").prop("checked");
	var clientid3 		=	0;
	var inlineRtgs 	= $('#download-show-inlineRtg').prop("checked");	
	var pslRtgs			= $('#download-show-ratings').prop("checked");
	var rtgsOn			= false;
	
	if(proposalRattingsOn !== 0 && pslRtgs){
		rtgsOn			= true;
	}
	
	if(datagridClients){	
		var usrClient = datagridClients.getSelectedRows();
		if(usrClient.length > 0){
			clientid3 = usrClient[0].id;
		}
	}	

	var re = '?proposalid='+proposalid;
	re = re + '&userid='+userid;
	re = re + '&tokenid='+apiKey;
	re = re + '&clientid1='+clientid1;
	re = re + '&clientid2='+clientid2;
	re = re + '&clientid3='+clientid3;
	re = re + '&headerid='+headerid;
	re = re + '&sort1='+sort1;
	re = re + '&sort2='+sort2;
	re = re + '&sort3='+sort3;
	re = re + '&logos='+logos;
	re = re + '&description='+description;
	re = re + '&includenew='+includenew;
	re = re + '&hiderates='+hidwerates;
	re = re + '&showratecard='+showrates;
	re = re + '&onlyfixed='+onlyfixed;
	re = re + '&addterms='+addterms;
	re = re + '&repfirmid='+repfirmid;
	re = re + '&agencyid='+agencyid;
	re = re + '&agencyid2='+agencyid2;
	re = re + '&calendar='+calendar;
	re = re + '&inlineRtgs='+inlineRtgs;
	re = re + '&proposalRattingsOn='+rtgsOn;
	re = re + '&episode='+episode;

	return re;
};


function buildQuickDownloadURL(){
	
	var sort1 = "startdate";
	var sort2 = "starttime";
	var sort3 = "network";
	var logos = true;
	var description = true;
	var includenew = true;
	var hidwerates = false;
	var showrates = false;
	var onlyfixed = false;
	var addterms = false;
	var calendar = $("#standard").prop("checked");
	var re = '?proposalid='+proposalid;
	re = re + '&userid='+userid;
	re = re + '&tokenid='+tokenid;
	re = re + '&clientid1='+clientid1;
	re = re + '&clientid2='+clientid2;
	re = re + '&headerid='+headerid;
	re = re + '&sort1='+sort1;
	re = re + '&sort2='+sort2;
	re = re + '&sort3='+sort3;
	re = re + '&logos='+logos;
	re = re + '&description='+description;
	re = re + '&includenew='+includenew;
	re = re + '&hiderates='+hidwerates;
	re = re + '&showratecard='+showrates;
	re = re + '&onlyfixed='+onlyfixed;
	re = re + '&addterms='+addterms;
	re = re + '&repfirmid='+repfirmid;
	re = re + '&agencyid='+agencyid;
	re = re + '&agencyid2='+agencyid2;
	re = re + '&calendar='+calendar;
	return re;
}




function downloadURL(){
	
	var sort1 = $("#download-sort-1").val();
	var sort2 = $("#download-sort-2").val();
	var sort3 = $("#download-sort-3").val();

	var logos = $("#download-include-logos").prop("checked");
	var description = $("#download-include-description").prop("checked");
	var includenew 	= $("#download-include-new").prop("checked");
	var hidwerates 	= $("#download-hide-rates").prop("checked");
	var showrates 	= $("#download-show-rates").prop("checked");
	var onlyfixed 	= $("#download-only-fixed").prop("checked");
	var addterms 	= $("#download-add-terms").prop("checked");
	var calendar 	= $("#standard").prop("checked");
	var inlineRtgs 	= $('#download-show-inlineRtg').prop("checked");

	var re = '?proposalid='+proposalid;
	re = re + '&userid='+userid;
	re = re + '&clientid1='+clientid1;
	re = re + '&clientid2='+clientid2;
	re = re + '&headerid='+headerid;
	re = re + '&sort1='+sort1;
	re = re + '&sort2='+sort2;
	re = re + '&sort3='+sort3;
	re = re + '&logos='+logos;
	re = re + '&description='+description;
	re = re + '&includenew='+includenew;
	re = re + '&hiderates='+hidwerates;
	re = re + '&showratecard='+showrates;
	re = re + '&onlyfixed='+onlyfixed;
	re = re + '&addterms='+addterms;
	re = re + '&repfirmid='+repfirmid;
	re = re + '&agencyid='+agencyid;
	re = re + '&agencyid2='+agencyid2;
	re = re + '&calendar='+calendar;
	re = re + '&inlineRtgs='+inlineRtgs;
	return re;
}


function buildMixDownloadParams(type,fileName,fileLink){
	
	var sort1 		= $("#download-sort-1 option:selected").text();
	var sort2 		= $("#download-sort-2 option:selected").text();
	var sort3 		= $("#download-sort-3 option:selected").text();
	var logos 		= $("#download-include-logos").prop("checked");
	var description= $("#download-include-description").prop("checked");
	var includenew 	= $("#download-include-new").prop("checked");
	var hidwerates 	= $("#download-hide-rates").prop("checked");
	var showrates 	= $("#download-show-rates").prop("checked");
	var onlyfixed 	= $("#download-only-fixed").prop("checked");
	var addterms 	= $("#download-add-terms").prop("checked");
	var calendar 	= ($("#standard").prop("checked"))?"Standard":"Broadcast";
	var epititle	= $('#download-show-episode').prop("checked");
	var inlineRtgs 	= $('#download-show-inlineRtg').prop("checked");
	var pslHeader	= $('#download-panel-header').text().replace(/No custom title/g, '');	
	var c1 			= $("#client-download-selectorlist option:selected").text();
	var c2 			= $("#proposal-download-selector-1 option:selected").text();
	var c3 			= '';
	var ag1 		= $("#agency-download-selectorlist option:selected").text();
	var ag2 		= $("#proposal-download-selector-2 option:selected").text();
	var rF 			= $("#repfirm-download-selectorlist option:selected").text().replace(/Select RepFirm/g,'');
	
	if(datagridClients){
		var usrClient = datagridClients.getSelectedRows();
		if(usrClient.length > 0){
			c3 = usrClient[0].name;
		}
	}	

	var client = String(c1+c2+c3).replace('Select Client','').replace('Select Customer', '');
	var agency = String(ag1+ag2).replace(/Select Agency/g,'');

	
	var data = {"agencyName":agency,
				"calendar":calendar,
				"clientName":client,
				"downloadType":type,
				"fileLink":fileLink,
				"fileName":fileName,
				"includeDesc":description,
				"includeEpiTitle":epititle,
				"includeLogos":logos,
				"includeNew":includenew,
				"includeRtgs":inlineRtgs,
				"includeRates":hidwerates,				
				"includeRateCard":showrates,
				"includeTerms":addterms,
				"includeOnlyFixed":onlyfixed,
				"proposalId":proposalid,
				"proposalName":fileName,
				"proposalTitle":pslHeader, 
				"repFirmName":rF,
				"sortOpt1": sort1, 
				"sortOpt2":sort2,
				"sortOpt3":sort3,
				"userId":userid};
	return data;
};



function sendEmail(){

	if(issaving){
		downloadtype = 'adsails';
		return;
	}
	if(proposalid == 0){
		loadDialogWindow('noproposalloaded', 'ShowSeeker Plus', 450, 180, 1);
		return;
	}
	if(datagridProposal.getCount() == 0){
		loadDialogWindow('emptyproposal','ShowSeeker Plus', 450, 180, 1);		
		return;
	}
	var settings = buildDownloadURL();
	var company = escape($('#email-company').val());
	var fname = escape($('#email-first-name').val());
	var lname = escape($('#email-last-name').val());
	var trafficid = escape($('#email-traffic-id').val());
	var strataid = escape($('#email-strata-id').val());
	var message = escape($('#email-message').val());
	var subject = escape($('#email-subject').val());
	var email1 = $('#email-email1').val();
	var email2 = $('#email-email2').val();
	if(email1.length == 0){
		return;
	}
	if(email1.length > 0){
		if(!IsEmail(email1)){
			alert('Please review the email account 1');
			return;
		}
	}
	if(email2.length > 0){
		if(!IsEmail(email2)){
			alert('Please review the second email account 2');
			return;
		}
	}

	var showmessage = $('#email-show-message').prop("checked");
	var showcustomerdata = $('#email-show-customer-data').prop("checked");
	var showcontactdata = $('#email-show-contact-data').prop("checked");
	var showsignaturearea = $('#email-show-signature-area').prop("checked");
	var attachword = $('#email-attach-word-document').prop("checked");
	var attachwordnr = $('#email-attach-word-no-rates').prop("checked");	
	var attachexcel = $('#email-attach-excel-document').prop("checked");
	var attachpdf = $('#email-attach-pdf-document').prop("checked");
	var attachstrata = $('#email-attach-strata-document').prop("checked");
	var attachpowerpoint = $('#email-attach-powerpoint-document').prop("checked");
	var re = '?proposalid='+proposalid;
	re = re + '&userid='+userid;
	re = re + '&tokenid='+apiKey;
	re = re + '&company='+company;
	re = re + '&fname='+fname;
	re = re + '&lname='+lname;
	re = re + '&trafficid='+trafficid;
	re = re + '&strataid='+strataid;
	re = re + '&message='+message;
	re = re + '&subject='+subject;
	re = re + '&email1='+email1;
	re = re + '&email2='+email2;
	re = re + '&showmessage='+showmessage;
	re = re + '&showcustomerdata='+showcustomerdata;
	re = re + '&showcontactdata='+showcontactdata;
	re = re + '&showsignaturearea='+showsignaturearea;
	re = re + '&attachword='+attachword;
	re = re + '&attachwordnr='+attachwordnr;
	re = re + '&attachexcel='+attachexcel;
	re = re + '&attachpdf='+attachpdf;
	re = re + '&attachstrata='+attachstrata;
	re = re + '&attachpowerpoint='+attachpowerpoint;
	re = re + '&settings='+escape(settings);
	var url	= 'https://downloadsapi.showseeker.com/email'+re;
	
	loadDialogWindow('sending-email', 'ShowSeeker Plus', 450, 180, 1);	
		
	$.ajax({
        type:'get',
		url: url,
        async:false,
        success:function(data){		        		        
			closeAllDialogs();		        
			if(data.success === true){
				loadDialogWindow('emailSent', 'ShowSeeker Plus', 450, 180, 1);
			}
			else{
				loadDialogWindow('email-error', 'ShowSeeker Plus', 450, 180, 1);
			}
        },
        error:function(){
    		closeAllDialogs();	
			loadDialogWindow('email-error', 'ShowSeeker Plus', 450, 180, 1);        		
        }
	});
	
	try{
        logUserEvent(50,JSON.stringify(url),1,proposalid);	
	}catch(e){}	
}



function setupDownloadPage(){
	$("#download-proposal-list").val(proposalid).change();
};



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
};



function downloadClientRemove(){
	$('#download-panel-client').html('No client loaded');
	$("#download-remove-client").css('display', 'none');
	$('#email-company').val('');
	$('#email-first-name').val('');
	$('#email-last-name').val('');
	$('#email-email1').val('');
	clientid = 0;
};



function downloadHeaderRemove(){
	$('#download-panel-header').html('No custom title');
	$("#download-remove-header").css('display', 'none');
	headerid = 0;
};



function downloadSortOne(){
	var val = $('#download-sort-1').val();

	if(val == 0){
		$("#download-sort-2").css('display', 'none');
		$("#download-sort-3").css('display', 'none');
	}else{
		$("#download-sort-2").css('display', 'inline');
		$("#download-sort-3").css('display', 'inline');
	}
	
	if(String(val) === 'network' || String(val) === 'title'){
		$('#download-sort-2').val('startdate');
		$('#download-sort-3').val('starttime');		
	}

	if(String(val) === 'starttime'){
		$('#download-sort-2').val('startdate');
		$('#download-sort-3').val('network');		
	}
	
	if(String(val) === 'startdate'){
		$('#download-sort-2').val('starttime');
		$('#download-sort-3').val('network');		
	}
}




function checkXMLRules(){
	var proposalines = datagridProposal.getDataSet();
	var ttlSpots = 0;
	var ttlCost = 0;
	
	//xmllines.length = 0;
	xmllines = [];	
	
	$.each(proposalines,function(i,row){
			st = Date.parse('01/01/2016 '+ row['starttime']).toString("HH:mm");	
			et = Date.parse('01/01/2016 '+ row['endtime']).toString("HH:mm");	
				
		if( (st >' 00:00' && st < '05:00') || (et > '00:00' && et < '05:00') ){
			xmllines.push(row);	
		}
	});
	
	if(xmllines.length > 0){
		dialogXmlErrors();

	    //log login event
	    logUserEvent(60,JSON.stringify(xmllines),1,proposalid);
	}
	else{
		xmlOptions();
	}	
};



function XMLdownload(){
	var x = buildDownloadURL();
	var url = 'https://godownload.showseeker.com/xml/'+x;
	var w = h = 550;
	
	var LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
	var TopPosition  = (screen.height) ? (screen.height-h)/2 : 0;
	var settings ='height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars=1,resizable=0,status=0';
	var win = window.open(url,'XMLExport',settings).focus();
	logUserEvent(15,'XML',1,proposalid);
	var dt = new Date().toString('MM-dd-yyyy');;
	var pslInfo = datagridProposalManager.getProposalInfo(proposalid);
	var fileLink = 'https://godownload.showseeker.com/tmp/'+pslInfo.name+'_'+dt+'.xml';
	var d 		= buildMixDownloadParams('XML', pslInfo.name, fileLink);
	mixpanel.track('Download ', d);	
};



function returnToProposalFromXML(){
	$('#panel1,#panel3').css('display', 'none');
	builderpanel['panel3'] = builderpanel['panel1'] = false;
	if(builderpanel['panel2'] == false){
		setPanel('panel2');
	}	
	var r = datagridProposal.selectRowsFromData(xmllines);
	panelManager('close');
	menuSelect('proposal-build');
	sidebarOpen();
	datagridProposal.renderGrid();
	datagridSearchResults.renderGrid();
	sizingTotalsBar();
};



function directDownloadScx(){
	var x 	= buildDownloadURL();
	
	$('#download-scx,#scx-wait-msg,#scx-wait-img').toggle();		
	
	var group 		=  $('#group').val();
	var agefrom 	= 	$('#agefrom').val();
	var ageto		=	$('#ageto').val();
	var campaign	=	$('#campaign-name').val();
	var product		=	$('#product-name').val();


	x = x + '&group='+group;
	x = x + '&agefrom='+agefrom;
	x = x + '&ageto='+ageto;
	x = x + '&campaign='+encodeURIComponent(campaign);
	x = x + '&product='+product+'';
	
	var url 	= 'http://godownload.showseeker.com/scx/'+x;
	var json 	= 'services/jsonbridge.php?url='+encodeURIComponent(url);
	
	
	//get the json result for the data
	$.getJSON(json, function(data) {

		var filename = data.filename;
		try{$.post("services/log.php", { userid:userid, eventslogid:11, request:'Strata', result:'http://export.showseeker.com/tmp/'+filename, proposalid:proposalid  },function(data) {});}catch(e){}

		var link = 'services/force.download.php?filename='+filename;
		
		window.location.href = link;
		logUserEvent(11,'Strata','https://export.showseeker.com/tmp/'+filename,proposalid);

	});
};




