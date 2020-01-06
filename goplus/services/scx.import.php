<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	include_once('../../config/mysqli.php');
	
	if ($_FILES[0]['error'] > 0){
		echo "Error: " . $_FILES[0]['error'] . "<br />"; 
		exit;
	}
	elseif (($handle = fopen($_FILES[0]['tmp_name'], "r")) !== FALSE){
		$scxFile 	= $_FILES[0]['tmp_name'];
		$name 		= substr(str_replace('.scx', '', $_FILES[0]['name']),0,70);
	}
	
	$xml 			= simplexml_load_file($scxFile);
	$document 		= $xml->document;
	$j				= 1;
	$proposalDate 	= $document->date;
	$fullArray 		= array();
	$orderArray 	= array();
	$pslzones		= array();
	$pslstartDate	= array();
	$pslendDate		= array();
	$pslSpots		= array();
	$pslTotal		= array();
	$znline			= array();	
	$unknowline		= array();	
	$unknowzone		= array();	
	$userid 		= $_POST['userid'];
	$usesstitles 	= $_POST['sstitle'];	
	$corpid			= getUserCorp($con,$userid);
	$weekCount 		= "";
	try{
		
		
		foreach ($xml->campaign->order as $order){
			
			foreach($order->systemOrder as $zn){
	
				$zone  	= mapZone($con,(string)$zn->system->syscode,$corpid);	
					
				if(! empty($zone)){
					$weeks 	= getWeeks($zn->weeks->week);
					$pslzones[]  = $zone['name'];
					
					foreach($zn->detailLine as $line){
						$newLine = mapLine($con,$line,$zone,$weeks,$pslstartDate,$pslendDate,$pslSpots,$pslTotal,$usesstitles);
						if($newLine['stationmapped'] == '1'){
							$znline[] = $newLine['line'];
						}
						else{
							$unknowline[]=$newLine['line'];
						}	
					}
				}
				else{
					//$unknowzone[] = (string)$zn->system->syscode;
					$unknowzone[] = (string)$zn->system->name;
				}
			}
		}
	
		
		if(!empty($znline)){
			array_unique($pslzones);
			$linesTotal		= count($pslstartDate);
			$pslStartDate 	= min($pslstartDate);
			$pslEndDate 	= max($pslendDate);
			$psllzones 		= implode(',',$pslzones);
			$proposaljs 	= json_encode($znline);
			$proposal 		= mysqli_real_escape_string($con,$name);
			$proposalid 	= createProposal($con,$userid,$name,$proposaljs,$pslzones,$pslStartDate,$pslEndDate,$linesTotal,$pslSpots,$pslTotal);
		}
		else{		
			$name 			= '';
			$proposalid 	= 0;
		}

	header('Content-Type: application/json');
	echo json_encode(array('proposalid'=>$proposalid,'proposal'=>$znline,'proposalname'=>$name,'nonmappedzone'=>json_encode($unknowzone),'nonmappednet'=>json_encode($unknowline)));
	
	}
	catch(Exception $e){
		header('Content-Type: application/json');		
		echo json_encode(array('error'=>$e->getMessage()));		
	}
	exit;
	
	
	
	function mapLine($con,$line, $zone,$weeks,&$pslstartDate,&$pslendDate,&$pslSpots,&$pslTotal,$usesstitles){
		
		foreach($line->network->ID as $scxnet){		
			if($scxnet->code->attributes()->codeOwner == 'Spotcable' || $scxnet->code->attributes()->codeOwner == 'NCC'){
				$station     = array('networkid'=>(string)$scxnet->code->attributes()->codeDescription,'callsign'=>(string)$scxnet->code,'name'=>(string)$line->network->name, 'mapped'=>'0');
			}
		}

		$stationInfo = mapStation($con, $zone['id'], $station);		
		$days 		 = mapDays($line);
		$times		 = mapTimes($line);
		if(getTitle($line) != ''){
			$title		 = getTitle($line);
		}
		else{
			$title		 = 'Various';			
		}
		$rate		 = getRate($line);
		$totalcost	 = getTotalCost($line);
		$spotsbyWk 	 = getSpotsByWeek($line,$weeks);
		$totalspots	 = $spotsbyWk['totalSpots'];
		$dates	 	 = getDates($weeks,$days,$times);
		$formatStartDateTimeClean = preg_replace('/[^A-Za-z0-9\-]/', '', $dates['startdatetime']);

		$linetype 	 = getLineType($spotsbyWk['activeWeeks'],$days,$dates['startdatetime'],$dates['enddatetime']);

		if($stationInfo['mapped'] == '1' && $usesstitles == '1')
			$titles  = getTitles($dates['startdate'], $times['stime'], $dates['enddate'], $times['etime'], $zone['abbreviation'], $stationInfo['networkid'], $days, $title, $con);
		else
			$titles	 = array('ids'=>'0','titles'=>$title, 'desc'=>'', 'epiTitle'=>'','ssid'=>uniqid());

		$pslstartDate[]	= $dates['startdatetime'];
		$pslendDate[]	= $dates['enddatetime'];
		$pslSpots[]		= $totalspots;
		$pslTotal[]		= $rate*$totalspots;
		
		$jsonline = array(	'callsign'=>$stationInfo['callsign'],
							'cols'=>count($weeks),		
							'cost'=> $totalcost, 
							'day'=>$days, 
							'dayFormat'=>formatDays($days), 
							'desc'=>$titles['desc'], 
							'enddate'=> $dates['enddate'], 
							'endtime'=>$times['etime'], 
							'enddatetime'=> $dates['enddatetime'], 
							'epititle'=>$titles['epiTitle'], 
							'genre'=>'', 
							'id'=>uniqid().'-'.$zone['id'], 
							'isnew'=>'', 
							'lineactive'=>1,
							'live'=>'',
							'linetype'=>$linetype, 
							'linenum'=>'', 
							'locked'=>'false', 
							'orgairdate'=>'',
							'premiere'=>'', 
							'programid'=>'', 
							'rate'=>$rate, 
							'ratecardid'=>'0', 
							'ratevalue'=>'0',
							'search'=>'',
							'showid'=>$titles['ids'],
							'split'=>'0',
							'spots'=>$totalspots,
							'spotsweek'=> floor($totalspots/$spotsbyWk['activeWeeks']),
							'stationname'=>$stationInfo['name'], 
							'stationnum'=>$stationInfo['networkid'],
							'startdate'=> $dates['startdate'], 
							'starttime'=>$times['stime'], 
							'startdatetime'=> $dates['startdatetime'], 
							'stars'=>'',
							'ssid'=>$titles['ssid'],
							'title'=> $titles['titles'], 
							'timestamp'=>date('Y-m-d H:i:s'),
							'total'=> $rate*$totalspots, 
							'totalspots'=> $spotsbyWk['totalSpots'],
							'weeks'=>$spotsbyWk['activeWeeks'], 
							'zone'=>$zone['name'],
							'zoneid'=>$zone['id'],
							'callsignFormat'=>$stationInfo['callsign'].'|'.$stationInfo['name'],
							'sortingStartDate'=>$zone['name'].$formatStartDateTimeClean.$stationInfo['callsign'].$title,
							'statusFormat'=>'|||',
							'titleFormat'=>$titles['titles']);
					
		foreach($spotsbyWk['detail'] as $spwk){
			$jsonline[$spwk['column']]= $spwk['spots'];
		}				
					
		return array('line'=>$jsonline,'stationmapped'=>$stationInfo['mapped']);
	}
	


	
	function getDates($weeks,$days,$times){
		$sdate = $weeks[0]['week'];
		$edate = $weeks[count($weeks)-1]['week'];
		$newDays = array();
		foreach($days as $cday){
			if($cday > 1)
				$newDays[]=$cday-2;
			else
				$newDays[]=6;			
		}
		sort($newDays);
		
		$startDate 		= date('m/d/Y', strtotime($sdate. ' + '.$newDays[0].' days'));
		$startDateTime 	= date('Y/m/d H:i', strtotime($startDate.' '.$times['stime']));
		$endDate 		= date('m/d/Y', strtotime($edate. ' + '.$newDays[count($newDays)-1].' days'));
		$endDateTime 	= date('Y/m/d H:i', strtotime($endDate.' '.$times['etime']));
		$r = array('startdate'=>$startDate, 'startdatetime'=>$startDateTime, 'enddate'=>$endDate, 'enddatetime'=>$endDateTime);
		return $r;
	}
	
	function getFlightDates($line,$times){
		$flighDates =  array();
		foreach($line as $att){
			if($att->code->attributes()->codeDescription[0] ==  'Start Date'){
				$flighDates['startdate'] = date('m/d/Y',strtotime((string)$att->code));
				$flighDates['startdatetime'] = date('Y/m/d H:i',strtotime((string)$att->code.' '.$times['stime']));
			}
			if($att->code->attributes()->codeDescription[0] ==  'End Date'){
				$flighDates['enddate'] = date('m/d/Y',strtotime((string)$att->code));
				$flighDates['enddatetime'] = date('Y/m/d H:i',strtotime((string)$att->code.' '.$times['etime']));
			}
		}

		return $flighDates;
		
	}

	function getLineType($weekCount, $days, $sdate, $edate){
		
		$d1= new DateTime($sdate); 
		$d2= new DateTime($edate);
		$interval= $d1->diff($d2);

		if(($weekCount == 1 && $interval->h < 7) || ($interval->d == 1 && $interval->h < 7)){
			return 'Fixed';	
		}
		else{
			return 'Rotator';
		}
	}


	function getRate($line){
		return (string)$line->spotCost;
	}


	function getSpotsByWeek($line,$weeks){
		$spotsbyWeek 	= array();
		$weeksCount 	= 0;
		$activewks 		= 0;		
		$spotsCount 	= 0;

		$totalSpots = (string)$line->totals->spots;
		
		if($totalSpots != '0'){
			foreach($line->spot as $spots){
				$wekNum 	 = (string)$spots->weekNumber;
				$spots  	 = (string)$spots->quantity;
	
				foreach($weeks as $wk){
					if($wekNum == $wk['weekNumber']){
						$spotsbyWeek[] = array('week'=>$wekNum, 'spots'=>$spots, 'column'=>$wk['column']);
						$spotsCount = $spotsCount+$spots;
						$weeksCount++;
						if($spots > 0)
							$activewks++;
					}	
				}
			}
		}
		else{
			foreach($weeks as $wk){
				$spotsCount++;
				$weeksCount++;
				$activewks++;
				$spotsbyWeek[] = array('week'=>$weeksCount, 'spots'=>'1', 'column'=>$wk['column']);
			}
		}
		return array('detail'=>$spotsbyWeek,'numberOfWeeks'=>$weeksCount,'totalSpots'=>$spotsCount,'activeWeeks'=>$activewks);
	}


	function getTitle($line){
		return trim((string)$line->program);
	}	

	
	function getTotalCost($line){
		return (string)$line->totals->cost;
	}	


	function getTotalSpots($line){
		return (string)$line->totals->spots;
	}

	
	function getWeeks($xmlWeeks){
		$proposalWeeks = array();		
		foreach($xmlWeeks as $wk){
			$thisDate 	= strtotime((string)$wk->attributes()->startDate);
			$proposalWeeks[] = array('column'=>"w".date("m", $thisDate).date("d",$thisDate).date("Y",$thisDate),
									 'week'=>(string)$wk->attributes()->startDate,
									 'weekNumber'=>(string)$wk->attributes()->number);
		}
		
		return $proposalWeeks;
	}	


	function formatDays($days){

		$wD = array('','SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT');
			
		if(count($days) == 1){
			return $wD[$days[0]];
		}
		else{
			$wD  = array('M', 'T', 'W', 'Th', 'F', 'Sa','Su');
			$nwD = array();
			$daypart = array();
			foreach($days as $thisday){
				if($thisday != 1)
					$nwD[] = $thisday-2;
				else
					$nwD[] = 6;
			}
			sort($nwD);
			$firstDay 	= $nwD[0];
			rsort($nwD);
			$lastDay 	= $nwD[0];
			$diff 		= $lastDay - $firstDay;
			
			if(count($nwD) - $diff == 1){
				$daypart[] = $wD[$nwD[count($nwD)-1]].'-'.$wD[$nwD[0]];
			}
			else{
				sort($nwD);				
				foreach($nwD as $thisday){
					$daypart[] = $wD[$thisday];
				}
			}
			return implode(',',$daypart);
		}
	}


	function mapLineType($line){
		if(count($line->spot) > 1){
			return 'Rotator';			
		}
		else{
			return 'Fixed';
		}
	}	


	function mapDays($line){
	
		$daysOfWeek = array();
			
		if($line->dayOfWeek->Monday == 'Y')
			$daysOfWeek[] = '2';
		
		if($line->dayOfWeek->Tuesday == 'Y')
			$daysOfWeek[] = '3';

		if($line->dayOfWeek->Wednesday == 'Y')
			$daysOfWeek[] = '4';
					
		if($line->dayOfWeek->Thursday == 'Y')
			$daysOfWeek[] = '5';
		
		if($line->dayOfWeek->Friday == 'Y')
			$daysOfWeek[] = '6';
		
		if($line->dayOfWeek->Saturday == 'Y')
			$daysOfWeek[] = '7';
			
		if($line->dayOfWeek->Sunday == 'Y')
			$daysOfWeek[] = '1';
		
		return $daysOfWeek;
	}	


	function mapStation($con,$zoneid,$station){
		
		$sql 	= " select 		tms_networks.callsign, tms_networks.networkid, tms_networks.name, '1' as mapped
					from 		tms_networks 
					inner join 	zonenetworks 
					on 			tms_networks.networkid = zonenetworks.networkid
					left outer join networkmapping
					on 			tms_networks.networkid = networkmapping.id
					where 		zonenetworks.zoneid = {$zoneid} ";

		if($station['networkid'] != -1 && $station['networkid'] != '')
			$sql = $sql."and networkmapping.scx_id = {$station['networkid']}";	
		else
			$sql = $sql."and networkmapping.ncc_callsign = '{$station['callsign']}'";

		$result 	= mysqli_query($con, $sql);
		
		if(mysqli_num_rows($result) > 0)
			$row 		= $result->fetch_assoc();		
		else
			$row		= $station;
			
		return $row;
	}
	

	function mapTimes($line){
		$st = date('H:i',strtotime('01-01-2016 '.(string)$line->startTime));
		$et = date('H:i',strtotime('01-01-2016 '.(string)$line->endTime));
		$et = str_replace('00:00','23:59',$et);
    	$times = array('stime'=> $st,'etime'=> $et);
		return $times;
	}


	function mapZone($con,$syscode,$corpid){
		$sql 		= "select 		zones.id, 
										zones.name, 
										zones.syscode,
										LOWER(timezones.abbreviation) as abbreviation
						from 			zones 
						inner join 	timezones 
						on 			zones.timezoneid = timezones.id 
						where 		syscode = ".$syscode." 
						and			corporationid = ".$corpid." ORDER BY id DESC  limit 0,1";
		$result 	= mysqli_query($con, $sql);
		
		if(mysqli_num_rows($result) > 0)
			$row 		= $result->fetch_assoc();
		else
			$row		= array();

		return $row;
	}


	function validateDate($date, $format = 'Y-m-d H:i:s'){
	    $d = DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) == $date;
	}


	function validateSCX(){
		$xml = new XMLReader();
		$xml->open('test.xml');
		$xml->setParserProperty(XMLReader::VALIDATE, true);

		if($xml->isValid()){
			return true;
		}
		else{
			return false;			
		}	
	}		
	
	
	function createProposal(&$con,&$userid,&$name,&$proposaljs,&$pslzones,&$pslStartDate,&$pslEndDate,&$linesTotal,&$pslSpots,&$pslTotal){
		$d 		= date('Y-m-d H:i:s');
		$sql 	= "INSERT INTO 	proposals (	
								userid, 
								name, 
								proposal, 
								zones, 
								weeks, 
								calendar, 
								createdat, 
								updatedat,
								startdate,
								enddate,
								linesttl,
								spots,
								grossttl)
					VALUES (	$userid, 
								'$name',
								'".mysqli_real_escape_string($con,$proposaljs)."',
								'".implode(',',$pslzones)."',
								'[]',
								'1',
								'$d',
								'$d',
								'$pslStartDate',
								'$pslEndDate',
								'$linesTotal',
								'".array_sum($pslSpots)."',
								'".array_sum($pslTotal)."')";
								
		$proposaid 	= mysqli_query($con, $sql);	
		return mysqli_insert_id ($con);
	}
	

	function getTitles($sdate,$stime,$edate,$etime,$tz,$net,$days,$program,$con){		
		
		$sdate = date('Y-m-d',strtotime($sdate)).'T00:00:00Z';
		$edate = date('Y-m-d',strtotime($edate)).'T23:59:59Z';
				
		$startdate 	 = "tz_start_" . $tz;
		$enddate 	 = "tz_end_" . $tz;
		$starttime 	 = "start_" . $tz;
		$startday 	 = "day_" .$tz;
		$endfix 	 = date("H:i:s",strtotime("01/01/1980 " . $etime." - 1 minutes"));
		$dates 		 = '&fq=' . $startdate . ':[' . $sdate . ' TO ' . $edate . ']';
		$times 		 = '&fq=' . $starttime . ':[' . $stime . ' TO ' . $endfix . ']';
	
		$zrl = 'http://solr.showseeker.net:8983/solr/gracenote/select/?q=*%3A*&version=2.2&start=0&indent=on&wt=json&fq=-sort:"Movie"&fq=-sort:"Paid Programming"&fq=-sort:"To Be Announced"&fq=projected:0&fq=-genre1:"consumer"&fq=-genre2:"consumer"';
		$zrl = $zrl."&rows=5000";
		$zrl = $zrl.$dates;
		$zrl = $zrl.$times;
		$zrl = $zrl."&fq=stationnum:".$net;
		$zrl = $zrl."&group=true&group.field=sort&fl=sort,showid,desc60,epititle,id";
		
		if(count($days) != 7){
			$re = "&fq=";
			foreach($days as $value){
				$re .= 'day_' . $tz . ':' . $value . '+';
			}
			$zrl = $zrl.$re;
		}		
		
		$zrl = $zrl."&sort=title asc";
			
		$url = str_replace(" ", "+", $zrl);
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = json_decode(curl_exec($ch),true);
		curl_close($ch);
		
		if(empty($data['grouped']['sort']['groups'])){
			return array('ids'=>'0','titles'=>$program, 'desc'=>'', 'epiTitle'=>'', 'ssid'=>uniqid());
		}
	
		return getTopTitles($data['grouped']['sort']['groups'],$con);
	}
	

	function getTopTitles($data,$con){
		
		$titles 	= array();
		$re 		= 'Various';
		$idList 	= '';
		$titleList	= '';

		$desc 		= '';
		$epiTitle 	= '';	
		$ssid 		= uniqid();

		foreach($data as $r){
			if($r['doclist']['docs'][0]['epititle'] != ''){
				$epiTitle = mysqli_real_escape_string($con, $r['doclist']['docs'][0]['epititle']);
				break;
			}
		}
			
		foreach($data as $r){
			$row = array('numfound' => $r['doclist']['numFound'], 
						 'title' => $r['groupValue'], 
						 'id'=> $r['doclist']['docs'][0]['showid'],
						 'desc'=> $r['doclist']['docs'][0]['desc60'],
						 'ssid'=>$r['doclist']['docs'][0]['id']
						 );
			$titles[] = $row;
		}

		if(count($titles) > 0){		
			usort($titles, "cmp");
			$i  			= 0;
			$desc 		= $titles[0]['desc'];
			$ssid 		= $titles[0]['ssid'];
					
			while($i < 5 && $i< count($titles)){
				$idList 	.= $titles[$i]['id'].', ';
				$titleList 	.= $titles[$i]['title'].', ';	
				$i++;	
			}
		}


	
		return array('ids'=>trim($idList, ', '),'titles'=>trim($titleList,', '), 'desc'=>$desc, 'epiTitle'=>$epiTitle, 'ssid'=>$ssid);
	}	
	
	
	function getUserCorp($con,&$userid){
		$sql 	= "select corporationid from users where id = ".$userid;
		$result = mysqli_query($con, $sql);
		$row 	= mysqli_fetch_assoc($result);
		return $row['corporationid'];
	}
	
	
	function cmp($a, $b){
	    return $b['numfound'] - $a['numfound'];
	}
?>