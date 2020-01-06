<?php
	ini_set('display_errors', TRUE);

	$zoneid = $_GET['zoneid'];
	$cardid = $_GET['cardid'];
	//$userid = $_GET['userid'];
	//$tokenid = $_GET['tokenid'];
	$startdate = $_GET['startdate'];
	$group = $_GET['group'];



	//include database
	include_once('../../config/database.php');


	if($cardid > 0){
		$sql = "SELECT		ratecard_cards.id,
								ratecard_cards.zoneid,
								ratecard_cards.marketid,
								ratecard_cards.name,
								ratecard_cards.startdate,
								ratecard_cards.enddate,
								ratecard_card_versions.published,
								zones.name AS zone,
								zones.syscode
		FROM 					ratecard_cards 
		INNER JOIN 			zones 
		ON 					zones.id = ratecard_cards.zoneid
		INNER JOIN 			ratecard_card_versions 
		ON 					ratecard_card_versions.ratecardid = ratecard_cards.id
		WHERE 				ratecard_cards.id = $cardid 
		AND 					ratecard_card_versions.published = 1";


		$result 				= mysql_query($sql);
		$row 					= mysql_fetch_assoc($result);
		$zoneid 				= $row['zoneid'];
		$syscode 			= $row['syscode'];		
		$marketid 			= $row['marketid'];


		$header 				= buildHeader($row);
		$cards 				= allRatecardsForZone($zoneid,$group,$startdate);
		$rules 				= ratecardRules($zoneid);
		$hotprograms 		= hotProgramming($cardid);

		$dayparts 			= getDaypartsList($marketid);
		$rates 				= getRates($zoneid);


		$re 					= array(	"responseHeader"=>$header,
											"ratecards"=>$cards,
											"dayparts"=>$dayparts,
											"rule"=>$rules,
											"hotprograms"=>$hotprograms,
											"response"=>$rates);
		print json_encode($re);
		return;
	}



	if($cardid == 0){

		//try to find the ratecard for the date specified
		$sql = "SELECT					'' as id,
											ShowSeeker.zones.id as zoneid,
											ShowSeeker.marketzones.marketid,
											'' as name,
											Customers.charter_ratecards.start_date as startdate,
											Customers.charter_ratecards.end_date as enddate,
											ShowSeeker.zones.name AS zone,
											ShowSeeker.zones.syscode
			FROM 							Customers.charter_ratecards 
			INNER JOIN 					ShowSeeker.zones 
			ON 							ShowSeeker.zones.syscode = Customers.charter_ratecards.syscode
			INNER JOIN 					ShowSeeker.marketzones 
			ON 							ShowSeeker.zones.id = ShowSeeker.marketzones.zoneid
			WHERE 						ShowSeeker.zones.id = ".$zoneid." 
			AND 							ShowSeeker.zones.deletedat IS NULL
			AND 							Customers.charter_ratecards.start_date <= '$startdate' 
			AND 							Customers.charter_ratecards.end_date >= '$startdate'
			ORDER BY 					Customers.charter_ratecards.END_DATE DESC
			LIMIT 						1";


		$result 	= mysql_query($sql);
		$cnt 		= mysql_num_rows($result);


		//if no rate card exists then lets rool back to the old one
		if($cnt == 0){
			$sql ="SELECT					'' as id,
											ShowSeeker.zones.id as zoneid,
											ShowSeeker.marketzones.marketid,
											'' as name,
											Customers.charter_ratecards.start_date as startdate,
											Customers.charter_ratecards.end_date as enddate,
											ShowSeeker.zones.name AS zone,
											ShowSeeker.zones.syscode
			FROM 							Customers.charter_ratecards 
			INNER JOIN 					ShowSeeker.zones 
			ON 							ShowSeeker.zones.syscode = Customers.charter_ratecards.syscode
			INNER JOIN 					ShowSeeker.marketzones 
			ON 							ShowSeeker.zones.id = ShowSeeker.marketzones.zoneid
			WHERE 						ShowSeeker.zones.id = ".$zoneid." 
			AND 							ShowSeeker.zones.deletedat IS NULL 
			ORDER BY 					Customers.charter_ratecards.END_DATE DESC
			LIMIT 						1";			
			
			$result = mysql_query($sql);
		}	


		$cnt 		= mysql_num_rows($result);

		if($cnt == 0){
			print '-1';
			return;
		}

		$row 			 = mysql_fetch_assoc($result);
		$cardid 		 = $row['id'];
		$zoneid 		 = $row['zoneid'];
		$syscode		 = $row['syscode'];
		$edate		 = $row['enddate'];
		$marketid 	 = $row['marketid'];

		$header 		 = buildHeader($row);
		$cards 		 = allRatecardsForZone($zoneid,$group,$startdate);
		$rules 		 = ratecardRules($zoneid);
		$hotprograms = hotProgramming($cardid);

		$dayparts 	 = getDaypartsList($marketid);
		$rates 		 = getRates($syscode, $edate);

		$re 			 = array("responseHeader"=>$header,
									"ratecards"=>$cards,
									"dayparts"=>$dayparts,
									"rule"=>$rules,
									"hotprograms"=>$hotprograms,
									"response"=>$rates);
		print json_encode($re);
		return;
	}







	//build the header file for the ratecard
	function buildHeader($row){
		$id 			= $row['id'];
		$longstart 	= $row['startdate'];
		$longend 	= $row['enddate'];
		$startdate 	= date('n/j/y',strtotime($row['startdate']));
		$enddate 	= date('n/j/y',strtotime($row['enddate']));
		$label 		= $row['name'].' - '.$startdate.' to '.$enddate;
		$zoneid 		= $row['zoneid'];
		$zone 		= $row['zone'];

		$header 		= array(	"location"=>"www.showseeker.com/ezrates/services/loadratecard2.php", 
									"ratecardtype"=>4, 
									"startdate"=>$longstart, 
									"enddate"=>$longend,
									"zoneid"=>$zoneid,
									"zone"=>$zone,
									"name"=>$row['name'], 
									"label"=>$label, "id"=>$id);
		return $header;
	}



	//list all the avaiable ratecards for this zone
	function allRatecardsForZone($zoneid,$group,$startdate){
		$sql = "SELECT					DISTINCT '' as id,
											ShowSeeker.zones.id as zoneid,
											ShowSeeker.marketzones.marketid,
											'' as name,
											Customers.charter_ratecards.start_date as startdate,
											Customers.charter_ratecards.end_date as enddate,
											ShowSeeker.zones.name AS zone
			FROM 							Customers.charter_ratecards 
			INNER JOIN 					ShowSeeker.zones 
			ON 							ShowSeeker.zones.syscode = Customers.charter_ratecards.syscode
			INNER JOIN 					ShowSeeker.marketzones 
			ON 							ShowSeeker.zones.id = ShowSeeker.marketzones.zoneid
			WHERE 						ShowSeeker.zones.id = ".$zoneid." 
			AND 							ShowSeeker.zones.deletedat IS NULL 
			AND 							Customers.charter_ratecards.end_date >= '$startdate'
			ORDER BY 					Customers.charter_ratecards.end_date";


		$result 		= mysql_query($sql);
		$response 	= array();

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
		$sql = "	SELECT 			ratecardrules.fixedseconds, 
										ratecardrules.fixedpct, 
										ratecardrules.rotatortype
			FROM 						zones
			INNER JOIN 				ratecardrules 
			ON 						zones.corporationid = ratecardrules.corporationid
			WHERE 					zones.id = $zoneid
			LIMIT 1";

		$result 		= mysql_query($sql);
		$row 			= mysql_fetch_assoc($result);
		return $row;
	}



	//get the hot programming for the zone selected
	function hotProgramming($cardid){
		$sql = "	SELECT 			programs 
			FROM 						ratecards_hot 
			WHERE 					ratecardid = 0";

		$result 		= mysql_query($sql);
		$cnt 			= mysql_num_rows($result);

		if($cnt == 0){
			return '[]';
		}

		$row 			= mysql_fetch_assoc($result);
		$data 		= json_decode($row['programs']);
		$response 	= array();
		$i 			= 0;
		
		
		foreach ($data as &$value) {
			$value->showtitle = strtolower(preg_replace('/\s+/', '', $value->showtitle));
			$value->id = $i;
			$response[] = $value;
			$i++;
		}

		return $response;
	}



	function getDaypartsList($marketid){
		
	
		$sql="SELECT 			 	CAST(rand(3)*10000 AS UNSIGNED) AS id,
										START_TIME 	as starttime, 
										END_TIME 	as endtime, 
										DAYPART		as daypart, 
										DAYS			as days, 
										Replace(UUID(),'-','') AS dkey,
										Customers.charter_ratecards.CREATEDAT AS createdat, 
										Customers.charter_ratecards.CREATEDAT AS updatedat,  
										''	AS deletedat,
										CAST(ABS(CAST(TIMEDIFF(END_TIME,START_TIME) AS SIGNED))/10000 AS SIGNED) AS diff 
				FROM 					Customers.charter_ratecards
				INNER JOIN 			ShowSeeker.zones
				ON						ShowSeeker.zones.syscode = Customers.charter_ratecards.syscode
				INNER JOIN 			ShowSeeker.marketzones 
				ON 					ShowSeeker.zones.id = ShowSeeker.marketzones.zoneid 
				INNER JOIN			ShowSeeker.regions 
				ON 					ShowSeeker.marketzones.marketid = ShowSeeker.regions.id
				WHERE 				ShowSeeker.regions.id = ".$marketid."
				AND 					ShowSeeker.zones.deletedat is null 
				GROUP BY				START_TIME, 
										END_TIME, 
										DAYPART, 
										DAYS";
										
		$result 		= mysql_query($sql);
		
		$response 	= array();

		while($row = mysql_fetch_assoc($result)){
			$response[] = $row;  
		}
		
		return $response;		
		
	}





	function getRates($syscode, $eDate){
	
		$response = array();
		$logobase = 'http://ww2.showseeker.com/images/_thumbnailsW/';
		
		$sql =	"SELECT 		tms_networks.NETWORKID, 
									tms_networks.CALLSIGN, 
									FILENAME
		FROM						zonenetworks
		INNER JOIN				zones
		ON							zonenetworks.zoneid = zones.id
		INNER JOIN 				tms_networks
		ON							tms_networks.networkid = zonenetworks.networkid
		LEFT OUTER JOIN 		networklogos
		ON							tms_networks.networkid = networklogos.networkid
		LEFT OUTER JOIN 		logos 
		ON							logos.id = networklogos.logoid
		WHERE 					zones.syscode =".$syscode."
		ORDER BY 				tms_networks.callsign";

		$result 			= mysql_query($sql);
		$stations 		= mysql_fetch_assoc($result);				
		
		while($station 		= mysql_fetch_assoc($result)){
			$networkid 	= $station["NETWORKID"];
			$callsign 	= $station["CALLSIGN"];
			$logo 		= $logobase.$station["FILENAME"];
			$network 	= array();

			$sql_rate = "SELECT 	Customers.charter_ratecards.rate as rate,
										(Customers.charter_ratecards.rate+Customers.charter_ratecards.rate*.25) AS ratefixed,
										Customers.charter_ratecards.start_time as starttime,
										Customers.charter_ratecards.end_time as endtime,
										Customers.charter_ratecards.days as days,
										Customers.charter_ratecards.daypart as daypart
						FROM 			Customers.charter_ratecards
						WHERE  		syscode = $syscode
						AND			Customers.charter_ratecards.NETWORK_ID = $networkid
						AND 			end_date = '$eDate'";
						
						

			$result_rates 		= mysql_query($sql_rate);
			$network = array();
			while($rates = mysql_fetch_assoc($result_rates)){

				$d = array(	"id"=>$rates['daypart'], 
											"rate"=>$rates['rate'], 
											"ratefixed"=>$rates['ratefixed'], 
											"fname"=>"", 
											"starts"=>$rates['starttime'], 
											"stops"=>$rates['endtime'], 
											"weekdays"=>$rates['days'], 
											"callsign"=>$callsign, 
											"networkid"=>$networkid);

				array_push($network, $d);

			}
			
			$response[$networkid] = $network;

		
		}
		
			return $response;
	}









