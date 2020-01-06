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

	//include the classes
	include_once('../../classes/User.php');
	include_once('../../classes/On.php');


	$showid = $_GET['id'];
	$showtype = substr($showid, 0, 2);


	//call to user class
	$user = new User($con,$userid,$tokenid);
	$userinfo = $user->getuserinfo();

	//call the on class
	$on = new On($con, $userid, $tokenid);

	$images = $on->getImageSizeShowID($showid);

	$array = array("images" => $images);
	$re = json_encode($array);
	print $re;
?>