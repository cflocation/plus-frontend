<?php

class Programming{
	
	protected $v;
	
	//build the basic information about the logged in user
	public function __construct(){
		date_default_timezone_set('America/Los_Angeles');
		$this->v 			= 8;
		$this->todaysdate 	=  date('Y-m-d');		
		$this->maxdate 		=  date('Y-m-d',strtotime($this->todaysdate.' +56 day'));
	}	
	
	
	public function getSchedFromSolr($fromDate,$toDate,$station,$startTimeG,$endTimeG,$zone){
		$iDate 		= $fromDate;
		$curl_arr 	= array();
		$master 	= curl_multi_init();
		$i			=	0;		
		$start		= "start_{$zone}";
		$tz_start	= "tz_start_{$zone}";
		$tz_end		= "tz_end_{$zone}";
		
		$st			= strtotime($startTimeG);
		$et			= strtotime($endTimeG);
				
		while(strtotime($toDate)>=strtotime($iDate)){
			if(strtotime($iDate) >= strtotime($this->todaysdate)){			
				$solrUrl = "http://solr.showseeker.net:8983/solr/gracenote/select/?q=*%3A*&version=2.2&start=0&indent=on&wt=json&fq=projected:0&rows=5000";
				$solrUrl = $solrUrl."&fq=tz_start_{$zone}:[{$iDate}T00:00:00Z%20TO%20{$iDate}T23:59:59Z]";
				$solrUrl = $solrUrl."&fq=start_{$zone}:[00:00:00%20TO%2023:59:59]";
				$solrUrl = $solrUrl."&fq=stationnum:{$station}";
				$solrUrl = $solrUrl."+&fl=id,epititle,genre1,genre2,descembed,stationnum,callsign,stars,showid,isnew,tmsid,stationnum,title,new,live,";
				$solrUrl = $solrUrl."duration,premierefinale,zone,orgairdate,tz_start_{$zone},start_{$zone},tz_end_{$zone}&sort=tz_start_pst asc";
				$solrUrl = preg_replace("/ /", "%20",$solrUrl);
			}
			else{
				$solrUrl = "http://solr.showseeker.net:8983/solr/gracenote/select/?q=*%3A*&version=2.2&start=0&indent=on&wt=json&fq=projected:0&rows=1&fq=stationnum:0&fl=id";
			}
			
			$curl_arr[$i] = curl_init($solrUrl); //
			curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, true);//
			curl_multi_add_handle($master, $curl_arr[$i]); //
			
			$dateArr[$i] = $iDate;
			$iDate = date('Y-m-d',strtotime($iDate.' +1 day'));
			$i++;
		}
		
		do {
			curl_multi_exec($master,$running);
		} while($running > 0);
		
		$returnArr 	= array();
		$threshold	= array();
		
