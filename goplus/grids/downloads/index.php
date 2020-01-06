<?php
	//error_reporting(E_ALL);
	ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);	
	ini_set('max_execution_time', 4800);
	
	require_once('../../../inc/s3/upload.php');
	require_once('../../../inc/tcpdf/tcpdf_config.php');
	require_once('../../../inc/tcpdf/tcpdf.php');

	$filetype 		= 'pdf';
	$year 			= date("Y");
	$day 			= date('z');
	$thisweek  		= date("d m Y",strtotime('monday this week'));
	$station		= $_GET['id'];
	$zid			= $_GET['zid'];
	$tz				= $_GET['tz'];
	$callsign		= $_GET['callsign'];
	$filename 		= $callsign.'.pdf';
	$zone 			= $tz;						
	$bucket 		= strtolower(getcwd()."/temp/$filename");
	$nextWeek		= date('Y-m-d',	strtotime($_GET['sd']));
	$startTimeG		= date('H:i:s', strtotime($_GET['st']));
	$endTimeG		= date('H:i:s', strtotime($_GET['et']));
	$option			= 1;
	$v				= 1;
	
	
	//setup teh pdf to start the processing
	$pdf = new TCPDF('P', 'mm', 'Letter', true, 'UTF-8', false);
	$pdf->SetFont('', '', 5, '', true);
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	$pdf->SetAutoPageBreak(TRUE, 0);
	$pdf->SetLineStyle(array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(204, 204, 204)));
	$pdf->SetFillColor(197, 225, 252);
	
	
	//loop over the pages for the PDF
	for($i = 0; $i < $v; $i++){
		$fromDate 	= (date('w',strtotime($nextWeek))==1)?$nextWeek:date('Y-m-d',strtotime('previous monday',strtotime($nextWeek)));
		$toDate   	= date('Y-m-d',strtotime($fromDate.' next sunday'));
		$nextWeek 	= date('Y-m-d', strtotime($toDate . ' + 1 day'));
		$stop	  	= 0;
		
		if($i==$v-1){
			$stop=1;
		}
	
		$schedule 	= getSchedFromSolr($fromDate,$toDate,$station,$startTimeG,$endTimeG,$zone);
		$str 		= getPDF($schedule,$fromDate,$startTimeG,$endTimeG,$pdf,$zone,$station,$tz);
	
		if($str==0){
			break;
		}
	}
	
	
	//save the PDF to the server
	$pdf->Output($bucket, 'F');
	$s3Type 	= "pdf";
	$s3UserId 	= 1;
	
	//upload the file and get the full path
	$s3filePath = uploadToS3($bucket,$filename,$s3Type,$s3UserId);
	
	//unlink the local file
	unlink($bucket); 
	
	//print $s3filePath;
	download($filename, $s3filePath);



