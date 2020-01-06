<?php
	include_once('../../inc/permissions.php');
	include_once('../../inc/geturl.php');

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
	//include_once('../../config/database.php');
	include_once('../include/database.php');

	if($event == "create"){
		$zoneid = $_POST['zoneid'];
		$special = $_POST['special'];
		$startdate = $_POST['startdate'];
		$enddate = $_POST['enddate'];
		$name = $_POST['name'];
		$marketid = $_POST['marketid'];

		//insert the new ratecard
		$sql = "INSERT INTO RateCard (zoneId, marketId, name, special, startDate, endDate, createdAt, updatedAt) VALUES ($zoneid,$marketid,'$name',$special,'$startdate','$enddate','$d','$d')";
		$result = mysql_query($sql);

		//grab the new ratecard id
		$id = mysql_insert_id();

		$sql = "INSERT INTO RateCardCardVersion (rateCardId, working, rateCard, userId, createdAt, updatedAt) VALUES ($id,1,'[]',$userid,'$d','$d')";
		$result = mysql_query($sql);

		print $id;
		return;
	}

	if($event == "delete"){
		$ids = $_POST['ids'];
		$idlist = implode(",", $ids);

		$sql = "UPDATE RateCard SET deletedAt = '$d' WHERE id IN ($idlist)";
		$result = mysql_query($sql);
 
		print $sql;
		return;
	}

	if($event == "listbymarket"){
		$marketid = $_POST['marketid'];
		
		//insert the new ratecard
		$sql = "SELECT Zone.name AS zone, RateCard.name, Zone.broadcast, RateCard.id AS id, Zone.id AS zoneid, Zone.sysCode AS syscode, RateCard.startDate AS startdate, RateCard.endDate AS enddate
					, RateCard.special ,RateCard.createdAt AS createdat, RateCard.updatedAt AS updatedat,
					(SELECT rateCardId AS ratecardid FROM RateCardCardVersion WHERE rateCardId = RateCard.id AND published = 1 LIMIT 1) AS published
					FROM RateCard
					INNER JOIN MarketZone ON MarketZone.zoneId = RateCard.zoneId
					INNER JOIN Zone ON Zone.id = RateCard.zoneId
					WHERE MarketZone.marketId =  $marketid AND RateCard.deletedAt IS NULL
					ORDER BY Zone.name, RateCard.name";

		$result = mysql_query($sql);
		$cnt = mysql_num_rows($result);

		if($cnt == 0){
			print 0;
			return;
		}

		//loop over and add to list
	    while($row = mysql_fetch_assoc($result)) {
	    	$data[] = $row;
	    }

	    print json_encode($data);
		return;
	}


	if($event == "publish"){
		$ratecardid = $_POST['ratecardid'];
		//$notes = urldecode($_POST['notes']);
		//$notes = mysql_real_escape_string($notes);


		$sql = "SELECT id, rateCardId AS ratecardid, notes, published, working, rateCard AS ratecard, userId AS userid, createdAt AS createdat, updatedAt AS updatedat, deletedAt AS deletedat 
				FROM   RateCardCardVersion 
				WHERE  rateCardId = $ratecardid 
				AND    working = 1";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);

		$ratecard = $row['ratecard'];
		$ratecardid = $row['ratecardid'];


		$sql = "UPDATE RateCardCardVersion SET published = 0 WHERE rateCardId = $ratecardid";
		$result = mysql_query($sql);

		$ratecardFix = mysql_real_escape_string($ratecard);

		$sql = "INSERT INTO RateCardCardVersion (rateCardId, working, published, rateCard, userId, createdAt, updatedAt) VALUES ($ratecardid,0,1,'$ratecardFix',$userid,'$d','$d')";
		$result = mysql_query($sql);

		print 1;
		return;
	}


	if($event == "publish-group"){
		$ratecardids = $_POST['ratecardid'];
		$notes = "";


		foreach ($ratecardids as &$ratecardid) {
			$sql = "SELECT id, rateCardId AS ratecardid, notes, published, working, rateCard AS ratecard, userId AS userid, createdAt AS createdat, updatedAt AS updatedat, deletedAt AS deletedat 
					FROM   RateCardCardVersion 
					WHERE  rateCardId = $ratecardid 
					AND    working = 1";
			$result = mysql_query($sql);
			$row = mysql_fetch_assoc($result);

			$ratecard = $row['ratecard'];
			$ratecardid = $row['ratecardid'];


			$sql = "UPDATE RateCardCardVersion SET published = 0 WHERE rateCardId = $ratecardid";
			$result = mysql_query($sql);

			$sql = "INSERT INTO RateCardCardVersion (rateCardId, notes, working, published, rateCard, userId, createdAt, updatedAt) VALUES ($ratecardid,'$notes',0,1,'$ratecard',$userid,'$d','$d')";
			$result = mysql_query($sql);

			print_r($ratecardid);
		}

		return;
	}


	if($event == "loadratecard"){
		$id = $_GET['id'];
		$type = $_GET['type'];



		//get the ratecard data if any
		if($type == 1){
			$sql = "SELECT id, rateCardId AS ratecardid, notes, published, working, rateCard AS ratecard, userId AS userid, createdAt AS createdat, updatedAt AS updatedat, deletedAt AS deletedat FROM RateCardCardVersion WHERE rateCardId = $id AND published = 1";
		}else{
			$sql = "SELECT id, rateCardId AS ratecardid, notes, published, working, rateCard AS ratecard, userId AS userid, createdAt AS createdat, updatedAt AS updatedat, deletedAt AS deletedat FROM RateCardCardVersion WHERE rateCardId = $id AND working = 1";
		}
		
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		$rates = json_decode($row['ratecard']);

		//insert the new ratecard
		$sql = "SELECT RateCard.id, RateCard.name, Zone.broadcast, Zone.name AS zone, Zone.id AS zoneid, RateCard.startDate AS startdate, RateCard.endDate AS enddate, Network.callSign AS callsign
					, Network.networkId AS networkid, 'default.gif' AS filename
					FROM RateCard
					INNER JOIN ZoneNetwork ON ZoneNetwork.zoneId = RateCard.zoneId
					INNER JOIN Zone ON Zone.id = RateCard.zoneId
					INNER JOIN Network ON Network.networkId = ZoneNetwork.networkId
					WHERE RateCard.id = $id
					ORDER BY Network.callsign";


		//$dp = getDayparts($id);
		$dayparts = getDayparts($id);
		//$daypsrtsarr = json_decode($dp);

		$result = mysql_query($sql);
		$cnt = mysql_num_rows($result);
		

		$info = mysql_fetch_assoc($result);


		$zoneid = $info['zoneid'];
		$broadcast = $info['broadcast'];

		$info = array('id'=>$info['id'],'zone'=>$info['zone'],'zoneid'=>$zoneid,'name'=>$info['name'],'broadcast'=>$broadcast);
		mysql_data_seek($result,0);


		if($broadcast == 0){

		    while($row = mysql_fetch_assoc($result)) {
		    	$name = $row['name'];
		    	$re = array();
		    	$stationid = $row['networkid'];

				foreach ($dayparts as &$daypart) {
				    $ratedatpart = 'daypart|'.$daypart->key;
				    $ratefixed = 'fixed|'.$daypart->key;
				    $ratepct = 'pct|'.$daypart->key;

				    $raterow = findNetworkRates($rates,$stationid);

				    //print_r($raterow);

				    $re['callsign'] = $row['callsign'];
				    $re['id'] = $row['networkid'];
				    $re['logo'] = "https://showseeker.s3.amazonaws.com/images/netwroklogo/75/{$row['networkid']}.png"; //'http://ww2.showseeker.com/images/_thumbnailsW/'.$row['filename'];

				    if(isset($raterow->$ratedatpart)){
				    	$re[$ratedatpart] = $raterow->$ratedatpart;
				    }else{
				    	$re[$ratedatpart] = 0;
				    }

				    if(isset($raterow->$ratefixed)){
				    	//var fixed2pct = (rate/frate * 100) - 100;
				    	$rate = $raterow->$ratedatpart;
				    	$frate = $raterow->$ratefixed;
				    	

				    	if($frate != $rate && $rate >0){
				    		$z = ($frate/$rate * 100) - 100;
				    	}else{
				    		$z = 0;
				    	}

				    	$pct = (int)$z;

				    	if($frate == 0){
				    		$pct = 0;
				    	}

				    	$re[$ratefixed] = $raterow->$ratefixed;
				    	$re[$ratepct] = $pct;
				    }else{
				    	$re[$ratefixed] = 0;
				    	$re[$ratepct] = 0;
				    }
				}

				$data[] = $re;

		    }
		}

	
		if($broadcast == 1){
			$data = $row['ratecard'];
		}



	    $hot = getHotProgramming($id);

	    $re = array("dayparts"=>$dayparts, "data"=>$data, "info"=>$info, "hot"=>$hot);
	    print json_encode($re);
		return;
	}


	if($event == "saveratecard"){
		$ratecard   = mysql_real_escape_string($_POST['json']);
		$ratecardid = $_POST['ratecardid'];
		$published  = $_POST['published'];
		$sql        = '';
		
		if($published == 1){
			$sql = "UPDATE RateCardCardVersion SET rateCard = '$ratecard', updatedAt = '$d' WHERE rateCardId = $ratecardid AND published = 1";
		}else{
			$sql = "UPDATE RateCardCardVersion SET rateCard = '$ratecard', updatedAt = '$d' WHERE rateCardId = $ratecardid AND working = 1";
		}
		$result = mysql_query($sql);
		return;
	}


	if($event == "addbroadcastrate"){
		$ratecardid = $_POST['ratecardid'];
		$starttime = $_POST['starttime'];
		$endtime = $_POST['endtime'];
		$days = $_POST['days'];


		$sql = "SELECT RateCard.id AS id, Zone.id AS zoneid, Zone.name, ZoneNetwork.networkId AS networkid, Network.callSign AS callsign, LOWER(TimeZone.abbreviation) AS tz
					FROM RateCard
					INNER JOIN Zone ON RateCard.zoneId = Zone.id
					INNER JOIN ZoneNetwork ON ZoneNetwork.zoneId = Zone.id
					INNER JOIN TimeZone ON TimeZone.id = Zone.timeZoneId
					INNER JOIN Network  ON Network.networkId = ZoneNetwork.networkId
					WHERE RateCard.id = $ratecardid";

		$result = mysql_query($sql);
		$cnt = mysql_num_rows($result);

		if($cnt != 1){
			print json_encode("invalid");
			return;
		}

		$row = mysql_fetch_assoc($result);
		$networkid = $row['networkid'];
		$callsign = $row['callsign'];
		$tz = $row['tz'];

		$url= 'http://solr.showseeker.net:8983/solr/gracenote/select/?q=*%3A*&version=2.2&start=0&indent=on&wt=json&fq=-sort:"Paid Programming"&fq=projected:0&group=true&group.field=title&fl=title&sort=sort asc&rows=100';
		$times = "&fq=start_".$tz.":[".$starttime." TO ".$endtime."]";
		$daylist = formatSolrDays($days,$tz);
		$station = "&fq=stationnum:".$networkid;

		$url.=$times.$daylist.$station;
		$solr = getUrl($url);
		
		$rows = json_decode($solr);

		$titlearray = $rows->grouped->title->groups;

		if(count($titlearray) == 0){
			$titles = array();
		}else{
			foreach ($titlearray as &$value) {
				$titles[] = $value->doclist->docs[0]->title;
			}
		}


		$re = array("titles"=>$titles,'starttime'=>$starttime,'endtime'=>$endtime,'days'=>formatDays($days),'networkid'=>$networkid,'callsign'=>$callsign);
		print json_encode($re);
		return;
	}

	if($event == "editratecards"){
		$name = $_POST['name'];
		$special = $_POST['special'];
		$startdate = $_POST['startdate'];
		$enddate = $_POST['enddate'];
		$rows = $_POST['rows'];

		foreach ($rows as &$id) {
			$sql = "UPDATE RateCard SET name = '$name', special = $special, startDate = '$startdate', endDate = '$enddate', updatedAt = '$d' WHERE id = $id";
			$result = mysql_query($sql);
		}
		print 1;
		return;
	}


	if($event == "copyratecard"){
		$sourceid = $_POST['sourceid'];
		$destinationids = $_POST['destinationids'];

		$name = $_POST['name'];
		$special = $_POST['special'];
		$startdate = $_POST['startdate'];
		$enddate = $_POST['enddate'];

		//grab the source ratecard
		$sql      = "SELECT  RateCardCardVersion.rateCard AS ratecard, RateCard.marketId AS marketid, RateCard.zoneId AS zoneid
						FROM RateCardCardVersion
						INNER JOIN RateCard ON RateCard.id = RateCardCardVersion.rateCardId
						WHERE rateCardId = $sourceid AND working = 1";
		$result   = mysql_query($sql);
		$row      = mysql_fetch_assoc($result);
		$source   = json_decode($row['ratecard']);
		$marketid = $row['marketid'];
		$zoneid   = $row['zoneid'];


		if($zoneid == $destinationids[0] && count($destinationids) == 1){
			$ratecard = json_encode($source);
			$ratecard = mysql_real_escape_string($ratecard);


			//insert the new ratecard
			$sql = "INSERT INTO RateCard (zoneId, marketId, name, special, startDate, endDate, createdAt, updatedAt) VALUES ($zoneid,$marketid,'$name',$special,'$startdate','$enddate','$d','$d')";
			$result = mysql_query($sql);

			//grab the new ratecard id
			$id = mysql_insert_id();

			$sql = "INSERT INTO RateCardCardVersion (rateCardId, working, rateCard, userId, createdAt, updatedAt) VALUES ($id,1,'$ratecard',$userid,'$d','$d')";

			print_r($sql);

			$result = mysql_query($sql);
			exit;
		}
		

		foreach ($destinationids as &$id) {
			
			$sql    = "SELECT ZoneNetwork.networkId AS networkid, Zone.broadcast
						FROM ZoneNetwork
						INNER JOIN Zone ON Zone.id = ZoneNetwork.zoneId
						WHERE ZoneNetwork.zoneId = $id AND ZoneNetwork.deletedAt IS NULL AND Zone.broadcast = 0";
			$result = mysql_query($sql);
			$cnt    = mysql_num_rows($result);

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
				$sql    = "INSERT INTO RateCard (zoneId, marketId, name, special, startDate, endDate, createdAt, updatedAt) VALUES ($id,$marketid,'$name',$special,'$startdate','$enddate','$d','$d')";
				$result = mysql_query($sql);

				//grab the new ratecard id
				$id     = mysql_insert_id();

				$sql    = "INSERT INTO RateCardCardVersion (rateCardId, working, rateCard, userId, createdAt, updatedAt) VALUES ($id,1,'$ratecard',$userid,'$d','$d')";
				$result = mysql_query($sql);
			}


		}

		return;	
	}


	if($event == "copyhotprograms"){
		$hotlist = $_POST['programs'];
		$destinationids = $_POST['destinationids'];

		$nprograms = json_encode($hotlist);
		$nprograms = mysql_real_escape_string($nprograms);

		foreach ($destinationids as &$ratecardid) {
			$sql = "SELECT * FROM RateCardsHot WHERE ratecardid = $ratecardid";
			$result = mysql_query($sql);
			$cnt = mysql_num_rows($result);


			if($cnt == 0){
				$sql = "INSERT INTO RateCardsHot (programs, ratecardid, createdat, updatedat) VALUES ('$nprograms',$ratecardid,'$d','$d')";
				$result = mysql_query($sql);
			}else{
				$row = mysql_fetch_assoc($result);
				$hotprograms = json_decode($row['programs']);


				foreach ($hotlist as &$hot) {
					$id = $hot['id'];
					$f = findNetworkID($id,$hotprograms);
					if($f->id === 0){
						array_push($hotprograms, $hot);
					}
				}			

				$programs = json_encode($hotprograms);
				$programs = mysql_real_escape_string($programs);
				$sql = "UPDATE RateCardsHot SET programs = '$programs', updatedat = '$d' WHERE ratecardid = $ratecardid";
				$result = mysql_query($sql);
			}
		}
		print 1;
		return;
	}


	function findNetworkID($id,$source){
		foreach ($source as &$value) {
			if(is_object($value) && $value->id == $id){
				return $value;
			}
		}

		$obj = (object) array('id' => 0);
		return $obj;
	}

	function formatSolrDays($days,$tz){
		$re = "&fq=";

		if($days[0] == '1,2,3,4,5,6,7'){
			return;
		}

		if($days[0] == '1,7'){
			$days = array(1,7);
		}

		if($days[0] == '2,3,4,5,6'){
			$days = array(2,3,4,5,6);
		}

		foreach ($days as &$value) {

			$re.='day_'.$tz.':'.$value.'+';
		}	

		return $re;
	}

	function formatDays($days){

		if($days[0] == '1,2,3,4,5,6,7'){
			return '1,2,3,4,5,6,7';
		}

		if($days[0] == '1,7'){
			return '1,7';
		}


		if($days[0] == '2,3,4,5,6'){
			return '2,3,4,5,6';
		}

		$commaList = implode(', ', $days);
		return $commaList;
	}

	function doBroadcast($result,$info){
		$re = array("dayparts"=>'[]', "data"=>'[]', "info"=>$info, "hot"=>'[]');
		print json_encode($re);
	}

	function getHotProgramming($id){
		$sql = "SELECT * FROM RateCardsHot WHERE ratecardid = $id";
		$result = mysql_query($sql);
		$cnt = mysql_num_rows($result);

		if($cnt == 0){
			$re = array();
			return $re;
		}
		
		$row = mysql_fetch_assoc($result);
		$re = json_decode($row['programs']);
		return $re;
	}

	function isBroadcast($zoneid){
		$sql = "SELECT zoneId AS zoneid, networkId AS networkid, createdAt AS createdat, updatedAt AS updatedat FROM ZoneNetwork WHERE zoneId = $zoneid AND deletedAt IS NULL";
		$result = mysql_query($sql);
		$cnt = mysql_num_rows($result);

		if($cnt == 1){
			$row = mysql_fetch_assoc($result);
			$networkid = $row['networkid'];
			$sql = "SELECT dmaNumber AS dmanumber FROM Network WHERE networkId = $networkid";
			$result = mysql_query($sql);
			$row = mysql_fetch_assoc($result);

			if($row['dmanumber'] > 0){
				return 1;
			}
		}
		return 0;
	}

	function getDayparts($id){
		$sql      = "SELECT RateCardMarketDayPart.dayParts AS dayparts
						FROM RateCard
						INNER JOIN RateCardMarketDayPart ON RateCardMarketDayPart.marketId = RateCard.marketId
						WHERE RateCard.id = $id";
		$result   = mysql_query($sql);
		$row      = mysql_fetch_assoc($result);
		$dayparts = json_decode($row['dayparts']);
		return $dayparts;
	}

	function findNetworkRates($rates,$stationid){
		foreach ($rates as &$value) {
			if($value->id == $stationid){
				return $value;
			}
		}
	}




