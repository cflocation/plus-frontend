 //datagrids
var datagridNetworks = new DatagridNetworks();
var datagridViewer = new DatagridViewer();
var datagridDownloadSchedule = new DatagridDownloadSchedule();
var datagridBreaks = new DatagridBreaks();
var datagridDownloadUpdateSchedule = new DatagridDownloadUpdateSchedule();
var datagridChanges = new DatagridChanges();
var datagridQueue = new DatagridQueue('datagrid-queue');


// var datagridNetworkSelector = new DatagridNetworkSelector();

if($('#is_superadmin_true').length == 1)
{
	var datagridCustomBreaks = new DatagridCustomBreaks();
	var datagridCustomBreakRulesets = new DatagridCustomBreakRulesets();
	var datagridCustomTitles = new DatagridCustomTitles();
	var datagridAccessNetworks = new DatagridAccessNetworks();
}




//varibles
var networkid = 0;
var tzid = 0;
var currentfile;
var groupid = 0;
var editid = 0;
var corporationid = 0;


//stroage
//lets store everything permanatly on the page load 
//so we don't have to call it evey time on the add edit network event
var timezones;
var netwroks;
var breaks;


var apiUrl 		= "https://plusapi.showseeker.com/";

$(document).ready(function() {
	$.ajax({
			type:'get',		
			url: apiUrl + 'user/info',
			headers:{"User":localStorage.getItem("userId"), "Api-Key":localStorage.getItem("apiKey")},
			success:function(resp){
				if( !resp.roles.ezBreaks ){
					window.location.href = '../login.php?logout=true&app=plus';
				}
				else{
                    checkApplicationStatus(1);
                    getGroups();
                    getSessionState();
                    getTimezoneList();
                    getNetworkList();
                    getCorporationList();
                    getBreaks(0);
                    getChangeList();
                    getQueue();
                
                    //populateBreaks();
                    //getBreaks();
                    //getNetworks();
                    getNetworkListForCustomBreaks();
                    toggleOn('sidebar-item-group-networks',3);
                    toggleOn('sidebar-item-group-viewer',2);
                    toggleOn('sidebar-item-group-break',2);
                	chooseUpdateScheduleType(1);
                	getUserDownloadSchedules();
                    getUserUpdateDownloadSchedules();
                    if($('#is_superadmin_true').length == 1)
                	{
                    	getNetworkCustomRules(0);
                    }
                    getCustomBreakTemplates();
				}
			}
	});


    
});



setInterval(function () {getQueue()}, 15000);


//queue
function getQueue() 
{
    $.getJSON("services/queue.php", function(data) {
    	datagridQueue.populateDatagrid(data);
    	datagridQueue.renderGrid();
    });
}





//change emails
function getChangeList() 
{
    $.getJSON("services/tracker.php?eventtype=list", function(data) {
    	datagridChanges.populateDatagrid(data.data);
    	datagridChanges.renderGrid();
    });
}




function chooseUpdateScheduleType(type)
{
	toggleOn('sidebar-update-scheduler-types',type);

	if(type==1)
	{
		$('#sidebar-update-scheduler-numweeks').show();
	} else
	{
		$('#sidebar-update-scheduler-numweeks').hide();
	}
}

//corporations
function getCorporationList() 
{
    $.getJSON("services/corporations.php?eventtype=list", function(data) {

    	$('#sidebar-access-corporation').append($("<option></option>").attr("value", 0).text("Select Corporation"));

   		$.each(data.data, function(i, value) {
            $('#sidebar-access-corporation').append($("<option></option>").attr("value", value.id).text(value.name));
        });
    });
}

//get permissions
function getPermissionGroups(id) 
{
	$('#sidebar-access-group').empty();
	datagridAccessNetworks.emptyGrid();

	if(id == 0){
		return;
	}

    $.getJSON("services/permissions.php?eventtype=list&id="+id, function(data) {

    	$('#sidebar-access-group').append($("<option></option>").attr("value", 0).text("Select Group"));

   		$.each(data.data, function(i, value) {
            $('#sidebar-access-group').append($("<option></option>").attr("value", value.id).text(value.name));
        });

   		getNetworksByCorporation(id);
    });
}

//corporation network list
function getNetworksByCorporation(id)
{
	var url = "services/permissions.php?eventtype=networklist&id="+id;
	$.getJSON(url, function(data) {
		datagridAccessNetworks.populateDatagrid(data.data);
    });
}



//load permissions
function loadPermissionsByGroup(id) 
{
	datagridAccessNetworks.unSelectAll();

	var url = "services/permissions.php?eventtype=getselectednetworks&id="+id;
	$.getJSON(url, function(data) {
		datagridAccessNetworks.selectRowsWithNetworkInstances(data);
    });
}







function saveAccessNetworks(){
	var list = datagridAccessNetworks.getCheckedNetworkInstances();
	var id = $('#sidebar-access-group').val();

	$.post("services/permissions.php", {
        eventtype: "saveaccesschanges",
        id:id,
        list:list,
    }).done(function(data){

    });
}








//New Add network from dialog this removed it from the sidebar
function dialogAddNetwork(e) {
	if(groupid == 0){
		loadDialogWindow('warning-no-group-selected', 'Warning', 380, 160);
		return;
	}

	if(e == 0){
		loadDialogWindow('add-edit-network', 'Add Network to Group', 380, 380);
	}else{
		loadDialogWindow('add-edit-network', 'Edit Selected Network', 380, 380, e);
	}	
}


//GET BREAK STRUCTURE
function getBreakStructure(id){
	$('#sidebar-item-breaks-edit').val(id);
	getBreaksFromID(id);
	checkApplicationStatus(7);

}


function convert(input) {
    var parts = input.split(':'),
        minutes = +parts[1],
        seconds = +parts[2];
    return (minutes * 60 + seconds).toFixed(3);
}


function IsNumeric(input){
    return (input - 0) == input && (''+input).replace(/^\s+|\s+$/g, "").length > 0;
}


//EDIT NETWORK BREAK
function editNetworkBreak(id) {
	editid = id;
	dialogAddNetwork('edit');
	return;

	var row = datagridNetworks.selectedRows();
	var breakid = row[0].breakid;
	var instance = row[0].instancecode;
	var tzid = row[0].timezoneid;
	var networkid = row[0].networkid;
	$('#sidebar-item-instance').val(instance);

	//$('#sidebar-item-timezone').val(tzid);
	//setupGroupbyTimezone();

	//$('#sidebar-network-edit-title').css('display', 'inline');
	$('#sidebar-network-edit-button').css('display', 'inline');
	$('#sidebar-network-add-buttons').css('display', 'none');

	//$('#sidebar-breaks-group').css('opacity', '0.3');
	//$('#sidebar-breaks-group').attr('disabled', 'disabled');

	$('#sidebar-item-timezone').val(tzid);

	setupGroupbyTimezone(breakid)
	//$('#sidebar-item-breaks').val(bid);
	


	var x = ['sidebar-breaks-group','network-start-date','network-end-date','sidebar-item-timezone','sidebar-item-network'];
	editFormElements(x,1);
	editid = id;
}


