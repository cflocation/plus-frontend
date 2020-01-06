<?php
ini_set("display_startup_errors",1);
ini_set("display_errors",1);
error_reporting(E_ALL);

$uid = $_GET['userid'] ;
if (isset($_GET['id']))
  {
	$x = $_GET['id'];
  } 


switch ($x) {
    case "1":
		$title = "Module #1";
        $movie = "//showseeker.s3.amazonaws.com/tutorials/2016/ShowSeeker_Tutorials_Module-1.mp4" ;
		$details = "Video Duration: 20 minutes" ; 
        break;
    case "2":
		$title = "Module #2";
        $movie = "//showseeker.s3.amazonaws.com/tutorials/2016/ShowSeeker_Tutorials_Module-2.mp4" ;
 		$details = "Video Duration: 22 minutes" ; 
       break;
    case "3":
		$title = "Module #3";
        $movie = "//showseeker.s3.amazonaws.com/tutorials/2016/ShowSeeker_Tutorials_Module-3.mp4" ;
  		$details = "Video Duration: 24 minutes" ; 
      break;
    case "4":
		$title = "E-z Grids";
        $movie = "//showseeker.s3.amazonaws.com/tutorials/2016/ShowSeeker_Tutorials_Just_E-z_Grids.mp4" ;
  		$details = "Video Duration: 6 minutes" ; 
      break;
    case "5":
		$title = "Projected Calendar";
        $movie = "//showseeker.s3.amazonaws.com/tutorials/2016/ShowSeeker_Tutorials_Just_Projected_Calendar.mp4" ;
  		$details = "Video Duration: 5 minutes" ; 
      break;
    case "6":
		$title = "Rotators and Avails";
        $movie = "//showseeker.s3.amazonaws.com/tutorials/2016/ShowSeeker_Tutorials_Just_Rotators-and-Avails.mp4" ;
   		$details = "Video Duration: 8 minutes" ; 
     break;
    case "7":
		$title = "Title & Keyword Search";
        $movie = "//showseeker.s3.amazonaws.com/tutorials/2016/ShowSeeker_Tutorials_Just_E-z_Search.mp4" ;
 		$details = "Video Duration: 6 minutes" ; 
       break;
    case "8":
		$title = "Help & Tutorials";
        $movie = "//showseeker.s3.amazonaws.com/tutorials/2016/ShowSeeker_Tutorials_Help-and-Tutorials.mp4" ;
  		$details = "Video Duration: 2 minutes" ; 
      break;
    case "9":
		$title = "Copy, Rename, Merge & Share";
        $movie = "//showseeker.s3.amazonaws.com/tutorials/2016/ShowSeeker_Tutorials_Copy-Rename-Share.mp4" ;
  		$details = "Video Duration: 3 minutes" ; 
      break;

	default:
		$title = "Module #1";
        $movie = "//showseeker.s3.amazonaws.com/tutorials/2016/ShowSeeker_Tutorials_Module-1.mp4" ;
		$details = "Video Duration: 20 minutes" ; 
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- title>ShowSeeker Plus - Video Tutorials</title -->
    <title>Video Tutorials | ShowSeeker</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- link rel="shortcut icon" href="/icon.gif" type="image/x-icon" / -->
	<link href="//showseeker.s3.amazonaws.com/public-site/assets/favicon.ico" name="favicon" rel="shortcut icon" type="image/png">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="cache-control" content="no-cache"> <!-- tells browser not to cache -->
    <meta http-equiv="expires" content="0"> <!-- says that the cache expires 'now' -->
    <meta http-equiv="pragma" content="no-cache"> <!-- says not to use cached stuff, if there is any -->
    <link rel="stylesheet" href="../inc/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../inc/foundation/css/normalize.css">
    <link rel="stylesheet" href="../inc/foundation/css/foundation.css">
    <link rel="stylesheet" href="../css/drk-theme/jquery-ui-1.10.4.custom.min.css">
    <link rel="stylesheet" href="../css/global.css">
		<style>
			a:hover {color:#303030;}
			font-family {Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif; }
		</style>
</head>
<br />
<div class="row">
	<div class='small-3 columns'><img style='width:250px;' src='//showseeker.s3.amazonaws.com/public-site/assets/logo/showseeker_login.png'></div>
</div>
<center>
<h3>ShowSeeker Training - <?php echo $title;?><br> <?php echo $details;?></h3>
<div class="row">
	<div class='small-12 columns'>
		 <video width="850" height="500" controls><source src="<?php echo $movie;?>" type="video/mp4">Your browser does not support the video tag.</video>
	</div>
</div>
<hr>
<div class="row">
	<div class='small-4 columns'> &nbsp; </div>
	<div class='small-6 columns'>
		<ul class="inline-list">
        <li><a href="play.php?id=1&userid=<?php echo $uid;?>">Training Module #1</a></li>
        <li><a href="play.php?id=2&userid=<?php echo $uid;?>">Training Module #2</a></li>
        <li><a href="play.php?id=3&userid=<?php echo $uid;?>">Training Module #3</a></li>
		</ul>
	</div>
	<div class='small-2 columns'> &nbsp; </div>
</div>
<div class="row">
	<div class='small-2 columns'> &nbsp; </div>
	<div class='small-8 columns'>
		<ul class="inline-list">
				<li><a href="play.php?id=7&userid=<?php echo $uid;?>">Title & Keyword Search</a></li>
				<li><a href="play.php?id=6&userid=<?php echo $uid;?>">Rotators & Avails</a></li>
				<li><a href="play.php?id=4&userid=<?php echo $uid;?>">E-z Grids</a></li>
				<li><a href="play.php?id=5&userid=<?php echo $uid;?>">Projected Calendar</a></li>
				<li><a href="play.php?id=8&userid=<?php echo $uid;?>">Help & Tutorials</a></li>
				<li><a href="play.php?id=9&userid=<?php echo $uid;?>">Copy, Rename, Merge & Share</a></li>
		</ul>
	</div>
	<div class='small-2 columns'> &nbsp; </div>
</div>
</center>
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.1/js/vendor/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.1/js/foundation.min.js"></script>
<script>

$(document).ready(function(){
});

var apiUrl 		= "https://plusapi.showseeker.com/";
if (localStorage.getItem("userId") === null || localStorage.getItem("apiKey") === null) {
    window.location.href = "login.php?logout=true";
}

var userid = localStorage.getItem("userId");
var apiKey = localStorage.getItem("apiKey");

function logUserEvent(eventId,requestBody,responseBody,proposalId){
    $.ajax({
        type:'post',
        url: apiUrl+"user/log/",
        dataType:"json",
        headers:{"Api-Key":apiKey,"User":userid},
        processData: false,
        contentType: 'application/json',
        data: JSON.stringify({"userId":userid,"location":1,"event":eventId,"request":requestBody,"response":responseBody,"proposalId":proposalId}),
        success:function(resp){}
    });
};

logUserEvent(56,'<?php print $title;?>',1,0);
</script>