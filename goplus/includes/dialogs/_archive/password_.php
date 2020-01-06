<p></p>

<div class="gridwrapper" style="padding:15px;float:left;margin-left:10px;" id="password-update-interface">
	<table cellpadding="3">
		<tr>
			<td nowrap="nowrap" align="right">
				Password:
			</td>
			<td>
				<input class="input-q rounded-corners" id="account-password1" type="text"/>
			</td>
		</tr>
		<tr>
			<td nowrap="nowrap" align="right">
				Password Again:
			</td>
			<td>
				<input class="input-q rounded-corners" id="account-password2" type="text"/>
			</td>
		</tr>
		<tr>
			<td nowrap="nowrap" align="center" colspan="2">
				<p></p>
				<button onclick="saveChangePassword();" class="btn-green">
					Save Changes
				</button>
				<button onclick='javascript:closeAllDialogs();' class="btn-red">
					Cancel
				</button>
			</td>
		</tr>
	</table>
</div>

<div class="gridwrapper" style="padding:15px; display: none;" id="password-msg-interface">
	<p>
		<center>
			<i>Your Password has been updated.</i>
			<BR><BR>
			Use it the next time you login in ShowSeeker.
			<BR><BR>
		</center>
	</p>
</div>


<div class="gridwrapper" style="padding:15px; display: none;" id="password-error">
	<p>
		<center>
			<BR><BR>
			<span style="background-color: yellow">Password entries do not match.</span>
			<BR><BR>
		</center>
	</p>
</div>



<script type="text/javascript">
	function saveChangePassword(){
		var password  = $('#account-password1').val();
		var password2 = $('#account-password2').val();
		
		if(password == '' || password2 == ''){
			return;
		}

		if(password != password2){
			$('#password-update-interface,#password-msg-interface').hide();
			$('#password-error').show();			
			setTimeout(function(){
				$('#password-msg-interface,#password-error').hide();
				$('#password-update-interface').show();
			}, 3000);
			return;
		}

		$.ajax({
	        type:'post',
	        url: apiUrl+"user/changepassword",
	        dataType:"json",
	        headers:{"Api-Key":apiKey,"User":userid},
	        processData: false,
	        contentType: 'application/json',
	        data: JSON.stringify({"newPassword":password}),
	        success:function(resp){
	            if(resp.result){
	            	$('#account-password1,#account-password2').val('');
					$('#password-update-interface,#password-error').hide();
					$('#password-msg-interface').show();
					setTimeout(function(){
						$('#password-msg-interface,#password-error').hide();
						$('#password-update-interface').show();
						closeAllDialogs();
					}, 5000);

	            } else {
	            	alert('There was an error');
	            }
	        }
	    });
		
		/*
			var url = '/services/1.0/password.save.php';
		 
		    $.when(buildToken(url)).done(function(token){
					url 		= token['url']; 
					tokenid 	= token['key'];
					userid 	= token['userid'];			
					$.post(url, {
							tokenid: tokenid,
							userid: userid,					
							password: password
				    }, function(data) {
					    
					    var result = JSON.parse(data);
					    
					    if(result.response == "ok"){
							$('#account-password1,#account-password2').val('');
							$('#password-update-interface,#password-error').hide();
							$('#password-msg-interface').show();
							setTimeout(function(){
								$('#password-msg-interface,#password-error').hide();
								$('#password-update-interface').show();
								closeAllDialogs();
								
							}, 5000);
						}
						else{
							alert('There was an error');
						}
				    });
		    });
		*/
	}

	$("button").button();
</script>
