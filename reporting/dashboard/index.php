<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);
?>

<head>
	<title>ShowSeeker - System Dashboard</title>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
	<link rel="stylesheet" href="//cdn.jsdelivr.net/foundation/5.5.0/css/foundation.css">
	<link rel="stylesheet" href="//cdn.jsdelivr.net/foundation/5.5.0/css/foundation.min.css">
	<script src="//cdn.jsdelivr.net/foundation/5.5.0/js/vendor/modernizr.js"></script>
</head>

<body>
<?php include ('menu.php');?>
<?php include ('scv.php');?>
<br />
<div class="row">
		  <div class="small-4 columns">
			  SYSTEM INFO<hr>
			  <a href="system_check.php"><h5 class="subheader">Server Checks</h5> (Up & Running)</a><hr>
			  <a href="system_stats.php"><h5 class="subheader">System Stats</h5> (Counts of searches, PW, users, proposals)</a><hr>
			  <a href="../plus" target="_blank"><h5 class="subheader">Search Stats</h5> (Which shows & actors are popular?)</a><hr>
		  </div>
  
		  <div class="small-4 columns">
			  SYSTEM CHECKS & LOGS<hr>
			  <a href="grids_mailer_check.php">EZ-Grids System Mailer Check</a><hr>
			  <a href="tutorials.php">Tutorials Log</a><hr>
			  <a href="searchSnooper.php">Search Snooper</a><hr>
		  </div>
  
		  <div class="small-4  columns">
			  SUPPORT<hr>
			  <a href="support/index.php">Support Tickets</a>
		  </div>
</div>


<script src="//cdn.jsdelivr.net/foundation/5.5.0/js/foundation.min.js"></script>
<script>
	$(document).foundation();
</script>