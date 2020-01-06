<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
		
		$iiDate 		= substr(date('Y-m-d',strtotime($fromDate.' -1 day')), 0,10);			
		$iDate 		= substr($fromDate, 0,10);
		$curl_arr 	= array();
		$master 		= curl_multi_init();
		$i				=	0;		
		$start		= "start_{$zone}";
		$tz_start	= "tz_start_{$zone}";
		$tz_end		= "tz_end_{$zone}";
		$st			= strtotime($startTimeG);
		$et			= strtotime($endTimeG);
		
		while(strtotime($toDate)>=strtotime($iDate)){
			
			$solrUrl = "http://snapshot.prod.showseeker.com:8983/solr/snapshot/select/?q=*%3A*&version=2.2&start=0&indent=on&indent=on&wt=json&rows=5000";		
			$solrUrl = $solrUrl."&fq=(tz_start_{$zone}:[{$iDate}T00:00:00Z%20TO%20{$iDate}T23:59:00Z] OR ";
			$solrUrl = $solrUrl."tz_end_{$zone}:[{$iDate}T00:00:00Z%20TO%20{$iDate}T23:59:00Z])";			
			$solrUrl = $solrUrl."&fq=start_{$zone}:[00:00:00%20TO%2023:59:00]";
			$solrUrl = $solrUrl."&fq=stationnum:{$station}";
			$solrUrl = $solrUrl."+&fl=id,epititle,genre1,genre2,descembed,stationnum,callsign,stars,showid,isnew,tmsid,stationnum,title,new,live,";
			$solrUrl = $solrUrl."duration,premierefinale,zone,orgairdate,tz_start_{$zone},start_{$zone},tz_end_{$zone}&sort=tz_start_{$zone} asc";
			$solrUrl = preg_replace("/ /", "%20",$solrUrl);					

			$curl_arr[$i] = curl_init($solrUrl); 
			curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, true);
			curl_multi_add_handle($master, $curl_arr[$i]);
			$dateArr[$i] = $iDate;
			$iDate = date('Y-m-d',strtotime($iDate.' +1 day'));
			$i++;
		}
		
		do{
			curl_multi_exec($master,$running);
		}while($running > 0);
		
		$returnArr 	= array();
		$threshold	= array();
		
		
		for($i = 0; $i < 7; $i++){


			$results 				= curl_multi_getcontent  ( $curl_arr[$i]  );

			if(json_decode($results)->responseHeader->status ===400){
				continue;
			}
			$refStart 				= strtotime($dateArr[$i].' '.$startTimeG);
			$refEnd 					= strtotime($dateArr[$i].' '.$endTimeG);
			$returnArr[$i] 		= array();			
			$dataArr 				= json_decode($results)->response->docs;		
			$d 						= 0;


			foreach($dataArr as $sh){		

				$fixSTime			= date('H:i:s', strtotime($sh->$start));
				$etime				= trim($sh->$tz_end,"Z");
				$timeEndVal			= substr($etime, strrpos($etime, "T") + 1);
				$sdt					= substr($sh->$tz_start,0,10);
				$edt					= substr($sh->$tz_end,0,10);
				$dayStartArr		= explode('T',$sh->$tz_start);
				$dayStartVal		= $dayStartArr[0];
				$timeStartArr		= explode('Z',$dayStartArr[1]);
				$timeStartVal		= $timeStartArr[0];
				$dayEndArr			= explode('T',$sh->$tz_end);
				$dayEndVal			= $dayEndArr[0];
				$timeEndArr			= explode('Z',$dayEndArr[1]);
				$timeEndVal			= $timeEndArr[0];
				$thisStartTime		= strtotime($dayStartVal.' '.$timeStartVal);
				$thisEndTime		= strtotime($dayEndVal.' '.$timeEndVal);	
				
				if(($thisStartTime >= $refStart && $thisStartTime < $refEnd) || ( $thisEndTime > $refStart && $thisEndTime <= $refEnd ) ){

					if(strtotime($timeStartVal)<$st){
						$timeStartVal 	= date('H:i:s',$st);
					}

					if(strtotime($timeEndVal) > $et || strtotime($timeEndVal)<$st){
						$timeEndVal 	= date('H:i:s',$et);
					}
					

					if(strtotime($sdt)<strtotime($edt) && count($returnArr[$i]) == 0){
						$sh->$start 	= '00:00:00';
						$timeStartVal	= '00:00:00';
						$dayStartVal	= date('Y-m-d',strtotime($dayEndVal));							
						$sh->duration 	= (strtotime($dayStartVal.' '.$timeStartVal) - strtotime($dayEndVal.' '.$timeEndVal))/60;
					}
					else if($sdt!= $edt){
						$tzvar 			= 'tz_end_'.$zone;
						$sh->$tzvar 	= $dayStartVal.'T23:59:00Z';
						$timeEndVal		= '23:59:00';
						$dayEndVal		= date('Y-m-d',strtotime($dayStartVal));	
						$sh->duration = (strtotime($dayEndVal.' '.$timeEndVal) - strtotime($dayStartVal.' '.$timeStartVal))/60;
					}					
					
					$zoneStartTime		= date('Y-m-d H:i:s',strtotime($dayStartVal.$timeStartVal));
					$zoneEndTime		= date('Y-m-d H:i:s',strtotime($dayEndVal.$timeEndVal));
					$startTm				= date('H:i:s', strtotime($sh->$start));
					$splitTimeStartArr= explode(":",$timeStartVal);
					$hs					= $splitTimeStartArr[0];
					$ms					= $splitTimeStartArr[1];
					$ss					= $splitTimeStartArr[2];
	
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
								$dayStartVal = date('Y-m-d', strtotime($dayStartVal. ' + 1 days'));	
								continue;
							}
							else{
								$hs=$hs+1;
							}
							
						}
					}
	
					$timeStartVal	= $hs.":".$ms.":".$ss;
					$splitTimeEndArr= explode(":",$timeEndVal);
					$he				= $splitTimeEndArr[0];
					$me				= $splitTimeEndArr[1];
					$se				= $splitTimeEndArr[2];
					
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
							$me="59";
						}
					}

					$timeEndVal			= $he.":".$me.":".$se;
					$startTimeApprox	= date('Y-m-d H:i:s',strtotime($dayStartVal.$timeStartVal));
					$endTimeApprox		= date('Y-m-d H:i:s',strtotime($dayEndVal.$timeEndVal));
					$duration			= (strtotime($endTimeApprox)-strtotime($startTimeApprox))/60;					
					$thisShowId 		= cleanStr($sh->id);
					
					if($duration > 0){
						$returnArr[$i][] = array(
								'id'					=> $thisShowId
								,'premierefinale'	=> (array_key_exists('premierefinale',$sh))?$sh->premierefinale:''
								,'isnew'				=> (array_key_exists('isnew',$sh))?$sh->isnew:''
								,'title'				=> substr($sh->title,0,30)
								,'epititle'			=> (array_key_exists('epititle',$sh))?substr($sh->epititle,0,30):''
								,'genre2'			=> (array_key_exists('genre2',$sh))?$sh->genre2:''
								,'genre1'			=> (array_key_exists('genre1',$sh))?$sh->genre1:''
								,$start				=> $startTm
								,$tz_start			=> $startTimeApprox	
								,$tz_end				=> $endTimeApprox
								,'duration'			=> $duration
								,'class'				=> (array_key_exists('live',$sh)&&array_key_exists('genre1',$sh)&&array_key_exists('isnew',$sh))?str_replace(" ","",$sh->live.$sh->genre1).' '.$sh->isnew:''
								,'live'				=> (array_key_exists('live',$sh))?$sh->live:''
								,'orgairdate'		=> (array_key_exists('orgairdate',$sh))?$sh->orgairdate:''
								,'descembed'		=> (array_key_exists('descembed',$sh))?$sh->descembed:''
							);
					}
		
				}
				$d++;
			}
		}
		
		/*print_r('<pre>');
		print_r($returnArr);
		exit;*/
		return $returnArr;
	}	
	

	
	public function programtitle($i,$k,$m,$PREMIERE,$NEW,$DESCRIPTION,$EPISODE,$PROGRAM,$GENRE,$LIVE,$x,$y,$STATIONID,$KEY){			
		
		if(strlen($PREMIERE) <= 1 && $NEW != "New" && $LIVE != "Live" && (strlen($DESCRIPTION) > 1 || strlen($EPISODE) > 1)){
			return 	"<a href=javascript:displayInfo('".$i.'-'.$k.$m."','$KEY','$NEW','$LIVE','black',$x,$y)>$PROGRAM</a><div class=programDesc>$DESCRIPTION</div>";
		
		}elseif (strlen($PREMIERE) > 1 && (strlen($DESCRIPTION) > 1 || strlen($EPISODE) > 1)){
			return 	"<a href=javascript:displayInfo('".$i.'-'.$k.$m."','$KEY','$NEW','$LIVE','red',$x,$y) style=color:red;   font-weight:700;><b>$PROGRAM</b></a>";
		
		}elseif (strlen($PREMIERE) <= 1 && $LIVE == "Live" && (strlen($DESCRIPTION) > 1 || strlen($EPISODE) > 1)){
			if($GENRE == 'sports event')
				return 	"<a href=javascript:displayInfo('".$i.'-'.$k.$m."','$KEY','$NEW','$LIVE','#5801AF',$x,$y) style=color:#5801AF; font-weight:700;><b>$PROGRAM <em>($LIVE)</em><BR><BR><font style=color:#666666; font-weight:300;>$EPISODE</font></b></a>";	
			else
				return 	"<a href=javascript:displayInfo('".$i.'-'.$k.$m."','$KEY','$NEW','$LIVE','#000000',$x,$y) style=color:#000000; font-weight:700;><b>$PROGRAM <font color=#5801AF><em>($LIVE)</em></font></b></a>";
		}elseif(strlen($PREMIERE) <= 1 && $LIVE == "Live"){
			
			if ($GENRE != 'sports event')
				return 	"<font style=color:#5801AF;>$PROGRAM <em>($LIVE)</em></font>";
			else
				return 	"<font style=color:#000000;>$PROGRAM <font color=#5801AF><em>($LIVE)</em></font></font>";
			
		}elseif(strlen($PREMIERE) <= 1 && $NEW == "New" && (strlen($DESCRIPTION) > 1 || strlen($EPISODE) > 1)){
			return 	"<a href=javascript:displayInfo('".$i.'-'.$k.$m."','$KEY','$NEW','$LIVE','green',$x,$y) style=color:green; font-weight:700><b>$PROGRAM</b></a>";
		
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
	function cleanStr($string){
		$newStr = $string;
		if( preg_match('/\s/',$string) ){
			$brokenId = explode(" ", $string);
			if(!is_numeric(substr($brokenId[0],0,1))){
				$newStr = substr($brokenId[0],14,5).substr($brokenId[0],0,14).substr(preg_replace('/[^A-Za-z0-9\-]/', '', $brokenId[1]),0,4).preg_replace('/-/', '',substr($brokenId[0],19,10));
			}
		}
		
	   $newStr = str_replace(' ', '-', $newStr); // Replaces all spaces with hyphens.
   	return preg_replace('/[^A-Za-z0-9\-]/', '', $newStr); // Removes special chars.
	}