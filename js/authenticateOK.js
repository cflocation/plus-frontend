var apiUrl  = "https://plusapi.showseeker.com";
var destApp = 'plus';
var appRole = null;

$(function(){
    destApp = GetURLParameter('app');
    roleId  = GetAppRoleId(destApp);

    if(window.location.href.indexOf("logout=true") !== -1){
        //its a forced logout
        localStorage.removeItem("userId");
        localStorage.removeItem("apiKey");
        localStorage.removeItem("token");
    }else if (localStorage.getItem("userId") !== null && localStorage.getItem("apiKey") !== null) {
        //User already logged in, redirect to app
        //validate role here?
        window.location.href = "index.php";
    }

	$('#frm-login').on("submit",function(e){

	    
        $("#login-error").hide();	    
        var pwd 	= $('#password').val();
        var email 	= $('#email').val();

		if(pwd !== '' && email !==''){
		    
	        $("#login-error").hide();
	        var data 		= {};
	        data.email 		= email;
	        data.password 	= pwd;
	        data.location 	= 1
	        data.roleId		= roleId;	    
	    
		    
	    
	  
	        $.ajax({
	            type:'post',
	            crossDomain: true,
	            cache: false,
	            url: apiUrl + "/user/login",
	            dataType:"json",
	            processData: false,
	            contentType: 'application/json',
	            data: JSON.stringify(data),
	            success:function(resp){
	                if(resp.cnt==0){
	                    $("#login-error").show();
	                    mixTrack("Login Error",{"appName":destApp,
		                    					"email":data.email,
		                    					"password":pwd});
	                    mixpanel.people.set({"$email": data.email});
	                } else {
						
						//LOGIN MIX PANEL EVENTS				
						mixpanel.identify(resp.id);
						mixpanel.people.set({"$email": data.email});
						mixpanel.track('Login',{"appName":resp.path,"email":data.email});
						
						
						//SAVING LOCAL STORAGE VARIABLES
	                    localStorage.setItem("userId", resp.id);
	                    localStorage.setItem("apiKey", resp.apiKey);
	                    localStorage.setItem("isLogin","1");
	                    
	                    
	                    //REDIRETING USER AFTER HALF A SEC TO GIVE MIX PANEL TIME TO SAVE EVENTS
	                    setTimeout(function(){window.location.href="/"+resp.path;},500);
	                }
	            }
	        });
					e.preventDefault()// cancel form submission
			return false;
		}
    });
});


function forgotPassword(){
    $("#success-message, #error-message").hide();

	var data = {};
	data.email = $('#email').val();

    $.ajax({
        type:'post',
        url: apiUrl + "/user/forgotpassword",
        dataType:"json",
        processData: false,
        contentType: 'application/json',
        data: JSON.stringify(data),
        success:function(resp){
            if(resp.result){
                $("#success-message").show();
				$("#btn-submit,#email-address").hide();
                mixTrack("Forgot Password Success",{"appName":destApp,"email":data.email});
            } else {
                $("#error-message").show();
                mixTrack("Forgot Password Error",{"appName":destApp,"email":data.email});
            }
        }
    });
}


function GetURLParameter(sParam){
    var sPageURL      = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');

    for (var i = 0; i < sURLVariables.length; i++){
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam){
            return sParameterName[1];
        }
    }
}

function GetAppRoleId(app){
    switch(app){
        case 'plus'           : return 14;
        case 'breaks'         : return 18;
        case 'grids'          : return 19;
        case 'movies'         : return 20;
        case 'shows'          : return 23;
        case 'packagebuilder' : return 26;
        case 'snapshot'       : return 28;
        case 'rcm'            : return 35;
    }

    return 14;
}