function getSchedFromSolr($fromDate,$toDate,$station,$startTimeG,$endTimeG,$zone){
	$iDate 		= $fromDate;
	$curl_arr 	= array();//
	$master 	= curl_multi_init();//
	$i			= 0;
	
	
	while(strtotime($toDate)>=strtotime($iDate)){		
		$solrUrl  = "http://solr.showseeker.net:8983/solr/gracenote/select/?q=*%3A*&version=2.2&start=0&indent=on&wt=json&fq=projected:0&rows=5000&fq=tz_start_{$zone}:[{$iDate}T00:00:00Z%20TO%20{$iDate}";
		$solrUrl .="T23:59:59Z]&fq=start_{$zone}:[00:00:00%20TO%2023:59:59]&fq=stationnum:{$station}";
		$solrUrl .="+&fl=id,epititle,genre1,genre2,descembed,showtype,stationnum,projected,callsign,search,stars,showid,isnew,tmsid,stationnum,";
		$solrUrl .="title,new,live,stationname,duration,premierefinale,zone,orgairdate,tz_start_{$zone},start_{$zone},day_{$zone},tz_end_{$zone}";
		$solrUrl  = preg_replace("/ /", "%20",$solrUrl);
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
		
		$returnArr = array();
		
		for($i = 0; $i < 7; $i++){

			$returnArr[$i] 	= array();
			$results 		= curl_multi_getcontent  ( $curl_arr[$i]  );
			$dataArr 		= json_decode($results)->response->docs;

			foreach($dataArr as $sh){
				
				$fixed 				= 0;				
				$start				= "start_{$zone}";
				$tz_start			= "tz_start_{$zone}";
				$tz_end				= "tz_end_{$zone}";
				$dayStartArr		= explode('T',$sh->$tz_start);
				$dayStartVal		= $dayStartArr[0];
				$timeStartArr		= explode('Z',$dayStartArr[1]);
				$timeStartVal		= $timeStartArr[0];
				$dayEndArr			= explode('T',$sh->$tz_end);
				$dayEndVal			= $dayEndArr[0];
				$timeEndArr			= explode('Z',$dayEndArr[1]);
				$timeEndVal			= $timeEndArr[0];
				$zoneStartTime		= date('Y-m-d H:i:s',strtotime($dayStartVal.$timeStartVal));
				$zoneEndTime		= date('Y-m-d H:i:s',strtotime($dayEndVal.$timeEndVal));
				$startTm			= date('H:i:s', strtotime($sh->$start));
				$splitTimeStartArr	= explode(":",$timeStartVal);
			
			
				if(strtotime($timeStartVal) < strtotime($startTimeG) && strtotime($timeEndVal) <= strtotime($startTimeG)){
					continue;
				}

				if(strtotime($timeStartVal) < strtotime($startTimeG)){
					
					$sh->duration 		= ((strtotime($timeEndVal) - strtotime($startTimeG))/60).'.0';
					$sh->$tz_start 		= $dayStartArr[0].'T'.$startTimeG.'Z';
					$sh->$start			= $startTimeG;
					$dayStartArr		= explode('T',$sh->$tz_start);
					$dayStartVal		= $dayStartArr[0];
					$timeStartArr		= explode('Z',$dayStartArr[1]);
					$timeStartVal		= $timeStartArr[0];
					$dayEndArr			= explode('T',$sh->$tz_end);
					$dayEndVal			= $dayEndArr[0];
					$timeEndArr			= explode('Z',$dayEndArr[1]);
					$timeEndVal			= $timeEndArr[0];
					$zoneStartTime		= date('Y-m-d H:i:s',strtotime($dayStartVal.$timeStartVal));
					$zoneEndTime		= date('Y-m-d H:i:s',strtotime($dayEndVal.$timeEndVal));
					$startTm			= date('H:i:s', strtotime($sh->$start));
					$splitTimeStartArr	= explode(":",$timeStartVal);
				}			
				
				if(strtotime($timeEndVal) > strtotime($endTimeG) || strtotime($timeEndVal) < strtotime($startTimeG)){

					$sh->duration 		= ((strtotime($endTimeG) - strtotime($timeStartVal))/60).'.0';
					$sh->$tz_end 		= $dayStartArr[0].'T'.$endTimeG.'Z';
					$dayStartArr		= explode('T',$sh->$tz_start);
					$dayStartVal		= $dayStartArr[0];
					$timeStartArr		= explode('Z',$dayStartArr[1]);
					$timeStartVal		= $timeStartArr[0];
					$dayEndArr			= explode('T',$sh->$tz_start);
					$dayEndVal			= $dayEndArr[0];
					$timeEndVal			= $endTimeG;
					$zoneStartTime		= date('Y-m-d H:i:s',strtotime($dayStartVal.$timeStartVal));
					$zoneEndTime		= date('Y-m-d H:i:s',strtotime($dayEndVal.$timeEndVal));
					$startTm			= date('H:i:s', strtotime($sh->$start));
					$splitTimeStartArr	= explode(":",$timeStartVal);
				}

				$hs				= $splitTimeStartArr[0];
				$ms				= $splitTimeStartArr[1];
				$ss				= $splitTimeStartArr[2];
				
				
				if($ms!="00"&&$ms!="30"&&$ms!="15"&&$ms!="45"){
				
					if( $ms>=0 && $ms<=6 ){
						$ms="00";
					}
					elseif( $ms >=7 && $ms<=21 ){
						$ms="15";
					}
					elseif( $ms>=22&&$ms<=36 ){
						$ms="30";
					}
					elseif( $ms>=37&&$ms<=51 ){
						$ms="45";
					}elseif( $ms>=52&&$ms<=59 ){
						
						$ms="00";
						
						if($hs==23){
							$hs="00";
							$s_date = new DateTime($dayStartVal);
							$s_date->modify('+1 day');
							$dayStartVal = $s_date->format('Y-m-d');
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
					}
					elseif(($me>=7&&$me<=21)){
						$me="15";
					}
					elseif(($me>=22&&$me<=36)){
						$me="30";
					}
					elseif(($me>=37&&$me<=51)){
						$me="45";
					}
					elseif(($me>=52&&$me<=59)){

						$me="00";
						
						if($he==23){
							$he="00";
						}
						else{
							$he=$he+1;
						}						
					}
				}
				
				$timeEndVal		= $he.":".$me.":".$se;
				$startTimeApprox= $zoneStartTime;
				$endTimeApprox	= $zoneEndTime;
					
				$duration		= (strtotime($endTimeApprox)-strtotime($startTimeApprox))/1800;
				$zoneStartArr	= explode(" ",$zoneStartTime);
				$zoneEndArr		= explode(" ",$zoneEndTime);
				$durationArr	= explode(".",$duration);
				
				if(isset($durationArr[1])){
					$durationpostDeci= substr($durationArr[1], 0, 1);
				}
				
				if(isset($durationpostDeci)&&$durationpostDeci==5){
					$durationVal	= $duration;
				}
				else{
					$durationVal	= round($duration);
				}
				
				$heightfactorVal=$durationVal;
				
				$timesincemid 	= ((strtotime($timeStartVal)/(60*30)) - (strtotime($startTimeG)/(60*30)));
				$timesincemidArr=explode(".",$timesincemid);
				
				if(isset($timesincemidArr[1])){
					$timesincemidpostDeci=substr($timesincemidArr[1], 0, 1);
				}
				
				if(isset($timesincemidpostDeci)&&$timesincemidpostDeci==5){
					$timesincemidVal=$timesincemid;
				}
				else{
					$timesincemidVal=round($timesincemid);
				}

				$st = $zoneStartArr[0]."T".$zoneStartArr[1]."Z";
				$et = $zoneEndArr[0]."T".$zoneEndArr[1]."Z";

				$returnArr[$i][] = array(
						'showid'			=> $sh->showid
						,'premierefinale'	=> $sh->premierefinale
						,'isnew'			=> $sh->isnew
						,'title'			=> substr($sh->title,0,30)
						,'epititle'			=> substr($sh->epititle,0,30)
						,'genre2'			=> $sh->genre2
						,'genre1'			=> $sh->genre1
						,'start_'.$zone		=> $startTm
						,'tz_start_'.$zone	=> $st	
						,'tz_end_'.$zone	=> $et
						,'duration'			=> $sh->duration
						,'timesincemid'		=> $timesincemidVal
						,'heightfactor'		=> $heightfactorVal
						,'network'			=> $sh->callsign
						,'live'				=> $sh->live
						,'orgairdate'		=> $sh->orgairdate
						,'descembed'		=> $sh->descembed
					);	
			}	
		}
		return $returnArr;
	}	



function getPDF($schedule,$fromDate,$startTimeG,$endTimeG,$pdf,$zone,$station,$tz){
	$pdf->AddPage();
	$height = 5;
	$width	= 25; 
	$toDate = date('Y-m-d',strtotime($fromDate.' next sunday'));

	for($j = 0; $j < 7; $j++){

		if($j==6){
			$empty = array_filter($schedule[$j]);
		}

		if (isset($empty)&&empty($empty)) {
			$empty2 = array_filter($schedule[0]);
		}
		
		if(isset($empty2)&&empty($empty2)) {
			$lastPage = $pdf->getPage();
			$pdf->deletePage($lastPage);
			return 0;
		}
			
		foreach($schedule[$j] AS $sh){			
			$network=$sh['network'];			
		}
	}
	
	/*$json_data	= file_get_contents("http://showseeker.com/services/timezone.php?tz=".$zone);
	
	// json decode proposal data
	$arrData = json_decode($json_data,true);
	
	$json_dataStation	= file_get_contents("http://showseeker.com/services/logo.php?networkid=".$station);
	
	// json decode proposal data
	$arrStation = json_decode($json_dataStation,true);*/
$arrData = array();
 $arrStation = array();
	//********************* LEFT BORDER START *******************************//

	
	if($arrStation[0]['file']==NULL){
		$pdf->writeHTMLCell(12, $height, 10,10, '<div style="display: table; overflow: hidden;"><span style="display: table-cell; vertical-align: middle;line-height:'. $height .'mm;"><b>'.$network.'</b></span></div>', 0, 0, 0, true, 'C', true);
	}
	elseif(!function_exists("gd_info")){
		$pdf->writeHTMLCell(12, $height, 10,10, '<div style="display: table; overflow: hidden;"><span style="display: table-cell; vertical-align: middle;line-height:'. $height .'mm;"><b>'.$network.'</b></span></div>', 0, 0, 0, true, 'C', true);
	}
	else{
	
		$border=array('LTRB' => array('width' => 0, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
		$pdf->setImageScale(1.53); 
		$pdf->Image(urldecode(stripslashes($arrStation[0]['url'])), 10, 9, 5, 5, 'GIF', '', '', true, 150, 'R', false, false, 0, false, false, false);
		$pdf->Image(urldecode(stripslashes($arrStation[0]['url'])), 13, 9, 5, 5, 'GIF', '', 'B', true, 150, '', false, false, 0, false, false, false);
	
	}
	
	$t		= date('H:i:s', strtotime($startTimeG. ' -30 minutes'));
	$n		= strtotime($endTimeG)-strtotime($startTimeG);
	$n		= $n/3600;
	$nArr	= explode('.',$n);
	
	if(isset($nArr[1]))
		$postDeci=substr($nArr[1], 0, 1);
		
	$n		= $n*2;

	
	for($i = 0; $i<$n; $i++){
		$t = date('h:iA',strtotime($t.' +30 minutes'));
		$y = 10+(($i+1)*$height);
		$pdf->writeHTMLCell(12, $height, 10,$y, '<div style="display: table; overflow: hidden;"><span style="display: table-cell; vertical-align: middle;line-height:'. $height .'mm;"><b>'.substr($t,0,-1).'</b></span></div>', 1, 0, 1, true, 'C', true);
	}
	
	$y = 10+(($i+1)*$height);
	
	$pdf->writeHTMLCell(12, $height, 10,$y, '<div style="display: table; overflow: hidden;"><span style="display: table-cell; vertical-align: middle;line-height:'. $height .'mm;"><b>'.$arrData['name_short'].'</b></span></div>', 0, 0, 0, true, 'C', true);

	//********************* LEFT BORDER END *******************************//

	$temp		=	0;
	$commonArr	=	array();

	for($i = 0; $i < 7; $i++){
		$x = 22+(25*$i);
		$y = 10;
		
		$htmlcontent='<div style="display: table; overflow: hidden;"><span style="display: table-cell; vertical-align: middle;line-height:'. ($height - 1) .'mm;"><b>'.date('M d - D',strtotime($fromDate."+$i days")).'</b></span></div>';
		
		$pdf->writeHTMLCell(25, $height,$x,$y, $htmlcontent, 1, 0, 1, true, 'C', true);
		$dateToday=date('Y-m-d');
		
		$currentDate=date('Y-m-d',strtotime($fromDate."+$i days")); 
		
		if(strtotime($currentDate)>=strtotime($dateToday)){
			
			$empty = array_filter($schedule[$i]);

			foreach($schedule[$i] AS $sh){
				
				if($sh['duration']>=7){
					
					$timeZone	= date('H:i:s', strtotime($startTimeG. ' +4 hours'));
					$y 			= 10+(($sh['timesincemid']+1)*$height);
					$startArr	= explode('T',$sh['tz_start_'.$tz]);
					$startVal	= $startArr[0];
					
					if(strtotime($startVal)!=strtotime($currentDate)){
						$x = 22+(25*($i+1));
					}
					else{
						$x = 22+(25*$i);
					}
					
					$endArr		= explode('T',$sh['tz_end_'.$tz]);
					$endVal		= $endArr[0];
					$endTime	= rtrim($endArr[1], "Z");
					$startTime	= $sh['start_'.$tz];

					if(strtotime($startVal)!= strtotime($endVal)){
						$lastProgram=$sh['title'];
					}
				
					$lastDay=date('Y-m-d',strtotime($fromDate."+ 7 days"));
					
					if($sh['timesincemid']>=0 && $sh['timesincemid']<$n){
					
						$color	= "#000000";
						$title	= "";
						$titleWord=$sh['title'];
						$title='<span style="color:#000000;">'.$titleWord.'</span>';
					
						if($sh['isnew']=='New'){
							$title='<span style="color:#006600;"><b>'.$titleWord.'</b></span>';
						}

						if( str_replace(" ", "", $sh['premierefinale']) != ''){
							$title='<span style="color:#FF0000;"><b>'.$titleWord.'</b></span>';
						}
											
						$epititle="";
						$eptitleWord="";
					
						if($sh['heightfactor']>1){
							$eptitleWord=$sh['epititle'];
							$epititle='<br><span align="center" style="color:#2B65EC;">'.$eptitleWord.'</span></br>';
						}
					
						$live="";
					
						if($sh['live']=='Live'){
							$live='<span style="color:#800080;"><b>&nbsp;(LIVE)</b></span>';
							if(trim($eptitleWord)!=""){
								$title='<span style="color:#800080;"><b>'.$titleWord.'</b></span>';
							}
							else{
								$title='<span style="color:#000000;"><b>'.$titleWord.'</b></span>';
							}
							$epititle='<span style="color:#808080;">'.$eptitleWord.'</span>';
						}
					
						$totalLength=strlen($sh['title']);
						$heightfactor=$sh['heightfactor'];
					
						if($totalLength>26 && $heightfactor==0.5){
							$pdf->SetFontSize('4',true);	
						}
						else{
							$pdf->SetFontSize('5',true);
						}
				
						while($heightfactor>=0){
				
							$trimHeight=$y+($heightfactor*$height);
						
							if($trimHeight<=255.5){
								$heightTrim=$heightfactor*$height;
								break;
								}
							else{
								$heightfactorVal=$heightfactor;
								$a=(float)($heightfactor) - (float)(0.5); 
								$heightfactor=$a;
								$epititle="";
							}
						}

						$lineHeight="";
						
						if($totalLength<=20&&$eptitleWord==""){
							$lineHeight='line-height:'. (($height*$sh['heightfactor'])) .'mm;';
						}

						if($sh['heightfactor']<=1){
							$live		=	"";
							$epititle	=	"";
						}
				

						$commnTempArr=array();
						$commonPresent=0;

						if($i==0 && (strtotime($startVal)==strtotime($endVal))){
							for($j=1;$j<5;$j++){
								foreach($schedule[$j] as $common){
									
									$commonendArr=explode('T',$common['tz_end_'.$tz]);
									$commonendVal=$commonendArr[0];
									$commonendTime=rtrim($commonendArr[1], "Z");
									$commonstartTime=$common['start_'.$tz];
									$commonTitle=$common['title'];
								
									if((strtotime($startTime)==strtotime($commonstartTime))&&(strtotime($endTime)==strtotime($commonendTime))&&(trim($titleWord)==trim($commonTitle))){
										$commonkey=strtotime($startTime);
										array_push($commnTempArr, $commonkey);
									}
								}
							}
						
						}
						elseif($i==1||$i==2||$i==3||$i==4){
							if(in_array(strtotime($startTime), $commonArr)){					 
								$commonPresent=1;
						}
						else{
							$commonPresent=0;
						}
					
					}
					
					if(count($commnTempArr)==4){
						$width		=	125;
						$epititle	=	"";
						array_push($commonArr, $commonkey);
					}
					else{
						$width=25;
					}
				
					$html='<div style="display: table; overflow: hidden; max-height:' .$height*$sh['heightfactor']. 'mm;"><span style="display: table-cell; vertical-align: middle; color:'.$color.'">'.$title.$live.$epititle.'</span></div>';
					$html2='<div style="display: table; overflow: hidden;"><span style="display: table-cell; vertical-align: middle; color:'.$color.'">'.$title.$live.'</span></div>';
				
					if(isset($_SESSION['lastProgram']) && $_SESSION['lastProgram']!= ""){

						if($temp==0){
							foreach($schedule[0] as $val){
								
								$startDateLastArr=explode('T',$val['tz_start_'.$tz]);				
								$startLastVal=$startDateLastArr[0];
								if($val['timesincemid']==0){
									if(strtotime($startLastVal)==strtotime($fromDate)){
										break;
									}
									else{
										$xVal = 22;
										$pdf->writeHTMLCell(25, $_SESSION['lastProgram']['height'],$xVal,15, $_SESSION['lastProgram']['html'], 1, 0, 0, true, 'C', true);
										$temp=1;
										break;
									}
								}
								else{
									$MondayToday = (date('w',strtotime($dateToday))==1)?$dateToday:date('Y-m-d',strtotime('previous monday',strtotime($dateToday)));
									if(strtotime($fromDate)!=strtotime($MondayToday)){
										
										$xVal = 22;
										//$pdf->writeHTMLCell(25, $_SESSION['lastProgram']['height'],$xVal,15, $_SESSION['lastProgram']['html2'], 1, 0, 0, true, 'C', true);
										$temp=1;
										break;	
									}
									else{	
										break;
									}
								}
							}
						}	
					}
					
					if(strtotime($startVal)<strtotime($lastDay)){
						
						if($commonPresent==0){
							$pdf->writeHTMLCell($width, $heightTrim,$x,$y, $html, 1, 0, 0, true, 'C', true);
						}
						
						if(strtotime($startVal)!=strtotime($endVal) && strtotime($endTime)>strtotime('00:05:00')){
							$currentDate2=date('Y-m-d',strtotime($fromDate."+ $i days")); 
							if(strtotime($currentDate2)<strtotime($toDate)){
	
								$x = 22+(25*($i+1));
								$topHeight=($sh['heightfactor']*$height)-$heightTrim;
								if($topHeight>2.5&&$topHeight<5){
									$topHeight=5;
								}
								
								$pdf->writeHTMLCell(25, $topHeight,$x,15,$html2, 1, 0, 0, true, 'C', true);
							}
						}
						$heightFirst=($sh['heightfactor']*$height)-$heightTrim;
					}
					else{
						$heightFirst=($sh['heightfactor']*$height);
					}
				}
				
				if(isset($heightFirst)&& isset($html2))
					$lastProgramArr=array( 'height'=>$heightFirst, 'html'=>$html2 );
				
				if(isset($_SESSION['lastProgram'])&&$_SESSION['lastProgram']!=""){
					session_unset();
				}
				if(isset($lastProgramArr))
					$_SESSION['lastProgram']=$lastProgramArr;
				}
			}
		}
		$x = 22+(25*$i);
		$y = 10+(($n+1)*$height);
		$pdf->writeHTMLCell(25, $height,$x,$y, '<div style="display: table; overflow: hidden;"><span style="display: table-cell; vertical-align: middle;line-height:'. $height .'mm;"><b>'.date('M d - D',strtotime($fromDate."+$i days")).'</b></span></div>', 1, 0, 1, true, 'C', true);
	}
	//********************* RIGHT BORDER START *******************************//
	if($arrStation[0]['file']==NULL){
	$pdf->writeHTMLCell(12, $height, 197,10, '<div style="display: table; overflow: hidden;"><span style="display: table-cell; vertical-align: middle;line-height:'. $height .'mm;"><b>'.$network.'</b></span></div>', 0, 0, 0, true, 'C', true);
	}elseif(!function_exists("gd_info")){
	$pdf->writeHTMLCell(12, $height, 197,10, '<div style="display: table; overflow: hidden;"><span style="display: table-cell; vertical-align: middle;line-height:'. $height .'mm;"><b>'.$network.'</b></span></div>', 0, 0, 0, true, 'C', true);
	
	}
	$t=date('H:i:s', strtotime($startTimeG. ' -30 minutes'));
	for($i = 0; $i<$n; $i++)
	{
		$t = date('h:iA',strtotime($t.' +30 minutes'));
		$y = 10+(($i+1)*$height);
		$pdf->writeHTMLCell(12, $height, 197,$y, '<div style="display: table; overflow: hidden;"><span style="display: table-cell; vertical-align: middle;line-height:'. $height .'mm;"><b>'.substr($t,0,-1).'</b></span></div>', 1, 0, 1, true, 'C', true);
	}
	$y = 10+(($i+1)*$height);
	$pdf->writeHTMLCell(12, $height, 197,$y, '<div style="display: table; overflow: hidden;"><span style="display: table-cell; vertical-align: middle;line-height:'. $height .'mm;"><b>'.$arrData['name_short'].'</b></span></div>', 0, 0, 0, true, 'C', true);
	//********************* RIGHT BORDER END *******************************//
	
	
	return 1;
}




function download($file,$filelocation) {
    set_time_limit(0);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $filelocation);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $r = curl_exec($ch);
    curl_close($ch);
    header('Expires: 0'); // no cache
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
    header('Cache-Control: private', false);
    header('Content-Type: application/force-download');
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: ' . strlen($r)); // provide file size
    header('Connection: close');
    echo $r;
}

?>
