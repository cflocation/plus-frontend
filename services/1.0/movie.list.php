<?php
	ini_set("display_errors",1);

	include_once('../../config/mysqli.php');
	mysqli_select_db($con,"Yoda");

	$sql = "SELECT tmsid AS id, title AS titleEn, id AS tmdbid, processed, bad, poster FROM themoviedb ORDER BY title";	
	$result = mysqli_query($con, $sql);

	//IF NOT RESULTS RETURN EMPTY ARRAY
	while ($row = $result->fetch_assoc()) {
		$row['title'] = utf8_encode($row['titleEn']);
		$data[] = $row;
	}

	print json_encode($data,true);
?>