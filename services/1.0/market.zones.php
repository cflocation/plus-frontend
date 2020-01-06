<?php
	ini_set("display_errors",1);

	//set the userid and the token id and other varibles that are needed
	$marketid = $_GET['marketid'];
	$userid = $_GET['userid'];
	$authtokin = $_GET['tokenid'];

	//if there is anything blank return an error
	if(empty($authtokin) || empty($userid) || empty($marketid)){
		exit('error');
	}

	//include the classes and the database
	include_once('../../config/mysqli.php');


	//Authentication
	require_once('../../classes/Auth.php');
	$auth = new Auth($con);
	$url = $_SERVER['PHP_SELF'];
	$key = $auth->checkAuth($url,$authtokin,$userid);

	if(!$key){
		print "Access denied - You are not authorized to access this page.";
		exit;
	}
	//set the token id for the user
	$tokenid = $key;

	require_once('../../classes/User.php');
	require_once('../../classes/Zones.php');

	//create the user events
	$user = new User($con,$userid,$tokenid);
	$userinfo = $user->getuserinfo();
	$nationalrep = $user->inrole(15);
	$showmarkets = $userinfo['showmarkets'];
	$corporationid = $userinfo['corporationid'];


	$zone = new Zones($con,$userid,$tokenid);
	$zones = $zone->getzones($marketid,$nationalrep,$showmarkets,$corporationid);

	$re = array("marketid" => $marketid, "zones" => $zones);

	print json_encode($re);
?>