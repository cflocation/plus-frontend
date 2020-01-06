function  dialogQuarters(){
	//buildQuatersList();
	$("#dialog-quarters").dialog("destroy");
	$("#dialog-quarters" ).dialog({
		width:265,
		height:300,
		position: [300,200],
		resizable: false,
		dialogClass: "pepper"
	});

}

function dialogXmlErrors(){	
	closeAllDialogs();	
	loadDialogWindow('xmlerrors','Warning',800,600,0,false,'','',[200,100]);
}

function dialogCompView(){
	setTimeout(function(){
	$("#dialog-comp-view").dialog({
      show: {
        effect: "blind",
        duration: 300
      },      
	  closeOnEscape: false,
	  open: function(event, ui) { $(".ui-dialog-titlebar-close", ui.dialog | ui).hide()},
		width:600,
		height:450,
		position: [350,20],		
		resizable: false,
		modal: true,
		draggable: false,
		closeOnEscape: false
	});	
	
	},500);

}
function dialogDisclaimer(option){
	 
	 var url = 'includes/dialogs/disclaimer.php?option='+option;	
	 
	 $('#dialog-disclaimer').empty().dialog("destroy");
	 var h = 550;
	 
	setTimeout(function(){
	if(option == 'MM'){
		 var h = 800;
	}
	$("#dialog-disclaimer").dialog({
      show: {
        effect: "blind",
        duration: 900
      },      
		width:800,
		height:h,
		position: [350,20],		
		resizable: false,
		modal: false,
		draggable: false,
		closeOnEscape: false
	});	
	
	
	$('#dialog-disclaimer').load(url, function() {
	});	
	
	},500);

}
function dialogDisclaimer2(){
	setTimeout(function(){
	$("#dialog-disclaimer2").dialog({
      show: {
        effect: "blind",
        duration: 900
      },      
		width:800,
		height:550,
		position: [350,20],		
		resizable: false,
		modal: false,
		draggable: false,
		closeOnEscape: false
	});	
	
	},200);

}


function loadHeaders(){	
	closeAllDialogs();	
	loadDialogWindow('custom-title','ShowSeeker',590,500,0,false);
}

function dialogExternal(){
	$("#dialog-external").dialog({
      show: {
        effect: "blind",
        duration: 1000
      },      
		width:800,
		height:500,
		position: [300,20],		
		resizable: false,
		modal: false,
		draggable: false,
		closeOnEscape: false
	});
}
function dialogDemosMoreInfo(){	
	//loadDialogWindow('demos-info','ShowSeeker',300,250,1,false);	
	$('#demonote').toggle();
	$('#dialog-demographics').height('auto');
}
function dialogContact(){
	closeAllDialogs();	
	loadDialogWindow('contact','ShowSeeker',600,350,0,false);
}

function dialogNewsletters(){
	closeAllDialogs();
	loadDialogWindow('newsletters','ShowSeeker Newsletter',300,200,0,false);
}

function saveProposalDestroy(){
	datagridProposal.emptyGrid();
	closeAllDialogs();
}

function dialogEclipse(){

	if(proposalid == 0){
		loadDialogWindow('noproposalloaded', 'ShowSeeker SnapShot', 450, 180, 1);
		return;
	}
	if(datagridProposal.getCount() == 0){
		loadDialogWindow('emptyproposal','ShowSeeker SnapShot', 450, 180, 1);		
		return;
	}
	var bad = datagridProposal.spotCount();	

	if(bad > 0){
		loadDialogWindow('nospots', 'ShowSeeker SnapShot', 450, 180, 1);
		return;
	}
	
	closeAllDialogs();
	
	loadDialogWindow('eclipse','Downloading',290,180,0,1,'eclipse');
}

