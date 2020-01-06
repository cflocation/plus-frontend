//EZGRIDS
function dialogEzGrids(){
	$("#dialog-image-ppt-selector").html('<center><BR><BR><h3>Preparing your files please wait ...</h3><br/><br/><img src=i/ajax.gif></center>');			
	var url = 'includes/dialogs/ezgrids.php?evt=ezgrids&user='+userid+'&token='+apiKey;

	$("#dialog-image-ppt-selector").dialog({
	    show: {effect: "blind",duration: 1},   
		width:480,
		height:500,
		resizable: false,
		modal: false,
		draggable: true,
		title: "ShowSeeker Plus",
		dialogClass: "pepper",
		open: function( event, ui ) {
			$('.ui-dialog :button').blur();
		}
	}).load(url, function() {});
}

//Network List
function dialogZones(loc){

	if(zoneid == 0){
		loadMessage('no-zone-selected');
		return;
	}

	if($("#dialog-zones").dialog( "isOpen" )===true) {
		$("#dialog-zones").dialog("moveToTop");
		return;
	}

	var pos = [290,65]

	$("#dialog-zones" ).dialog({
		width:265,
		height:520,
		position: pos,
		resizable: false,
		dialogClass: "pepper"
	});
}

function dialogAvgBooks(wHeight){
	closeAllDialogs();	
	var h = 160;
	if(wHeight){
		h = wHeight;
	}
	loadDialogWindow('avgBooks', 'Average Books', 400, h, 0,false,'','');
	return;
};

function xmlOptions(){

	closeAllDialogs();
	var url = 'includes/dialogs.php?evt=xml&type=0&proposalid='+proposalid+'&downloadformat=';

	$('#dialog-window').load(url, function(){

		$("#dialog-window").dialog({
			width:500,
			height:420,
			resizable: false,
			title: 'ShowSeeker XML Download',
			position: [230,100],
			modal: true,
			draggable: true,
			dialogClass: "pepper",
			open: function( event, ui ) {
				$('.ui-dialog :button').blur();
			}
		});
	});
	
};

function dialogScxImport(){
	closeAllDialogs();	
	loadDialogWindow('scx-import','ShowSeeker SCX Importer',450,230,0,false,'','',[532,112]);
};

function dialogDiscardRatings(){
	closeAllDialogs();
	loadDialogWindow('discardRatings', 'ShowSeeker Plus', 400, 160, 0,0);
};

function dialogToggleColumns(){
	closeAllDialogs();
	loadDialogWindow('toggle-columns', 'ShowSeeker Plus', 400, 520, 0,0);
};

function dialogJSON(){
	$("#dialog-image-ppt-selector").empty().dialog("destroy");
	
	if(proposalid == 0){
		loadDialogWindow('noproposalloaded', 'ShowSeeker Plus', 450, 180, 1);
		return;
	}
	if(datagridProposal.getCount() == 0){
		loadDialogWindow('emptyproposal','ShowSeeker Plus', 450, 180, 1);		
		return;
	}	

	proposalSpotsCount = datagridProposal.spotCount();	

	if(proposalSpotsCount > 0){
		loadDialogWindow('nospots', 'ShowSeeker Plus', 450, 180, 1);
		return;
	}
	
	var url = 'includes/dialogs.php?evt=mediamathjson&type=0&downloadformat=api&proposalid='+proposalid+'&user='+userid+'&token='+apiKey;

	$("#dialog-image-ppt-selector").html('<center><BR><BR><h3>Loading... Please Wait</h3><br/><br/><img src=i/ajax.gif></center>');		
	$("#dialog-image-ppt-selector").dialog({
	    show: {effect: "blind",duration: 1},   
		width:480,
		height:500,
		resizable: false,
		modal: false,
		draggable: true,
		title: "ShowSeeker Plus",
		dialogClass: "pepper",
		open: function( event, ui ) {
			$('.ui-dialog :button').blur();
		}
	}).load(url, function() {});


	
};

