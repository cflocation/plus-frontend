var newUsrId;


function resetMessages(){
  document.getElementById('login-error').style.display = 'none';
  document.getElementById('request-reset').style.display = 'none';
  document.getElementById('token-error').style.display = 'none';
  document.getElementById('success-message').style.display = 'none';
  document.getElementById('reset-password-message').style.display = 'none';
  document.getElementById('success-message-reset-email').style.display = 'none';

}



function autoLogin(){
	
	var data 	= {};
	data.email 	= $('#email').val();

	if(data.email && isValidEmailAddress(data.email)){
	    $.ajax({
	        type:'post',
	        url: "https://plusapi.showseeker.com/user/quicklogin/sendlink",
	        dataType:"json",
	        processData: false,
	        contentType: 'application/json',
	        data: JSON.stringify(data),
	        success:function(resp){
	            if(resp.result){
	                $("#success-message").show();
                  $('#messageModal').foundation('open');
					$("#btn-submit,#email-address,#error-message").hide();
	            } else {
	                $("#error-message").show();
					$("#warning-message").hide();
	            }			
	        }
	    });
	}
	else{
		$("#error-message").hide();
		$("#warning-message").show();
		$("#message").text('Please enter a valid email');
			
	}
	
	return;
};




function checkMagicalLink(token){
	var data 	= {};
	data.token 	= token;
		
    $.ajax({
        type:'post',
        url: "https://plusapi.showseeker.com/user/quicklogin/verifytoken",
        dataType:"json",
        processData: false,
        contentType: 'application/json',
        data: JSON.stringify(data),
        success:function(resp){
            if(resp.result.apiKey){
	  			localStorage.setItem("userId", resp.result.id);
	  			localStorage.setItem("apiKey", resp.result.apiKey);
	  			window.location.href="index.php";
            } else {
                $("#token-error").show();
            }
        }
    });	
};



function checkToken(token){
	if(!token){

        $("#error-message").show();
	}
	else{
		var data 	= {};
		data.token 	= token;
			
	    $.ajax({
	        type:'post',
	        url: "https://plusapi.showseeker.com/user/passwordreset/verifytoken",
	        dataType:"json",
	        processData: false,
	        contentType: 'application/json',
	        data: JSON.stringify(data),
	        success:function(resp){
	            if(resp.result){
	                $("#error-message,#request-reset").hide();
	                $("#newPassword,#confirm,#btn-submit,#email-address,").show();
	                newUsrId = resp.result.id;
	            } else {
	                $("#error-message").show();
	            }
	        }
	    });
    }
    return;
};





function formInit(type){
  //login-form
  if(type === 'reset'){
    document.getElementById('login-form').style.display = 'none';
    document.getElementById('reset-form').style.display = 'inline';
    document.getElementById('reset-password-message').style.display = 'inline';
    resetMessages();
  }else{
    document.getElementById('login-form').style.display = 'inline';
    document.getElementById('reset-form').style.display = 'none';
    document.getElementById('reset-password-message').style.display = 'none';
    resetMessages();
  }
}

        
function resetPwd(){
	
	var pwd1 		= $('#password1').val(); 
	var pwd2 		= $('#password2').val();
	var guideLines 	= emailGuideLines(pwd1,pwd2);

	$('#message').text(guideLines.msg);
	
	if(guideLines.isValid){
		$('#warning-message').hide();	    
		
		var data = {};
		data.userId 	= newUsrId;
		data.password 	= pwd1;
	    $.ajax({
	        type:'post',
	        url: "https://plusapi.showseeker.com/user/passwordreset/reset",
	        dataType:"json",
	        processData: false,
	        contentType: 'application/json',
	        data: JSON.stringify(data),
	        success:function(resp){
	            if(resp.result){
	                $("#newPassword,#confirm,#btn-submit,#email-address,#guidelines").hide();
	                $("#success-message-reset").show();
	            } else {
	                $("#error-message").show();
	            }
	        }
	    });


	}
	else{
		$('#warning-message').show();
		$('#password1').focus();
	}
	
	return false;
 };



function emailGuideLines(pwd1, pwd2){
	var re;
	var r 		= true;	 
	var msg 	= ''; 
	var result	= {};
	
	if(pwd1 !== "" && pwd1 === pwd2) {

		re = /[ !@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/g;			
		if(!re.test(pwd1)) {
			msg = 'Password must contain at least one special character!';
			r = false;
		}

		re = /[A-Z]/;
		if(!re.test(pwd1)) {
			msg = 'Password must contain at least one uppercase letter (A-Z)!';
			r = false;
		}

		re = /[a-z]/;
		if(!re.test(pwd1)) {
			msg = 'Password must contain at least one lowercase letter (a-z)!';
			r = false;
		}

		re = /[0-9]/;
		if(!re.test(pwd1)) {
			msg = 'Password must contain at least one number (0-9)!';
			r = false;
		}

		if(pwd1.length < 8) {
			msg = 'Password must contain at least eight characters!';
			r = false;
		}			
			
    } 
    else {
		msg = 'Please check that you have entered and confirmed your password!';
		r = false;
    }
    
    result.msg = msg;
    result.isValid = r;
    return result;
    
};


function strongLogin(){
        var data = {"email":$('#email').val(),"password":$('#password').val(),"location":1};
        $.ajax({
            type:'post',
            crossDomain: true,
            cache: false,
            url: "https://plusapi.showseeker.com/user/loginv2",
            dataType:"json",
            processData: false,
            contentType: 'application/json',
            data: JSON.stringify(data),
            success:function(resp){

                if(resp.needsPassReset === true){
	                $("#request-reset").show();
                    $("#login-error").hide();
                }
                else if(parseInt(resp.cnt) === 0){
                    $("#login-error").show();
                    $("#request-reset").hide();
                }
                else {
                    localStorage.setItem("userId", resp.id);
                    localStorage.setItem("apiKey", resp.apiKey);
                    window.location.href="index.php";
                }
                return false;
            }
        });
    return false;
};


function sendPwdReset(){
	var data 	= {};
	data.email 	= $('#email').val();

	
	if(data.email && isValidEmailAddress(data.email)){	
	    $.ajax({
	        type:'post',
	        url: "https://plusapi.showseeker.com/user/passwordreset/sendlink",
	        dataType:"json",
	        processData: false,
	        contentType: 'application/json',
	        data: JSON.stringify(data),
	        success:function(resp){
	            if(resp.result){
	                $("#success-message-reset-email").show();
					$("#btn-submit,#email-address,#error-message,#warning-message").hide();
	            } else {
					$("#warning-message").hide();
	                $("#error-message").show();
	            }
	        }
	    });	
	}
	else{	
		$("#error-message").hide();
		$("#warning-message").show();
		$("#message").text('Please enter a valid email');
			
	}
	
	return;
}


/*
$("#magic-link-btn").hover(
  function() {
    document.getElementById('magic-link').style.display = 'inline';
  }, function() {
    document.getElementById('magic-link').style.display = 'none';
  }
);
*/
 
function isValidEmailAddress(emailAddress) {
	var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
}; 



