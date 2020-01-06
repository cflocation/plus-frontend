<?php
	header('Location:goplus/password.php');    
    include_once('config/database.php');
	require_once 'inc/lib/swift_required.php';
	
	if(isset($_POST['email'])){

		$email = $_POST['email'];
		$sql = "SELECT * FROM users WHERE email = '{$email}' LIMIT 1";
		$re = mysql_query($sql);
		$num_rows = mysql_num_rows($re);
		$row = mysql_fetch_array($re);
		
		
		if($num_rows == 1){
		
			if ($row['deletedat'] == null) {

				$message = "<div style='font-family:Arial, Helvetica, sans-serif; font-size:13px;'>Hello!<br /><br />
							Your password for ShowSeeker is: ".$row['password']."<br />Site Address: http://plus.showseeker.com<br /><br />
							If you have any questions or comments, please feel free to contact us at:<br />support@showseeker.com<br /><br />
							Thank you, <br />The ShowSeeker Team. </div>";
			}
		else {
				$message = "We do not recognize your email address and/or password.\r\n\r\n
							Please contact our Support Team for assistance at:\r\n
							support@showseeker.com\r\n\r\n
							Thank you, \r\nThe ShowSeeker Team.";
		}
	
		$transport = Swift_SmtpTransport::newInstance('smtpout.secureserver.net', 465, "ssl")->setUsername('help@showseeker.com')->setPassword('C0v3nant');


		$mailer = Swift_Mailer::newInstance($transport);

		$message = Swift_Message::newInstance('ShowSeeker Password')
			->setFrom(array('help@showseeker.com' => 'ShowSeeker Support'))
			//->setCc(array('support@showseeker.com' => 'ShowSeeker - Support Account'))
			->setTo(array($email))->setBody($message, 'text/html' );

		$result = $mailer->send($message);


		$good = true;

		}else{
			$error = true;
		}
		
	}else{
		session_destroy();
	}
	
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="cache-control" content="no-cache"> <!-- tells browser not to cache -->
		<meta http-equiv="expires" content="0"> <!-- says that the cache expires 'now' -->
		<meta http-equiv="pragma" content="no-cache"> <!-- says not to use cached stuff, if there is any -->
		<link href="https://showseeker.s3.amazonaws.com/public-site/assets/favicon.png" name="favicon" rel="shortcut icon" type="image/png">
		<link rel="stylesheet" href="inc/fontawesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="inc/foundation/css/normalize.css">
		<link rel="stylesheet" href="inc/foundation/css/foundation.css">
		<link rel="stylesheet" href="inc/timepicker/jquery.timepicker.css">
		<link rel="stylesheet" href="css/drk-theme/jquery-ui-1.10.4.custom.min.css">
		<link rel="stylesheet" href="css/global.css">
		
		<title>ShowSeeker Plus - Login</title>
	</head>

	<body>	
		
		<br><br>

		<form  action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		
		<div class="large-4 small-centered columns">
		
			<div class="panel radius">
		
				<center><img src="images/logo200.png"></center>
				<br><br>
				<?php
					if($good){
						print '
						
							  <div class="row">
							    <div class="small-12">
							      <div class="row">
							        <div class="small-4 columns">
							          <label class="right inline"></label>
							        </div>
							        <div class="row collapse">
								        <div class="small-8 columns">
								          <span style="font-size:12pt;">&nbsp;&nbsp;&nbsp; &nbsp; Email Sent</span>
								        </div>
							        </div>
							      </div>
							    </div>
							  </div>						
							  <BR><BR>

							  <div class="row">
							    <div class="small-12">
							      <div class="row">
							        <div class="small-4 columns">
							          <label class="right inline"></label>
							        </div>
							        <div class="row collapse">
								        <div class="small-8 columns">
											<a href="login.php"><span style=font-size:12pt>Back to login page</span></a>
								        </div>
							        </div>
							      </div>
							    </div>
							  </div>';
						exit;
					}
				?>
				<?php			
					if($error){
						print '<div class="row">
									<div class="small-12">
										<div class="row">
											<div class="small-4 columns">
												<label class="right inline"></label>
											</div>
											<div class="row collapse">
												<div class="small-7 columns" style="background: #fad0d0;  border: 1px solid #f6abab; padding: 10px 10px 7px 19px;">
													Invalid Email Adddress
												</div>
											</div>
										</div>
									</div>
								</div>
								<BR>';
					}
				?>	  
				
				<div class="row">
					<div class="small-12">
					  <div class="row">
					    <div class="small-4 columns">
					      <label for="email" class="right inline">
					          Email Address:
					      </label>
					    </div>
					    <div class="row collapse">
					    <div class="small-7 columns">
					        <input required type="text" name="email" id="email">
					    </div>
					    </div>
					  </div>
					</div>
				</div>

			  <div class="row">
			    <div class="small-12">
			      <div class="row">
			        <div class="small-4 columns">
			          <label for="ratecard-market" class="right inline"></label>
			        </div>
			        <div class="row collapse">
			        <div class="small-8 columns">
			          <button type="submit" class="button tiny green" style="padding:8px;">Send Password</button>
			        </div>
			        </div>
			      </div>
			    </div>
			  </div>
			  
			  <BR><BR>
			  
			  <div class="row">
			    <div class="small-12">
			      <div class="row">
			        <div class="small-4 columns">
			          <label for="ratecard-market" class="right inline"></label>
			        </div>
			        <div class="row collapse">
			        <div class="small-8 columns">
			         	<a href="login.php">Back to login page</a>
			        </div>
			        </div>
			      </div>
			    </div>
			  </div>
				

			</div>
		</div>
		
		</form>	
		
	</body>
	
</html>
