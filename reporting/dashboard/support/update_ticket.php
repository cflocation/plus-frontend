<?php
	$con=mysqli_connect("db4.showseeker.net","vastdb","jK6YK71tJ","logs");
	$problem = addslashes($_POST[issue]);
	$solution = addslashes($_POST[solution]);

	$now = date("Y-m-d H:i:s");
	$ud = '';
	$cd = '';

	if ($_POST[status] == '4') {	
		$cd = $now ;
	}
	else {
		$cd = '';
	}
	if ($_POST[status] == '3') {	
		$ud = $now ;
	}
	else {
		$ud = '';
	}
	if ($_POST[status] == '2') {	
		$ud = $now ;
	}
	else {
		$ud = '';
	}


$sql="UPDATE supportItems SET issue='$problem', solution='$solution', priority='$_POST[priority]', browser='$_POST[browser]', status='$_POST[status]', updatedat='$ud', closedat='$cd' where SID ='$_POST[sid]'";
$result=mysqli_query($con,$sql);
header('Location: index.php');				
mysqli_close($con);
?>