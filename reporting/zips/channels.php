<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);
	session_start();
	//$con = mysqli_connect("61b1ed95616f65903ff311cc9decad51aa4cac3d.rackspaceclouddb.com","vastdb","VastPlus#01","Lineups");
	$con = mysqli_connect("db4.showseeker.net","vastdb","VastPlus#01","Lineups");
	if (isset($_GET['pid'])) {
		$providerID = $_GET['pid'] ;
		$channel_sql = "SELECT * FROM channel WHERE pid = $providerID ORDER BY callsign ASC";
		$channels_sql = mysqli_query($con, $channel_sql);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//cdn.jsdelivr.net/foundation/5.4.3/css/normalize.css">
<link rel="stylesheet" href="//cdn.jsdelivr.net/foundation/5.4.3/css/foundation.css">
<script src="//cdn.jsdelivr.net/foundation/5.4.3/js/vendor/modernizr.js"></script>
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<?php include 'top.php'; ?>
<div class="row"><br />
	<div class="small-12 large-centered columns">
			<table>
			  <thead>
				<tr>
				  <th width="150">Call Sign</th>
				  <th width="150">Channel #</th>
				  <th width="150">Channel ID</th>
				</tr>
			  </thead>
			<?php while ($row = mysqli_fetch_assoc($channels_sql)) {
				echo "<tr><td>" .$row['callsign'] ."</td><td>" . $row['channel'] ."</td><td>" . $row['channelid'] . "</td></tr>" ; } ?>
			</table>
	</div>
</div>
<script src="//cdn.jsdelivr.net/foundation/5.4.3/js/foundation.min.js"></script>
<script>
	$(document).foundation();
</script>


