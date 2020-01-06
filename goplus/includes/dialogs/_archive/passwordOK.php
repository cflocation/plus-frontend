<p></p>

<div class="gridwrapper" id="password-update-interface" align="center">
	<table cellpadding="3" style="width: 100%">
		<tr>
			<td nowrap="nowrap" align="right">
				<span style="color: red">*</span> Password:
			</td>
			<td>
				<input class="input-q rounded-corners" id="account-password1" type="text"/>
			</td>
		</tr>
		<tr>
			<td nowrap="nowrap" align="right">
				Confirm New Password:
			</td>
			<td>
				<input class="input-q rounded-corners" id="account-password2" type="text"/>
			</td>
		</tr>
		<tr>
			<td align="center" colspan="2" style="padding: 5px;">
				<p></p>
				<button onclick="saveChangePassword();" class="btn-green" id="btnResetPwd">
					Save Changes
				</button>
				<button onclick='javascript:closeAllDialogs();' class="btn-red">
					Cancel
				</button>
			</td>
		</tr>
	</table>
</div>

<p>
	<div style="display:none; background:#c4f7c4; border:1px solid #58904e; padding:15px;" id="password-msg-interface" align="center">
		<b>Your Password has been updated!</b>
	</div>
	
	<div style="display:none; font-size:9pt; background: #fad0d0; border: 1px solid #f6abab; line-height: 18px; padding: 12px" id="password-error" align="center">

		<span id="resetErrorMessage"></span>
	</div>		
</p>

<p>
	<div  align="center">
		<span style="color: red">*</span> 
		<b>Password Guidelines: </b>It should be at least 8 characters long. 
		Use at least one number, one capital letter and one special character.
	</div>
</p>


<script type="text/javascript">
	function saveChangePassword(){

		var password  	= $('#account-password1').val();
		var password2 	= $('#account-password2').val();
		
		if(password === '' || password2 === ''){
			return;
		}

		var guideLines 	= emailGuideLines(password, password2);
	
		$('#message').text(guideLines.msg);
		
		if(guideLines.isValid){	    
			
			var data = {};
			data.userId 	= userid;
			data.password 	= password;
		    $.ajax({
		        type:'post',
		        url: "https://plusapi.showseeker.com/user/passwordreset/reset",
		        dataType:"json",
		        processData: false,
		        contentType: 'application/json',
		        data: JSON.stringify(data),
		        success:function(resp){
		            if(resp.result){
						$('#password-error,#password-update-interface').hide();
						$('#password-update-interface,#password-msg-interface').show();
						setTimeout(function(){ closeAllDialogs();}, 3000);
		            } 
		            else { 						
						$('#password-msg-interface').hide();
						$('#resetErrorMessage').text(guideLines.msg);
						$('#password-error').show();
						
		            }
		        }
		    });
		}
		else{			
			$('#resetErrorMessage').text(guideLines.msg);				
			$('#password-error').show();			
		}


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

	}

	$("button").button();
</script>
