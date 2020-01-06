<?php $uuid = uniqid(); ?>
<!DOCTYPE html>
<html>
	<head>		
		<link rel="stylesheet" href="css/reset.pasword.css?r=<?php print $uuid;?>">
		<link rel="stylesheet" href="goplus/css/custom-theme/jquery.ui.all.css?r=<?php print $uuid; ?>">
		<link rel="stylesheet" href="goplus/css/custom-theme/jquery-ui-1.8.21.custom.css?r=<?php print $uuid; ?>">
		<link href="https://showseeker.s3.amazonaws.com/public-site/assets/favicon.png" name="favicon" rel="shortcut icon" type="image/png">
		<script src="/js/jquery-1.7.2.min.js"></script>
		<script src="/js/ui/minified/jquery.ui.core.min.js"></script>
		<script src="/js/ui/minified/jquery.ui.widget.min.js"></script>
		<script src="/js/ui/minified/jquery.ui.button.min.js"></script>
		<title>Reset Password | ShowSeeker</title>
	</head>
	<body>
<?php 
	//include("status/index.html");
?>
		<div style="width:100%;" align="center" id="frm-login">
			<div class="panel radius" style="width: 500px">
				<p>
					<center><img src="/images/logo200.png?r=<?php print $uuid;?>"></center>
				</p>
				<br>
				<p>
					<form id="pwdForm" action method="post">
						<div class="pwdFrm" style="display: none;">
							<center>
								<table>
									<tr id="trNewPwd">
										<td width="100%"  height="30px">
											<label for="pwd"><span style="color: red;">*</span> <span style="color: #000;">New Password:</span> </label>
											<input type="text" required="true" maxlength="50" width="100" id="pwd">
											<input type="hidden" id="userId">
										</td>
									</tr>
								</table>
							</center>
							<div align="center">
								<table style="font-size: 9pt; color: #000; width: 88%;">
									<tr>
										<td width="46%">
											<ul>
												<li id="eightChars">8 to 25 characters long</li>
												<li id="upperChars">One upper case character </li>
												<li id="lowerChars">One lower case character </li>
											</ul>
										</td>
										<td width="54%">
											<ul>
												<li id="consecutiveChars">Nonconsecutive characters</li>
												<li id="numberChars">One number</li>
												<li id="specialChars">One special character</li>
											</ul>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div style="height: 20px; width: 100%;">
							<div class="panel radius" style="display: none; font-size: 11px; background: #fad0d0;  border: 1px solid #f6abab; color: #555;" id="errorMessage">
								Invalid token!
							</div>
							<div class="panel radius" style="display: none; font-size: 11px; background: #fad0d0;  border: 1px solid #f6abab; color: #555;" id="pwdTooLong">
								Password cannot be longer than 25 characters.
							</div>
							<div class="panel radius" style="display: none; font-size: 11px; background: #f1f1f1;  border: 1px solid #f6abab; color: #555;" id="keepOnMessage">
								“Keep going! <br>Simply review the lines in darker type and adjust your password to include those elements.”
							</div>
							<div class="panel radius" style="display: none; font-size: 11px; background:#c4f7c4;  border: 1px solid #58904e;; color: #555;" id="okMessage">
								Successful password update!
							</div>
						</div>
						<br><br>
						<div class="pwdFrm" style="display:none;" id="resetAction">
							<div align="center">
								<button class="button tiny green ui-button-disabled ui-state-disabled" id="pwdReset" disabled="true"> Update Password </button>
								<a href="https://plus.showseeker.com/login.php" style="display: none; font-size: 12px; text-decoration:none; color:blue;" id="plusLink"> Go To ShowSeeker Plus </a>
								<a href="http://ezgrids.com/index.php" style="display: none; font-size: 12px; text-decoration:none; color:blue;" id="gridsLink"> Go To EzGrids</a>
								<a href="http://go.showseeker.com/" style="display: none; font-size: 12px; text-decoration:none; color:blue;" id="goLink"> ShowSeeker GO</a>
								<a href="https://plus.showseeker.com/reset.php" style="display:none; font-size: 12px; color:blue;" id="newLink"> Request new Token</u></a>
								<a href="javascript:window.close()" style="display: none; font-size: 12px; text-decoration:none; color:blue;" id="close"> Close </a>
							</div>
						</div>
					</form>
					<p></p>
					<div style="font-size: 8pt; color: #333; width: 80%;">
						Have Questions? Email us at <a href="mailto:support@showseeker.com?Subject=Reset_Password">support@showseeker.com</a>
					</div>
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
		var userEmail 	= "<?php print($_GET['email']);?>";
		var t 			= "<?php print(trim($_GET['t']));?>";
		if(t === ''){
			 t 			= "<?php print(trim($_GET['token']));?>";			
		}
		var token 		= t.split(' ')[0];
		var app 		= "<?php print($_GET['app']);?>";
		$('#usr').val(userEmail);
		verifyToken();
		
		
		$("#pwd").bind("paste", function(e){
		    var pastedData = e.originalEvent.clipboardData.getData('text').length;
		    if(pastedData > 25){
			    $('#pwdTooLong').show();
			    setTimeout(function(){
				    $('#pwdTooLong').hide();
			    }, 4000)
		    }
		} );		
		
	</script>
</html>