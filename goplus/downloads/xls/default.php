<?php$styleHead1 = array(    'font'  => array(        'bold'  => true,        'italic' => true,        'color' => array('rgb' => '000000'),        'size'  => 13,        'name'  => 'Verdana'    ));    $styleHead2 = array(        'font'  => array(                'bold'  => true,                'color' => array('rgb' => 'FFFFFF'),                'size'  => 11));$styleBorderLines = array(           	'style' => PHPExcel_Style_Border::BORDER_THIN,           	'color' => array('rgb' => 'CCCCCC'));// create PHPExcel object$objExcel 		= new PHPExcel();// Set document properities$objExcel->getProperties()->setCreator("Excel Export")         ->setLastModifiedBy("VAST")         ->setTitle("ShowSeeker")         ->setSubject("Report")         ->setDescription("Proposal XLSX");// page settings$sheet = $objExcel->getActiveSheet();//Centering Page Contents$sheet->getStyle( $sheet->calculateWorksheetDimension() )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); $sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER);$sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 4);$pageMargins = $sheet->getPageMargins();$margin = 0.6 / 2.54;$pageMargins->setTop($margin);$pageMargins->setBottom($margin);$pageMargins->setLeft($margin);$pageMargins->setRight($margin);//$objExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);//$objExcel->getActiveSheet()->getPageSetup()->setFitToWidth(0);//$objExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);// Set active sheet index to the first sheet$objExcel->setActiveSheetIndex(0);// Assing style to header text$objRichText = new PHPExcel_RichText();    $corporation = trim($proposalLines['corporation'][0]['name']);    $logo = trim($proposalLines['corporation'][0]['logo']);    $imgPath = str_replace('/services/downloads','',getcwd());    $logo = str_replace('http://ww2.showseeker.com',$imgPath.'/showseeker', $logo);//inserting Company Name	$objBold = $objRichText->createTextRun(trim($corporation));	$objBold->getFont()->setBold(true)->setName('Verdana')->setSize(11);// Collect User info    $user_name 			= $proposalLines['user'][0]['firstname'].' '.$proposalLines['user'][0]['lastname'];	    $user_off_address 	= $proposalLines['user'][0]['officeaddress'];    $user_state 			= $proposalLines['user'][0]['officestate'];    $user_city 			= $proposalLines['user'][0]['officecity'];    $user_zip 				= $proposalLines['user'][0]['officezipcode'];    $user_phone 			= $proposalLines['user'][0]['phone'];    $objNormal = $objRichText->createTextRun("\n".$user_name." \n".$user_off_address." \n".$user_city.", ".$user_state." ".$user_zip." \n".$user_phone."");	 $objNormal->getFont()->setSize(8)->setName('Verdana');// Display Corporation Logo$objExcel->getActiveSheet()->mergeCells('A1:D1');$objDrawing = new PHPExcel_Worksheet_Drawing();$objDrawing->setPath($logo);$objDrawing->setHeight(80);$objDrawing->setWidth(180);$objDrawing->setCoordinates('A1');$objDrawing->setOffsetX(1);$objDrawing->setWorksheet($objExcel->getActiveSheet());// Display user name and address in excel report$objExcel->getActiveSheet()->mergeCells('E1:J1');$objExcel->getActiveSheet()->getStyle('E1:J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);$objExcel->getActiveSheet()->setCellValue('E1',$objRichText);$objExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(60);$objExcel->getActiveSheet()->getStyle('E1')->getAlignment()->setWrapText(true);// Set bottom border to header row$objExcel->getActiveSheet()->getStyle('A1:J1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);//Display Header line (proposal info) of excel report$objExcel->getActiveSheet()->mergeCells('A2:J2');$objExcel->getActiveSheet()->getStyle('A2:J2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);if(isset($proposalLines['proposalinfo'])){    $proposalinfo = trim($proposalLines['proposalinfo']['name']);}else{    $proposalinfo = '';}$objExcel->getActiveSheet()->setCellValue('A2',$proposalinfo);$objExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleHead1);$objExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('Page &P / &N');$objExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter('Page &P / &N');// Set columns heading with grey background$objExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(25);$objExcel->getActiveSheet()->getStyle('A3:J3')->applyFromArray($styleHead2);$objExcel->getActiveSheet()->getStyle('A3:J3')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => '333333')));$objExcel->getActiveSheet()->getStyle('A3:J3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);$objExcel->getActiveSheet()->getStyle('A3:J3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);$objExcel->getActiveSheet()->setCellValue('A3','Network');$objExcel->getActiveSheet()->setCellValue('B3','DayPart');$objExcel->getActiveSheet()->setCellValue('C3','Show');$objExcel->getActiveSheet()->setCellValue('D3','Start');$objExcel->getActiveSheet()->setCellValue('E3','End');$objExcel->getActiveSheet()->setCellValue('F3','Wks');$objExcel->getActiveSheet()->setCellValue('G3',"Spots\nWk");$objExcel->getActiveSheet()->getStyle('G3')->getAlignment()->setWrapText(true);$objExcel->getActiveSheet()->getStyle('G3')->applyFromArray(array('font'  => array('size'  => 8)));$objExcel->getActiveSheet()->setCellValue('H3','Rate');$objExcel->getActiveSheet()->setCellValue('I3',"Total\nSpots");$objExcel->getActiveSheet()->getStyle('I3')->getAlignment()->setWrapText(true);$objExcel->getActiveSheet()->getStyle('I3')->applyFromArray(array('font'  => array('size'  => 8)));$objExcel->getActiveSheet()->setCellValue('J3','Total');// FreezePane rows (Assign vertical scrolling from this column and row)$objExcel->getActiveSheet()->freezePane('A4');$row = 4;// Printing actual proposal data (Zone wise)foreach($arrProposal as $proposalKey => $proposalValue) {    $objExcel->getActiveSheet()->mergeCells('A'.$row.':C'.$row.'');    $objExcel->getActiveSheet()->mergeCells('D'.$row.':J'.$row.'');    $objExcel->getActiveSheet()->getStyle('D'.$row.':J'.$row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);    $zoneName = isset($proposalValue['zone']['zonename']) ? $proposalValue['zone']['zonename'] : '';    $objExcel->getActiveSheet()->setCellValue('D'.$row,'Zone : '.$zoneName);    $objExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(25);    $objExcel->getActiveSheet()->getStyle('A'.$row.':'.'J'.$row)->applyFromArray(array('font'  => array('color' => array('rgb' => '000000'),'size'  => 8, 'name'  => 'Verdana')));    $objExcel->getActiveSheet()->getStyle('A'.$row.':'.'J'.$row)->getBorders()->getBottom()->applyFromArray($styleBorderLines);                $zone_row = $row;        foreach($proposalValue['lines'] as $zoneKey => $zoneValue){                $row++;                $logo= '';        		  if($includelogos != 'false'){        if(isset($proposalLines['networks'])){            foreach($proposalLines['networks'] as $network){                if(trim($network['stationnum'])==$zoneValue['stationnum']){                    $logo = trim($network['100x100']);                    $imgPath = str_replace('/services/downloads','',getcwd());                    $logo = str_replace('http://ww2.showseeker.com',$imgPath.'/showseeker', $logo);                }            }        }                //NETWORK LOGOS        $objDrawing = new PHPExcel_Worksheet_Drawing();        $objDrawing->setPath($logo);        $objDrawing->setHeight(50);        $objDrawing->setWidth(50);        $objDrawing->setCoordinates('A'.$row);        $objDrawing->setOffsetX(5);        $objDrawing->setOffsetY(7);        $objDrawing->setWorksheet($objExcel->getActiveSheet());               $objExcel->getActiveSheet()->setCellValue('A'.$row,'');                        }                else{			  $objRichText 		= new PHPExcel_RichText();			  $objCellCallsign 	= $objRichText->createTextRun($zoneValue['callsign']);			  $objCellCallsign->getFont()->setName('Verdana')->setSize(8)->setBold(true);			  $objExcel->getActiveSheet()->setCellValue('A'.$row, $objRichText);		                }                $objExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(50);                $objExcel->getActiveSheet()->getStyle('A'.$row.':'.'J'.$row)->applyFromArray(array('font'  => array('color' => array('rgb' => '000000'),'size'  => 8, 'name'  => 'Verdana')));        $objExcel->getActiveSheet()->getStyle('A'.$row.':'.'J'.$row)->getBorders()->getBottom()->applyFromArray($styleBorderLines);                                $objExcel->getActiveSheet()->getStyle('A'.$row.':'.'J'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);        $objExcel->getActiveSheet()->getStyle('A'.$row.':'.'J'.$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);        $days = isset($zoneValue['dayFormat']) ? $zoneValue['dayFormat'] : '';        $dayPart = trim($days).' '.str_replace('M','',str_replace(':00','',date ('g:iA',strtotime($zoneValue['starttime'])))).'-'.str_replace('M','',str_replace(':00','',date ('g:iA',strtotime($zoneValue['endtime']))));                       $objRichText = new PHPExcel_RichText();		  		  //DAYPART		  $objCellDayPart = $objRichText->createTextRun($dayPart);		  $objCellDayPart->getFont()->setName('Verdana')->setSize(8);		  					 //PREMIERES FLAG		  $premieres = $zoneValue['premiere'];		  if($premieres != ''){		  	  if($premieres == 'Premiere'){			  	  $premieres = "Movie Premiere";		  	  }			  $objCellPremiere = $objRichText->createTextRun(" \n". $premieres."");	  		  $objCellPremiere->getFont()->setName('Verdana')->setSize(8)->setItalic(true)->setBold(true)->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_RED ));		  }		 //NEW FLAG		  elseif($zoneValue['premiere'] == '' && $zoneValue['isnew'] == 'New' && $includenew != 'false'){			  $objCellNew = $objRichText->createTextRun(" \n". $zoneValue['isnew']."");	  		  $objCellNew->getFont()->setName('Verdana')->setSize(8)->setItalic(true)->setBold(true)->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_DARKGREEN ));		  		  }		          $objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);        $objExcel->getActiveSheet()->setCellValue('B'.$row, $objRichText);		  $objExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setWrapText(true);                $objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(49);                $objExcel->getActiveSheet()->getStyle('C'.$row)->getFont()->setBold(true);        $objExcel->getActiveSheet()->getStyle('C'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);			//SHOW DETAILS		  $objTitle =  $objRichText = new PHPExcel_RichText();		  		  //SHOW TITLE        $title = $zoneValue['title'];		  $thesetitles = explode(',',$title);	     if(count($thesetitles) > 5){				$title = $thesetitles[0].', '.$thesetitles[1].', '.$thesetitles[2].', '.$thesetitles[3].', '.$thesetitles[4].', more…';		  } 		  		  		  $objCellTitle = $objTitle->createTextRun(rtrim($title,', '));		  $objCellTitle->getFont()->setBold(true)->setName('Verdana')->setSize(8);		  		  //EPISODE		  $objCellEpisode = $objTitle->createTextRun(' '.$zoneValue['epititle']);	  	  $objCellEpisode->getFont()->setName('Verdana')->setSize(8)->setItalic(true)->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_BLUE ));		  		  //DESCRIPTION		  if($zoneValue['linetype'] == 'Fixed' && array_key_exists('desc60', $zoneValue) && $zoneValue['desc60'] != '' && $includedesc != 'false'){			  $showDescription = isset($zoneValue['desc60']) ? $zoneValue['desc60'] : '';			  $objCellEpisode = $objTitle->createTextRun(' '.$showDescription);	  		  $objCellEpisode->getFont()->setName('Verdana')->setSize(8);		  }		  			//WRITING TITLE, EPISODE AND DESCRIPTION		        $objExcel->getActiveSheet()->setCellValue('C'.$row, $objTitle);		  $objExcel->getActiveSheet()->getStyle('C'.$row)->getAlignment()->setWrapText(true);		  //START DATE        $objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(11);        $objExcel->getActiveSheet()->setCellValue('D'.$row, date('n-j-Y',strtotime($zoneValue['startdate'])));		  $objExcel->getActiveSheet()->getStyle('D'.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DMYMINUS);		  //END DATE        $objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(11);        $objExcel->getActiveSheet()->setCellValue('E'.$row,date ('n-j-Y',strtotime($zoneValue['enddate'])));		  $objExcel->getActiveSheet()->getStyle('E'.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DMYMINUS);                //WEEKS        $objExcel->getActiveSheet()->getColumnDimension('F')->setWidth(5);        $objExcel->getActiveSheet()->setCellValue('F'.$row,$zoneValue['weeks']);        		  //SPOTS BY WEEK        $objExcel->getActiveSheet()->getColumnDimension('G')->setWidth(5);        $objExcel->getActiveSheet()->setCellValue('G'.$row,$zoneValue['spotsweek']);        		  //RATE        $objExcel->getActiveSheet()->getColumnDimension('H')->setWidth(8);        $objExcel->getActiveSheet()->setCellValue('H'.$row,'=Dollar('.$zoneValue['rate'].')');        		  //TOTAL SPOTS BY LINE        $objExcel->getActiveSheet()->getColumnDimension('I')->setWidth(8);        $objExcel->getActiveSheet()->setCellValue('I'.$row,$zoneValue['spots']);		  //TOTAL COST BY LINE        $objExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);        $objExcel->getActiveSheet()->setCellValue('J'.$row,'=(H'.$row.'*I'.$row.')');		  $objExcel->getActiveSheet()->getStyle('J'.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);		  $objExcel->getActiveSheet()->getStyle('J'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);        //$objExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(-1);                            }    $row ++;    $objExcel->getActiveSheet()->getStyle('F'.$row.':'.'J'.$row)->getFont()->setBold(true);    $objExcel->getActiveSheet()->getStyle('F'.$row.':'.'H'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);    $objExcel->getActiveSheet()->mergeCells('F'.$row.':H'.$row);    $objExcel->getActiveSheet()->setCellValue('F'.$row,'Total');    $objExcel->getActiveSheet()->getStyle('I'.$row.':'.'J'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);    $objExcel->getActiveSheet()->getStyle('I'.$row.':'.'J'.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);        $objExcel->getActiveSheet()->setCellValue('I'.$row,'=SUM(I'.$zone_row.':I'.($row-1).')');    $objExcel->getActiveSheet()->setCellValue('J'.$row,'=DOLLAR(SUM(J'.$zone_row.':J'.($row-1).'))');    $row += 2;}//Display Month Breakdown Header$row += 2;$areaOfTotals = $row;$objExcel->getActiveSheet()->getStyle('E'.$row.':J'.$row.'')->applyFromArray($styleHead2);$objExcel->getActiveSheet()->getStyle('E'.$row.':J'.$row.'')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => '000000')));$objExcel->getActiveSheet()->getStyle('E'.$row.':J'.$row.'')->getFont()->setBold(true);$objExcel->getActiveSheet()->mergeCells('E'.$row.':G'.$row.'');$objExcel->getActiveSheet()->getStyle('E'.$row.':G'.$row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);$objExcel->getActiveSheet()->setCellValue('E'.$row,'Breakdown by Month');$objExcel->getActiveSheet()->getStyle('H'.$row.':J'.$row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);$objExcel->getActiveSheet()->setCellValue('H'.$row,'Month');$objExcel->getActiveSheet()->setCellValue('I'.$row,'Spots');$objExcel->getActiveSheet()->setCellValue('J'.$row,'Cost');$objExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);$row++;//Display Month Breakdown dataif(isset($proposalLines['brodmonthstotal'])){    foreach($proposalLines['brodmonthstotal'] as $yearKey => $yearVal){        $year = substr($yearKey,2,2);        foreach($yearVal as $monthKey => $monthVal){            $objExcel->getActiveSheet()->getStyle('H'.$row.':J'.$row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);            $objExcel->getActiveSheet()->setCellValue('H'.$row,$monthVal['monthnumber'].'-'.$year);            $objExcel->getActiveSheet()->setCellValue('I'.$row,$monthVal['spotsmonth']);            $objExcel->getActiveSheet()->setCellValue('J'.$row,'=DOLLAR('.$monthVal['monthtotal'].')');		      $objExcel->getActiveSheet()->getStyle('H'.$row.':'.'J'.$row)->getBorders()->getBottom()->applyFromArray($styleBorderLines);            $row++;        }    }}$row++;//Display total Net and Gross valuesif(isset($proposalLines['totals'])){    $objExcel->getActiveSheet()->getStyle('F'.$row.':'.'J'.$row)->getFont()->setBold(true);    $objExcel->getActiveSheet()->getStyle('F'.$row.':'.'H'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);    $objExcel->getActiveSheet()->mergeCells('F'.$row.':H'.$row);    $objExcel->getActiveSheet()->setCellValue('F'.$row,'Gross');    $objExcel->getActiveSheet()->mergeCells('I'.$row.':J'.$row);    $objExcel->getActiveSheet()->getStyle('I'.$row.':'.'J'.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);    $objExcel->getActiveSheet()->getStyle('I'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);    $objExcel->getActiveSheet()->setCellValue('I'.$row,'=DOLLAR('.$proposalLines['totals']['gross'].')');    $row++;    $objExcel->getActiveSheet()->getStyle('F'.$row.':'.'J'.$row)->getFont()->setBold(true);    $objExcel->getActiveSheet()->getStyle('F'.$row.':'.'H'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);    $objExcel->getActiveSheet()->mergeCells('F'.$row.':H'.$row);    $objExcel->getActiveSheet()->setCellValue('F'.$row,'Net Total');    $objExcel->getActiveSheet()->mergeCells('I'.$row.':J'.$row);    $objExcel->getActiveSheet()->getStyle('I'.$row.':'.'J'.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);    $objExcel->getActiveSheet()->getStyle('I'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);    $objExcel->getActiveSheet()->setCellValue('I'.$row,'=DOLLAR('.$proposalLines['totals']['net'].')');    $row++;    // Display signature and date field    $objExcel->getActiveSheet()->getStyle('A'.$row.':'.'J'.$row)->getFont()->setBold(true);    $objExcel->getActiveSheet()->setCellValue('A'.$row,'Signature');    $objExcel->getActiveSheet()->getStyle('B'.$row.':'.'E'.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);    $objExcel->getActiveSheet()->getStyle('F'.$row.':'.'H'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);    $objExcel->getActiveSheet()->mergeCells('F'.$row.':H'.$row);    $objExcel->getActiveSheet()->setCellValue('F'.$row,'Date');    $objExcel->getActiveSheet()->mergeCells('I'.$row.':J'.$row);    $objExcel->getActiveSheet()->getStyle('I'.$row.':'.'J'.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);    $objExcel->getActiveSheet()->getStyle('I'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);    $objExcel->getActiveSheet()->setCellValue('I'.$row,date('m-d-Y'));}$objExcel->getActiveSheet()->getStyle('A'.$areaOfTotals.':'.'J'.$row)->applyFromArray(array('font'  => array('size'  => 8, 'name'  => 'Verdana')));if($includetc == 'true' && $corporation_id == 25){	include_once('terms/midhudson.php');	termsandconditions($objExcel,$row,10);}elseif($includetc == 'true' && $corporation_id == 4){	$row++;	$objRichText 		= new PHPExcel_RichText();	$objCellTC = $objRichText->createTextRun('See Attached Terms and Conditions');	$objCellTC->getFont()->setName('Verdana')->setSize(8)->setItalic(true)->setBold(true)->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_BLUE ));	$objExcel->getActiveSheet()->setCellValue('A'.$row, $objRichText);			 	$objExcel->getActiveSheet()->mergeCells('A'.$row.':H'.$row.'');    	  	$objExcel->getActiveSheet()->getCell('A' . $row)->getHyperlink('')->setUrl('http://ww2.showseeker.com/exportshotlink/tc');}// Create and Save file//$objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');$objWriter = new PHPExcel_Writer_Excel2007($objExcel);$pslFileName = cleanStr($proposalinfo);$objWriter->save('/var/www/html/showseeker/files/tmp/'.$pslFileName.'.xlsx');echo '{"filename":"'.$pslFileName.'.xlsx"}';exit;?>