function dialogImageSelector(){
	if(proposalid == 0){
		loadDialogWindow('noproposalloaded', 'ShowSeeker SnapShot', 450, 180, 1);
		return;
	}
	if(datagridProposal.getCount() == 0){
		loadDialogWindow('emptyproposal','ShowSeeker SnapShot', 450, 180, 1);		
		return;
	}
	var bad = datagridProposal.spotCount();	

	if(bad > 0){
		loadDialogWindow('nospots', 'ShowSeeker SnapShot', 450, 180, 1);
		return;
	}
	var url = 'includes/dialogs/images.php?id='+proposalid;
	
/*	$("#dialog-image-selector").dialog("destroy");

	$("#dialog-image-selector").dialog({
			width:945,
			height:600,
			resizable: false,
			dialogClass: "pepper"}).load(url, function() {});*/
			
			
	$("#dialog-image-selector").empty().dialog("destroy");

	$("#dialog-image-selector").dialog({
	    show: {effect: "blind",duration: 1500},   
		width:950,
		height:600,
		resizable: false,
		modal: false,
		draggable: true,
		dialogClass: "pepper",
		open: function( event, ui ) {
			$('.ui-dialog :button').blur();
		}
	}).load(url, function() {});
			
}

function dialogImagePPTSelector(){

	if(proposalid == 0){
		loadDialogWindow('noproposalloaded', 'ShowSeeker SnapShot', 450, 180, 1);
		return;
	}
	if(datagridProposal.getCount() == 0){
		loadDialogWindow('emptyproposal','ShowSeeker SnapShot', 450, 180, 1);		
		return;
	}	
	var bad = datagridProposal.spotCount();	

	if(bad > 0){
		loadDialogWindow('nospots', 'ShowSeeker SnapShot', 450, 180, 1);
		return;
	}
	
	var url = 'includes/dialogs.php?evt=ppt-images&type=0&downloadformat=ppt&proposalid='+proposalid+'&iseeker='+iseeker+''+'&user='+userid+'&token='+apiKey;

	if(tmpPslId != proposalid){				

		$("#dialog-image-ppt-selector").empty().dialog("destroy");
		$("#dialog-image-ppt-selector").html('<center><BR><BR><h3>Loading... Please Wait</h3></center>');		
		if(iseeker != 'No'){
			$("#dialog-image-ppt-selector").dialog({
			    show: {effect: "blind",duration: 1500},   
				width:950,
				height:600,
				resizable: false,
				modal: false,
				draggable: true,
				dialogClass: "pepper",
				open: function( event, ui ) {
					$('.ui-dialog :button').blur();
				}
			}).load(url, function() {});
		}
		else{
			$("#dialog-image-ppt-selector").dialog({
				width:485,
				height:330,
				position: [330,65],
				resizable: false,
				modal: false,
				draggable: true,
				dialogClass: "pepper",
				open: function( event, ui ) {
					$('.ui-dialog :button').blur();
				}
			}).load(url, function() {});;
		}
	

		tmpPslId = proposalid;
	}
	else{
			if(iseeker != 'No'){
				$("#dialog-image-ppt-selector").dialog({
					width:950,
					height:600,
					resizable: false,
					dialogClass: "pepper"
				});
			}
			else{
				$("#dialog-image-ppt-selector").dialog({
					width:485,
					height:330,
					position: [330,65],
					resizable: false,
					dialogClass: "pepper"
				});				
			}
	}
	//$("#dialog-image-ppt-selector").html('<center><BR><BR><h3>Loading... Please Wait</h3></center>');	
}

/* Load More Info */
function loadShow(){
	closeAllDialogs();
	loadDialogWindow('moreinfo','ShowSeeker',800,580,0,false,0,selectedShowId);
}

function loadPassword(type){
	closeAllDialogs();
	loadDialogWindow('resetPassword', 'ShowSeeker SnapShot', 350, 200, 0, false);
}


function dialogSearching(){
    loadDialogWindow('search', 'ShowSeeker SnapShot', 450, 180, 1);
}

//save proposal
function mergeProposal(){
	closeAllDialogs();
	if(datagridProposalManager.getSelectedRows().length < 2){
		loadDialogWindow('mergeamount','Merge Proposal',450,180,1,true);
		return;
	}
	loadDialogWindow('proposal-merge','Merge Proposal',450,180,0,false);
}


