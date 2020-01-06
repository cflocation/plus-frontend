<?phpini_set('display_errors',1);ini_set('max_execution_time','0');// phpWord Classesrequire_once 'Classes/PHPWord.php';//GETTING PROPOSAL DATArequire_once 'service_data.php';// json decode proposal data$proposalLines = json_decode($json_data,true);// passed proposal data to array variable$arrProposal = $proposalLines['proposal'];// New Word Document$objWord = new PHPWord();// New portrait section$section = $objWord->createSection(array('orientation'=>'landscape','marginLeft'=>600, 'marginRight'=>600, 'marginTop'=>600, 'marginBottom'=>600, 'pageSizeH'=>12240, 'pageSizeW'=>15840));if(isset($proposalLines['corporation'])){    $corporation = trim($proposalLines['corporation'][0]['name']);    $logo = trim($proposalLines['corporation'][0]['logo']);    $imgPath = str_replace('/services/downloads','',getcwd());    $logo = str_replace('http://ww2.showseeker.com',$imgPath.'/showseeker', $logo);}else{    $corporation = '';    $logo = '';}//$styleTable = array('borderColor'=>'C2C2C2',//			  'borderSize'=>10);//$styleFirstRow = array('bgColor'=>'66BBFF');$bordeinferior = array('borderBottomColor' => 'CCCCCC','borderBottomSize' => 20);$styleFirstRow = array();$styleTable 	= array();$objWord->addTableStyle('myTable', $styleTable, $bordeinferior);$objWord->addFontStyle('fStyle', array('size'=>9, 'name' => 'Verdana'));$objWord->addFontStyle('fStyleUser', array('size'=>9, 'name' => 'Verdana', 'italic'=>true));$objWord->addFontStyle('fStyleCompany', array('size'=>14, 'name' => 'Verdana'));$objWord->addParagraphStyle('pStyle', array('align'=>'center', 'spaceAfter'=>0));//Create Table$table = $section->addTable('doc_report');//Header Image and User details$table->addRow(1400);$cellStyle1		=	array('gridSpan' => 3,'valign'=>'center','borderBottomColor' => '000000','borderBottomSize' => 20);$table->addCell(9300,$cellStyle1)->addImage($logo, array('width'=>170, 'height'=>70, 'align'=>'left'));$cellStyle2		=	array('gridSpan' => 8,'valign'=>'center','borderBottomColor' => '000000','borderBottomSize' => 20);$userinfocell 	= $table->addCell(6100, $cellStyle2);if(isset($proposalLines['user'])){	 $userinfocell->addText($corporation,"fStyleCompany","pStyle");    $user_name = $proposalLines['user'][0]['firstname'].' '.$proposalLines['user'][0]['lastname'];	 $userinfocell->addText($user_name,"fStyleUser","pStyle");    $user_off_address = isset($proposalLines['user'][0]['officeaddress']) ? $proposalLines['user'][0]['officeaddress'] : '';	 $userinfocell->addText($user_off_address,"fStyle","pStyle");          $user_state 	= isset($proposalLines['user'][0]['officestate']) ? ' '.$proposalLines['user'][0]['officestate'] : '';    $user_city 	= isset($proposalLines['user'][0]['officecity']) ? $proposalLines['user'][0]['officecity'] : '';    $user_zip 		= isset($proposalLines['user'][0]['officezipcode']) ? $proposalLines['user'][0]['officezipcode'] : '';        $userinfocell->addText($user_city.', '.$user_state.', '.$user_zip,"fStyle","pStyle");           $user_phone = isset($proposalLines['user'][0]['phone']) ? $proposalLines['user'][0]['phone'] : '';    $userinfocell->addText($user_phone,"fStyle","pStyle");   }//ProposalName$proposalinfo = trim($proposalLines['proposalinfo']['name']);    $table->addRow(400);    $cellStyle2=array('gridSpan' => 11,'valign'=>'center');    $table->addCell(15400,$cellStyle2)->addText($proposalinfo, array('bold'=>true, 'size'=>12),"pStyle");// Listing Headings$table->addRow(420);$cellHeadingStyle = array('bgColor'=>'333333','valign'=>'center');$objWord->addFontStyle('headFontStyle', array('bold'=>true,'name' => 'Verdana', 'size'=>8,'color'=>'FFFFFF'));$objWord->addFontStyle('headFontStyle1', array('bold'=>true,'name' => 'Verdana', 'size'=>8,'color'=>'FFFFFF'));$objWord->addParagraphStyle('headPrStyle', array('align'=>'center','spaceAfter' => 0));$table->addCell(1000,$cellHeadingStyle)->addText("Network","headFontStyle","headPrStyle");$table->addCell(2300,$cellHeadingStyle)->addText("DayPart","headFontStyle","headPrStyle");$table->addCell(5000,$cellHeadingStyle)->addText("Show","headFontStyle","headPrStyle");$table->addCell(1000,$cellHeadingStyle)->addText("Start","headFontStyle","headPrStyle");$table->addCell(1000,$cellHeadingStyle)->addText("End","headFontStyle","headPrStyle");$table->addCell(500,$cellHeadingStyle)->addText("Wks","headFontStyle","headPrStyle");$spotsweek = $table->addCell(800,$cellHeadingStyle);$spotsweek->addText("Spots","headFontStyle1","headPrStyle");$spotsweek->addText("Wk","headFontStyle1","headPrStyle");$table->addCell(1000,$cellHeadingStyle)->addText("RC","headFontStyle","headPrStyle");$table->addCell(1000,$cellHeadingStyle)->addText("Rate","headFontStyle","headPrStyle");$table->addCell(600,$cellHeadingStyle)->addText("Total Spots","headFontStyle1","headPrStyle");$table->addCell(1200,$cellHeadingStyle)->addText("Total","headFontStyle","headPrStyle");// Printing actual proposal data (Zone wise)foreach($arrProposal as $proposalKey => $proposalValue) {    $zoneName = isset($proposalValue['zone']['zonename']) ? $proposalValue['zone']['zonename'] : '';        $table->addRow(600);        $cellStyle4=array('gridSpan' => 11,'valign'=>'center','borderBottomSize'=>3,'borderBottomColor'=>'CCCCCC');    $table->addCell(15400,$cellStyle4)->addText("Zone : ".$zoneName,array('bold'=>true,'name' => 'Verdana', 'size'=>9),array('align'=>'right','spaceAfter' => 0));    $zone_total_spot = 0;    $zone_total = 0;    foreach($proposalValue['lines'] as $zoneKey => $zoneValue){        $logo= '';        $table->addRow(100,null);        $cellStyle = array('valign'=>'center','borderBottomSize'=>3,'borderBottomColor'=>'CCCCCC');        $objWord->addFontStyle('cellFontStyle', 	 array('name' => 'Verdana', 'size'=>8,'color'=>'000000'));        $objWord->addFontStyle('cellFontStyle1', 	 array('bold'=>true,'name' => 'Verdana', 'size'=>8,'color'=>'000000'));		  $objWord->addFontStyle('cellFontStyle2', 	 array('size'=>8, 'name' => 'Verdana','color'=>'FF0000')); 		  $objWord->addFontStyle('cellFontStyleRC', 	 array('italic'=>true,'bold'=>true,'size'=>8, 'name' => 'Verdana','color'=>'006400'));         $objWord->addParagraphStyle('cellPrStyle',  array('align'=>'center','spaceAfter' => 0));        $objWord->addParagraphStyle('cellPrStyle1', array('align'=>'left','spaceAfter' => 0));        $objWord->addParagraphStyle('cellPrStyle2', array('align'=>'right','spaceAfter' => 0));			// NETWORK LOGO OR CALLSIGN 		  if($includelogos != 'false'){	        if(isset($proposalLines['networks'])){	            foreach($proposalLines['networks'] as $network){	                if(trim($network['stationnum'])==$zoneValue['stationnum']){	                    $logo = trim($network['100x100']);	                    $imgPath = str_replace('/services/downloads','',getcwd());	                    $logo = str_replace('http://ww2.showseeker.com',$imgPath.'/showseeker', $logo);	                }	            }	        }	   	     $table->addCell(1000,$cellStyle)->addImage($logo, array('width'=>50, 'height'=>50, 'align'=>'center'),array('align'=>'center','spaceAfter' => 0));        }        else{        		$table->addCell(1000,$cellStyle)->addText($zoneValue['callsign'],array('bold'=>true,'name' => 'Verdana', 'size'=>8),array('align'=>'center'));        }		  // DAYPART        $days 			= isset($zoneValue['dayFormat']) ? $zoneValue['dayFormat'] : '';        $dayPart 		= trim($days).' '.str_replace('M','',str_replace(':00','',date ('g:iA',strtotime($zoneValue['starttime'])))).'-'.str_replace('M','',str_replace(':00','',date ('g:iA',strtotime($zoneValue['endtime']))));        $dayPartInfo = $table->addCell(2300,$cellStyle);        $dayPartInfo->addText($dayPart,"cellFontStyle","cellPrStyle");					  if($zoneValue['premiere'] != ''){	       $dayPartInfo->addText($zoneValue['premiere'],array('bold'=>true,'name' => 'Verdana', 'size'=>8, 'color'=>'FF0000'),array('align'=>'center'));		  		  }		  elseif($zoneValue['premiere'] == '' && $zoneValue['isnew'] === 'New' && $includenew != 'false'){	       $dayPartInfo->addText($zoneValue['isnew'],array('bold'=>true,'name' => 'Verdana', 'size'=>8, 'color'=>'green', 'italic'=>true),array('align'=>'center'));		  }		                  		   //SHOW TITLE	     $title = $zoneValue['title'];        		  $thesetitles = explode(',',$title);	     if(count($thesetitles) > 5){				$title = $thesetitles[0].', '.$thesetitles[1].', '.$thesetitles[2].', '.$thesetitles[3].', '.$thesetitles[4].', more…';		  } 			  $premiereInfo = $table->addCell(5000,$cellStyle);			$textrun = $premiereInfo->createTextRun();			$textrun->addText(rtrim($title,', '),"cellFontStyle1","cellPrStyle1");			if($zoneValue['linetype'] == 'Fixed'){				$textrun->addText(' '.$zoneValue['epititle'],array('name' => 'Verdana', 'size'=>8, 'color'=>'blue', 'italic'=>true));							//SHOW DESCRIPTION				if($includedesc != 'false' && array_key_exists('desc60', $zoneValue) && $zoneValue['desc60'] != ''){					$textrun->addText(' '.$zoneValue['desc60'],array('name' => 'Verdana', 'size'=>8));					}				//LIVE 				$textrun->addText(' '.$zoneValue['live'],array('name' => 'Verdana', 'size'=>8, 'color'=>'purple', 'italic'=>true, 'bold'=>true));								}        			// START TIME        $startTime = explode('/',$zoneValue['startdate']);        $table->addCell(1000,$cellStyle)->addText($startTime[0].'-'.$startTime[1].'-'.substr($startTime[2],2,2),"cellFontStyle","cellPrStyle");			// END TIME        $endTime = explode('/',$zoneValue['enddate']);        $table->addCell(1000,$cellStyle)->addText($endTime[0].'-'.$endTime[1].'-'.substr($endTime[2],2,2),"cellFontStyle","cellPrStyle");			//WEEKS        $table->addCell(500,$cellStyle)->addText($zoneValue['weeks'],"cellFontStyle","cellPrStyle");			//SPOTS BY WEEK        $table->addCell(800,$cellStyle)->addText($zoneValue['spotsweek'],"cellFontStyle","cellPrStyle");			//RATE CARD        $table->addCell(1000,$cellStyle)->addText('$'. number_format($zoneValue['ratevalue'],2,'.',','),"cellFontStyleRC","cellPrStyle");			//RATE        $table->addCell(1000,$cellStyle)->addText('$'. number_format($zoneValue['rate'],2,'.',','),"cellFontStyle","cellPrStyle");			//TOTAL SPOTS        $zone_total_spot += (int)$zoneValue['spots'];        $table->addCell(600,$cellStyle)->addText($zoneValue['spots'],"cellFontStyle","cellPrStyle");						// LINE TOTAL        $total 		= trim($zoneValue['rate'])*trim($zoneValue['spots']);        $zone_total 	=  $zone_total + $total;        $table->addCell(1200,$cellStyle)->addText('$'. number_format($total,2,'.',','),"cellFontStyle","cellPrStyle2");    }    	// ZONE TOTAL    $table->addRow(250,"exact");        $cellStyle5	=array('gridSpan' => 8,'valign'=>'center');        $table->addCell(12600,$cellStyle5)->addText('');    $cellStyle6	=	array('valign'=>'center','borderBottomSize'=>3,'borderBottomColor'=>'D2D2D2');        $table->addCell(1000,$cellStyle6)->addText("Total ",array('bold'=>true,'name' => 'Verdana', 'size'=>8),array('align'=>'center'));    $table->addCell(600,$cellStyle6)->addText($zone_total_spot,array('bold'=>true,'name' => 'Verdana', 'size'=>8),array('align'=>'center'));    $table->addCell(1200,$cellStyle6)->addText('$'.number_format($zone_total,2),array('bold'=>true,'name' => 'Verdana', 'size'=>8),array('align'=>'right'));}	$table->addRow(300,"exact");	$table->addCell(15400,array('gridSpan' => 11))->addText('');	//BREAKDOWN BY BROADCAST MONTH		$table->addRow(250,"exact");		$cellStyle7=array('gridSpan' => 5);	$table->addCell(10300,$cellStyle7)->addText('');		$cellStyle7=array('gridSpan' => 3,'bgColor'=>'333333','valign'=>'center');	$table->addCell(2300,$cellStyle7)->addText('Breakdown By Month',array('bold'=>true,'name' => 'Verdana', 'size'=>8),array('align'=>'center','spaceAfter' => 0));	$cellStyle7=array('valign'=>'center','bgColor'=>'333333');	$table->addCell(1000,$cellStyle7)->addText('Month',array('bold'=>true,'name' => 'Verdana', 'size'=>8),array('align'=>'center','spaceAfter' => 0));	$table->addCell(600,$cellStyle7)->addText('Spots',array('bold'=>true,'name' => 'Verdana', 'size'=>8),array('align'=>'center','spaceAfter' => 0));	$table->addCell(1200,$cellStyle7)->addText('Cost',array('bold'=>true,'name' => 'Verdana', 'size'=>8),array('align'=>'center','spaceAfter' => 0));	$grandGrossTotal = 0;	$grandNetTotal = 0;		if(isset($proposalLines['brodmonthstotal']) && !empty($proposalLines['brodmonthstotal'])){    foreach($proposalLines['brodmonthstotal'] as $yearKey => $yearTotal){            foreach($yearTotal as $monthValue){                $table->addRow(250,"exact");            $cellStyle7=array('gridSpan' => 8,'valign'=>'center');                $table->addCell(12600,$cellStyle7)->addText('');            $cellStyle7=array('valign'=>'center','borderBottomColor' => 'CCCCCC','borderBottomSize' => 3);            //BROADCAST MONTH            $table->addCell(1000,$cellStyle7)->addText($monthValue['monthnumber'].'-'.substr($yearKey,2,2),array('bold'=>true,'name' => 'Verdana', 'size'=>8,'color'=>'000000'),array('align'=>'center'));				// TOTAL SPOTS BY MONTH            $table->addCell(600,$cellStyle7)->addText($monthValue['spotsmonth'],array('bold'=>true,'name' => 'Verdana', 'size'=>8,'color'=>'000000'),array('align'=>'center'));				// TOTAL COST BY MONTH            $table->addCell(1200,$cellStyle7)->addText('$'.number_format($monthValue['monthtotal'],2),array('bold'=>true,'name' => 'Verdana', 'size'=>8,'color'=>'000000'),array('align'=>'right'));				$grandGrossTotal 	= $grandGrossTotal + $monthValue['monthtotal'];				$grandNetTotal 	= $grandNetTotal + $monthValue['nettotal'];	        }    }	}$table->addRow(300,"exact");$table->addCell(15400,array('gridSpan' => 11))->addText('');//Gross Total$table->addRow(250,"exact");$cellStyle=array('gridSpan' => 8,'valign'=>'center');$table->addCell(12600,$cellStyle)->addText('');$cellStyle=array('valign'=>'center');$table->addCell(1000,$cellStyle)->addText('Gross',array('bold'=>true,'name' => 'Verdana', 'size'=>8),array('align'=>'left','spaceAfter' => 0));$cellStyle = array('gridSpan' => 2,'valign'=>'center','borderBottomSize'=>3,'borderBottomColor'=>'CCCCCC');$table->addCell(1800,$cellStyle)->addText('$'.number_format($grandGrossTotal,2),array('bold'=>true,'name' => 'Verdana', 'size'=>8),array('align'=>'right','spaceAfter' => 0));//Net Total$table->addRow(250,"exact");$cellStyle=array('gridSpan' => 8,'valign'=>'center');$table->addCell(12600,$cellStyle)->addText('');$cellStyle=array('valign'=>'center');$table->addCell(1000,$cellStyle)->addText('Net Total',array('bold'=>true,'name' => 'Verdana', 'size'=>8),array('align'=>'left','spaceAfter' => 0));$cellStyle = array('gridSpan' => 2,'valign'=>'center','borderBottomSize'=>3,'borderBottomColor'=>'CCCCCC');$table->addCell(1800,$cellStyle)->addText('$'.number_format($grandNetTotal,2),array('bold'=>true,'name' => 'Verdana', 'size'=>8),array('align'=>'right','spaceAfter' => 0));//Signature and Date$table->addRow(250,"exact");$cellStyle=array('valign'=>'center');$table->addCell(1000,$cellStyle)->addText('Signature',array('bold'=>true,'name' => 'Verdana', 'size'=>8),array('align'=>'center','spaceAfter' => 0));$cellStyle=array('gridSpan' => 6,'borderBottomSize'=>3,'borderBottomColor'=>'CCCCCC');$table->addCell(10800,$cellStyle)->addText('');$cellStyle=array('valign'=>'center');$table->addCell(800,$cellStyle)->addText('');$cellStyle=array('valign'=>'center');//SIGNATURE DATE$table->addCell(1000,$cellStyle)->addText('Date',array('bold'=>true,'name' => 'Verdana', 'size'=>8),array('align'=>'left','spaceAfter' => 0));$cellStyle = array('gridSpan' => 2,'valign'=>'center','borderBottomSize'=>3,'borderBottomColor'=>'CCCCCC');$table->addCell(1800,$cellStyle)->addText(date('m-d-Y'),array('bold'=>true,'name' => 'Verdana', 'size'=>8),array('align'=>'right','spaceAfter' => 0));if($includetc == 'true' && $corporation_id == 25){//MidHudson T&C	include_once('terms/midhudson.php');	termsandconditions($table,11);}	// Save File$objWriter = PHPWord_IOFactory::createWriter($objWord, 'Word2007');$pslFileName = cleanStr($proposalinfo);$objWriter->save('/var/www/html/showseeker/files/tmp/'.$pslFileName.'.docx');echo '{"filename":"'.$pslFileName.'.docx"}';exit;// Download fileecho $file = $excelLocation.'word_report_'.$_GET['proposalid'].'.docx';if (file_exists($file)) {    header('Content-Description: File Transfer');    header('Content-Type: application/octet-stream');    header('Content-Disposition: attachment; filename='.basename($file));    header('Content-Transfer-Encoding: binary');    header('Expires: 0');    header('Cache-Control: must-revalidate');    header('Pragma: public');    header('Content-Length: ' . filesize($file));    ob_clean();    flush();    readfile($file);    exit;}?>