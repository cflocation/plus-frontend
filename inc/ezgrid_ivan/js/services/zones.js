	$('document').ready(function(){
	
		getUserSettings(userid,  tokenid);
		getUserProposals(userid, tokenid);
		loadTimes();
	
	});
	


	function getUserSettings(userid, tokenid){

    //set the global user variables
    guserid 	= userid;
    gtokinid 	= tokenid;

    //set the url to get the user settings
    var url 	= "sourcedata/user.settings.php?userid=" + userid + "&tokenid=" + tokenid + "";
		
    //get the json result for the data
	  $.getJSON(url, function(data) {												


			  var zones = jQuery.parseJSON(data.zones);

	        $.each(zones.response.zones, function(i, value) {
	            if (value.isdma == "NO") {
	                $('#zone-selector').append($("<option></option>").attr("value", value.zoneid).text(value.zonename));
	            }
	        });
	        
	        $('#zone-selector').val("14");
	        
	        
	        populateNetworkList($('#zone-selector').val(),userid, tokenid);

			return false;
		});		
		
		return false;
	}

	
	
	
	
	//zone
function zoneSelected() {
    datagridSearchResults.emptyGrid();
    zoneid = $('#zone-selector').val();
    zone = $('#zone-selector :selected').text();
    populateNetworkList(zoneid, userid, tokinid);

    //set the user settings and save them
    userSettings.lastZoneId = zoneid;
    saveUserSettings();
}



/* NETWORK FUNCTIONS */
/* populate the network list based on the selected zone */
function populateNetworkList(zoneid, userid, tokenid) {
    arrayNetworks = [];
    var url = 'sourcedata/networklist.php?zoneid=' + zoneid + '&userid=' + userid + '&tokenid=' + tokenid;
    $.getJSON(url, function(data) {



			 var networks = data['response']['networks'];


	        $.each(networks, function(i, value) {
	            $('#network-selector').append($("<option></option>").attr("value", value.id).text(value.callsign));
	        });
	        
	        alert(network_id);
	        $('#network_selector').val(network_id);


        /*
        
        ------- REVIEW THIS CODE LATTER -------
        
        datagridNetworks = new DatagridNetworks();
        setTimezoneDayparts(data.responseHeader.tzabbreviation);  
        timezone = data.responseHeader.tzabbreviation.toLowerCase();
        datagridNetworks.emptyGrid();
        datagridNetworks.populateDataGrid(data.response.networks);

        if (ratecard == true) {
            getRateCard(zoneid);
        }

        resetChecker();
        isresetting = false;

        $("#dialog-modal-message").dialog("close");
        
        */
        
        
    });
}