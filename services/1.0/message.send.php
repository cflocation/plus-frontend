<?php
	ini_set("display_errors",0);

	$userid = $_GET['userid'];
	$authtokin = $_GET['tokenid'];

	//if there is anything blank return an error
	if(empty($authtokin) || empty($userid)){
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


	//posted
	$items = json_decode($_POST['items']);
	$users = json_decode($_POST['users']);
	$message = $_POST['message'];
	$sharetype = $_POST['sharetype'];


	//included the needed classes now that the user has been authenticated
	include_once('../../classes/Message.php');

	//call to user class
	if($sharetype == "Proposal"){
		$share = new Message($con,$userid,$tokenid);
		$share = $share->sendproposals($items,$sharetype,$message,$users);
	}
	else{
		$share = new Message($con,$userid,$tokenid);
		$share = $share->sendsearch($items,$sharetype,$message,$users);	
	}
?>
