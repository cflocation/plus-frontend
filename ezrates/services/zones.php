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
	//include_once('../../config/database.php');
	include_once('../include/database.php');


	if($event == "list"){
		$sql = "SELECT Zone.name AS zone, MarketZone.marketId AS marketid, Market.name AS market, Zone.isDMA AS isdma, MD5(CONCAT(Zone.name,MarketZone.marketId)) AS id
					FROM MarketZone
					INNER JOIN Zone ON Zone.id = MarketZone.zoneId
					INNER JOIN Market ON Market.id = MarketZone.marketId
					WHERE Zone.deletedAt IS NULL AND Zone.corporationId = $corporationid AND Zone.isDMA = 'NO'
					ORDER BY Zone.name,Market.name";

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

		$sql = "SELECT  Network.callSign AS callsign, Network.name, Network.networkId AS id
				FROM ZoneNetwork 
				INNER JOIN Network ON Network.networkId = ZoneNetwork.networkId
				WHERE zoneId = $zoneid
				ORDER BY Network.callSign";

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
		$ratecardid = $_POST['ratecardid'];


		$sql = "SELECT * FROM RateCardsHot WHERE ratecardid = $ratecardid";
		$result = mysql_query($sql);

		$cnt = mysql_num_rows($result);

		if($cnt == 0){
			$sql = "INSERT INTO RateCardsHot (programs, ratecardid, createdat, updatedat) VALUES ('$programs',$ratecardid,'$d','$d')";
		}else{
			$sql = "UPDATE RateCardsHot SET programs = '$programs' WHERE ratecardid = $ratecardid";
		}

		
		$result = mysql_query($sql);

		print_r($programs);

		return;
	}




