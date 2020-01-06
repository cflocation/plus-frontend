<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);

	$con = mysqli_connect("db4.showseeker.net","vastdb","jK6YK71tJ","ShowSeeker");
	$con1 = mysqli_connect("db4.showseeker.net","vastdb","jK6YK71tJ","logs");

//DATES - Today, Week, Month
	$today = date("Y-m-d");
	$lastWeek = date("Y-m-d", strtotime("last week monday"));
	$lastWeek2 = date("Y-m-d", strtotime("last week sunday"));

	$lastMonthRaw = date("Y-m-", strtotime("last month"));
	$thisMonthRaw = date("Y-m-", strtotime("this month"));

	$lastMonth = $lastMonthRaw ."01" ;
	$thisMonth = $thisMonthRaw ."01";

//USER COUNTS

	//TOTAL
	$result ="SELECT COUNT(*) as userCount FROM users"; 
	$users = mysqli_query($con, $result );
	$userCount = mysqli_fetch_array($users);

	//DAILY
	$uDResult = "SELECT count(*) as userDaily FROM eventlogs WHERE eventslogid = '58' and createdat BETWEEN '$today 00:00:01' AND '$today 23:59:59'"; 
	$uDaily = mysqli_query($con1, $uDResult);
	$uD = mysqli_fetch_array($uDaily);

	//WEEKLY
	$uWResult = "SELECT count(*) as userWeekly FROM eventlogs WHERE eventslogid = '58' and createdat BETWEEN '$lastWeek' and '$lastWeek2'"; 
	$uWeekly = mysqli_query($con1, $uWResult);
	$uW = mysqli_fetch_array($uWeekly);

	//MONTHLY
	$uMResult = "SELECT count(*) as userMonthly FROM eventlogs WHERE eventslogid = '58' and createdat BETWEEN '$lastMonth' and '$thisMonth'"; 
	$uMonthly = mysqli_query($con1, $uMResult);
	$uM = mysqli_fetch_array($uMonthly);

	$userDailyCount = $uD['userDaily'] ; 
	$userWeeklyCount = $uW['userWeekly'] ;  
	$userMonthlyCount = $uM['userMonthly'] ;  
	$userTotalCount = $userCount['userCount'] ;

//PROPOSAL COUNTS 
	//TOTAL
	$result1 ="SELECT COUNT(*) as proposalCount FROM proposals"; 
	$proposals = mysqli_query($con, $result1 );
	$proposalCount = mysqli_fetch_array($proposals);

	//DAILY
	$pDResult = "SELECT count(*) as proposalDaily FROM proposals WHERE createdat BETWEEN '$today 00:00:01' AND '$today 23:59:59'"; 
	$pDaily = mysqli_query($con, $pDResult);
	$pD = mysqli_fetch_array($pDaily);

	//WEEKLY
	$pWResult = "SELECT count(*) as proposalWeekly FROM proposals WHERE createdat BETWEEN '$lastWeek' and '$lastWeek2'"; 
	$pWeekly = mysqli_query($con, $pWResult);
	$pW = mysqli_fetch_array($pWeekly);

	//MONTHLY
	$pMResult = "SELECT count(*) as proposalMonthly FROM proposals WHERE createdat BETWEEN '$lastMonth' and '$thisMonth'"; 
	$pMonthly = mysqli_query($con, $pMResult);
	$pM = mysqli_fetch_array($pMonthly);

	$proposalDailyCount = $pD['proposalDaily'] ; 
	$proposalWeeklyCount =  $pW['proposalWeekly'] ;  
	$proposalMonthlyCount = $pM['proposalMonthly'] ;  
	$proposalTotalCount = $proposalCount['proposalCount'] ;


//PASSWORD COUNTS

	//TOTAL
	$result4 ="SELECT count(*) as passwordCount FROM eventlogs WHERE eventslogid = '57'"; 
	$pwGrandTotal = mysqli_query($con1, $result4 );
	$pwT = mysqli_fetch_array($pwGrandTotal);

	//DAILY
	$pwDResult = "SELECT count(*) as  passwordDaily FROM eventlogs WHERE eventslogid = '57' and createdat BETWEEN '$today 00:00:01' AND '$today 23:59:59'"; 
	$pwDaily = mysqli_query($con1, $pwDResult);
	$pwD = mysqli_fetch_array($pwDaily);

	//WEEKLY
	$pwWResult = "SELECT count(*) as  passwordWeekly FROM eventlogs WHERE eventslogid = '57' and createdat BETWEEN '$lastWeek' and '$lastWeek2'"; 
	$pwWeekly = mysqli_query($con1, $pwWResult);
	$pwW = mysqli_fetch_array($pwWeekly);

	//MONTHLY
	$pwMResult = "SELECT count(*) as  passwordMonthly FROM eventlogs WHERE eventslogid = '57' and createdat BETWEEN '$lastMonth' and '$thisMonth'"; 
	$pwMonthly = mysqli_query($con1, $pwMResult);
	$pwM = mysqli_fetch_array($pwMonthly);

	$passwordDailyCount = $pwD['passwordDaily'] ; 
	$passwordWeeklyCount = $pwW['passwordWeekly'] ;  
	$passwordMonthlyCount = $pwM['passwordMonthly'] ;   
	$passwordTotalCount = $pwT['passwordCount'] ; 