function resetEditNetworkBreak() {
	$('#sidebar-item-instance').val("");
	//$('#sidebar-network-edit-title').css('display', 'inline');
	$('#sidebar-network-edit-button').css('display', 'none');
	$('#sidebar-network-add-buttons').css('display', 'inline');

	var x = ['sidebar-breaks-group','network-start-date','network-end-date','sidebar-item-timezone','sidebar-item-network'];
	editFormElements(x,0);
}




function updateNetworkinGroup()
{
	var breakid 		= $('#sidebar-item-breaks').val();
	var instancecode 	= $('#sidebar-item-instance').val();
	var id 				= editid;
	var livegrouping 	= ($('#chk-live-grouping').is(':checked'))?'Y':'N';

	$.post("services/groups.php", {
        eventtype: "updatenetwork",
        id:id,
        breakid:breakid,
        instancecode:instancecode,
        livegrouping:livegrouping
    }).done(function(data) {
    	//resetEditNetworkBreak();
    	getNetworksForGroup(groupid,false);
    });
}




function editFormElements(items,type){
	$.each(items, function( index, value ) {
		if(type == 1){
			$('#'+value).css('opacity', '0.3');
			$('#'+value).attr('disabled', 'disabled');
		}else{
			$('#'+value).css('opacity', '1');
			$('#'+value).removeAttr("disabled");     
		}
	});
}




//GET FULL BREAK LIST
function populateBreaks() {
    $.getJSON("services/breaks.php?eventtype=breaksfull", function(data) {
    	//$('#sidebar-breaks-networks').append($("<option></option>").attr("value", 0).text("Select Break Structure"));
   		
    	$('#sidebar-item-breaks-edit').append($("<option></option>").attr("value", 0).text("Select Break Structure"));

   		$.each(data.fixed, function(i, value) {
   			//sidebar-breaks-networks
   			//$('#sidebar-breaks-networks').append($("<option></option>").attr("value", value.id).text(value.name));
            $('#sidebar-item-breaks-edit').append($("<option class='grid-blue'></option>").attr("value", value.id).text(value.name));
        });

        $.each(data.custom, function(i, value) {
   			//sidebar-breaks-networks
   			//$('#sidebar-breaks-networks').append($("<option></option>").attr("value", value.id).text(value.name));
            $('#sidebar-item-breaks-edit').append($("<option></option>").attr("value", value.id).text(value.name));
        });
    });
}





//BREAK LIST
function getBreaks(tzid) {
    $.getJSON("services/breaks.php?eventtype=breaks&tzid="+tzid, function(data) {
    	breaks = data;
    	populateBreaksViewier(breaks);
    });
}





function populateBreaksViewier(data) {
	$('#sidebar-item-breaks-edit').append($("<option></option>").attr("value", 0).text("Select Break Structure"));
   		
   	$.each(data.fixed, function(i, value) {
    	$('#sidebar-item-breaks-edit').append($("<option class='grid-blue'></option>").attr("value", value.id).text(value.name));
    });

    $.each(data.custom, function(i, value) {
        $('#sidebar-item-breaks-edit').append($("<option></option>").attr("value", value.id).text(value.name));
    });
}
 






function populateBreaks(data) {
	$('#sidebar-item-breaks').append($("<option></option>").attr("value", 0).text("Select Break Structure"));
   		
   	$.each(data.fixed, function(i, value) {
    	$('#sidebar-item-breaks').append($("<option class='grid-blue'></option>").attr("value", value.id).text(value.name));
    });

    $.each(data.custom, function(i, value) {
        $('#sidebar-item-breaks').append($("<option></option>").attr("value", value.id).text(value.name));
    });
    //$('#sidebar-item-breaks').val(breakid);
}
 












//BREAKS
function getNetworks() {
    $.getJSON("services/breaks.php?eventtype=networks", function(data) {
    	$('#sidebar-breaks-networks').append($("<option></option>").attr("value", 0).text('Select Network'));
   		$.each(data.data, function(i, value) {
            $('#sidebar-breaks-networks').append($("<option></option>").attr("value", value.id).text(value.name));
        });
    });
}


function getBreaksFromID(id) {
	var url = "services/breaks.php?eventtype=breaklist&id="+id;
    $.getJSON(url, function(data) {
    	datagridBreaks.populateDatagrid(data.data);
    	processAvailCount(data.data);
    	var title = $('#sidebar-item-breaks-edit option:selected').text();
    	$('#label-break-name').html('<b>'+title+'</b>');
    });
}


function processAvailCount(data){
	var mon = 0;
	var tue = 0;
	var wed = 0;
	var thu = 0;
	var fri = 0;
	var sat = 0;
	var su = 0;

	$.each(data, function(i, value) {
		mon += processAvialValue(value.d1);
		tue += processAvialValue(value.d2);
		wed += processAvialValue(value.d3);
		thu += processAvialValue(value.d4);
		fri += processAvialValue(value.d5);
		sat += processAvialValue(value.d6);
		su += processAvialValue(value.d7);
	});

	$('#sidebar-breaks-mon').val(mon);
	$('#sidebar-breaks-tue').val(tue);
	$('#sidebar-breaks-wed').val(wed);
	$('#sidebar-breaks-thu').val(thu);
	$('#sidebar-breaks-fri').val(fri);
	$('#sidebar-breaks-sat').val(sat);
	$('#sidebar-breaks-sun').val(su);
}


function processAvialValue(val){
	if(val != undefined){
		return val/30;
	}
	return 0;
}







//SESSION //ADMIN //ETC
function getSessionState() {
	$.getJSON("services/session.php", function(data) {
		//menu-3
		if(data.sa == true){
			$('#menu-3').css('display', 'inline');
			$('#menu-4').css('display', 'inline');
			$('#menu-6').css('display', 'inline');
		}
	});
}



