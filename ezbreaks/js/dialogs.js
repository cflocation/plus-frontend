//dialogs
function closeAllDialogs(){
	$("#dialog-window").dialog("destroy");
}

function closeAllAltDialogs(){
	$("#dialog-window-alt").dialog("destroy");
}



function openCopyDayparts(){
	var id = $('#markets-id').val();
	if(id == 0){
		loadDialogWindow('warning-no-market-selected','Warning',290,150);
		return;
	}

	var rows = datagridDaypartSelected.selectedRows();
	if(rows.length == 0){
		loadDialogWindow('warning-no-rows-selected','Warning',290,150);
		return;
	}
	loadDialogWindow('wizard-import-dayparts','Copy Dayparts',290,350);
}




//load the dialog into memory
function loadDialogWindow(type,title,h,w,e){
	e = typeof e !== 'undefined' ? e : 0;
	
	var url = 'include/dialogs.php?d='+type+'&e='+e;
	$('#dialog-window').load(url, function() {
		displayDialogWindow(h,w,title);
	});
}

//display the dialog
function displayDialogWindow(h,w,title){
	//console.log(url);
	$("#dialog-window").dialog({
		width:h,
		height:w,
		resizable: false,
		title: title,
		modal: true,
		draggable: false,
		open: function( event, ui ) {
			$('.ui-dialog :button').blur();
		}
	});
}


/* Global load errors */
function loadMessage(type){
	var url = 'includes/messages.php?type='+type;
	$('#dialog-message').load(url, function() {
		displayError();
	});
}

//load the error dialog
function displayError(){
	$("#dialog-message").dialog("destroy");
	$("#dialog-message").dialog({
		width:400,
		height:150,
		resizable: false,
		title: "Dialog Title"
	});
}




//load the dialog into memory
function loadDialogWindowAlt(type,title,h,w){
	//$("#dialog-window").dialog("destroy");
	var url = 'include/dialogs.php?d='+type;
	$('#dialog-window-alt').load(url, function() {
		displayDialogWindowAlt(h,w,title);
	});
}

//display the dialog
function displayDialogWindowAlt(h,w,title){
	//console.log(url);
	$("#dialog-window-alt").dialog({
		width:h,
		height:w,
		resizable: false,
		title: title,
		modal: true,
		draggable: false,
		open: function( event, ui ) {
			$('.ui-dialog :button').blur();
		}
	});
}