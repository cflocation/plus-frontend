<?php 
		require_once 'common/initialize.php';
		header("Content-Type: application/json"); 
		ini_set("display_errors","on");
		error_reporting(E_ALL);



		if(isset($_REQUEST['dmaid']) && $_REQUEST['dmaid'] != '') $dmaid	= trim($_REQUEST['dmaid']);
		else $dmaid	= "0";
		
		if(isset($_REQUEST['zoneid']) && $_REQUEST['zoneid'] != '') $zoneid	= trim($_REQUEST['zoneid']);
		else $zoneid	= "0";
		
		if(isset($_REQUEST['type']) && $_REQUEST['type'] != '') $type	= trim($_REQUEST['type']);
		else $type	= "title";
		
		if(isset($_REQUEST['q']) && $_REQUEST['q'] != '') $keywords	= trim($_REQUEST['q']);
		else $keywords	= "";
		
		if(isset($_REQUEST['startdate']) && $_REQUEST['startdate'] != '') $startdate	= trim($_REQUEST['startdate']);
		else $startdate	= "";
		
		if(isset($_REQUEST['enddate']) && $_REQUEST['enddate'] != '') $enddate	= trim($_REQUEST['enddate']);
		else $enddate	= "";
		
		if(isset($_REQUEST['starttime']) && $_REQUEST['starttime'] != '') $starttime	= trim($_REQUEST['starttime']);
		else $starttime	= "";
		
		if(isset($_REQUEST['endtime']) && $_REQUEST['endtime'] != '') $endtime	= trim($_REQUEST['endtime']);
		else $endtime	= "";
		
		if(isset($_REQUEST['premieres']) && $_REQUEST['premieres'] != '') $premieres	= trim($_REQUEST['premieres']);
		else $premieres	= "";
		
		if(isset($_REQUEST['days']) && $_REQUEST['days'] != '') $days	= trim($_REQUEST['days']);
		else $days	= "";
		
		if(isset($_REQUEST['new']) && $_REQUEST['new'] != '') $new	= trim($_REQUEST['new']);
		else $new	= "";
		
		if(isset($_REQUEST['marathon']) && $_REQUEST['marathon'] != '') $marathon	= trim($_REQUEST['marathon']);
		else $marathon	= "";
		
		if(isset($_REQUEST['nets']) && $_REQUEST['nets'] != '') $nets	= trim($_REQUEST['nets']);
		else $nets	= "";
		
		if(isset($_REQUEST['selectedgenres']) && $_REQUEST['selectedgenres'] != '') $selectedgenres	= trim($_REQUEST['selectedgenres']);
		else $selectedgenres	= "";
		
		if(isset($_REQUEST['live']) && $_REQUEST['live'] != '') $live	= trim($_REQUEST['live']);
		else $live	= "";
		
		if(isset($_REQUEST['showids']) && $_REQUEST['showids'] != '') $showids	= trim($_REQUEST['showids']);
		else $showids	= "";
		
		if(isset($_REQUEST['page']) && $_REQUEST['page'] != '') $page	= trim($_REQUEST['page']);
		else $page	= "";
		
		if(isset($_REQUEST['page']) && $_REQUEST['page'] != '') $page	= trim($_REQUEST['page']);
		else $page	= "1";
		
		if(isset($_REQUEST['returncount']) && $_REQUEST['returncount'] != '') $returncount	= trim($_REQUEST['returncount']);
		else $returncount	= "49";
		
		if(isset($_REQUEST['header']) && $_REQUEST['header'] != '') $header	= trim($_REQUEST['header']);
		else $header	= "1";
		
		if(isset($_REQUEST['showtype']) && $_REQUEST['showtype'] != '') $showtype	= trim($_REQUEST['showtype']);
		else $showtype	= "";
		
		if(isset($_REQUEST['grouping']) && $_REQUEST['grouping'] != '') $grouping	= trim($_REQUEST['grouping']);
		else $grouping	= "showid";
		
		
		if(!isset($_REQUEST['userid']) || (isset($_REQUEST['userid']) && $_REQUEST['userid'] == '')) exit('-1');
		else $userid = trim($_REQUEST['userid']);
		
		//if(!isset($_REQUEST['tokenid']) || (isset($_REQUEST['tokenid']) && $_REQUEST['tokenid'] == '')) exit('-1');
		//else $tokenid = trim($_REQUEST['tokenid']);
		
		
		//if(!is_token_valid($userid,$tokenid)) exit('-1');
		
		$searchingfornew		= ($new!="")?1:0;
		$searchingformarathon	= ($marathon!="")?1:0;
		
		
		if(count($db->fetch_result("SELECT * FROM user_last_searches WHERE userid = $userid "))>0)
		$db->execute("UPDATE user_last_searches SET zoneid='$zoneid', starttime='$starttime', endtime='$endtime', new='$searchingfornew', marathon='$searchingformarathon', premiere='$premieres'
		, genres='$selectedgenres', timezone='', daysofweek='$days', stations='$nets', updatedat=NOW()	WHERE userid = '$userid' ");
		else
		$db->execute("INSERT INTO user_last_searches (userid , zoneid, starttime, endtime, new, marathon, premiere, genres, timezone, daysofweek, stations, createdat, updatedat)
		VALUES('$userid', '$zoneid', '$starttime', '$endtime', '$searchingfornew', '$searchingformarathon', '$premieres', '$selectedgenres', '', '$days', '$nets', NOW(), NOW())");
		
		if($searchingformarathon==1)
		{
			if($nets=='')
			{
				$selQuery = "SELECT zn.networkid FROM zonenetworks AS zn INNER JOIN zones AS z ON zn.zoneid = z.id AND z.deletedat IS NULL INNER JOIN timezones AS tz ON z.timezoneid = tz.id
				WHERE zn.zoneid = $zoneid  AND zn.deletedat IS NULL";
				$Selresult = $db->fetch_result($selQuery);
				foreach($Selresult as $row)
				{
					$nets .= $row['networkid'].",";
				}
				$nets = substr($nets,0,-1);
			}
			doMarathonSearch($dmaid,$zoneid,$type,$keywords,$startdate,$enddate,$starttime,$endtime,$premieres,$days,$new,$marathon,$nets,$selectedgenres,$live,$showids,$page,$returncount,$header,$showtype,$grouping);
			exit;
		}
		if ($header == 1){
			$countshows = solrLink($dmaid,$zoneid,'', $startdate, $enddate, $starttime, $endtime, $type, $nets, $keywords, $days, $new, $marathon, $premieres, $showids, $selectedgenres, '', '', '', $grouping, 'SH,EP', 5000, '', '',$live);
			$countmovies = solrLink($dmaid,$zoneid,'', $startdate, $enddate, $starttime, $endtime, $type, $nets, $keywords, $days, $new, $marathon, $premieres, $showids, $selectedgenres, '', '', '', $grouping, 'MV', 5000, '', '',$live);
			$countsports = solrLinkSp($dmaid,$zoneid,'', $startdate, $enddate, $starttime, $endtime, $type, $nets, $keywords, $days, $new, $marathon, $premieres, $showids, $selectedgenres, '', '', '', $grouping, '', 4000, '', '',$live);
			
			//<!--- SPORTS COUNT ADDED BY IVAN ON OCT 24 2012--->
			$sptCount = array();
			for($i=0;$i<count($countsports->grouped->$grouping->groups);$i++)
			{
				$sptCount[] = $countsports->grouped->$grouping->groups[$i]->doclist->docs[0]->title;
			}
			
			//skipping unique count :paresh on 11nov14
			$uniqueSpIds = array_unique($sptCount);
			$countSpIds = $uniqueSpIds;
			//<!--- END OF SPORTS COUNT --->
			
			$showcount = count($countshows->grouped->$grouping->groups);
			$moviecount = count($countmovies->grouped->$grouping->groups);
			$sportscount = count($sptCount);
			$totalcount = $showcount+$moviecount+$sportscount;

			//print $totalcount." = ".$showcount."+".$moviecount."+".$sportscount;

			
		}
		
		//<!--- VRIFYING CONDITIONS TO RUN A SPORTS SEARCH --->
		$spsearch = 0;
		if ($showtype == "SP" || ($live == 1 && ($selectedgenres  == "sports event" || $selectedgenres  == ",sports event")))
			$spsearch = 1;
		//<!--- ------------------------------------------ --->
		
		
		//<!--- DO THE BASIC SEARCH --->
		if ($spsearch == 0)
		{
			
			$solrdata = solrLink($dmaid,$zoneid,'', $startdate, $enddate, $starttime, $endtime, $type, $nets, $keywords, $days, $new, $marathon, $premieres, $showids, $selectedgenres, '', '', '', $grouping, $showtype, $returncount, $page, '',$live);
			$showdata = $solrdata->grouped->$grouping->groups;
		} else
		{
			//<!--- DO THE SPORTS SEARCH --->
			$solrdata = solrLinkSp($dmaid,$zoneid,'', $startdate, $enddate, $starttime, $endtime, $type, $nets, $keywords, $days, $new, $marathon, $premieres, $showids, $selectedgenres, '', '', '', $grouping, '', $returncount, $page, '',$live);
			$showdata = $solrdata->grouped->$grouping->groups;
			//$mySports = sportsSearch($showdata, $showcount, $sportscount, $totalcount, $keywords, $dmaid, $zoneid,$startdate, $enddate, $starttime, $endtime, $nets);
		}
		
		$cnt = 0;
		$keylist = array();
		$responseHeader = array(
							"TOTALMOVIES"=> $moviecount,
							"TOTALSPORTS"=> $sportscount,
							"TOTALSHOWS"=> $showcount,
							"TOTAL"=> $totalcount,
							"PAGE"=> $page
						);
		$data = array();

		for($i=0;$i<count($showdata);$i++)
		{
			$xid = $i;
			
			$thisduration =$showdata[$i]->doclist->docs[0]->duration;
			$thisgenre = trim($showdata[$i]->doclist->docs[0]->genre);
			$thisid =$showdata[$i]->doclist->docs[0]->id;
			$thisnew =$showdata[$i]->doclist->docs[0]->new;
			$thispremierefinale =$showdata[$i]->doclist->docs[0]->premierefinale;
			$thisshowid =$showdata[$i]->doclist->docs[0]->showid;
			$thistmsid =$showdata[$i]->doclist->docs[0]->tmsid;
			$thisshowtype =$showdata[$i]->doclist->docs[0]->showtype;
			$thisstarttime =$showdata[$i]->doclist->docs[0]->starts;
			$thistitle =$showdata[$i]->doclist->docs[0]->title;
			$thisepititle =$showdata[$i]->doclist->docs[0]->epititle;
			$thistmsid =$showdata[$i]->doclist->docs[0]->tmsid;
			$thislive =$showdata[$i]->doclist->docs[0]->live;
			$thisstationnum =$showdata[$i]->doclist->docs[0]->stationnum;
			
			$logoRes = $db->fetch_result("SELECT nl.networkid, nl.logoid, nl.createdat, nl.updatedat, nl.deletedat, l.id, l.name, l.filename, l.createdat AS lcreatedat, l.updatedat AS lupdatedat, l.deletedat AS ldeletedat FROM networklogos AS nl INNER JOIN logos AS l ON nl.logoid = l.id AND l.deletedat IS NULL WHERE nl.networkid = {$thisstationnum} AND nl.deletedat IS NULL ");
			if (count($logoRes)>0){
			$thislogo = "https://showseeker.s3.amazonaws.com/images/netwroklogo/75/{$thisstationnum}.png";}
			
			$thistitle = str_ireplace('£','',$thistitle);
			$keylist[] = $thisshowid;
			$stats = showstats($dmaid, $zoneid, '',$startdate, $enddate, $starttime, $endtime, $nets, $keywords, $thistitle, $thisshowid, $grouping, '');
			
			$next = $thisstarttime;
			$next = explode('T',$next);
			$next = explode('-',$next[0]);
			$next = "{$next[1]}/{$next[2]}/{$next[0]}";
			
			$data[$thisshowid] = array();
			$data[$thisshowid]["POS"] = $cnt;
			$data[$thisshowid]["TITLE"] = $thistitle;
			$data[$thisshowid]["NEW"] = $stats['new'];
			$data[$thisshowid]["SHOWTYPE"] = $thisshowtype;
			$data[$thisshowid]["NEXT"] = $next;
			$data[$thisshowid]["COUNT"] = $stats['total'];
			$data[$thisshowid]["SHOWID"] = $thisshowid;
			$data[$thisshowid]["TMSID"] = $thistmsid;
			$data[$thisshowid]["PREMIERES"] = $stats['premiere'];
			$data[$thisshowid]["KEYWORDS"] = $stats['keywords'];
			$data[$thisshowid]["STARTTIME"] = $thisstarttime;
			$data[$thisshowid]["GENRE"] = $thisgenre;
			$data[$thisshowid]["LIVE"] = $stats['live'];
			$data[$thisshowid]["DURATION"] = $thisduration;
			$data[$thisshowid]["NETWORKS"] = array($thisstationnum=>$thislogo);
			$cnt++;
		}
		
		if(count($data)==0) $data = new stdclass();
		$res = array('RESPONSEHEADER'=>$responseHeader,'DATA'=>$data,"KEYS"=>$keylist);
		print json_encode($res);
		exit;
		
		
		
		
		function sportsSearch($showdata, $showcount, $sportscount, $totalcount, $keywords, $dmaid, $zoneid,$startdate, $enddate, $starttime, $endtime, $nets){
			$cnt = 0;
			$keylist = array();
			$thissport = "";
			$numShows = 1;
			$programsList = array();
			
			$responseHeader = array(
				"TOTALMOVIES"=> 0,
				"TOTALSPORTS"=> $sportscount,
				"TOTALSHOWS"=> $showcount,
				"TOTAL"=> $totalcount,
				"PAGE"=> 1
			);
			
			$data = array();
			$cnt = 0;
			for($i=0;$i<count($showdata);$i++){

				$xid = $i;
				
				if(!in_array(trim($showdata[$i]->doclist->docs[0]->title),$programsList)){
					$thissport = $showdata[$i]->doclist->docs[0]->title;
					$programsList[] = trim($thissport);
					$thisduration = $showdata[$i]->doclist->docs[0]->duration;
					$thisgenre = trim($showdata[$i]->doclist->docs[0]->genre);
					$thisid = $showdata[$i]->doclist->docs[0]->id;
					$thisnew = $showdata[$i]->doclist->docs[0]->new;
					$thispremierefinale = $showdata[$i]->doclist->docs[0]->premierefinale;
					$thisshowid = $showdata[$i]->doclist->docs[0]->showid;
					$thistmsid = $showdata[$i]->doclist->docs[0]->tmsid;	
					$thisshowtype = "SP";
					$thisstarttime = $showdata[$i]->doclist->docs[0]->starts;
					$thistitle = $showdata[$i]->doclist->docs[0]->title;
					$thisepititle = $showdata[$i]->doclist->docs[0]->epititle;
					$thistmsid = $showdata[$i]->doclist->docs[0]->tmsid;
					$thislive = $showdata[$i]->doclist->docs[0]->live;
					$thisstationnum = $showdata[$i]->doclist->docs[0]->stationnum;
					
					$thistitle = str_ireplace('£','',$thistitle);
					$keylist[] = $thisshowid;
					$stats = showstats($dmaid, $zoneid, '',$startdate, $enddate, $starttime, $endtime, $nets, $keywords, $thistitle, $thisshowid, 'title', '');
					
					$next = $thisstarttime;
					$next = explode('T',$next);
					$next = explode('-',$next[0]);
					$next = "{$next[1]}/{$next[2]}/{$next[0]}";
					
					$data[$thisshowid] = array();
					$data[$thisshowid]["POS"] = "$cnt";
					$data[$thisshowid]["TITLE"] = $thistitle;
					$data[$thisshowid]["NEW"] = $stats['new'];
					$data[$thisshowid]["SHOWTYPE"] = $thisshowtype;
					$data[$thisshowid]["NEXT"] = $next;
					$data[$thisshowid]["COUNT"] = $stats['total'];
					$data[$thisshowid]["SHOWID"] = $thisshowid;
					$data[$thisshowid]["TMSID"] = $thistmsid;
					$data[$thisshowid]["PREMIERES"] = $stats['premiere'];
					$data[$thisshowid]["KEYWORDS"] = $stats['keywords'];
					$data[$thisshowid]["STARTTIME"] = $thisstarttime;
					$data[$thisshowid]["GENRE"] = $thisgenre;
					$data[$thisshowid]["LIVE"] = $stats['live'];
					$data[$thisshowid]["DURATION"] = (float)$thisduration;
					$data[$thisshowid]["NETWORKS"] = array($thisstationnum=>"https://showseeker.s3.amazonaws.com/images/netwroklogo/75/{$thisstationnum}");
					
					$cnt++;
				}
			}

			$shcount = substr_count(implode(',',$keylist), "SH") + substr_count(implode(',',$keylist), "EP");
			$responseHeader["TOTALSHOWS"] = $shcount;
			if(count($data)==0) $data = new stdclass();
			$res = array('RESPONSEHEADER'=>$responseHeader,'DATA'=>$data,'KEYS'=>$keylist);
			print json_encode($res);
			exit;
		}
		
		function showstats($dmaid=0, $zoneid=0, $timezone="",$startdate="", $enddate="", $starttime="", $endtime="", $nets="", $keywords="", $titlematch="", $showids="", $groupby="", $days=""){

			if ($groupby == "title"){
				$solrdata = solrLink($dmaid,$zoneid,'', $startdate, $enddate, substr($starttime,0,8), substr($endtime,0,8), 'title', $nets, '', $days, '', '', '', '', '', 'premierefinale,new,live,search,title,showtype', '', 10, '', '', '','', urlencode($titlematch),'');
			}
			else {
				$solrdata = solrLink($dmaid,$zoneid,'', $startdate, $enddate, substr($starttime,0,8), substr($endtime,0,8), 'title', $nets, '', $days, '', '', '', $showids, '', 'premierefinale,new,live,search,title,showtype', '', 10, '', '', '', '', '','');
			}
			
			$stats = array();
			$stats['total'] = 0;
			$stats['new'] = 0;
			$stats['live'] = 0;
			$stats['premiere'] = 0;
			$stats['keywords'] = 0;
			
			for($i=0;$i<count($solrdata->response->docs);$i++){
				$stats['total'] += 1;
				$stats['title'] = $solrdata->response->docs[$i]->title;
				
				if ($i == 0){
					$next = $solrdata->response->docs[$i]->starts;
					$tempArr = explode('T',$next);
					$tempArr = explode('-',$tempArr[0]);
					$next = "{$tempArr[1]}/{$tempArr[2]}/{$tempArr[0]}";
					$stats['next'] = $next;
				}
				
				if ($solrdata->response->docs[$i]->new == "New"){
					if ($solrdata->response->docs[$i]->live != "Live")
					{
						$stats['new'] += 1;
					}
				}
				
				if ($solrdata->response->docs[$i]->live == "Live"){
					$stats['live'] += 1;
				}
				
				if ($solrdata->response->docs[$i]->premierefinale != ""){
					$stats['new'] += 1;
					$stats['premiere'] += 1;
				}

				foreach(explode(',',$keywords) as $kw){
					if(stripos($solrdata->response->docs[$i]->search, $kw) !== false)
					{
						$stats['keywords'] += 1;
						break;
					}
				}
			}
			return $stats;
		}
		
		function doMarathonSearch($dmaid,$zoneid,$type,$keywords,$startdate,$enddate,$starttime,$endtime,$premieres,$days,$new,$marathon,$nets,$selectedgenres,$live,$showids,$page,$returncount,$header,$showtype,$grouping){
			
			if($showtype == "SP" || $selectedgenres == "sports event" || $selectedgenres == "sports event,sports event" || $selectedgenres == ",sports event"){
				$showtype = "";
				$selectedgenres = "sports event";
				$grouping = 'title';
			}
			
			$theseIds = array();
			
			foreach(explode(',',$nets) as $thisnetwork){

				if(is_numeric($thisnetwork)){

					$marathonsData = solrLink($dmaid,$zoneid,'',$startdate,$enddate,$starttime,$endtime,$type,$thisnetwork,$keywords,$days,$new,$marathon,$premieres,$showids,$selectedgenres,'','','','','',10000,1,'',$live);
				
					for($indx=0;$indx<count($marathonsData->response->docs);$indx++){
						$theseIds[] = $marathonsData->response->docs[$indx];
					}
				}
			}

			$marathonIds = marathons($theseIds);
	

			if(!is_array($marathonIds))	exit("-1");
			
			$mthids = array();
			$mthtmsids = array();
			
			for($i=0;$i<count($marathonIds); $i++){
				$mthids[]    = $marathonIds[$i][0];
				$mthtmsids[] = $marathonIds[$i][1];
			}
			
			//<!--- 14 DIGIT SHOW IDS --->	
			$uniquemarathonIds = array_unique($mthtmsids);
			//<!--- ------------------- --->
			
			
			//<!--- 10 DIGIT SHOW IDS --->
			$uniqueshowIds 	= array_unique($mthids);
			//<!--- ------------------- --->
			
			

			
			$showcount 	= 0;
			$moviecount = 0;
			$sportscount= 0;
			$totalcount = 0;
			
			//<!--- HEADER COUNTS --->
			foreach($uniqueshowIds as $i){
				if(strpos($i,"EP")!== false || strpos($i,"SH")!== false)
					$showcount += 1;
				else if(strpos($i,"SP")!== false)
					$sportscount += 1;
				else if(strpos($i,"MV")!== false)
					$moviecount += 1;
			}
			
			$totalcount 	= $showcount + $sportscount;
			//<!---- ------------ --->	
			$counter = 1;
	
			//<!--- do the basic search --->
			$solrdata = solrLink($dmaid,$zoneid,'', $startdate, $enddate, $starttime, $endtime, $type, $nets, $keywords, $days, $new, $marathon, $premieres, implode(',',$uniqueshowIds), $selectedgenres, '', '', '', $grouping, $showtype, $returncount, $page, '',$live);

			$showdata = $solrdata->grouped->$grouping->groups;
			$cnt = 0;
			$keylist = array();
			
			$responseHeader = array(
							"TOTALMOVIES"	=> $moviecount
							,"TOTALSPORTS"	=> $sportscount
							,"TOTALSHOWS"	=> $showcount
							,"TOTAL"		=> $totalcount
							,"PAGE"			=> $page
						);
			$data = array();
			for($i=0;$i<count($showdata);$i++)
			{
				$xid = $i;
				
				// setup all the vars for this record --->
				$thisduration = $showdata[$i]->doclist->docs[0]->duration;
				$thisgenre = trim($showdata[$i]->doclist->docs[0]->genre);
				$thisid = $showdata[$i]->doclist->docs[0]->id;
				$thisnew = $showdata[$i]->doclist->docs[0]->new;
				$thispremierefinale = $showdata[$i]->doclist->docs[0]->premierefinale;
				$thisshowid = $showdata[$i]->doclist->docs[0]->showid;
				$thistmsid = $showdata[$i]->doclist->docs[0]->tmsid;
				$thisshowtype = $showdata[$i]->doclist->docs[0]->showtype;
				$thisstarttime = $showdata[$i]->doclist->docs[0]->starts;
				$thistitle = $showdata[$i]->doclist->docs[0]->title;
				$thisepititle = $showdata[$i]->doclist->docs[0]->epititle;
				$thistmsid = $showdata[$i]->doclist->docs[0]->tmsid;
				$thislive = $showdata[$i]->doclist->docs[0]->live;
				$thisstationnum = $showdata[$i]->doclist->docs[0]->stationnum;
				$thistitle = str_ireplace('£','',$thistitle);
				$keylist[] = $thisshowid;
				
				$thiscount = showcounter($thisshowid,$mthids);
				$thesemarathonids = marathonidfinder($thisshowid, $marathonIds);
				$icons = json_decode(file_get_contents("https://happydata1.showseeker.com/showdetails.php?showid=$thisshowid"));

				$next = $thisstarttime;
				$next = explode('T',$next);
				$next = explode('-',$next[0]);
				$next = "{$next[1]}/{$next[2]}/{$next[0]}";
				
				$stats = livepremierecounter($dmaid,$zoneid,$thesemarathonids);
				
				$data[$thisshowid] = array();
				$data[$thisshowid]['POS'] = $cnt;
				$data[$thisshowid]['TITLE'] = $thistitle;
				$data[$thisshowid]['NEW'] = $stats['new'];
				$data[$thisshowid]['SHOWTYPE'] = $thisshowtype;
				$data[$thisshowid]['NEXT'] = $next;
				$data[$thisshowid]['COUNT'] = $thiscount;
				$data[$thisshowid]['SHOWID'] = $thisshowid;
				$data[$thisshowid]['TMSID'] = $thistmsid;
				$data[$thisshowid]['PREMIERES'] = $stats['premiere'];
				$data[$thisshowid]['KEYWORDS'] = $stats['keywords'];
				$data[$thisshowid]['STARTTIME'] = $thisstarttime;
				$data[$thisshowid]['GENRE'] = $thisgenre;
				$data[$thisshowid]['LIVE'] = $stats['live'];
				$data[$thisshowid]['DURATION'] = $thisduration;
				$data[$thisshowid]['ICONS'] = $icons;
				$data[$thisshowid]['MARATHONIDS'] = $thesemarathonids; // '['.implode(',',$thesemarathonids).']';
				$data[$thisshowid]['NETWORKS'] = array($thisstationnum=>"https://showseeker.s3.amazonaws.com/images/netwroklogo/75/$thisstationnum");
				$cnt++;
			}
			
			//$keys = '['.implode(',',$keylist).']'; //"";
			if(count($data)==0) $data = new stdclass();
			$res = array('RESPONSEHEADER'=>$responseHeader,'DATA'=>$data,'KEYS'=>$keylist);
			print json_encode($res);
			//print str_replace('\\/', '/',json_format($res));
			exit;
		}
		
		function livepremierecounter($dmaid=0, $zone="", $showids=""){
			global $db;
			$zsql = "SELECT zn.zoneid ,zn.networkid ,zn.createdat ,zn.updatedat ,zn.deletedat ,z.id ,z.name ,z.dmaid ,z.syscode ,z.zipcode ,z.timezoneid ,z.isdma ,z.corporationid ,z.createdat AS zonecreatedat ,z.updatedat AS zoneupdatedat ,z.deletedat AS zonedeletedat ,tz.name AS timezonename ,tz.databasename ,tz.abbreviation ,tz.phpname ,tz.utcdifference ,tz.createdat AS timezonecreatedat ,tz.updatedat AS timezoneupdatedat FROM zonenetworks AS zn INNER JOIN zones AS z ON zn.zoneid = z.id AND z.deletedat IS NULL INNER JOIN timezones AS tz ON z.timezoneid = tz.id WHERE zn.zoneid = $zone  AND  zn.deletedat IS NULL LIMIT 1 ";
			$thiszone =  $db->fetch_result($zsql);
			$timezone = $thiszone[0]['abbreviation'];
			
			$theseids = "";
			
			foreach($showids as $show){
				$theseids .= 'id:"'.$show.'"+';
			}
			
			$theseids = substr($theseids,0,-1);
			
			$url = preg_replace("/ /", "%20", "solr.showseeker.net:8983/solr/gracenote/select/?q=*:*&indent=true&wt=json&sort=tz_start_pst%20asc&fl=premierefinale,new,live,search,title,showtype&rows=2000&fq=$theseids+&fq=-type:%22Paid%20Programming%22");

			$solrResJson = get_data('http://'.$url);
			$solrResJson = preg_replace("^\s*[[:word:]]*\s*\(\s*^",'',$solrResJson);
			$solrResJson = preg_replace("^\s*\)\s*$^",'',$solrResJson);
			$solrdata=json_decode($solrResJson);
			
			
			
			$stats = array();
			$stats['total'] = 0;
			$stats['new'] = 0;
			$stats['live'] = 0;
			$stats['premiere'] = 0;
			$stats['keywords'] = 0;
			if($solrdata){
				for($i=0;$i<count($solrdata->response->docs);$i++){
					$stats['total'] += 1;
				
					if ($solrdata->response->docs[$i]->new == "New" && $solrdata->response->docs[$i]->live != "Live"){
						$stats['new'] += 1;
					}
					
					if ($solrdata->response->docs[$i]->live == "Live"){
						$stats['live'] += 1;
					}
					
					if ($solrdata->response->docs[$i]->premierefinale != ""){
						$stats['new'] -= 1;
						$stats['premiere'] += 1;
					}
				}
			}
			return $stats;
		}
		
		function marathonidfinder($showid="",$shows="")
		{
			$keys = "";
			for($i=0;$i<count($shows);$i++)
				if ($shows[$i][0] == $showid)
					$keys[] = $shows[$i][2];
			return $keys;
		}
		
		function showcounter($showid="",$showslist="")
		{
			$count = 0;
			$pos   = 0;
			foreach($showslist as $i)
			{
				
				//if(strpos($showid,$i,$pos) != false)
				if($showid==$i)
				{
					$count++;
					//$pos = strpos($showid,$i, $pos);
				}
			}
			return $count;
		}
		
		function solrLink($dma,$zone,$timezone, $startdate, $enddate, $starttime, $endtime, $type, $networks, $keywords, $days, $new, $marathon, $premiere, $showids, $genres, $columns, $returnformat, $logid, $grouping, $showtype, $returncount, $page, $titlematch,$live){
			global $db;
			$nets = "fq=";
			
			//<!---------- START DMA INPUT ---------->
			if($dma != 0){
				
				$dsql = "SELECT dm.id,dm.name,dm.rank,dm.code,
								dm.marketsid,dm.timezoneid,
								dm.createdat,dm.updatedat,
								tz.name AS timezonename,
								tz.databasename,tz.abbreviation,tz.phpname,
								tz.utcdifference 

						FROM 	dmas AS dm 
						
						INNER JOIN timezones AS tz 
						ON 		dm.timezoneid = tz.id 

						WHERE dm.id = '{$dma}' 
						AND dm.deletedat IS NULL";

				$dresult = $db->fetch_result($dsql);
				
				$ssql = "	SELECT 	tms.networkid,
									tms.timezone,
									tms.name,
									tms.callsign,
									tms.affiliate,
									tms.city,
									tms.state,
									tms.zipcode,
									tms.country,
									tms.dma,
									tms.dmanumber,
									tms.field12,tms.field13 
							FROM 	tms_networks AS tms 
							WHERE tms.dma = '{$dresult[0]['name']}' ORDER BY tms.callsign ASC ";
				$stations = $db->fetch_result($ssql);
				foreach($stations as $st)
				$nets .= "stationnum:{$st['networkid']}+";
				//$nets = substr($nets,0,-1);
			}
			//----------- END DMA INPUT ----------->

			
			//---------- START ZONE INPUT ---------->
			if($zone != 0){
				$zsql = "SELECT 	zn.zoneid, zn.networkid, 
									z.id, z.name,  z.syscode,  
									z.isdma, z.corporationid, 
									tz.name AS timezonename, 
									tz.databasename, tz.abbreviation, 
									tz.phpname, 
									tz.utcdifference
									
						FROM 		zonenetworks AS zn 
						
						INNER JOIN 	zones AS z 
						
						ON 			zn.zoneid = z.id 	AND 		z.deletedat IS NULL 

						INNER JOIN 	timezones AS tz ON z.timezoneid = tz.id 
						
						WHERE 		zn.zoneid = {$zone}  AND zn.deletedat IS NULL";
						
				$zresult = $db->fetch_result($zsql);
				
				foreach($zresult as $st)
				
				$nets .= "stationnum:{$st['networkid']}+";
			}
			//---------- END ZONE INPUT ---------->
			
			
			//--- START TZ ----->
			$timezone = (isset($timezone) && $timezone != "")?$timezone:$zresult[0]['abbreviation'];
			//--- END TZ ----->
			
			
			//---------- START SINGLE NETWORK LIST ---------->
			if (isset($networks) && $networks != ""){
				$nets = "fq=";
				foreach(explode(',',$networks) as $snetworks){
					if($snetworks != 'undefined')
						$nets .= 'stationnum:"'.$snetworks.'"+';
				}
			}

			//---------- END SINGLE NETWORK LIST ---------->
			
			//<!---------- START DATE TIME ---------->
			$timezonestart = "tz_start_".strtolower($timezone);
			$timezoneend   = "tz_end_".strtolower($timezone);
			$starttimetz   = "start_".strtolower($timezone);
			$timezoneday   = "day_".strtolower($timezone);
			
			$daterange = "fq={$timezonestart}:[{$startdate}T00:00:00Z TO {$enddate}T23:59:59Z]";
			$timerange = "fq={$starttimetz}:[{$starttime} TO {$endtime}]";
			//----------- END DATE TIME ----------->


			//---------- START THE SHOWTYPE ---------->
			switch ($type)
			{
				case "title": $type = "title"; break;
				case "all": $type = "search"; break;
				case "movie": $type = "title"; $ismovie = truebreak; break;
			}
			//<!---------- END THE SHOWTYPE ---------->
			
			//<!----- BUILD THE BASIC SOLR LINK ----->
			$solr  = "http://solr.showseeker.net:8983/solr/gracenote/select/?q=*:*&indent=true&wt=json&fq=projected:0";
			$solr .= "&sort={$timezonestart} asc";
			$solr .= "&{$daterange}";
			$solr .= "&{$timerange}";
			$solr .= "&{$nets}";
			//<!----- END THE SOLR LINK ----->


			//<!--- START THE COLUMN SECTION ---->
			if (isset($columns) && $columns != "")
			$solr .= "&fl={$starttimetz},{$timezonestart},{$timezoneend},{$columns}";
			else
			$solr .= "&fl=id,showtype,showid,tmsid,stationnum,live,genre,title,premierefinale,new,epititle,{$starttimetz},duration,{$timezonestart},{$timezoneend}";
			//<!--- END THE COLUMN SECTION ----->

			
			//<!--- START THE COUNT --->
			if (isset($returncount) && $returncount > 0)
			$solr .= "&rows={$returncount}";
			else
			$solr .= "&rows=5000";
			//<!--- END THE COUNT --->


			//<!--- START THE GENRE LIST --->
			if (isset($genres) && $genres != ""){
				$genrelist = "&fq=(";
				foreach(explode(',',$genres) as $gen){
					$genrelist .= 'genre1:"'.$gen.'" OR genre2:"'.$gen.'" OR genre3:"'.$gen.'" OR genre4:"'.$gen.'" OR genre5:"'.$gen.'" OR ';
				}
				$genrelist .= 'genre1:"showseekerendlist")';
				$solr .= $genrelist;
			}
			//<!--- END THE GENRE LIST --->


			//<!--- START THE KEYWORDS --->
			if (isset($keywords) && $keywords != ""){
				$keyword = "&fq=(";
				$z = 0;
				foreach(explode(',',$keywords) as $i){
					$z++;
					if (substr($i,-1,1)=="s"){
						$i2 = substr($i,1,-1);
						$keyword .=$type.':"'.$i.'" OR '.$type.':"'.$i2.'"';
						} else {
						$i2 = "{$i}s";
						$keyword .=$type.':"'.$i.'" OR '.$type.':"'.$i2.'"';
					}
					if($z != count(explode(',',$keywords))){
						$keyword .= ' OR ';
					}
				}
				$keyword .= ')';
				$solr .= $keyword;
			}
			//<!--- END THE KEYWORDS --->


			//<!--- START THE DAYS  --->
			if (isset($days) && $days != ""){
				$daylist = "fq=";
				foreach(explode(',',$days) as $i){
					if($i != '')
					$daylist .= "{$timezoneday}:{$i}+";
				}
				$solr .= "&{$daylist}";
			}
			//<!--- END THE DAYS  --->


			//<!--- START THE PREMIERE  --->
			if (isset($premiere) && $premiere != ""){
				$premieres = "fq=";
				foreach(explode(',',$premiere) as $i){
					$premieres .= 'premierefinale:"'.$i.'"+';
				}
				$solr .= "&{$premieres}";
			}
			//<!--- END THE PREMIERE  --->

			
			//<!--- START THE SHOW IDS  --->
			if (isset($showids) && $showids != ""){
				$showid = "fq=";
				foreach(explode(',',$showids) as $i){
					$epname = str_ireplace('SH','EP',$i);
					$shname = str_ireplace('EP','SH',$i);
					$showid .='showid:"'.$epname.'"+showid:"'.$shname.'"+';
				}
				$solr .= "&{$showid}";
			}
			//<!--- END THE SHOW IDS  --->


			//<!--- START THE TITLE MATCHES  --->
			if (isset($titlematch) && $titlematch != "") {
				$solr .= '&fq=sort:"'.$titlematch.'"';
			}
			//<!--- END THE TITLE MATCHES  --->


			
			//<!---- START THE NEW ----->
			if (isset($new) && $new == '1') {
				$solr .="&fq=new:New";
			}
			//<!---- START THE NEW ----->
			
			//<!---- START IS A MOVIE ----->
			if (isset($ismovie)){
				$solr .= "&fq=showtype:MV";
			}
			//<!---- END IS A MOVIE ----->			
			
			//<!---- START PAGE ----->
			if (isset($page) && $page != ""){
				$solr .= "&start=".($page*$returncount-$returncount); 
			}
			//<!---- END PAGE ----->
			
			//<!---- START IS A LIVE EVENT ----->
			if (isset($live) && $live == '1') {
				$solr .= "&fq=live:Live";
			}
			//<!---- END  IS A LIVE EVEN ----->
			
			//<!---- START GROUPING ----->
			if (isset($grouping) && $grouping == "showid"){
				$solr .= "&group=true&group.field=showid";
			}
			
			if (isset($grouping) && $grouping == "id"){
				$solr .= "&group=true&group.field=id";
			}
			
			if (isset($grouping) && $grouping == "tmsid"){
				$solr .= "&group=true&group.field=tmsid";
			}
			
			if (isset($grouping) && $grouping == "title"){
				$solr .= "&group=true&group.field=title";
			}
			
			if (isset($grouping) && $grouping == "none"){
			}
						
			
			//<!---- END  GROUPING ----->
			
			//<!---- START THE SHOWTYPE ----->
			if (isset($showtype) && $showtype != ""){
				$showtypes = "fq=";
				foreach(explode(',',$showtype) as $i){
					if ($i == "SH" || $i == "EP"){
						$solr .='&fq=-genre1:"sports event"';
					}
					$showtypes .= 'showtype:"'.$i.'"+';
				}
				$solr .= "&{$showtypes}";
			}
			//<!---- END THE SHOWTYPE ----->
			
					
			$solr .='&fq=-type:"Paid Programming"';
			
			$solr .='&fq=-title:"Movie" ';			



			//$solr = preg_replace("/ /", "%20", $solr);
			//$data = file_get_contents($solr);		
			$solr = str_replace(" ", "+", $solr);			
			
			$data = get_data($solr);
			
			
			$data = str_ireplace($timezonestart, 'starts',$data);
			$data = str_ireplace($timezoneend, 'ends',$data);
			
			if (isset($returnformat) && $returnformat == 'link')
			return $solr;
			else
			return json_decode($data);
		}

		function get_data($url) {	
			$ch = curl_init();
			$timeout = 5;
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}

		
		function marathons($showdata, $marathonmaker=4){
			//SETTING UP VARIABLES THAT WILL DETERMINE THE MARATHONS
			$marathonList = Array();
			$marathonResult = Array();
			$programCounter = 0;
			$grlIndex  = 0;
			$prevId    = ""; //PREVIOUS PROGRAM ID IN THE LIST
			$prevEndTime = ""; //PREVIOUS PROGRAM ENDTIME IN THE LIST; THIS WILL CHAIN THE PROGRAM TO MAKE THE MARATHON
			
			//HOLDER OF MARATHON IDS
			for($j=0;$j<$marathonmaker;$j++) {
				$tmpMarathon[$j][0] = 0;
				$tmpMarathon[$j][1] = 0;
				$tmpMarathon[$j][2] = 0;
			}

			for($i=0;$i<count($showdata);$i++){
				$sdatetime 		= str_replace(array('T','Z'),array(' ',''),$showdata[$i]->starts);
				$thisstarttime 	= date('d-m-Y H:i:s',strtotime($sdatetime));
				$thisprogramkey = $showdata[$i]->stationnum . $showdata[$i]->showid . date('mdY',strtotime($sdatetime));
				$intDuration 	= (int)$showdata[$i]->duration;
				$thisendtime 	= date('d-m-Y H:i:s',strtotime($sdatetime." +{$intDuration} minutes"));
	


				//<!--- Trying to Chain Programs --->
				if ($thisprogramkey == $prevId  && $prevEndTime == $thisstarttime && !in_array($showdata[$i]->title,array("Paid Programming","Programa Pagado","To Be Announced"))){
					$tmpMarathon[$programCounter][0] 	= $showdata[$i]->showid;
					$tmpMarathon[$programCounter][1] 	= $showdata[$i]->tmsid;
					$tmpMarathon[$programCounter][2] 	= $showdata[$i]->id;
					$programCounter++;
					
					if($programCounter == $marathonmaker){
						
						for($ii=0;$ii<$marathonmaker;$ii++){
							$marathonList[] 	= $tmpMarathon[$ii];
						}
						
						$programCounter =0;
					}

				} else {
					
					for($j=0;$j<$marathonmaker;$j++){
						$tmpMarathon[$j][0] = 0;
						$tmpMarathon[$j][1] = 0;
						$tmpMarathon[$j][2] = 0;
					}

					$tmpMarathon[0][0] 	= $showdata[$i]->showid;
					$tmpMarathon[0][1] 	= $showdata[$i]->tmsid;
					$tmpMarathon[0][2] 	= $showdata[$i]->id;
					
					$programCounter 	= 1;
				}
							
				
				$prevId = $thisprogramkey;
				$prevEndTime   = $thisendtime;
				
	
			}

			return (count($marathonList)>0)?$marathonList:"";
		}
	
		function solrLinkSp($dma,$zone,$timezone, $startdate, $enddate, $starttime, $endtime, $type, $networks, $keywords, $days, $new, $marathon, $premiere, $showids, $genres, $columns, $returnformat, $logid, $grouping, $showtype, $returncount, $page, $titlematch,$live)
		{
			global $db;
			$nets = "fq=";
			
			//<!---------- START DMA INPUT ---------->
			if($dma != 0)
			{
				$dsql = "SELECT dm.id,dm.name,dm.rank,dm.code,dm.marketsid,dm.timezoneid,dm.createdat,dm.updatedat,dm.deletedat,tz.name AS timezonename,tz.databasename,tz.abbreviation,tz.phpname,tz.utcdifference,tz.createdat AS timezonecreatedat,tz.updatedat AS timezoneupdatedat FROM dmas AS dm INNER JOIN timezones AS tz ON dm.timezoneid = tz.id WHERE dm.id = '{$dma}' AND dm.deletedat IS NULL";
				$dresult = $db->fetch_result($dsql);
				
				$ssql = "SELECT tms.networkid,tms.timezone,tms.name,tms.callsign,tms.affiliate,tms.city,tms.state,tms.zipcode,tms.country,tms.dma,tms.dmanumber,tms.field12,tms.field13 FROM tms_networks AS tms WHERE tms.dma = '{$dresult[0]['name']}' ORDER BY tms.callsign ASC ";
				$stations = $db->fetch_result($ssql);
				foreach($stations as $st)
				$nets .= "stationnum:{$st['networkid']}+";
				//$nets = substr($nets,0,-1);
			}
			//<!----------- END DMA INPUT ----------->
			
			//<!---------- START ZONE INPUT ---------->
			if($zone != 0)
			{
				$zsql = "SELECT zn.zoneid, zn.networkid, zn.createdat, zn.updatedat, zn.deletedat, z.id, z.name, z.dmaid, z.syscode, z.zipcode, z.timezoneid, z.isdma, z.corporationid, z.createdat AS zonecreatedat, z.updatedat AS zoneupdatedat, z.deletedat AS zonedeletedat, tz.name AS timezonename, tz.databasename, tz.abbreviation, tz.phpname, tz.utcdifference, tz.createdat AS timezonecreatedat, tz.updatedat AS timezoneupdatedat FROM zonenetworks AS zn INNER JOIN zones AS z ON zn.zoneid = z.id AND z.deletedat IS NULL INNER JOIN timezones AS tz ON z.timezoneid = tz.id WHERE 
				zn.zoneid = {$zone}  AND zn.deletedat IS NULL";
				$zresult = $db->fetch_result($zsql);
				foreach($zresult as $st)
				$nets .= "stationnum:{$st['networkid']}+";
				//$nets = substr($nets,0,-1);
			}
			//<!---------- END ZONE INPUT ---------->
			
			//<!--- START TZ ----->
			$timezone = (isset($timezone) && $timezone != "")?$timezone:$zresult[0]['abbreviation'];
			//<!--- END TZ ----->
			
			//<!---------- START SINGLE NETWORK LIST ---------->
			if (isset($networks) && $networks != "")
			{
				$nets = "fq=";
				foreach(explode(',',$networks) as $snetworks)
				$nets .= "stationnum:{$snetworks}+";
				//$nets  = substr($nets,0,-1);
			}
			//<!---------- END SINGLE NETWORK LIST ---------->
			
			//<!---------- START DATE TIME ---------->
			$timezonestart = "tz_start_".strtolower($timezone);
			$timezoneend   = "tz_end_".strtolower($timezone);
			$starttimetz   = "start_".strtolower($timezone);
			$timezoneday   = "day_".strtolower($timezone);
			
			$daterange = "fq={$timezonestart}:[{$startdate}T00:00:00Z TO {$enddate}T00:00:00Z]";
			$timerange = "fq={$starttimetz}:[{$starttime} TO {$endtime}]";
			//<!----------- END DATE TIME ----------->
			
			//<!---------- START THE SHOWTYPE ---------->
			switch ($type)
			{
				case "title": $type = "title"; break;
				case "all": $type = "search"; break;
				case "movie": $type = "title"; $ismovie = truebreak; break;
			}
			//<!---------- END THE SHOWTYPE ---------->
			
			//<!----- BUILD THE BASIC SOLR LINK ----->
			$solr  = "http://solr.showseeker.net:8983/solr/gracenote/select/?q=*:*&indent=true&wt=json";
			$solr .= "&sort={$timezonestart} asc&sort=title asc";
			$solr .= "&{$daterange}";
			$solr .= "&{$timerange}";
			$solr .= "&{$nets}";
			//<!----- END THE SOLR LINK ----->
			
			//<!--- START THE COLUMN SECTION ---->
			if (isset($columns) && $columns != "")
			$solr .= "&fl={$starttimetz},{$timezonestart},{$timezoneend},{$columns}";
			else
			$solr .= "&fl=id,showtype,showid,tmsid,stationnum,live,genre,title,premierefinale,new,epititle,{$starttimetz},duration,{$timezonestart},{$timezoneend}";
			//<!--- END THE COLUMN SECTION ----->
			
			//<!--- START THE COUNT --->
			if (isset($returncount) && $returncount > 0)
			$solr .= "&rows={$returncount}";
			else
			$solr .= "&rows=2000";
			//<!--- END THE COUNT --->
			
			
			//<!--- START THE GENRE LIST --->
			if (isset($genres) && $genres == "sports event")
			{
				$genrelist = '&fq=genre1:"sports event"';
				$solr .= $genrelist;
			} else
			{ 
				$genrelist = "&fq=(";
				foreach(explode(',',$genres) as $gen)
				{
					$genrelist .= '((genre1:"sports event") AND (genre2:"'.$gen.'" OR genre3:"'.$gen.'" OR genre4:"'.$gen.'" OR genre5:"'.$gen.'")) OR ';
				}
				$genrelist = substr($genrelist,0,-4).')';
				$solr .= $genrelist;
			}
			//<!--- END THE GENRE LIST --->
			
			
			//<!--- START THE KEYWORDS --->
			if (isset($keywords) && $keywords != "")
			{
				$keyword = "&fq=(";
				$z = 0;
				foreach(explode(',',$keywords) as $i){
					$z++;
					if (substr($i,-1,1)=="s"){
						$i2 = substr($i,1,-1);
						$keyword .=$type.':"'.$i.'" OR '.$type.':"'.$i2.'"';
					} else {
						$i2 = "{$i}s";
						$keyword .=$type.':"'.$i.'" OR '.$type.':"'.$i2.'"';
					}
					if($z != count(explode(',',$keywords))){
						$keyword .= ' OR ';
					}
				}
				$keyword .= ')';
				$solr .= $keyword;
			}
			//<!--- END THE KEYWORDS --->
			
			//<!--- START THE DAYS  --->
			if (isset($days) && $days != "")
			{
				$daylist = "fq=";
				foreach(explode(',',$days) as $i){
					$daylist .= "{$timezoneday}:{$i}+";
				}
				$solr .= "&{$daylist}";
			}
			//<!--- END THE DAYS  --->
			
			//<!--- START THE PREMIERE  --->
			if (isset($premiere) && $premiere != "")
			{
				$premieres = "fq=";
				foreach(explode(',',$premiere) as $i){
					$premieres .= 'premierefinale:"'.$i.'"+';
				}
				$solr .= "&{$premieres}";
			}
			//<!--- END THE PREMIERE  --->
			
			//<!--- START THE SHOW IDS  --->
			if (isset($showids) && $showids != "") {
				$showid = "fq=";
				foreach(explode(',',$showids) as $i){
					$epname = str_ireplace('SH','EP',$i);
					$shname = str_ireplace('EP','SH',$i);
					$showid .='showid:"'.$epname.'"+showid:"'.$shname.'"+';
				}
				$solr .= "&{$showid}";
			}
			//<!--- END THE SHOW IDS  --->
			
			//<!--- START THE TITLE MATCHES  --->
			if (isset($titlematch) && $titlematch != "") {
				$solr .= '&fq=sort:"'.$titlematch.'"';
			}
			//<!--- END THE TITLE MATCHES  --->
			
			//<!---- START THE NEW ----->
			if (isset($new) && $new == '1') {
				$solr .="&fq=new:New";
			}
			//<!---- START THE NEW ----->
			
			//<!---- START IS A MOVIE ----->
			if (isset($ismovie)){
				$solr .= "&fq=showtype:MV";
			}
			//<!---- END IS A MOVIE ----->
			
			//<!---- START PAGE ----->
			if (isset($page) && $page != ""){
				$solr .= "&start=".($page*$returncount-$returncount); 
			}
			//<!---- END PAGE ----->
			
			//<!---- START IS A LIVE EVENT ----->
			if (isset($live) && $live == '1') {
				$solr .= "&fq=live:Live";
			}
			//<!---- END  IS A LIVE EVEN ----->
			
			//<!---- START GROUPING ----->
			if (isset($grouping) && $grouping == "showid"){
				$solr .= "&group=true&group.field=showid";
			}
			
			if (isset($grouping) && $grouping == "id"){
				$solr .= "&group=true&group.field=id";
			}
			
			if (isset($grouping) && $grouping == "tmsid"){
				$solr .= "&group=true&group.field=tmsid";
			}
			
			if (isset($grouping) && $grouping == "title"){
				$solr .= "&group=true&group.field=title";
			}
			
			if (isset($grouping) && $grouping == "none"){
			}
			//<!---- END  GROUPING ----->
			
			//<!---- START THE SHOWTYPE ----->
			if (isset($showtype) && $showtype != ""){
				$showtypes = "fq=";
				foreach(explode(',',$showtype) as $i){
					if ($i == "SH" || $i == "EP"){
						$solr .='&fq=-genre1:"sports event"';
					}
					$showtypes .= 'showtype:"'.$i.'"+';
				}
				$solr .= "&{$showtypes}";
			}
			//<!---- END THE SHOWTYPE ----->

			
			
			$solr .='&fq=-type:"Paid Programming" ';
			$solr = preg_replace("/ /", "%20", $solr);

			//print "---------".$solr;

			$data = file_get_contents($solr);
			$data = str_ireplace($timezonestart, 'starts',$data);
			$data = str_ireplace($timezoneend, 'ends',$data);
			
			
			// <!--- LOG --->
				// <cftry>
				// <cfif isDefined("arguments.logid") AND #arguments.logid# GT "">
				// <cfset loggerid = #arguments.logid#>
				// <cfelse>
				// <cfset loggerid = 7>
				// </cfif>
				
				// <cfset userlog = model("userlogs").new() />
				// <cfset userlog.userid = 0>
				// <cfset userlog.eventslogid = #loggerid# />
				// <cfset userlog.request = "#CGI.SERVER_NAME#" & "#CGI.PATH_INFO#" & "?#CGI.QUERY_STRING#" />
				// <cfset userlog.result = "#solr#"/>
				// <cfset userlog.save() />
				// <cfcatch></cfcatch>
			// </cftry>
			
			if (isset($returnformat) && $returnformat == 'link')
			return $solr;
			else
			return json_decode($data);
		}

	?>