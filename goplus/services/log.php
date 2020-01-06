<?php
//	date_default_timezone_set('America/Los_Angeles');
	include_once('../../config/database.php');
	
	$userid = $_POST['userid'];
	$eventslogid = $_POST['eventslogid'];
	$request = $_POST['request'];
	$result = $_POST['result'];
	if( isset($_POST['proposalid']) ){
		$proposalid = $_POST['proposalid'];	
	}
	else{
		$proposalid = '1';		
	}
	
	$d = date('Y-m-d H:i:s');


	//LOG EVENT
	mysql_select_db("logs", $con);
	$req = mysql_real_escape_string($request);
	$sql = "INSERT INTO logs.eventlogs (userid,eventslogid,request,result,proposalid,createdat, updatedat)VALUES ({$userid}, {$eventslogid},'{$req}','{$result}','{$proposalid}','{$d}','{$d}')";
	$result = mysql_query($sql);
	print_r($result);
?>