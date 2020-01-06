<?php
	return;
	include_once('../../config/database.php');
	ini_set("display_errors",1);
	mysql_select_db("ezbreaks", $con);

	$sql = "SELECT * FROM breaknetworks";
	$result = mysql_query($sql);

	//loop over and add to list
	while($row = mysql_fetch_assoc($result)) {

		$breaksid = $row['id'];
		$networkid = $row['networkid'];

		$sql = "INSERT INTO breaknetworks_items(breaknetworksid, breaktime, weekday, length, recommprogbased)
		SELECT $breaksid AS breaknetworksid, breaktime, weekday, length, recommprogbased
		FROM breaksxls_utc
		WHERE breaktype = 1 AND networkid = $networkid";
		mysql_query($sql);


		print_r($row);
	}
?>