//GROUPS
function getGroups() {
    $.getJSON("services/groups.php?eventtype=list", function(data) {
    	//$('#sidebar-breaks-group').append($("<option></option>").attr("value", 0).text('Select Group'));
    	$('#sidebar-breaks-group').append($("<option></option>").attr("selected","selected").attr("value", 0).text('All Networks'));
   		$.each(data.data, function(i, value) {
            $('#sidebar-breaks-group').append($("<option></option>").attr("value", value.id).text(value.name));
            $('#sidebar-groups-choice-list').append('<li><input id="sidebar-scheduler-groups-'+i+'" type="checkbox" value="'+value.id+'"><label for="sidebar-scheduler-groups-'+i+'">'+value.name+'</label></li>');
			$('#sidebar-update-scheduler-groups-choice-list').append('<li><input id="sidebar-update-scheduler-groups-'+i+'" type="checkbox" value="'+value.id+'"><label for="sidebar-update-scheduler-groups-'+i+'">'+value.name+'</label></li>');
        });
   		
   		if(data.data.length > 0){
   			getNetworksForGroup(0,false);
   		}
   		
   		/*if(data.data.length == 1){
   			$("#sidebar-breaks-group option[value="+data.data[0].id+"]").attr('selected', 'selected');
   			getNetworksForGroup(data.data[0].id,false);
   		}*/
    });
}




function getNetworksForGroup(id,load){
	/*if(id == 0){
		$('#sidebar-tab-1-sub').css('display', 'none');
		$('#top-bar-group-options').css('display', 'none');
		
	}else{
		$('#sidebar-tab-1-sub').css('display', 'inline');
		$('#top-bar-group-options').css('display', 'inline');
	}*/

	var url = "services/groups.php?eventtype=grouplist&id="+id;
	$.getJSON(url, function(data) {

		var groupname = $('#sidebar-breaks-group option:selected').text();
		var lbl = "<b>" + groupname +"</b>";
		$('#label-group-name').html(lbl);

		if(data.data.length > 0){
			$('#sidebar-tab-1-sub').css('display', 'inline');
			$('#top-bar-group-options').css('display', 'inline');
		} else {
			$('#sidebar-tab-1-sub').css('display', 'none');
			$('#top-bar-group-options').css('display', 'none');
		}

		datagridNetworks.populateDatagrid(data.data);
	
		datagridNetworks.groupByColumn("name");
		datagridNetworks.collapseAllGroups();

		groupid = id;

		if(typeof autoloadViewer !== 'undefined' && autoloadViewer){
	    	loadDialogWindow('load', 'Loading Please Wait', 380, 160);
	    	doAutoLoadViwer();
	    }
    });
}


//TIMEZONES
function getTimezoneList() {
	//$('#sidebar-item-timezone').append($("<option></option>").attr("value", 0).text("Select Timezone"));
    $.getJSON("services/groups.php?eventtype=timezones", function(data) {
    	timezones = data.data;
    });
}


function populateTimezoneList(data) {
	$.each(data, function(i, value) {
    	$('#sidebar-item-timezone').append($("<option></option>").attr("value", value.id).text(value.name));
    });
}






//NETWORKS
function getNetworkList() {
    $.getJSON("services/groups.php?eventtype=networks", function(data) {
    	networks = data.data;
    });
}


function populateNetworkList(data) {
	$.each(data, function(i, value) {
    	$('#sidebar-item-network').append($("<option></option>").attr("value", value.id).text(value.name));
    });
}





//UPDATE LIST
function updateNetworkListFromSystem(type){
	getNetworkList(type);
}



//ADD NETWORK TO GROUP
function addNetworktoGroup()
{
	var breakgroupsid 	= $('#sidebar-breaks-group').val();
	var timezoneid 		= $('#sidebar-item-timezone').val();
	var tmsid 			= $('#sidebar-item-network').val();
	var instancecode 	= $('#sidebar-item-instance').val();
	var breakid 		= $('#sidebar-item-breaks').val();
	var livegrouping 	= ($('#chk-live-grouping').is(':checked'))?'Y':'N';

	$.post("services/groups.php", {
        eventtype: "addnetwork",
        breakgroupsid:breakgroupsid,
        timezoneid:timezoneid,
        tmsid:tmsid,
        instancecode:instancecode,
        breakid:breakid,
        livegrouping:livegrouping
    }).done(function(data) {
    	$('#sidebar-item-instance').val("");
    	getNetworksForGroup(groupid,false);
    });

}



function confirmNetworksDelete() {
    loadDialogWindow('confirm-delete-group-network', 'Confirm Delete', 380, 150);
}


function eventDeleteGroupNetwork(){
	var ids = datagridNetworks.selectedIds();

	$.post("services/groups.php", {
        eventtype: "deletenetworks",
        ids:ids
    }).done(function(data) {
    	getNetworksForGroup(groupid,false);
    	closeAllDialogs();
    });
}



function setupGroupbyTimezone(breakid){
	var group = $('#sidebar-item-timezone option:selected').text();
	var tzid = $('#sidebar-item-timezone option:selected').val();
	//datagridNetworks.collapseAllGroups();
	//datagridNetworks.expandSelectedGroup(group);

	if(tzid != 0){
		getBreaks(tzid,breakid);
	}
}




function updateSidebarForTimezoneSelection(t){
	var x = ['sidebar-item-network','sidebar-item-breaks','sidebar-item-instance'];
	editFormElements(x,t);
}



//BREAK TYOES
function getBreakTypes() {
    $.getJSON("services/groups.php?eventtype=breaktypes", function(data) {
   		$.each(data.data, function(i, value) {
            $('#sidebar-item-breaktype').append($("<option></option>").attr("value", value.id).text(value.name));
        });
    });
}






















//TIMEZONES
//list timezones for all the zones
function getTimezones() {
    $.getJSON("services/networks.php?eventtype=list", function(data) {
        $('#sidebar-breaks-timezone').append($("<option></option>").attr("value", 0).text('Select Timezone'));
        
        var tzones = [];
        $.each(data.data, function(i, value) {
            tzones.push(value.id);
            $('#sidebar-breaks-timezone, #sidebar-scheduler-timezone').append($("<option></option>").attr("value", value.id).text(value.name));
        });
        $('#sidebar-scheduler-timezone').prepend($("<option></option>").attr("value", tzones.join(',')).text('All Timezones').attr("selected","selected"));

        $.each(data.networks, function(i, value) {
        	var n = value.callsign + " - " + value.name;
        	var n2 = "<b>"+value.callsign + "</b> - " + value.name;
            $('#sidebar-custom-breaks-network, #sidebar-custom-titles-network').append($("<option></option>").attr("value", value.id).text(n));
            $('#sidebar-neworks-choice-list').append('<li><input id="sidebar-scheduler-nework-'+i+'" type="checkbox" value="'+value.id+'" data-callsign="'+value.callsign+'" checked="checked"><label for="sidebar-scheduler-nework-'+i+'">'+"<img width='25' src='http://ww2.showseeker.com/images/_thumbnailsW/"+value.filename+"'>  "+n2+'</label></li>');
        });

        bindSchedulerNetChoiceList();
    });
}