function dialogImg(url){
	$("#dialog-image-ppt-selector").empty().dialog("destroy");
	$("#dialog-image-ppt-selector").dialog({
	    show: {effect: "blind",duration: 1},   
		width:330,
		height:215,
		resizable: false,
		position: [330,50],			
		modal: false,
		draggable: true,
		title: "ShowSeeker Plus",
		dialogClass: "pepper",
		open: function( event, ui ) {
		$('#dialog-image-ppt-selector').append('<table height=100% width=100%><tr><td valign="middle" align="center" style="overflow:hidden;"><img src='+url+' style="border:solid 1px #ddd;" height="170" width="300"><tr><td></table>');
		}
	});	
};

function dialogHelpFlightDates(){
	closeAllDialogs();
	loadDialogWindow('outOfFlight', 'ShowSeeker Plus', 450, 180, 1);
};

function dialogDownloadApi(){
	
	var currentSpotsCount = proposalSpotsCount;
	
	if(proposalid == 0){
		loadDialogWindow('noproposalloaded', 'ShowSeeker Plus', 450, 180, 1);
		return;
	}
	if(datagridProposal.getCount() == 0){
		loadDialogWindow('emptyproposal','ShowSeeker Plus', 450, 180, 1);		
		return;
	}	

	proposalSpotsCount = datagridProposal.spotCount();	

	if(proposalSpotsCount > 0){
		loadDialogWindow('nospots', 'ShowSeeker Plus', 450, 180, 1);
		return;
	}
	
	var url = 'includes/dialogs.php?evt=api&type=0&downloadformat=api&proposalid='+proposalid+'&user='+userid+'&token='+apiKey;

	$("#dialog-image-ppt-selector").empty().dialog("destroy");
	$("#dialog-image-ppt-selector").html('<center><BR><BR><h3>Loading... Please Wait</h3><br/><br/><img src=i/ajax.gif></center>');		
	$("#dialog-image-ppt-selector").dialog({
	    show: {effect: "blind",duration: 1},   
		width:370,
		height:600,
		resizable: false,
		modal: false,
		draggable: true,
		title: "ShowSeeker Plus",
		dialogClass: "pepper",
		open: function( event, ui ) {
			$('.ui-dialog :button').blur();
		}
	}).load(url, function() {});

	tmpPslId = proposalid;
}

function dialogSpotLength(){

	if(datagridProposal.selectedRows().length == 0){
		loadDialogWindow('nolineselected', 'ShowSeeker Plus', 450, 210, 1);
		return;
	}
	closeAllDialogs();	
	loadDialogWindow('edit-spotLength','Spot Length',380,160,0,false);	
};

function dialogRatingsReport(){
	closeAllDialogs();	
	loadDialogWindow('ratings-report', 'ShowSeeker Ratings Reports',400,290,0,false,'','',[295,109]);
	return;	
};

function dialogLineTypeInfo(){
	closeAllDialogs();	
	loadDialogWindow('linesByDayInfo', 'Line Type Info', 650, 280, 1,false,'','',[295,109]);
	return;
};

function dialogEditSpotInWeek(weekInfo){
	closeAllDialogs();		
	var url = 'includes/dialogs.php?evt=linebyday&type=0&downloadformat=&proposalid=&showid=';
	$('#dialog-spots-by-day').data('dialogData',weekInfo).load(url, function(){
		$("#dialog-spots-by-day").dialog({
			width:200,
			height:330,
			resizable: false,
			title: 'Spots By Day',
			modal: false,
			draggable: true,
			dialogClass: "pepper",
			open: function( event, ui){ $('.ui-dialog :button').blur(); }
		});	

	});		
};

function dialogLineByDay(){
	var p = [300,105];
	if($("#dialog-daysofweek").dialog( "isOpen" )===true){
		p = [590,200];
	}
	else{
		closeAllDialogs();	
	}
	loadDialogWindow('spotsbyday','Spots by Day',200,330,0,false,'','',p);
};

