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

	//create the user events
	$user 	= new User($con,$userid,$tokenid);
	$token 	= $user->getUserToken();

	//p;rint to thje broswer
	print json_encode($token);
?>