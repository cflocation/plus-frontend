<?php
	ini_set("display_errors",0);

	//include the classes and the database
	include_once('../../config/mysqli.php');

	$sql = "SELECT networklogos.networkid, logos.filename FROM networklogos INNER JOIN logos ON logos.id = networklogos.logoid";
	$result = mysqli_query($con, $sql);

	while ($row = $result->fetch_assoc()) {
		$re[] = $row;
	}

	print json_encode($re);
?>