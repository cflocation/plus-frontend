<?php
	header("HTTP/1.1 301 Moved Permanently");
	header("location: ../reset.php?app=snapshot");
	exit;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="shortcut icon" href="https://showseeker.s3.amazonaws.com/public-site/assets/favicon.ico" type="image/x-icon" /> 
        
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
        
        <title>ShowSeeker Plus - Login</title>
    </head>
    
    <body>  
        
        <br/>
        <br/>
        
        <form  action="" method="post">
            
            <div class="large-4 small-centered columns">
                
                <div class="panel radius">
                    
                    <center><img src="https://showseeker.s3.amazonaws.com/public-site/assets/logo/showseeker_login.png"></center>
                    <br/>
                    <br/>
                    
                    
                    <div id="success-message" style="display:none;">
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
                        </div>
                    </div>              
                    
                    <div id="error-message" style="display:none;">
                        <div class="row">
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
                                    <label for="ratecard-market" class="right inline"></label>
                                </div>
                                <div class="row collapse">
                                    <div class="small-8 columns">
                                        <button type="button" class="button tiny green" style="padding:8px;" onclick="javascript:forgotPassword();">Send Password</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <br/>
                    <br/>
                    
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
        <!-- Latest compiled and minified JavaScript -->
        <script src='/js/jquery-1.7.2.min.js'></script>
        <script src='/js/jquery.event.drag-2.0.min.js'></script>
        <script src='/js/jquery.event.drop-2.0.min.js'></script>
        <script src='/inc/foundation/js/foundation.min.js'></script>
        <script src='js/authenticate.js'></script>

        <script>
        $(document).foundation();
        </script>
    </body>    
</html>