function dialogDemos(){
	closeAllDialogs();
	var url = 'includes/dialogs.php?evt=demos&type=0&proposalid='+proposalid+'&downloadformat=';
	$('#dialog-window').load(url, function() {
		$("#dialog-window").dialog({
			width:500,
			height:350,
			resizable: false,
			title: 'Demographics',
			position: [230,100],
			modal: true,
			draggable: true,
			dialogClass: "pepper",
			open: function( event, ui ) {
				$('.ui-dialog :button').blur();
			}
		});
	})
		
}

function dialogDecades(){

	$('#decade-options').val('1930 TO 2019');
	
	if($("#dialog-decades").dialog( "isOpen" )===true) {
		$("#dialog-decades").dialog("moveToTop");
		return;
	}

	$("#dialog-decades" ).dialog({
		width:265,
		height:340,
		position: [350,200],
		resizable: false,
		dialogClass: "pepper"
	});
	
};


function dialogDemographics(){
	
	$('#demonote').hide();
		
	if($("#dialog-demographics").dialog( "isOpen" )===true) {
		$("#dialog-demographics").dialog("destroy");	
		return;
	}

	$("#dialog-demographics" ).dialog({
		width:265,
		height:"auto",
		position: [350,140],
		resizable: false,
		dialogClass: "pepper"
	});
	
};


//dialog dialogAvails Quarters
function dialogAvailsQuarters(){
	$("#dialog-avails-quarters").dialog("destroy");
	$("#dialog-avails-quarters" ).dialog({
		width:265,
		height:300,
		position: [300,200],
		resizable: false,
		dialogClass: "pepper"
	});
}



function dialogAvailsDayparts(){
	$("#dialog-avails-dayparts-30").dialog("destroy");
	$("#dialog-avails-dayparts-60").dialog("destroy");
	
	$("#dialog-avails-dayparts" ).dialog({
		width:265,
		height:300,
		position: [300,300],
		resizable: false,
		dialogClass: "pepper"
	});
}




function dialogAvailsDayparts60(){

	$("#dialog-avails-dayparts-30").dialog("destroy");
	$("#dialog-avails-dayparts").dialog("destroy");

	selectTimeRangeForAvails('avails-dayparts-60');
	
	$("#dialog-avails-dayparts-60" ).dialog({
		width:265,
		height:300,
		position: [300,300],
		resizable: false,
		dialogClass: "pepper"
	});
}


function dialogAvailsDayparts30(){
	$("#dialog-avails-dayparts-60").dialog("destroy");
	$("#dialog-avails-dayparts").dialog("destroy");
	
	selectTimeRangeForAvails('avails-dayparts-30');
	
	$("#dialog-avails-dayparts-30" ).dialog({
		width:265,
		height:300,
		position: [300,300],
		resizable: false,
		dialogClass: "pepper"
	});
}


/*function dialogAvailsDayparts30(){
	closeAllDialogs();
	loadDialogWindow('avail-dayparts-30', 'Select Dayparts 30 Minute', 265, 300, 'dayparts-30', false);
}

function dialogAvailsDayparts60(){
	closeAllDialogs();
	loadDialogWindow('avail-dayparts-60', 'Select Dayparts 60 Minute', 265, 300, 'dayparts-60', false);
}

function dialogAvailsDayparts(){
	loadDialogWindow('avail-dayparts', 'Select Dayparts', 265, 300, 'dayparts', false);
	closeAllDialogs();
}*/

//save proposal
function dialogCreatingAvails(){
	closeAllDialogs();
	loadDialogWindow('addlines', 'ShowSeeker SnapShot', 450, 180, 1, false);
}


function dialogShareSearch(){
	closeAllDialogs();

	var rows = datagridSavedSearches.getSelectedRows();
	proposalShareType = 'Search';
	
	if(rows.length != 1){
		loadDialogWindow('onlyone', 'ShowSeeker SnapShot', 450, 180, 1);
		return;
	}else{
		loadDialogWindow('share', 'Share Saved Searches', 600, 680, 2);
		return;
	}
}

function dialogMessages(){
	closeAllDialogs();
	loadDialogWindow('messages', 'ShowSeeker SnapShot', 550, 500, 0, false)
}

