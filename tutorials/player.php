<?php
ini_set("display_startup_errors",1);
ini_set("display_errors",1);
error_reporting(E_ALL);

if (isset($_GET['id']))
  {
	$x = $_GET['id'];
  } 


switch ($x) {
    case "1":
		$title = "Module #1";
        $movie = "https://player.vimeo.com/video/155542022?autoplay=1" ;
		$details = "Video Duration: 20 minutes" ; 
        break;
    case "2":
		$title = "Module #2";
        $movie = "https://player.vimeo.com/video/155884594?autoplay=1" ;
 		$details = "Video Duration: 22 minutes" ; 
       break;
    case "3":
		$title = "Module #3";
        $movie = "https://player.vimeo.com/video/156422728?autoplay=1" ;
  		$details = "Video Duration: 24 minutes" ; 
      break;
    case "4":
		$title = "E-z Grids";
        $movie = "https://player.vimeo.com/video/156443921?autoplay=1" ;
  		$details = "Video Duration: 6 minutes" ; 
      break;
    case "5":
		$title = "Projected Calendar";
        $movie = "https://player.vimeo.com/video/156447608?autoplay=1" ;
  		$details = "Video Duration: 5 minutes" ; 
      break;
    case "6":
		$title = "Rotators and Avails";
        $movie = "https://player.vimeo.com/video/156454613?autoplay=1" ;
   		$details = "Video Duration: 8 minutes" ; 
     break;
    case "7":
		$title = "E-z Search";
        $movie = "https://player.vimeo.com/video/156461527?autoplay=1" ;
 		$details = "Video Duration: 6 minutes" ; 
       break;
    case "8":
		$title = "Help & Tutorials";
        $movie = "https://player.vimeo.com/video/156595481?autoplay=1&hd=1" ;
  		$details = "Video Duration: 2 minutes" ; 
      break;
    case "9":
		$title = "Copy, Rename, Merge & Share";
        $movie = "https://player.vimeo.com/video/156621168?autoplay=1" ;
  		$details = "Video Duration: 3 minutes" ; 
      break;

	default:
		$title = "Module #1";
        $movie = "https://player.vimeo.com/video/155542022?autoplay=1" ;
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
		<style>
			a:hover {color:#303030;}
			font-family {Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif; }
		</style>
</head>
<div class="row">
	<div class='small-3 columns'><img style='width:250px;' src='images/ss_logo.png'></div>
</div>
<center>
<h3>ShowSeeker Training - <?php echo $title;?><br> <?php echo $details;?></h3>
<div class="row">
	<div class='small-12 columns'><iframe src="<?php echo $movie;?>" width="850" height="500" frameborder="1" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>
</div>

<hr>

<div class="row">
	<div class='small-4 columns'> &nbsp; </div>
	<div class='small-6 columns'>
		<ul class="inline-list">
        <li><a href="player.php?id=1">Training Module #1</a></li>
        <li><a href="player.php?id=2">Training Module #2</a></li>
        <li><a href="player.php?id=3">Training Module #3</a></li>
		</ul>
	</div>
	<div class='small-2 columns'> &nbsp; </div>
</div>
<div class="row">
	<div class='small-2 columns'> &nbsp; </div>
	<div class='small-8 columns'>
		<ul class="inline-list">
				<li><a href="player.php?id=7">E-z Search</a></li>
				<li><a href="player.php?id=6">Rotators and Avails</a></li>
				<li><a href="player.php?id=4">E-z Grids</a></li>
				<li><a href="player.php?id=5">Projected Calendar</a></li>
				<li><a href="player.php?id=8">Help & Tutorials</a></li>
				<li><a href="player.php?id=9">Copy, Rename, Merge & Share</a></li>
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
</script>