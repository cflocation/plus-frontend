<?php
	ini_set('max_execution_time','0');
	ini_set('memory_limit','1024M');
	set_time_limit(480);
	
	
	//phpEXCEL Classes
	require_once 'Classes/PHPExcel.php';
	
	$cacheMethod 	= PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
	$cacheSettings 	= array( ' memoryCacheSize ' => '64MB');
	PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
	
	require_once 'Classes/PHPExcel/Writer/Excel2007.php';
	
	//GETTING PROPOSAL DATA
	require_once 'proposal.data.ratings.php';
	require_once 's3/upload.php';

	// json decode proposal data
	$proposalLines = json_decode($json_data,true);
	
	//corporation
	$corporation_id = $user->corporationId;
	
	
	//USER ID
	$userid 		= $user->id;

	$userFirstName 	= $user->firstName;
	$userLastName	= $user->lastName;
	$userAddress 	= $user->address;
	$userCity 		= $user->city;
	$userState 		= $user->state;
	$userZip 		= $user->zip;
	$userPhone 		= $user->phone;


	
	
	// passed proposal data to array variable
	$arrProposal 			= $proposalLines['proposal'];
	$arrRatingsTotals 		= $proposalLines['ratingsTotals'];
	$arrRatingsSettings 	= $proposalLines['ratingsSettings'];
	$ratingsSettingsRatings = $arrRatingsSettings['ratings'];
	$ratingsSettingsAverage = $arrRatingsSettings['average'];
	$ratingsSettingsImpressions = $arrRatingsSettings['impressions'];
	$ratingsSettingsRounded = $arrRatingsSettings['rounded'];

	$alphabet 				= ('0, A, B, C, D, E, F, G, H, I, J, K, L, M, N, O, P, Q, R, S, T, U, V, W, X, Y, Z, AA, AB, AC, AD, AE, AF, AG, AH, AI, AJ, AK, AL, AM, AN, AO, AP, AQ, AR, AS, AT, AU, AV, AW, AX, AY, AZ, BA, BB, BC, BD, BE, BF, BG, BH, BI, BJ, BK, BL, BM, BN, BO, BP, BQ, BR, BS, BT, BU, BV, BW, BX, BY, BZ');
	$alphaCols 				= explode(', ',$alphabet);
	$columnTotalArray 		= ("CPP, GRPs, CPM, gImps, Reach%, Freq") ;
	$columnTotalTitles 		= explode(', ',$columnTotalArray); 
	$TotalArrayKeysSRC 		= ("CPP, gRps, CPM, gImps, reach, freq") ;
	$TotalArrayKeys 		= explode(', ',$TotalArrayKeysSRC); 
	$columnTitles 			= $arrRatingsSettings['demosInfo']['header'];
	$cols					= count($columnTitles);

	if($cols == 5){
		$colSize = 5;
	}	
	else{
		$colSize = 3;		
	}
	
	$ratingsHolder 	= $arrRatingsSettings['demosInfo']['keys'];


	
	$styleHead1 = array(
	    	'font'  => array(
	        'bold'  => true,
	        'color' => array('rgb' => '003366'),
	        'size'  => 14,
	        'name'  => 'Arial'
	    ));
	    
	$styleHead2 = array(
	        		'font'  => array('color' => array('rgb' => 'FFFFFF'),
	                'size'  => 9,
	                'name'  => 'Arial'));
	
	$styleTZ = array(
	        		'font'  => array(
	                'color' => array('rgb' => '333333'),
	                'size'  => 8,
	                'name'  => 'Arial'
	));
	
	$epititleStyling = array(
	        		'font'  => array(
	                'color' => array('rgb' => '003366'),
	                'size'  => 8,
	                'name'  => 'Arial'
	));
	
	$styleBorderLines = array(
	           	'style' => PHPExcel_Style_Border::BORDER_THIN,
	           	'color' => array('rgb' => 'CCCCCC')
	);
	
	
	$styleBorderZones = array(
	           	'style' => PHPExcel_Style_Border::BORDER_THIN,
	           	'color' => array('rgb' => '999999')
	);
	
	$styleRatings1 = array(
	    'font'  => array(
	        'bold'  => true,
	        'color' => array('rgb' => '003366'),
	        'size'  => 14,
	        'name'  => 'Arial'
	    ));



	$styleThinBottomGrey= array('borders' => array('bottom' => array('style' => 
	PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '999999'),)));
	
	$styleThinLeftGrey= array('borders' => array('left' => array('style' => 
	PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '999999'),)));
	
	$styleThinRightGrey= array('borders' => array('left' => array('style' => 
	PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '999999'),)));
	
	$styleThinBottomWhite= array('borders' => array('bottom' => array('style' => 
	PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FFFFFF'),)));
	
	$styleThinRightWhite= array('borders' => array('left' => array('style' => 
	PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => 'FFFFFF'),)));
	
	
	
	
	$styleRows 		= array('font'  => array('color' => array('rgb' => '000000'),'size'  => 8, 'name'  => 'Arial'));
	
	// create PHPExcel object
	$objExcel 		= new PHPExcel();
	
	
	$date 			= new DateTime();
	$cDate 			= $date->format('m-d-y');

// Set document properities
$objExcel->getProperties()->setCreator("Excel Export")
         ->setLastModifiedBy("VAST")
         ->setTitle("ShowSeeker")
         ->setSubject("Report")
         ->setDescription("Proposal XLSX");


	// page settings
	$sheet = $objExcel->getActiveSheet();

	// Set active sheet index to the first sheet
	$objExcel->setActiveSheetIndex(0);


	// Assing style to header text
	$objRichText 	= new PHPExcel_RichText();

	$corporation 	= trim($user->corporationName);
	$logo 			= trim($user->logo);
	$networklogo 	= explode('/', $logo);
	$nlogo 			= $networklogo[sizeof($networklogo)-1];
	$imgPath 		= str_replace('goexport','',getcwd());
	$logo 			= $imgPath.'/logos/'.$nlogo;
	$proposalinfo 	= trim($proposalLines['proposalinfo']['name']);


	if($corporation_id == 4 || $corporation_id == 10 || $corporation_id == 20){
		$lCellRange 	= 	'A1:AZ1';
		$rCellRange 	= 	'BA1:BT1';
		$logoPosition 	= 	'B1';
		$logoWidth 		= 	360;
	}
	else{
		$lCellRange		= 	'A1:AZ1';
		$rCellRange 	= 	'BA1:BT1';
		$logoPosition 	= 	'B1';
		$logoWidth 		= 	180;
	}

	$sheet->mergeCells($lCellRange);
	$sheet->getStyle($lCellRange)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$sheet->getStyle($lCellRange)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	
	$sheet->mergeCells('A2:BT2');
	$sheet->setCellValue('A2',$proposalinfo);
	$sheet->getStyle('A2:BT2')->applyFromArray($styleHead1);
	$sheet->getStyle('A2:BT2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('A2:BT2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$sheet->getStyle('A2:BT2')->getAlignment()->setWrapText(true);


	// Display Corporation Logo
	$sheet->mergeCells($rCellRange);
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setPath($logo);
	$objDrawing->setHeight(80);
	$objDrawing->setWidth($logoWidth);
	$objDrawing->setCoordinates($logoPosition);
	$objDrawing->setOffsetX(1);
	$objDrawing->setWorksheet($objExcel->getActiveSheet());
	
	$userHeaderBlock =  " $corporation \n $userFirstName $userLastName \n $userAddress \n $userCity, $userState. $userZip \n $userPhone" ;
	
	$sheet->setCellValue('BA1',$userHeaderBlock);
	$sheet->getStyle('A1:BT1')->getAlignment()->setWrapText(true);
	$sheet->getStyle('AI1:BT1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$sheet->getStyle($rCellRange)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_BOTTOM);
	$sheet->getStyle('AI1:BT1')->getFont()->setSize(9);
	
	// Set bottom border to header row
	$sheet->getStyle('A1:BT1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$sheet->getHeaderFooter()->setOddFooter('Page &P / &N');
	$sheet->getHeaderFooter()->setEvenFooter('Page &P / &N');
	$sheet->getDefaultColumnDimension()->setWidth(1.8);


	//HEADER ROW1	
	$sheet->mergeCells('A3:D3');
	$sheet->mergeCells('E3:K3');
	$sheet->mergeCells('L3:R3');
	$sheet->mergeCells('S3:AB3');
	$sheet->mergeCells('AC3:AF3');
	$sheet->mergeCells('AG3:AL3');
	$sheet->mergeCells('AM3:AP3');
	$sheet->mergeCells('AQ3:AT3');
	$sheet->mergeCells('AU3:AW3');
	$sheet->mergeCells('AX3:BB3');
	$sheet->mergeCells('BC3:BF3');
	$sheet->mergeCells('BG3:BN3');
	$sheet->mergeCells('BO3:BT3');



	$sheet->setCellValue('A3', 'Net')
            ->setCellValue('E3', 'Program')
            ->setCellValue('L3', 'Episode')
            ->setCellValue('S3', 'Description')
            ->setCellValue('AC3', 'Day')
            ->setCellValue('AG3', 'Airdate')
            ->setCellValue('AM3', 'Starts')
            ->setCellValue('AQ3', 'Ends')
            ->setCellValue('AU3', 'Du')
            ->setCellValue('AX3', 'Sp/Ln')
            ->setCellValue('BC3', 'Status')
            ->setCellValue('BG3', 'Genre')
            ->setCellValue('BO3', 'Genre2');

	$sheet->getStyle('A3:BT4')->getFont()->setName('Candara');
	$sheet->getStyle('A3:BT3')->getFont()->setBold(true);
	$sheet->getStyle('A3:BT3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


	// Set columns heading with grey background
	$sheet->getRowDimension(3)->setRowHeight(25);
	$sheet->getStyle('A3:BT3')->applyFromArray($styleHead2);
	$sheet->getStyle('A3:BT3')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => '003366')));
	$sheet->getStyle('A3:BT3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('A3:BT3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$sheet->getStyle('A3:BT3')->getFont()->setSize(10);


	// FreezePane rows (Assign vertical scrolling from this column and row)
	$sheet->freezePane('A4');
	
	$row = 4;


	// Printing actual proposal data (Zone wise)
	foreach($arrProposal as $proposalValue) {
		
	    $zoneName = isset($proposalValue['zone']['zonename']) ? $proposalValue['zone']['zonename'] : '';
	    
	    $sheet->mergeCells('A'.$row.':BT'.$row.'');
	    $sheet->getStyle('A'.$row.':BT'.$row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	    $sheet->setCellValue('A'.$row,'Zone : '.$zoneName);
	    $sheet->getRowDimension($row)->setRowHeight(20);
	    $sheet->getStyle('A'.$row.':'.'BT'.$row)->applyFromArray(array('font'  => array('color' => array('rgb' => '000000'),'size'  => 8, 'bold'  => true, 'name'  => 'Arial')));
	    $sheet->getStyle('A'.$row.':'.'BT'.$row)->getBorders()->getBottom()->applyFromArray($styleBorderZones);
		
	    $zone_row 	= $row;
	    $fline 		= $row+1;
	    $lline 		= count($proposalValue['lines']) + $fline -1 ;
		
		$sheet->getRowDimension()->setRowHeight(-1);
		$sheet->getRowDimension(1)->setRowHeight(60);	
	
	

	    foreach($proposalValue['lines'] as  $zoneValue){
	
			$row ++;
			$sheet->mergeCells('A'.$row.':BT'.$row.'');
			$sheet->getRowDimension($row)->setRowHeight(10);

			$row++;
			$sheet->mergeCells('A'.$row.':D'.$row);
			$sheet->mergeCells('E'.$row.':K'.$row);
			$sheet->mergeCells('L'.$row.':R'.$row);
			$sheet->mergeCells('S'.$row.':AB'.$row);
			$sheet->mergeCells('AC'.$row.':AF'.$row);
			$sheet->mergeCells('AG'.$row.':AL'.$row);
			$sheet->mergeCells('AM'.$row.':AP'.$row);
			$sheet->mergeCells('AQ'.$row.':AT'.$row);
			$sheet->mergeCells('AU'.$row.':AW'.$row);
			$sheet->mergeCells('AX'.$row.':BB'.$row);
			$sheet->mergeCells('BC'.$row.':BF'.$row);
			$sheet->mergeCells('BG'.$row.':BN'.$row);
			$sheet->mergeCells('BO'.$row.':BT'.$row);

			//CALLSIGN
			$sheet->setCellValue('A'.$row, $zoneValue['callsign']);
			  
			//PROGRAM TITLE
			if(! is_array($zoneValue['title'])){
				$thesetitles = explode(',',$zoneValue['title']);
				
				if(count($thesetitles) > 5){
					$title = $thesetitles[0].', '.$thesetitles[1].', '.$thesetitles[2].', '.$thesetitles[3].', '.$thesetitles[4].', more…';
				}
				else{
					$title = $zoneValue['title'];
				}
			}
			else{
				$title = implode(', ', $zoneValue['title']);
			}
			
			$sheet->setCellValue('E'.$row, $title);
	
			//EPISODE
			if($zoneValue['epititle'] != ''){
				$sheet->setCellValue('L'.$row, $zoneValue['epititle']);
			}
	
			//DESCRIPTION
			$showDescription = $zoneValue['desc'];
			$sheet->setCellValue('S'.$row, $showDescription);
	
			//WEEK DAYS
	        $days 		= isset($zoneValue['dayFormat']) ? $zoneValue['dayFormat'] : '';
	        $dayPart 	= trim($days);
			$sheet->setCellValue('AC'.$row, $dayPart);
	
	
			//AIR DATE
	       $sheet->setCellValue('AG'.$row, date('n-j-y',strtotime($zoneValue['startdate'])));
	
			//START TIME
	       $sheet->setCellValue('AM'.$row,str_replace('M','',str_replace(':00','',date ('g:iA',strtotime($zoneValue['starttime'])))) );
	        
			//END TIME
	       $sheet->setCellValue('AQ'.$row,str_replace('M','',str_replace(':00','',date ('g:iA',strtotime($zoneValue['endtime'])))) );
	
			//DURATION
			if($zoneValue['linetype'] == 'Fixed'){
				$to_time 	= strtotime($zoneValue['enddatetime']);
				$from_time 	= strtotime($zoneValue['startdatetime']);
				if($to_time < $from_time){
					$to_time = strtotime($zoneValue['enddate'].' 23:59:59'); 
				}
				$duration 	= round(abs($to_time - $from_time) / 60,0);			
			}
			else{
				$to_time 	= strtotime($zoneValue['endtime']);
				$from_time 	= strtotime($zoneValue['starttime']);
				$duration 	= round(abs($to_time - $from_time) / 60,2);			
			}
	       $sheet->setCellValue('AU'.$row, $duration);

			//SPOT DURATION
			$spotLength = $zoneValue['spotLength'] .'s';
			$sheet->setCellValue('AX'.$row, $spotLength);


	        
			//AIR STYLE
			$objRichText = new PHPExcel_RichText();
	
			//MOVIE PREMIERES FLAG
			$premieres = $zoneValue['premiere'];
			
			if($premieres != '' && $premieres != 'pNew'){
				if($premieres == 'Premiere'){
				  	$premieres = "Movie Premiere";
				}
				$objCellPremiere = $objRichText->createTextRun($premieres);
				$objCellPremiere->getFont()->setName('Arial')->setSize(8)->setItalic(true)->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_RED ));
			}
			
			//LIVE EVENTS FLAG
			elseif($zoneValue['live'] == 'Live'){
				$objCellLive = $objRichText->createTextRun('Live');
				$objCellLive->getFont()->setName('Arial')->setSize(8)->setColor( new PHPExcel_Style_Color( '660066'));		  
			}
	
			//NEW EVENTS FLAG
			elseif(($zoneValue['premiere'] == '' && $zoneValue['isnew'] == 'New' && $includenew != 'false') || $zoneValue['premiere'] == 'pNew'){
				$objCellNew = $objRichText->createTextRun('New');
				$objCellNew->getFont()->setName('Arial')->setSize(8)->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_DARKGREEN ));		  
			}
			
			$sheet->setCellValue('BC'.$row, $objRichText);
	        
			//GENRE
			$sheet->setCellValue('BG'.$row,$zoneValue['genre']);
	        
			//GENRE 2
			if(isset($zoneValue['genre2'])){
				$sheet->setCellValue('BO'.$row,$zoneValue['genre2']); 
			}



			$sheet->getStyle('A'.$row.':'.'BT'.$row)->applyFromArray($styleRows);
			$sheet->getStyle('A'.$row.':'.'BT'.$row)->getBorders()->getBottom()->applyFromArray($styleBorderLines);
			$sheet->getStyle('A'.$row.':'.'BT'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A'.$row.':'.'BT'.$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$sheet->getStyle('E'.$row.':AB'.$row)->getAlignment()->setWrapText(true)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);	
			$sheet->getStyle('AC'.$row.':AC'.$row)->getAlignment()->setWrapText(true);
			$sheet->getStyle('AG'.$row.':AG'.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DMYMINUS);
			$sheet->getStyle('AY'.$row.':BT'.$row)->getAlignment()->setWrapText(true);
			$sheet->getStyle('L'.$row.':'.'L'.$row)->applyFromArray($epititleStyling);
			$sheet->getStyle('A'.$row)->applyFromArray($styleThinLeftGrey);



			// DETERMINE ROW HEIGHT, AUTO SIZER IS NOT DOING ITS JOB
			
			
			$titleSize = strlen($title) ;
			
			if($titleSize != 0) {
			
				if($titleSize >= 0){ $descRowHeight = 60; }
			
				if($titleSize >= 80){ $descRowHeight = 100; }
			
				if($titleSize >= 200){ $descRowHeight = 145; }
			
				$sheet->getRowDimension($row)->setRowHeight($descRowHeight);
			}


			$descSize = strlen($showDescription) ;

			if ($descSize != 0){
			
				if($descSize >= 0){ $descRowHeight = 50; }
				
				if($descSize >= 80){ $descRowHeight = 80; }
				
				if($descSize >= 200){ $descRowHeight = 125; }
				
				$sheet->getRowDimension($row)->setRowHeight($descRowHeight);
			
			}


		$row++;


		//demo 1
		$demographicTitle1 = $arrRatingsSettings['demographics'][0];
		$sheet->mergeCells('A'.$row.':X'.$row);
		$sheet->setCellValue('A'.$row, $demographicTitle1);

		//demo 2
		if(isset($arrRatingsSettings['demographics'][1])){
			$demographicTitle2 = $arrRatingsSettings['demographics'][1];
			$sheet->mergeCells('Y'.$row.':AV'.$row);
			$sheet->setCellValue('Y'.$row, $demographicTitle2);
		}

		//demo 3
		if(isset($arrRatingsSettings['demographics'][2])){
			$demographicTitle3 = $arrRatingsSettings['demographics'][2];
			$sheet->mergeCells('AW'.$row.':BT'.$row);
			$sheet->setCellValue('AW'.$row, $demographicTitle3);
		}


		$sheet->getStyle('A'.$row.':BT'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('A'.$row.':BT'.$row)->getFont()->setSize(9)->setBold(true);
		$sheet->getStyle('A'.$row.':BT'.$row)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => 'f2f2f2')));


		if($cols == 5){
			$sheet->getStyle('A'.$row.':Y'.$row)->applyFromArray(array('borders' => array('right' => array('style' => 
				PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '999999'),))));
			
			if(isset($arrRatingsSettings['demographics'][1])){
				$sheet->getStyle('Y'.$row.':AV'.$row)->applyFromArray(array('borders' => array('right' => array('style' => 
				PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '999999'),))));
			}
		}	
		else{
			$sheet->getStyle('A'.$row.':X'.$row)->applyFromArray(array('borders' => array('right' => array('style' => 
				PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '999999'),))));
			
			if(isset($arrRatingsSettings['demographics'][1])){
				$sheet->getStyle('Y'.$row.':AV'.$row)->applyFromArray(array('borders' => array('right' => array('style' => 
				PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '999999'),))));
			}
		}


		$sheet->getStyle('A'.$row)->applyFromArray($styleThinLeftGrey);


		$row++;
		$startPos 	= 1;
		$endNum 	= $startPos + $colSize -1 ;
				
		foreach($arrRatingsSettings['demographics']  as  $demoTitles){

			foreach($zoneValue['rating'] as  $ratingLine){
	
				if( $demoTitles == $ratingLine['demo']){

					for ($x = 0; $x < $cols; $x++) {
						$mcString 	= $alphaCols[$startPos].$row.":".$alphaCols[$endNum].$row;
						$sheet->mergeCells($mcString);
						$sheet->setCellValue($alphaCols[$startPos].$row, $columnTitles[$x]) ;
						$startPos 	+= $colSize ;			
						$endNum 	+= $colSize ;

					} 

				} 

			}
		}

		$sheet->getStyle('A'.$row.':BT'.$row)->getFont()->setSize(9);
		$sheet->getStyle('A'.$row.':BT'.$row)->getFont()->setBold(true);
		$sheet->getStyle('A'.$row.':BT'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('A'.$row.':BT'.$row)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => 'f2f2f2')));
		$sheet->getStyle('A'.$row)->applyFromArray($styleThinLeftGrey);


		if($cols == 5){
			$sheet->getStyle('A'.$row.':Y'.$row)->applyFromArray(array('borders' => array('right' => array('style' => 
				PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '999999'),))));
			
			if(isset($arrRatingsSettings['demographics'][1])){
				$sheet->getStyle('Y'.$row.':AV'.$row)->applyFromArray(array('borders' => array('right' => array('style' => 
				PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '999999'),))));
			}
		}	
		else{
			$sheet->getStyle('A'.$row.':X'.$row)->applyFromArray(array('borders' => array('right' => array('style' => 
				PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '999999'),))));
			
			if(isset($arrRatingsSettings['demographics'][1])){
				$sheet->getStyle('Y'.$row.':AV'.$row)->applyFromArray(array('borders' => array('right' => array('style' => 
				PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '999999'),))));
			}
		}

	        
		$row++;
		$startPos 	= 1;
		$endNum 	= $startPos + $colSize -1 ;
		
		foreach($arrRatingsSettings['demographics']  as  $demoTitles){
							
			foreach($zoneValue['rating'] as  $ratingLine){
	
				if( $demoTitles == $ratingLine['demo']){
					for ($x = 0; $x < $cols; $x++){
						$mcString = $alphaCols[$startPos].$row.":".$alphaCols[$endNum].$row;
						$sheet->mergeCells($mcString);
						$sheet->setCellValue($alphaCols[$startPos].$row, $ratingLine[$ratingsHolder[$x]]);
						$startPos 	+= $colSize;
						$endNum 	+= $colSize;
					} 
				}
			}

		}
		
		$sheet->getStyle('A'.$row.':BT'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$sheet->getStyle('A'.$row.':BT'.$row)->getFont()->setSize(7);
		$sheet->getStyle('A'.$row.':BT'.$row)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => 'f2f2f2')));
		$sheet->getStyle('A'.$row)->applyFromArray($styleThinLeftGrey);
		$sheet->getStyle('A'.$row.':BT'.$row)->applyFromArray($styleThinBottomGrey);

		if($cols == 5){
			$sheet->getStyle('A'.$row.':Y'.$row)->applyFromArray(array('borders' => array('right' => array('style' => 
				PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '999999'),))));
			
			if(isset($arrRatingsSettings['demographics'][1])){
				$sheet->getStyle('Y'.$row.':AV'.$row)->applyFromArray(array('borders' => array('right' => array('style' => 
				PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '999999'),))));
			}
		}	
		else{
			$sheet->getStyle('A'.$row.':X'.$row)->applyFromArray(array('borders' => array('right' => array('style' => 
				PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '999999'),))));
			
			if(isset($arrRatingsSettings['demographics'][1])){
				$sheet->getStyle('Y'.$row.':AV'.$row)->applyFromArray(array('borders' => array('right' => array('style' => 
				PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '999999'),))));
			}
		}
	}

	    $row ++;

	}




		$row ++; $row ++; $row ++; 





		//demo 1 & header 
		$sheet->mergeCells('A'.$row.':R'.$row);
		$sheet->setCellValue('A'.$row, 'Ratings Totals');

		$demographicTitle1 = $arrRatingsSettings['demographics'][0];
		$sheet->mergeCells('S'.$row.':AJ'.$row);
		$sheet->setCellValue('S'.$row, $demographicTitle1);
		$sheet->getStyle('A'.$row.':BT'.$row)->getFont()->setSize(9);
		$sheet->getStyle('A'.$row.':BT'.$row)->getFont()->setBold(true);
		$sheet->getStyle('A'.$row.':BT'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('A'.$row.':BT'.$row)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => '003366')));
		$sheet->getStyle('A'.$row.':'.'BT'.$row)->applyFromArray(array('font'  => array('color' => array('rgb' => 'FFFFFF'),'size'  => 8, 'bold'  => true, 'name'  => 'Arial')));


		//demo 2
		if(isset($arrRatingsSettings['demographics'][1])){
			$demographicTitle2 = $arrRatingsSettings['demographics'][1];
			$sheet->mergeCells('AK'.$row.':BB'.$row);
			$sheet->setCellValue('AK'.$row, $demographicTitle2);
		}

		//demo 3
		if(isset($arrRatingsSettings['demographics'][2])){
			$demographicTitle3 = $arrRatingsSettings['demographics'][2];
			$sheet->mergeCells('BC'.$row.':BT'.$row);
			$sheet->setCellValue('BC'.$row, $demographicTitle3);
		}

		$row ++;


		$startPos 	= 19;
		$endNum 	= $startPos + $colSize -1 ;
		
		
		foreach($arrRatingsTotals['zonesTotals'] as $zonesTotals) {
			
			$sheet->mergeCells('A'.$row.':R'.$row);
			$sheet->setCellValue('A'.$row, 'Zone');	
			$sheet->getStyle('A'.$row.':BT'.$row)->getFont()->setSize(9);
			$sheet->getStyle('A'.$row.':BT'.$row)->getFont()->setBold(true);
			$sheet->getStyle('A'.$row.':BT'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			foreach($arrRatingsSettings['demographics']  as  $demoTitles){

				foreach($zonesTotals['zoneTotals']  as  $zoneTotal){
					if( $demoTitles == $zoneTotal['demo'] ) {
						for ($x = 0; $x < 6; $x++) {
					if($startPos > 78)$startPos=78; 
					if($endNum > 78)$endNum=78;
							$mcString = $alphaCols[$startPos].$row.":".$alphaCols[$endNum].$row;
							$sheet->mergeCells($mcString);
							$sheet->setCellValue($alphaCols[$startPos].$row, $columnTotalTitles[$x]);
							$startPos += $colSize ;
							$endNum += $colSize;
						}
					}
				}
			}
		}

		$row ++;

		$startPos 	= 19;
		$endNum 	= $startPos + $colSize -1 ;


		foreach($arrRatingsTotals['zonesTotals'] as $zonesTotals) {
			
			$sheet->mergeCells('A'.$row.':R'.$row);
			$sheet->setCellValue('A'.$row, $zonesTotals['zoneName']);
			$sheet->getStyle('A'.$row.':R'.$row)->getFont()->setBold(true);
			$sheet->getStyle('A'.$row.':BT'.$row)->getFont()->setSize(7);
			$sheet->getStyle('A'.$row.':BT'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			foreach($arrRatingsSettings['demographics']  as  $demoTitles){
					foreach($zonesTotals['zoneTotals']  as  $zoneTotal){				
						if( $demoTitles == $zoneTotal['demo']   ) {

							for ($x = 0; $x < 6; $x++) {
					if($startPos > 78)$startPos=78; 
					if($endNum > 78)$endNum=78;
								$mcString = $alphaCols[$startPos].$row.":".$alphaCols[$endNum].$row;
								$sheet->mergeCells($mcString);
								$sheet->setCellValue($alphaCols[$startPos].$row, $zoneTotal[$TotalArrayKeys[$x]]);
								$startPos += $colSize ;
								$endNum += $colSize ;
							}
						}

					}
			}
		}



		$row ++;$row ++;$row ++;
		
		
		//FOOTER INFO AND DISCLAIMER
		
						$sheet->mergeCells('A'.$row.':AS'.$row);

						$objRichText = new PHPExcel_RichText();
						$run1 = $objRichText->createTextRun(utf8_encode('All reporting results are prepared using ShowSeeker'.chr(174).' MediaMath'));

						$run1->getFont()->applyFromArray(array( "size" => 8, "name" => "Calibri", "color" => array("rgb" => "000000")));

						$run2 = $objRichText->createTextRun('TM');
						$run2->getFont()->applyFromArray(array( "size" => 6, "name" => "Calibri", "color" => array("rgb" => "000000")));
						$run2->getFont()->setSuperScript(true);

						$run3 = $objRichText->createTextRun(' research.');
						$run3->getFont()->applyFromArray(array( "size" => 8, "name" => "Calibri", "color" => array("rgb" => "000000")));
						
						$sheet->setCellValue('A'.$row, $objRichText);
		$row ++;
		
						$sheet->mergeCells('A'.$row.':AS'.$row);
						$run4 = utf8_encode('ShowSeeker MediaMath and report designs Copyright '.chr(169) . date("Y").' Visual Advertising Sales Technology, Grass Valley, CA.'); 
						$sheet->setCellValue('A'.$row, $run4);
						$sheet->getStyle('A'.$row.':AM'.$row)->getFont()->setSize(8);
		$row ++;
		
						$sheet->mergeCells('A'.$row.':AS'.$row);
						$run5 = utf8_encode('Nielsen Audience Estimates Copyright '.chr(169) . date("Y").' The Nielsen Company'.chr(174) ) ; 
						$sheet->setCellValue('A'.$row, $run5);
						$sheet->getStyle('A'.$row.':AM'.$row)->getFont()->setSize(8);
		
		$row ++;$row ++;
		
						$sheet->mergeCells('A'.$row.':AS'.$row);
						$sheet->setCellValue('A'.$row, 'Adjustments: Network Insertability without Network Carriage has been factored into calculations.');
						$sheet->getStyle('A'.$row.':AM'.$row)->getFont()->setSize(8);
		$row ++;
		
						$sheet->mergeCells('A'.$row.':AS'.$row);
						$sheet->setCellValue('A'.$row, $arrRatingsSettings['survey'] . ' ratings are based on survey populations.');
						$sheet->getStyle('A'.$row.':AM'.$row)->getFont()->setSize(8);
		$row ++;
						$sheet->mergeCells('A'.$row.':AS'.$row);
						$sheet->setCellValue('A'.$row, 'Source: '.$arrRatingsSettings['survey']);
						$sheet->getStyle('A'.$row.':AM'.$row)->getFont()->setSize(8);
		
		
		$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4)->setFitToPage(true);




	// Create and Save file
	$objWriter 		= new PHPExcel_Writer_Excel2007($objExcel);
	$pslFileName 	= cleanStr($proposalinfo);
	$s3FileName	 	= $pslFileName.'_'.$cDate.'_Detail.xlsx';
	$s3FilePath 	= "tmp/$s3FileName";
	$objWriter->save($s3FilePath);
	$s3Type 		= "xlsspec";
	$s3UserId 		= $userid;



	
	
	if(checkS3()){
		//upload the file and get the full path
		$s3filePath = uploadToS3($s3FilePath,$s3FileName,$s3Type,$s3UserId);
	
		//unlink the local file
		unlink($s3FilePath); 
	
		print json_encode(array('filename'=>$s3filePath));

	}
	else{
		print json_encode(array('filename'=>'https://godownload.showseeker.com/'.$s3FilePath));		
	}
	
	
	


	function letterNumber($startLetter) {
	
		$alphabet =   ('0, A, B, C, D, E, F, G, H, I, J, K, L, M, N, O, P, Q, R, S, T, U, V, W, X, Y, Z, 
		AA, AB, AC, AD, AE, AF, AG, AH, AI, AJ, AK, AL, AM, AN, AO, AP, AQ, AR, AS, AT, AU, AV, AW, AX, AY, AZ, 
		BA, BB, BC, BD, BE, BF, BG, BH, BI, BJ, BK, BL, BM, BN, BO, BP, BQ, BR, BS, BT, BU, BV, BW, BX, BY, BZ');
		$alphaCols 		= explode(', ',$alphabet);
		$letterNumber 	= array_search($startLetter, $alphaCols);
		return $letterNumber;
	
	}

	//FORMATTING FILE NAME	
	function cleanStr($string) {
   		$string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.
	   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}


	function checkS3(){
		$r 	= true;
		$host = 'showseeker.s3.amazonaws.com';

		if($socket =@ fsockopen($host, 80, $errno, $errstr, 30)) {
			fclose($socket);
		} 
		else {
			$r = false;
		}		
		return $r;
	}


?>



