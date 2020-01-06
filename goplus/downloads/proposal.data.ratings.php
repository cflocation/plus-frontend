<?php 
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	//require_once 'commons/initialize.php';
	define("XML_FOLDER","xmls/");
	//$apiUrl = 'https://apidev.showseeker.com:8585/';	
	$apiUrl = 'https://plusapi.showseeker.com/';
	if(isset($_GET['proposalid'])){
		
		$hiderate		= urldecode($_GET['hiderates']);
		$includelogos	= urldecode($_GET['logos']);
		$includedesc	= urldecode($_GET['description']);
		$includenew		= urldecode($_GET['includenew']);
		$includetc		= urldecode($_GET['addterms']);
		$showratecard	= urldecode($_GET['showratecard']);
		$proposalid		= trim(urldecode($_GET['proposalid']));
		$userid			= trim(urldecode($_GET['userid']));
		$tokenid		= trim(urldecode($_GET['tokenid']));
		$headerid		= trim(urldecode($_GET['headerid']));
		$onlyfixed		= (isset($_GET['onlyfixed']) && urldecode(trim($_GET['onlyfixed']))=='true')?true:false;
		$sort1			= (isset($_GET['sort1']) && $_GET['sort1']!='default')?trim(urldecode($_GET['sort1'])):'startdate';
		$sort2			= (isset($_GET['sort2']) && $_GET['sort2']!='default')?trim(urldecode($_GET['sort2'])):'starttime';
		$sort3			= (isset($_GET['sort3']) && $_GET['sort3']!='default')?trim(urldecode($_GET['sort3'])):'network';
		$isemail		= (isset($_GET['email']))?trim(urldecode($_GET['email'])):'false';
		$standardTotals	= urldecode(trim($_GET['calendar']));
		$agencyId		= urldecode(trim($_GET['agencyid']));
		$agencyId2		= urldecode(trim($_GET['agencyid2']));
		$clientId		= urldecode(trim($_GET['clientid1']));
		$clientId2		= urldecode(trim($_GET['clientid2']));
		$repfirmId		= urldecode(trim($_GET['repfirmid']));

		//USER INFO
		$usrUrl 	= $apiUrl."user/info";
		$usr 		= getProposalJSON($usrUrl,$tokenid,$userid);
		$user 		= json_decode($usr);
		$user 		= $user->userInfo;
		$corp 		= $user->corporationId;	
		$tk 		= $user->tokenId;		

		//!--- SELECTED AGENCY --->
		if($agencyId != '' && $agencyId != '0'){
			$url	= $apiUrl."client/".$corp."/agency/$agencyId";
			$ag 	= getData2($url,$tokenid,$userid);
			$agency = $ag->agency->name;
		}
		else{
			$agency = '';			
		}
		
		//--- SELECTED ADVERTISER --->
		if($clientId != '' && $clientId != '0'){
			$url	= $apiUrl."client/".$corp."/client/$clientId";
			$cl 	= getData2($url,$tokenid,$userid);		
			$clientselected = $cl->client->name;
		}	
		else{
			$clientselected = '';			
		}

	
		//--- SELECTED REPFIRM --->
		if($repfirmId != '' && $repfirmId != 0){
			$url	= $apiUrl."repfirm/".$repfirmId;
			$rep 	= getData2($url,'dab36cc50029de44279cd3ba32e31b23','3312');	
			$repfirm_result = $rep->repFirm->name;
		}
		else{
			$repfirm_result = '';
		}

		//GET THE PROPOSAL
		$pslUrl 	= $apiUrl."proposal/json/".$proposalid;
		$psl 		= getProposalJSON($pslUrl,$tk,$userid);
		$pslJSON 	= json_decode($psl);


		//PROPOSAL LINES
		$proposal = $pslJSON->lines;


		//CORPORATION INFO
		$corporation = $user;
		
		
		if($onlyfixed){
			
			foreach($proposal AS $k=>$value){

				if($value->linetype!="Fixed"){
					unset($proposal[$k]);
				}		
			}

			$proposal = array_values($proposal);
		}
		
		
		//PROPOSAL INFO
		$proposalDateArr 	= getProposalStartEndDate($proposal);
		$pslname 			= $pslJSON->name;		
		$proposalInfo 		= array('createdate'	=> $pslJSON->createdAt,
									'enddate'		=> $proposalDateArr['enddate'],
									'id'			=> $proposalid,'name'=>$pslname,
									'ratings'		=> $pslJSON->ratings,
									'startdate'		=> $proposalDateArr['startdate'],
									'updatedate'	=> $pslJSON->updatedAt);

		//DISCOUNT
		$discountpackagetype= ($pslJSON->discountType=='2')?'$':'percent';
		$agencydiscount		= ($pslJSON->agencyDiscount=='1')?true:false;
		$discount			= array("discount"=>$pslJSON->discount, "type" => $discountpackagetype, "agency" => $agencydiscount);

		//ZONES
		$zones 				= $pslJSON->zones;	
		
		//NETWORK LOGOGS
		$networks 			= getNetworks($pslJSON->networks);
		
		//WEEKS IN PROPOSAL
		$weeksTotal			= getBreakdownByWeeks($proposal); //array('01/10/2014' => '125.00','01/17/2014'=>'1254.36','01/22/2014'=>'568.20');
				
		//BREAKOUT MONTHS
		$brodmonthstotal 	= getBreakdownByMonth($proposal, $pslJSON->discount, $pslJSON->discountType, $agencydiscount);
		$calmonthstotal 	= getBreakdownByStandardCalendarMonth($proposal, $pslJSON->discount, $pslJSON->discountType, $agencydiscount);
		
		//TOTALS
		$totals 			= getTotals($proposal, $pslJSON->discount, $pslJSON->discountType, $agencydiscount,$pslJSON->totals->spot);	
		$proposal 			= getProposalsByZone($proposal, $pslJSON->discount, $pslJSON->discountType, $agencydiscount, $sort1,$sort2,$sort3,$zones);


		//RATINGS
		$ratingsTotals		= getRatingsTotals($pslJSON->totals);
		$ratingsSettings	= getRatingsSettings($pslJSON->ratingsSetttings);
		
		
		$re = array("broadcasttotals"	=> $brodmonthstotal,
					"corporation"		=> $corporation,
					"discount"			=> $discount,
					"networks"			=> $networks,
					"ratingsSettings"	=> $ratingsSettings,
					"ratingsTotals"		=> $ratingsTotals,
					"standardtotals"	=> $calmonthstotal,
					"proposal"			=> $proposal,
					"proposalinfo"		=> $proposalInfo,
					"totals"			=> $totals,
					"user"				=> $user,
					"weeks"				=> $weeksTotal,
					"zones"				=> $zones);
										
		
		$json_data =  json_encode($re);	
		
	}
	
	function getProposalsByZone($proposal, $discountpackage, $discountpackagetype, $agencydiscount, $sort1,$sort2,$sort3,$zones){

		$return = array();
	
		foreach($proposal as $ln){
		
			$zoneid = intval($ln->zoneid);
		
			if(!isset($return[$zoneid])){
				$return[$zoneid] 			= array();
				$return[$zoneid]['zone']	= getZoneInfo($zoneid,$zones);
				$return[$zoneid]['lines']	= array();
			}
			
			$return[$zoneid]['lines'][] = $ln;
		}
		
		foreach($return AS &$z){
			$z['weeks']				= getBreakdownByWeeks($z['lines']); 
			$z['brodcastmonths']	= getBreakdownByMonth($z['lines'], $discountpackage, $discountpackagetype, $agencydiscount); 
			$z['calendarmonths']	= getBreakdownByStandardCalMonth($z['lines'], $discountpackage, $discountpackagetype, $agencydiscount); 
			$z['lines']				= sortProposalLines($z['lines'], $sort1,$sort2,$sort3);
		}
		
		usort($return, function($a, $b){
			return strcmp($a['zone']['zonename'],$b['zone']['zonename']);
		});
			
		return $return;
	}
	
	
	
	//NETWORK LOGOS
	function getNetworks($nets){
		$networks = array();
		
		foreach($nets as $n){
			$netinfo = array('stationnum'	=> $n->networkId,
							 'callsign'		=> $n->callSign,
							 'ncc'			=> $n->nccCallsign,
							 'nccId'		=> $n->nccId,
							 'dmaId'		=> $n->dmaNumber,
							 's3'			=> '/showseeker/images/netwroklogo/75/'.$n->networkId.'.png',
							 '100x100'		=> $n->filename,
							 '40x40'		=> 'http://ww2.showseeker.com/images/_thumbnails/'.$n->filename);

			array_push($networks,$netinfo);
		}
		
		return $networks;
	}
	

	
	
	//BREAKOUT MONTHS
	function getBreakdownByMonth($proposalLines, $discountpackage, $discountpackagetype, $agencydiscount){
		$removeAttrArr = array(
		'id','ssid','zone','zoneid','linetype','title','callsign','stationnum','stationname','startdate','enddate','starttime','endtime','startdatetime'
		,'enddatetime','day','desc','epititle','live','genre','premiere','isnew','stars','orgairdate','lineactive','search','showid','locked'
		,'rate','ratecardid','ratevalue','weeks','spotsweek','spots','timestamp','total','split','titleFormat','callsignFormat','dayFormat','statusFormat','sortingStartDate'
		,'sortFormat','showLine','sortingMarathons','linetype2','year','genre2','showtype','programid','projected','avail','availsDay','availsShow','statusOrder','_dirty'
		,'weekId','zonetitle','zonenetwork','titlenetworkFormat','weekdays','ratename','ncc','zonenetworktitle','networktitle','broadcastweek','tvrating','tvrating','cost');
		$monthsArr 	= array();
		$totalAmt 	= 0;
		foreach($proposalLines AS $ln){
			
			$ln 		= (array)$ln;
			$rate 		= $ln['rate'];
			$isRotator 	= ($ln['linetype']=="Rotator")?true:false;

			foreach($removeAttrArr as $attr)
				unset($ln[$attr]);
			
			foreach($ln as $wk=>$spots){
				
				if (strpos($wk,'hide') !== false) continue; //Hidden Week, Ignore it.
				if (substr($wk,0,1)!='w') continue; //not a week.. you better stop here![ something remained to be removed.. it made it way till here..:) but no further..]
				if (strpos($wk,'days') >0) continue;
				
				$month	= substr($wk,5,2);
				$day	= substr($wk,7,2);
				$year	= substr($wk,1,4);
				
				$wkdate			= "$month/$day/$year";
				$brcastmonthYr  = explode('-',getBroadcastMonthYear($wkdate));
				
				if(isset($monthsArr[$brcastmonthYr[1]][$brcastmonthYr[0]])){
					$monthsArr[$brcastmonthYr[1]][$brcastmonthYr[0]]['spotsmonth'] 	+= $spots;
					$monthsArr[$brcastmonthYr[1]][$brcastmonthYr[0]]['monthtotal'] 	+= $spots*$rate;
					$monthsArr[$brcastmonthYr[1]][$brcastmonthYr[0]]['fixed'] 		+= ($isRotator)?0:floatval($spots);
					$monthsArr[$brcastmonthYr[1]][$brcastmonthYr[0]]['rotators'] 	+= ($isRotator)?floatval($spots):0;
					$totalAmt += $spots*$rate;
				} 
				else{
					$monthsArr[$brcastmonthYr[1]][$brcastmonthYr[0]] = array(
						'monthnumber'	=> str_pad($brcastmonthYr[0],2,'0',STR_PAD_LEFT)
						,'monthfull'	=> date('F',mktime(0,0,0,$brcastmonthYr[0],1,$brcastmonthYr[1]))
						,'monthabr'		=> date('M',mktime(0,0,0,$brcastmonthYr[0],1,$brcastmonthYr[1]))
						,'spotsmonth'	=> $spots
						,'fixed'		=> ($isRotator)?0:floatval($spots)
						,'rotators'		=> ($isRotator)?floatval($spots):0
						,'monthtotal'	=> $spots*$rate
					);
					$totalAmt += $spots*$rate;
				}
			}
		}
		ksort($monthsArr);

		foreach($monthsArr AS &$y){
			foreach($y as &$m){
				$pkgDiscnt 		= 0;
				$agncyDiscnt 	= 0;
				
				if($discountpackage>0){
					if($discountpackagetype==1){
						$pkgDiscnt = ($discountpackage * $m['monthtotal']) / 100;
					}
					else if($discountpackagetype==2){
						$PercentDiscount = ($totalAmt==0)?0:($discountpackage*100)/$totalAmt;
						$pkgDiscnt = ($PercentDiscount * $m['monthtotal']) / 100;
					}
				}
				
				if($agencydiscount){
					$agncyDiscnt = (($m['monthtotal']-$pkgDiscnt)*0.15);
				}
				
				$m['discount'] = round($pkgDiscnt+$agncyDiscnt,2);
				$m['nettotal'] = round(($m['monthtotal']-$pkgDiscnt-$agncyDiscnt),2);
			}
			ksort($y);
			$y = array_values($y);
		}
		return $monthsArr;
	}


	//BREAKOUT MONTHS
	function getBreakdownByStandardMonth($proposalLines, $discountpackage, $discountpackagetype, $agencydiscount){
		$removeAttrArr = array(
		'id','ssid','zone','zoneid','linetype','title','callsign','stationnum','stationname','startdate','enddate','starttime','endtime','startdatetime'
		,'enddatetime','day','desc','epititle','live','genre','premiere','isnew','stars','orgairdate','lineactive','search','showid','locked'
		,'rate','ratecardid','ratevalue','weeks','spotsweek','spots','timestamp','total','split','titleFormat','callsignFormat','dayFormat','statusFormat','sortingStartDate'
		,'sortFormat','showLine','sortingMarathons','linetype2','year','genre2','showtype','programid','projected','avail','availsDay','availsShow','statusOrder','_dirty'
		,'weekId','zonetitle','zonenetwork','titlenetworkFormat','weekdays','ratename','ncc','zonenetworktitle','networktitle','broadcastweek','tvrating','tvrating','cost');
		
		$monthsArr = array();
		$totalAmt = 0;
		foreach($proposalLines AS $ln){
			
			$ln 		= (array)$ln;
			
			$rate 		= $ln['rate'];
			
			$isRotator 	= ($ln['linetype']=="Rotator")?true:false;

			foreach($removeAttrArr as $attr)
				unset($ln[$attr]);
			
			foreach($ln as $wk=>$spots){
				
				if (strpos($wk,'hide') !== false) continue; //Hidden Week, Ignore it.
				if (substr($wk,0,1)!='w') continue; //not a week.. you better stop here![ something remained to be removed.. it made it way till here..:) but no further..]
				if (strpos($wk,'days') >0) continue;
				
				$month	= substr($wk,5,2);
				$day	= substr($wk,7,2);
				$year	= substr($wk,1,4);
				$wkdate			= "$month/$day/$year";
				$brcastmonthYr  = explode('-',getStandardMonthYear($wkdate));
				
				if(isset($monthsArr[$brcastmonthYr[1]][$brcastmonthYr[0]])){
					$monthsArr[$brcastmonthYr[1]][$brcastmonthYr[0]]['spotsmonth'] 	+= $spots;
					$monthsArr[$brcastmonthYr[1]][$brcastmonthYr[0]]['monthtotal'] 	+= $spots*$rate;
					$monthsArr[$brcastmonthYr[1]][$brcastmonthYr[0]]['fixed'] 		+= ($isRotator)?0:floatval($spots);
					$monthsArr[$brcastmonthYr[1]][$brcastmonthYr[0]]['rotators'] 	+= ($isRotator)?floatval($spots):0;
					$totalAmt += $spots*$rate;
				} 
				else{
					$monthsArr[$brcastmonthYr[1]][$brcastmonthYr[0]] = array(
						'monthnumber'	=> str_pad($brcastmonthYr[0],2,'0',STR_PAD_LEFT)
						,'monthfull'	=> date('F',mktime(0,0,0,$brcastmonthYr[0],1,$brcastmonthYr[1]))
						,'monthabr'		=> date('M',mktime(0,0,0,$brcastmonthYr[0],1,$brcastmonthYr[1]))
						,'spotsmonth'	=> $spots
						,'fixed'		=> ($isRotator)?0:floatval($spots)
						,'rotators'		=> ($isRotator)?floatval($spots):0
						,'monthtotal'	=> $spots*$rate);
						
					$totalAmt += $spots*$rate;
				}
			}
		}
		ksort($monthsArr);

		foreach($monthsArr AS &$y){
			foreach($y as &$m){
				$pkgDiscnt = 0;
				$agncyDiscnt = 0;

				if($discountpackage>0){
					if($discountpackagetype==1){
						$pkgDiscnt = ($discountpackage * $m['monthtotal']) / 100;
					}
					else if($discountpackagetype==2){
						$PercentDiscount = ($totalAmt==0)?0:($discountpackage*100)/$totalAmt;
						$pkgDiscnt = ($PercentDiscount * $m['monthtotal']) / 100;
					}
				}
				
				if($agencydiscount){
					$agncyDiscnt = (($m['monthtotal']-$pkgDiscnt)*0.15);
				}
				
				$m['discount'] = round($pkgDiscnt+$agncyDiscnt,2);
				$m['nettotal'] = round(($m['monthtotal']-$pkgDiscnt-$agncyDiscnt),2);
			}
			ksort($y);
			$y = array_values($y);
		}
		return $monthsArr;
	}



	function getRatingsTotals($totals){
		$rTotals = array('zonesTotals'=>$totals->ratingsZonesTotals,'grandTotal'=>$totals->ratingsTotals);
		return $rTotals;
	}


	function getRatingsSettings($rtgSettings){
		$s = array(
		'average' 		=> $rtgSettings->average,
		'books' 		=> $rtgSettings->books,
		'cdma' 			=> mapArea($rtgSettings->demographicArea,2),
		'dma' 			=> mapArea($rtgSettings->demographicArea,1),
		'demographics' 	=> $rtgSettings->demographics,
		'demosInfo'		=> mapDemoInfo($rtgSettings->ratings,$rtgSettings->impressions),
		'impressions' 	=> $rtgSettings->impressions,
		'marketId' 		=> $rtgSettings->market,
		'projection' 	=> $rtgSettings->projection,
		'ratings' 		=> $rtgSettings->ratings,
		'rounded'  		=> $rtgSettings->rounded,
		'survey' 		=> $rtgSettings->survey,
		'surveyMarket' 	=> $rtgSettings->surveyMarket
		);
		
		return $s;
	}

	function mapArea($areas,$v){
		foreach($areas as $a){
			if($a == $v){
				return true;
			}
		}
		return 0;
	}

	function mapDemoInfo($rtg,$imp){
		if($rtg == 1 && $imp == 1){

			$h = array('RTG', 'CPP', 'GRPs', 'SHR',  'IMP', 'GIMPs', 'CPM', 'R%','Freq');
			$k = array('rating','CPP','gRps','share','persons','gImps','CPM','reach','freq');
		}
		elseif($rtg == 1){
			$h = array('RTG', 'CPP', 'GRPs', 'SHR', 'Reach%','Freq');
			$k = array('rating','CPP', 'gRps','share','reach','freq');
		}
		else{
			$h = array('IMP', 'GIMPs', 'CPM', 'Reach%', 'Freq');
			$k = array('persons','gImps','CPM','reach','freq');
		}
		return array('header'=>$h, 'keys'=>$k);
	}
	
	function getTotals($proposal, $discountpackage, $discountpackagetype, $agencydiscount, $totalspots){
		$grossTotal 	= 0;
		$totalRotators 	= 0;
		$totalFixed 	= 0;
		$totalSpotsCal 	= 0;
		
		foreach($proposal as $ln){
			$totalRotators 	+= ($ln->linetype == "Rotator")?floatval($ln->spots):0;
			$totalFixed 	+= ($ln->linetype == "Rotator")?0:floatval($ln->spots);
			$totalSpotsCal 	+= floatval($ln->spots);
			$grossTotal 	+= floatval($ln->total);
		}

		$pkgDiscnt = 0;
		$agncyDiscnt = 0;
		
		if($discountpackage>0){
			if($discountpackagetype==1){
				$pkgDiscnt = ($discountpackage * $grossTotal) / 100;
			}
			else if($discountpackagetype==2){
				$pkgDiscnt = $discountpackage;
			}
		}
		
		if($agencydiscount){
			$agncyDiscnt = (($grossTotal-$pkgDiscnt)*0.15);
		}
		
		$netTotal = $grossTotal-$pkgDiscnt-$agncyDiscnt;
		
		return array(
					  'gross'=>round($grossTotal,2)
					  ,'packagediscount'=>round($pkgDiscnt,2)
					  ,'agencydiscount'=>round($agncyDiscnt,2)
					  ,'net'=>round($netTotal,2)
					  ,'spots'=>$totalspots
					  ,'rotators'=>$totalRotators
					  ,'fixed'=>$totalFixed
					 );
	}
	
	
	
	
	
	function getBreakdownByWeeks($proposalLines){
		$removeAttrArr = array(
		'id','ssid','zone','zoneid','linetype','title','callsign','stationnum','stationname','startdate','enddate','starttime','endtime','startdatetime'
		,'enddatetime','day','desc','epititle','live','genre','premiere','isnew','stars','orgairdate','lineactive','search','showid','locked'
		,'rate','ratecardid','ratevalue','weeks','spotsweek','spots','timestamp','total','split','titleFormat','callsignFormat','dayFormat','statusFormat','sortingStartDate'
		,'sortFormat','showLine','sortingMarathons','linetype2','year','genre2','showtype','programid','projected','avail','availsDay','availsShow','statusOrder','_dirty'
		,'weekId','zonetitle','zonenetwork','titlenetworkFormat','weekdays','ratename','ncc','zonenetworktitle','networktitle','broadcastweek','tvrating','tvrating','cost'
		);
		
		$weeksArr = array();
		
		foreach($proposalLines AS $ln){
			
			$ln = (array)$ln;
			$rate = floatval($ln['rate']);
			$isRotator = ($ln['linetype']=="Rotator")?true:false;
			
			foreach($removeAttrArr as $attr){
				unset($ln[$attr]);
			}
			
			foreach($ln as $wk=>$spots){
				if (strpos($wk,'hide') !== false) continue; //Hidden Week, Ignore it.
				if (substr($wk,0,1)!='w') continue; //not a week.. you better stop here![ something remained to be removed.. it made its way till here..:) but no further..]
				if (strpos($wk,'days') >0) continue;
				
				$month	= substr($wk,5,2);
				$day	= substr($wk,7,2);
				$year	= substr($wk,1,4);
				$index 	= "$year$month$day";
				$week 	= "$year/$month/$day";				
				
				if(isset($weeksArr[$index])){
					$weeksArr[$index]['spots'] +=  floatval($spots);
					$weeksArr[$index]['total'] +=  round((floatval($spots)*$rate),2);
					$weeksArr[$index]['rotators'] +=  ($isRotator)?floatval($spots):0;
					$weeksArr[$index]['fixed'] +=  ($isRotator)?0:floatval($spots);
				} 
				else{
					$weeksArr[$index] = array('week'=>$week,'spots'=>floatval($spots),'total'=>round((floatval($spots)*$rate),2),"rotators"=>($isRotator)?floatval($spots):0,"fixed"=>($isRotator)?0:floatval($spots));
				}
			}
		}
		
		ksort($weeksArr);
		$weeksArr = array_values($weeksArr);
		return $weeksArr;
	}
	
	function getProposalStartEndDate($proposalLines){
		$startDate	= false;
		$endDate	= false;
		foreach($proposalLines as &$ln){
			$startDate = ($startDate === false)?strtotime($ln->startdate):min($startDate,strtotime($ln->startdate));
			$endDate = ($endDate === false)?strtotime($ln->enddate):max($endDate,strtotime($ln->enddate));
		}
		return array('startdate'=>date('m/d/Y',$startDate), 'enddate'=>date('m/d/Y',$endDate));
	}

	function sortProposalLines($proposalLines, $sort1,$sort2,$sort3){
	
		$sort1 = ($sort1=='network')?'callsign':$sort1;
		$sort2 = ($sort2=='network')?'callsign':$sort2;
		$sort3 = ($sort3=='network')?'callsign':$sort3;

		foreach($proposalLines as &$ln){
		
			$tstime =$ln->starttime;
			$tetime =$ln->endtime;			

			$ln->startdate 	= date('Y-m-d',strtotime($ln->startdate));
			$ln->enddate 	= date('Y-m-d',strtotime($ln->enddate));
			$ln->starttime 	= date('H:i:s',strtotime($ln->starttime));
			$ln->endtime 	= date('H:i:s',strtotime($ln->endtime));
			$ln->sortkey 	= "{$ln->$sort1}-{$ln->$sort2}-{$ln->$sort3}";
			$ln->startdate 	= date('m/d/Y',strtotime($ln->startdate));
			$ln->enddate 	= date('m/d/Y',strtotime($ln->enddate));
			$ln->starttime 	= $tstime;
			$ln->endtime 	= $tetime;
		}
		
		usort($proposalLines, "compareLines");
		
		foreach($proposalLines as &$ln){
			unset($ln->sortkey);
		}
		
		return $proposalLines;
		
	}
	
	function compareLines($a, $b){
		return strcmp($a->sortkey,$b->sortkey);
	}
	
	function getProposalName($proposalname,$headerid){
		if($headerid != 0){
			$sql = "select header from headers where id = $headerid";
			$result = mysql_query($sql);
			$pn = mysql_fetch_assoc($result);
			$pname = $pn['header'];
		}
		else{
			$pname = $proposalname;
		}
		return $pname;
	}

	function getBroadcastMonthYear($date){
		$dtstmp = strtotime($date);
		$monthName = date('F',mktime(0,0,0,substr($date,0,2),1,substr($date,-4)));
		$lastSunStmp = strtotime("last Sunday of $monthName ".substr($date,-4));
		if($dtstmp>$lastSunStmp)
		return date('m-Y',mktime(0,0,0,intval(substr($date,0,2))+1,1,substr($date,-4)));
		else
		return date('m-Y',mktime(0,0,0,intval(substr($date,0,2)),1,substr($date,-4)));
	}

	function getStandardMonthYear($date){
		$dtstmp 	= strtotime($date);
		return date('m-Y', $dtstmp);	
	}

	
	//Zones
	function getZoneInfo($zoneId,$zones){
		
		foreach($zones as $z){
			if($z->id == $zoneId){
				$zone = array(	'zoneid' =>  $z->id,
								'zonename' => $z->name, 
								'isdma' => 	$z->isDMA,
								'dmaid' =>  $z->dmaId,
								'syscode' =>  $z->sysCode,
								'timezoneid' => $z->timeZoneId, 
								'timezonename' => $z->timezoneName, 
								'abbreviation' => $z->abbreviation,
								'utcdifference' => $z->UTCdifference);
			}			
		}

		if(!isset($zone)){
			$zone = array();
		}
		
		return $zone;
	}
	
	//BREAKOUT MONTHS
	function getBreakdownByStandardCalMonth($proposalLines, $discountpackage, $discountpackagetype, $agencydiscount){
		$removeAttrArr = array(
		'id','ssid','zone','zoneid','linetype','title','callsign','stationnum','stationname','startdate','enddate','starttime','endtime','startdatetime'
		,'enddatetime','day','desc','epititle','live','genre','premiere','isnew','stars','orgairdate','lineactive','search','showid','locked'
		,'rate','ratecardid','ratevalue','weeks','spotsweek','spots','timestamp','total','split','titleFormat','callsignFormat','dayFormat','statusFormat','sortingStartDate'
		,'sortFormat','showLine','sortingMarathons','linetype2','year','genre2','showtype','programid','projected','avail','availsDay','availsShow','statusOrder','_dirty'
		,'weekId','zonetitle','zonenetwork','titlenetworkFormat','weekdays','ratename','ncc','zonenetworktitle','networktitle','broadcastweek','tvrating','tvrating','cost'
		);
		$monthsArr = array();
		$totalSpots = 0;
		$totalAmt = 0;
		foreach($proposalLines AS $ln)
		{
			$ln 		= (array)$ln;
			$rate 		= $ln['rate'];
			$isRotator 	= ($ln['linetype']=="Rotator")?true:false;
			foreach($removeAttrArr as $attr)
			unset($ln[$attr]);
			
			foreach($ln as $wk=>$spots)
			{
				if (strpos($wk,'hide') !== false) continue; //Hidden Week, Ignore it.
				if (substr($wk,0,1)!='w') continue; //not a week.. you better stop here![ something remained to be removed.. it made it way till here..:) but no further..]
				if (strpos($wk,'days') >0) continue;
				
				$month	= substr($wk,5,2);
				$day	= substr($wk,7,2);
				$year	= substr($wk,1,4);

				$wkdate			= "$month/$day/$year";
				$brcastmonthYr  = explode('-',getBroadcastMonthYear($wkdate));
				
				if(isset($monthsArr[$year][$month]))
				{
					$monthsArr[$year][$month]['spotsmonth'] += $spots;
					$monthsArr[$year][$month]['fixed'] 		+= ($isRotator)?0:floatval($spots);
					$monthsArr[$year][$month]['rotators'] 	+= ($isRotator)?floatval($spots):0;
					$monthsArr[$year][$month]['monthtotal'] += $spots*$rate;
					$totalSpots += $spots;
					$totalAmt += $spots*$rate;
				} else
				{
					$monthsArr[$year][$month] = array(
					'monthnumber'	=> str_pad($month,2,'0',STR_PAD_LEFT)
					,'monthfull'	=> date('F',mktime(0,0,0,$month,1,$year))
					,'monthabr'		=> date('M',mktime(0,0,0,$month,1,$year))
					,'spotsmonth'	=> $spots
					,'fixed'		=> ($isRotator)?0:floatval($spots)
					,'rotators'		=> ($isRotator)?floatval($spots):0
					,'monthtotal'	=> $spots*$rate
					);
					$totalSpots += $spots;
					$totalAmt += $spots*$rate;
				}
			}
		}
		
		ksort($monthsArr);
		foreach($monthsArr AS &$y)
		{
			foreach($y as &$m)
			{
				$pkgDiscnt = 0;
				$agncyDiscnt = 0;
				if($discountpackage>0)
				{
					if($discountpackagetype==1)
					{
						$pkgDiscnt = ($discountpackage * $m['monthtotal']) / 100;
					}
					else if($discountpackagetype==2)
					{
						$PercentDiscount = ($totalAmt==0)?0:($discountpackage*100)/$totalAmt;
						$pkgDiscnt = ($PercentDiscount * $m['monthtotal']) / 100;
					}
				}
				
				if($agencydiscount)
				{
					$agncyDiscnt = (($m['monthtotal']-$pkgDiscnt)*0.15);
				}
				
				$m['discount'] = round($pkgDiscnt+$agncyDiscnt,2);
				$m['nettotal'] = round(($m['monthtotal']-$pkgDiscnt-$agncyDiscnt),2);
			}
			ksort($y);
			$y = array_values($y);
		}
		return $monthsArr;
	}
	


