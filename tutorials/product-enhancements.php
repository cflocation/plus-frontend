<?php	
session_start();
$userid =  $_GET['userid'] ; 

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
      <li id="menu-2"><a href="http://www.showseeker.com/tutorials/guides/ShowSeeker_Plus_Basic_User_Guide.pdf" target="_blank">Download User Guide</a></li>
      <li id="menu-3"><a href="contact.php">Contact Us</a></li>
      <li id="menu-4"><a href="#">Questions? Call us at 866-980-8278</a></li>
    </ul>
  </section>
</nav>

    <div id="mainwrapper">
      <div id="ss-menu" class="container-content" style="overflow:hidden;">
        <?php 

			echo "<h2><u>Learning ShowSeeker Plus</u><br>Product Enhancements</h2>";
			echo "<iframe src='//player.vimeo.com/video/153387913?title=0&amp;byline=0&amp;portrait=0' width='864' height='486' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
			echo "<h4 style='width:800px;'>ShowSeeker Plus - Product Enhancements<br>Running Time: 18 minutes.</h4>"; 

			$con1 = mysqli_connect("db4.showseeker.net","vastdbuser","jK6YK71tJ","logs");
			$dtime = date('Y-m-d H:i:s');
			
			$sql = "INSERT INTO eventlogs (userid,eventslogid,request,result,createdat, updatedat) VALUES ('{$_SESSION['user']}', '56','Product Enhancements','".$_SERVER['REMOTE_ADDR']."','{$dtime}','{$dtime}')";
			mysqli_query($con1, $sql);

		?>
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
