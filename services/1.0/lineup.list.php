<?php
	ini_set("display_errors",1);

	//include the classes and the database
	include_once('../../config/mysqli.php');

	$zip = $_GET['zip'];
	$sql = "SELECT Provider.name, ProviderZipcode.zipcode, Provider.lineupId, Provider.type FROM ProviderZipcode INNER JOIN Provider ON Provider.lineupId = ProviderZipcode.lineupId WHERE zipcode = '$zip' ORDER BY Provider.name";
	mysqli_select_db($con,"Lineups");
	$result = mysqli_query($con, $sql);
	$row_cnt = $result->num_rows;

	if($row_cnt == 0){
		$re = array("linups"=>0);
		print json_encode($re);
		return;
	}

	while ($row = $result->fetch_assoc()) {
		$stripped = preg_replace("/\([^)]+\)/","",$row['name']);
		$row['stripped'] = $stripped;
		$re[] = $row;
	}

	$re = array("linups"=>$re);
	print json_encode($re);
?>