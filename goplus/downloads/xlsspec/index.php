<?php
	
ini_set('max_execution_time','0');
ini_set('memory_limit','1024M');
set_time_limit(480);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

$styleRows = array('font'  => array('color' => array('rgb' => '000000'),'size'  => 8, 'name'  => 'Arial'));

// create PHPExcel object
$objExcel 		= new PHPExcel();


// Set document properities
$objExcel->getProperties()->setCreator("Excel Export")
         ->setLastModifiedBy("VAST")
         ->setTitle("ShowSeeker")
         ->setSubject("Report")
         ->setDescription("Proposal XLSX");


// page settings
$sheet = $objExcel->getActiveSheet();

//Centering Page Contents
$sheet->getStyle( $sheet->calculateWorksheetDimension() )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 

$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER);
$sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 2);

$pageMargins = $sheet->getPageMargins();
$margin = 0.6 / 2.54;

$pageMargins->setTop($margin);
$pageMargins->setBottom($margin);
$pageMargins->setLeft($margin);
$pageMargins->setRight($margin);

// Set active sheet index to the first sheet
$objExcel->setActiveSheetIndex(0);


// Assing style to header text
$objRichText = new PHPExcel_RichText();

$logo = trim($proposalLines['corporation'][0]['logo']);

$networklogo = explode('/', $logo);
$nlogo = $networklogo[sizeof($networklogo)-1];
$imgPath = str_replace('downloads','',getcwd());
$logo = $imgPath.'i/networklogos/'.$nlogo;  


$proposalinfo = trim($proposalLines['proposalinfo']['name']);


$sheet->mergeCells('A1:F1');
$sheet->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:F1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->setCellValue('A1',$proposalinfo);
$sheet->getStyle('A1')->applyFromArray($styleHead1);
$sheet->getStyle('A1')->getAlignment()->setWrapText(true);


// Display Corporation Logo
$sheet->mergeCells('G1:L1');
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setPath($logo);
$objDrawing->setHeight(80);
$objDrawing->setWidth(180);
$objDrawing->setCoordinates('G1');
$objDrawing->setOffsetX(1);
$objDrawing->setWorksheet($objExcel->getActiveSheet());


$sheet->getStyle('G1:L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$sheet->getStyle('G1:L1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_BOTTOM);
//$sheet->setCellValue('G1', ucwords (strtolower($arrProposal[0]['zone']['timezonename'] )).' Time');
$sheet->getStyle('G1')->applyFromArray($styleTZ);


