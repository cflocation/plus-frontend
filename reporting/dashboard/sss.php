<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);

	$con = mysqli_connect("db4.showseeker.net","vastdb","jK6YK71tJ","ShowSeeker");
	$con1 = mysqli_connect("db4.showseeker.net","vastdb","jK6YK71tJ","logs");

	$w1 = $_GET['w1'] ;
	$w2 = $_GET['w2'] ;
	$y = $_GET['y'] ;



	$week_start = new DateTime();
	$week_start->setISODate($y,$w1);

	$week_end = new DateTime();
	$week_end->setISODate($y,$w2);

	$lastWeek = $week_start->format('Y-m-d');
	$lastWeek2 = $week_end->format('Y-m-d');




//PROPOSAL COUNTS 
	//WEEKLY
	$pWResult = "SELECT count(*) as proposalWeekly FROM proposals WHERE createdat BETWEEN '$lastWeek' and '$lastWeek2'"; 
	$pWeekly = mysqli_query($con, $pWResult);
	$pW = mysqli_fetch_array($pWeekly);

	$proposalWeeklyCount =  $pW['proposalWeekly'] ;  

	$logger1 = "INSERT INTO historicalLogs (title, count, week, year) VALUES ('proposals', '$proposalWeeklyCount', '$w1', '$y')" ; 
	$dashboard_log = mysqli_query($con1,$logger1);


//SEARCH COUNTS 
	//WEEKLY
	$sWResult = "SELECT count(*) as searchWeekly FROM eventlogs WHERE eventslogid = '5' and createdat BETWEEN '$lastWeek' and '$lastWeek2'"; 
	$sWeekly = mysqli_query($con1, $sWResult);
	$sW = mysqli_fetch_array($sWeekly);

	$searchWeeklyCount = $sW['searchWeekly'] ; 

	$logger2 = "INSERT INTO historicalLogs (title, count, week, year) VALUES ('search', '$searchWeeklyCount', '$w1', '$y')" ; 
	$dashboard_log = mysqli_query($con1,$logger2);


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
<br />
<body>
<div class="row">
  <div class="small-10 columns">
  <table>
		  <tr><td class="text-center">Proposals</td><td class="text-center"><?php echo $proposalWeeklyCount;?></td></tr>
		  <tr><td class="text-center">Searches</td><td class="text-center"><?php echo $searchWeeklyCount;?></td></tr>		  
  </table>
  </div>
</div>

<div class="row">
  <div class="small-10 columns"><h2><center><a href="sss.php?w1=<?php echo $w1+1;?>&w2=<?php echo $w2+1;?>&y=<?php echo $y;?>">Click Here to Run the Next Week</a></center></h2></div>
</div>

<script src="//cdn.jsdelivr.net/foundation/5.5.0/js/foundation.min.js"></script>
<script>
	$(document).foundation();
</script>