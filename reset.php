<?php
$uuid = uniqid();
?>
<!DOCTYPE html>
<html>
	<head>		
		<link rel="stylesheet" href="css/reset.pasword.css?r=<?php print $uuid;?>">
		<link rel="stylesheet" href="css/custom-theme/jquery.ui.all.css?r=<?php print $uuid; ?>">
		<link rel="stylesheet" href="css/custom-theme/jquery-ui-1.8.21.custom.css?r=<?php print $uuid; ?>">
		<link href="https://showseeker.s3.amazonaws.com/public-site/assets/favicon.png" name="favicon" rel="shortcut icon" type="image/png">
		
		<script src="/js/jquery-1.7.2.min.js"></script>
		<script src="/js/ui/minified/jquery.ui.core.min.js"></script>
		<script src="/js/ui/minified/jquery.ui.widget.min.js"></script>
		<script src="/js/ui/minified/jquery.ui.button.min.js"></script>
		<title>Reset Password | ShowSeeker</title>
	</head>
	<body>
		<div style="width:100%;" align="center">
			
			<div class="panel radius" style="width: 500px">
				<p>
					<center><img src="/images/logo200.png?r=<?php print $uuid; ?>"></center>
				</p>
				<br>	
				<p>
					<form id="pwdForm" action method="post">
						<p>
							<center>
								<table>
									<tr id="trEmail">
										<td width="100%" align="right" height="30px">
											<label for="usr"><span style="color: red;">*</span> Email: </label>
											<input type="email" required="true" maxlength="80" width="100" id="usrMail">
										</td>
									</tr>
									
								</table>
							</center>
						</p>
						<div style="height: 20px; width: 100%;">
							<div class="panel radius" style="display: none; font-size: 11px; background: #fad0d0;  border: 1px solid #f6abab; color: #555;" id="errorMessage">
								Please check your email and try again.
							</div>
							<div class="panel radius" style="display: none; font-size: 11px; background:#c4f7c4;  border: 1px solid #58904e;; color: #555;" id="okMessage">
								A link has been sent to your email to reset your password.
							</div>
						</div>
						<br><br>
						<p>
							<div align="center">
								<button class="button tiny green" id="submitBtn"> Reset Password </button>
								<a href="javascript:window.close()" style="display: none; font-size: 12px; text-decoration:none; color:blue;" id="close"> Close </a>
							</div>
						</p>
					</form>
				</p>
			</div>
		</div>
		<div class="row" style="color: rgb(0, 0, 0); font-size: 11px;">
			<center>GOPlus v1.5.42</center>
		</div>  			
	</body>
	<script src="js/mixPanel.js"></script>  
	<script src="js/log.events.js"></script>
	<script src="js/reset.pwd/controller.js?r=<?php print $uuid; ?>"></script>
	<script src="js/reset.pwd/model.js?r=<?php print $uuid; ?>"></script>
	<script>
		var userEmail = "<?php print($_GET['email']);?>";
		var userApp = "<?php print($_GET['app']);?>";
		$('#resetUsr').val(userEmail);
	</script>
</html>