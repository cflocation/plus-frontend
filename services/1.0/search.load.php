<?php
	ini_set("display_errors",1);

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
	include_once('../../classes/Search.php');


	//call to user class
	$user = new User($con,$userid,$tokenid);

	//call the search class so we cen get the searches
	$search = new Search($con, $userid, $tokenid);
	$searchs = $search->getsavedsearches();

	print json_encode(utf8ize($searchs));
	
	function utf8ize($d) {
	    if (is_array($d)) {
	        foreach ($d as $k => $v) {
	            $d[$k] = utf8ize($v);
	        }
	    } else if (is_string ($d)) {
	        return utf8_encode($d);
	    }
	    return $d;
	}	
	
?>