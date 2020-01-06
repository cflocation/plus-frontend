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



	/*
		Create daypart
	*/
	if($event == "createdaypart"){
		$daypartid = $_POST['daypartid'];
		$name = $_POST['name'];
		$starttime = $_POST['starttime'];
		$endtime = $_POST['endtime'];
		$days = $_POST['days'];
		
		sort($days);
		$dayslist = implode(",", $days);

		//see if that record exists
		$sql = "SELECT * FROM ratecard_dayparts WHERE starttime = '$starttime' AND endtime = '$endtime' AND days = '$dayslist' LIMIT 1";
		$result = mysql_query($sql);
		$cnt = mysql_num_rows($result);

		if($cnt > 0){
			$row = mysql_fetch_assoc($result);
			print json_encode($row);
			return;
		}

		//log event
		$sql = "INSERT INTO ratecard_logs (eventid, userid) VALUES (2,$userid)";
		mysql_query($sql);

		$sql = "INSERT INTO ratecard_dayparts (starttime, endtime, days, createdat, updatedat) VALUES ('$starttime','$endtime','$dayslist','$d','$d')";
		mysql_query($sql);
		$id = mysql_insert_id();

		$sql = "SELECT * FROM ratecard_dayparts WHERE id = $id";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		print json_encode($row);
		return;
	}



	/*
		List dayparts
	*/
	if($event == "listdayparts"){

		$sql = "SELECT * FROM ratecard_dayparts ORDER BY days, starttime, endtime";

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


	/*
		Copy dayparts
	*/
	if($event == "copydayparts"){
		$ids = $_POST['ids'];
		$rows = $_POST['rows'];
		$rowsarr = json_decode($rows);


		//loop over the ids to look them up in the database
		foreach ($ids as &$value) {
			$marketid = $value;
			$sql = "SELECT * FROM ratecard_market_dayparts WHERE marketid = $marketid";
			$result = mysql_query($sql);
			$cnt = mysql_num_rows($result);
			if($cnt == 0){
				$sql = "INSERT INTO ratecard_market_dayparts (marketid, dayparts, createdat, updatedat) VALUES ('$marketid','$rows','$d','$d')";
				mysql_query($sql);
			}else{
				$row = mysql_fetch_assoc($result);
				$rowdayparts = json_decode($row['dayparts']);

				//loop over the rows passed into copy
				foreach ($rowsarr as &$daypart) {
					//set the id for the current passedin id
					$id = $daypart->id;
					//find out if this has the 
					$dupe = findDaypart($rowdayparts,$id);

					if($dupe == false){
						array_push($rowdayparts, $daypart);
					}
				}

				$data = json_encode($rowdayparts);
				$sql = "UPDATE ratecard_market_dayparts SET dayparts='$data',updatedat='$d' WHERE marketid=$marketid";
				mysql_query($sql);
			}
		}
		return;
	}


	function findDaypart($rowdayparts,$id){

		foreach ($rowdayparts as &$daypart) {
			if($daypart->id == $id){
				return true;
			}
		}
		return false;
	}


?>


