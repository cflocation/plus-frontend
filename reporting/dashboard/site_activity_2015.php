<?php
	session_start();
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);

?>

<head>
	<title>ShowSeeker - Site Activity</title>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
	<link rel="stylesheet" href="//cdn.jsdelivr.net/foundation/5.5.0/css/foundation.css">
	<link rel="stylesheet" href="//cdn.jsdelivr.net/foundation/5.5.0/css/foundation.min.css">
	<script src="//cdn.jsdelivr.net/foundation/5.5.0/js/vendor/modernizr.js"></script>
</head>


<div class="row">
		  <div class="small-12 columns "><iframe src="search_2015.php" width="810px" height="460px" scrolling="no"></iframe></div>
</div>
<div class="row">
		  <div class="small-12 columns "><iframe src="proposals_2015.php" width="810px" height="460px" scrolling="no"></iframe></div>
</div>

<script src="http://www.showseeker.com/inc/foundation/js/foundation.min.js"></script>

<script>
	$(document).foundation();
</script>