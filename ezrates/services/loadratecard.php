<?php
	ini_set('display_errors', TRUE);

	$zoneid = $_GET['zoneid'];
	$cardid = $_GET['cardid'];
	$userid = $_GET['userid'];
	$tokenid = $_GET['tokenid'];
	$startdate = $_GET['startdate'];
	$group = $_GET['group'];


	//include database
	//include_once('../../config/database.php');
	include_once('../include/database.php');


	if($cardid > 0){
		


		
		$sql = "SELECT
		RateCard.id,
		RateCard.zoneId AS zoneid,
		RateCard.marketId AS marketid,
		RateCard.name,
		RateCard.startDate AS startdate,
		RateCard.endDate AS enddate,
		Zone.name AS zone
		FROM RateCard 
		INNER JOIN Zone ON Zone.id = RateCard.zoneId
		WHERE RateCard.id = $cardid";
		
		
		$result   = mysql_query($sql);
		$row      = mysql_fetch_assoc($result);
		$zoneid   = $row['zoneid'];
		$marketid = $row['marketid'];


		$header = buildHeader($row);
		$cards = allRatecardsForZone($zoneid,$group);
		$rules = ratecardRules($zoneid);
		$hotprograms = hotProgramming($zoneid);

		$dayparts = getDaypartsList($marketid);
		$rates = getRates($cardid, $dayparts);


		$re = array("responseHeader"=>$header,"ratecards"=>$cards,"dayparts"=>$dayparts,"rule"=>$rules,"hotprograms"=>$hotprograms,"response"=>$rates);
		print json_encode($re);
		return;
	}






	if($cardid == 0){

		//try to find the ratecard for the date specified
		$sql = "SELECT
			RateCard.id,
			RateCard.zoneId AS zoneid,
			RateCard.marketId AS marketid,
			RateCard.name,
			RateCard.startDate AS startdate,
			RateCard.endDate As enddate,
			Zone.name AS zone
			FROM RateCard 
			INNER JOIN Zone ON Zone.id = RateCard.zoneId
			WHERE RateCard.zoneId = $zoneid AND RateCard.deletedAt IS NULL AND startDate <= '$startdate' AND endDate >= '$startdate'";
		$result = mysql_query($sql);
		$cnt = mysql_num_rows($result);


		//if no rate card exists then lets rool back to the old one
		if($cnt == 0){
			$sql = "SELECT
			RateCard.id,
			RateCard.zoneId AS zoneid,
			RateCard.marketId AS marketid,
			RateCard.name,
			RateCard.startDate AS startdate,
			RateCard.endDate As enddate,
			Zone.name AS zone
			FROM RateCard 
			INNER JOIN Zone ON Zone.id = RateCard.zoneId
			WHERE RateCard.zoneId = $zoneid AND RateCard.deletedAt IS NULL LIMIT 1";
			$result = mysql_query($sql);
		}	


		$cnt = mysql_num_rows($result);

		if($cnt == 0){
			print '-1';
			return;
		}

		$row = mysql_fetch_assoc($result);
		$cardid = $row['id'];
		$zoneid = $row['zoneid'];
		$marketid = $row['marketid'];

		$header = buildHeader($row);
		$cards = allRatecardsForZone($zoneid,$group);
		$rules = ratecardRules($zoneid);
		$hotprograms = hotProgramming($zoneid);

		$dayparts = getDaypartsList($marketid);
		$rates = getRates($cardid, $dayparts);

		$re = array("responseHeader"=>$header,"ratecards"=>$cards,"dayparts"=>$dayparts,"rule"=>$rules,"hotprograms"=>$hotprograms,"response"=>$rates);
		print json_encode($re);
		return;
	}







	//build the header file for the ratecard
	function buildHeader($row){
		$id = $row['id'];
		$longstart = $row['startdate'];
		$longend = $row['enddate'];
		$startdate = date('n/j/y',strtotime($row['startdate']));
		$enddate = date('n/j/y',strtotime($row['enddate']));
		$label = $row['name'].' - '.$startdate.' to '.$enddate;
		$zoneid = $row['zoneid'];
		$zone = $row['zone'];

		$header = array("ratecardtype"=>4, "startdate"=>$longstart, "enddate"=>$longend,"zoneid"=>$zoneid,"zone"=>$zone,"name"=>$row['name'], "label"=>$label, "id"=>$id);
		return $header;
	}



	//list all the avaiable ratecards for this zone
	function allRatecardsForZone($zoneid,$group){
	
		$sql = "SELECT id, name, zoneId AS zoneid, startDate AS startdate, endDate AS enddate FROM RateCard WHERE zoneId = $zoneid  AND deletedAt IS NULL ORDER BY special, startDate";
		$result = mysql_query($sql);
		$response = array();

		while($row = mysql_fetch_assoc($result)){
			$sdate = date('n/j/y',strtotime($row['startdate']));
			$edate = date('n/j/y',strtotime($row['enddate']));
			$row['sdate'] = $sdate;
			$row['edate'] = $edate;
			$name = $row['name'];
			$row['group'] = $group;

			

			if($group == $name){
				$row['select'] = 1;
			}else{
				$row['select'] = 0;
			}
			$response[] = $row;  
		}
		return $response;
	}



	//get the rules for the ratecard corporation
	function ratecardRules($zoneid){
		$sql = "SELECT RateCardRule.fixedSeconds AS fixedseconds, RateCardRule.fixedPct AS fixedpct
		FROM Zone
		INNER JOIN RateCardRule ON Zone.corporationId = RateCardRule.corporationId
		WHERE Zone.id = $zoneid
		LIMIT 1";

		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		return $row;
	}



	//get the hot programming for the zone selected
	function hotProgramming($zoneid){
		$sql = "SELECT programs FROM RateCardsHot WHERE zoneid = $zoneid";

		$result = mysql_query($sql);

		$cnt = mysql_num_rows($result);

		if($cnt == 0){
			return '[]';
		}

		$row = mysql_fetch_assoc($result);
		$data = json_decode($row['programs']);

		$response = array();

		$i = 0;
		foreach ($data as &$value) {
			$value->showtitle = strtolower(preg_replace('/\s+/', '', $value->showtitle));
			$value->id = $i;
			$response[] = $value;
			$i++;
		}

		return $response;
	}



	function getDaypartsList($marketid){
		$sql = "SELECT dayParts AS dayparts FROM RateCardMarketDayPart WHERE marketId = $marketid";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		$dayparts = $row['dayparts'];
		$data = json_decode($dayparts);

		return $data;
	}





	function getRates($ratecardid, $dayparts){
		$sql = "SELECT rateCard AS ratecard FROM RateCardCardVersion WHERE rateCardId = $ratecardid AND working = 1";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		$rates = $row['ratecard'];

		$data = json_decode($rates);
		$response = array();


		//loop over the rate lines
		foreach ($data as &$values) {
			$row = $values;
			$networkid = $row->id;
			$callsign = $row->callsign;
			$logo = $row->logo;
			$network = array();

			//loop over the keys in the lines
			foreach ($values as $key => $value) {
				$rates = explode("|", $key);
				$daypartname = $rates[0];

				if($daypartname == "daypart"){
					$daypartid = $rates[1];
					$daypart = findDaypart($daypartid, $dayparts);

					if($daypart){
						$starts = $daypart->starttime;
						$stops = $daypart->endtime;

						//set the rate key and get the value
						$daypartkey = 'daypart|'.$daypartid;
						$rate = $row->$daypartkey;

						//set the fixed key and get the value
						$daypartkey = 'fixed|'.$daypartid;
						$ratefixed = $row->$daypartkey;

						//get the daypart days
						$days = $daypart->days;

						$d = array("id"=>$daypartid, "rate"=>$rate, "ratefixed"=>$ratefixed, "fname"=>"", "starts"=>$starts, "stops"=>$stops, "weekdays"=>$days, "callsign"=>$callsign, "networkid"=>$networkid);
						array_push($network, $d);
					}
				}
			}
			$response[$networkid] = $network;
		}
		return $response;
	}



	//find the daypart for the ratecard
	function findDaypart($daypartid, $dayparts){
		foreach ($dayparts as &$value) {
			$id = $value->key;
			if($id == $daypartid){
				return $value;
			}
		}
	}

	return;
?>