/* Load Client Manager */
function loadManager(type){
	loadDialogWindow('client-manager','Client Manager',800,500,0,false);
}

function setDialogMessage(id,title){
	$("#"+id).dialog( "option", "title", title);
}

function dialogSaveSearch(){
	closeAllDialogs();
	loadDialogWindow('save-search','ShowSeeker SnapShot',400,280,0,false);
}

function titlesResetList() {
    datagridTitles.resetFilter();
    $("#searchinput").val("");
}


function loadModalMessage(){
	closeAllDialogs();
	loadDialogWindow('reset-ss','ShowSeeker SnapShot',450,180,1,true);
}

//save proposal
function saveProposal(){
	closeAllDialogs();
	loadDialogWindow('proposal-save','Save SnapShot',450,180,0,false);
}



function dialogFlight(){
	
	if(proposalid == 0){
		loadDialogWindow('noproposalloaded', 'ShowSeeker SnapShot', 450, 180, 1);
		return;
	}

	closeAllDialogs();

	loadDialogWindow('calendar-flight','ShowSeeker SnapShot',550,460,0,false);


}



function dialogDuplicateLinesWait(){
	loadDialogWindow('duplicate-line-wait','ShowSeeker SnapShot',450,180,0,true);
}

function dialogDuplicateLines(){

	if(datagridProposal.selectedRows().length == 0){
		loadDialogWindow('nolines', 'ShowSeeker SnapShot', 450, 180, 1);
		return;
	}
	closeAllDialogs();
	loadDialogWindow('duplicate-line', 'ShowSeeker SnapShot', 200,300,0,false);
}

function dialogEditLines(){

	if(datagridProposal.selectedRows().length == 0){
		loadDialogWindow('nolineselected', 'ShowSeeker SnapShot', 450, 210, 1);
		return;
	}
		
	closeAllDialogs();	

	loadDialogWindow('edit-line','Spots & Rates',450,210,0,false);

}



function dialogClearSearch(){

	if(datagridSearchResults.selectedRows().length > 0){
		closeAllDialogs();	
		loadDialogWindow('clearsearchresults','ShowSeeker SnapShot',450,180,1,1);
	}
	
	return;
}

function dialogDeleteLines(){

	if(datagridProposal.selectedRows().length == 0){
		loadDialogWindow('nolinestodelete', 'ShowSeeker SnapShot', 450, 180, 1);
		return;
	}
	
	closeAllDialogs();	
	loadDialogWindow('deletelines','ShowSeeker SnapShot',450,180,1,1);

}



function dialogEditTitle(){

	/*var bad = datagridProposal.spotCount();	
	if(bad > 0){
		loadMessage('nolines2');
		return;
	}
	if($("#dialog-edit-lines").dialog( "isOpen" )===true) {
		$("#dialog-edit-lines").dialog("moveToTop");
		return;
	}*/	


	if(datagridProposal.selectedRows().length == 0){
		loadDialogWindow('nolinesrotator', 'ShowSeeker SnapShot', 450, 180, 1);
		return;
	}

	if(datagridProposal.selectedRows().length == 1 && datagridProposal.selectedRows()[0].linetype == 'Fixed'){
		loadDialogWindow('onlyrotators', 'ShowSeeker SnapShot', 450, 180, 1);
		return;
	}
	
	closeAllDialogs();	

	loadDialogWindow('edit-line-title','Change Line Titles',290,180,0,1);

}

function dialogEditRates(){

	/*var bad = datagridProposal.spotCount();	
	if(bad > 0){
		loadMessage('nolines2');
		return;
	}
	if($("#dialog-edit-lines").dialog( "isOpen" )===true) {
		$("#dialog-edit-lines").dialog("moveToTop");
		return;
	}*/	

	if(datagridProposal.selectedRows().length == 0){
		loadDialogWindow('nolines', 'ShowSeeker SnapShot', 450, 180, 1);
		return;
	}
		
	closeAllDialogs();	

	loadDialogWindow('edit-line-rate','Rates',290,180,0,1);

}