function dialogXmlErrors(){	
	closeAllDialogs();	
	loadDialogWindow('xmlerrors','Warning',800,600,0,false,'','',[200,100]);
};

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

};


function dialogDisclaimerNew(option,packageName){
	var title = option;

	if(packageName){
		title = decodeURIComponent(packageName);
	}

	mixTrack('Packages - Menu',{"packageId":option,"package":title});
	var url = apiUrl+'plus/package-disclaimer/'+option;
	

	$('#dialog-disclaimer').empty().dialog("destroy");
	var h = 550;
	var pos = 	[350,20];
	var drag = false;
	var resize = false;
	 
	setTimeout(function(){
	if(option == 'MM'){
		 var h = 800;
	}
	if(option == 'collegeFootball'){
		 pos= [180,20];
		 drag = true;
		 resize = true;
	}
	$("#dialog-disclaimer").dialog({
      show: {
        effect: "blind",
        duration: 900
      },      
		width:800,
		height:h,
		position: pos,		
		resizable: resize,
		modal: false,
		draggable: drag,
		closeOnEscape: false
	});	
	
	
	$('#dialog-disclaimer').load(url, function() {
	});	
	
	},500);

}

function dialogDisclaimer(option){
	 
	 var url = 'includes/dialogs/disclaimer.php?option='+option;	
	 
	 $('#dialog-disclaimer').empty().dialog("destroy");
	 var h = 410;
	 var pos = 	[350,20];
	 var drag = false;
	 var resize = false;
	 
	setTimeout(function(){

	$("#dialog-disclaimer").dialog({
      show: {
        effect: "blind",
        duration: 900
      },      
		width:700,
		height:h,
		position: pos,		
		resizable: resize,
		modal: false,
		draggable: drag,
		closeOnEscape: false
	});	
	
	$('#dialog-disclaimer').load(url, function() {
	});	
	
	},500);

};

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
	datagridTotals.emptyGrid();
	closeAllDialogs();
}

function dialogEclipse(){

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
	
	closeAllDialogs();
	
	loadDialogWindow('eclipse','Downloading',290,180,0,1,'eclipse');
}

