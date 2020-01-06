<?php
	
	$zoneid = $_GET['zoneid'];
	$userid = $_GET['userid'];
	$tokenid = $_GET['tokenid'];
	$startdate = $_GET['startdate'];
	
	$group = $_GET['group'];
			
	include_once('../../config/database.php');

	//--- MAPPING THE ZONEID TO GET THE SYSCODE --->
	$sql 	= "SELECT	SYSCODE,ID FROM zones where id=$zoneid";
	$zoneinfo 	= mysql_fetch_assoc(mysql_query($sql));



	//--- DAY PART PERIODS --->
	$dayparts= getDateParts();
	
	
	//--- COLLECTING RATECARDS
	$ratecard = ratecard_values($zoneinfo);



	
	//--- FROMATTING 
	$rcbody = array('fringe'=>$dayparts,'zone'=>$ratecard);
	
	$finalResult = array('responseHeader'=>array('ratecardtype'=>'1'), 'response'=>$rcbody);

	header('Content-Type: application/json');
	echo json_encode($finalResult);
	
	
	
	
	function getDateParts(){
		$sql 	=  "SELECT 	name as fname,
							daypartid as fringe, 
							starts, 
							stops
					FROM 	Customers.SuddenlinkDayParts";
					
		$re		= mysql_query($sql);		
		$day_parts = array();

		while($row = mysql_fetch_assoc($re)){
			$day_parts[] = $row;
		}
		return 	$day_parts;
	}

	//--- COLLECT THE RATECARD VALUES FOR THE SYSCODE --->	
	function getRates($syscode, $thisdate){

		$sql = "SELECT 		* 
				FROM 		Customers.SuddenlinkRateCards 
				WHERE 		syscode = $syscode 
				AND 		'$thisdate' >= startdate
				AND 		'$thisdate' <= enddate
				ORDER BY 	network";
				
		$sql = "SELECT 		network,daypartid,daypart,rate,startdate,enddate 
				FROM 		Customers.SuddenlinkRateCards 
				WHERE 		syscode = $syscode 
				AND 		enddate = '$thisdate'
				ORDER BY 	network";		

		$re= mysql_query($sql);
		$rc = array();
		while($row = mysql_fetch_assoc($re)){
			$rc[] = $row;
		}
		return $rc;
	}
	
	//--- COLLECT THE RATECARD VALUES FOR THE SYSCODE --->	
	function getRange($syscode, $thisdate){

		$sql = "SELECT 		enddate 
				FROM 		Customers.SuddenlinkRateCards 
				WHERE 		syscode = $syscode 
				AND 		'$thisdate' >= startdate
				AND 		'$thisdate' <= enddate
				GROUP BY 	enddate
				ORDER BY 	enddate asc
				LIMIT 1";

		$re= mysql_fetch_assoc(mysql_query($sql));

		return $re['enddate'];
	}	
	
	//--- COLLECT THE RATECARD VALUES FOR THE SYSCODE --->	
	function getLastRC($syscode, $thisdate){
		$sql = "SELECT 		enddate 
				FROM 		Customers.SuddenlinkRateCards 
				WHERE 		syscode = $syscode 
				GROUP BY 	enddate
				ORDER BY 	enddate asc
				LIMIT 1";

		$re= mysql_fetch_assoc(mysql_query($sql));

		return $re['enddate'];
	}	
	
	//--- STATION LINEUP OF THE ZONE --->	
	function getNets($zoneid){
		$sql = "SELECT 		tms_networks.networkid,
							tms_networks.callsign,
							suddenlink_mapping as suddenlink_callsign
				
				FROM 		zonenetworks 
				
				INNER JOIN 	tms_networks
				ON			zonenetworks.networkid = tms_networks.networkid
				
				INNER JOIN 	networkmapping
				ON			tms_networks.networkid = networkmapping.id
				
				WHERE 		zonenetworks.zoneid = $zoneid";
		
		$re		= mysql_query($sql);
		$nets  	= array();
		$textid	= array();
		$ids	= array();
		
		while($row = mysql_fetch_assoc($re)){
			$textid[]	= $row['callsign'];
			$ids[] 		= $row['networkid'];
			$nets[] 	= $row;
		}
		$result = array('callsign'=>$textid,'networkid'=>$ids,'networks'=>$nets);
		return $result;
	}
	


	function ratecard_values($zoneinfo){

		$todayis 	= date('Y-m-d');	
		$zone_syscode = $zoneinfo['SYSCODE'];
		$rclimit	= getRange($zone_syscode, $todayis);
		if($rclimit == ''){
			$rclimit	= getLastRC($zone_syscode);	
		}
		$rc 		= getRates($zone_syscode,$rclimit);
		$zone_nets 	= getNets($zoneinfo['ID']);

		$ratecardsbynet = array();
		
		foreach($zone_nets['networks'] as $thisnet){

			$thesedayparts = array();						
			$suddenlikcallsign = $thisnet['suddenlink_callsign'];
			foreach($rc as $ratecard){
				if($suddenlikcallsign == $ratecard['network']){
					$thesedayparts[] = array($ratecard['daypartid']=>$ratecard['rate']);
				}
			}
			
			$ratecardsbynet[] = array($thisnet['networkid']=>$thesedayparts);			
		}
		
		$result = array($zoneinfo['ID']=>$ratecardsbynet);

		return $result;
	}	

	
	
?>