function dialogEditSpots(){

	/*var bad = datagridProposal.spotCount();	
	if(bad > 0){
		loadMessage('nolines2');
		return;
	}
	if($("#dialog-edit-lines").dialog( "isOpen" )===true) {
		$("#dialog-edit-lines").dialog("moveToTop");
		return;
	}*/
	
	if(datagridProposal.selectedRows().length == 0){
		loadDialogWindow('nolines', 'ShowSeeker SnapShot', 450, 180, 1);
		return;
	}
	
	closeAllDialogs();	


	loadDialogWindow('edit-line-spots','Spots',290,180,0,1);

}

function dialogDownloadFile(type){
	if(proposalid == 0){
		loadDialogWindow('noproposalloaded', 'ShowSeeker SnapShot', 450, 180, 1);
		return;
	}
	
	if(datagridProposal.getCount() == 0){
		loadDialogWindow('emptyproposal','ShowSeeker SnapShot', 450, 180, 1);
		return;
	}

	var bad = datagridProposal.spotCount();	

	if(bad > 0){
		loadDialogWindow('nospots', 'ShowSeeker SnapShot', 450, 180, 1);
		return;
	}

	var url = 'includes/dialogs/download.proposal.php?type='+type;
	
	
	closeAllDialogs();

	loadDialogWindow('proposal-download','Downloading',290,180,0,true,type);
	
}

//------------------------

function closeAllDialogs(){
	$("#dialog-actor").dialog("destroy");
	$("#dialog-avails-dayparts-30").dialog("destroy");
	$("#dialog-avails-dayparts-60").dialog("destroy");
	$("#dialog-avails-dayparts").dialog("destroy");
	$("#dialog-avails-quarters").dialog("destroy");
	$("#dialog-daysofweek").dialog("destroy");
	$("#dialog-dayparts").dialog("destroy");
	$("#dialog-decades").dialog("destroy");
	$("#dialog-demographics").dialog("destroy");	
	$("#dialog-genre").dialog("destroy");
	$("#dialog-image-ppt-selector").dialog("destroy");	
	$("#dialog-keyword").dialog("destroy");
	$("#dialog-networks").dialog("destroy");
	$("#dialog-premiere").dialog("destroy");
	$("#dialog-title").dialog("destroy");
	$("#dialog-window").dialog("destroy");
}




//load the dialog into memory
function loadDialogWindow(evt,title,w,h,type,modal,downloadformat,showid,pos){
	var modal = typeof modal !== 'undefined' ? modal : true;
	
	
	if(type == 1){
		displayDynamicDialog(evt,title,w,h,type,modal,downloadformat,showid,pos);
		return;
	}
	
	var url = 'includes/dialogs.php?evt='+evt+'&type='+type+'&downloadformat='+downloadformat+'&proposalid='+proposalid+'&showid='+showid;
	$('#dialog-window').load(url, function() {
		displayDialogWindow(w,h,title,modal,pos);
	});
}


function displayDynamicDialog(evt,title,w,h,type,modal,downloadformat,showid,pos){
	if(ssDialogs !== undefined){
		var usrMessage = findMessage(evt);	
		var popup;
	
		
		if( parseInt(usrMessage['alert']) === 1){
			popup = 	'<div class="ui-widget">';
			popup +=	'<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">'
			popup +=	'<p> <i class="fa fa-exclamation-triangle fa-2x" style="float: left; margin-right: .3em;"></i>'; 
			popup +=	'<strong>Alert:</strong>'+ usrMessage['message']+ '</p></div><p></p><center>';
		
			if(usrMessage['event'] !== ""){
				popup +='<button onclick="'+ usrMessage['event']+'; destroyDialog()" class="btn-green">'+usrMessage['eventLabel']+'</button>';
			}
			
			if(parseInt(usrMessage['close']) === 1){
				popup +='<button onclick="destroyDialog()" class="btn-red"><i class="fa fa-times-circle"></i> Close</button>';
			}
			
			if(parseInt(usrMessage['ajax']) === 1){
				popup +='<br><img src="i/ajax.gif">';
			}
			popup +=	'</center></div>';
		}


				
		if(parseInt(usrMessage['alert']) === 0){

			popup = 	'<div><center><h3>'+ usrMessage['message']+'</h3>';
			
			if(parseInt(usrMessage['ajax']) === 1){
				popup += '<img src="i/ajax.gif"><br>';
			}
			
			if(parseInt(usrMessage['close']) === 1){
				popup += '<br><button onclick="destroyDialog();" class="btn-red"><i class="fa fa-times-circle"></i> Close</button>';
			}
			popup += 	'</center></div>';
		}	
			

		$('#dialog-window').dialog({
			open: function(event, ui){
				$('#dialog-window').html(popup);
				displayDialogWindow(w,h,title,modal,pos);				
				$("button").button();
		}});
		//$('#dialog-window').dialog('open');
	}

}

