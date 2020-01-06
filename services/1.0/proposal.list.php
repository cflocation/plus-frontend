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

	//included the needed classes now that the user has been authenticated

	include_once('../../classes/User.php');
	include_once('../../classes/Proposal.php');


	//call to user class
	$user = new User($con,$userid,$tokenid);
	$userinfo = $user->getuserinfo();
	$corporationid = $userinfo['corporationid'];


	//call the proposal class file
	$proposal = new Proposal($con, $userid, $tokenid);
	$proposals = $proposal->getproposals();



	$re = array(
		"responseHeader" => array("count" => count($proposals)),
		"response" => array("proposals" => $proposals)
	);

	print json_encode($re);
?>