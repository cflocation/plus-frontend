<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);

	$next_thursday    =     strtotime("last tuesday");
	$this_thurday     =     strtotime('tuesday this week');
	if($next_thursday==$this_thurday)
	{ 
	$numberOfWeeks = 1;
	$next_thursday = $next_thursday + ($numberOfWeeks * 60 * 60 * 24 * 7);
	}
	$check =  date("Y-m-d", $this_thurday) ;

	//$con = mysqli_connect("db4.showseeker.net","vastdbuser","jK6YK71tJ","ShowSeeker");
	$con  = mysqli_connect("db0.showseeker.net","dbadmin","XddS2fTFr4521","ShowSeeker");
	$sql = "select * from EzGridsNotification where sentDate > '$check' order by sentDate desc" ; 
	$ezgrids_log = mysqli_query($con,$sql);
	$row_cnt = mysqli_num_rows($ezgrids_log);
	
?>
<head>
<title>ShowSeeker Dashbaord - EzGrids - Emailer Status</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//cdn.jsdelivr.net/foundation/5.4.3/css/normalize.css">
<link rel="stylesheet" href="//cdn.jsdelivr.net/foundation/5.4.3/css/foundation.css">
<script src="//cdn.jsdelivr.net/foundation/5.4.3/js/vendor/modernizr.js"></script>
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<nav class="top-bar" data-topbar>
  <ul class="title-area">
    <li class="name">
      <h1><a href="#">ShowSeeker  - System Dashboard</a></h1>
    </li>
  </ul>
  <section class="top-bar-section">
    <ul class="right">
      <li class="has-dropdown">
        <a href="#">System Menu</a>
        <ul class="dropdown">
          <li><a href="index.php">Main Menu</a></li>
		  <li class="divider"></li>
          <li><a href="system_check.php">System Check</a></li>
          <li><a href="system_stats.php">System Stats</a></li>
          <li><a href="grids_mailer_check.php">Ez-Grids - Email Check</a></li>
          <li><a href="tutorials.php">Tutorials - Logs</a></li>
          <li><a href="../support/index.php">Supprt Tickets</a></li>
        </ul>
      </li>
    </ul>
</nav>
<?php
echo "<h4><center>Current Tuesday: "  . $check ."</center></h4>";
?>
<div class="row">
  <div class="small-12 columns">
<?php echo "Total Emails Sent: " . $row_cnt . "<br />" ;?>
	<table>
	  <thead>
		<tr>
		  <th width="40%">Email Address</th>
		  <th width="40%">Date Sent</th>
		  <th width="10%">Error ? </th>
		</tr>
	  </thead>
	<tbody>


<?php
	while ($row = mysqli_fetch_assoc($ezgrids_log)) {
	
		if ($row['error'] === '1') { $errorMsg = "<i class='btn-danger fa fa-times fa-2x' style='color:red'></i>" ;} 
		else { $errorMsg = '' ; }

		echo "<tr><td>" . $row['email'] ."</td><td>" . $row['sentDate'] . "</td><td>" . $errorMsg . "</td></tr>" ;
	}
?>
	</table>

</div>
</div>


<script src="//cdn.jsdelivr.net/foundation/5.4.3/js/foundation.min.js"></script>
<script>
	$(document).foundation();
</script>