function destroyDialog(){
	 $("#dialog-window").dialog("destroy");
}

function findMessage(e){
	for(var i=0; i <ssDialogs.length; i++){
		if(e === ssDialogs[i]['name']){
			return ssDialogs[i];
		}
	}
	return {};
}



//display the dialog
function displayDialogWindow(w,h,title,modal,pos){
	if(pos === undefined){
		$("#dialog-window").dialog({
			width:w,
			height:h,
			resizable: false,
			title: title,
			modal: modal,
			draggable: true,
			dialogClass: "pepper",
			open: function( event, ui ) {
				$('.ui-dialog :button').blur();
			}
		});
	}
	else{
		$("#dialog-window").dialog({
			width:w,
			height:h,
			position: pos,
			resizable: false,
			title: title,
			modal: modal,
			draggable: true,
			dialogClass: "pepper",
			open: function( event, ui ) {
				$('.ui-dialog :button').blur();
			}
		});		
	}
}





//dialog clone proposal
function dialogCloneProposal(){
	var eventrows = datagridProposalManager.getSelectedRows();
	$('input[name=flight]').attr('checked', false);
	if(eventrows.length > 1) {
		loadDialogWindow('leastone', 'ShowSeeker SnapShot', 450, 180, 1);
		return;
	}
	if(eventrows.length == 0) {
		loadDialogWindow('leastone', 'ShowSeeker SnapShot', 450, 180, 1);
		return;
	}
	//load the dialog
	loadDialogWindow('proposal-clone', 'Copy Proposal', 275, 420, 0, false,'','',[770,85]);
}


//save proposal
function savingProposal(){
	$("#dialog-saving-proposal").dialog("destroy");
	$("#dialog-saving-proposal").dialog({
		width:450,
		height:180,
		resizable: false,
		modal: true,
		draggable: false,
		closeOnEscape: false,
		open: function(event, ui) {
			$(this).parent().children().children('.ui-dialog-titlebar-close').hide();
		}
	});
}


//title
function dialogTitle(type){
	if(hackedtitle == true){
		return;
	}

	if(zoneid == 0){
		loadMessage('no-zone-selected');
		return;
	}
	
	if(zoneid == 0){
		loadMessage('no-zone-selected');
		return;
	}

	/*if(arrayNetworks.length > 1){
		alert('Please select a Network from the list');
		return;
	}
    datagridTitlesSelected.empty();	*/

	
	
	//close other dialogs
	$("#dialog-keyword,#dialog-actor").dialog("close");

	if($("#dialog-title").dialog("isOpen")===true) {

		if(type == 1){
			$('#dialog-title').dialog('option', 'title', 'Title Search');
			$("#dialog-title-search-btn,#dialog-title-save-btn-reminder").hide();
			$("#dialog-title-save-btn").show();
			searchtitletype = 'title';			
			searchTitles();
		}
		else{	
			$('#dialog-title').dialog('option', 'title', 'Archived Title Search');
			$("#dialog-title-search-btn,#dialog-title-save-btn").hide();
			$("#dialog-title-save-btn-reminder").show();
			searchtitletype = 'archived title';
			searchTitlesArchived(type);
		}

		$("#dialog-title").dialog("moveToTop");
		return;
	}


	$("#dialog-title").dialog({
		width:670,
		height:580,
		resizable: false,
		open: function(event,ui){
			if(type == 1){
				$('#dialog-title').dialog('option', 'title', 'Title Search');
				$("#dialog-title-search-btn,#dialog-title-save-btn").show();
				$("#dialog-title-save-btn-reminder").hide();
				searchtitletype = 'title';
				searchTitles();
			}else{
				$('#dialog-title').dialog('option', 'title', 'Archived Title Search');
				$("#dialog-title-search-btn,#dialog-title-save-btn").hide();
				$("#dialog-title-save-btn-reminder").show();
				searchtitletype = 'archived title';
				searchTitlesArchived(type);
			}
		},
		dialogClass: "pepper"
	});
}


