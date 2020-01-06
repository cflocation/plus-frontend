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
		mysql_select_db("ezbreaks", $con);
		$sql = "SELECT id, name FROM breakgroups WHERE corporationid = $corporationid ORDER BY name";

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



	if($event == "breaktypes"){
		mysql_select_db("ezbreaks", $con);
		$sql = "SELECT id, name FROM breaktypes ORDER BY id";

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




	if($event == "timezones"){
		$sql = "SELECT id, name AS tzname, abbreviation FROM timezones ORDER BY name";

		$result = mysql_query($sql);

	    //loop over and add to list
	    while($row = mysql_fetch_assoc($result)) {
	    	$row['name'] = $row['tzname']." (".$row['abbreviation'].")";
	    	$data[] = $row;
	    }

	    //build the return
	   	$re = array("data"=>$data);

	   	//print the output
	    print json_encode($re);
		return;
	}







	if($event == "networks"){
		$sql = "SELECT tms_networks.networkid AS id, tms_networks.callsign, tms_networks.name AS longname
		FROM tms_networks
		WHERE tms_networks.dmanumber = 0
		GROUP BY tms_networks.networkid
		ORDER BY tms_networks.callsign";

		
		$sql = "SELECT tms_networks.networkid AS id, tms_networks.callsign, tms_networks.name AS longname
		FROM zonenetworks
		INNER JOIN tms_networks ON tms_networks.networkid = zonenetworks.networkid
		WHERE tms_networks.dmanumber = 0
		GROUP BY tms_networks.networkid
		ORDER BY tms_networks.callsign";


		$result = mysql_query($sql);

	    //loop over and add to list
	    while($row = mysql_fetch_assoc($result)) {
	    	$row['name'] = $row['callsign']." - ".$row['longname'];
	    	$data[] = $row;
	    }

	    //build the return
	   	$re = array("data"=>$data);

	   	//print the output
	    print json_encode($re);
		return;
	}


	if($event == "deletenetworks"){
		mysql_select_db("ezbreaks", $con);
		$ids = $_POST['ids'];
		$idlist = implode(',', $ids);

		$sql = "DELETE FROM breakgroups_items WHERE id IN ($idlist)";
		$result = mysql_query($sql);

		print 1;
		return;
	}




	if($event == "grouplist"){
		$id = $_GET['id'];

		$allowedInstances = getUsersAllowedInstanceIds($userid);

		mysql_select_db("ezbreaks", $con);
		if(intval($id) > 0){
			$sql = "SELECT 
						breaks.name AS breakname,
						breaks.id AS breakid,
						breakgroups.name AS breakgroup,
						breakgroups_items.id,
						breakgroups_items.tmsid,
						breakgroups_items.breakid,
						breakgroups_items.instancecode,
						breakgroups_items.timezoneid,
						IF(breakgroups_items.livegrouping='N','No','Yes') AS livegrouping,
						timezones.name AS tz,
						timezones.abbreviation AS abbreviation,
						tms_networks.name,
						tms_networks.callsign,
						IFNULL(networkcommonname.commonname,tms_networks.name) AS commonname,
						IFNULL(logos.filename,'default.gif') AS filename
						FROM breakgroups
						INNER JOIN breakgroups_items ON breakgroups_items.breakgroupsid = breakgroups.id
						LEFT JOIN networkcommonname ON networkcommonname.tmsid = breakgroups_items.tmsid
						LEFT JOIN ShowSeeker.timezones ON ShowSeeker.timezones.id = breakgroups_items.timezoneid
						INNER JOIN ShowSeeker.tms_networks ON ShowSeeker.tms_networks.networkid = breakgroups_items.tmsid
				        LEFT JOIN breaks ON breaks.id = breakgroups_items.breakid
						LEFT JOIN ShowSeeker.networklogos ON ShowSeeker.networklogos.networkid = breakgroups_items.tmsid
						LEFT JOIN ShowSeeker.logos ON ShowSeeker.logos.id = ShowSeeker.networklogos.logoid
						WHERE breakgroups.id = $id AND breakgroups_items.deletedat IS NULL
						ORDER BY commonname, timezones.name";
		} else {
			$sql = "SELECT 
						breaks.name AS breakname,
						breaks.id AS breakid,
						breakgroups.name AS breakgroup,
						breakgroups_items.id,
						breakgroups_items.tmsid,
						breakgroups_items.breakid,
						breakgroups_items.instancecode,
						breakgroups_items.timezoneid,
						IF(breakgroups_items.livegrouping='N','No','Yes') AS livegrouping,
						timezones.name AS tz,
						timezones.abbreviation AS abbreviation,
						tms_networks.name,
						tms_networks.callsign,
						IFNULL(networkcommonname.commonname,tms_networks.name) AS commonname,
						IFNULL(logos.filename,'default.gif') AS filename
						FROM breakgroups
						INNER JOIN breakgroups_items ON breakgroups_items.breakgroupsid = breakgroups.id
						LEFT JOIN networkcommonname ON networkcommonname.tmsid = breakgroups_items.tmsid
						LEFT JOIN ShowSeeker.timezones ON ShowSeeker.timezones.id = breakgroups_items.timezoneid
						INNER JOIN ShowSeeker.tms_networks ON ShowSeeker.tms_networks.networkid = breakgroups_items.tmsid
				        LEFT JOIN breaks ON breaks.id = breakgroups_items.breakid
						LEFT JOIN ShowSeeker.networklogos ON ShowSeeker.networklogos.networkid = breakgroups_items.tmsid
						LEFT JOIN ShowSeeker.logos ON ShowSeeker.logos.id = ShowSeeker.networklogos.logoid
						WHERE breakgroups.corporationid = $corporationid AND breakgroups_items.deletedat IS NULL
						ORDER BY commonname, timezones.name";
		}


		$result = mysql_query($sql);

	    //loop over and add to list
	    while($row = mysql_fetch_assoc($result)) {
	    	
	    	if(!(in_array($row['id'], $allowedInstances))) continue;

	    	$row['tzname'] = $row['tz']." (".$row['abbreviation'].")";
	    	$data[] = $row;
	    }

	    $cnt = mysql_num_rows($result);
	    if($cnt == 0){
	    	$data = array();
	    }


	    //build the return
	   	$re = array("data"=>$data);

	   	/*print "<pre>";
	   	print_r($re);
	   	exit;*/

	   	//print the output
	   	header("Access-Control-Allow-Origin: *");
	    print json_encode($re);
		return;
	}





	if($event == "addnetwork"){
		mysql_select_db("ezbreaks", $con);
		$breakgroupsid 	= $_POST['breakgroupsid'];
		$tmsid 			= $_POST['tmsid'];
		$timezoneid 	= $_POST['timezoneid'];
		$instancecode 	= $_POST['instancecode'];
		$breakid 		= $_POST['breakid'];
		$livegrouping 	= $_POST['livegrouping'];

		$sql = "SELECT * FROM breakgroups_items WHERE breakgroupsid = $breakgroupsid AND tmsid = $tmsid AND timezoneid = $timezoneid AND instancecode = '$instancecode' AND breakid = $breakid";
		$result = mysql_query($sql);
		$cnt = mysql_num_rows($result);


		if($cnt > 0){
	    	print 0;
			return;
	    }

		$sql = "INSERT INTO breakgroups_items (breakid, breakgroupsid, tmsid, timezoneid, instancecode, livegrouping, createdat, updatedat) VALUES ($breakid, $breakgroupsid,$tmsid,$timezoneid,'$instancecode', '$livegrouping','$d','$d')";
		$result = mysql_query($sql);



		print 1;
		return;
	}






	if($event == "updatenetwork"){
		mysql_select_db("ezbreaks", $con);
		$id = $_POST['id'];
		$breakid = $_POST['breakid'];
		$instancecode = $_POST['instancecode'];
		$livegrouping = $_POST['livegrouping'];

		$sql = "UPDATE breakgroups_items SET breakid = $breakid, instancecode = '$instancecode', livegrouping='$livegrouping' WHERE id = $id";
		$result = mysql_query($sql);

		return;
	}	









	if($event == "groupnetlistforscheduler")
	{
		$id = trim($_GET['id']);
		
		$allowedInstances = getUsersAllowedInstanceIds($userid);

		$sql1 = " SELECT id, name FROM ezbreaks.breakgroups WHERE corporationid=$corporationid AND deletedat IS NULL ";
		if($id != 'ALL' && intval($id) > 0)
			$sql1 .= "AND id = $id ";

		$result = mysql_query($sql1);
		
	    $data = array();
	    //loop over and add to list
	    while($row = mysql_fetch_assoc($result)) 
	    {
	    	
	    	$row['groupname'] = $row['name'];
	    	$row['groupid']   = $row['id'];
	    	$row['groupnets'] = array();
	    	
	    	$sql = " SELECT 
	    				breakgroups_items.id,
						breakgroups_items.tmsid,
						breakgroups_items.breakid,
						breakgroups_items.instancecode,
						breakgroups_items.timezoneid,
						timezones.name AS tz,
						timezones.abbreviation AS abbreviation,
						tms_networks.name,
						tms_networks.callsign,
						logos.filename
					FROM ezbreaks.breakgroups_items
					INNER JOIN ShowSeeker.timezones ON ShowSeeker.timezones.id = breakgroups_items.timezoneid
					INNER JOIN ShowSeeker.tms_networks ON ShowSeeker.tms_networks.networkid = breakgroups_items.tmsid
			       	LEFT JOIN ShowSeeker.networklogos ON ShowSeeker.networklogos.networkid = breakgroups_items.tmsid
					LEFT JOIN ShowSeeker.logos ON ShowSeeker.logos.id = ShowSeeker.networklogos.logoid
					WHERE breakgroups_items.breakgroupsid = {$row['id']} AND breakgroups_items.deletedat IS NULL
					ORDER BY breakgroups_items.instancecode ";
					//ORDER BY timezones.name, tms_networks.callsign";
			$resnets = mysql_query($sql);
			while($net = mysql_fetch_assoc($resnets)) 
			{
				if(in_array($net['id'], $allowedInstances)) 
					$row['groupnets'][] = $net;
			}

	    	$data[] = $row;
	    }

	    //build the return
	   	$re = array("data"=>$data);

	   	//print the output
	    print json_encode($re);
		return;
	}


	function getUsersAllowedInstanceIds($userId)
	{
		$sql = "SELECT pb.networkinstances FROM ShowSeeker.permissionbreakuser AS pbu INNER JOIN ShowSeeker.permissionbreaks AS pb ON pb.id=pbu.groups WHERE pbu.userid = $userId";
		$res = mysql_query($sql);
		
		if(mysql_num_rows($res) ==0) return array();
		
		$obj = mysql_fetch_object($res);
		$instances = explode(',',$obj->networkinstances);
		return $instances;
	}

?>