//get the network list based on a timezone id
function getNetworksForTimezone(timezoneid){
	tzid = timezoneid;
	if(timezoneid == 0){
		$('#label-market-timezone').html("<b>No Timezone Selected</b>");
		datagridNetworks.emptyGrid();
		$('#sidebar-tab-1-sub').css('display', 'none');
		$('#top-bar-ratecard-options').css('display', 'none');
		return;
	}

	$.getJSON("services/networks.php?eventtype=networklist&timezoneid="+timezoneid, function(data) {
		datagridNetworks.populateDatagrid(data.data);

		var lbl = "<b>" + data.header.tzname + " (" + data.header.tzabbreviation + ")</b>";
		$('#label-market-timezone').html(lbl);
		$('#sidebar-tab-1-sub').css('display', 'inline');
		$('#top-bar-ratecard-options').css('display', 'inline');
    });
}
//End Timezones




//Viewer
//$startdate 		= (isset($_REQUEST['startdate']))?$_REQUEST['startdate']:'';
//$enddate 		= (isset($_REQUEST['enddate']))?$_REQUEST['enddate']:'';

function getNetworkBreaksForViewer(id){
	resetEditNetworkBreak();
	loadDialogWindow('load', 'Loading Please Wait', 380, 160);
	var row = datagridNetworks.selectedRows();

	var lbl = "<b>" + row[0].name + "</b>";
	$('#label-network-viewer').html(lbl);
	
	var startdate = $('#network-start-date').val();
	var enddate = $('#network-end-date').val();

	var url = "services/networks.php?eventtype=viewnetwork&id="+id+"&tzid="+tzid+"&startdate="+startdate+"&enddate="+enddate;
	$.getJSON(url, function(data) {
		currentfile = data.header.xmlfile;
		networkid = id;
		datagridViewer.populateDatagrid(data.data);
		datagridViewer.renderGrid();
		closeAllDialogs();
		checkApplicationStatus(2);
    });

}








/*
function downloadNetworkBreaks(){
	var url = "/inc/getfile.php?file="+currentfile;
	window.location = url;
}*/
function downloadNetworkBreaks(selectedIds){
	if(selectedIds.length == 0)
	{
		loadDialogWindow('warning-no-rows-selected', 'No Rows Selected', 380, 160);
		return;
	}

	loadDialogWindow('download-wait', 'Loading Please Wait', 420, 170);
	var ids = selectedIds.join(',');
	var startdate = $('#network-start-date').val();
	var enddate = $('#network-end-date').val();

	var url = "services/networks.php?eventtype=downloadnetworkbreaks&ids="+ids+"&tzid="+tzid+"&startdate="+startdate+"&enddate="+enddate;
	$.getJSON(url, function(data) {
		if (typeof data.file != 'undefined')
		{
		  	var url = "/inc/getfile.php?file="+data.file;
			window.location = url;
			closeAllDialogs();
			return;
		}
		else if (typeof data.queueid != 'undefined')
		{
		  	loadDialogWindow('download-queued', 'Download Queued', 380, 160);
			return;
		} else
		{
			loadDialogWindow('download-error', 'Error', 380, 160);
			return;
		}	
    });
}

function downloadSelectedNetworkBreaks(){
	var selectedIds = datagridNetworks.selectedIds();
	// alert(selectedIds);
	downloadNetworkBreaks(selectedIds);
}




function downloadAllNetworkBreaks(){
	var selectedIds = datagridNetworks.selectAll();
	downloadNetworkBreaks(selectedIds);
}


function createCustomBreakRule(){
	var label 				= $('#sidebar-custom-breaks-label').val();
	var networkid 			= $('#sidebar-custom-breaks-network').val();
	var breakaddorremove 	= $('#sidebar-custom-breaks-break-hidden').val();
	var length 				= $('#sidebar-custom-breaks-length').val();
	var startdate 			= $('#sidebar-custom-breaks-start-date').val();
	var enddate 			= $('#sidebar-custom-breaks-end-date').val();
	var starttime 			= $('#sidebar-custom-breaks-start-time').val();
	var endtime 			= $('#sidebar-custom-breaks-end-time').val();
	var template 			= $('#sidebar-custom-breaks-template').val();

	var instancecodesarr     = [];

	$('.checkbox-custom-breaks:checked').each(function(){
		instancecodesarr.push($(this).val());
	});

	if(instancecodesarr.length == 0)
	{
		loadDialogWindow('select-atleast-one-network-instance','ShowSeeker Error',380,150);
		return;
	}
	var instancecodesstr = instancecodesarr.join(',');

	var url = "services/networks.php?eventtype=createcustomrule&label="+label+"&networkid="+networkid+"&breakaddorremove="+breakaddorremove+"&length="+length+"&startdate="+startdate+"&enddate="+enddate+"&starttime="+starttime+"&endtime="+endtime+"&template="+template+"&starttime="+starttime+"&endtime="+endtime+"&instances="+instancecodesstr;
	$.getJSON(url, function(data) {
		getNetworkCustomRules($("#sidebar-custom-breaks-network").val());
    });
	
}

function resetCustomBreakRuleForm(){
	$('#sidebar-custom-breaks-label,#sidebar-custom-rule-edit-hidden-id').val('');
	$('option','#sidebar-custom-breaks-network').eq(0).attr('Selected','selected');
	$('#sidebar-custom-breaks-length').val('30');
	$("#sidebar-custom-breaks-start-date").datepicker("setDate", new Date());
	$("#sidebar-custom-breaks-end-date").datepicker("setDate", +40);

	$('#sidebar-custom-breaks-start-time').datetimepicker('setDate', (new Date("Jan 01, 2014 00:00:00")) );
	$('#sidebar-custom-breaks-start-time').val("00:00 ET");
	
	$('#sidebar-custom-breaks-end-time').datetimepicker('setDate', (new Date("Jan 01, 2014 23:59:59")) );
	$('#sidebar-custom-breaks-end-time').val("23:59 ET");

	chooseCustomBreakType('');
}

function getNetworkCustomRules(networkid){
	//getSelectedNetworkInstances('custom-breaks');

	var url = "services/networks.php?eventtype=viewncreatecustomrule&networkid="+networkid;
	$.getJSON(url, function(data) 
	{
		datagridCustomBreaks.populateDatagrid(data.data);
		datagridCustomBreaks.renderGrid();
		closeAllDialogs();
    });

    $.getJSON("services/customrulewizard.php?eventtype=viewcustombreakrules", function(data) 
	{
		datagridCustomBreakRulesets.populateDatagrid(data.data);
		datagridCustomBreakRulesets.renderGrid();
    });
}

