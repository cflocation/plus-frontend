
$("#pwd").on('keyup',function(){
	passwordGuideLines();
});

$("#pwdReset").on("click",function(e){
	var isNewPwdValid = passwordGuideLines();
	if(isNewPwdValid){
		externalResetPassword();
		e.preventDefault()// cancel form submission		
		return false;
	}
});

$("#submitBtn").on("click",function(e){
	var isNewPwdValid = $('#usrMail').val();
	if(isNewPwdValid !== ''){
		$('#submitBtn').prop('disabled',true);
		sendToken();
		e.preventDefault()// cancel form submission		
		return false;
	}
});
	
	
function passwordGuideLines(){
	var pwd1  	= $('#pwd').val();
	var re;
	var r 		= true;	 
	var msg 	= ''; 
	var result	= {};
	
	
	if(pwd1 === ''){
		$('#pwdReset').addClass('ui-button-disabled ui-state-disabled').button('refresh');
		$('#pwdReset').prop('disabled',true).button('refresh');		
		$('li').removeClass('resetPwd3');
		return false;
	}
	var re = /(.)\1\1/.test(pwd1);
		
	if(re) {
		$('#consecutiveChars').removeClass('resetPwd3');
		r = false;
	}
	else{
		$('#consecutiveChars').addClass('resetPwd3');
	}
	
	
	re = /[ !@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/g;			
	if(!re.test(pwd1)) {
		$('#specialChars').removeClass('resetPwd3');
		r = false;
	}
	else{
		$('#specialChars').addClass('resetPwd3');
	}
	
	
	re = /[A-Z]/;
	if(!re.test(pwd1)) {
		$('#upperChars').removeClass('resetPwd3');
		r = false;				
	}
	else{
		$('#upperChars').addClass('resetPwd3');
	}

	re = /[a-z]/;
	if(!re.test(pwd1)) {
		r = false;				
		$('#lowerChars').removeClass('resetPwd3');
	}
	else{
		$('#lowerChars').addClass('resetPwd3');
	}

	
	re = /[0-9]/;
	if(!re.test(pwd1)) {
		r = false;				
		$('#numberChars').removeClass('resetPwd3');
	}
	else{
		$('#numberChars').addClass('resetPwd3');
	}
	
	
	if(pwd1.length < 8 || pwd1.length > 25) {
		r = false;				
		$('#eightChars').removeClass('resetPwd3');
	}
	else{
		$('#eightChars').addClass('resetPwd3');
	}
	
	
	
	
    if(r){
		$('#pwdReset').removeClass('ui-button-disabled ui-state-disabled').button('refresh');
		$('#pwdReset').prop('disabled',false).button('refresh');
    }
    else{
		$('#pwdReset').addClass('ui-button-disabled ui-state-disabled').button('refresh');
		$('#pwdReset').prop('disabled',true).button('refresh');			    
    }
    
    return r;
};  		

