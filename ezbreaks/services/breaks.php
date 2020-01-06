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


	if($event == "networks"){
		$sql = "SELECT zonenetworks.networkid AS id, tms_networks.callsign, tms_networks.name AS fullname
		FROM zones
		INNER JOIN zonenetworks ON zonenetworks.zoneid = zones.id
		INNER JOIN tms_networks ON tms_networks.networkid = zonenetworks.networkid
		WHERE corporationid = $corporationid
		GROUP BY zonenetworks.networkid
		ORDER BY tms_networks.callsign";

		$result = mysql_query($sql);

	    //loop over and add to list
	    while($row = mysql_fetch_assoc($result)) {
	    	$row['name'] = $row['callsign']." ".$row['fullname'];
	    	$data[] = $row;
	    }

	    //build the return
	   	$re = array("data"=>$data);

	   	//print the output
	    print json_encode($re);
		return;
	}



	if($event == "breaks"){
		mysql_select_db("ezbreaks", $con);
		$timezoneid = $_GET['tzid'];


		$sql = "SELECT * FROM breaks WHERE timezoneid = 0 ORDER BY name";
		$result = mysql_query($sql);

		//loop over and add to list
	    while($row = mysql_fetch_assoc($result)) {
	    	$fixed[] = $row;
	    }



		//$sql = "SELECT * FROM breaks WHERE timezoneid = $timezoneid ORDER BY name";
		$sql = "SELECT * FROM breaks WHERE timezoneid != 0 ORDER BY name";
		$result = mysql_query($sql);

		//loop over and add to list
	    while($row = mysql_fetch_assoc($result)) {
	    	$custom[] = $row;
	    }



	    //build the return
	   	$re = array("fixed"=>$fixed,"custom"=>$custom);

	   	//print the output
	    print json_encode($re);
		return;
	}




	if($event == "breaksfull"){
		mysql_select_db("ezbreaks", $con);


		$sql = "SELECT * FROM breaks WHERE timezoneid = 0 ORDER BY name";
		$result = mysql_query($sql);

		//loop over and add to list
	    while($row = mysql_fetch_assoc($result)) {
	    	$fixed[] = $row;
	    }



		$sql = "SELECT * FROM breaks WHERE timezoneid != 0 ORDER BY name";
		$result = mysql_query($sql);

		//loop over and add to list
	    while($row = mysql_fetch_assoc($result)) {
	    	$custom[] = $row;
	    }



	    //build the return
	   	$re = array("fixed"=>$fixed,"custom"=>$custom);

	   	//print the output
	    print json_encode($re);
		return;
	}




	if($event == "breaklist"){
		mysql_select_db("ezbreaks", $con);
		$id = $_GET['id'];

		$sql = "SELECT DISTINCT breaktime FROM breaks_items WHERE breakid = $id ORDER BY breaktime";
		$result = mysql_query($sql);
		$cnt = 0;

	    //loop over and add to list
	    while($row = mysql_fetch_assoc($result)) {
	    	$breaktime = $row["breaktime"];
	    	$sql = "SELECT id, breaktime, weekday, length, recommprogbased FROM breaks_items WHERE breakid = $id AND breaktime = '$breaktime' ORDER BY breaktime";
	    	$results = mysql_query($sql);

	    	$breakrow = array("breaktime"=>$breaktime);

	    	while($rows = mysql_fetch_assoc($results)) {
	    		$wd = $rows["weekday"];
	    		$wdname = "d".$wd;
	    		$breakrow[$wdname] = $rows["length"];
	    	}

	    	$breakrow["id"] = $cnt;
	    	$data[] = $breakrow;
	    	$cnt++;

	    }


	    //build the return
	   	$re = array("data"=>$data);

	   	//print the output
	    print json_encode($re);
		return;
	}



	




?>








