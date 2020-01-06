<?php
	session_start();
	ini_set("display_errors",1);
	header('Content-type: text/html');
	//session ids
	$corporationid = $_SESSION['corporationid'];
	$userid = $_SESSION['userid'];


	//if the corporation id is not set return
	if(!isset($corporationid)){
		return 0;
	}

	//are we posting or getting the event type
	if(isset($_GET['id'])){
		$id = $_GET['id'];
	}else{
		$id = $_POST['id'];
	}

	//set the global date for inset update delete
	$d = date('Y-m-d H:i:s');


	//include database
	include_once('../config/database.php');
	mysql_select_db("ezbreaks", $con);


	$sql = "SELECT * FROM programtracker WHERE message_id = '$id' ";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);

	$body = $row['body'];
	//$body = str_replace("=", "", $body);
	$body = str_replace("\r", '', $body); // remove new lines
	$body = str_replace("\n", '', $body); // remove new lines
	echo $body;




?>
