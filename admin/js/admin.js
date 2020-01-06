var downloadData 			= '';
var deleteusergroup 		= [];
var selectedusergroup 	= [];
var navpage					= '';

$(document).ready(function() {

	$('.usr-accounts').change(function(){
	
		var usrid = selectedusergroup.indexOf($(this).val());
	
		if( usrid == -1 ){
			selectedusergroup.push($(this).val());
		}
		else{
			selectedusergroup.splice(usrid, 1);
		}
	});
});


$(document).on('pagehide', function(event, ui) {
    var page = jQuery(event.target);
});

$(document).bind("mobileinit", function() {
    $.mobile.defaultDialogTransition = 'none';
});


function marketDataEvent(eventtype, marketid) {
    var active = $("#market-active").val();
    var goApp = $("#market-go").val();
    var iseeker = $("#market-iseeker").val();
    var name = $("#market-name").val();    
    var rounded = $("#roundedResults").val();
    var scximporter = $("#market-scximporter").val();
    var snapshot = $("#market-snapshot").val();

	var d = {};
	    d.eventtype = eventtype;
        d.marketid = marketid
        d.name = name;
        d.active = active;
        d.rounded = rounded;
        d.goApp = goApp;
        d.snapshot = snapshot;
    var url = "services/markets.php";

    $.post(url, d).done(function(data) {
        window.location = "/markets.php?marketid=" + data;
    });
}





function getUrlVars(name) {
   
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));    
}



function s4() {
    return Math.floor((1 + Math.random()) * 0x10000)
        .toString(16)
        .substring(1);
};

function guid() {
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
        s4() + '-' + s4() + s4() + s4();
}


//////////////// USER //////////////////////

function userUpdate(id, corporationid) {
    if (id == 0) {
        userCreate(corporationid);
        return;
    }
    var firstname = $("#firstname").val();
    var lastname = $("#lastname").val();
    var title = $("#title").val();
    var email = $("#email").val();
    var password = $("#password").val();
    var phone = $("#phone").val();
    var cell = $("#cell").val();
    var fax = $("#fax").val();
    var active = $("#active").val();
    var address = $("#address").val();
    var address2 = $("#address2").val();
    var city = $("#city").val();
    var state = $("#state").val();
    var zip = $("#zip").val();
    var defaultoffice = $("#defaultoffice").val();

    var useroffices = [];
    $('input[name="useroffices"]:checkbox:checked').each(function(i) {
        useroffices[i] = $(this).val();
    });

    var usergroups = [];
    $('input[name="usergroups"]:checkbox:checked').each(function(i) {
        usergroups[i] = $(this).val();
    });

    var ezbreakgroups = [];
    $('input[name="ezbreakgroups"]:checkbox:checked').each(function(i) {
        ezbreakgroups[i] = $(this).val();
    });


	if (firstname == '' || lastname == '' || title == '' || email == '' || password == '' || phone == '') {
		alert("Please Fill Required Fields");
		return;
	} 


    $.post("/services/users.php", {
        id: id,
        eventtype: "update",
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
        ezbreakgroups: ezbreakgroups
    }).done(function(data) {
        //window.alert('User Updated');
        $.mobile.back();
        //window.location = "/markets.php?marketid="+data;
    });
}



function userCreate(corporationid) {
    var firstname = $("#firstname").val();
    var lastname = $("#lastname").val();
    var title = $("#title").val();
    var email = $("#email").val();
    var password = $("#password").val();
    var phone = $("#phone").val();
    var cell = $("#cell").val();
    var fax = $("#fax").val();
    var active = $("#active").val();
    var address = $("#address").val();
    var address2 = $("#address2").val();
    var city = $("#city").val();
    var state = $("#state").val();
    var zip = $("#zip").val();
    var defaultoffice = $("#defaultoffice").val();

    var useroffices = [];
    $('input[name="useroffices"]:checkbox:checked').each(function(i) {
        useroffices[i] = $(this).val();
    });

    var usergroups = [];
    $('input[name="usergroups"]:checkbox:checked').each(function(i) {
        usergroups[i] = $(this).val();
    });


    $.post("/services/users.php", {
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
        usergroups: usergroups
    }).done(function(data) {
        if (data == '0') {
            window.alert('Email in use');
        } else {
            $.mobile.back();
        }
    });
}




