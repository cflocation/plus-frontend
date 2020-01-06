<?php
	session_start();
	ini_set("display_errors",0);

	//session ids
	$corporationid = $_SESSION['corporationid'];
	$userid = $_SESSION['userid'];


	//if the corporation id is not set return
	if(!isset($corporationid)){
		return 0;
	}

	if(!isset($userid)){
		return 0;
	}

	//are we posting or getting the event type
	if(isset($_GET['eventtype'])){
		$event = $_GET['eventtype'];
	}else{
		$event = $_POST['eventtype'];
	}


	//include database
	include_once('../../config/database.php');


	if($event == "title"){
		$str = $_GET['str'];

		//mysql connector
		$con = mysql_connect("2b639ef8b778f163dc465bf92d7b7fa6c3763a8f.rackspaceclouddb.com","showseeker","truV5WAp");
		if (!$con){
			die('Could not connect: ' . mysql_error());
		}
		//select our database
		mysql_select_db("catalog", $con);

		$sql = "SELECT title, md5(title) AS id FROM titles WHERE title LIKE '%$str%' GROUP BY title ORDER BY title LIMIT 250";

		$result = mysql_query($sql);

		$cnt = mysql_num_rows($result);

		if($cnt == 0){
	    	print 0;
			return;
		}

		//loop over and add to list
	    while($row = mysql_fetch_assoc($result)) {
	        $data[] = $row;
	    }

	    $re = json_encode($data);
	    print $re;

		return;
	}

?>