// Set bottom border to header row
$sheet->getStyle('A1:L1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);



// SET COLUMN WIDTHS
$sheet->getColumnDimension('B')->setWidth(21);			
$sheet->getColumnDimension('C')->setWidth(21);
$sheet->getColumnDimension('D')->setWidth(25);
$sheet->getColumnDimension('E')->setWidth(5);
$sheet->getColumnDimension('F')->setWidth(8);
$sheet->getColumnDimension('G')->setWidth(6);
$sheet->getColumnDimension('H')->setWidth(6);		
$sheet->getColumnDimension('I')->setWidth(5);		
$sheet->getColumnDimension('J')->setWidth(10);
$sheet->getColumnDimension('K')->setWidth(10);			
$sheet->getColumnDimension('L')->setWidth(10);


$sheet->getHeaderFooter()->setOddFooter('Page &P / &N');
$sheet->getHeaderFooter()->setEvenFooter('Page &P / &N');


// Set columns heading with grey background
$sheet->getRowDimension(2)->setRowHeight(25);
$sheet->getStyle('A2:L2')->applyFromArray($styleHead2);
$sheet->getStyle('A2:L2')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => '003366')));
$sheet->getStyle('A2:L2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A2:L2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


	
$sheet->setCellValue('A2','Net');
$sheet->setCellValue('B2','Program');
$sheet->setCellValue('C2','Episode');
$sheet->setCellValue('D2','Description');
$sheet->setCellValue('E2','Day');
$sheet->setCellValue('F2','Airdate');
$sheet->setCellValue('G2',"Starts");
$sheet->setCellValue('H2','Ends');
$sheet->setCellValue('I2','Du');
$sheet->setCellValue('J2',"Status");
$sheet->setCellValue('K2','Genre');
$sheet->setCellValue('L2','Genre2');

$sheet->getColumnDimension('A')->setWidth(7);

// FreezePane rows (Assign vertical scrolling from this column and row)
$sheet->freezePane('A3');

$row = 3;


	// Printing actual proposal data (Zone wise)
	foreach($arrProposal as $proposalValue) {
		
	    $zoneName = isset($proposalValue['zone']['zonename']) ? $proposalValue['zone']['zonename'] : '';
	    
	    $sheet->mergeCells('A'.$row.':L'.$row.'');
	    $sheet->getStyle('A'.$row.':L'.$row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	    $sheet->setCellValue('A'.$row,'Zone : '.$zoneName);
	    $sheet->getRowDimension($row)->setRowHeight(20);
	    $sheet->getStyle('A'.$row.':'.'L'.$row)->applyFromArray(array('font'  => array('color' => array('rgb' => '000000'),'size'  => 8, 'bold'  => true, 'name'  => 'Arial')));
	    $sheet->getStyle('A'.$row.':'.'L'.$row)->getBorders()->getBottom()->applyFromArray($styleBorderZones);
		
	    $zone_row 	= $row;
	    $fline 		= $row+1;
	    $lline 		= count($proposalValue['lines']) + $fline -1 ;
		
		$sheet->getRowDimension()->setRowHeight(-1);
		$sheet->getRowDimension(1)->setRowHeight(60);
		$sheet->getStyle('A'.$fline.':'.'L'.$lline)->applyFromArray($styleRows);
		$sheet->getStyle('A'.$fline.':'.'L'.$lline)->getBorders()->getBottom()->applyFromArray($styleBorderLines);
		$sheet->getStyle('A'.$fline.':'.'L'.$lline)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('A'.$fline.':'.'L'.$lline)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheet->getStyle('B'.$fline.':D'.$lline)->getAlignment()->setWrapText(true)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);	
		$sheet->getStyle('E'.$fline.':E'.$lline)->getAlignment()->setWrapText(true);
		$sheet->getStyle('F'.$fline.':F'.$lline)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DMYMINUS);
		$sheet->getStyle('J'.$fline.':L'.$lline)->getAlignment()->setWrapText(true);
		$sheet->getStyle('C'.$fline.':'.'C'.$lline)->applyFromArray($epititleStyling); 	
	
	
	    foreach($proposalValue['lines'] as  $zoneValue){
		    
	       $row++;
	
			//CALLSIGN
			$sheet->setCellValue('A'.$row, $zoneValue['callsign']);
			  
			//PROGRAM TITLE
			$title 			= $zoneValue['title'];
			$thesetitles	= explode(',',$title);
			if(count($thesetitles) > 5){
				$title = $thesetitles[0].', '.$thesetitles[1].', '.$thesetitles[2].', '.$thesetitles[3].', '.$thesetitles[4].', more…';
			} 		  	  
			$sheet->setCellValue('B'.$row, $title);
	
			//EPISODE
			if($zoneValue['epititle'] != ''){
				$sheet->setCellValue('C'.$row, $zoneValue['epititle']);
			}
	
			//DESCRIPTION
			$showDescription = $zoneValue['desc'];
			$sheet->setCellValue('D'.$row, $showDescription);
	
			//WEEK DAYS
	        $days 		= isset($zoneValue['dayFormat']) ? $zoneValue['dayFormat'] : '';
	        $dayPart 	= trim($days);
			$sheet->setCellValue('E'.$row, $dayPart);
	
	
			//AIR DATE
	       $sheet->setCellValue('F'.$row, date('n-j-y',strtotime($zoneValue['startdate'])));
	
			//START TIME
	       $sheet->setCellValue('G'.$row,str_replace('M','',str_replace(':00','',date ('g:iA',strtotime($zoneValue['starttime'])))) );
	        
			//END TIME
	       $sheet->setCellValue('H'.$row,str_replace('M','',str_replace(':00','',date ('g:iA',strtotime($zoneValue['endtime'])))) );
	
			//DURATION
			if($zoneValue['linetype'] == 'Fixed'){
				$to_time 	= strtotime($zoneValue['enddatetime']);
				$from_time 	= strtotime($zoneValue['startdatetime']);
				$duration 	= round(abs($to_time - $from_time) / 60,2);			
			}
			else{
				$to_time 	= strtotime($zoneValue['endtime']);
				$from_time 	= strtotime($zoneValue['starttime']);
				$duration 	= round(abs($to_time - $from_time) / 60,2);			
			}
	       $sheet->setCellValue('I'.$row, $duration);
	        
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
			
			$sheet->setCellValue('J'.$row, $objRichText);
	        
	        //GENRE
			$sheet->setCellValue('K'.$row,$zoneValue['genre']);
	        
			//GENRE 2
			if(isset($zoneValue['genre2'])){
				$sheet->setCellValue('L'.$row,$zoneValue['genre2']); 
			}
	        
	    }
	    $row ++;
	}


	// Create and Save file
	$objWriter 		= new PHPExcel_Writer_Excel2007($objExcel);
	$pslFileName 	= cleanStr($proposalinfo);
	$s3FileName	 	= $pslFileName.'.xlsx';
	$s3FilePath 	= "/var/www/html/www.showseeker.com/goplus/downloads/xmls/$s3FileName";


	$objWriter->save($s3FilePath);
	$s3Type 		= "xlsspec";
	$s3UserId 		= intval($userid);
	
	//upload the file and get the full path
	$s3filePath = uploadToS3($s3FilePath,$s3FileName,$s3Type,$s3UserId);
	
	//unlink the local file
	//unlink($s3FilePath); 
	
	print json_encode($s3filePath);
	
	exit;		

?>