function userDelete(userid, corporationid) {
    var confirm = $("#user-delete-confirm").val();
    var confirmTrimmed = $.trim(confirm);

    if (confirmTrimmed == "delete") {
    
    
    		setTimeout(function(){

		        $.post("services/users.php", {
		            eventtype: 'delete',
		            userid: userid,
		            corporationid: corporationid
		        }).done(function(data) {
		            $.mobile.back();
		        });

			}, 100);	
    
    
        $.post("services/users.php", {
            eventtype: 'delete',
            userid: userid,
            corporationid: corporationid
        }).done(function(data) {
            $.mobile.back();
        });
        
    } else {
        window.alert('If you wish to delete this user type delete in the box and click this button again');
    }
}




function usersDelete() {

	var currentusers = parseInt($('#users-count').text());
	
	//clear the array of users to be deleted
	while(deleteusergroup.length > 0) {
	    deleteusergroup.pop();
	}
	
	// adds new selected users
	for(var i=0; i < selectedusergroup.length; i++) {
		deleteusergroup.push(selectedusergroup[i]);
	}
	

	$.post("services/users.php", {
	
		eventtype: 'deletegroup',
		userids: deleteusergroup.join(",")

		}).done(function(data) {

			currentusers = currentusers - deleteusergroup.length;
			
			//clear array of deleted users
			while(selectedusergroup.length > 0) {
			   selectedusergroup.pop();
			}
			
			//confirmation message is updated and displayed
			$('#confirmation-msg-area').text(deleteusergroup.length +" deleted account(s)").toggle();
			
			//number of users in corporation, market or office is updated too
			$('#users-count').text(currentusers);

			//UNDO button is shown
			$('#user-delete-undo:hidden').toggle();
			
			//Hidding removed users from the view
			for(var j=0; j < deleteusergroup.length; j++) {
				$('#usr_'+deleteusergroup[j]).css({'display':'none'});
			}
			
			
			//setting time out to remove the confirmation message
			setTimeout(function(){
					$('#confirmation-msg-area').text("");
					$('#confirmation-msg-area').toggle();
				}
				,5000);
				
		});
}

function undousersDelete() {
	
		//getting number of users displayed at the header of the widget
		var currentusers = parseInt($('#users-count').text());	
	
		$.post("services/users.php", {
			eventtype: 'undodelete',
			userids: deleteusergroup.join(",")
		})
		.done(function(data) {

			currentusers = currentusers + deleteusergroup.length;

			$('#user-delete-undo').toggle();
			
			$('#confirmation-msg-area').toggle();
			$('#confirmation-msg-area').text(deleteusergroup.length +" account(s) recovered");
			
			$('#users-count').text(currentusers);			
			
			
			for(var j=0; j < deleteusergroup.length; j++) {
				$('#usr_'+deleteusergroup[j]).toggle();
				$('#'+deleteusergroup[j]).prop('checked',false);

				$('#usr_'+deleteusergroup[j]+' label').toggleClass('ui-checkbox-on');
				$('#usr_'+deleteusergroup[j]+' label').toggleClass('ui-checkbox-off');
				
				$('#usr_'+deleteusergroup[j]+' span').toggleClass('ui-icon-checkbox-on');
				$('#usr_'+deleteusergroup[j]+' span').toggleClass('ui-icon-checkbox-off');

				
			}				

			
			//empty users array
			while(deleteusergroup.length > 0) {
			    deleteusergroup.pop();
			}

			setTimeout(function(){
				$('#confirmation-msg-area').text("");
				$('#confirmation-msg-area').toggle();
				}
				,5000);

		});       
}



function preDeleteUser(){

	//gets the number of selected users to be deleted

	var cn = selectedusergroup.length;
	
	if(cn > 0){
		$('#number-users-deleted').text(cn);	
	}
	else{
		$('#number-users-deleted').text('');		
	}
}




