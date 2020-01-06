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
		$sql = "SELECT zones.name AS zone, marketzones.marketid, regions.name AS market, zones.isdma, MD5(CONCAT(zones.name,marketzones.marketid)) AS id
		FROM marketzones
		INNER JOIN zones ON zones.id = marketzones.zoneid
		INNER JOIN regions ON regions.id = marketzones.marketid
		WHERE zones.deletedat IS NULL AND zones.corporationid = $corporationid AND zones.isdma = 'NO'
		ORDER BY zones.name,regions.name";

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



	if($event == "zonenetworks"){
		$zoneid = $_GET['zoneid'];

		$sql = "SELECT  tms_networks.callsign, tms_networks.name, tms_networks.networkid AS id
		FROM zonenetworks 
		INNER JOIN tms_networks ON tms_networks.networkid = zonenetworks.networkid
		WHERE zoneid = $zoneid
		ORDER BY tms_networks.callsign";
	

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



	if($event == "savehotprogramming"){
		$programs = mysql_real_escape_string($_POST['programs']);
		$zoneid = $_POST['zoneid'];



		$sql = "SELECT * FROM ratecards_hot WHERE zoneid = $zoneid";
		$result = mysql_query($sql);

		$cnt = mysql_num_rows($result);

		if($cnt == 0){
			$sql = "INSERT INTO ratecards_hot (programs, zoneid, createdat, updatedat) VALUES ('$programs',$zoneid,'$d','$d')";
		}else{
			$sql = "UPDATE ratecards_hot SET programs = '$programs' WHERE zoneid = $zoneid";
		}

		
		$result = mysql_query($sql);

		print_r($programs);

		return;
	}

?>








