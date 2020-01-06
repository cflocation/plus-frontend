<?php
	ini_set('display_errors', TRUE);

	$zoneid = $_GET['zoneid'];
	$cardid = $_GET['cardid'];
	$userid = $_GET['userid'];
	$tokenid = $_GET['tokenid'];
	$startdate = $_GET['startdate'];
	$group = $_GET['group'];




	//include database
	include_once('../../config/database.php');


	if($cardid > 0){
		$sql = "SELECT
		ratecard_cards.id,
		ratecard_cards.zoneid,
		ratecard_cards.marketid,
		ratecard_cards.name,
		ratecard_cards.startdate,
		ratecard_cards.enddate,
		zones.name AS zone
		FROM ratecard_cards 
		INNER JOIN zones ON zones.id = ratecard_cards.zoneid
		WHERE ratecard_cards.id = $cardid";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
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






	if($cardid == 0){

		//try to find the ratecard for the date specified
		$sql = "SELECT
			ratecard_cards.id,
			ratecard_cards.zoneid,
			ratecard_cards.marketid,
			ratecard_cards.name,
			ratecard_cards.startdate,
			ratecard_cards.enddate,
			zones.name AS zone
			FROM ratecard_cards 
			INNER JOIN zones ON zones.id = ratecard_cards.zoneid
			WHERE ratecard_cards.zoneid = $zoneid AND ratecard_cards .deletedat IS NULL AND startdate <= '$startdate' AND enddate >= '$startdate'";
		$result = mysql_query($sql);
		$cnt = mysql_num_rows($result);


		//if no rate card exists then lets rool back to the old one
		if($cnt == 0){
			$sql = "SELECT
			ratecard_cards.id,
			ratecard_cards.zoneid,
			ratecard_cards.marketid,
			ratecard_cards.name,
			ratecard_cards.startdate,
			ratecard_cards.enddate,
			zones.name AS zone
			FROM ratecard_cards 
			INNER JOIN zones ON zones.id = ratecard_cards.zoneid
			WHERE ratecard_cards.zoneid = $zoneid AND ratecard_cards .deletedat IS NULL LIMIT 1";
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
		$sql = "SELECT id, name, zoneid, startdate, enddate FROM ratecard_cards WHERE zoneid = $zoneid  AND deletedat IS NULL ORDER BY special, startdate";
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
		$sql = "SELECT ratecardrules.fixedseconds, ratecardrules.fixedpct
		FROM zones
		INNER JOIN ratecardrules ON zones.corporationid = ratecardrules.corporationid
		WHERE zones.id = $zoneid
		LIMIT 1";

		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		return $row;
	}



	//get the hot programming for the zone selected
	function hotProgramming($zoneid){
		$sql = "SELECT programs FROM ratecards_hot WHERE zoneid = $zoneid";

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
		$sql = "SELECT dayparts FROM ratecard_market_dayparts WHERE marketid = $marketid";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		$dayparts = $row['dayparts'];
		$data = json_decode($dayparts);

		return $data;
	}





	function getRates($ratecardid, $dayparts){
		$sql = "SELECT ratecard FROM ratecard_card_versions WHERE ratecardid = $ratecardid AND working = 1";
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





