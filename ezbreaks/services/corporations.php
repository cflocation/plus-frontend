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
		$sql = "SELECT c.id, CONCAT(c.name, ' (', count(bgi.id),')') AS name 
		FROM ShowSeeker.corporations AS c 
		LEFT OUTER JOIN ezbreaks.breakgroups AS bg ON bg.corporationid=c.id
		LEFT OUTER JOIN ezbreaks.breakgroups_items AS bgi ON bgi.breakgroupsid=bg.id

		WHERE c.ezbreaks = 1
		GROUP BY c.id
		ORDER BY c.name";

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


?>