function confirmCustomBreakRuleDelete() {
    loadDialogWindow('confirm-delete-customrule', 'Confirm Delete', 380, 150);
}

function eventCustomBreakRuleDelete () {
    var ids = datagridCustomBreaks.selectedRowIds();
    $.post("services/networks.php", {
        eventtype: "deletecustombreakrule",
        ids: ids
    }).done(function(data) {
        closeAllDialogs();
        getNetworkCustomRules($("#sidebar-custom-breaks-network").val());
        //getCardsForMarket(selectedmarketid, false);
    });
}


function doEspnExcelUpload() {
	loadDialogWindow('upload-espn-excel', 'Upload ESPN Excel', 380, 230);
	// loadDialogWindow('upload-excel', 'Upload Excel', 380, 200);
}


//Custom Title related stuff
function getNetworkShowSchedule(){
	loadDialogWindow('load', 'Loading Please Wait', 380, 160);

	var networkid 			= $('#sidebar-custom-titles-network').val();
	var startdate 			= $('#sidebar-custom-titles-start-date').val();
	var enddate 			= $('#sidebar-custom-titles-end-date').val();
	var starttime 			= $('#sidebar-custom-titles-start-time').val();
	var endtime 			= $('#sidebar-custom-titles-end-time').val();

	var url = "services/networks.php?eventtype=getnetworkshowschedule&networkid="+networkid+"&startdate="+startdate+"&enddate="+enddate+"&starttime="+starttime+"&endtime="+endtime;
	$.getJSON(url, function(data) {
		datagridCustomTitles.populateDatagrid(data);
		datagridCustomTitles.renderGrid();
		closeAllDialogs();
		$('#sidebar-tab-4-sub, #sidebar-tab-4-sub-2').css('display', 'inline');
    });
}

function addEditCustomTitle()
{
	var selectedRows = datagridCustomTitles.selectedRows();
	if(selectedRows.length == 0)
	{
		loadDialogWindow('warning-no-rows-selected', 'No Rows Selected', 380, 160);
	} else
	{
		loadDialogWindow('add-edit-custom-title', 'Add/Edit Custom Title', 380, 160);
	}

	if($('.checkbox-custom-titles:checked').length == 0)
	{
		loadDialogWindow('select-atleast-one-network-instance','ShowSeeker Error',380,150);
		return;
	}
	
}

function saveCustomTitle()
{
	var selectedRows = datagridCustomTitles.selectedRows();

	var instancecodesarr     = [];
	$('.checkbox-custom-titles:checked').each(function(){ instancecodesarr.push($(this).val());	});
	var instancecodesstr = instancecodesarr.join(',');


	$.post("services/networks.php",
	{
		eventtype:"addeditcustomtitle",
		customtitle:$('#custom-show-title-text').val(),
		selectedrows:selectedRows,
		instancecodes:instancecodesstr
	}).done(function(data){
				getNetworkShowSchedule();
				closeAllDialogs();
				datagridCustomTitles.renderGrid();
			});
}


function confirmCustomTitleDelete() {
    loadDialogWindow('confirm-delete-customtitle', 'Confirm Delete', 380, 160);
}

function eventCustomTitleDelete() {
    var selectedRows = datagridCustomTitles.selectedRows();
	
	var instancecodesarr     = [];
	$('.checkbox-custom-titles:checked').each(function(){ instancecodesarr.push($(this).val());	});
	var instancecodesstr = instancecodesarr.join(',');
	
	$.post("services/networks.php",
	{
		eventtype:"addeditcustomtitle",
		customtitle:'',
		selectedrows:selectedRows,
		instancecodes:instancecodesstr
	}).done(function(data){
				getNetworkShowSchedule();
				closeAllDialogs();
				datagridCustomTitles.renderGrid();
			});
}

//Download scheduler related functions
function saveSchedule()
{
	var networkIds = [];
	$('[class^="network-group-"]:checked').each(function(){
		networkIds.push($(this).val());
	});

	if(networkIds.length == 0)
	{
		loadDialogWindow('select-atleast-one-network','ShowSeeker Error',380,150);
		return;
	}

	var selectedDays = [];
	$('#sidebar-weekday-choice-list input[type=checkbox]:checked').each(function(){
		selectedDays.push($(this).val());
	});

	if(selectedDays.length == 0)
	{
		loadDialogWindow('select-atleast-one-weekday','ShowSeeker Error',380,150);
		return;
	}

	if($("#sidebar-scheduler-emails").val().trim() == "")
	{
		loadDialogWindow('enter-email','ShowSeeker Error',380,150);
		return;
	}

	var emails = $("#sidebar-scheduler-emails").val().trim().split(/[;,\s\n]+/);
	var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i

	$.each(emails, function(i,value)
	{
		if(!pattern.test(value))
		{
			loadDialogWindow('enter-valid-email','ShowSeeker Error',380,150);
			return;
		}
	});

	var time 	   = $('#sidebar-scheduler-time').val();
	var scheduleId = $("#sidebar-hidden-scheduleid").val();
	var numdays    = $("#sidebar-scheduler-days").val();
	var label 	   = $("#sidebar-scheduler-label").val();

	$.post("services/networks.php", {
        eventtype: "savedownloadschedule",
        networkIds: networkIds,
        selectedDays: selectedDays,
        time: time,
        emails: emails,
        numdays: numdays,
        label: label,
        scheduleid: scheduleId
    }).done(function(data) {
        closeAllDialogs();
        getUserDownloadSchedules();
    });
}

function getUserDownloadSchedules()
{
	//loadDialogWindow('load', 'Loading Please Wait', 380, 160);
	resetSchedulerForm();
	var url = "services/networks.php?eventtype=getuserdownloadschedules";
	$.getJSON(url, function(data) {
		datagridDownloadSchedule.populateDatagrid(data);
		closeAllDialogs();
		datagridDownloadSchedule.renderGrid();
    });
}

