var downloadData = '';


$(document).bind("mobileinit", function() {
    $.mobile.defaultDialogTransition = 'none';
});


$(document).ready(function(){

    $('input[name="usergroups"]').change(function(){
		
		if($(this).val() == 14 && $(this).is(':checked')){
			$('#15').prop('checked',false).checkboxradio('refresh');
		}

		if($(this).val() == 15 && $(this).is(':checked')){
		    $('#14').prop('checked',false).checkboxradio('refresh');
		}

		if($(this).val() == 19){
			
			var userid = $('#id').val();
			
			if(userid != 0){
				getMarkets(userid);
				openEzGridsMarketsPanel();
				setTimeout(function(){checkEzGridsState()},200);
			}
			else{
				var r = getNewUserMarkets();
				$("#ezgrids-user-markets" ).panel( "open" );
				checkEzGridsState();
			}
		}
    });
});

function openEzGridsMarketsPanel(){
	$("#ezgrids-user-markets" ).panel( "open" );
	return true;
}


function toggleZoneRatecard(zoneid, marketid, checked) {
    $.post("/services/zones.php", {
        eventtype: "addremovezone",
        zoneid: zoneid,
        marketid: marketid,
        checked: checked
    }).done(function(data) {

    });
}


function toggleUserZone(zoneid, userid, checked) {
    $.post("services/zones.php", {
        eventtype: "userzone",
        zoneid: zoneid,
        userid: userid,
        checked: checked
    }).done(function(data) {

    });
}


function toggleUserRatecardMarket(marketid, userid, checked) {
	if(userid != 0){
	    $.post("/services/user.markets.php", {
	        eventtype: "usermarket",
	        marketid: marketid,
	        userid: userid,
	        checked: checked
	    }).done(function(data){});
	}
}



function deleteAltAddress(userid) {

	$.post("services/users.php", {
	   eventtype: "deletealtaddress",
	   userid: userid
	}).done(function(data) {
		$('#address').val('');
		$('#address2').val('');
		$('#city').val('');
		$('#zip').val('');
		$('#state').val(1);
		$('#a-del-alt-address,#lbl-del-alt-address').toggle();
		$('#popupDeletedAddress').popup('open');
	});
}



function getUrlVars(name) {
   
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));    
}




//////////////// USER //////////////////////
function userSetup(id, corporationid) {
	var email 		= $("#email").val().replace(/^\s+|\s+$/g,'');
	var url 			= "services/account.override.php?user="+ email +"&action=getaccount";
	var override 	= $("#override").val();
    $.ajax({
	        type:		"post",
	        url: 		url,
	        dataType:	"json",
	        processData:false,
	        success:	function(resp){
				if((id == 0 && resp[0] === 0) || (id == 0 && override === 1)) {
					userCreate(corporationid);
				}
				else if(userEmail === email || (userEmail !== email && resp[0] === 0)){
					userUpdate(id, corporationid);
				}
				else{
					displayUserAccounts(resp);				      
				}
			}
		});

	return;
}



