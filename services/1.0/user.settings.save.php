<?php
	ini_set("display_errors",1);

	$userid = $_POST['userid'];
	$authtokin = $_POST['tokenid'];

	//posted settings
	$settings = $_POST['settings'];

	//if there is anything blank return an error
	if(empty($authtokin) || empty($userid)){
		exit('error');
	}

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


	//get the user call file
	require_once('../../classes/User.php');

	//create the user events
	$user = new User($con,$userid,$tokenid);
	$save = $user->saveusersettings($settings);
	
	print json_encode($save);
?>