function editSelectedSchedule()
{
	var selectedRows = datagridDownloadSchedule.selectedRows();
	if(selectedRows.length != 1)
	{
		loadDialogWindow('single-row', 'Confirm Delete', 380, 150);
		return;
	}
	var sched = selectedRows[0];
	$('#sidebar-scheduler-emails').val(sched.emailsarr.join(', \n'));
	$.each(sched.daysarr, function(i, value) {
            $('#sidebar-scheduler-week-days-'+value).attr("checked","checked").trigger("change");
    });

	$.each(sched.networksarr, function(i, net) {
		$('#sidebar-neworks-choice-list input[type=checkbox][value='+net.id+']').attr("checked","checked").trigger("change");
    });

    $('#sidebar-scheduler-time').datetimepicker('setTime', sched.tztime);
	$('select','.ui_tpicker_timezone').eq(0).val(sched.usertimezone.replace('s','').toUpperCase()).trigger("change");
	$('#sidebar-hidden-scheduleid').val(sched.id);
	$('#sidebar-scheduler-submit').html('<i class="fa fa-plus-circle fa-lg"></i> Update Schedule');
	
}

function resetSchedulerForm()
{
	$('#sidebar-weekday-choice-list input[type=checkbox]').each(function(){
		$(this).removeAttr("checked").trigger("change");
	})


	$('#sidebar-neworks-choice-list input[type=checkbox]').each(function(){
		$(this).attr("checked","checked").trigger("change");
	})

	$('#sidebar-scheduler-time').datetimepicker('setTime', '00:00:00');
	$('select','.ui_tpicker_timezone').eq(0).val("ET").trigger("change");
	$('#sidebar-scheduler-emails, #sidebar-hidden-scheduleid').val('');
	$('option[value="0"]','#sidebar-scheduler-timezone').attr("selected","selected");
	$('option[value="1"]','#sidebar-scheduler-days').attr("selected","selected");
	$('#sidebar-scheduler-submit').html('<i class="fa fa-plus-circle fa-lg"></i> Create Schedule');
}


function confirmSScheduleDelete() {
    var selectedRows = datagridDownloadSchedule.selectedRowIds();
    if(selectedRows.length > 0)
    {
    	loadDialogWindow('confirm-delete-schedule', 'Confirm Delete', 380, 160);
    	return;
    } else
    {
    	loadDialogWindow('warning-no-rows-selected','ShowSeeker Error',380,150);
    	return;
    }
}

function eventDownloadScheduleDelete()
{
	var selectedRows = datagridDownloadSchedule.selectedRowIds();
	$.get("services/networks.php",
	{
		eventtype:"deletedownloadschedule",
		selectedrows:selectedRows
	}).done(function(data){
				closeAllDialogs();
				getUserDownloadSchedules();
			});
}

function customBreakEmailUpdate()
{
	loadDialogWindow('custom-break-email-form', 'Email Break Update', 700, 500);
	var ids = datagridCustomBreaks.selectedRowIds();
	console.log(ids);
}

function updateShedulerNetworkList(groupId, eventType)
{
	if(eventType == 'ADD')
	{
		var url = "services/groups.php?eventtype=groupnetlistforscheduler&id="+groupId;
		$.getJSON(url, function(data) {
			$.each(data.data, function(i, value) {
	            
	            if(value.groupnets.length > 0) 
	            {

	            	if($('#sidebar-scheduler-network-group-'+value.id).length == 0)
	            	{
	            		$('#sidebar-networks-choice-list').append('<li class="sidebar-scheduler-network-group-li" ><input id="sidebar-scheduler-network-group-'+value.id+'" type="checkbox" value="'+value.id+'" checked="checked"><label for="sidebar-scheduler-network-group-'+value.id+'"><strong>'+value.name+'</strong></label></li>');

			            $.each(value.groupnets, function(j, net) {
			            	var n2 = "<b>"+net.instancecode + "</b> - " + net.name;
			            	$('#sidebar-networks-choice-list').append('<li class="sidebar-scheduler-network-li" ><input id="sidebar-scheduler-network-'+net.id+'" class="network-group-'+value.id+'" type="checkbox" value="'+net.id+'" checked="checked"><label for="sidebar-scheduler-network-'+net.id+'">'+"<img width='25' src='http://ww2.showseeker.com/images/_thumbnailsW/"+net.filename+"'>  "+n2+'</label></li>');
			        	});
			        }
		        }
	        });

			if($('[class^="network-group-"]').length > 0 )
				$('[class^="network-group-"]').eq(0).trigger("change");
			else $('#sidebar-scheduler-network').html("Select Network(s)");
	    });
	}

	else if(eventType == 'REMOVE')
	{
		$('#sidebar-scheduler-network-group-'+groupId).parent().remove();
		$('.network-group-'+groupId).each(function() { $(this).parent().remove(); });
		
		if($('[class^="network-group-"]').length > 0 )
			$('[class^="network-group-"]').eq(0).trigger("change");
		else $('#sidebar-scheduler-network').html("Select Network(s)");
	}
}





function toggleOn(group,item){
	var selectedId = group + "-" + item;


	$("#"+group+" li a").each(function(index) {
		var buttonId = $(this)[0].id;
		if(buttonId == selectedId){
			$(this).addClass("drkgrey");
		}else{
			$(this).removeClass("drkgrey");
		}
		
	});
}


function getNetworkListForCustomBreaks() {
    $.getJSON("services/networks.php?eventtype=networklistforcustombreaks", function(data) {
   		$.each(data.data, function(i, value) {
            $('#sidebar-custom-titles-network, #sidebar-custom-breaks-network').append($("<option></option>").attr("value", value.id).text(value.charter_callsign + ' - '+ value.name));
        });

        getSelectedNetworkInstances('custom-breaks');
        getSelectedNetworkInstances('custom-titles');
    });
}


function getSelectedNetworkInstances(calledFrom)
{
	var selectedNetwrkId = $('#sidebar-'+calledFrom+'-network').val();
	$.getJSON("services/networks.php?eventtype=getselectednetworkinstances&networkid="+selectedNetwrkId, function(data) {
   		$('#sidebar-'+calledFrom+'-instances-choice-list').empty();

   		if(data.data.length == 0)
   		{
   			$('#sidebar-'+calledFrom+'-instances-choice-list').append('<li><label>No Instance availabe</label></li>');
   		} else
   		{
   			$.each(data.data, function(i, value) {
            	$('#sidebar-'+calledFrom+'-instances-choice-list').append('<li><input id="sidebar-'+calledFrom+'-instances-'+i+'" class="checkbox-'+calledFrom+'" type="checkbox" value="'+value.id+'" checked="checked"><label for="sidebar-'+calledFrom+'-instances-'+i+'"><b>'+value.instancecode+'</b> - ['+value.timezone+'] ['+value.breakgroupname+']</label></li>');
        	});
        }
        refreshSideBarNetInstanceLabel(calledFrom);
    });    
}

function refreshSideBarNetInstanceLabel(calledFrom)
{
	var selectedInstancesCount = $('.checkbox-'+calledFrom+':checked').length;
	if(selectedInstancesCount == 0)
	{
		$('#sidebar-'+calledFrom+'-instances').html("Select Instance(s)");
		return;
	}

	$('#sidebar-'+calledFrom+'-instances').html(selectedInstancesCount + " Instance(s) Selected ");
}




