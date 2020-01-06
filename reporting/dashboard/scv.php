<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);
$siteup = "<i style='color:#4472B4' class='fa fa-check-square '></i> ";
$sitedown = "<i style='color:#F20010' class='fa fa-exclamation-triangle '></i> ";
$timestamp = date("h:i:s a") ;
$datestamp = date("F-d-Y");
$datecheck = date("Y-m-d");

$timeskip = date("H") ;
?>

<head>
<title>ShowSeeker Dashbaord - System Check</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//cdn.jsdelivr.net/foundation/5.4.3/css/normalize.css">
<link rel="stylesheet" href="//cdn.jsdelivr.net/foundation/5.4.3/css/foundation.css">
<script src="//cdn.jsdelivr.net/foundation/5.4.3/js/vendor/modernizr.js"></script>
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
</head>

<?php
//EventCode Key:
// #1 - Website
// #2 - Database - Date Mismatch Issue
// #3 - Database - Date Mismatch Issue
// #4 - Task Servers (PDF / XLS Generators 
// #5 - Other Services
// #9 - Dashboard Check

//SKIP 12AM (00) and 1AM (01), as the database servers are processing the latest show data, begin checking at 2AM again 
$timeskip = date("H") ;
if ( (!($timeskip == '00')) or (!($timeskip == '01')) ) {
//END SKIP CODE
 
$event='Dashboard Check'; $eventcode=9; $host=''; logme($event, $eventcode, $host);
?>
<br>
<div class="row">
  <div class="small-2 columns">
			  <h6><small>showseeker.com</small></h6>
			  <?php $host = '50.57.116.229'; $up = ping($host); if( $up ) {echo $siteup;} else {echo $sitedown; $event='Site Unavailable - '.$host.''; $eventcode=1; logme($event, $eventcode, $host);} ?>
			  <?php echo "<small>" . $timestamp . "</small>";?>
	</div>
    <div class="small-2 columns">
			  <h6><small>nodex.showseeker.com</small></h6>
			  <?php $host = 'nodex1.showseeker.com'; $up = ping($host); if( $up ) {echo $siteup;} else {echo $sitedown; $event='Site Unavailable - '.$host.''; $eventcode=1; logme($event, $eventcode, $host);} ?><?php echo "<small>" . $timestamp . "</small>";?>

	</div>
    <div class="small-2 columns">
			  <h6><small>managed.showseeker.com</small></h6>
			  <?php $host = 'managed.showseeker.com'; $up = ping($host); if( $up ) {echo $siteup;} else {echo $sitedown; $event='Site Unavailable - '.$host.''; $eventcode=1; logme($event, $eventcode, $host);} ?><?php echo "<small>" . $timestamp . "</small>";?>

	</div>
    <div class="small-2 columns ">
			  <h6><small>chocolate.showseeker.com</small></h6>
			  <?php $host = 'chocolate.showseeker.com'; $up = ping($host); if( $up ) {echo $siteup;} else {echo $sitedown; $event='Site Unavailable - '.$host.''; $eventcode=1; logme($event, $eventcode, $host);} ?>  <?php echo "<small>" . $timestamp . "</small>";?>

	</div>

  <div class="small-2 columns">
			<?php
				$file_s1 = file_get_contents('http://solr.showseeker.net:8983/solr/gracenote/solr-import');
				$xml_s1 = simplexml_load_string($file_s1);
					$s1_fetched = trim($xml_s1->lst[2]->str[1]);
					$s1_date = substr($xml_s1->lst[2]->str[3], 0, -9);
					$s1_processed = trim($xml_s1->lst[2]->str[6]);
			//ITEMS CHECK
			if ( $s1_fetched <> $s1_processed) { $s1_status = $sitedown . "<br>F:" .$s1_fetched . "<br> P:" . $s1_processed; $event='Count Mismatch - Solr 1'; $eventcode=3; logme($event, $eventcode, $host);} 
			else {$s1_status = $siteup;}
			//DATE CHECK
			if ( $s1_date <> $datecheck) {$s1_status = $sitedown . "<br>Date Mismatch"; $event='Date Mismatch - Solr 1'; $eventcode=2; logme($event, $eventcode, $host);} 
			else {$s1_status = $siteup;}
			?>

			  <h6><small>Solr 1</small></h6>
			  <?php echo $s1_status;?>
			  <?php echo "<small>" . $timestamp . "</small>";?>
	</div>
  <div class="small-2 columns">
			<?php
				$file_s2 = file_get_contents('http://solr.showseeker.net:8983/solr/gracenote/solr-import');
				$xml_s2 = simplexml_load_string($file_s2);
					$s2_fetched = trim($xml_s2->lst[2]->str[1]);
					$s2_date = substr($xml_s2->lst[2]->str[3], 0, -9);
					$s2_processed = trim($xml_s2->lst[2]->str[6]);
			//ITEMS CHECK
			if ( $s2_fetched <> $s2_processed) { $s2_status = $sitedown . "<br>F:" .$s2_fetched . "<br> P:" . $s2_processed; $event='Count Mismatch  - Solr 2'; $eventcode=3; logme($event, $eventcode, $host);} 
			else {$s2_status = $siteup;}
			//DATE CHECK
			if ( $s2_date <> $datecheck) {$s2_status = $sitedown . "<br>Date Mismatch"; $event='Date Mismatch - Solr 2'; $eventcode=2; logme($event, $eventcode, $host);} 
			else {$s2_status = $siteup;}
			?>

			  <h6><small>Solr 2</small></h6>
			  <?php echo $s2_status;?>
			  <?php echo "<small>" . $timestamp . "</small>";?>

	</div>
</div>
<hr>
<?php
//END SKIP IF STATEMENT
}
?>

<?php
function ping($host,$port=80,$timeout=6)
{
		$fsock = fsockopen($host, $port, $errno, $errstr, $timeout);
        if ( ! $fsock ) {
			return FALSE;
			}
        else { 
			return TRUE;
			}
}
?>
<?php

function logme ($event, $eventcode, $host)
{		
		//$stack = array();
		$timestamp = date("h:i:s a") ;
		$datestamp = date("m-d-Y");
		$con = mysqli_connect("db4.showseeker.net","vastdbuser","jK6YK71tJ","logs");
		$logger = "INSERT INTO dashboard (event, eventcode, createdat) VALUES ('$event', '$eventcode', '$datestamp.$timestamp')" ; 
        $dashboard_log = mysqli_query($con,$logger);
		//array_push($stack, $host, $event);
		//echo json_encode ($stack) ;

}
?>

<script src="//cdn.jsdelivr.net/foundation/5.4.3/js/foundation.min.js"></script>
<script>
	$(document).foundation();
</script>