function getBreakdownByStandardCalendarMonth($proposalLines, $discountpackage, $discountpackagetype, $agencydiscount){
	$removeAttrArr = array('id','ssid','zone','zoneid','linetype','title','callsign','stationnum','stationname','startdate','enddate','starttime','endtime','startdatetime','enddatetime','day','desc','epititle','live','genre','premiere','isnew','stars','orgairdate',
	'lineactive','search','showid','locked','rate','ratecardid','ratevalue','weeks','spotsweek','spots','timestamp','total','split','titleFormat','callsignFormat','dayFormat','statusFormat','sortingStartDate','sortFormat','showLine','sortingMarathons','linetype2',
	'year','genre2','showtype','programid','projected','avail','availsDay','availsShow','statusOrder','_dirty','weekId','zonetitle','zonenetwork','titlenetworkFormat','weekdays','ratename','ncc','zonenetworktitle','networktitle','broadcastweek','tvrating','tvrating','cost');
	
	$monthsArr = array();
	$totalSpots = 0;
	$totalAmt = 0;


	foreach($proposalLines AS $ln){
		$ln 		= (array)$ln;
		if(array_key_exists('lineactive' , $ln))
			$islineactive = $ln['lineactive'];
		else
			$islineactive = 0;

		if($islineactive == 1){
			$rate 		= $ln['rate'];
			$isRotator 	= ($ln['linetype']=="Rotator")?true:false;
			$weekDays  	= formatWeekDays($ln['day']);
			
			//GETTING DAYS TO BE CONSIDERED IN THE CALCULATIONS
			$sdate 		= strtotime($ln['startdate']);
			$edate 		= strtotime($ln['enddate']);
			
			foreach($removeAttrArr as $attr)
				unset($ln[$attr]);

			foreach($ln as $wk=>$spots){
				
				if (strpos($wk,'hide') !== false) continue; //Hidden Week, Ignore it.
				if (substr($wk,0,1)!='w') continue; //not a week.. you better stop here![ something remained to be removed.. it made it way till here..:) but no further..]
				if (strpos($wk,'days') >0) continue;
				//if (! $wk instanceof DateTime) {continue;}

				if ( $spots != 0){
					$month	= substr($wk,5,2);
					$day	= substr($wk,7,2);
					$year	= substr($wk,1,4);
					$wkdate			= strtotime("$month/$day/$year");
					
					$partialTotals 	= array();
					$validWDays		= array();
					$dDate 			= (date('w',$wkdate)==1)?date('Y-m-d',$wkdate):date('Y-m-d',strtotime('previous monday',$wkdate));
					$rm 			= 1;			
					
					foreach($weekDays as $k){
						$thisDate = strtotime($dDate. ' + '.$k.' days');
						if ($thisDate >= $sdate && $thisDate <= $edate)
							$validWDays[] = $k;
					}
					$wkDayCount 		= count($validWDays);
					if($wkDayCount < 1) continue;
					$spotsbyWk 			= (int)($spots / $wkDayCount);
					$spotsbyWkRemaining = $spots % $wkDayCount;
		
					foreach($validWDays as $j)
						$partialTotals[$j] = $spotsbyWk;
					
					if($spotsbyWkRemaining > 0){
						while ($rm <= $spotsbyWkRemaining){
							foreach($validWDays as $m){
								$partialTotals[$m] = $partialTotals[$m] +1;
								$rm = $rm+1;
								if ($rm > $spotsbyWkRemaining)
									break;
							}
						}
					}
					
					foreach($validWDays as $vday){
						$thisDate 	= strtotime($dDate. ' + '.$vday.' days');								
						$month		= date('m',$thisDate);
						$day		= date('d',$thisDate);
						$year		= date('Y',$thisDate);	
						
						if(isset($monthsArr[$year][$month])){
							$monthsArr[$year][$month]['spotsmonth'] += $partialTotals[$vday];
							$monthsArr[$year][$month]['fixed'] 		+= ($isRotator)?0:floatval($spots);
							$monthsArr[$year][$month]['rotators'] 	+= ($isRotator)?floatval($spots):0;
							$monthsArr[$year][$month]['monthtotal'] += $partialTotals[$vday]*$rate;
							$totalSpots += $spots;
							$totalAmt 	+= $spots*$rate;
						} 
						else{
							$monthsArr[$year][$month] = array(
							'monthnumber'	=> str_pad($month,2,'0',STR_PAD_LEFT)
							,'monthfull'	=> date('F',mktime(0,0,0,$month,1,$year))
							,'monthabr'		=> date('M',mktime(0,0,0,$month,1,$year))
							,'spotsmonth'	=> $partialTotals[$vday]
							,'fixed'		=> ($isRotator)?0:floatval($partialTotals[$vday])
							,'rotators'		=> ($isRotator)?floatval($partialTotals[$vday]):0
							,'monthtotal'	=> $partialTotals[$vday]*$rate);
							$totalSpots += $partialTotals[$vday];
							$totalAmt 	+= $partialTotals[$vday]*$rate;
						}
					}
				}
			}
		}
	}	
	
	
	ksort($monthsArr);
	
	
	foreach($monthsArr AS &$y){
		foreach($y as &$m){
			$pkgDiscnt = 0;
			$agncyDiscnt = 0;
			if($discountpackage>0){
				if($discountpackagetype==1){
					$pkgDiscnt = ($discountpackage * $m['monthtotal']) / 100;
				}
				else if($discountpackagetype==2){
					$PercentDiscount = ($totalAmt==0)?0:($discountpackage*100)/$totalAmt;
					$pkgDiscnt = ($PercentDiscount * $m['monthtotal']) / 100;
				}
			}
			if($agencydiscount){
				$agncyDiscnt = (($m['monthtotal']-$pkgDiscnt)*0.15);
			}
			$m['discount'] = round($pkgDiscnt+$agncyDiscnt,2);
			$m['nettotal'] = round(($m['monthtotal']-$pkgDiscnt-$agncyDiscnt),2);
		}
		ksort($y);
		$y = array_values($y);
	}
		
	
	return $monthsArr;
}	