function updateChangeShedulerNetworkList(groupId, eventType)
{
	if(eventType == 'ADD')
	{
		var url = "services/groups.php?eventtype=groupnetlistforscheduler&id="+groupId;
		$.getJSON(url, function(data) {
			$.each(data.data, function(i, value) {
	            
	            if(value.groupnets.length > 0) 
	            {

	            	if($('#sidebar-update-scheduler-network-group-'+value.id).length == 0)
	            	{
	            		$('#sidebar-update-scheduler-networks-choice-list').append('<li class="sidebar-update-scheduler-network-group-li" ><input id="sidebar-update-scheduler-network-group-'+value.id+'" type="checkbox" value="'+value.id+'" checked="checked"><label for="sidebar-update-scheduler-network-group-'+value.id+'"><strong>'+value.name+'</strong></label></li>');

			            $.each(value.groupnets, function(j, net) {
			            	var n2 = "<b>"+net.instancecode + "</b> - " + net.name;
			            	$('#sidebar-update-scheduler-networks-choice-list').append('<li class="sidebar-update-scheduler-network-li" ><input id="sidebar-update-scheduler-network-'+net.id+'" class="update-scheduler-network-group-'+value.id+'" type="checkbox" value="'+net.id+'" checked="checked"><label for="sidebar-update-scheduler-network-'+net.id+'">'+"<img width='25' src='http://ww2.showseeker.com/images/_thumbnailsW/"+net.filename+"'>  "+n2+'</label></li>');
			        	});
			        }
		        }
	        });

			if($('[class^="update-scheduler-network-group-"]').length > 0 )
				$('[class^="update-scheduler-network-group-"]').eq(0).trigger("change");
			else $('#sidebar-update-scheduler-network').html("Select Network(s)");
	    });
	}

	else if(eventType == 'REMOVE')
	{
		$('#sidebar-update-scheduler-network-group-'+groupId).parent().remove();
		$('.network-group-'+groupId).each(function() { $(this).parent().remove(); });
		
		if($('[class^="network-group-"]').length > 0 )
			$('[class^="network-group-"]').eq(0).trigger("change");
		else $('#sidebar-update-scheduler-network').html("Select Network(s)");
	}
}


//Creating update scheduler
function saveUpdateSchedule()
{
	var networkIds = [];
	$('[class^="update-scheduler-network-group-"]:checked').each(function(){
		networkIds.push($(this).val());
	});

	if(networkIds.length == 0)
	{
		loadDialogWindow('select-atleast-one-network','ShowSeeker Error',380,150);
		return;
	}

	var selectedDays = [];
	$('#sidebar-update-scheduler-weekday-choice-list input[type=checkbox]:checked').each(function(){
		selectedDays.push($(this).val());
	});

	if(selectedDays.length == 0)
	{
		loadDialogWindow('select-atleast-one-weekday','ShowSeeker Error',380,150);
		return;
	}

	if($("#sidebar-update-scheduler-emails").val().trim() == "")
	{
		loadDialogWindow('enter-email','ShowSeeker Error',380,150);
		return;
	}

	var emails = $("#sidebar-update-scheduler-emails").val().trim().split(/[;,\s\n]+/);
	var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i

	$.each(emails, function(i,value)
	{
		if(!pattern.test(value))
		{
			loadDialogWindow('enter-valid-email','ShowSeeker Error',380,150);
			return;
		}
	});

	var time 		= $('#sidebar-update-scheduler-time').val();
	//var scheduleId= $("#sidebar-hidden-scheduleid").val();
	var numweeks 	= $("#sidebar-update-scheduler-days").val();
	var weekstartid = $("#sidebar-update-scheduler-weekstart").val();
	var updatetype  = $("#sidebar-hidden-update-scheduler-types").val();

	$.post("services/networks.php", {
        eventtype: "saveupdatedownloadschedule",
        networkIds: networkIds,
        selectedDays: selectedDays,
        time: time,
        emails: emails,
        numweeks: numweeks,
        updatetype: updatetype,
        weekstartid: weekstartid
    }).done(function(data) {
        closeAllDialogs();
        getUserUpdateDownloadSchedules();
    });
}

function getUserUpdateDownloadSchedules()
{
	//loadDialogWindow('load', 'Loading Please Wait', 380, 160);
	//resetSchedulerForm();
	var url = "services/networks.php?eventtype=getuserupdatedownloadschedules";
	$.getJSON(url, function(data) {
		datagridDownloadUpdateSchedule.populateDatagrid(data);
		closeAllDialogs();
		datagridDownloadUpdateSchedule.renderGrid();
    });
}


function confirmUpdaeteScheduleDelete() {
    var selectedRows = datagridDownloadUpdateSchedule.selectedRowIds();
    if(selectedRows.length > 0)
    {
    	loadDialogWindow('confirm-delete-update-schedule', 'Confirm Delete', 380, 160);
    	return;
    } else
    {
    	loadDialogWindow('warning-no-rows-selected','ShowSeeker Error',380,150);
    	return;
    }
}

function eventDownloadUpdateScheduleDelete()
{
	var selectedRows = datagridDownloadUpdateSchedule.selectedRowIds();
	$.get("services/networks.php",
	{
		eventtype:"deletedownloadupdateschedule",
		selectedrows:selectedRows
	}).done(function(data){
				closeAllDialogs();
				getUserUpdateDownloadSchedules();
			});
}