		for($i = 0; $i < 7; $i++){
			
			$returnArr[$i] = array();
			$results = curl_multi_getcontent  ( $curl_arr[$i]  );
			$dataArr = json_decode($results)->response->docs;
			
			if(count($threshold) > 0){
				array_unshift ( $dataArr , (Object) $threshold );
				unset($threshold);
				$threshold = array();
			}
			
			foreach($dataArr as $sh){
				
				$etime			= trim($sh->$tz_end,"Z");
				$timeEndVal		= substr($etime, strrpos($etime, "T") + 1);
				$sdt				= substr($sh->$tz_start,0,10);
				$edt				= substr($sh->$tz_end,0,10);


				if((strtotime($sh->$start)>= $st || strtotime($timeEndVal) > $st) && strtotime($sh->$start)<$et){
				
					$dayStartArr	= explode('T',$sh->$tz_start);
					$dayStartVal	= $dayStartArr[0];
					
					$timeStartArr	= explode('Z',$dayStartArr[1]);
					$timeStartVal	= $timeStartArr[0];

					$dayEndArr		= explode('T',$sh->$tz_end);
					$dayEndVal		= $dayEndArr[0];

					$timeEndArr		= explode('Z',$dayEndArr[1]);
					$timeEndVal		= $timeEndArr[0];

					if(strtotime($timeStartVal)<$st)
						$timeStartVal = date('H:i:s',$st);

					if(strtotime($timeEndVal) > $et || strtotime($timeEndVal)<$st)
						$timeEndVal = date('H:i:s',$et);


					if( $sdt != $edt ){
						
						//$threshold	= $sh;

			            $threshold = array(
								            'showid' => $sh->showid
								            ,'premierefinale' => $sh->premierefinale
								            ,'callsign' => $sh->callsign
								            ,$start => '00:00:00'
								            ,'genre1' => $sh->genre1
								            ,'epititle' => $sh->epititle
								            ,'orgairdate' => $sh->orgairdate
								            ,'isnew' => $sh->isnew
								            ,'genre2' => $sh->genre2
								            ,'live' => $sh->live
								            ,'genre1' => $sh->genre1
								            ,'descembed' => $sh->descembed
								            ,'stars' => $sh->stars
								            ,$tz_start => date('Y-m-d',strtotime($dayEndVal)).'T00:00:00Z'
								            ,$tz_end => $sh->$tz_end
								            ,'id' => $sh->id
								            ,'title' => $sh->title
								            ,'tmsid' => $sh->tmsid
								            ,'stationnum' => $sh->stationnum
								            ,'new' => $sh->new
								            ,'duration' => (strtotime($sh->$tz_end)-strtotime(date('Y-m-d',strtotime($dayEndVal)).'T00:00:00Z'))/60
								            );
					}
					
					
					$zoneStartTime	= date('Y-m-d H:i:s',strtotime($dayStartVal.$timeStartVal));
					$zoneEndTime	= date('Y-m-d H:i:s',strtotime($dayEndVal.$timeEndVal));
					
					$startTm		= date('H:i:s', strtotime($sh->$start));
					
					$splitTimeStartArr=explode(":",$timeStartVal);
					$hs				= $splitTimeStartArr[0];
					$ms				= $splitTimeStartArr[1];
					$ss				= $splitTimeStartArr[2];
	
					if($ms!="00"&&$ms!="30"&&$ms!="15"&&$ms!="45"){
						if(($ms>=0&&$ms<=6)){
							$ms="00";
						}elseif(($ms>=7&&$ms<=21)){
							$ms="15";
						}elseif(($ms>=22&&$ms<=36)){
							$ms="30";
						}elseif(($ms>=37&&$ms<=51)){
							$ms="45";
						}elseif(($ms>=52&&$ms<=59)){
							$ms="00";
							if($hs==23){
								$hs="00";
								$s_date = new DateTime($dayStartVal);
								$s_date->modify('+1 day');
								$dayStartVal = $s_date->format('Y-m-d');
							}else{
								$hs=$hs+1;
							}
							
						}
					}
	
					$timeStartVal=$hs.":".$ms.":".$ss;
					
					
					$splitTimeEndArr=explode(":",$timeEndVal);
					
					$he=$splitTimeEndArr[0];
					$me=$splitTimeEndArr[1];
					$se=$splitTimeEndArr[2];
					
					if($me!="00"&&$me!="30"&&$me!="15"&&$me!="45"){
					
						if(($me>=0&&$me<=6)){
							$me="00";
						}elseif(($me>=7&&$me<=21)){
							$me="15";
						}elseif(($me>=22&&$me<=36)){
							$me="30";
						}elseif(($me>=37&&$me<=51)){
							$me="45";
						}elseif(($me>=52&&$me<=59)){
							$me="00";
							if($he==23)
							{
								$he="00";
							}else{
								$he=$he+1;
							}
							
						}
					}

					$timeEndVal=$he.":".$me.":".$se;
					
					$startTimeApprox= date('Y-m-d H:i:s',strtotime($dayStartVal.$timeStartVal));
					$endTimeApprox	= date('Y-m-d H:i:s',strtotime($dayEndVal.$timeEndVal));
					$duration=(strtotime($endTimeApprox)-strtotime($startTimeApprox))/60;					
	
					$returnArr[$i][] = array(
							'id'			=> $sh->id
							,'premierefinale'=> $sh->premierefinale
							,'isnew'		=> $sh->isnew
							,'title'		=> substr($sh->title,0,30)
							,'epititle'		=> substr($sh->epititle,0,30)
							,'genre2'		=> $sh->genre2
							,'genre1'		=> $sh->genre1
							,$start			=> $startTm
							,$tz_start		=> $startTimeApprox	
							,$tz_end		=> $endTimeApprox
							,'duration'		=> $duration
							,'class'		=> str_replace(" ","",$sh->live.$sh->genre1).' '.$sh->isnew
							,'live'			=> $sh->live
							,'orgairdate'	=> $sh->orgairdate
							,'descembed'	=> $sh->descembed
						);		
		
				}
			}
		}

