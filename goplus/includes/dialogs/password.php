<div  id="password-update-interface" align="center">
	<table cellpadding="3" style="width: 100%">
		<tr>
			<td nowrap="nowrap" align="center" colspan="2">
				<span style="color: red">*</span> 
				Password:
				<input class="input-q rounded-corners" id="account-password1" type="text" onkeyup="return emailGuideLines();"/>
			</td>
		</tr>
		<!--tr>
			<td nowrap="nowrap" align="right">
				Show Password
			</td>
			<td>
				<input type="checkbox" id="showPwdCtrl">
			</td>
		</tr -->
		<tr>
			<td>
				<ul>
					<li id="eightChars" style="margin: 3px 0;">Eight characters or longer</li>
					<li id="upperChars" style="margin: 3px 0;">One upper case character</li>
					<li id="lowerChars" style="margin: 3px 0;">One lower case character</li>
				</ul>
			</td>
			<td>
				<ul>
					<li id="consecutiveChars" style="margin: 3px 0;">Nonconsecutive characters</li>
					<li id="numberChars" style="margin: 3px 0;">One numbers</li>
					<li id="specialChars" style="margin: 3px 0;">One special characters</li>
				</ul>
			</td>
		</tr>
		
		<tr>
			<td align="center" colspan="2" style="padding: 5px; padding-top:15px;">
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


<div style="display:none; background:#c4f7c4; border:1px solid #58904e; padding:15px;" id="password-msg-interface" align="center">
	<b>Your Password has been updated!</b>
</div>

<div style="display:none; font-size:9pt; background: #fad0d0; border: 1px solid #f6abab; line-height: 18px; padding: 12px" id="password-error" align="center">
	<span id="resetErrorMessage"></span>
</div>


<script type="text/javascript">
	$('#showPwdCtrl').on('change',function(){
	    var x = document.getElementById("account-password1");
	    if (x.type === "password") {
	        x.type = "text";
	    } else {
	        x.type = "password";
	    }		
	});
	var pwdRulesOk = false;
	
	function saveChangePassword(){
		var password  	= $('#account-password1').val();
		var password2 	= $('#account-password2').val();
		
		if(password === '' || password2 === ''){
			return;
		}
	
		var guideLines 	= emailGuideLines(password, password2);
		$('#message').text(guideLines.msg);
		    
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
				closeAllDialogs();		        
	            /*if(resp.result){
					//$('#password-error,#password-update-interface').hide();
					//$('#password-update-interface,#password-msg-interface').show();
					closeAllDialogs();
					setTimeout(function(){ closeAllDialogs();}, 3000);
	            } 
	            else { 						
					$('#password-msg-interface').hide();
					$('#resetErrorMessage').text(guideLines.msg);
					$('#password-error').show();
					
	            }*/
	        }
	    });
	
	}
	
	function emailGuideLines(){
			var pwd1  	= $('#account-password1').val();
			var re;
			var r 		= true;	 
			var msg 	= ''; 
			var result	= {};
			var re = /(.)\1\1/.test(pwd1);
				
				
			if(re) {
				msg = 'Password must not include more than two consecutive characters!';
				$('#consecutiveChars').removeClass('resetPwd');
				r = false;
			}
			else{
				$('#consecutiveChars').addClass('resetPwd');
				r = true;
			}
			
			
			re = /[ !@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/g;			
			if(!re.test(pwd1)) {
				$('#specialChars').removeClass('resetPwd');
				msg = 'Password must contain at least one special character!';
				r = false;
			}
			else{
				$('#specialChars').addClass('resetPwd');
				r = true;					
			}
			
			
			re = /[A-Z]/;
			if(!re.test(pwd1)) {
				$('#upperChars').removeClass('resetPwd');
				msg = 'Password must contain at least one uppercase letter (A-Z)!';
				r = false;
			}
			else{
				$('#upperChars').addClass('resetPwd');
				r = true;
			}

			re = /[a-z]/;
			if(!re.test(pwd1)) {
				$('#lowerChars').removeClass('resetPwd');
				msg = 'Password must contain at least one uppercase letter (A-Z)!';
				r = false;
			}
			else{
				$('#lowerChars').addClass('resetPwd');
				r = true;
			}

			
			
			re = /[0-9]/;
			if(!re.test(pwd1)) {
				$('#numberChars').removeClass('resetPwd');
				msg = 'Password must contain at least one number (0-9)!';
				r = false;
			}
			else{
				$('#numberChars').addClass('resetPwd');
				r = true;
			}
			
			
			if(pwd1.length < 8) {
				$('#eightChars').removeClass('resetPwd');
				msg = 'Password must contain at least eight characters!';
				r = false;
			}
			else{
				$('#eightChars').addClass('resetPwd');
				r = true;
			}

			pwdRulesOk = r;
		    if(r){
				$('#btnResetPwd').prop('disabled',false).button('refresh');
		    }
		    else{
				$('#btnResetPwd').prop('disabled',true).button('refresh');			    
		    }
		    
		    result.msg = msg;
		    result.isValid = r;
		    return result;
		};
	

	$('#btnResetPwd').prop('disabled',true);
	$("button").button();	
</script>
