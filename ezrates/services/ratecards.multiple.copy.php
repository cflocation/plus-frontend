<?php
	include_once('../../inc/permissions.php');
	include_once('../../inc/geturl.php');


	//if the corporation id is not set return
	if(!isset($corporationid)){
		return 0;
	}
	
	//session ids
	$corporationid 	= $_SESSION['corporationid'];
	$userid 		= $_SESSION['userid'];


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

	
	if($event == "copymultipleratecard"){
		$ratecards		= json_decode($_POST['ratecards']);
		$name 			= $_POST['name'];
		$special 		= $_POST['special'];
		$startdate 		= $_POST['startdate'];
		$enddate 		= $_POST['enddate'];
		
		foreach($ratecards as $rcs){
			$sourceid 	 = $rcs->id;
			//$destination = $rcs->zoneid;
			//print_r($source.' - '.$destination);
			//print_r('<BR>');
			//grab the source ratecard
			$sql = "SELECT  	RateCardCardVersion.rateCard, 
								RateCard.marketId, 
								RateCard.zoneId
					FROM 		RateCardCardVersion
					INNER JOIN 	RateCard 
					ON 			RateCard.id = RateCardCardVersion.rateCardId
					WHERE 		rateCardId = $sourceid 
					AND 		working = 1";
	
			$result 	= mysql_query($sql);
			$row 		= mysql_fetch_assoc($result);
			$source 	= json_decode($row['rateCard']);
			$marketId 	= $row['marketId'];
			$zoneId 	= $row['zoneId'];
	
	
			//if($zoneId == $destinationids[0] && count($destinationids) == 1){
				
				$rateCard = json_encode($source);
				$rateCard = mysql_real_escape_string($rateCard);
	
	
				//insert the new ratecard
				$sql 	= " INSERT INTO RateCard (zoneId, marketId, name, special, startDate, endDate, createdAt, updatedAt) 
							VALUES 	($zoneId,$marketId,'$name',$special,'$startdate','$enddate','$d','$d')";
				$result = mysql_query($sql);
	
				//grab the new ratecard id
				$id = mysql_insert_id();
	
				$sql 	= "INSERT INTO RateCardCardVersion (rateCardId, working, rateCard, userId, createdAt, updatedAt) 
							VALUES ($id,1,'$rateCard',$userid,'$d','$d')";
				print_r($sql);
	
				$result = mysql_query($sql);
				//exit;
			//}
	
			/*foreach ($destinationids as &$id) {
				
				$sql = "SELECT 		zonenetworks.networkid, 
									zones.broadcast
						FROM 		zonenetworks
						INNER JOIN 	zones 
						ON 			zones.id = zonenetworks.zoneId
						WHERE 		zonenetworks.zoneId = $id 
						AND 		zonenetworks.deletedat IS NULL 
						AND 		zones.broadcast = 0";
	
				$result = mysql_query($sql);
				$cnt = mysql_num_rows($result);
	
	
				if($cnt > 0){
					while($row = mysql_fetch_assoc($result)) {
						$available = findNetworkID($row['networkid'],$source);
	
						if($available){
							$data[] = $available;
						}
					}
	
					$ratecard = json_encode($data);
					$ratecard = mysql_real_escape_string($ratecard);
					
					//insert the new ratecard
					$sql = "INSERT INTO RateCard (zoneid, marketid, name, special, startdate, enddate, createdat, updatedat) 
							VALUES ($id,$marketid,'$name',$special,'$startdate','$enddate','$d','$d')";
					$result = mysql_query($sql);
	
					//grab the new ratecard id
					$id = mysql_insert_id();
	
					$sql = "INSERT INTO RateCardCardVersion (ratecardid, working, ratecard, userid, createdat, updatedat) 
							VALUES ($id,1,'$ratecard',$userid,'$d','$d')";
					$result = mysql_query($sql);
				}
			}*/
		}
		return;	
	}
?>





