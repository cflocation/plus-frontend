<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);

	$con=mysqli_connect("db4.showseeker.net","vastdb","jK6YK71tJ","logs");
	$result ="SELECT * FROM `eventlogs` WHERE `eventslogid` = 56 and userid != '2136' ORDER BY `eventlogs`.`createdat` DESC"; 
	$views = mysqli_query($con, $result );
?> 
<head>
	<title>ShowSeeker - Tutorial Logging </title>
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
<h3 class="text-center">Tutorial Logs</h3>
<center>
<table>
<thead>
	<tr>
		<td>UserID</td><td>Tutorial Viewed</td><td>IP</td><td>Date</td>
	</tr>
</thead>
<tbody>	
<?php while($row = mysqli_fetch_array($views)) { ?>
	<tr>
		<td><?php echo $row['userid'] ;?></td><td><?php echo $row['request'] ;?></td><td><?php echo $row['result'] ;?></td><td><?php echo $row['createdat'] ;?></td>
	</tr>
<?php } ?>

</table>
</center>



<script src="//cdn.jsdelivr.net/foundation/5.5.0/js/foundation.min.js"></script>
<script>
	$(document).foundation();
</script>