document.domain 	= "showseeker.com";	
var sswin 			= window.opener;
	
function saveChangePassword(){
	var email  		= $('#usr').val();
	var password  	= $('#old').val();
	var newPassword  = $('#pwd').val();
	
	if(password === '' || password2 === ''){
		return;
	}
	    
	var data = {};
	data.userId 		= userid;
	data.password 		= password;
	data.newPassword 	= password;
	
    $.ajax({
        type:'post',
        url: "https://plusapi.showseeker.com/user/passwordreset/reset",
        dataType:"json",
        processData: false,
        contentType: 'application/json',
        data: JSON.stringify(data),
        success:function(resp){
        }
    });
};

function externalResetPassword(){
	var newPassword  	= $('#pwd').val();
	var userId  		= parseInt($('#userId').val());	
	if(newPassword !== ''){	    
		var data 			= {};
		data.password 		= newPassword;
		data.userId 		= userId;
		data.token 			= token;
		
		var apiUrl = "https://plusapi.showseeker.com/user/passwordreset/changepassword";
		$.ajax({
			type:'post',
			url: apiUrl,
			dataType:"json",
			processData: false,
			contentType: 'application/json',
			data: JSON.stringify(data),
			success:function(resp){
				if(app !== 'plus' && app !== 'grids' && app !== 'go'){
					app = "email";
				}
				
				if(resp.result.error === false){
					$('#errorMessage').show();
					$('#okMessage').hide();
					mixpanel.identify(userId);
					mixpanel.track("Password Reset",{"location":app,"result":"Error","event":"Password Update","userId":userId});
				}
				else{
					$('#errorMessage').text('');
					$('#errorMessage,#pwdReset').hide();
					$('#okMessage').show();
					if(app === 'grids'){
						$('#gridsLink').show();
					}
					else if(app === 'go'){
						$('#goLink').show();
					}
					else{
						//localStorage.removeItem("userId");
						//localStorage.removeItem("apiKey");
						if(sswin){
							sswin.pwdToken();
						}
						else{
							$('#plusLink').show();
						}
					}

					mixpanel.identify(userId);
					mixpanel.track("Password Reset",{"location":app,"result":"Updated","event":"Password Update","userId":userId});
				}
				
				setTimeout(function(){
					$('#errorMessage,#okMessage').hide();
					if(window.opener){ //if(app === 'plus' || app === 'grids'){
						$("#close").show();
					}
				}, 4000);
			}
		});
	}
};
			
		
function sendToken(){
	var data 	= {};
	data.email 	= $('#usrMail').val();
	data.userId = $('#userId').val();
	data.app 	= userApp;
	
    $.ajax({
        type:'post',
        url: "https://plusapi.showseeker.com/user/passwordreset/sendlink",
        dataType:"json",
        processData: false,
        contentType: 'application/json',
        data: JSON.stringify(data),
        success:function(resp){
			if(resp.result === false){
				$('#errorMessage').show();
				$('#submitBtn').prop('disabled',false);				
				setTimeout(function(){
					$('#errorMessage,#okMessage').hide();
				}, 4000);
				mixpanel.track("Password Reset",{"appName":userApp,"email":data.email,"result":"Error","event":"Link Request"});
			}
			else{
				$('#okMessage').show();
				$('#submitBtn').hide();
                mixpanel.people.set({"$email": data.email});			
				mixpanel.track("Password Reset",{"appName":userApp,"email":data.email,"result":"Sent","event":"Link Request"});
			}
        }
    });	
};



function verifyToken(){    
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
	        $('#dbError').hide();	        
	        if(resp.result === false){
				$('#errorMessage').text('Error: Wrong Token.').show();
				$('#resetAction,#newLink').show();
				$('#plusLink,#pwdReset').hide();		        
	        }
			else if(resp.result.expired === true){
				$('#errorMessage').text('Error: Token has expired.').show();
				$('#resetAction,#newLink').show();
				$('#plusLink,#pwdReset').hide();
			}
			else if(resp.result.used === true){
				$('#errorMessage').text('Error: Token has been already used.').show();
				$('#newLink').show();				
			}
			else{
				$('#errorMessage').hide();
				$('.pwdFrm').show();
				$('#userId').val(resp.result.id)				
			}
        },
        error:function(){
	        $('#frm-login').hide();
	        $('#dbError').show();
        }
    });
}


function pwdToken(){
	var data 	= {};
	data.userId = userid;

	$.ajax({type:'post',
            url: apiUrl+"user/passwordreset/passwordtoken",
            dataType:"json",
            headers:{"Api-Key":apiKey,"User":userid},
            processData: false,
            contentType: 'application/json',
			data:JSON.stringify(data),
			success:function(resp){
				localStorage.setItem("token", resp.token);
			}
	});
}	
		
		
		
		