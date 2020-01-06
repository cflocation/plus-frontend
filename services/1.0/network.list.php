<?php
	ini_set("display_errors",0);

	$userid = $_GET['userid'];
	$authtokin = $_GET['tokenid'];
	$zoneid = $_GET['zoneid'];

	//if there is anything blank return an error
	if(empty($authtokin) || empty($userid) || empty($zoneid)){
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
	include_once('../../classes/Zones.php');


	//call to user class
	$user = new User($con,$userid,$tokenid);
	$userinfo = $user->getuserinfo();
	$corporationid = $userinfo['corporationid'];


	//call teh zone class file
	$zone = new Zones($con, $userid, $tokenid, $corporationid);
	$zoneinfo = $zone->getzoneinfo($zoneid);
	$networks = $zone->getzonenetworks($zoneid);


	//response header
	$responseHeader = array(
		"count" => count($networks),
		"zoneid" => (int)$zoneinfo['id'],
		"zonename" => $zoneinfo['zonename'],
		"syscode" => $zoneinfo['syscode'],
		"broadcast" => $zoneinfo['broadcast'],
		"dmaid" => $zoneinfo['dmaid'],
		"userid" => (int)$userid,
		"tokenid" => $tokenid,
		"tzid" => $zoneinfo['tzid'],
		"tzname" => $zoneinfo['tzname'],
    	"tzabbreviation" => $zoneinfo['tzabbreviation']
	);


	//loop over the networks
	foreach ($networks as &$row) {
    	$network = array(
			"name" => $row['name'],
			"callsign" => $row['callsign'],
			"id" => $row['id'],
			"timnezoneid" => $zoneinfo['tzid'],
			"logos" => "/plus/i/thumbnails/".$row['filename']
		);

		$networklist[] = $network; 
	}


	$re = array("responseHeader"=>$responseHeader,"response"=>array("networks"=>$networklist));
	header('Content-Type: application/json');
	print json_encode($re);
?>