//TUTORIALS COUNTS

	//TOTAL
	$result2 ="SELECT count(*) as tutorialCount FROM eventlogs WHERE eventslogid = '56'"; 
	$tGrandTotal = mysqli_query($con1, $result2 );
	$tT = mysqli_fetch_array($tGrandTotal);

	//DAILY
	$tDResult = "SELECT count(*) as tutorialDaily FROM eventlogs WHERE eventslogid = '56' and createdat BETWEEN '$today 00:00:01' AND '$today 23:59:59'"; 
	$tDaily = mysqli_query($con1, $tDResult);
	$tD = mysqli_fetch_array($tDaily);

	//WEEKLY
	$tWResult = "SELECT count(*) as tutorialWeekly FROM eventlogs WHERE eventslogid = '56' and createdat BETWEEN '$lastWeek' and '$lastWeek2'"; 
	$tWeekly = mysqli_query($con1, $tWResult);
	$tW = mysqli_fetch_array($tWeekly);

	//MONTHLY
	$tMResult = "SELECT count(*) as tutorialMonthly FROM eventlogs WHERE eventslogid = '56' and createdat BETWEEN '$lastMonth' and '$thisMonth'"; 
	$tMonthly = mysqli_query($con1, $tMResult);
	$tM = mysqli_fetch_array($tMonthly);

	$tutorialDailyCount = $tD['tutorialDaily'] ; 
	$tutorialWeeklyCount = $tW['tutorialWeekly'] ;  
	$tutorialMonthlyCount = $tM['tutorialMonthly'] ;  
	$tutorialTotalCount = $tT['tutorialCount'] ; 


//SEARCH COUNTS 

	//TOTAL
	$result2 ="SELECT count(*) as searchCount FROM eventlogs WHERE eventslogid = '5'"; 
	$sGrandTotal = mysqli_query($con1, $result2 );
	$sT = mysqli_fetch_array($sGrandTotal);

	//DAILY
	$sDResult = "SELECT count(*) as searchDaily FROM eventlogs WHERE eventslogid = '5' and createdat BETWEEN '$today 00:00:01' AND '$today 23:59:59'"; 
	$sDaily = mysqli_query($con1, $sDResult);
	$sD = mysqli_fetch_array($sDaily);

	//WEEKLY
	$sWResult = "SELECT count(*) as searchWeekly FROM eventlogs WHERE eventslogid = '5' and createdat BETWEEN '$lastWeek' and '$lastWeek2'"; 
	$sWeekly = mysqli_query($con1, $sWResult);
	$sW = mysqli_fetch_array($sWeekly);

	//MONTHLY
	$sMResult = "SELECT count(*) as searchMonthly FROM eventlogs WHERE eventslogid = '5' and createdat BETWEEN '$lastMonth' and '$thisMonth'"; 
	$sMonthly = mysqli_query($con1, $sMResult);
	$sM = mysqli_fetch_array($sMonthly);

	$searchDailyCount = $sD['searchDaily'] ; 
	$searchWeeklyCount = $sW['searchWeekly'] ; 
	$searchMonthlyCount = $sM['searchMonthly'] ;  
	$searchTotalCount = $sT['searchCount'] ;  


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
<?php include ('menu.php');?>
<br />
<body>
<div class="row">
  <div class="small-10 columns">
  ShowSeeker Stats - At a Glance
  <table>
	  <thead>
		  <tr><td class="text-center">Item</td><td class="text-center">Today</td><td class="text-center">This <br> Week</td><td class="text-center">Last <br> Month</td><td class="text-center">Grand <br> Total</td>
		  </tr>
	  </thead>
	  <tbody>
		  <tr><td class="text-center">Proposals</td><td class="text-center"><?php echo $proposalDailyCount;?></td><td class="text-center"><?php echo $proposalWeeklyCount;?></td><td class="text-center"><?php echo $proposalMonthlyCount; ?></td><td class="text-center"><?php echo $proposalTotalCount;?></td></tr>
		  <tr><td class="text-center">Searches</td><td class="text-center"><?php echo $searchDailyCount;?></td><td class="text-center"><?php echo $searchWeeklyCount;?></td><td class="text-center"><?php echo $searchMonthlyCount;?></td><td class="text-center"><?php echo $searchTotalCount;?></td></tr>		  
		  <tr><td class="text-center">Users</td><td class="text-center"><?php echo $userDailyCount;?></td><td class="text-center"><?php echo $userWeeklyCount;?></td><td class="text-center"><?php echo $userMonthlyCount;?></td><td class="text-center"><?php echo $userTotalCount ;?></td></tr>
		  <tr><td class="text-center">Password</td><td class="text-center"><?php echo $passwordDailyCount;?></td><td class="text-center"><?php echo $passwordWeeklyCount;?></td><td class="text-center"><?php echo $passwordMonthlyCount;?></td><td class="text-center"><?php echo $passwordTotalCount;?></td></tr>
		  <tr><td class="text-center">Tutorials</td><td class="text-center"><?php echo $tutorialDailyCount;?></td><td class="text-center"><?php echo $tutorialWeeklyCount;?></td><td class="text-center"><?php echo $tutorialMonthlyCount;?></td><td class="text-center"><?php echo $tutorialTotalCount;?></td></tr>
	  </tbody>
  </table>
  </div>
</div>
<center><h5 class="subheader">Tutorial Logging started: 07-14-15<br>Users & Password Logging started: 08-27-15</h5></center>
<script src="//cdn.jsdelivr.net/foundation/5.5.0/js/foundation.min.js"></script>
<script>
	$(document).foundation();
</script>