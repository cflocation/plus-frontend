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
	include_once('../../classes/User.php');
	include_once('../../classes/Proposal.php');

	//incoming data
	$id = $_GET['proposalid'];

	//call to user class
	$user = new User($con,$userid,$tokenid);
	$userinfo = $user->getuserinfo();


	//call the proposal class
	$proposal = new Proposal($con, $userid, $tokenid);
	$load = $proposal->getproposalbyid($id);


	
	if($load['proposal'] == ""){
		$data = array();
		//return;
	}else{
		$data = $load['proposal'];
	}

	$re = array(
		'id'=>$load['id'],
		'calendar'=>$load['calendar'],
		'name'=>$load['name'],
		'discountpackage'=>$load['discountpackage'],
		'discountagency'=>$load['discountagency'],
		'discountpackagetype'=>$load['discountpackagetype'],
		'proposal'=>$data,
		'weeks'=>$load['weeks'],
		'createdat'=>$load['createdat'],
		'updatedat'=>$load['updatedat'],
		'altname'=>$load['altname']
	);
	
	
	print json_encode($re);
?>