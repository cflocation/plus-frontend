<?php
	ini_set("display_errors",1);

	//set the userid and the token id
	$userid = $_GET['userid'];
	$tokenid = $_GET['tokenid'];

	//if there is anything blank return an error
	if(empty($tokenid) || empty($userid)){
		exit('error');
	}

	//include the datbase file for the authentication and other services
	//include_once('../../config/mysqli.php');
	include_once('../../config/databaseOn.php');

	//include the classes
	include_once('../../classes/On.php');


	$showid = $_GET['id'];
	$showtype = substr($showid, 0, 2);

	//call the on class
	$on = new On($con, $userid, $tokenid);

	$images = $on->getCoverImage($showid,240,360);

	$array = array("images" => $images);
	$re = json_encode($array);
	print $re;
?>