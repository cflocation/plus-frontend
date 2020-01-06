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
	include_once('../../config/database.php');

	if($event == "create"){
		$zoneid = $_POST['zoneid'];
		$special = $_POST['special'];
		$startdate = $_POST['startdate'];
		$enddate = $_POST['enddate'];
		$name = $_POST['name'];
		$marketid = $_POST['marketid'];

		//insert the new ratecard
		$sql = "INSERT INTO ratecard_cards (zoneid, marketid, name, special, startdate, enddate, createdat, updatedat) VALUES ($zoneid,$marketid,'$name',$special,'$startdate','$enddate','$d','$d')";
		$result = mysql_query($sql);

		//grab the new ratecard id
		$id = mysql_insert_id();

		$sql = "INSERT INTO ratecard_card_versions (ratecardid, working, ratecard, userid, createdat, updatedat) VALUES ($id,1,'[]',$userid,'$d','$d')";
		$result = mysql_query($sql);

		print $id;
		return;
	}



	if($event == "delete"){
		$ids = $_POST['ids'];
		$idlist = implode(",", $ids);

		$sql = "UPDATE ratecard_cards SET deletedat = '$d' WHERE id IN ($idlist)";
		$result = mysql_query($sql);
 
		print $sql;
		return;
	}




	if($event == "listbymarket"){
		$marketid = $_POST['marketid'];
		
		//insert the new ratecard
		$sql = "SELECT zones.name AS zone, ratecard_cards.name, zones.broadcast, ratecard_cards.id AS id, zones.id AS zoneid, zones.syscode, ratecard_cards.startdate, ratecard_cards.enddate, ratecard_cards.special ,ratecard_cards.createdat, ratecard_cards.updatedat,
		(SELECT ratecardid FROM ratecard_card_versions WHERE ratecardid = ratecard_cards.id AND published = 1 LIMIT 1) AS published
		FROM ratecard_cards
		INNER JOIN marketzones ON marketzones.zoneid = ratecard_cards.zoneid
		INNER JOIN zones ON zones.id = ratecard_cards.zoneid
		WHERE marketzones.marketid =  $marketid AND ratecard_cards.deletedat IS NULL
		ORDER BY zones.name, ratecard_cards.name";

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
		$notes = urldecode($_POST['notes']);
		$notes = mysql_real_escape_string($notes);


		$sql = "SELECT * FROM ratecard_card_versions WHERE ratecardid = $ratecardid AND working = 1";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);

		$ratecard = $row['ratecard'];
		$ratecardid = $row['ratecardid'];


		$sql = "UPDATE ratecard_card_versions SET published = 0 WHERE ratecardid = $ratecardid";
		$result = mysql_query($sql);

		$sql = "INSERT INTO ratecard_card_versions (ratecardid, notes, working, published, ratecard, userid, createdat, updatedat) VALUES ($ratecardid,'$notes',0,1,'$ratecard',$userid,'$d','$d')";
		$result = mysql_query($sql);

		print 1;
		return;
	}














	if($event == "loadratecard"){
		$id = $_GET['id'];
		$type = $_GET['type'];



		//get the ratecard data if any
		if($type == 1){
			$sql = "SELECT * FROM ratecard_card_versions WHERE ratecardid = $id AND published = 1";
		}else{
			$sql = "SELECT * FROM ratecard_card_versions WHERE ratecardid = $id AND working = 1";
		}
		
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		$rates = json_decode($row['ratecard']);

		//insert the new ratecard
		$sql = "SELECT ratecard_cards.id, ratecard_cards.name, zones.broadcast, zones.name AS zone, zones.id AS zoneid, ratecard_cards.startdate, ratecard_cards.enddate, tms_networks.callsign, tms_networks.networkid, coalesce(logos.filename,'default.gif') AS filename
		FROM ratecard_cards
		INNER JOIN zonenetworks ON zonenetworks.zoneid = ratecard_cards.zoneid
		INNER JOIN zones ON zones.id = ratecard_cards.zoneid
		INNER JOIN tms_networks ON tms_networks.networkid = zonenetworks.networkid
		LEFT JOIN networklogos ON networklogos.networkid = tms_networks.networkid
		LEFT JOIN logos ON logos.id = networklogos.logoid
		WHERE ratecard_cards.id = $id
		ORDER BY tms_networks.callsign";


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
				    $re['logo'] = 'http://ww2.showseeker.com/images/_thumbnailsW/'.$row['filename'];

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



	    $hot = getHotProgramming($zoneid);

	    $re = array("dayparts"=>$dayparts, "data"=>$data, "info"=>$info, "hot"=>$hot);
	    print json_encode($re);
		return;
	}







	if($event == "saveratecard"){
		$ratecard = mysql_real_escape_string($_POST['json']);
		$ratecardid = $_POST['ratecardid'];
		$published = $_POST['published'];

		if($published == 1){
			$sql = "UPDATE ratecard_card_versions SET ratecard = '$ratecard', updatedat = '$d' WHERE ratecardid = $ratecardid AND published = 1";
		}else{
			$sql = "UPDATE ratecard_card_versions SET ratecard = '$ratecard', updatedat = '$d' WHERE ratecardid = $ratecardid AND working = 1";
			print $sql;
		}

		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		return;
	}





	if($event == "addbroadcastrate"){
		$ratecardid = $_POST['ratecardid'];
		$starttime = $_POST['starttime'];
		$endtime = $_POST['endtime'];
		$days = $_POST['days'];


		$sql = "SELECT ratecard_cards.id AS id, zones.id AS zoneid, zones.name, zonenetworks.networkid, tms_networks.callsign, LOWER(timezones.abbreviation) AS tz
		FROM ratecard_cards
		INNER JOIN zones ON ratecard_cards.zoneid = zones.id
		INNER JOIN zonenetworks ON zonenetworks.zoneid = zones.id
		INNER JOIN timezones ON timezones.id = zones.timezoneid
		INNER JOIN tms_networks ON tms_networks.networkid = zonenetworks.networkid
		WHERE ratecard_cards.id = $ratecardid";

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
			$sql = "UPDATE ratecard_cards SET name = '$name', special = $special, startdate = '$startdate', enddate = '$enddate', updatedat = '$d' WHERE id = $id";
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
		$sql = "SELECT  ratecard_card_versions.ratecard, ratecard_cards.marketid, ratecard_cards.zoneid
		FROM ratecard_card_versions
		INNER JOIN ratecard_cards ON ratecard_cards.id = ratecard_card_versions.ratecardid
		WHERE ratecardid = $sourceid AND working = 1";

		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		$source = json_decode($row['ratecard']);
		$marketid = $row['marketid'];
		$zoneid = $row['zoneid'];


		if($zoneid == $destinationids[0] && count($destinationids) == 1){
			$ratecard = json_encode($source);
			$ratecard = mysql_real_escape_string($ratecard);


			//insert the new ratecard
			$sql = "INSERT INTO ratecard_cards (zoneid, marketid, name, special, startdate, enddate, createdat, updatedat) VALUES ($zoneid,$marketid,'$name',$special,'$startdate','$enddate','$d','$d')";
			$result = mysql_query($sql);

			//grab the new ratecard id
			$id = mysql_insert_id();

			$sql = "INSERT INTO ratecard_card_versions (ratecardid, working, ratecard, userid, createdat, updatedat) VALUES ($id,1,'$ratecard',$userid,'$d','$d')";

			print_r($sql);

			$result = mysql_query($sql);
			exit;
		}
		

		foreach ($destinationids as &$id) {
			
			$sql = "SELECT zonenetworks.networkid, zones.broadcast
			FROM zonenetworks
			INNER JOIN zones ON zones.id = zonenetworks.zoneid
			WHERE zonenetworks.zoneid = $id AND zonenetworks.deletedat IS NULL AND zones.broadcast = 0";

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
				$sql = "INSERT INTO ratecard_cards (zoneid, marketid, name, special, startdate, enddate, createdat, updatedat) VALUES ($id,$marketid,'$name',$special,'$startdate','$enddate','$d','$d')";
				$result = mysql_query($sql);

				//grab the new ratecard id
				$id = mysql_insert_id();

				$sql = "INSERT INTO ratecard_card_versions (ratecardid, working, ratecard, userid, createdat, updatedat) VALUES ($id,1,'$ratecard',$userid,'$d','$d')";
				$result = mysql_query($sql);
			}


		}

		return;	
	}








	if($event == "copyhotprograms"){
		$hotlist = $_POST['programs'];
		$destinationids = $_POST['destinationids'];

		foreach ($destinationids as &$zoneid) {
			$sql = "SELECT * FROM ratecards_hot WHERE zoneid = $zoneid";
			$result = mysql_query($sql);
			$cnt = mysql_num_rows($result);


			if($cnt == 0){
				$programs = json_encode($programs);
				$programs = mysql_real_escape_string($programs);
				$sql = "INSERT INTO ratecards_hot (programs, zoneid, createdat, updatedat) VALUES ('$programs',$zoneid,'$d','$d')";
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
				//$programs = mysql_real_escape_string($programs);
				$sql = "UPDATE ratecards_hot SET programs = '$programs', updatedat = '$d' WHERE zoneid = $zoneid";
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




	function getHotProgramming($zoneid){
		$sql = "SELECT * FROM ratecards_hot WHERE zoneid = $zoneid";
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
		$sql = "SELECT * FROM zonenetworks WHERE zoneid = $zoneid AND deletedat IS NULL";
		$result = mysql_query($sql);
		$cnt = mysql_num_rows($result);

		if($cnt == 1){
			$row = mysql_fetch_assoc($result);
			$networkid = $row['networkid'];
			$sql = "SELECT dmanumber FROM tms_networks WHERE networkid = $networkid";
			$result = mysql_query($sql);
			$row = mysql_fetch_assoc($result);

			if($row['dmanumber'] > 0){
				return 1;
			}
		}
		return 0;
	}



	function getDayparts($id){
		$sql = "SELECT ratecard_market_dayparts.dayparts
		FROM ratecard_cards
		INNER JOIN ratecard_market_dayparts ON ratecard_market_dayparts.marketid = ratecard_cards.marketid
		WHERE ratecard_cards.id = $id";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
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



//var rateDatpart = "daypart_"+dayparts[i].key;
//var rateFixed = "fixed_"+dayparts[i].key;
//var ratePct = "pct_"+dayparts[i].key;

?>








