<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);

	if (isset($_GET['corp']))
	{ $cid = $_GET['corp'] ; 	}


	$con = mysqli_connect("61b1ed95616f65903ff311cc9decad51aa4cac3d.rackspaceclouddb.com","vastdb","VastPlus#01","ShowSeeker");
	$user_sql = "SELECT id FROM `users` WHERE `corporationid` = $cid ORDER BY `id` ASC" ;	 

	$users = mysqli_query($con, $user_sql);


while ($row1 = mysqli_fetch_array($users)) {

	echo $row1['id'] . "<br>"; 

}


?>


