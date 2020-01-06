<?php

	session_start();
	ini_set("display_errors",0);

	//session ids
	$corporationid = $_SESSION['corporationid'];
	$userid = $_SESSION['userid'];


	$url = "http://ezbreaks.showseeker.com/services/files.php?cid={$corporationid}";
	$json = file_get_contents($url);
	print $json;
?>