		return $returnArr;
	}	
	
	
	
	
	
	public function programtitle($i,$k,$m,$PREMIERE,$NEW,$DESCRIPTION,$EPISODE,$PROGRAM,$GENRE,$LIVE,$x,$y,$STATIONID,$KEY){			
		
		if(strlen($PREMIERE) <= 1 && $NEW != "New" && $LIVE != "Live" && (strlen($DESCRIPTION) > 1 || strlen($EPISODE) > 1)){
			return 	"<a href=javascript:displayInfo('".$i.$k.$m."','$KEY','$NEW','$LIVE','black',$x,$y)>$PROGRAM</a><div class=programDesc>$DESCRIPTION</div>";
		
		}elseif (strlen($PREMIERE) > 1 && (strlen($DESCRIPTION) > 1 || strlen($EPISODE) > 1)){
			return 	"<a href=javascript:displayInfo('".$i.$k.$m."','$KEY','$NEW','$LIVE','red',$x,$y) style=color:red;   font-weight:700;><b>$PROGRAM</b></a>";
		
		}elseif (strlen($PREMIERE) <= 1 && $LIVE == "Live" && (strlen($DESCRIPTION) > 1 || strlen($EPISODE) > 1)){
			if($GENRE == 'sports event')
				return 	"<a href=javascript:displayInfo('".$i.$k.$m."','$KEY','$NEW','$LIVE','#5801AF',$x,$y) style=color:#5801AF; font-weight:700;><b>$PROGRAM <em>($LIVE)</em><BR><BR><font style=color:#666666; font-weight:300;>$EPISODE</font></b></a>";	
			else
				return 	"<a href=javascript:displayInfo('".$i.$k.$m."','$KEY','$NEW','$LIVE','#000000',$x,$y) style=color:#000000; font-weight:700;><b>$PROGRAM <font color=#5801AF><em>($LIVE)</em></font></b></a>";
		}elseif(strlen($PREMIERE) <= 1 && $LIVE == "Live"){
			
			if ($GENRE != 'sports event')
				return 	"<font style=color:#5801AF;>$PROGRAM <em>($LIVE)</em></font>";
			else
				return 	"<font style=color:#000000;>$PROGRAM <font color=#5801AF><em>($LIVE)</em></font></font>";
			
		}elseif(strlen($PREMIERE) <= 1 && $NEW == "New" && (strlen($DESCRIPTION) > 1 || strlen($EPISODE) > 1)){
			return 	"<a href=javascript:displayInfo('".$i.$k.$m."','$KEY','$NEW','$LIVE','green',$x,$y) style=color:green; font-weight:700><b>$PROGRAM</b></a>";
		
		}elseif (strlen($PREMIERE) > 1){
			return "<font color=red>$PROGRAM</font>";
		
		}elseif ($NEW == "New"){
			return "<font color=green>$PROGRAM</font>";
		
		}elseif ($LIVE == "Live"){
			return "<font color=#000000>$PROGRAM <font color=#5801AF><em>($LIVE)</em></font></font>";
		
		}else{
			return $PROGRAM;
		}	
	}	
	
}
