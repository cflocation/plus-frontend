<?php
	$token = false;	
	if(isset($_GET['m'])){
		$token =  $_GET['t'];	
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon" /> 

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="cache-control" content="no-cache"> <!-- tells browser not to cache -->
    <meta http-equiv="expires" content="0"> <!-- says that the cache expires 'now' -->
    <meta http-equiv="pragma" content="no-cache"> <!-- says not to use cached stuff, if there is any -->

    <link rel="stylesheet" href="/inc/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/inc/foundation/css/normalize.css">
    <link rel="stylesheet" href="/inc/foundation/css/foundation.css">
    <link rel="stylesheet" href="/inc/timepicker/jquery.timepicker.css">
    <link rel="stylesheet" href="/css/drk-theme/jquery-ui-1.10.4.custom.min.css">
    <link rel="stylesheet" href="/css/global.css">

	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
	
	  ga('create', 'UA-93179105-2', 'auto');
	  ga('send', 'pageview');
	</script> 

    <title>ShowSeeker Plus - Login</title>
</head>
<body>
    <br/>
    <br/>
    <form id="frm-login" action="" method="post" onsubmit="return strongLogin();">
    <!-- form id="frm-login" action="" method="post" -->
        <div class="large-4 small-centered columns">
            <div class="panel radius">
                <center>
                	<img src="/images/logo200.png">
                </center>
                
                <br/><br/>

                <div id="login-error" style="display:none;">
                    <div class="row">
                        <div class="small-12">
                            <div class="row">
                                <div class="row collapse">
                                    <div class="small-12 columns"  style="background: #fad0d0;  border: 1px solid #f6abab; padding: 15px; font-size: 9pt;">
	                                    <center><b>Invalid Username or Password</b></center>
	                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br/>  
                </div>

				<div id="request-reset" style="display:none;">
                    <div class="row">
                        <div class="small-12">
                            <div class="row">
                                <div class="row collapse">
									<div class="small-12 columns" style="background:#FFE086;  border: 1px solid #f4e06e; padding: 15px; font-size:9pt; line-height: 18px;">
	                                    <center><b>We have updated our login system.<br />Please <a href="#" onclick="javascript:resetAccount('reset')">Reset your Password</a>.</b></center>
	                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br/>  
                </div>

                <div id="token-error" style="display:none;">
                    <div class="row">
                        <div class="small-12">
                            <div class="row">
                                <div class="row collapse">
                                    <div class="small-12 columns" style="background:#ffd183;  border: 1px solid #a38215; padding: 15px; font-size:9pt; line-height: 18px;">
	                                    <center>
		                                    <b>The auto sign-in key has expired.<br />Request another one or Reset your password.</b>
	                                    </center>
	                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br/>  
                </div>
       

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
			                    <label for="password" class="right inline">
			                        Password:
			                    </label>
			                </div>
			                <div class="row collapse">
			                    <div class="small-7 columns">
			                        <input type="password" name="password" id="password">
			                    </div>
			                </div>
			            </div>
			        </div>
			    </div>
			    
			    <div class="row">
			        <div class="small-12">
			            <div class="row">
			                <div class="small-4 columns" style="line-height: 30px;">&nbsp;</div>
			                <div class="small-4 columns" style="line-height: 30px;">
			                    <label class="inline"><a href="#" onclick="javascript:resetAccount('reset')">Forgot Password</a></label>
			                </div>
			                <div class="small-1 columns"style="line-height: 30px;">&nbsp;</div>
			                <div class="small-2 columns" style="line-height: 30px;">
								<label class="inline"><a href="#" onclick="javascript:resetAccount('auto')" title="Magic sign-in link">Auto Login <i class="fa fa-magic"></i></a></label>
			                </div>
			                <div class="small-1 columns"style="line-height: 30px;">&nbsp;</div>
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
			                    <div class="small-7 columns">
			                        <textarea name="" id="" cols="30" rows="10" style="height:150px;">
			                            <?php include_once('../inc/terms.php'); ?>
			                        </textarea>
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
			                <div class="row collapse" style="line-height: 30px;">
			                    <div class="small-5 columns">
			                        <!-- button type="submit" class="button tiny green" style="padding:8px;">I Agree & Login</button -->
									<!-- XXa href="#" class="button tiny green" style="padding:8px;" onclick="strongLogin();">I Agree & Login</a XX -->
									<input  type="submit"class="button tiny green" style="padding:8px;" value="I Agree & Login">
			                    </div>
			                    <!-- div class="small-3 columns">
			                        <a href="#" class="button tiny" onclick="secureLogin();">Strong Password</a>
			                    </div -->
			                </div>
			            </div>
			        </div>
			    </div>


			</div>
		</div>
	</form>

	<!-- Latest compiled and minified JavaScript -->
	<script src='/js/jquery-1.7.2.min.js'></script>
	<script src='/js/jquery.event.drag-2.0.min.js'></script>
	<script src='/js/jquery.event.drop-2.0.min.js'></script>
	<script src='/inc/foundation/js/foundation.min.js'></script>
	<!-- script src='js/authenticate.js?v=6'></script -->
	<script src='js/reset.account.js?r=<?php print uniqid(); ?>'></script>
	
	<script>
	var token = '<?php print($token);?>';
	$(document).foundation();
	$(document).ready(function(){
		if(token){
			checkMagicalLink(token);
		}
	});
	</script>
</body>
</html>