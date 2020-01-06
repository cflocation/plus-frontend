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
    <title>ShowSeeker Plus - Video Tutorials</title>
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
      <li id="menu-1"><a href="?userid=<?php print $userid?>">Tutorials Home</a></li>
      <li id="menu-2"><a href="javascript:downloadGuide('ShowSeeker_Plus_Basic_User_Guide.pdf')">Download User Guide</a></li>
      <li id="menu-3"><a href="contact.php">Contact Us</a></li>
      <li id="menu-4"><a href="#">Questions? Call us at 866-980-8278</a></li>
    </ul>
  </section>
</nav>

    <div id="mainwrapper">
      <div id="ss-menu" class="container-content" style="overflow:hidden;">
        <?php 

			echo "<h2><u>Learning ShowSeeker Plus</u><br>Training Refresher</h2>";
			//echo '<video width="850" height="500" controls><source src="https://showseeker.s3.amazonaws.com/tutorials/ShowSeeker_Plus_Tutorial_Refresher.mp4" type="video/mp4">Your browser does not support the video tag.</video>';
			echo '<video width="850" height="500" controls><source src="//showseeker.s3.amazonaws.com/tutorials/2016/ShowSeeker_Tutorials_Refresher_032316.mp4" type="video/mp4">Your browser does not support the video tag.</video>';



			echo "<h4 style='width:800px;'>ShowSeeker Plus - Training Refresher<br>Running Time: 26 minutes.</h4>"; 

			$con1 = mysqli_connect("db4.showseeker.net","vastdbuser","jK6YK71tJ","logs");
			$dtime = date('Y-m-d H:i:s');
			
			$sql = "INSERT INTO eventlogs (userid,eventslogid,request,result,createdat, updatedat) VALUES ('{$userid}', '56','Training Refresher','".$_SERVER['REMOTE_ADDR']."','{$dtime}','{$dtime}')";
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
    
    function downloadGuide(filename){
	    window.location.href = 'guides/fdownload.php?filename='+filename;
    }
    
  </script>
	</body>
</html>