//keyword
function dialogKeyword(){
	if(zoneid == 0){
		loadMessage('no-zone-selected');
		return;
	}

	//close other dialogs
	$("#dialog-title").dialog("close");
	$("#dialog-actor").dialog("close");

	if($("#dialog-keyword").dialog( "isOpen" )===true) {
		$("#dialog-keyword").dialog("moveToTop");
		return;
	}

	$("#dialog-keyword" ).dialog({
		width:670,
		height:580,
		resizable: false,
		dialogClass: "pepper"
	});
}




//Network List
function dialogNetworkList(loc){

	if(zoneid == 0){
		loadMessage('no-zone-selected');
		return;
	}

	if($("#dialog-networks").dialog( "isOpen" )===true) {
		$("#dialog-networks").dialog("moveToTop");
		return;
	}

	var pos = [290,65]

	if(loc == 'over'){
		pos = [760,65];
	}

	$("#dialog-networks" ).dialog({
		width:265,
		height:520,
		position: pos,
		resizable: false,
		dialogClass: "pepper"
	});
}





//premiere
function dialogPremiere(){
	if(zoneid == 0){
		loadMessage('no-zone-selected');
		return;
	}

	if($("#dialog-premiere").dialog( "isOpen" )===true) {
		$("#dialog-premiere").dialog("moveToTop");
		return;
	}

	$("#dialog-premiere" ).dialog({
		width:265,
		height:300,
		position: [350,250],
		resizable: false,
		dialogClass: "pepper"
	});
}





//actor
function dialogActor(){
	if(zoneid == 0){
		loadMessage('no-zone-selected');
		return;
	}

	//close other dialogs
	$("#dialog-title").dialog("close");
	$("#dialog-keyword").dialog("close");

	if($("#dialog-actor").dialog( "isOpen" )===true) {
		$("#dialog-actor").dialog("moveToTop");
		return;
	}

	$("#dialog-actor" ).dialog({
		width:670,
		height:580,
		resizable: false,
		open: function(event,ui){
			searchActors();
		},
		dialogClass: "pepper"
	});
}



//genre
function dialogGenre(){
	if(zoneid == 0){
		loadMessage('no-zone-selected');
		return;
	}

	if($("#dialog-genre").dialog( "isOpen" )===true) {
		$("#dialog-genre").dialog("moveToTop");
		return;
	}

	$("#dialog-genre").dialog({
		width:265,
		height:520,
		position: [350,75],
		resizable: false,
		open: function(event,ui){
			searchGenres();
		},
		dialogClass: "pepper"
	});
}




//dayparts
function dialogDayparts(){
	if($("#dialog-dayparts").dialog( "isOpen" )===true) {
		$("#dialog-dayparts").dialog("moveToTop");
		return;
	}

	$("#dialog-dayparts" ).dialog({
		width:265,
		height:300,
		position: [300,200],
		resizable: false,
		dialogClass: "pepper"
	});
}


//days of week
function dialogDayOfWeek(){
	if(zoneid == 0){
		loadMessage('no-zone-selected');
		return;
	}

	if($("#dialog-daysofweek").dialog( "isOpen" )===true) {
		$("#dialog-daysofweek").dialog("moveToTop");
		return;
	}

	$("#dialog-daysofweek" ).dialog({
		width:265,
		height:300,
		position: [300,200],
		resizable: false,
		dialogClass: "pepper"
	});
}


function loadMessage(msg){
	if(msg === 'no-zone-selected'){
		alert('Please select a Zone from the list');	
	}
};