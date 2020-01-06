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



	if($event == "networkinstances")
	{
		$networkids = $_GET['networkids'];
		$allowedInstances 	= getUsersAllowedInstanceIds($userid);

		$sql = " SELECT bgi.id, bgi.instancecode, tz.name AS timezone, tz.abbreviation AS tzabbreviation, CONCAT('http://ww2.showseeker.com/images/_thumbnailsW/',IFNULL(logos.filename,'default.gif')) AS logofullpath
				 FROM ezbreaks.breakgroups_items AS bgi
				 INNER JOIN ShowSeeker.timezones AS tz ON tz.id = bgi.timezoneid
				 LEFT JOIN ShowSeeker.networklogos ON networklogos.networkid = bgi.tmsid
				 LEFT JOIN ShowSeeker.logos ON logos.id = networklogos.logoid 
				 WHERE bgi.deletedat IS NULL AND bgi.tmsid IN( $networkids )
				 ORDER BY bgi.instancecode ";
		$result = mysql_query($sql);

		$data = array();
		//loop over and add to list
		while($row = mysql_fetch_assoc($result)) 
		{
			if(in_array($row['id'], $allowedInstances)) 
				$data[] = $row;
		}

		print json_encode($data);
		return;
	}

	if($event == "createnewcustomruleset")
	{
		$label			= mysql_real_escape_string(trim($_POST['rulesetLabel']));
		$ruletype		= trim($_POST['rulesetType']);
		$ruletitle		= (trim($_POST['title']) != "") ? mysql_real_escape_string(trim($_POST['title'])) : '';
		$startdate		= (trim($_POST['fromDate']) != "") ? date("Y-m-d",strtotime(trim($_POST['fromDate']))) : '';
		$enddate		= (trim($_POST['toDate']) != "") ? date("Y-m-d",strtotime(trim($_POST['toDate']))) : '';
		$starttime		= (trim($_POST['fromTime']) != "") ? date("H:i:s",strtotime(trim($_POST['fromTime']))) : '';
		$endtime		= (trim($_POST['toTime']) != "") ? date("H:i:s",strtotime(trim($_POST['toTime']))) : '';
		$timezone		= trim($_POST['timezone']);
		$livesportsonly	= ($_POST['liveSports']) ? 'Yes' : 'No';
		$instanceids	= implode(",", $_POST['instanceIds']);
		$networkIds		= $_POST['networkIds'];
		$rulesetItems	= (isset($_POST['rulesetItems']) && is_array($_POST['rulesetItems']) && count($_POST['rulesetItems']) > 0)?$_POST['rulesetItems']:array();
		$createdat		= date("Y-m-d H:i:s");

		$sql 		= "INSERT INTO ezbreaks.custom_break_rulesets(id, label, ruletype, ruletitle, startdate, enddate, starttime, endtime, timezone, livesportsonly, instanceids, userid, createdat, updatedat, deletedat) 
						VALUES (null, '{$label}', '{$ruletype}', '{$ruletitle}', '{$startdate}', '{$enddate}', CONVERT_TZ('{$startdate} {$starttime}','{$timezone}','GMT'), CONVERT_TZ('{$startdate} {$endtime}','{$timezone}','GMT'), '{$timezone}', '{$livesportsonly}', '{$instanceids}', '{$userid}', '{$createdat}', null, null)";
		$flag 		= mysql_query($sql);
		$ruleSetId 	= mysql_insert_id();

		//insert the networks
		foreach ($networkIds as $netId)
			$flag 	= mysql_query("INSERT INTO ezbreaks.custom_break_rulenets(rulesetid, networkid) VALUES ($ruleSetId, $netId) ");
	
		//insert the rule items
		foreach ($rulesetItems as $item)
		{
			$breaktime 	 = date("H:i:s",strtotime($item['breakclocktime']));
			$breaklength = $item['breaklength'];
			if($ruletype == 1)
				$sql = "INSERT INTO ezbreaks.custom_break_ruleitems(id, rulesetid, breaktime, breaklength) VALUES (null, $ruleSetId, CONVERT_TZ('{$startdate} {$breaktime}','{$timezone}','GMT'), '{$breaklength}')";
			else
				$sql = "INSERT INTO ezbreaks.custom_break_ruleitems(id, rulesetid, breaktime, breaklength) VALUES (null, $ruleSetId, '{$breaktime}', '{$breaklength}')";
			$flag 		= mysql_query($sql);
		}

		print json_encode(array("done"=>true));
	}

	if($event == "deletecustomruleset")
	{
		foreach ($_POST['ids'] as $id)
		{
			$now = date("Y-m-d H:i:s");
			$sql = "UPDATE ezbreaks.custom_break_rulesets SET deletedat = '{$now}' WHERE id={$id} ";
			$flag = mysql_query($sql);
		}
		
		print json_encode(array("done"=>true));
	}

	
	if($event == "viewcustombreakrules")
	{
		$sql = "SELECT 
				rs.id
				,rs.label AS breaklabel
				,rs.ruletype
				,IF(rs.startdate = '0000-00-00' , 'NA', rs.startdate) AS startdate
				,IF(rs.enddate = '0000-00-00' , 'NA', rs.enddate) AS enddate
				,IFNULL(TIME(CONVERT_TZ(CONCAT(rs.startdate, ' ', rs.starttime),'GMT', rs.timezone)), '')AS starttime
				,IFNULL(TIME(CONVERT_TZ(CONCAT(rs.startdate, ' ', rs.endtime),'GMT', rs.timezone)), '') AS endtime
				,rs.ruletitle as title
				,rs.livesportsonly
				,rs.instanceids
			FROM ezbreaks.custom_break_rulesets AS rs  
			WHERE deletedat IS NULL AND (enddate > CURDATE() OR enddate = '0000-00-00')";

		$res   = mysql_query($sql);

		$dataArr = array();
		while($row = mysql_fetch_object($res))
		{
			$row = addNetworkInstanceCodes($row);
			$row = addNetworkCodes($row);
			$dataArr[] = $row;
		}

		$response  = array('data' => $dataArr);
		print json_encode($response);
	}

	if($event == "viewrulesetitems")
	{
		$ruleSetId =  $_GET['rulesetid'];
		$sql = "SELECT * FROM ezbreaks.custom_break_rulesets AS rs WHERE rs.id=$ruleSetId ";
		$res = mysql_query($sql);
		$set = mysql_fetch_object($res);
		
		if($set->ruletype == 1)
			$sql = "SELECT 1 AS breakid, cbri.id, TIME_FORMAT(TIME(CONVERT_TZ(CONCAT('{$set->startdate}',' ',cbri.breaktime),'GMT','{$set->timezone}')),'%h:%i %p') AS breakclocktime, cbri.breaklength FROM ezbreaks.custom_break_ruleitems AS cbri WHERE cbri.rulesetid = $ruleSetId ORDER BY cbri.breaktime ASC ";
		else if($set->ruletype == 2)
			$sql = "SELECT 1 AS breakid, cbri.id, TIME_FORMAT(cbri.breaktime,'%H:%i') AS breakclocktime, cbri.breaklength FROM ezbreaks.custom_break_ruleitems AS cbri WHERE cbri.rulesetid = $ruleSetId ORDER BY cbri.breaktime ASC ";
		
		$res   = mysql_query($sql);
		$dataArr = array();
		while($row = mysql_fetch_object($res))
		{
			$dataArr[] = $row;
		}

		$response  = array('data' => $dataArr);
		print json_encode($response);
	}

	if($event == "gettemplateoptions")
	{
		$sql = "SELECT id, name FROM ezbreaks.custom_breaks_templates WHERE deletedat IS NULL ";
		$res = mysql_query($sql);
		$dataArr = array();
		while($row = mysql_fetch_object($res))
		{
			$dataArr[] = $row;
		}

		$response  = array('data' => $dataArr);
		print json_encode($response);
	}


	if($event == "buildcustomruleitems")
	{
		$rulesetType = $_GET['rulesetType'];
		$fromtime = $_GET['fromtime'];
		$totime = $_GET['totime'];
		$templid = $_GET['templid'];
		$ruletempltype = $_GET['ruletempltype'];

		$starttime = "00:00:00";

		if($rulesetType == 1)
		{
			$starttime = date("H:i:s",strtotime($fromtime));
			$endtime = date("H:i:s",strtotime($totime));
			$length = (strtotime($endtime) - strtotime($starttime))/60;
		}

		if($ruletempltype == "template")
		{
			$sql = "SELECT * FROM ezbreaks.custom_breaks_templates WHERE id=$templid ";
			$res = mysql_query($sql);
			$row = mysql_fetch_object($res);
			$breakstructure = json_decode($row->breakstructure, true);
			
			$breakList = array();
			$showStartMin = intval(date("i",strtotime($starttime)));
			$breakid = 1;
			if($rulesetType == 1 && $row->repeating == 'Y')
			{
				$addHour = 0;
				while(1)
				{
					foreach ($breakstructure as $breakTime => $breakLength)
					{
						$bMin = $breakTime-$showStartMin+$addHour;
						if($bMin >0 &&  $bMin <= $length)
						{
							$bt = $addHour+$breakTime;
							$t = date("h:i a",strtotime("$starttime +{$bt} minutes"));
							$breakList[] = (object)array('id'=>$breakid, 'breakid'=>$breakid,'breakclocktime'=>$t,'breaklength'=>$breakLength);
							$breakid++;
						}
					}
					$addHour += 60;

					if($addHour > ($length-$showStartMin))
						break;
				}
			} else
			{
				foreach ($breakstructure as $breakTime => $breakLength)
				{
					$bMin = $breakTime-$showStartMin;
					if($bMin >0)
					{
						$t = date("h:i a",strtotime("$starttime +{$breakTime} minutes"));
						$breakList[] = (object)array('id'=>$breakid, 'breakid'=>$breakid,'breakclocktime'=>($rulesetType == 1)?$t:gmdate("H:i",($bMin*60)),'breaklength'=>$breakLength);
						$breakid++;
					}
				}
			}
		}

		$response  = array('data' => $breakList);
		print json_encode($response);
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

function addNetworkCodes(&$row)
{
	$sql 	= "SELECT tms.callsign FROM ezbreaks.custom_break_rulenets AS rn INNER JOIN ShowSeeker.tms_networks AS tms ON tms.networkid=rn.networkid	WHERE rn.rulesetid={$row->id} ";
	$res   	= mysql_query($sql);
	$netArr = array();
	while($r = mysql_fetch_object($res))
		$netArr[] = $r->callsign;
	$row->networkslist = implode(', ', $netArr);

	return $row;
	
}

function addNetworkInstanceCodes(&$row)
{
	$sql = "SELECT instancecode FROM ezbreaks.breakgroups_items WHERE id IN({$row->instanceids}) ";
	$res   = mysql_query($sql);
	$instancesArr = array();
	while($r = mysql_fetch_object($res))
	{
		$instancesArr[] = $r->instancecode;
	}
	
	$row->instancecodeslist = implode(', ', $instancesArr);
	
	return $row;
}