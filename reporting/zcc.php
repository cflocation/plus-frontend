
<?php
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=ss_channel_count.xls");  //File name extension was wrong
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);


	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);

$con = mysqli_connect("61b1ed95616f65903ff311cc9decad51aa4cac3d.rackspaceclouddb.com","vastdb","VastPlus#01","Lineups");
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

	$sql_providers = "SELECT * FROM `provider` order by PID ASC LIMIT 0, 5000" ; 
	$zips = mysqli_query($con, $sql_providers);
	//$row_pids = mysqli_fetch_array($zips);


?>
<table width="55%">
<thead>
<tr>
<td>ZIP</td><td>Provider ID</td><td>Providers</td><td>Channels</td>
</tr>
</thead>

<?php

while ($row_pids = mysqli_fetch_array($zips)) {

	$pid = $row_pids['pid'];
	$zip = $row_pids['zipcode'];
	$provider = $row_pids['provider_lineupid'];


	$zip_sql = "select count(*) as pc from `provider` where zipcode = '$zip'";
	$zip_count = mysqli_query($con, $zip_sql);
	$row_zip = mysqli_fetch_assoc($zip_count);
	$provider_cnt = $row_zip['pc'];


	$channel_sql = "select count(*) as channels from channel where pid = $pid";
	$channel_count = mysqli_query($con, $channel_sql);
	$row_channel = mysqli_fetch_assoc($channel_count);
	$channel_cnt = 	$row_channel['channels'];


echo "<tr><td>'".$zip."</td><td>".$provider ."</td><td>".$provider_cnt ."</td><td>". $channel_cnt ."</td></tr>" ; 

}

?>
</table>

