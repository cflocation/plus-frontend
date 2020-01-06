<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);

	$con=mysqli_connect("db4.showseeker.net","vastdb","jK6YK71tJ","logs");
	$issue = mysqli_real_escape_string($con, $_POST[issue]);

	$sql="INSERT INTO supportItems (createdat, ae, assignedTo, customer, platform, browser, issue, priority, status) VALUES ('$_POST[createdat]','$_POST[ae]','$_POST[assignedTo]','$_POST[customer]','$_POST[platform]','$_POST[browser]', '$issue','$_POST[priority]', '1')";

if (!mysqli_query($con,$sql))
  {
  die('Error: ' . mysqli_error($con));
  }
header('Location: index.php');
mysqli_close($con);

?>