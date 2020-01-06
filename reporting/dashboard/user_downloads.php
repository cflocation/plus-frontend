<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);
	session_start();


	$uid = $_GET['uid'];
	$con = mysqli_connect("db4.showseeker.net","vastdb","jK6YK71tJ","logs");
	$sql = "SELECT * FROM `eventlogs` WHERE `userid` = $uid AND `eventslogid` IN ( 7, 8, 9, 10, 11, 12, 13, 14, 15, 48, 49, 54, 55 ) ORDER BY `eventlogs`.`createdat` DESC LIMIT 0 , 50";
	$search_downloads = mysqli_query($con, $sql);

?>
<!DOCTYPE html>
<head>
<title>ShowSeeker Plus - User Downloads</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.1/css/normalize.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.1/css/foundation.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.1/js/vendor/modernizr.js"></script>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>


<div class="row">
	<div class="large-12 columns">
		<center><table width="75%">
		<thead><tr><td>Request</td><td>Result</td><td>Proposal</small></td></tr></thead>
		
<?php
			while ($row = mysqli_fetch_assoc($search_downloads)) {

				echo "<tr><td><small>". $row['request']."</small></td><td><small>".$row['result']."</small></td><td>".$row['proposalid']."</td></tr>";
				}

?>
		</table>
	</div>
</div>






<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.1/js/foundation.min.js"></script>
<script>
	$(document).foundation();
</script>