<?php
	include_once('../../inc/permissions.php');

	//are we posting or getting the event type
	if(isset($_GET['eventtype'])){
		$event = $_GET['eventtype'];
	}else{
		$event = $_POST['eventtype'];
	}

	//set the global date for inset update delete
	$d = date('Y-m-d H:i:s');




	if($event == "list"){
		

		if(isRole($roles,17)){
			$sql = "SELECT regions.name, regions.id FROM regions WHERE regions.deletedat IS NULL AND regions.corporationid = ".$corporationid." ORDER BY name";
		}else{
			$sql = "SELECT regions.name, regions.id
			FROM officedefaults
			INNER JOIN offices ON offices.id = officedefaults.officeid
			INNER JOIN regions ON regions.id = offices.regionid
			WHERE regions.deletedat IS NULL AND officedefaults.userid = $userid AND regions.corporationid = $corporationid
			ORDER BY name";
		}

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







	if($event == "getzonesformarket"){
		$marketid = $_POST['marketid'];

		$sql = "SELECT dayparts FROM ratecard_market_dayparts WHERE marketid = $marketid AND deletedat IS NULL";
		$result = mysql_query($sql);
		$cnt = mysql_num_rows($result);
		$row = mysql_fetch_assoc($result);


		if($cnt == 0 || $row['dayparts'] == '[]'){
			print 0;
			return;
		}

		$sql = "SELECT zones.name AS zone, zones.id AS id
		FROM marketzones
		INNER JOIN zones ON marketzones.zoneid = zones.id
		WHERE marketzones.marketid = $marketid AND zones.deletedat IS NULL AND zones.isdma = 'NO' AND zones.id IN (SELECT zoneid FROM ratecard_access)
		ORDER BY zones.name";

		$result = mysql_query($sql);

		//loop over and add to list
	    while($row = mysql_fetch_assoc($result)) {
	    	$data[] = $row;
	    }

	    print json_encode($data);
		return;
	}








	if($event == "updatedayparts"){
		$dayparts = $_POST['dayparts'];
		$marketid = $_POST['marketid'];

		$sql = "UPDATE ratecard_market_dayparts SET dayparts='$dayparts',updatedat='$d' WHERE marketid=$marketid";
		$result = mysql_query($sql);
		print 'sss';
		return;
	}








	if($event == "marketdayparts"){
		$marketid = $_GET['marketid'];
		$sql = "SELECT dayparts FROM ratecard_market_dayparts WHERE marketid = $marketid";

		$result = mysql_query($sql);
		$cnt = mysql_num_rows($result);

		if($cnt == 0){
			$sql = "INSERT INTO ratecard_market_dayparts (marketid, dayparts, createdat, updatedat) VALUES ('$marketid','[]','$d','$d')";
			$result = mysql_query($sql);
			
			$re = array("data"=>'[]');
			print json_encode($re);
			return;
		}

		$row = mysql_fetch_assoc($result);
		$re = array("data"=>$row['dayparts']);
		print json_encode($re);
		return;
	}





	//add new daypart to market
	if($event == "marketdaypartadd"){
		$daypartid = $_POST['daypartid'];
		$marketid = $_POST['marketid'];

		//query the database to get the selected daypart
		$sql = "SELECT starttime, endtime, days FROM ratecard_dayparts WHERE id = $daypartid";
		$result = mysql_query($sql);
		$cnt = mysql_num_rows($result);
		
		//escape if no record is found
		if($cnt == 0){
			print 0;
			return;
		}

		//get the row for the daypart
		$row = mysql_fetch_assoc($result);

		//set the varibles to insert and check for duplicates
		$starttime = $row['starttime'];
		$endtime = $row['endtime'];
		$days = $row['days'];
		
		//check for the duplcate record
		$duplicate = duplicateCheck($starttime,$endtime,$days,$marketid);

		if($duplicate == 0){
			$sql = "INSERT INTO ratecard_market_dayparts (marketid, starttime, endtime, days, createdat, updatedat) VALUES ('$marketid','$starttime','$endtime','$days','$d','$d')";
			$result = mysql_query($sql);
			print 1;
			return;
		}

		print 0;
		return;
	}





	//get selected daypart
	if($event == "getselecteddaypart"){
		$daypartid = $_GET['daypartid'];

		$sql = "SELECT * FROM ratecard_dayparts WHERE id = $daypartid";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);

		$re = array("data"=>$row);
		print json_encode($re);
		return;
	}



	function duplicateCheck($starttime,$endtime,$days,$marketid){
		$sql = "SELECT starttime, endtime, days FROM ratecard_market_dayparts WHERE starttime = '$starttime' AND endtime = '$endtime' AND days = '$days' AND marketid = $marketid";
		$result = mysql_query($sql);
		$cnt = mysql_num_rows($result);
		return $cnt;
	}


?>