function queryOfQuery(	$rs, 							// The recordset to query
  								$fields = "*", 			// optional comma-separated list of fields to return, or * for all fields 
							  	$distinct = false, 		// optional true for distinct records
							  	$fieldToMatch = null, 	// optional database field name to match
 		 						$valueToMatch = null) { // optional value to match in the field, as a comma-separated list

  $newRs 			= Array();
  $row 				= Array();
  $valueToMatch 	= explode(",",$valueToMatch);
  $matched 			= true;

  mysql_data_seek($rs, 0);

  if($rs) {
    while ($row_rs = mysql_fetch_assoc($rs)){
      if($fields == "*") {
        if($fieldToMatch != null) {
          $matched = false;
          if(is_integer(array_search($row_rs[$fieldToMatch],$valueToMatch))){ 
            $matched = true;
          }
        }
        if($matched) $row = $row_rs;
      }else{
        $fieldsArray=explode(",",$fields);
        foreach($fields as $field) {
          if($fieldToMatch != null) {
            $matched = false;
            if(is_integer(array_search($row_rs[$fieldToMatch],$valueToMatch))){
              $matched = true;
            }
          }
          if($matched) $row[$field] = $row_rs[$field];
        }
      } 
      if($matched)array_push($newRs, $row);
    };
    if($distinct) {
      sort($newRs);
      for($i = count($newRs)-1; $i > 0; $i--) {
        if($newRs[$i] == $newRs[$i-1]) unset($newRs[$i]);
      }
    }
  }
  mysql_data_seek($rs, 0);
  return $newRs;
}



?>