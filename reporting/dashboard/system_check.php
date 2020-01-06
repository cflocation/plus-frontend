<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);
$siteup = "<i style='color:#4472B4' class='fa fa-check-square fa-lg'></i>";
$sitedown = "<i style='color:#F20010' class='fa fa-exclamation-triangle fa-lg'></i>";
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
<?php include ('menu.php');?>

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

?>

<div class="row">
  <div class="small-8 large-centered columns">
  <?php echo $datestamp; 
	  $event='Dashboard Check'; $eventcode=9; $host=''; logme($event, $eventcode, $host);
  
  ?>

		<table width="100%">
		  <thead>
			<tr align="center">
			  <th width="55%">Service Name</th>
			  <th width="15%">Status</th>
			  <th width="25%">Time</th>
			</tr>
		  </thead>
		  <tbody>

			<tr>
			  <td align="center" colspan="3"><strong>WEBSITES</strong></td>
			</tr>
			
			<tr>
			  <td>showseeker.com (50.57.116.229)</td>
			  <td align="center"><?php $host = '50.57.116.229'; $up = ping($host); if( $up ) {echo $siteup;} else {echo $sitedown; $event='Site Unavailable - '.$host.''; $eventcode=1; logme($event, $eventcode, $host);} ?></td>
			  <td><?php echo $timestamp;?></td>
			</tr>

			<tr>
			  <td>plus.showseeker.com</td>
			  <td align="center"><?php $host = 'plus.showseeker.com'; $up = ping($host); if( $up ) {echo $siteup;} else {echo $sitedown; $event='Site Unavailable - '.$host.''; $eventcode=1; logme($event, $eventcode, $host);} ?></td>
			  <td><?php echo $timestamp;?></td>
			</tr>

			<tr>
			  <td>nodex1.showseeker.com</td>
			  <td align="center"><?php $host = 'nodex1.showseeker.com'; $up = ping($host); if( $up ) {echo $siteup;} else {echo $sitedown; $event='Site Unavailable - '.$host.''; $eventcode=1; logme($event, $eventcode, $host);} ?></td>
			  <td><?php echo $timestamp;?></td>
			</tr>

			<tr>
			  <td>managed.showseeker.com</td>
			  <td align="center"><?php $host = 'managed.showseeker.com'; $up = ping($host); if( $up ) {echo $siteup;} else {echo $sitedown; $event='Site Unavailable - '.$host.''; $eventcode=1; logme($event, $eventcode, $host);} ?></td>
			  <td><?php echo $timestamp;?></td>
			</tr>

			<tr>
			  <td>chocolate.showseeker.com</td>
			  <td align="center"><?php $host = 'chocolate.showseeker.com'; $up = ping($host); if( $up ) {echo $siteup;} else {echo $sitedown; $event='Site Unavailable - '.$host.''; $eventcode=1; logme($event, $eventcode, $host);} ?></td>
			  <td><?php echo $timestamp;?></td>
			</tr>

			<tr>
			  <td align="center" colspan="3"><strong>DATABASES</strong></td>
			</tr>

			<?php
				$file_s1 = file_get_contents('http://solr.showseeker.net:8983/solr/gracenote/dataimport');
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

			<tr>
			  <td>Solr Server 1 - Stats</td>
			  <td align="center"><?php echo $s1_status ;?></td>
			  <td><?php echo $timestamp;?></td>
			</tr>

			<?php
				$file_s2 = file_get_contents('http://solr.showseeker.net:8983/solr/gracenote/dataimport');
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

			<tr>
			  <td>Solr Server 2 - Stats</td>
			  <td align="center"><?php echo $s2_status ;?></td>
			  <td><?php echo $timestamp;?></td>
			</tr>
			<tr>
			  <td align="center" colspan="3"><strong>TASK SERVERS</strong></td>
			</tr>

			<tr>
			  <td>PDF-AST - 162.209.8.5</td>
			  <td align="center"><?php $host = '162.209.8.5'; $up = ping($host); if( $up ) {echo $siteup;} else {echo $sitedown; $event='Site Unavailable - '.$host.''; $eventcode=4; logme($event, $eventcode, $host);} ?></td>
			  <td><?php echo $timestamp;?></td>
			</tr>

			<tr>
			  <td>PDF-CST - 162.209.8.13</td>
			  <td align="center"><?php $host = '162.209.8.13'; $up = ping($host); if( $up ) {echo $siteup;} else {echo $sitedown; $event='Site Unavailable - '.$host.''; $eventcode=4; logme($event, $eventcode, $host);} ?></td>
			  <td><?php echo $timestamp;?></td>
			</tr>

			<tr>
			  <td>PDF-EST - 162.209.8.75</td>
			  <td align="center"><?php $host = '162.209.8.75'; $up = ping($host); if( $up ) {echo $siteup;} else {echo $sitedown; $event='Site Unavailable - '.$host.''; $eventcode=4; logme($event, $eventcode, $host);} ?></td>
			  <td><?php echo $timestamp;?></td>
			</tr>

			<tr>
			  <td>PDF-HAST - 162.209.8.17</td>
			  <td align="center"><?php $host = '162.209.8.17'; $up = ping($host); if( $up ) {echo $siteup;} else {echo $sitedown; $event='Site Unavailable - '.$host.''; $eventcode=4; logme($event, $eventcode, $host);} ?></td>
			  <td><?php echo $timestamp;?></td>
			</tr>

			<tr>
			  <td>PDF-MDT - 162.209.8.33</td>
			  <td align="center"><?php $host = '162.209.8.33'; $up = ping($host); if( $up ) {echo $siteup;} else {echo $sitedown; $event='Site Unavailable - '.$host.''; $eventcode=4; logme($event, $eventcode, $host);} ?></td>
			  <td><?php echo $timestamp;?></td>
			</tr>

			<tr>
			  <td>PDF-MST - 162.209.8.9</td>
			  <td align="center"><?php $host = '162.209.8.9'; $up = ping($host); if( $up ) {echo $siteup;} else {echo $sitedown; $event='Site Unavailable - '.$host.''; $eventcode=4; logme($event, $eventcode, $host);} ?></td>
			  <td><?php echo $timestamp;?></td>
			</tr>

			<tr>
			  <td>PDF-PR - 162.209.8.14</td>
			  <td align="center"><?php $host = '162.209.8.14'; $up = ping($host); if( $up ) {echo $siteup;} else {echo $sitedown; $event='Site Unavailable - '.$host.''; $eventcode=4; logme($event, $eventcode, $host);} ?></td>
			  <td><?php echo $timestamp;?></td>
			</tr>

			<tr>
			  <td>PDF-PST - 162.209.88.45</td>
			  <td align="center"><?php $host = '162.209.88.45'; $up = ping($host); if( $up ) {echo $siteup;} else {echo $sitedown; $event='Site Unavailable - '.$host.''; $eventcode=4; logme($event, $eventcode, $host);} ?></td>
			  <td><?php echo $timestamp;?></td>
			</tr>

			<tr>
			  <td>XLS-AST - 162.209.7.250</td>
			  <td align="center"><?php $host = '162.209.7.250'; $up = ping($host); if( $up ) {echo $siteup;} else {echo $sitedown; $event='Site Unavailable - '.$host.''; $eventcode=4; logme($event, $eventcode, $host);} ?></td>
			  <td><?php echo $timestamp;?></td>
			</tr>

			<tr>
			  <td>XLS-CST - 192.237.176.11</td>
			  <td align="center"><?php $host = '192.237.176.11'; $up = ping($host); if( $up ) {echo $siteup;} else {echo $sitedown; $event='Site Unavailable - '.$host.''; $eventcode=4; logme($event, $eventcode, $host);} ?></td>
			  <td><?php echo $timestamp;?></td>
			</tr>

			<tr>
			  <td>XLS-EST - 67.207.156.150</td>
			  <td align="center"><?php $host = '67.207.156.150'; $up = ping($host); if( $up ) {echo $siteup;} else {echo $sitedown; $event='Site Unavailable - '.$host.''; $eventcode=4; logme($event, $eventcode, $host);} ?></td>
			  <td><?php echo $timestamp;?></td>
			</tr>

			<tr>
			  <td>XLS-HAST - 162.209.6.99</td>
			  <td align="center"><?php $host = '162.209.6.99'; $up = ping($host); if( $up ) {echo $siteup;} else {echo $sitedown; $event='Site Unavailable - '.$host.''; $eventcode=4; logme($event, $eventcode, $host);} ?></td>
			  <td><?php echo $timestamp;?></td>
			</tr>

			<tr>
			  <td>XLS-MDT - 162.209.88.214</td>
			  <td align="center"><?php $host = '162.209.88.214'; $up = ping($host); if( $up ) {echo $siteup;} else {echo $sitedown; $event='Site Unavailable - '.$host.''; $eventcode=4; logme($event, $eventcode, $host);} ?></td>
			  <td><?php echo $timestamp;?></td>
			</tr>

			<tr>
			  <td>XLS-MST - 192.237.176.254</td>
			  <td align="center"><?php $host = '192.237.176.254'; $up = ping($host); if( $up ) {echo $siteup;} else {echo $sitedown; $event='Site Unavailable - '.$host.''; $eventcode=4; logme($event, $eventcode, $host);} ?></td>
			  <td><?php echo $timestamp;?></td>
			</tr>

			<tr>
			  <td>XLS-PR - 192.237.173.16</td>
			  <td align="center"><?php $host = '192.237.173.16'; $up = ping($host); if( $up ) {echo $siteup;} else {echo $sitedown; $event='Site Unavailable - '.$host.''; $eventcode=4; logme($event, $eventcode, $host);} ?></td>
			  <td><?php echo $timestamp;?></td>
			</tr>

			<tr>
			  <td>XLS-PST - 162.209.4.246</td>
			  <td align="center"><?php $host = '162.209.4.246'; $up = ping($host); if( $up ) {echo $siteup;} else {echo $sitedown; $event='Site Unavailable - '.$host.''; $eventcode=4; logme($event, $eventcode, $host);} ?></td>
			  <td><?php echo $timestamp;?></td>
			</tr>

			<tr>
			  <td align="center" colspan="3"><strong>OTHER SERVICES</strong></td>
			</tr>

			<tr>
			  <td>Images server post to Amazon - 50.57.74.41</td>
			  <td align="center"><?php $host = '50.57.74.41'; $up = ping($host); if( $up ) {echo $siteup; } else {echo $sitedown; $event='Site Unavailable - '.$host.''; $eventcode=5; logme($event, $eventcode, $host);} ?></td>
			  <td><?php echo $timestamp;?></td>
			</tr>

			<tr>
			  <td>Ftpdump Client Server - 23.253.53.164</td>
			  <td align="center"><?php $host = '23.253.53.164'; $port='21'; $up = ping($host, $port); if( $up ) {echo $siteup;} else {echo $sitedown; $event='Site Unavailable - '.$host.''; $eventcode=5; logme($event, $eventcode, $host);} ?></td>
			  <td><?php echo $timestamp;?></td>
			</tr>

		  </tbody>
		</table>
	</div>
</div>
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

