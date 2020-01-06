

function daypartGroupCreate(){
	var name = $('#daypartgroup-form-name').val();

	$.post( "services/saveDaypart.php", {
		eventtype: "newgroup", 
		name: name
	}).done(function(data){
		getDaypartGroups();
		$("#dialog-daypart-group").dialog("destroy");
	});

}






function getDaypartGroups(){
	$.getJSON("services/getDaypartGroups.php", function(data){
		
	$('#daypart-form-group').find('option').remove().end();
	$('#zone-form-groupid').find('option').remove().end();


		$.each(data.data, function(i, value) {
			$('#daypart-form-group').append($("<option></option>").attr("value", value.id).text(value.name));
			$('#zone-form-groupid').append($("<option></option>").attr("value", value.id).text(value.name));
		});
	});
}






function getDayparts(mode){
	$.getJSON("services/getDayparts.php", function(data){
		datagridDayparts.populateDatagrid(data.data);
		if(mode == 'init'){
			datagridDayparts.collapseAllGroups();
		}
		
	});
}





function daypartEvents(){
	var groupid = $('#daypart-form-group').val();
	var name = $('#daypart-form-name').val();
	var starttime = $('#daypart-form-start').val();
	var endtime = $('#daypart-form-end').val();
	var days = $('#daypart-form-days').val();
	var eventtype = "add";


	if(daypartEditMode == true){
		eventtype = "edit";
	}


	$.post( "/services/saveDaypart.php", {
		eventtype: eventtype, 
		id: daypartEditId,
		groupid: groupid, 
		name: name, 
		starttime:starttime, 
		endtime:endtime, 
		days:days }).done(function(data){
			datagridDayparts.unSelectAll();
			getDayparts();
			$("#dialog-dayparts").dialog("destroy");
	});

}




function processItem(action){
  if(action == 'daypart'){
    var rows = datagridDayparts.selectedRows();

    $.post( "services/saveDaypart.php", {
      eventtype: "remove", 
      rows: rows}).done(function(data){
        $("#dialog-message").dialog("destroy");
        getDayparts();
    });
  }
}




//ZONES
function getZones(){
	$('#zonesid').append($("<option></option>").attr("value", 0).text('Select Zone'));
	$.getJSON("services/getZones.php", function(data){
		$.each(data.data, function(i, value) {
			$('#zone-form-zoneid').append($("<option></option>").attr("value", value.id).text(value.name));
			$('#zonesid').append($("<option></option>").attr("value", value.id).text(value.name));
		});
	});
}


function createRatecard(){
	var zoneid = $('#zone-form-zoneid').val();
	var groupid = $('#zone-form-groupid').val();
	var startdate = $('#zone-form-start').val();
	var enddate = $('#zone-form-end').val();
	var name = $('#zone-form-name').val();
	var desc = $('#zone-form-desc').val();


	startdate = Date.parse(startdate + " 00:00:00").toString("yyyy/MM/dd");
	enddate = Date.parse(enddate + " 00:00:00").toString("yyyy/MM/dd");

	$.post( "services/postZones.php", {
      eventtype: "newzone", 
      zoneid: zoneid,
      groupid: groupid,
      startdate:startdate,
      enddate:enddate,
      name:name,
      desc:desc
  	}).done(function(data){

  		//post to update zones
	    $.post( "update.php", {id: data
	  	}).done(function(data){
	  		$("#dialog-zone-create").dialog("destroy");
	  		getRatecards();
	    });

    });

}



function deleteSelectedatecards(){
	var ids = datagridRatecards.selectedIds();

	$.post( "services/eventRatecards.php", {
		eventtype: "deleteratecards", 
		ids: ids
		}).done(function(data){
			getRatecards();
			$("#dialog-message").dialog("destroy");
	});
}



function getRatecards(){
	$.getJSON("services/getRatecards.php", function(data){

		$('#ratecard-id-copy').find('option').remove().end();
		$('#ratecard-id-clone').find('option').remove().end();

		$.each(data.data, function(i, value) {
			var name = value.ratecard + " - " + value.zone;
			$('#ratecard-id-copy').append($("<option></option>").attr("value", value.id).text(name));
			$('#ratecard-id-clone').append($("<option></option>").attr("value", value.id).text(name));
		});

		datagridRatecards.populateDatagrid(data.data,firstLoad);
		datagridRatecards.groupByColumn('ratecard');
	});
}




function saveRatecard(){
	issaving = true;
	var data = datagridRatecardPricing.getData();
	$.post( "services/zones.php", {
		eventtype: "savechanges", 
		ratecardid: ratecardid, 
		data: data
		}).done(function(data){
			$("#dialog-saving").dialog("destroy");
			issaving = false;
	    });
}


function editRatecardDates(){
	$("#dialog-edit-ratecard-dates").dialog("destroy");

	var sdate = $('#form-start-date-edit').val();
	var edate = $('#form-end-date-edit').val();
	sdate = Date.parse(sdate + " 00:00:00").toString("yyyy-MM-dd");
    edate = Date.parse(edate + " 00:00:00").toString("yyyy-MM-dd");

	var rows = datagridRatecards.selectedIds();


	$.post( "services/zones.php", {
		eventtype: "editratecardates", 
		rows: rows,
		sdate: sdate,
		edate: edate
		}).done(function(data){
			$("#dialog-saving").dialog("destroy");
			getRatecards();
	});
}



function editRatecardName(){
	$("#dialog-edit-ratecard-name").dialog("destroy");

	var name = $('#form-name-edit').val();
	var rows = datagridRatecards.selectedIds();
	var special = $('#form-special-edit').val();

	$.post( "/services/zones.php", {
		eventtype: "editratecardname", 
		rows: rows,
		name: name,
		special: special
		}).done(function(data){
			$("#dialog-saving").dialog("destroy");
			getRatecards();
	});
}






function importRates(){
  	var copyid = $('#ratecard-id-copy').val();
  	$("#dialog-import-rates").dialog("destroy");
  	dialogSaveChanges();
	  $.post( "services/eventRatecards.php", {
	    eventtype: 'importrates', 
	    ratecardid: ratecardid,
	    copyid: copyid
	    }).done(function(data){
	    	loadRatecardByID(ratecardid);
	      $("#dialog-saving").dialog("destroy");
	  });
}





function cloneRatecard(){  	
  	$("#dialog-clone-ratecard").dialog("destroy");
  	var row = datagridRatecards.selectedRows();
  	var id = row[0].id;
  	var name = $("#ratecard-form-name-clone").val();

  	dialogSaveChanges();
	  $.post( "services/eventRatecards.php", {
	    eventtype: 'clonecard', 
	    id: id,
	    name: name
	    }).done(function(data){
	    	getRatecards();
	      	$("#dialog-saving").dialog("destroy");
	  });
}



function getRules(mode){
	$.getJSON("services/eventRules.php?eventtype=getrules", function(data){
		var fixedpct = data.fixedpct;
		var fixedseconds = data.fixedseconds;

		$('#rules-fixedpct').val(fixedpct);
		$('#rules-fixedseconds').val(fixedseconds);
	});
}



function updateRules(){
	dialogSaveChanges();
	var fixedpct = $('#rules-fixedpct').val();
	var fixedseconds = $('#rules-fixedseconds').val();

	$.post( "services/eventRules.php", {
	    eventtype: 'updaterules', 
	    fixedpct: fixedpct,
	    fixedseconds: fixedseconds
	    }).done(function(data){
	      	$("#dialog-saving").dialog("destroy");
	});
}











