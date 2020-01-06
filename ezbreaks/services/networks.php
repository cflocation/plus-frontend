<?php
	include_once('../../inc/permissions.php');
	include_once('../../inc/geturl.php');

	//if the corporation id is not set return
	if(!isset($corporationid))
	{
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



	if($event == "viewnetwork"){
		$id        = $_GET['id'];
		$tz        = $_GET['tzid'];
		$startdate = $_GET['startdate'];
		$enddate   = $_GET['enddate'];
	
		$url  = "http://ezbreaks.showseeker.com/parseNetwork.php?ids=$id&tz=$tz&cid=$corporationid&apikey=$corpApiKey&startdate=$startdate&enddate=$enddate";
		$json = file_get_contents($url); 
		
		header("Content-Type: application/json");
		print $json;
		return;
	}

	if($event == "downloadnetworkbreaks"){
		$ids = $_GET['ids'];
		$tz = $_GET['tzid'];
		$startdate = $_GET['startdate'];
		$enddate = $_GET['enddate'];
	
		$url = "http://ezbreaks.showseeker.com/addDownloadBreaksQueue.php?userid=$userid&ids=$ids&tz=$tz&cid=$corporationid&apikey=$corpApiKey&startdate=$startdate&enddate=$enddate";
		
		$arContext['http']['timeout'] = 1200;
		$context = stream_context_create($arContext);
		$json = file_get_contents($url,0,$context); 
		print $json;
		return;
	}


	if($event == "list"){
		$sql = "SELECT timezones.id, timezones.name
		FROM zones
		INNER JOIN timezones ON timezones.id = zones.timezoneid
		WHERE zones.corporationid = $corporationid
		GROUP BY timezones.id";

		$result = mysql_query($sql);

		//loop over and add to list
	    while($row = mysql_fetch_assoc($result)) {
	    	$data[] = $row;
	    }


	    $sql = "SELECT zonenetworks.networkid AS id, tms_networks.callsign, tms_networks.name, logos.filename
		FROM zones
		INNER JOIN zonenetworks ON zonenetworks.zoneid = zones.id
		INNER JOIN tms_networks ON tms_networks.networkid = zonenetworks.networkid
		INNER JOIN networklogos ON networklogos.networkid = tms_networks.networkid
		INNER JOIN logos ON logos.id = networklogos.logoid
		WHERE corporationid = $corporationid
		GROUP BY zonenetworks.networkid
		ORDER BY tms_networks.name";


		$result = mysql_query($sql);

		//loop over and add to list
	    while($row = mysql_fetch_assoc($result)) {
	    	$networks[] = $row;
	    }


	    //build the return
	   	$re = array("data"=>$data,"networks"=>$networks);

	   	//print the output
	    print json_encode($re);
		return;
	}

	if($event == "networklist"){
		$timezoneid = $_GET['timezoneid'];

		$sql = "SELECT zonenetworks.networkid AS id, tms_networks.callsign, tms_networks.name, logos.filename
		FROM zones
		INNER JOIN zonenetworks ON zonenetworks.zoneid = zones.id
		INNER JOIN tms_networks ON tms_networks.networkid = zonenetworks.networkid
		INNER JOIN networklogos ON networklogos.networkid = tms_networks.networkid
		INNER JOIN logos ON logos.id = networklogos.logoid 
		WHERE corporationid = $corporationid AND timezoneid = $timezoneid AND zonenetworks.deletedat IS NULL
		GROUP BY zonenetworks.networkid
		ORDER BY tms_networks.callsign";


		$result = mysql_query($sql);

		//loop over and add to list
	    while($row = mysql_fetch_assoc($result)) {
	    	$data[] = $row;
	    }

	    if($corporationid == 15){
	    	$data = mappingCharter($data,$timezoneid);
	    }

	    $tz = getTimezoneData($timezoneid);

	    //build the return
	   	$re = array("header"=>$tz,"data"=>$data);

	   	//print the output
	    print json_encode($re);
		return;
	}

	if ($event == "createcustomrule") 
	{
		$label 				= rawurlencode($_GET['label']);
		$networkid 			= rawurlencode($_GET['networkid']);
		$breakaddorremove	= rawurlencode($_GET['breakaddorremove']);
		$length 			= rawurlencode($_GET['length']);
		$startdate 			= rawurlencode($_GET['startdate']);
		$enddate 			= rawurlencode($_GET['enddate']);
		$starttime 			= rawurlencode($_GET['starttime']);
		$endtime 			= rawurlencode($_GET['endtime']);
		$template 			= rawurlencode($_GET['template']);
		$instances 			= rawurlencode($_GET['instances']);

		$url  = "http://ezbreaks.showseeker.com/createCustomRule.php?cid=$corporationid&apikey=$corpApiKey&";
		$url .= "label=$label&networkid=$networkid&breakaddorremove=$breakaddorremove&length=$length&startdate=$startdate&enddate=$enddate&starttime=$starttime&endtime=$endtime&template=$template&instances=$instances";		
		$json = file_get_contents($url); 
		print $json;
		return;
	}

	if ($event == "updatecustomrule") 
	{
		$breakid 			= rawurlencode($_GET['breakid']);
		$label 				= rawurlencode($_GET['label']);
		$networkid 			= rawurlencode($_GET['networkid']);
		$breakaddorremove	= rawurlencode($_GET['breakaddorremove']);
		$length 			= rawurlencode($_GET['length']);
		$startdate 			= rawurlencode($_GET['startdate']);
		$enddate 			= rawurlencode($_GET['enddate']);
		$starttime 			= rawurlencode($_GET['starttime']);
		$endtime 			= rawurlencode($_GET['endtime']);
		
		$url  = "http://ezbreaks.showseeker.com/updateCustomRule.php?cid=$corporationid&apikey=$corpApiKey&";
		$url .= "breakid=$breakid&label=$label&networkid=$networkid&breakaddorremove=$breakaddorremove&length=$length&startdate=$startdate&enddate=$enddate&starttime=$starttime&endtime=$endtime";
		$json = file_get_contents($url); 
		print $json;
		return;
	}

	if ($event == "viewncreatecustomrule") 
	{
		$networkid 			= rawurlencode($_GET['networkid']);
		
		$url  = "http://ezbreaks.showseeker.com/viewNetworkCustomRules.php?cid=$corporationid&apikey=$corpApiKey&";
		$url .= "networkid=$networkid";
		$json = file_get_contents($url);

		header('Content-Type: application/json');
		print $json;
		return;
	}

	if ($event == "deletecustombreakrule") 
	{
		$ids	= $_POST['ids'];
		$cSIds	= implode(',', $ids);

		$url  = "http://ezbreaks.showseeker.com/deleteNetworkCustomRules.php?cid=$corporationid&apikey=$corpApiKey&";
		$url .= "ids=$cSIds";
		$json = file_get_contents($url); 
		print $json;
		return;
	}

	if ($event == "importespnxlsfile") 
	{
		$file		= $_GET['file'];
		$timezone	= $_GET['timezone'];
		$path 		= "/var/www/html/www.showseeker.com/tmp/breaks/";

		$url  = "https://ezbreaks.showseeker.com/importEspnExcelFile.php";
		//$headers = array("Content-Type:multipart/form-data");
        $postfields = array("xlsfile" => "@$path$file", "file" => $file, "timezone" => $timezone, "cid" => $corporationid, "apikey" => $corpApiKey);
       

       	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		
		$response = curl_exec ($ch);

		if(!curl_errno($ch))
        {
            echo $response;
        }
        else
        {
           print  $errmsg = curl_error($ch);
        }

		curl_close ($ch);

		return;
	}

	if ($event == "getnetworkshowschedule") 
	{
		
		$networkid = rawurlencode($_GET['networkid']);
		$startdate = rawurlencode($_GET['startdate']);
		$enddate   = rawurlencode($_GET['enddate']);
		$starttime = rawurlencode($_GET['starttime']);
		$endtime   = rawurlencode($_GET['endtime']);

    	$url  = "http://ezbreaks.showseeker.com/getNetworkScheduleForTitles.php?cid=$corporationid&apikey=$corpApiKey&";
    	$url .= "networkid=$networkid&startdate=$startdate&enddate=$enddate&starttime=$starttime&endtime=$endtime";
		
		$json = file_get_contents($url); 
		print $json;
		return;
	}

	if ($event == "addeditcustomtitle") 
	{
		
		$url 						 = "https://ezbreaks.showseeker.com/saveCustomTitle.php";
        $postfields 				 = array();
        $postfields['customtitle']   = trim($_POST['customtitle']);
        $postfields['selectedrows']  = json_encode(($_POST['selectedrows']));
        $postfields['cid'] 			 = $corporationid;
        $postfields['apikey'] 		 = $corpApiKey;
        $postfields['instancecodes'] = trim($_POST['instancecodes']);
       
       	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		
		$response = curl_exec ($ch);

		if(!curl_errno($ch))
        {
            echo $response;
        }
        else
        {
           $errmsg = curl_error($ch);
           print json_encode(array("error"=>true, 'message'=>$errmsg));
        }

		curl_close ($ch);

		return;
	}

	if ($event == "savedownloadschedule") 
	{
		$url 				  = "https://ezbreaks.showseeker.com/saveDownloadSchedule.php";
        $postfields 		  = $_POST;
        $postfields['cid'] 	  = $corporationid;
        $postfields['apikey'] = $corpApiKey;
        $postfields['userid'] = $userid;
        $field_string 		  = http_build_query($postfields);

       	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $field_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		
		$response = curl_exec ($ch);

		if(!curl_errno($ch))
            echo $response;
        else
           print json_encode(array("error"=>true, 'message'=>curl_error($ch)));

		curl_close ($ch);

		return;
	}


	if($event == "getuserdownloadschedules"){
		$url = "https://ezbreaks.showseeker.com/viewUserDownloadSchedules.php?cid=$corporationid&apikey=$corpApiKey&userid=$userid";
		$json = file_get_contents($url); 
		print $json;
		return;
	}


	//timezone data
	function getTimezoneData($id){
		$sql = "SELECT name AS tzname, abbreviation AS tzabbreviation FROM timezones WHERE id = $id";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		return $row;
	}



	if($event == "deletedownloadschedule"){

		$ids = implode(",", $_GET['selectedrows']);
		
		$url = "http://ezbreaks.showseeker.com/deleteUserDownloadSchedules.php?cid=$corporationid&apikey=$corpApiKey&userid=$userid&scheduleids=$ids";
		$json = file_get_contents($url); 
		print $json;
		return;
	}


	if($event == "networklistforcustombreaks")
	{
		$allowedNets = getUsersAllowedNetworks($userid);

		$sql = " SELECT tn.networkid AS id, tn.callsign, tn.name, logos.filename, nm.charter_mapping AS charter_callsign
						FROM ezbreaks.breakgroups AS bg 
						INNER JOIN ezbreaks.breakgroups_items AS bgi ON bg.id = bgi.breakgroupsid
						INNER JOIN ShowSeeker.tms_networks AS tn  ON tn.networkid = bgi.tmsid
						INNER JOIN ShowSeeker.networkmapping AS nm  ON tn.networkid = nm.id
						LEFT JOIN ShowSeeker.networklogos ON networklogos.networkid = tn.networkid
						LEFT JOIN ShowSeeker.logos ON logos.id = networklogos.logoid 
						WHERE corporationid=$corporationid AND bgi.deletedat IS NULL AND bg.deletedat IS NULL
						GROUP BY tn.networkid ORDER BY tn.callsign ";
		$result = mysql_query($sql);

		//loop over and add to list
	    while($row = mysql_fetch_assoc($result)) {
	    	if(in_array($row['id'], $allowedNets))
	    		$data[] = $row;
	    }

	    //build the return
	   	$re = array("data"=>$data);

	   	//print the output
	   	print json_encode($re);
		return;
	}





	if($event == "networklistforcustombreaks2")
	{



		$sql = " SELECT tn.networkid AS id, tn.callsign
						FROM ezbreaks.breakgroups AS bg 
						INNER JOIN ezbreaks.breakgroups_items AS bgi ON bg.id = bgi.breakgroupsid
						INNER JOIN ShowSeeker.tms_networks AS tn  ON tn.networkid = bgi.tmsid
						INNER JOIN ShowSeeker.networkmapping AS nm  ON tn.networkid = nm.id
						LEFT JOIN ShowSeeker.networklogos ON networklogos.networkid = tn.networkid
						LEFT JOIN ShowSeeker.logos ON logos.id = networklogos.logoid 
						WHERE corporationid=$corporationid AND bgi.deletedat IS NULL AND bg.deletedat IS NULL
						GROUP BY tn.networkid ORDER BY tn.callsign ";
		$result = mysql_query($sql);

		//loop over and add to list
	    while($row = mysql_fetch_assoc($result)) {
	    	$data[] = $row;
	    }


	   	//print the output
	   	print json_encode($data);
		return;
	}


	if($event == "getselectednetworkinstances")
	{
		$networkId  = $_GET['networkid'];

		//$allowedInstances = getUsersAllowedInstanceIds($userid);

		$sql = " SELECT bgi.id, bgi.instancecode, tz.name AS timezone, tz.abbreviation AS tzabbreviation, bg.name as breakgroupname
				 FROM ezbreaks.breakgroups_items AS bgi
				 INNER JOIN ShowSeeker.timezones AS tz ON tz.id = bgi.timezoneid
				 INNER JOIN ezbreaks.breakgroups AS bg ON bg.id = bgi.breakgroupsid
				 WHERE bgi.deletedat IS NULL AND bgi.tmsid = $networkId AND bg.id NOT IN(13,15)
				 ORDER BY bg.id, bgi.instancecode ";

		$result = mysql_query($sql);

		$data = array();
		//loop over and add to list
	    while($row = mysql_fetch_assoc($result)) {
	    	//if(in_array($row['id'], $allowedInstances)) 
	    		$data[] = $row;
	    }

	    //build the return
	   	$re = array("data"=>$data);

	   	//print the output
	   	print json_encode($re);
		return;
	}


	if ($event == "saveupdatedownloadschedule") 
	{
		$url 				  = "https://ezbreaks.showseeker.com/saveUpdateDownloadSchedule.php";
        $postfields 		  = $_POST;
        $postfields['cid'] 	  = $corporationid;
        $postfields['apikey'] = $corpApiKey;
        $postfields['userid'] = $userid;
        
        $field_string 		  = http_build_query($postfields);

       	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $field_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		
		$response = curl_exec ($ch);

		if(!curl_errno($ch))
            echo $response;
        else
           print json_encode(array("error"=>true, 'message'=>curl_error($ch)));

		curl_close ($ch);

		return;
	}

	if($event == "getuserupdatedownloadschedules"){
		$url = "http://ezbreaks.showseeker.com/viewUserUpdateDownloadSchedules.php?cid=$corporationid&apikey=$corpApiKey&userid=$userid";
		$json = file_get_contents($url); 
		print $json;
		return;
	}

	if($event == "deletedownloadupdateschedule"){

		$ids = implode(",", $_GET['selectedrows']);
		
		$url = "http://ezbreaks.showseeker.com/deleteUserDownloadUpdateSchedules.php?cid=$corporationid&apikey=$corpApiKey&userid=$userid&scheduleids=$ids";
		$json = file_get_contents($url); 
		print $json;
		return;
	}

	if($event == "listcustombreaktemplates"){

		$sql = "SELECT id,name FROM ezbreaks.custom_breaks_templates WHERE deletedat IS NULL ORDER BY name ASC";
		$res = mysql_query($sql);
		$data = array();
		while($row=mysql_fetch_object($res))
		{
			array_push($data,$row);
		}

		//build the return
	   	$re = array("data"=>$data);

	   	//print the output
	   	print json_encode($re);
		return;
	}

	if($event == "getchangesemails")
	{
		print "h";
	}


	//charter mapping
	function mappingCharter($data,$timezoneid){
		
		/*foreach ($data as &$value) {
			$id = $value['id'];
			$sql = "SELECT instancecode FROM break_instancecode_map WHERE networkid=$id AND timezoneid=$timezoneid AND corporationid=15";
			$result = mysql_query($sql);
			$value['networkcode'] =  (mysql_num_rows($result) >0)?mysql_fetch_object($result)->instancecode:'';

		}*/

		foreach ($data as &$value) {
			$id = $value['id'];
			$sql = "SELECT CONCAT(charter_mapping, '-', (SELECT value FROM break_instances WHERE item = $timezoneid)) AS networkcode FROM networkmapping WHERE id = $id LIMIT 1";
			$result = mysql_query($sql);
			$cnt = mysql_num_rows($result);

			if($cnt > 0){
				$row = mysql_fetch_object($result);
				$value['networkcode'] = $row->networkcode;
			}else{
				$value['networkcode'] = "";
			}


		}

		return $data;
	}





	function findID($id,$source){
		foreach ($source as &$value) {
			if(is_object($value) && $value->id == $id){
				return $value;
			}
		}

		$obj = (object) array('id' => 0);
		return $obj;
	}

	function getUsersAllowedNetworks($userId)
	{
		$sql = "SELECT pb.networkinstances FROM ShowSeeker.permissionbreakuser AS pbu INNER JOIN ShowSeeker.permissionbreaks AS pb ON pb.id=pbu.groups WHERE pbu.userid = $userId";
		$res = mysql_query($sql);
		
		if(mysql_num_rows($res) ==0) return array();
		
		$obj = mysql_fetch_object($res);
		
		if(count(explode(',',$obj->networkinstances)) == 0) return array();

		$sql = "SELECT DISTINCT breakgroups_items.tmsid FROM ezbreaks.breakgroups_items WHERE id IN ({$obj->networkinstances}) ";
		$result = mysql_query($sql);

		$networks = array();
	    while($row = mysql_fetch_object($result))
	    {
	    	$networks[] = $row->tmsid;
	    }

		return $networks;
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








