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

	//include the classes
	include_once('../../classes/User.php');
	include_once('../../classes/Proposal.php');

	//posted rows
	$rows = $_POST['rows'];

	//call to user class
	$user = new User($con,$userid,$tokenid);
	$userinfo = $user->getuserinfo();


	//call the proposal class file
	$proposal = new Proposal($con, $userid, $tokenid);
	$remove = $proposal->deleteproposals($rows);

	print 1;
?>