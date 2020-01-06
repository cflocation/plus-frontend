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
    <link rel="shortcut icon" href="/icon.gif" type="image/x-icon" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="cache-control" content="no-cache"> <!-- tells browser not to cache -->
    <meta http-equiv="expires" content="0"> <!-- says that the cache expires 'now' -->
    <meta http-equiv="pragma" content="no-cache"> <!-- says not to use cached stuff, if there is any -->
    <link rel="stylesheet" href="../inc/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../inc/foundation/css/normalize.css">
    <link rel="stylesheet" href="../inc/foundation/css/foundation.css">
    <link rel="stylesheet" href="../css/drk-theme/jquery-ui-1.10.4.custom.min.css">
    <link rel="stylesheet" href="../css/global.css">
    <!-- title>ShowSeeker Plus - Video Tutorials</title -->
    <title>Video Tutorials | ShowSeeker</title>
<style>
	a:hover {color:#303030;}
	font-family {Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif; }
</style>
</head>
<body>
<nav id="main-nav" class="top-bar" data-topbar>
  <ul class="title-area">
    <li class="name" style="width: 290px;"><img src="/images/logosm_w.png" style="padding-left:5px;"></li>
  </ul>
  <section class="top-bar-section">
    <ul class="left">
      <li id="menu-1"><a href="index.php">Tutorials Home</a></li>
      <li id="menu-2"><a href="http://plus.showseeker.com/guides/download.php?filename=ShowSeeker_Rate-Card-Training_071114.pdf" target="_blank">Download User Guide</a></li>
      <li id="menu-3"><a href="contact.php">Contact Us</a></li>
      <li id="menu-4"><a href="#">Questions? Call us at 866-980-8278</a></li>
    </ul>
  </section>
</nav>

    <div id="mainwrapper">
      <div id="ss-menu" class="container-content" style="overflow:hidden;">
        <?php 


			echo "<br><div class='row'>";
			echo "<div class='small-3 columns'><img style='width:250px;' src='images/ss_logo.png'></div>";
			echo "<div class='small-9 columns'><h3>Welcome to the ShowSeeker Plus - Rate Card Tutorial <br> If you have been assigned as the Rate Card Manager for your Market or Markets, this tutorial will guide you in how to edit current Rate Cards and create new Rate Cards.</h3></div>";
			echo "</div><br>";
		?>

<div class="small-6 large-6 columns">
<u><h3>Rate Card Tutorial</h3></u>

<iframe src="//player.vimeo.com/video/100519260" width="864" height="486" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>




	  <h4>Total Time - 20 minutes</h4>
</div>
  

  
  
  </div>
  <div id="dialog-window" style="display:none;"></div>
  <!-- Latest compiled and minified JavaScript -->
  <script src='../js/jquery-1.7.2.min.js'></script>
  <script src='../inc/timepicker/jquery.ui.timepicker.js'></script>
  <script src='../inc/foundation/js/foundation.min.js'></script>
  <script src='../inc/timepicker/jquery.ui.timepicker.js'></script>
  <script src='../js/date.js'></script>
  <script src='../inc/foundation/js/vendor/modernizr.js'></script>
  <script>
    $(document).foundation();
  </script>
	</body>
</html>
