<?php
	$con=mysqli_connect("db4.showseeker.net","vastdb","jK6YK71tJ","logs");
	$SID = $_GET['SID'] ;

	$sql = "DELETE from supportItems where SID = $SID" ; 
	$result=mysqli_query($con,$sql);
	header('Location: index.php');				
	mysqli_close($con);
?>