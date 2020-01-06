<?php
	session_start();
	ini_set("display_errors",1);

	//session ids
	$corporationid = $_SESSION['corporationid'];
	$userid = $_SESSION['userid'];


	//if the corporation id is not set return
	if(!isset($corporationid)){
		return 0;
	}

	//are we posting or getting the event type
	if(isset($_GET['eventtype'])){
		$event = $_GET['eventtype'];
	}else{
		$event = $_POST['eventtype'];
	}

	//set the global date for inset update delete
	$d = date('Y-m-d H:i:s');


	//include database
	include_once('../../config/database.php');

	if($event == "list"){
		mysql_select_db("ezbreaks", $con);

		$sql = "SELECT message_id AS id, emailfrom, indate, subject, deleted, finished 
		FROM programtracker
		WHERE deleted = 0 ORDER BY indate DESC";

		$result = mysql_query($sql);

	    //loop over and add to list
	    while($row = mysql_fetch_assoc($result)) {
	    	$data[] = $row;
	    }

	    //build the return
	   	$re = array("data"=>$data);

	   	//print the output
	    print json_encode($re);

		return;
	}

	if($event == "markcomplete"){
		$ids = $_POST['ids'];

		$sql =  "UPDATE ezbreaks.programtracker SET finished = '1' WHERE message_id IN ('".implode("','",$ids)."')";
		$result = mysql_query($sql);
		return;
	}

	if($event == "delete"){
		$ids = $_POST['ids'];

		$sql =  "UPDATE ezbreaks.programtracker SET deleted = '1' WHERE message_id IN ('".implode("','",$ids)."')";
		$result = mysql_query($sql);
		return;
	}

	if($event == "getemaildetails"){
		$emailid = $_POST['id'];

		$sql = "SELECT * FROM ezbreaks.programtracker WHERE message_id = '$emailid' ";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);

		
		$row['body'] = htmlentities($row['body']);
		preg_match('~<([^>]+)>~',$row['emailfrom'],$match);
		
		print_r($match);
		exit;
		$row['emailfrom'] = $match[1];
		
		print json_encode($row);
		return;
	}

	if($event == "sendemail"){
		print "<pre>";
		
		$sendTo = urldecode($_POST['sendto']);
		$subject = urldecode($_POST['subject']);
		$content = urldecode($_POST['content']);

		$matches = array();
		$emails = array();
  		$pattern = '/[A-Za-z0-9_-]+@[A-Za-z0-9_-]+\.([A-Za-z0-9_-][A-Za-z0-9_]+)/';
   		preg_match_all($pattern, $sendTo, $matches);
   		foreach ($matches as $m) {
   			var_dump(filter_var($m[0], FILTER_VALIDATE_EMAIL));
   			print_r($m);
   			print "<hr/>";
   		}

		
	}

	if($event == "getcontent"){
		$emailid = $_POST['emailid'];

		$sql = "SELECT * FROM ezbreaks.programtracker WHERE message_id = '$emailid' ";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);

		$body  = $row['body'];
		//$body = str_replace("=", "", $body);
		$body = str_replace("\r", '', $body); // remove new lines
		$body = str_replace("\n", '', $body); // remove new lines

		$header  = "<p><b>From</b>: {$row['emailfrom']}<br/>";
		$header .= "<b>Date</b>: {$row['indate']}<br/>";
		$header .= "<b>Subject</b>: {$row['subject']}</p><hr/>";
		
		echo $header.$body;
		return;
	}

?>