function panelEditCustomRule(type) {
    var selRow = datagridCustomBreaks.selectedRows();


    if (selRow.length != 1 && type == 0) {
        loadDialogWindow('single-row', 'Select Row', 380, 150);
        return;
    }

    if (type == 1) {
        $('#sidebar-custom-breaks-update-button, #sidebar-custom-breaks-close-button').css('display', 'none');
        $('#sidebar-custom-breaks-add-button,#sidebar-custom-breaks-edit-button,#sidebar-custom-breaks-reset-button').css('display', 'inline');
        $('.main').css('background-color', '#F2F5F7');
        $('.sidebar').css('background-color', '#F1F1F1');

        resetCustomBreakRuleForm();

    } else {
        $('#sidebar-custom-breaks-update-button, #sidebar-custom-breaks-close-button').css('display', 'inline');
        $('#sidebar-custom-breaks-add-button,#sidebar-custom-breaks-edit-button,#sidebar-custom-breaks-reset-button').css('display', 'none');
        $('.main').css('background-color', '#E2CFC8');
        $('.sidebar').css('background-color', '#E2CFC8');

        selRow = selRow[0];
        $('#sidebar-custom-rule-edit-hidden-id').val(selRow.id);
        $('#sidebar-custom-breaks-label').val(selRow.breaklabel);
        $('#sidebar-custom-breaks-network').val(selRow.networkid);
        $('#sidebar-custom-breaks-length').val(selRow.length);
        $('#sidebar-custom-breaks-start-date').val(selRow.startdate);
        $('#sidebar-custom-breaks-end-date').val(selRow.enddate);
        $('#sidebar-custom-breaks-start-time').val(selRow.starttime.substring(0,5)+" ET");
        if(selRow.endtime != "NA")
       	 $('#sidebar-custom-breaks-end-time').val(selRow.endtime.substring(0,5)+" ET");

        if(selRow.showbreak =='Yes')
        {
        	chooseCustomBreakType('Yes');
        	toggleOn('sidebar-item-group-break',1);
        } else
        {
        	chooseCustomBreakType('No');
        	toggleOn('sidebar-item-group-break',2);
        }
    }
}

function updateCustomBreakRule()
{
	var label 				= $('#sidebar-custom-breaks-label').val();
	var networkid 			= $('#sidebar-custom-breaks-network').val();
	var breakaddorremove 	= $('#sidebar-custom-breaks-break-hidden').val();
	var length 				= $('#sidebar-custom-breaks-length').val();
	var startdate 			= $('#sidebar-custom-breaks-start-date').val();
	var enddate 			= $('#sidebar-custom-breaks-end-date').val();
	var starttime 			= $('#sidebar-custom-breaks-start-time').val();
	var endtime 			= $('#sidebar-custom-breaks-end-time').val();
	var breakid 			= $('#sidebar-custom-rule-edit-hidden-id').val();

	var url = "services/networks.php?eventtype=updatecustomrule&breakid="+breakid+"&label="+label+"&networkid="+networkid+"&breakaddorremove="+breakaddorremove+"&length="+length+"&startdate="+startdate+"&enddate="+enddate+"&starttime="+starttime+"&endtime="+endtime;
	$.getJSON(url, function(data) {
		panelEditCustomRule(1);
		getNetworkCustomRules($("#sidebar-custom-breaks-network").val());

    });
}

function getCustomBreakTemplates()
{
	$.getJSON("services/networks.php?eventtype=listcustombreaktemplates", function(data) {
   		$.each(data.data, function(i, value) {
            $('#sidebar-custom-breaks-template').append($("<option></option>").attr("value", value.id).text(value.name));
        });
    });
}

function setLiveGrouping(value)
{
  if(value == 'No')
  {
  	$('#chk-live-grouping').removeAttr('checked');

  } else
  {
  	$('#chk-live-grouping').attr('checked','checked');
  }
}

function sendCustomBreakEmail()
{
	var selectedRows = datagridCustomBreaks.selectedRowIds();

	$.post("services/networks.php", {
        eventtype: "custombreakupdateemail",
        custombreakids:selectedRows,
    }).done(function(data){

    });
}

function viewchangesemail(mailId)
{
	//loadDialogWindow('changes-email', datagridChanges.selectedRows()[0].subject, 740, 420);
	window.open('viewemail.php?id='+mailId,'_blank','height=700,width=820,location=no,menubar=no,scrollbars=yes');
}

function confirmChangesMarckComplete()
{
	loadDialogWindow('confirm-change-mark-complete', 'Confirm Mark Complete', 380, 160);
}

function confirmChangesEmailDelete()
{
	loadDialogWindow('confirm-change-delete', 'Confirm Delete', 380, 150);
}

function markselectedChangeComplete()
{
	var ids = datagridChanges.selectedRowIds();

	$.post("services/tracker.php", {
        eventtype: "markcomplete",
        ids:ids
    }).done(function(data) {
    	getChangeList();
    	closeAllDialogs();
    });
}

function deleteSelectedChangesEmail()
{
	var ids = datagridChanges.selectedRowIds();

	$.post("services/tracker.php", {
        eventtype: "delete",
        ids:ids
    }).done(function(data) {
    	getChangeList();
    	closeAllDialogs();
    });
}

function ReplyToEmail()
{
	loadDialogWindow('changes-email-reply', "Reply: "+datagridChanges.selectedRows()[0].subject, 740, 520);
}

function forwardEmail()
{
	loadDialogWindow('changes-email-forward', "Forward: "+datagridChanges.selectedRows()[0].subject, 740, 520);
}

function sendChangesEmailReply()
{
	$.post("services/tracker.php",
		{
        	eventtype: "sendemail",
        	id:datagridChanges.selectedRowIds()[0],
        	sendto:encodeURI($('#changes-email-reply-recepients').val()),
        	subject:encodeURI($('#changes-email-reply-subject').val()),
        	content:encodeURI(CKEDITOR.instances['changes-email-reply-content'].getData())
    	},
        function(data)
    	{
    		console.log(data);
    	}, "json");
}

function doAutoLoadViwer(){
	var row = datagridNetworks.getRowByInstanceId(autoLoadInstanceId);
	var lbl = "<b>" + row.name + "</b>";
	$('#label-network-viewer').html(lbl);

	var url = "services/networks.php?eventtype=viewnetwork&id="+autoLoadInstanceId+"&tzid=0&startdate="+autoLoadDate+"&enddate="+autoLoadDate;
	$.getJSON(url, function(data) {
		currentfile = data.header.xmlfile;
		networkid = autoLoadInstanceId;
		datagridViewer.populateDatagrid(data.data);
		datagridViewer.renderGrid();
		datagridViewer.expandAllGroups()

		var interestedIds = [];
		$.each(data.data, function(i, drow){
			if(drow.starttime == autoLoadWindow){
				interestedIds.push((Number(drow.id)+1));
			}
		});
		closeAllDialogs();
		checkApplicationStatus(2);
		datagridViewer.selectRowsById(interestedIds);
		datagridViewer.scrollRowIntoView(interestedIds[0]-1);
    });

    autoloadViewer = false;
}

function dialogHelp(){
	closeAllDialogs();
	loadDialogWindow('help-and-tutorial', 'ShowSeeker E-z Breaks',600,370,0,false);
}

function openTutorial(url){
	var w = 1024;
	var h = 880;

	LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
	TopPosition  = (screen.height) ? (screen.height-h)/2 : 0;
	tutWindow    = window.open(url, "tutorialwindow", "location=no,status=yes,resizable=yes,scrollbars=yes,width="+w+",height="+h+",top="+TopPosition+",left="+LeftPosition);
	tutWindow.focus();
}