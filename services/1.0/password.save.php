<?php
	ini_set("display_errors",1);

	$userid = $_POST['userid'];
	$authtokin = $_POST['tokenid'];

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

	//include database
	include_once('../../config/database.php');
	
	
	$password 	= $_POST['password'];

	$sql = "UPDATE users SET password='".mysql_real_escape_string($password)."' WHERE id = ".$userid." AND tokenid = '".$tokenid."'";
	mysql_query($sql);
	
	print '{"response":"ok"}';
	exit;
?>