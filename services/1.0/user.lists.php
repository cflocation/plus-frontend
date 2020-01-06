<?php
	ini_set("display_errors",0);

	$userid = $_GET['userid'];
	$authtokin = $_GET['tokenid'];
	$type = $_GET['type'];


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

	//included the needed classes now that the user has been authenticated
	include_once('../../classes/User.php');

	//call to user class
	$user 			= new User($con,$userid,$tokenid);
	$userinfo 		= $user->getuserinfo();
	$corporationid 	= $userinfo['corporationid'];

	$re = array();

	//type office
	if($type == 'office'){
		$re = $user->getUsersByOffice();
	}

	if($type == 'market'){
		$re = $user->getUsersByMarket();
	}


	if($type == 'corporation'){
		$re = $user->getUsersByCorporation();
	}

	print json_encode($re);
?>