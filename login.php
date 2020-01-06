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
        
        <!-- link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon" /-->
		  <link href="https://showseeker.s3.amazonaws.com/public-site/assets/favicon.png" name="favicon" rel="shortcut icon" type="image/png">
        <link rel="stylesheet" href="/inc/fontawesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="/inc/foundation/css/normalize.css">
        <link rel="stylesheet" href="/inc/foundation/css/foundation.css">
        <link rel="stylesheet" href="/inc/timepicker/jquery.timepicker.css">
        <link rel="stylesheet" href="/css/drk-theme/jquery-ui-1.10.4.custom.min.css">
        <link rel="stylesheet" href="/css/global.css">
        
        <title>Login | ShowSeeker</title>
    </head>
    <body>
		
		<?php 
			
			if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
			    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			    header('HTTP/1.1 301 Moved Permanently');
			    header('Location: ' . $redirect);
			    exit();
			}			
		?>
		
        <br/>
        <br/>
        <form id="frm-login" action="" method="post">
        <div class="row">
    
            <div class="small-6 medium-5 large-4 small-centered columns">
                <div class="panel radius">
                    <center><img src="https://showseeker.s3.amazonaws.com/public-site/assets/logo/showseeker_login.png"></center>
                    <br/>
                    <br/>
                    <div id="login-error" style="display:none;">
                        <div class="row">
                            <div class="small-12">
                                <div class="row">
                                    <div class="small-4 columns">
                                        <label for="ratecard-market" class="right inline"></label>
                                    </div>
                                    <div class="row collapse">
                                        <div class="small-7 columns"  style="background: #fad0d0;  border: 1px solid #f6abab; padding: 10px 10px 7px 19px;">
                                            Invalid username or password
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
                                    <label for="email" class="right inline">Email Address:</label>
                                </div>
                                <div class="row collapse">
                                    <div class="small-7 columns">
                                        <input type="email" name="email" id="email" required="true">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <div class="row">
                        <div class="small-12">
                            <div class="row">
                                <div class="small-4 columns">
                                    <label for="password" class="right inline">Password:</label>
                                </div>
                                <div class="row collapse">
                                    <div class="small-7 columns">
                                        <input type="password" name="password" id="password" required="true">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        
                    <div class="row">
                        <div class="small-12">
                            <div class="row">
                                <div class="small-11 columns">
                                    <label class="right inline"><a href="https://plus.showseeker.com/reset.php?app=plus">Forgot Password</a></label>
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
                                    <div class="small-7 columns">
                                        <textarea name="" id="" cols="30" rows="10" style="height:150px;">
                                        <?php include_once('inc/terms.php'); ?>
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
                                <div class="row collapse">
                                    <div class="small-8 columns">
                                        <button id="ssLoginBtn" type="submit" class="button tiny green" style="padding:8px;">I Agree &amp; Login</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row" style="color: rgb(0, 0, 0); font-size: 11px;">
                    <center>GOPlus v1.5.42</center>
                </div>              
                
            </div>
        
        </div>
        </form>

        <!-- Latest compiled and minified JavaScript -->
        <script src='/js/jquery-1.7.2.min.js'></script>
        <script src='/js/jquery.event.drag-2.0.min.js'></script>
        <script src='/js/jquery.event.drop-2.0.min.js'></script>
        <script src='/inc/foundation/js/foundation.min.js'></script>
        <script src='js/mixPanel.js'></script>  
        <script src='js/log.events.js'></script>
        <script src='js/authenticate_dev.js?v=123'></script>
        <script src='js/server.js?v=1'></script>        
        
        <script>
            $(document).foundation();
            //verifyServer();
        </script>
    </body>
</html>