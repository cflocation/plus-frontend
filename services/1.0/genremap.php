<?php
	ini_set("display_errors",1);

	//include the classes and the database
	include_once('../../config/mysqli.php');

	//connect the database
	mysqli_select_db($con,"Programming");

	//set the SQL
	$sql = "SELECT trim(LOWER(genre)) AS genre FROM genres";
	$result = mysqli_query($con, $sql);

	//loop over the records and find it
	while ($row = $result->fetch_assoc()) {
		$re[] = $row['genre'];
	}

	//print the data
	print json_encode($re);
?>