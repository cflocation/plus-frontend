<?php
	ini_set("display_errors",1);
	$lineupId = $_GET['lineupId'];

	//include the classes and the database
	include_once('../../config/mysqli.php');

	$sql = "SELECT name,networks FROM ProviderPackages WHERE lineupId = '$lineupId' ORDER BY name";
	mysqli_select_db($con,"Lineups");
	$result = mysqli_query($con, $sql);
	$row_cnt = $result->num_rows;

	if($row_cnt == 0){
		print json_encode(0);
		return;
	}

	while ($row = $result->fetch_assoc()) {
		$re[] = $row;
	}

	$re = array("networks"=>$re);
	print json_encode($re);
?>