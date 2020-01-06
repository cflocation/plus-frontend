<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);
	session_start();


	$uid = $_GET['userid'] ;
	$con=mysqli_connect("db4.showseeker.net","vastdbuser","jK6YK71tJ","Support");
	$con1 = mysqli_connect("db4.showseeker.net","vastdbuser","jK6YK71tJ","logs");
	$dtime = date('Y-m-d H:i:s');
	$sql = "INSERT INTO eventlogs (userid,eventslogid,request,result,createdat, updatedat) VALUES ('{$uid}', '56','Main Page','".$_SERVER['REMOTE_ADDR']."','{$dtime}','{$dtime}')";
	mysqli_query($con1, $sql);

	$_SESSION['userid'] = $uid;

	$completeCheck = "SELECT * from TrainingUsers where userid = '$uid'" ;
	$completed = mysqli_query($con, $completeCheck );
	$completeCount=mysqli_num_rows($completed);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>ShowSeeker Plus - Video Tutorials</title>
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
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
	<link rel="stylesheet" href="http://www.showseeker.com/inc/foundation/css/normalize.css">
	<link rel="stylesheet" href="http://www.showseeker.com/inc/foundation/css/foundation.css">
	<script src="http://www.showseeker.com/inc/foundation/js/vendor/modernizr.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
		<style>
			a:hover {color:#303030;}
			font-family {Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif; }


		</style>
</head>
<div class="row">
	<div class='small-3 columns'><img style='width:250px;' src='images/ss_logo.png'></div>
</div>

<center>
<h3>Welcome to the ShowSeeker Plus - Video Tutorials </h3>

<?php
if (($_GET['m']=='1') AND ($completeCount == 0)){
?>
<div class="row">
        <div class="small-12 columns "><br /><table class="text-center"><tr><td><h3 class="subheader  panel callout radius">How to Receive your ShowSeeker Training Certificate <br /> and be entered to Win a Prize!</h3></td></tr><tr><td><img src="images/sample_certificate.png"><br /><h4>Simply view the 3-part video tutorials and answer a few questions after each module.  <br />The entire process takes just over an hour and you will be ready to start enjoying ShowSeeker Plus! <br />Also, your name will be entered into our monthly drawing for a $50 Amazon Gift Card.</h4></td></tr></table></div>
</div>
<?php
}?>



<div class="row">
        <div class="small-4 columns"><a href="play.php?id=1&userid=<?php echo $uid;?>" target="_blank"><img src="images/Module1.png"></a><br><a href="#" data-reveal-id="myModal1"><i class="fa fa-info-circle"></i> - Click Here for subjects covered in the video</a>
<?php
if (($_GET['m']=='1') AND ($completeCount == 0)){
?>
<br /><br /><a href="../certification/loader.php?s=A1" class="button small success radius round">Click here for Module 1 Quiz</a>		
<?php
}?>
		</div>
        <div class="small-4 columns"><a href="play.php?id=2&userid=<?php echo $uid;?>" target="_blank"><img src="images/Module2.png"></a><br><a href="#" data-reveal-id="myModal2"><i class="fa fa-info-circle"></i> - Click Here for subjects covered in the video</a>
<?php
if (($_GET['m']=='1') AND ($completeCount == 0)){
?>
<br /><br /><a href="../certification/loader.php" class="button small success radius round">Click here for Module 2 Quiz</a>		
<?php
}?>		
		</div>
        <div class="small-4 columns"><a href="play.php?id=3&userid=<?php echo $uid;?>" target="_blank"><img src="images/Module3.png"></a><br><a href="#" data-reveal-id="myModal3"><i class="fa fa-info-circle"></i> - Click Here for subjects covered in the video</a>
<?php
if (($_GET['m']=='1') AND ($completeCount == 0)){
?>
<br /><br /><a href="../certification/loader.php" class="button small success radius round">Click here for Module 3 Quiz</a>		
<?php
}?>		
		</div>
</div>

<?php
if (($_GET['m']=='1') AND ($completeCount == 0)){
?>
<div class="row">
        <div class="small-2 columns">&nbsp;</div>
        <div class="small-8 columns "><h5 class="subheader panel callout radius">* You may watch the tutorials without taking the quizzes but you won't receive a Certificate of Completion or be entered into our contest.</h5></div>
        <div class="small-2 columns">&nbsp;</div>
</div>
<?php
}?>

<hr />
<h3>Mini Modules</h3><h4>You may view a refresher on any of the sections from the main tutorials here:</h4>
<div class="row">

	<div class="small-2 columns">
			<a href="play.php?id=7&userid=<?php echo $uid;?>" target="_blank"><img src="images/tutorials_ezsearch.png" border="1"><br><a href="#" data-reveal-id="myModal7"><i class="fa fa-info-circle"></i></a> - Title & Keyword Search</a>
	</div>
	<div class="small-2 columns">
			<a href="play.php?id=6&userid=<?php echo $uid;?>" target="_blank"><img src="images/tutorials_rotators.png"><br><a href="#" data-reveal-id="myModal6"><i class="fa fa-info-circle"></i></a> - Rotators & Avails</a>
	</div>
	<div class="small-2 columns">
			<a href="play.php?id=4&userid=<?php echo $uid;?>" target="_blank"><img src="images/tutorials_ezgrids.png"><br><a href="#" data-reveal-id="myModal4"><i class="fa fa-info-circle"></i></a> - E-z Grids</a>
	</div>
	<div class="small-2 columns">
			<a href="play.php?id=5&userid=<?php echo $uid;?>" target="_blank"><img src="images/tutorials_projected.png"><br><a href="#" data-reveal-id="myModal5"><i class="fa fa-info-circle"></i></a> - Projected Calendar</a>
	</div>
	<div class="small-2 columns">
			<a href="play.php?id=8&userid=<?php echo $uid;?>" target="_blank"><img src="images/tutorials_help.png"><br><a href="#" data-reveal-id="myModal8"><i class="fa fa-info-circle"></i></a> - Help & Tutorials</a>
	</div>
	<div class="small-2 columns">
			<a href="play.php?id=9&userid=<?php echo $uid;?>" target="_blank"><img src="images/tutorials_share.png"><br><a href="#" data-reveal-id="myModal9"><i class="fa fa-info-circle"></i></a> - Copy, Rename, Merge & Share</a>
	</div>
</div>
</center>
<br>
<center>
<div class="row">
		<div class="small-12 large-centered columns">Having troubles viewing the tutorials ? <br> Try our alternate location <a href="https://showseeker.s3.amazonaws.com/tutorials/2016/index.htm">HERE</a></div>
</div>
</center>

<div id="myModal1" class="reveal-modal small" data-reveal aria-labelledby="modalTitle1" aria-hidden="true" role="dialog">
  <h2 id="modalTitle1"><center>Training Module #1 <br>Includes top section of the left Settings Bar:</center></h2>
  <h4><center>Video Duration: 20 minutes</center></h4>
  <ul class="disc">
	  <li>Choosing Zone & Networks</li>
	  <li>Ctrl & Shift keys</li>
	  <li>Calendar options</li>
	  <li>Date, Time & Day settings</li>
	  <li>Reset</li>
	  <li>Title/Keyword/Actor Search</li>
	  <li>Creating a Proposal</li>
	  <li>Duplicate Zones</li>
	  <li>Add Rates</li>
	  <li>Download options</li>
	  <li>Saved Searches</li>
  <ul>
  <a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>

<div id="myModal2" class="reveal-modal small" data-reveal aria-labelledby="modalTitle2" aria-hidden="true" role="dialog">
  <h2 id="modalTitle2"><center>Training Module #2 <br>Includes bottom section of the left Settings Bar:</center></h2>
  <h4><center>Video Duration: 22 minutes</center></h4>
  <ul class="disc">
	  <li>Sports</li>
	  <li>Fixed & Grouped options</li>
	  <li>Premieres & Finales</li>
	  <li>Genres</li>
	  <li>Movies/Live/New filters</li>
	  <li>Marathons</li>
	  <li>Nets by Demo</li>
	  <li>Rotators</li>
	  <li>Avails</li>
	  <li>Standard/Broadcast Calendar toggle</li>
	  <li>Hide Columns</li>
	  <li>PDF Instant Print</li>
  <ul>
  <a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>

<div id="myModal3" class="reveal-modal small" data-reveal aria-labelledby="modalTitle3" aria-hidden="true" role="dialog">
  <h2 id="modalTitle3"><center>Training Module #3 <br>Includes E-z Grids and Top Menu Bar:</center></h2>
  <h4><center>Video Duration: 24 minutes</center></h4>
 <ul class="disc">
	  <li>Sports Packages</li>
	  <li>Projected Calendar</li>
	  <li>Help & Tutorials</li>
	  <li>Freeze Columns & Auto Split Lines</li>
	  <li>Message Center (shared proposals and password reset)</li>
	  <li>Copy/Rename/Merge & Share options</li>
	  <li>Download tab options</li>
  <ul>
  <a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>


<div id="myModal4" class="reveal-modal small" data-reveal aria-labelledby="modalTitle4" aria-hidden="true" role="dialog">
  <h2 id="modalTitle4"><center>E-z Grids</center></h2>
  <h4><center>Video Duration: 6 minutes</center></h4>
  <p>E-z Grids - Covers how to access any network's 8-week programming Grid/Add shows from Grids to Proposals and how to Print current view or Print all weeks.</p>
  <a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>


<div id="myModal5" class="reveal-modal small" data-reveal aria-labelledby="modalTitle5" aria-hidden="true" role="dialog">
  <h2 id="modalTitle5"><center>Projected Calendar</center></h2>
  <h4><center>Video Duration: 5 minutes</center></h4>
  <p>Projected Calendar - Covers how to access programming that goes beyond the ShowSeeker 8-week window/Add shows from the Projected Calendar to proposals and how to search by Title or Network.</p>
  <a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>


<div id="myModal6" class="reveal-modal small" data-reveal aria-labelledby="modalTitle6" aria-hidden="true" role="dialog">
  <h2 id="modalTitle6"><center>Rotators & Avails</center></h2>
  <h4><center>Video Duration: 8 minutes</center></h4>
  <p>Rotators & Avails - Covers how to create a Rotator proposal or add Rotators to an existing schedule/Editing Rotators/Creating Avails.</p>
  <a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>

<div id="myModal7" class="reveal-modal small" data-reveal aria-labelledby="modalTitle7" aria-hidden="true" role="dialog">
  <h2 id="modalTitle7"><center>Title & Keyword Search</center></h2>
  <h4><center>Video Duration: 6 minutes</center></h4>
  <p>Title & Keyword Search - Covers how to search by Title, Keyword or Actors/ How to Create a Proposal.</p>
  <a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>

<div id="myModal8" class="reveal-modal small" data-reveal aria-labelledby="modalTitle8" aria-hidden="true" role="dialog">
  <h2 id="modalTitle8"><center>Help & Tutorials</center></h2>
  <h4><center>Video Duration: 2 minutes</center></h4>
  <p>Help & Tutorials - Shows how to access Video Tutorials and Basic User Guides/FAQs/past 3 issues of our Newsletter/Contact Us/Browser Info/Reload.</p>
  <a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>

<div id="myModal9" class="reveal-modal small" data-reveal aria-labelledby="modalTitle9" aria-hidden="true" role="dialog">
  <h2 id="modalTitle9"><center>Copy/Rename/Merge/Share</center></h2>
  <h4><center>Video Duration: 3 minutes</center></h4>
  <p>Copy/Rename/Merge/Share - Demonstrates how to Copy or Rename a Proposal, Merge 2 Proposals together or Share Proposals with a person or group.</p>
  <a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>




<script src="http://www.showseeker.com/inc/foundation/js/foundation.min.js"></script>
<script>
	$(document).foundation();
</script>