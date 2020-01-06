//dialogs
function dialogHotProgramming(){
	$("#dialog-hot-program").dialog({
		width:382,
		height:500,
		resizable: false,
		modal: false,
		draggable: true,
		closeOnEscape: false,
		dialogClass: "pepper",
		open: function( event, ui ) {
			//getShowTitles();
		}
	});
}



//dialogs
function dialogImportHotProgramming(){
	$("#dialog-import-hot-programming").dialog({
		width:300,
		height:150,
		resizable: false,
		modal: true,
		draggable: false,
		closeOnEscape: false,
		dialogClass: "pepper",
		open: function( event, ui ) {
			//getShowTitles();
		}
	});
}






function dialogCloneRatecard(){
  var row = datagridRatecards.selectedRows();

  if(row.length != 1){
    loadMessage('singleshow');
    return;
  }

    var name = row[0].ratecard+" - Clone";
  	$("#ratecard-form-name-clone").val(name);

	$("#dialog-clone-ratecard").dialog({
		width:310,
		height:120,
		resizable: false,
		modal: true,
		draggable: false,
		closeOnEscape: false,
		dialogClass: "pepper"
	});
}







function dialogImportRates(){
  var row = datagridRatecards.selectedRows();

  if(row.length != 1){
    loadMessage('singleshow');
    return;
  }

	$("#dialog-import-rates").dialog({
		width:310,
		height:200,
		resizable: false,
		modal: true,
		draggable: false,
		closeOnEscape: false,
		dialogClass: "pepper"
	});
}








function dialogEditRatecardName(){
	var rows = datagridRatecards.selectedRows();
	
	if(rows.length == 0){
		loadMessage('selectcolumns');
		return;
	}

	$("#dialog-edit-ratecard-name").dialog({
		width:290,
		height:200,
		resizable: false,
		modal: true,
		draggable: false,
		closeOnEscape: false,
		dialogClass: "pepper",
		open: function( event, ui ) {
			var row = datagridRatecards.selectedRows()[0];
			$('#form-name-edit').val(row['ratecard']);
			$('#form-special-edit').val(row['special']);
			console.log(row);
		}
	});
}






function dialogEditRatecardDates(){
	var rows = datagridRatecards.selectedRows();
	
	if(rows.length == 0){
		loadMessage('selectcolumns');
		return;
	}

	$("#dialog-edit-ratecard-dates").dialog({
		width:290,
		height:200,
		resizable: false,
		modal: true,
		draggable: false,
		closeOnEscape: false,
		dialogClass: "pepper"
	});
}






function dialogDaypartGroup(){
	$("#dialog-daypart-group").dialog({
		width:270,
		height:140,
		resizable: false,
		modal: true,
		draggable: false,
		closeOnEscape: false,
		dialogClass: "pepper"
	});
}


function dialogSaveChanges(){
	$("#dialog-saving").dialog({
		width:450,
		height:180,
		resizable: false,
		modal: true,
		draggable: false,
		closeOnEscape: false
	});
}



function dialogDayparts(){
	$("#dialog-dayparts").dialog({
		width:310,
		height:340,
		resizable: false,
		modal: true,
		draggable: false,
		closeOnEscape: false,
		dialogClass: "pepper"
	});
}



function dialogDaypartsCopy(){
	var rows = datagridDayparts.selectedRows();
	
	if(rows.length == 0){
		loadMessage('selectdayparts');
		return;
	}

	$("#dialog-dayparts-copy").dialog({
		width:310,
		height:200,
		resizable: false,
		modal: true,
		draggable: false,
		closeOnEscape: false,
		dialogClass: "pepper"
	});
}






function dialogZoneCreate(){
	$("#dialog-zone-create").dialog({
		width:310,
		height:340,
		resizable: false,
		modal: true,
		draggable: false,
		closeOnEscape: false,
		dialogClass: "pepper",
		open: function( event, ui ) {
			var x = $("#zone-form-start").val() + "-" + $("#zone-form-end").val();
			$("#zone-form-name").val(x);
		}
	});
}




function dialogLoading(){
	$("#dialog-loading").dialog({
		width:450,
		height:180,
		resizable: false,
		modal: true,
		draggable: false,
		closeOnEscape: false
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
		dialogClass: "pepper"
	});
}

