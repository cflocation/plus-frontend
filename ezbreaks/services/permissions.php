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
		$id = $_GET['id'];
		$sql = "SELECT id, name FROM permissionbreaks WHERE corporationid = $id ORDER BY name";

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




	if($event == "saveaccesschanges"){
		$id = $_POST['id'];
		$list = $_POST['list'];
		$list = implode(",", $list);
		$sql = "UPDATE permissionbreaks SET networkinstances = '$list', updatedat = '$d' WHERE id = $id";
		$result = mysql_query($sql);
		return;
	}



	if($event == "getselectednetworks"){
		$id = $_GET['id'];
		$sql = "SELECT networkinstances FROM permissionbreaks WHERE id = $id LIMIT 1";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		$networkinstances = explode(',',$row['networkinstances']);

		print json_encode($networkinstances);
		return;
	}



	if($event == "networklist"){
		$id = $_GET['id'];
		mysql_select_db("ezbreaks", $con);
		$sql = "SELECT 
		breaks.name AS breakname,
		breaks.id AS breakid,
		breakgroups.name AS breakgroup,
		breakgroups_items.id,
		breakgroups_items.tmsid,
		breakgroups_items.breakid,
		breakgroups_items.instancecode,
		breakgroups_items.timezoneid,
		timezones.name AS tz,
		timezones.abbreviation AS abbreviation,
		tms_networks.name,
		tms_networks.callsign,
		IFNULL(logos.filename,'default.gif') AS filename
		FROM breakgroups
		INNER JOIN breakgroups_items ON breakgroups_items.breakgroupsid = breakgroups.id
		INNER JOIN ShowSeeker.timezones ON ShowSeeker.timezones.id = breakgroups_items.timezoneid
		INNER JOIN ShowSeeker.tms_networks ON ShowSeeker.tms_networks.networkid = breakgroups_items.tmsid
        LEFT JOIN breaks ON breaks.id = breakgroups_items.breakid
		LEFT JOIN ShowSeeker.networklogos ON ShowSeeker.networklogos.networkid = breakgroups_items.tmsid
		LEFT JOIN ShowSeeker.logos ON ShowSeeker.logos.id = ShowSeeker.networklogos.logoid
		WHERE breakgroups.corporationid = $id AND breakgroups_items.deletedat IS NULL
		ORDER BY breakgroups_items.instancecode";

		$result = mysql_query($sql);

	    //loop over and add to list
	    while($row = mysql_fetch_assoc($result)) {
	    	$row['tzname'] = $row['tz']." (".$row['abbreviation'].")";
	    	$data[] = $row;
	    }

	    $cnt = mysql_num_rows($result);
	    if($cnt == 0){
	    	$data = array();
	    }


	    //build the return
	   	$re = array("data"=>$data);

	   	//print the output
	    print json_encode($re);
		return;
	}





?>