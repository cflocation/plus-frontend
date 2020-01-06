<?php
	ini_set("display_errors",1);

	//set the userid and the token id
	$userId = $_GET['userId'];
	$apiKey = $_GET['apiKey'];



	//if there is anything blank return an error
	if(empty($apiKey) || empty($userId)){
		exit('error');
	}

	//include the datbase file for the authentication and other services
	include_once('../../config/mysqli.php');

	$tokenid = checkapikey($con,$userId,$apiKey);



	//if the auth is correct keep moving on
	require_once('../../classes/User.php');
	require_once('../../classes/Zones.php');

	//create the user events
	$user = new User($con,$userId,$tokenid);
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
    	"markets" => $markets,
    	"tokenid" => $tokenid,
	);

	//p;rint to thje broswer
	print json_encode($re);



function checkapikey($con,$userId,$apiKey){
	$sql    = "SELECT id, tokenid FROM users WHERE id = $userId AND MD5(CONCAT(id,tokenid)) = '$apiKey'";
	$result = mysqli_query($con, $sql);
	$row    = $result->fetch_assoc();
	return $row['tokenid'];
}