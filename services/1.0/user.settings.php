<?php
	ini_set("display_errors",1);

	//set the userid and the token id
	$userid = $_GET['userid'];
	$authtokin = $_GET['tokenid'];

	//if there is anything blank return an error
	if(empty($authtokin) || empty($userid)){
		exit('error');
	}

	//include the datbase file for the authentication and other services
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

	//if the auth is correct keep moving on
	require_once('../../classes/User.php');
	require_once('../../classes/Zones.php');

	//create the user events
	$user = new User($con,$userid,$tokenid);
	$userinfo = $user->getuserinfo();
	$markets = $user->getmarkets();
	//$zones = $user->getzones();
	$settings = $user->getsettings();
	$buttons = $user->buttons();


	//setup the array for the passback
	$re = array(
		"button" => $buttons,
		"ratecards" => $userinfo['ratecards'],
		"showmarkets" => $userinfo['showmarkets'],
		"ratecardmode" => $userinfo['ratecardmode'],
		"regionsid" => $userinfo['regionsid'],
		"fname" => $userinfo['firstname'],
		"lname" => $userinfo['lastname'],
		"iseeker" => $userinfo['iseeker'],
		"altid1" => $userinfo['altid1'],
		"corporationid" => $userinfo['corporationid'],
		"userid" => $userinfo['userid'],
    	"settings" => $settings,
    	"markets" => $markets
	);

	//p;rint to thje broswer
	print json_encode($re);
?>