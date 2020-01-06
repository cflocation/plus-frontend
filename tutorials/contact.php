<?php	
	session_start();
	if(!isset($_SESSION['userid'])){
		//header( 'Location: /login' ) ;
	}
	$uuid = uniqid();
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- link rel="shortcut icon" href="/icon.gif" type="image/x-icon" / -->
	<link href="https://showseeker.s3.amazonaws.com/public-site/assets/favicon.ico" name="favicon" rel="shortcut icon" type="image/png">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="cache-control" content="no-cache"> <!-- tells browser not to cache -->
    <meta http-equiv="expires" content="0"> <!-- says that the cache expires 'now' -->
    <meta http-equiv="pragma" content="no-cache"> <!-- says not to use cached stuff, if there is any -->
    <link rel="stylesheet" href="../inc/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../inc/foundation/css/normalize.css">
    <link rel="stylesheet" href="../inc/foundation/css/foundation.css">
    <link rel="stylesheet" href="../css/drk-theme/jquery-ui-1.10.4.custom.min.css">
    <link rel="stylesheet" href="../css/global.css">
    <title>Tutorials - Contact Us | ShowSeeker</title>
</head>
<body>

<nav id="main-nav" class="top-bar" data-topbar>
  <ul class="title-area">
    <li class="name" style="width: 290px;"><img src="../images/logosm_w.png" style="padding-left:5px;"></li>
  </ul>

  <section class="top-bar-section">
    <ul class="left">
      <!--<li><a style="background-color:black;" href="javascript:void(0);"><i class="fa fa-arrow-circle-left fa-lg"></i></a></li>-->
      <li id="menu-1"><a href="index.php">Tutorials Home</a></li>
      <li id="menu-2"><a href="http://www.showseeker.com/tutorials/guides/ShowSeeker_Plus_Basic_User_Guide.pdf" target="_blank">Download User Guide</a></li>
      <li id="menu-3"><a href="contact.php">Contact Us</a></li>
      <li id="menu-4"><a href="#">Questions? Call us at 866-980-8278</a></li>      
    </ul>
  </section>
</nav>
<br>


<section class="main">
    <div id="mainwrapper">
      <br style="clear:both;">
      <div id="ss-menu" class="container-content" style="overflow:hidden;">

  <div class="row">
    <div class="large-3 columns">&nbsp;</div>
        <div class="large-6 columns">
          <p align="center"><strong>ShowSeeker - Contact Us</strong></p>
          <p align="center">For Technical Support (difficulty logging in or other technical issues): <br> <a href="mailto:help@showseeker.com">help@showseeker.com</a></p>
          <p align="center">For assistance in using ShowSeeker, refer to the <a href="http://www.showseeker.com/tutorials/guides/ShowSeeker_Plus_Basic_User_Guide.pdf" target="_blank">User Guides</a> or FAQ’s. <br> If your answer is not found: <br><a href="mailto:support@showseeker.com">support@showseeker.com</a> </p>
          <p align="center">For Suggestions on how we may improve our product: <br><a href="mailto:suggestions@showseeker.com">suggestions@showseeker.com</a></p>
          <p align="center">To submit Success Stories: <br><a href="mailto:wins@showseeker.com">wins@showseeker.com</a></p> 
          <p align="center">If you have specific questions not covered above, call us at: 866-980-8278</p>
          <p align="center">Software developed by Visual Advertising Sales Technology. <br> U.S. Patent No. 7,742,946 <br> N.Z. Patent No. 537510 <br> Copyright © VAST 2003 - 2014</p>
        </div>
    <div class="large-3 columns">&nbsp;</div>

    </div>
  </div>
  </section>

  <div id="dialog-window" style="display:none;"></div>
  <!-- Latest compiled and minified JavaScript -->
  <script src='../js/jquery-1.7.2.min.js'></script>
  <script src='../inc/timepicker/jquery.ui.timepicker.js'></script>
  <script src='../inc/foundation/js/foundation.min.js'></script>
  <script src='../inc/timepicker/jquery.ui.timepicker.js'></script>
  <script src='../js/date.js'></script>
  <script src='../inc/foundation/js/vendor/modernizr.js'></script>
  <script>
    sidebarClose();
    $(document).foundation();


function sidebarClose(){
    $('.sidebar').css('left', -300);
    $('.main').css('margin-left', 0);
    $('#mainwrapper').css('left', 0);
}
  </script>
	</body>
</html>