<?php 
	require_once 'commons/initialize.php';
	define("XML_FOLDER","xmls/");
	
	ini_set("display_errors", 'off');
	//error_reporting(E_ALL);
	
	if(isset($_GET['proposalid'])){		
		$hiderate		= urldecode(trim($_GET['hiderates']));
		$includelogos	= urldecode(trim($_GET['logos']));
		$includedesc	= urldecode(trim($_GET['description']));
		$includenew		= urldecode(trim($_GET['includenew']));
		$includetc		= urldecode(trim($_GET['addterms']));
		$showratecard	= urldecode(trim($_GET['showratecard']));
		$proposalid		= trim(urldecode($_GET['proposalid']));
		$userid			= trim(urldecode($_GET['userid']));
		$tokenid		= trim(urldecode($_GET['tokenid']));
		$headerid		= trim(urldecode($_GET['headerid']));
		$onlyfixed		= (isset($_GET['onlyfixed']) && urldecode(trim($_GET['onlyfixed']))=='true')?true:false;
		$sort1			= (isset($_GET['sort1']) && $_GET['sort1']!='default')?trim(urldecode($_GET['sort1'])):'startdate';
		$sort2			= (isset($_GET['sort2']) && $_GET['sort2']!='default')?trim(urldecode($_GET['sort2'])):'starttime';
		$sort3			= (isset($_GET['sort3']) && $_GET['sort3']!='default')?trim(urldecode($_GET['sort3'])):'network';		
		
		//GET THE PROPOSAL
		$curl = curl_init();
		
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://plusapi.showseeker.com/proposal/json/{$proposalid}",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_POSTFIELDS => "",
			CURLOPT_HTTPHEADER => array(
									"api-key: {$tokenid}",
									"cache-control: no-cache",
									"user: {$userid}"
									),
		));

		$response = curl_exec($curl);
		$err      = curl_error($curl);

		curl_close($curl);
		$row      = json_decode($response);

		if(!$row){
			echo json_encode(array('Invalid proposalid or userid or tokenid'));
			exit;
		}
				
		//USER INFO
		$user 		= $row->userInfo;
		
		//PROPOSAL LINES
		$proposal 	= $row->lines;
		

		foreach($proposal AS $k=>$value){
			if($onlyfixed){
				if($value->linetype!="Fixed"){
					unset($proposal[$k]);
				}
			}
			else{
				$proposal[$k]->desc60 = '';	
				
				if($value->linetype=="Fixed"){
					$desc60 = programdescription($proposal[$k]->ssid);
					if(count($desc60)>0){
						$proposal[$k]->desc60 = $desc60[0]->reduceddesc;
					}
				}
			}
		}

		$proposal 	= array_values($proposal);
		
		//PROPOSAL INFO
		$proposalDateArr 		= getProposalStartEndDate($proposal);
		$pslname 				= getProposalName(urldecode($row->name),$headerid);		
		$proposalInfo 			= array('id'=>$row->id,
										'name'=>utf8_encode($pslname),
										'startdate'=>$proposalDateArr['startdate'],
										'enddate'=>$proposalDateArr['enddate'],
										'createdate'=>$row->createdAt,
										'updatedate'=>$row->updatedAt);

		//DISCOUNT
		$discountpackagetype  	= ($row->discountType=='2')?'$':'percent';
		$agencydiscount		  	= ($row->agencyDiscount=='1')?true:false;
		$discount			  	= array("discount"=>$row->discount, "type" => $discountpackagetype, "agency" => $agencydiscount);
		
		//ZONES
		$zones 					= $row->zones;
		
		//NETWORK LOGOGS
		$networks 				= $row->networks;
		
		//WEEKS IN PROPOSAL
		$weeksTotal 			= getBreakdownByWeeks($proposal);
		
		//BREAKOUT MONTHS
		$brodmonthstotal 		= getBreakdownByMonth($proposal, $row->discount, $row->discountType, $agencydiscount);
		$calmonthstotal 		= getBreakdownByStandardCalendarMonth($proposal, $row->discount, $row->discountType, $agencydiscount);

		//TOTALS
		$totals 				= getTotals($proposal, $row->discount, $row->discountType, $agencydiscount,$row->totals->spot);		
		$proposal 				= getProposalsByZone($proposal,$row->discount,$row->discountType,$agencydiscount,$sort1,$sort2,$sort3,$zones);
		
		$re 					= array("proposalinfo"=>$proposalInfo,
										"user"=>$user,
										"networks"=>$networks,
										"weeks"=>$weeksTotal,
										"brodmonthstotal"=>$brodmonthstotal,
										"calmonthstotal"=>$calmonthstotal,
										"discount"=>$discount,
										"totals"=>$totals,
										"proposal"=>$proposal,
										"zones"=>$zones);
		$json_data 				=  json_encode($re);
	}

	function getProposalsByZone($proposal, $discountpackage, $discountpackagetype, $agencydiscount, $sort1,$sort2,$sort3,$zones){
	
		$return = array();
	
		foreach($proposal as $ln){
		
			$zoneid = intval($ln->zoneid);
		
			if(!isset($return[$zoneid])){
				$return[$zoneid] 			= array();
				$return[$zoneid]['zone']	= getZoneInfo($zoneid,$zones);
				$exit;
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

	//Zones
	function getZoneInfo($zoneid,$zones){
		$zns = array();
		foreach($zones as $value){
			if($value->id == $zoneid){
               $zns = array("zoneid"=> $value->id,
			                "zonename" => $value->name,
			                "isdma" => $value->isDMA,
			                "dmaid" => $value->dmaId,
			                "syscode" => $value->sysCode,
			                "timezoneid" => $value->timeZoneId,
			                "timezonename" => $value->timezoneName,
			                "abbreviation" => $value->abbreviation,
			                "utcdifference" => $value->UTCdifference);
			}
		}
		return $zns;
	}

	function getZoneInfo_OLD($zoneId){
		global $db;
		$zones 		= array();
		$sql 		= 	"SELECT 	z.id as zoneid, 
									z.name AS zonename, 
									z.isdma, 
									z.dmaid, 
									z.syscode, 
									z.timezoneid, 
									tz.name AS timezonename, 
									tz.abbreviation, 
									tz.utcdifference
						FROM 		zones AS z
						INNER JOIN 	timezones AS tz 
						ON 			tz.id=z.timezoneid 
						WHERE 		z.id = $zoneId ";
						
		$result 	= $db->fetch_result($sql);
		
		return $result[0];
	}

	
	
	//NETWORK LOGOS
	function getNetworks($data,$db){
		$stations = array();
		
		foreach ($data as &$value){
			array_push($stations, $value->stationnum);
		}
	
		$stations 	= array_unique($stations);
		$ids 		= join(',',$stations);
	
		$sql 		= "	SELECT 		networklogos.networkid AS stationnum, 
									tms_networks.callsign,
									logos.filename AS 100x100, 
									CONCAT('http://ww2.showseeker.com/images/_thumbnails/',logos.filename) AS 40x40
						FROM 		networklogos
						INNER JOIN 	logos 
						ON 			logos.id = networklogos.logoid
						INNER JOIN 	tms_networks 
						ON 			networklogos.networkid = tms_networks.networkid
						WHERE 		networklogos.networkid IN ($ids)";
						
		$result = $db->fetch_result($sql);
		return $result;
	};


	//BREAKOUT MONTHS
	function getBreakdownByMonth($proposalLines, $discountpackage, $discountpackagetype, $agencydiscount){
		$removeAttrArr = array(
		'id','ssid','zone','zoneid','linetype','title','callsign','stationnum','stationname','startdate','enddate','starttime','endtime','startdatetime'
		,'enddatetime','day','desc','epititle','live','genre','premiere','isnew','stars','orgairdate','lineactive','search','showid','locked'
		,'rate','ratecardid','ratevalue','weeks','spotsweek','spots','timestamp','total','split','titleFormat','callsignFormat','dayFormat','statusFormat','sortingStartDate'
		,'sortFormat','showLine','sortingMarathons','linetype2','year','genre2','showtype','programid','projected','avail','availsDay','availsShow','statusOrder','_dirty'
		,'weekId','zonetitle','zonenetwork','titlenetworkFormat','weekdays','ratename','ncc','zonenetworktitle','networktitle','broadcastweek','tvrating','tvrating','cost'
		);
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
				if (strpos($wk,'_days') !==false) continue;				
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
					//$totalSpots += $spots;
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
						$PercentDiscount= ($totalAmt==0)?0:($discountpackage*100)/$totalAmt;
						$pkgDiscnt 		= ($PercentDiscount * $m['monthtotal']) / 100;
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
		,'weekId','zonetitle','zonenetwork','titlenetworkFormat','weekdays','ratename','ncc','zonenetworktitle','networktitle','broadcastweek','tvrating','tvrating','cost'
		);
		
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
					//$totalSpots += $spots;
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
					//$totalSpots += $spots;
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

	$pkgDiscnt 		= 0;
	$agncyDiscnt 	= 0;
	
	if($discountpackage>0){
		if($discountpackagetype==1)
			$pkgDiscnt = ($discountpackage * $grossTotal) / 100;
		else if($discountpackagetype==2)
			$pkgDiscnt = $discountpackage;
	}
	
	if($agencydiscount){
		$agncyDiscnt = (($grossTotal-$pkgDiscnt)*0.15);
	}
	
	$netTotal = $grossTotal-$pkgDiscnt-$agncyDiscnt;
	
	return array('gross'=>round($grossTotal,2)
				  ,'packagediscount'=>round($pkgDiscnt,2)
				  ,'agencydiscount'=>round($agncyDiscnt,2)
				  ,'net'=>round($netTotal,2)
				  ,'spots'=>$totalspots
				  ,'rotators'=>$totalRotators
				  ,'fixed'=>$totalFixed);
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
		foreach($removeAttrArr as $attr)
		unset($ln[$attr]);

		foreach($ln as $wk=>$spots){
			if (strpos($wk,'hide') !== false) continue; //Hidden Week, Ignore it.
			if (substr($wk,0,1)!='w') continue; //not a week.. you better stop here![ something remained to be removed.. it made its way till here..:) but no further..]
			if (strpos($wk,'_days') !==false) continue;
						
			$month	= substr($wk,1,2);
			$day	= substr($wk,3,2);
			$year	= substr($wk,5,4);
			$index 	= "$year$month$day";
			$week 	= "$month/$day/$year";
			
			if(isset($weeksArr[$index])){
				$weeksArr[$index]['spots'] 		+=  floatval($spots);
				$weeksArr[$index]['total'] 		+=  round((floatval($spots)*$rate),2);
				$weeksArr[$index]['rotators'] 	+=  ($isRotator)?floatval($spots):0;
				$weeksArr[$index]['fixed'] 		+=  ($isRotator)?0:floatval($spots);
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
		$tstime 		= $ln->starttime;
		$tetime 		= $ln->endtime;			
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
	
	foreach($proposalLines as &$ln)
		unset($ln->sortkey);
	
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
	$dtstmp 		= strtotime($date);
	$monthName 		= date('F',mktime(0,0,0,substr($date,0,2),1,substr($date,-4)));
	$lastSunStmp 	= strtotime("last Sunday of $monthName ".substr($date,-4));
	
	if($dtstmp>$lastSunStmp)
		return date('m-Y',mktime(0,0,0,intval(substr($date,0,2))+1,1,substr($date,-4)));
	else
		return date('m-Y',mktime(0,0,0,intval(substr($date,0,2)),1,substr($date,-4)));
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
	foreach($proposalLines AS $ln){
		$ln 		= (array)$ln;
		$rate 		= $ln['rate'];
		$isRotator 	= ($ln['linetype']=="Rotator")?true:false;
		foreach($removeAttrArr as $attr)
		unset($ln[$attr]);
		
		foreach($ln as $wk=>$spots){
			
			if (strpos($wk,'hide') !== false) continue; //Hidden Week, Ignore it.
			if (substr($wk,0,1)!='w') continue; //not a week.. you better stop here![ something remained to be removed.. it made it way till here..:) but no further..]
			if (strpos($wk,'_days') !==false) continue;						
			$month	= substr($wk,5,2);
			$day	= substr($wk,7,2);
			$year	= substr($wk,1,4);
							
			$wkdate			= "$month/$day/$year";
			$brcastmonthYr  = explode('-',getBroadcastMonthYear($wkdate));
			
			if(isset($monthsArr[$year][$month])){
				$monthsArr[$year][$month]['spotsmonth'] += $spots;
				$monthsArr[$year][$month]['fixed'] 		+= ($isRotator)?0:floatval($spots);
				$monthsArr[$year][$month]['rotators'] 	+= ($isRotator)?floatval($spots):0;
				$monthsArr[$year][$month]['monthtotal'] += $spots*$rate;
				$totalSpots += $spots;
				$totalAmt += $spots*$rate;
			} 
			else{
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
				$agncyDiscnt 	= (($m['monthtotal']-$pkgDiscnt)*0.15);
			}
			
			$m['discount'] 		= round($pkgDiscnt+$agncyDiscnt,2);
			$m['nettotal'] 		= round(($m['monthtotal']-$pkgDiscnt-$agncyDiscnt),2);
		}
		ksort($y);
		$y = array_values($y);
	}
	return $monthsArr;
}
	


function getBreakdownByStandardCalendarMonth($proposalLines, $discountpackage, $discountpackagetype, $agencydiscount){
	$removeAttrArr = array('id','ssid','zone','zoneid','linetype','title','callsign','stationnum',
	'stationname','startdate','enddate','starttime','endtime','startdatetime','enddatetime',
	'day','desc','epititle','live','genre','premiere','isnew','stars','orgairdate',
	'lineactive','search','showid','locked','rate','ratecardid','ratevalue','weeks',
	'spotsweek','spots','timestamp','total','split','titleFormat','callsignFormat',
	'dayFormat','statusFormat','sortingStartDate','sortFormat','showLine','sortingMarathons',
	'linetype2','year','genre2','showtype','programid','projected','avail','availsDay',
	'availsShow','statusOrder','_dirty','weekId','zonetitle','zonenetwork','titlenetworkFormat',
	'weekdays','ratename','ncc','zonenetworktitle','networktitle','broadcastweek','tvrating','tvrating','cost');
	
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
				if (strpos($wk,'_days') !==false) continue;				
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
		
					if($wkDayCount > 0){
						$spotsbyWk 			= (int)($spots / $wkDayCount);
						$spotsbyWkRemaining = $spots % $wkDayCount;
					}
					else{
						$spotsbyWk 			= 1;
						$spotsbyWkRemaining = 0;
					}
					

		
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
		
		
function programdescription($programid){

		$url = "http://solr.showseeker.net:8983/solr/gracenote/select/?q=*:*&indent=true&wt=json&fq=id:$programid&fl=desc60,reduceddesc";
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_POSTFIELDS => ""
		));

		$response = curl_exec($curl);
		$err      = curl_error($curl);
		
		curl_close($curl);
		$row      = json_decode($response);
		
		return $row->response->docs;
}
		
		
	
	?>
