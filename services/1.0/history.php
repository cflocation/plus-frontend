<?php
	session_start();
	ini_set("display_errors",1);

	$userid = $_SESSION['userid'];
	$tokenid = $_SESSION['tokenid'];
	$callsign = $_GET['callsign'];
	$date = $_GET['date'];
	$tz = $_GET['tz'];

	//if there is anything blank return an error
	if(empty($tokenid) || empty($userid) || empty($callsign) || empty($date) || empty($tz)){
		exit('error');
	}
	
	//mysql connector
	include_once('../config/mysqli.php');
	include_once('../classes/Search.php');


	//search class call
	$search = new Search($con, $userid, $tokenid);
	$data = $search->getNetworkHistory($callsign,$date,$tz);


	print_r($data);

	/*
	//call the on class
	$on = new On($con, $userid, $tokenid);
	$images = $on->getimagebyshowid($showid);


	$array = array("images" => $images);
	$re = json_encode($array);
	print $re;
	*/
?>