function formatWeekDays($wkdays){
	if(is_array($wkdays)){
		if(is_numeric($wkdays[0])){
			$wD = array();
			foreach($wkdays  as $idx){	
				$wD[] = str_replace(1,8,$idx);
			}
		}
		else{
			if($wkdays[0] == 'ms')
				$wD = array(2,3,4,5,6,7,8);
			
			if($wkdays[0] == 'mf')
				$wD = array(2,3,4,5,6);
			
			if($wkdays[0] == 'ss')
				$wD = array(7,8);
		}
	}
	else{
		$wD =	array(str_replace(1,8,$wkdays));
	}	
	
	$result = array();
	
	foreach($wD as $ii)
		$result[]= $ii-2;
		
	asort($result);
	
	return $result;
}

	function getProposalJSON($url,$appiKey, $userId){
		$ch = curl_init();
		$timeout = 5;
		
		$headers = array(
		    'Api-Key: '.$appiKey,
		    'User: '.$userId
		);
		

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);		
		curl_close($ch);

		return $data;
	}

	function getData($url){
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		$r = wddx_deserialize($data);
		if(isset($r['NAME'][0])){
			return $r['NAME'][0];			
		}
		return '';
	}


	function getData2($url,$apiKey,$user){
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => "$url",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => array("Api-Key: {$apiKey}","User: {$user}")
		));
		$response = curl_exec($curl);
		$err      = curl_error($curl);
		curl_close($curl);
		return    json_decode($response);
	}

	function date_compare($a, $b){
	    $t1 = strtotime($a[0]);
	    $t2 = strtotime($b[0]);
	    return $t1 - $t2;
	}    

		
	
	?>