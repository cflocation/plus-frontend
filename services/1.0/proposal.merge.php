<?php
	session_start();
	ini_set("display_errors",1);

	//set the userid and the token id
	$userid			= $_POST['userid'];
	$authtokin 		= $_POST['tokenid'];
	$proposalname 	= $_POST['proposalname'];	

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

	
	//include database
	include_once('../../config/database.php');
	
	//posted
	$rows 	= $_POST['rows'];
	$arr 	= json_decode($rows);
	$ids 	= join(',',$arr);  	
	$title 	= '';
	$data 	= array();
	$dtime 	= date('Y-m-d H:i:s');
	$cal	= 1;
	
	
	$sql = "SELECT * FROM proposals WHERE id IN ($ids)";
	$result = mysql_query($sql);
	

	while($row = mysql_fetch_array($result))
  	{
  		$title .= $row['name'].' | ';
  		$z = json_decode($row['proposal']);
  		
		if($row['createdat'] < '2015-12-07 00:00:00'){
			$cal	= 0;
		}
  		
  		foreach ($z as &$value) {
  			$d = fArray($data, $value->id);
  			
  			if($d == 0){
  				array_push($data, $value);
  			}
  
		}	
  	}
  	
  	
  	$proposal = json_encode($data);
	$proposal = mysql_real_escape_string($proposal);
	//$title .= 'MERGED';
	$title = mysql_real_escape_string($proposalname);
	
	
	//$sql = "INSERT INTO proposals (userid, name, proposal, createdat, updatedat)VALUES ({$userid}, '{$title}','{$proposal}','{$dtime}','{$dtime}')";
	$sql = "INSERT INTO proposals (userid, name, proposal, calendar, createdat, updatedat)VALUES ({$userid}, '{$title}','{$proposal}','{$cal}','{$dtime}','{$dtime}')";
	mysql_query($sql);

	$re = mysql_insert_id();
	print $re;


	//LOG EVENT
	mysql_select_db("logs", $con);
	$sql = "INSERT INTO eventlogs (userid,eventslogid,request,result,createdat, updatedat)VALUES ({$userid}, 47,'{$rows}','{$re}','{$dtime}','{$dtime}')";
	mysql_query($sql);
	
	
	function fArray($arr,$key){
	
		if(count($arr) == 0){
			return 0;
		}
	
		foreach ($arr as &$value) {
			if($value->id == $key){
				return 1;
			}
		}
		
		return 0;	
	}
?>