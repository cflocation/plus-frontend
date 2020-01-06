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


	$rows = array();

	$sql = "SELECT
	proposals.id, 
	proposals.name, 
	proposals.total, 
	proposals.zones AS zone, 
	proposals.linesttl, 
	proposals.spots, 
	proposals.proposal,
	proposals.discountid,
	proposals.grossttl, 
	proposals.netttl, 
	proposals.amount,
	proposals.startdate AS fstart,
	proposals.enddate AS fend,
	proposals.createdat AS created,
	DATE_FORMAT(proposals.updatedat, '%m/%d/%Y %h:%i %p') AS updatedat
	FROM proposals 
	WHERE proposals.userid = $userid AND proposals.deletedat IS NULL
	ORDER BY proposals.createdat DESC";
	
	$result = mysqli_query($con, $sql);

	//call the proposal class file
	#$proposal = new Proposal($con, $userid, $tokenid);
	#$proposals = $proposal->getproposals();


	while ($row = $result->fetch_array()) {
		$stats = parseJsonToStats($row['proposal']);
		
		$r = array();
		
		$r['id'] = $row['id'];
		//$r['name'] = urldecode($row['name']);
		$r['name'] = utf8_encode(urldecode($row['name']));		
		$r['spots'] = $stats['spots'];
		$r['linesttl'] = $stats['lines'];
		$r['total'] = $stats['total'];
		$r['zone'] = $stats['zone'];
		$r['fstart'] = $stats['fstart'];
		$r['fend'] = $stats['fend'];
		$r['created'] = $row['created'];
		$r['updatedat'] = $row['updatedat'];
		$r['netttl'] = $stats['total'];
		
		
		//print_r($r);
		
    	array_push($rows,$r);
	}
	
	//row count
	$ttl = $result->num_rows;


	
	$re = array(
		"responseHeader" => array("count" => $ttl),
		"response" => array("proposals" => $rows)
	);

	print json_encode($re);





	function parseJsonToStats($data){
		$proposal = json_decode($data);

		$spots = 0;
		$lines = 0;
		$total = 0;
		$zones = array();
		$dates = array();
		
		foreach ($proposal as &$value) {

			$total += $value->rate*$value->spots;
			$spots += $value->spots;
			$lines += 1;
			

			$x = date('Ymd', strtotime($value->startdate));
			$xl = date('m/d/Y', strtotime($value->startdate));


			$xe = date('Ymd', strtotime($value->enddate));
			$xle = date('m/d/Y', strtotime($value->enddate));

			$dates[$x] = $xl;
			$dates[$xe] = $xle;


			array_push($zones,$value->zone);
		}
		
		$zones1 = array_unique($zones);
		
		asort($zones1);
		
		$z = implode(", ", $zones1);
		
	
		ksort($dates);
	

		$ttl = count($dates);

		if($ttl == 0){
			$fstart = '';
			$fend = '';
		}else{
			$fstart = current($dates);
			$fend = end($dates);
		}

		$re = array("spots" => $spots,"lines" => $lines,"total" => $total,"zone" => $z, "fstart" => $fstart, "fend" => $fend);
		return $re;
	}



?>