//////////////// USER //////////////////////
function userUpdate(id, corporationid) {

    var firstname 		= $("#firstname").val().trim();
    var lastname		= $("#lastname").val().trim();
    var title 			= $("#title").val()
    var email 			= $("#email").val().replace(/^\s+|\s+$/g,'');
    var phone 			= $("#phone").val().trim();
    var cell 			= $("#cell").val().trim();
    var fax 			= '';
    var active 			= $("#active").val();
    var address 		= $("#address").val().trim();
    var address2 		= $("#address2").val().trim();
    var city 			= $("#city").val().trim();
    var state 			= $("#state").val().trim();
    var zip 			= $("#zip").val().trim();
    var override 		= $("#override").val();
    var useroffices 	= [];    
    var defaultoffice 	= $("#defaultoffice").val();
    var usergroups 		= [15];
    var ezbreakgroups 	= [];
    var c 				= 0;
	var userRole		= $('#userRole').val();
    
	useroffices.push(defaultoffice);
    

	if(parseInt(userRole) === 6){
		usergroups.push(userRole);
	}

	if(firstname == '' || lastname == '' || email == '' || title == '' || defaultoffice == ''){
		$('#popupWarning').popup('open');
		return;
	}

	if(!validateEmail(email)){
		$('#popupEmailAddress').popup('open');
		return;				
	}

	
	var data 				= {};
		data.id				= id;
        data.eventtype 		= "update";
        data.firstname 		= firstname;
        data.lastname 		= lastname;
        data.title 			= title;
        data.email 			= email;
        data.phone 			= phone;
        data.cell 			= cell;
        data.fax 			= fax;
        data.active 		= active;
        data.address 		= address;
        data.address2 		= address2;
        data.city 			= city;
        data.state 			= state;
        data.zip 			= zip;
        data.defaultoffice 	= defaultoffice;
        data.useroffices 	= useroffices;
        data.usergroups 	= usergroups;
        data.ezbreakgroups	= ezbreakgroups;
		data.createdBy 		= localStorage.getItem("userId");
		data.override		= override;

	
    $.post("services/users.php", data).done(function(d) {
		$('#popupUserUpdated').popup('open');	    
		try{mixTrack("Admin - UserUpdate",data);}
		catch(e){}
        //$.mobile.back();
    });
	return;
}



function userCreate(corporationid) {
	
    $.ajax({
        type:'post',
        url: "services/password.php",
        dataType:"text",
        processData: false,
        success:function(resp){
			var firstname 		= $("#firstname").val().trim();
		    var lastname 		= $("#lastname").val().trim();
		    var title 			= $("#title").val().trim();
		    var email 			= $("#email").val().replace(/^\s+|\s+$/g,'');
		    var password 		= resp;
		    var phone 			= $("#phone").val().trim();
		    var cell 			= $("#cell").val().trim();
		    var fax 			= '';
		    var active 			= 1;
		    var address 		= $("#address").val().trim();
		    var address2 		= $("#address2").val().trim();
		    var city 			= $("#city").val().trim();
		    var state 			= $("#state").val();
		    var zip 			= $("#zip").val().trim();
		    var defaultoffice	= $("#defaultoffice").val();
			var override 		= $("#override").val();
		    var useroffices 	= [];
		    var usergroups 		= [15];
		    var userRole		= $('#userRole').val();
		    useroffices.push(defaultoffice);
			
			if(firstname == '' || lastname == '' || email == '' || title == '' || defaultoffice == ''){
				$('#popupWarning').popup('open');
				return;
			}

			if(parseInt(userRole) === 6){
				usergroups.push(userRole);
			}

			if(!validateEmail(email)){
				$('#popupEmailAddress').popup('open');
				return;				
			}

			var postData = {
			        corporationid: corporationid,
			        eventtype: "create",
			        firstname: firstname,
			        lastname: lastname,
			        title: title,
			        email: email,
			        password: password,
			        phone: phone,
			        cell: cell,
			        fax: fax,
			        active: active,
			        address: address,
			        address2: address2,
			        city: city,
			        state: state,
			        zip: zip,
			        defaultoffice: defaultoffice,
			        useroffices: useroffices,
			        usergroups: usergroups,
			        override:	override,
					createdBy:  localStorage.getItem("userId")
			    };
		
		    $.post("services/users.php", postData).done(function(data){
			    
		        if (data == '0'){
		            window.alert('Email in use');
		        }
		        else{
					var options = 3;
					var random = Math.floor(Math.random() * options) + 1 ;

					$('#msg'+random).show();
					$('#popupNewUser').popup('open');
					$('#saveUserAccount').prop('href','javascript:userSetup('+data.id+','+postData.corporationid+','+data.id+')');
					$('#first').val(data.firstName);
					$('#usrEmail').val(data.email);
					$('#active').val(1);
					userEmail = data.email;
					
					$('#id').val(data.id);
					try{mixTrack("Admin - UserCreate",postData);}
					catch(e){}
		            //$.mobile.back();
		        }
		    });



        }
    });	
}