function dialogImageSelector(){
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
	var url = 'includes/dialogs/images.php?id='+proposalid;
			
			
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

var proposalSpotsCount = 0;
function dialogImagePPTSelector(){
	
	var currentSpotsCount = proposalSpotsCount;
	
	if(proposalid == 0){
		loadDialogWindow('noproposalloaded', 'ShowSeeker Plus', 450, 180, 1);
		return;
	}
	if(datagridProposal.getCount() == 0){
		loadDialogWindow('emptyproposal','ShowSeeker Plus', 450, 180, 1);		
		return;
	}	

	proposalSpotsCount = datagridProposal.spotCount();	

	if(proposalSpotsCount > 0){
		loadDialogWindow('nospots', 'ShowSeeker Plus', 450, 180, 1);
		return;
	}
	
	var url = 'includes/dialogs.php?evt=ppt-images&type=0&downloadformat=ppt&proposalid='+proposalid+'&iseeker='+iseeker+''+'&user='+userid+'&token='+apiKey;

		$("#dialog-image-ppt-selector").empty().dialog("destroy");
		$("#dialog-image-ppt-selector").html('<center><BR><BR><h3>Loading... Please Wait</h3><br/><br/><img src=i/ajax.gif></center>');		
		$("#dialog-image-ppt-selector").dialog({
		    show: {effect: "blind",duration: 1500},   
			width:880,
			height:600,
			resizable: false,
			modal: false,
			draggable: true,
			dialogClass: "pepper",
			open: function( event, ui ) {
				$('.ui-dialog :button').blur();
			}
		}).load(url, function() {});

		tmpPslId = proposalid;
}


/* Load More Info */
function loadShow(){
	closeAllDialogs();
	loadDialogWindow('moreinfo','ShowSeeker',800,580,0,false,0,selectedShowId);
}

function loadPassword(type){
	closeAllDialogs();
	loadDialogWindow('resetPassword', 'ShowSeeker Plus', 420, 200, 0, true);
}


function dialogSearching(){
    loadDialogWindow('search', 'ShowSeeker Plus', 450, 180, 1);
}

//save proposal
function mergeProposal(){

	closeAllDialogs();	
	
	var eventrows = datagridProposalManager.getSelectedRows();
	
	if(eventrows.length < 2){
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
			height:400,
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
		width:275,
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


//save proposal
function dialogCreatingAvails(){
	closeAllDialogs();
	loadDialogWindow('addlines', 'ShowSeeker Plus', 450, 180, 1, false);
}


function dialogShareSearch(){
	closeAllDialogs();

	var rows = datagridSavedSearches.getSelectedRows();
	proposalShareType = 'Search';
	
	if(rows.length != 1){
		loadDialogWindow('onlyone', 'ShowSeeker Plus', 450, 180, 1);
		return;
	}else{
		loadDialogWindow('share', 'Share Saved Searches', 600, 680, 2);
		return;
	}
}

function dialogMessages(){
	closeAllDialogs();
	loadDialogWindow('messages', 'ShowSeeker Plus', 550, 500, 0, false)
}

/* Load Client Manager */
function loadManager(type){
	if(type === 'Client'){
		mixTrack("Settings - Client Manager");
	}
	loadDialogWindow('client-manager','Client Manager',800,500,0,false);
}

function setDialogMessage(id,title){
	$("#"+id).dialog( "option", "title", title);
}

function dialogSaveSearch(){
	closeAllDialogs();
	loadDialogWindow('save-search','ShowSeeker Plus',400,280,0,false);
}

function titlesResetList() {
    datagridTitles.resetFilter();
    $("#searchinput").val("");
}

function genresResetList() {
    $("#genre-filter").val("");	
    datagridGenres.resetFilter();
}

function loadModalMessage(){
	closeAllDialogs();
	loadDialogWindow('reset-ss','ShowSeeker Plus',450,180,1,true);
}

//save proposal
function saveProposal(){
	closeAllDialogs();
	loadDialogWindow('proposal-save','Save Proposal',450,180,0,false);
}



function dialogFlight(){
	
	if(parseInt(proposalid) === 0){
		loadDialogWindow('noproposalloaded', 'ShowSeeker Plus', 450, 180, 1);
		return;
	}

	if(datagridProposal.getCount() == 0){
		loadDialogWindow('nolines','ShowSeeker Plus', 450, 180, 1);		
		return;
	}
	closeAllDialogs();
	loadDialogWindow('calendar-flight','ShowSeeker Plus',550,460,0,false);
}



function dialogDuplicateLinesWait(){
	loadDialogWindow('duplicate-line-wait','ShowSeeker Plus',450,180,0,true);
}

function dialogDuplicateLines(){

	if(datagridProposal.selectedRows().length == 0){
		loadDialogWindow('nolines', 'ShowSeeker Plus', 450, 180, 1);
		return;
	}
	closeAllDialogs();
	loadDialogWindow('duplicate-line', 'ShowSeeker Plus', 200,340,0,false);
	mixpanel.track("Duplicate Line Button");
}

function dialogEditLines(){

	if(datagridProposal.selectedRows().length == 0){
		loadDialogWindow('nolineselected', 'ShowSeeker Plus', 450, 210, 1);
		return;
	}
		
	closeAllDialogs();	

	loadDialogWindow('edit-line','Spots & Rates',450,210,0,false);

}



function dialogClearSearch(){

	if(datagridSearchResults.selectedRows().length > 0){
		closeAllDialogs();	
		loadDialogWindow('clearsearchresults','ShowSeeker Plus',450,180,1,1);
	}
	
	return;
}

function dialogDeleteLines(){

	if(datagridProposal.selectedRows().length == 0){
		loadDialogWindow('nolinestodelete', 'ShowSeeker Plus', 450, 180, 1);
		return;
	}
	
	closeAllDialogs();	
	loadDialogWindow('deletelines','ShowSeeker Plus',450,180,1,1);

}



function dialogEditTitle(){

	if(datagridProposal.selectedRows().length == 0){
		loadDialogWindow('nolinesrotator', 'ShowSeeker Plus', 450, 180, 1);
		return;
	}

	if(datagridProposal.selectedRows().length == 1 && datagridProposal.selectedRows()[0].linetype == 'Fixed'){
		loadDialogWindow('onlyrotators', 'ShowSeeker Plus', 450, 180, 1);
		return;
	}
	
	closeAllDialogs();	

	loadDialogWindow('edit-line-title','Change Line Titles',290,180,0,1);

}

function dialogEditRates(){

	if(datagridProposal.selectedRows().length == 0){
		loadDialogWindow('nolines', 'ShowSeeker Plus', 450, 180, 1);
		return;
	}
		
	closeAllDialogs();	

	loadDialogWindow('edit-line-rate','Rates',290,180,0,1);

}

function dialogEditSpots(){
	
	if(datagridProposal.selectedRows().length == 0){
		loadDialogWindow('nolines', 'ShowSeeker Plus', 450, 180, 1);
		return;
	}
	
	closeAllDialogs();	


	loadDialogWindow('edit-line-spots','Spots',290,180,0,1);

}

function dialogDownloadFile(type){
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
	$("#dialog-zones").dialog("destroy");
	$("#dialog-premiere").dialog("destroy");
	$("#dialog-title").dialog("destroy");
	$("#dialog-window").dialog("destroy");
	$("#dialog-tvr").dialog("destroy");	
	$("#dialog-spots-by-day").dialog("destroy");
}




//load the dialog into memory
function loadDialogWindow(evt,title,w,h,type,modal,downloadformat,showid,pos,dialogData){
	var modal = typeof modal !== 'undefined' ? modal : true;

	
	if(type == 1){
		displayDynamicDialog(evt,title,w,h,type,modal,downloadformat,showid,pos);
	}
	else{	

		var url = 'includes	/dialogs.php?evt='+evt+'&type='+type;
		url += '&downloadformat='+downloadformat+'&proposalid='+proposalid+'&showid='+showid;
		
		$('#dialog-window').data('dialogData',dialogData).load(url, function(){
			displayDialogWindow(w,h,title,modal,pos,dialogData);
		});
	}
	return;
}


function displayDynamicDialog(evt,title,w,h,type,modal,downloadformat,showid,pos){
	if(ssDialogs !== undefined){
		var usrMessage = findMessage(evt);	
		var popup;
		if( parseInt(usrMessage['alert']) === 1){
			popup = 	'<div class="ui-widget">';
			popup +=	'<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">'
			popup +=	'<p> <i class="fa fa-exclamation-triangle fa-2x" style="float: left; margin-right: .3em;"></i>'; 
			popup +=	'<strong>Alert: </strong>'+ usrMessage['message'];
			popup +=	'</p></div>';
			popup +=	'<p></p><center>';
		
			if(usrMessage['event'] !== ""){
				popup +='<button onclick="'+ usrMessage['event']+'; destroyDialog();" class="btn-green">'+usrMessage['eventLabel']+'</button>';
			}
			
			if(parseInt(usrMessage['close']) === 1){
				popup +='<button onclick="destroyDialog(); "id="closeDialogButton" class="btn-red"><i class="fa fa-times-circle"></i> Close</button>';
			}
			
			if(parseInt(usrMessage['ajax']) === 1){
				popup +='<br><img src="i/ajax.gif">';
			}
			popup +=	'</center></div>';
		}
	
		if(parseInt(usrMessage['alert']) === 0){
			popup = 	'<center><h3>'+ usrMessage['message']+'</h3>';

			if(evt === 'addlines'){
				popup +='<br><b>Now creating <span id="avail_current">0</span> of <span id="avail_total">0</span></b><br><br>';
			}
			
			if(parseInt(usrMessage['ajax']) === 1){
				popup += '<img src="i/ajax.gif"><br>';
			}
			
			if(parseInt(usrMessage['close']) === 1){ 
				popup += '<br><button onclick="destroyDialog()" id="closeDialogButton" class="btn-red"><i class="fa fa-times-circle"></i> Close</button>';
			}
			popup += 	'</center>';
		}	
			

		$('#dialog-window').html(popup).dialog({
			modal: modal,
			open:function(e,ui){
				displayDialogWindow(w,h,title,modal,pos);	
				$('button').button();
			}
			});
		$('button').button();			
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
function displayDialogWindow(w,h,title,modal,position){
	var dialogSettings 			= {};
	dialogSettings.width 		= w;
	dialogSettings.height 		= h;
	dialogSettings.resizable 	= false;
	dialogSettings.title 		= title;
	dialogSettings.modal 		= modal;
	dialogSettings.draggable 	= true;
	dialogSettings.dialogClass 	= "pepper";
	dialogSettings.open 		= function( event, ui ){$('.ui-dialog :button').blur()};	
	
	if(position){
		dialogSettings.position = position;
	}
	$("button").button();
	$("#dialog-window").dialog(dialogSettings);
	$("#dialog-window").dialog("moveToTop");	
};





//dialog clone proposal
function dialogCloneProposal(){
	var eventrows = datagridProposalManager.getSelectedRows();

	$('input[name=flight]').attr('checked', false);

	if(eventrows.length === 0){
		loadDialogWindow('leastone', 'ShowSeeker Plus', 450, 180, 1);
		return;
	}
	
	if(eventrows.length > 1) {
		loadDialogWindow('onlyOneProposal', 'ShowSeeker Plus', 450, 180, 1);
		return;
	}

	//load the dialog
	loadDialogWindow('proposal-clone', 'Copy Proposal', 295, 490, 0, false,'','',[770,85]);
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
	//close other dialogs
	$("#dialog-keyword,#dialog-actor").dialog("close");

	if($("#dialog-title").dialog("isOpen")===true) {

		if(type == 1){
			$('#dialog-title').dialog('option', 'title', 'Title Search');
			//$("#dialog-title-search-btn,#dialog-title-save-btn-reminder").hide();
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

	$("#dialog-genre" ).dialog({
		width:530,
		height:520,
		position: [350,75],
		resizable: false,
		open: function(event,ui){
			searchGenres();
		},
		dialogClass: "pepper"
	});
}



// TV RATINGS
function dialogTVR(){
	if($("#dialog-tvr").dialog( "isOpen" )===true) {
		$("#dialog-tvr").dialog("moveToTop");
		return;
	}

	$("#dialog-tvr" ).dialog({
		width:265,
		height:320,
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

	var p = [300,200];

	if($("#dialog-window").dialog( "isOpen" )===true){
		p = [510,105];
   	}	
	$("#dialog-daysofweek" ).dialog({
		width:265,
		height:300,
		position: p,
		resizable: false,
		dialogClass: "pepper"
	});
}

function dialogRegionalPackages(){
	closeAllDialogs();
	
	$('body').append('<div id="dialog-custom-packages" style="display:none;"></div>');
	var url = 'includes/dialogs.php?evt=regional-packages&type=0&downloadformat=&proposalid=0&showid=';
	$('#dialog-custom-packages').load(url, function() {
		$("#dialog-custom-packages").dialog({
			width:809,
			height:500,
			position: [300,50],
			resizable: false,
			title: "ShowSeeker Plus Custom Packages",
			modal: false,
			draggable: true,
			dialogClass: "pepper",
			open: function( event, ui ) {
				$('.ui-dialog :button').blur();
			},
			close: function( event, ui ){
				$('#dialog-custom-packages').remove();
			}
		});
	});
}



