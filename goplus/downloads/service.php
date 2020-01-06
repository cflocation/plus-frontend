<?php
ini_set('max_execution_time','0');
ini_set("display_errors",1);
require_once 'Classes/PHPExcel.php';
require_once 'service_data.php';

$styleHead2 = array(
        'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => 'FFFFFF'),
                'size'  => 11
));

// decode proposal data
$proposalLines = json_decode($json_data,true);

// decoding proposal array
$arrProposal = $proposalLines['proposal'];

// create PHPExcel object
$objExcel = new PHPExcel();

// Set document properities
$objExcel->getProperties()->setCreator("Service Report")
        ->setLastModifiedBy("Service User")
        ->setTitle("ShowSeeker")
        ->setSubject("Report")
        ->setDescription("Testing")
        ->setKeywords("Office 5 Excel")
        ->setCategory("Test Category");

$objExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$objExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$objExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
$objExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
$objExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);

// Set active sheet index to the first sheet
$objExcel->setActiveSheetIndex(0);
$objExcel->getActiveSheet()->getProtection()->setSheet(true);
$start_row = 1;
$end_row = 8;
foreach($arrProposal as $proposalKey => $proposalValue) {
    $end_row = $end_row + 1;
    foreach($proposalValue['lines'] as $zoneKey => $zoneValue){
        $end_row = $end_row + 1;
    }
    $end_row = $end_row + 2;
}
$objExcel->getActiveSheet()->getStyle('A'.$start_row.':AZ'.$end_row.'')->getProtection()->setLocked( PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);

// Display heading of excel report
// First Row
$objExcel->getActiveSheet()->getStyle('A1:AB1')->applyFromArray(array('font'  => array('bold'  => true)));
$objExcel->getActiveSheet()->mergeCells('A1:K1');
$objExcel->getActiveSheet()->setCellValue('A1',isset($proposalLines['proposalinfo']['name']) ? trim($proposalLines['proposalinfo']['name']) : '');
$objExcel->getActiveSheet()->mergeCells('L1:N1');
$objExcel->getActiveSheet()->setCellValue('L1','Flight Dates');
$objExcel->getActiveSheet()->setCellValue('O1','Start');
$objExcel->getActiveSheet()->setCellValue('S1','End');
$objExcel->getActiveSheet()->setCellValue('AB1',isset($proposalLines['corporation']['name']) ? trim($proposalLines['corporation']['name']) : '');

// Second Row
$objExcel->getActiveSheet()->getStyle('A2:AB2')->applyFromArray(array('font'  => array('bold'  => true)));
$objExcel->getActiveSheet()->getStyle('A2:AB2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objExcel->getActiveSheet()->mergeCells('A2:K2');
$objExcel->getActiveSheet()->setCellValue('A2','Order');
$objExcel->getActiveSheet()->mergeCells('L2:T2');
$objExcel->getActiveSheet()->setCellValue('L2','Billing');
$objExcel->getActiveSheet()->mergeCells('U2:AB2');
$objExcel->getActiveSheet()->setCellValue('U2','Totals');

// Third Row
$objExcel->getActiveSheet()->mergeCells('A3:B3');
$objExcel->getActiveSheet()->setCellValue('A3','AE');
$objExcel->getActiveSheet()->mergeCells('C3:K3');
$objExcel->getActiveSheet()->setCellValue('C3',$proposalLines['user'][0]['firstname'].' '.$proposalLines['user'][0]['lastname']);
$objExcel->getActiveSheet()->setCellValue('L3','Address');
$objExcel->getActiveSheet()->mergeCells('M3:T3');
$objExcel->getActiveSheet()->mergeCells('U3:V3');
$objExcel->getActiveSheet()->setCellValue('U3','Total Spots');
$objExcel->getActiveSheet()->mergeCells('W3:X3');
$objExcel->getActiveSheet()->setCellValue('W3','');
$objExcel->getActiveSheet()->mergeCells('Y3:Z3');
$objExcel->getActiveSheet()->setCellValue('Y3','Gross $');

// Fourth Row
$objExcel->getActiveSheet()->mergeCells('A4:B4');
$objExcel->getActiveSheet()->setCellValue('A4','Agency');
$objExcel->getActiveSheet()->mergeCells('C4:K4');
$objExcel->getActiveSheet()->setCellValue('L4','City');
$objExcel->getActiveSheet()->mergeCells('M4:P4');
$objExcel->getActiveSheet()->setCellValue('Q4','State');
$objExcel->getActiveSheet()->setCellValue('S4','Zip');
$objExcel->getActiveSheet()->mergeCells('U4:V4');
$objExcel->getActiveSheet()->setCellValue('U4','Commission');
$objExcel->getActiveSheet()->mergeCells('W4:X4');
$objExcel->getActiveSheet()->mergeCells('Y4:Z4');
$objExcel->getActiveSheet()->mergeCells('AA4:AB4');

// Fifth Row
$objExcel->getActiveSheet()->mergeCells('A5:B5');
$objExcel->getActiveSheet()->setCellValue('A5','Client');
$objExcel->getActiveSheet()->mergeCells('C5:K5');
$objExcel->getActiveSheet()->setCellValue('L5','Contact');
$objExcel->getActiveSheet()->mergeCells('M5:P5');
$objExcel->getActiveSheet()->setCellValue('Q5','Phone');
$objExcel->getActiveSheet()->mergeCells('R5:T5');
$objExcel->getActiveSheet()->mergeCells('U5:V5');
$objExcel->getActiveSheet()->mergeCells('W5:X5');
$objExcel->getActiveSheet()->mergeCells('Y5:Z5');
$objExcel->getActiveSheet()->mergeCells('AA5:AB5');

// Sixth Row
$objExcel->getActiveSheet()->mergeCells('A6:B6');
$objExcel->getActiveSheet()->setCellValue('A6','RepFirm');
$objExcel->getActiveSheet()->mergeCells('C6:K6');
$objExcel->getActiveSheet()->mergeCells('M6:P6');
$objExcel->getActiveSheet()->mergeCells('R6:T6');
$objExcel->getActiveSheet()->mergeCells('U6:V6');
$objExcel->getActiveSheet()->mergeCells('W6:X6');
$objExcel->getActiveSheet()->mergeCells('Y6:Z6');
$objExcel->getActiveSheet()->setCellValue('Y6','Net $');

// decode weeks array
$arrWeeks = array();
$arrWeeks = $proposalLines['weeks'];

// Set headers of listing
// Seventh Row
for ($col = 65; $col <= 84; $col++) {
    $objExcel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
}
$objExcel->getActiveSheet()->getRowDimension(7)->setRowHeight(25);
$objExcel->getActiveSheet()->getStyle('A7:AJ7')->applyFromArray($styleHead2);
$objExcel->getActiveSheet()->getStyle('A7:AJ7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objExcel->getActiveSheet()->getStyle('A7:AJ7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
if(84+count($arrWeeks)>90){
    $afterZ = (84+count($arrWeeks))-90;
    $upToCell = 64+$afterZ;
    $bg_width = 'A7:A'.chr($upToCell).'7';
}else{
$bg_width = 'A7:'.chr(84+count($arrWeeks)).'7';
}

$objExcel->getActiveSheet()->getStyle($bg_width)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => '000000')));
$objExcel->getActiveSheet()->setCellValue('A7','#');
$objExcel->getActiveSheet()->setCellValue('B7','Network');
$objExcel->getActiveSheet()->mergeCells('C7:D7');
$objExcel->getActiveSheet()->setCellValue('C7','Show');
$objExcel->getActiveSheet()->setCellValue('E7','M');
$objExcel->getActiveSheet()->setCellValue('F7','T');
$objExcel->getActiveSheet()->setCellValue('G7','W');
$objExcel->getActiveSheet()->setCellValue('H7','Th');
$objExcel->getActiveSheet()->setCellValue('I7','F');
$objExcel->getActiveSheet()->setCellValue('J7','S');
$objExcel->getActiveSheet()->setCellValue('K7','S');
$objExcel->getActiveSheet()->setCellValue('L7','Start');
$objExcel->getActiveSheet()->setCellValue('M7','End');
$objExcel->getActiveSheet()->setCellValue('N7','From');
$objExcel->getActiveSheet()->setCellValue('O7','To');
$objExcel->getActiveSheet()->setCellValue('P7','Wks');
$objExcel->getActiveSheet()->setCellValue('Q7',"Spots\nWk");
$objExcel->getActiveSheet()->getStyle('Q7')->getAlignment()->setWrapText(true);
$objExcel->getActiveSheet()->getStyle('Q7')->applyFromArray(array('font'  => array('size'  => 8)));
$objExcel->getActiveSheet()->setCellValue('R7',"Rate");
$objExcel->getActiveSheet()->setCellValue('S7',"Total\nSpots");
$objExcel->getActiveSheet()->getStyle('S7')->getAlignment()->setWrapText(true);
$objExcel->getActiveSheet()->getStyle('S7')->applyFromArray(array('font'  => array('size'  => 8)));
$objExcel->getActiveSheet()->setCellValue('T7',"Total\nAmount ");
$objExcel->getActiveSheet()->getStyle('T7')->getAlignment()->setWrapText(true);
$objExcel->getActiveSheet()->getStyle('T7')->applyFromArray(array('font'  => array('size'  => 8)));

// Set number of weeks listing heading
$w = 1;
$char = 85;  // Ascii value of U
$col = 65;  // Ascii value of A
$column = chr($char).'7';
$columnH = chr($char);
foreach($arrWeeks as $key => $value) {
    $objExcel->getActiveSheet()->getColumnDimension($columnH)->setAutoSize(true);
    $week = explode('/',$value['week']);
    $objExcel->getActiveSheet()->setCellValue($column,"W".$w."\n".$week[0]." - ".$week[1]);
    $objExcel->getActiveSheet()->getStyle($column)->getAlignment()->setWrapText(true);
    $objExcel->getActiveSheet()->getStyle($column)->applyFromArray(array('font'  => array('size'  => 8)));
    if($char==90 && $col!=75) {
        $column = 'A'.chr($col).'7';
        $columnH = 'A'.chr($col);
        $col++;
    }else {
        $char++;
        $column = chr($char).'7';
        $columnH = chr($char);
    }
    $w++;
}

// FreezePane rows (Assign vertical scrolling from this column and row)
$objExcel->getActiveSheet()->freezePane('A8');

$i=1;
$row = 8;
$total_amount = 0;
$total_spots = 0;
$gross = 0;
$net = 0;
$arrTotalSpots = array();      // Contains total spots cells range of each zone
$arrFinalTotalCell = array();  // Contain $ Gross totals cells of each zone

// Printing actual proposal data
foreach($arrProposal as $proposalKey => $proposalValue) {
    $zone_amount = 0;
    $arrWeeksTotal = array();
    foreach($arrWeeks as $key => $value) {
        $arrWeeksTotal[$value['week']] = 0;
    }
    // Zone name and sys code
    $objExcel->getActiveSheet()->mergeCells('A'.$row.':K'.$row);
    $zoneName = isset($proposalValue['zone']['zonename']) ? $proposalValue['zone']['zonename'] : '';
    $sysCode = isset($proposalValue['zone']['syscode']) ? $proposalValue['zone']['syscode'] : '';
    $objExcel->getActiveSheet()->setCellValue('A'.$row,'Zone : '.$zoneName.'  SysCode : '.$sysCode);
    $startDate = trim($proposalValue['lines'][0]['startdate']);
    $row++;
    $zone_row = $row;
    foreach($proposalValue['lines'] as $zoneKey => $zoneValue){
        $objExcel->getActiveSheet()->setCellValue('A'.$row,$i);
        $objExcel->getActiveSheet()->setCellValue('B'.$row,trim($zoneValue['callsign']));
        $objExcel->getActiveSheet()->mergeCells('C'.$row.':D'.$row);
        $objExcel->getActiveSheet()->setCellValue('C'.$row,trim($zoneValue['title']));

        // display day wise cross (X) mark
        $mon = '';
        $tue = '';
        $wed = '';
        $thur = '';
        $fri = '';
        $sat = '';
        $sun = '';
        // If days value is in array format
        if(is_array($zoneValue['day'])) {
            $dayCount = count($zoneValue['day']);
            for($j=0;$j<$dayCount;$j++) {
                $day = $zoneValue['day'][$j];
                if(trim($day)==1) {
                    $sun = 'X';
                }
                if(trim($day)==2) {
                    $mon = 'X';
                }
                if(trim($day)==3) {
                    $tue = 'X';
                }
                if(trim($day)==4) {
                    $wed = 'X';
                }
                if(trim($day)==5) {
                    $thur = 'X';
                }
                if(trim($day)==6) {
                    $fri = 'X';
                }
                if(trim($day)==7) {
                    $sat = 'X';
                }
            }
        }else {
            // If days value is directly assign to day index
            $day = $zoneValue['day'];
            if(trim($day)==1) {
                $sun = 'X';
            }
            if(trim($day)==2) {
                $mon = 'X';
            }
            if(trim($day)==3) {
                $tue = 'X';
            }
            if(trim($day)==4) {
                $wed = 'X';
            }
            if(trim($day)==5) {
                $thur = 'X';
            }
            if(trim($day)==6) {
                $fri = 'X';
            }
            if(trim($day)==7) {
                $sat = 'X';
            }
        }
        $objExcel->getActiveSheet()->getStyle('E'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objExcel->getActiveSheet()->setCellValue('E'.$row,$mon);
        $objExcel->getActiveSheet()->getStyle('F'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objExcel->getActiveSheet()->setCellValue('F'.$row,$tue);
        $objExcel->getActiveSheet()->getStyle('G'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objExcel->getActiveSheet()->setCellValue('G'.$row,$wed);
        $objExcel->getActiveSheet()->getStyle('H'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objExcel->getActiveSheet()->setCellValue('H'.$row,$thur);
        $objExcel->getActiveSheet()->getStyle('I'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objExcel->getActiveSheet()->setCellValue('I'.$row,$fri);
        $objExcel->getActiveSheet()->getStyle('J'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objExcel->getActiveSheet()->setCellValue('J'.$row,$sat);
        $objExcel->getActiveSheet()->getStyle('K'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objExcel->getActiveSheet()->setCellValue('K'.$row,$sun);
        $objExcel->getActiveSheet()->getStyle('L'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objExcel->getActiveSheet()->setCellValue('L'.$row,trim($zoneValue['startdate']));
        $objExcel->getActiveSheet()->getStyle('M'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objExcel->getActiveSheet()->setCellValue('M'.$row,trim($zoneValue['enddate']));
        $startTime = date('H:i',strtotime(trim($zoneValue['starttime'])));
        $objExcel->getActiveSheet()->getStyle('N'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objExcel->getActiveSheet()->setCellValue('N'.$row,$startTime);
        $endTime = date('H:i',strtotime(trim($zoneValue['endtime'])));
        $objExcel->getActiveSheet()->getStyle('O'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objExcel->getActiveSheet()->setCellValue('O'.$row,$endTime);
        $objExcel->getActiveSheet()->getStyle('P'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objExcel->getActiveSheet()->setCellValue('P'.$row,trim($zoneValue['weeks']));
        $objExcel->getActiveSheet()->getStyle('Q'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objExcel->getActiveSheet()->setCellValue('Q'.$row,trim($zoneValue['spotsweek']));
        $objExcel->getActiveSheet()->getStyle('R'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objExcel->getActiveSheet()->getStyle('R'.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);
        $objExcel->getActiveSheet()->setCellValue('R'.$row,number_format(trim($zoneValue['rate']),2));
        $objExcel->getActiveSheet()->getStyle('S'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objExcel->getActiveSheet()->setCellValue('S'.$row,trim($zoneValue['spots']));
        $total_spots = $total_spots + trim($zoneValue['spots']);
        $objExcel->getActiveSheet()->getStyle('T'.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);
        $objExcel->getActiveSheet()->getStyle('T'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $char = 85;  // Ascii value of U
        $col = 65;  // Ascii value of A
        $w1 = chr($char).$row;
        $columnH = chr($char);
        foreach($arrWeeks as $key => $value) {
            if($char==90 && $col!=75) {
                $column = 'A'.chr($col).$row;
                $col++;
            }else {
                $char++;
                $column = chr($char).$row;
            }
        }
        $w2 = $column;
        $objExcel->getActiveSheet()->setCellValue('T'.$row,'=R'.$row.' * SUM('.$w1.':'.$w2.')');
        //$objExcel->getActiveSheet()->setCellValue('T'.$row,number_format(trim($zoneValue['total']),2));
        $zone_amount += trim($zoneValue['total']);
        $total_amount += trim($zoneValue['total']);

        // Set weeks actual data
        $char = 85;  // Ascii value of U
        $col = 65;  // Ascii value of A
        $column = chr($char).$row;
        $columnH = chr($char);
        foreach($arrWeeks as $key => $value) {
            $week = str_replace('/','',$value['week']);
            $objExcel->getActiveSheet()->getStyle($column)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            if(isset($zoneValue['w'.$week])) {
                $arrWeeksTotal[$value['week']] += $zoneValue['w'.$week];
                $objExcel->getActiveSheet()->setCellValue($column,$zoneValue['w'.$week]);
            }else {
                $arrWeeksTotal[$value['week']] += 0;
                $objExcel->getActiveSheet()->setCellValue($column,'0');
            }
            if($char==90 && $col!=75) {
                $column = 'A'.chr($col).$row;
                $columnH = 'A'.chr($col);
                $col++;
            }else {
                $char++;
                $column = chr($char).$row;
                $columnH = chr($char);
            }
        }

        $endDate = trim($zoneValue['enddate']);
        $row++;   // row increment
        $i++;     // record increment
    }

    // Printing zone wise total amount
    $objExcel->getActiveSheet()->mergeCells('A'.$row.':S'.$row);
    $objExcel->getActiveSheet()->getStyle('A'.$row.':S'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objExcel->getActiveSheet()->setCellValue('A'.$row,trim($zoneName).' Totals');
    if(84+count($arrWeeks)>90){
        $afterZ = (84+count($arrWeeks))-90;
        $upToCell = 64+$afterZ;
        $bg_width = 'T'.$row.':A'.chr($upToCell).$row;
    }else{
    $bg_width = 'T'.$row.':'.chr(84+count($arrWeeks)).$row;
    }

    $objExcel->getActiveSheet()->getStyle($bg_width)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objExcel->getActiveSheet()->getStyle($bg_width)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => 'C0C0C0')));
    $objExcel->getActiveSheet()->getStyle('T'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objExcel->getActiveSheet()->getStyle('T'.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);
    $objExcel->getActiveSheet()->setCellValue('T'.$row,'=SUM(T'.$zone_row.':T'.($row-1).')');    
    // Lock Total Amount column
    $objExcel->getActiveSheet()->getStyle('T'.$zone_row.':T'.($row).'')->getProtection()->setLocked( PHPExcel_Style_Protection::PROTECTION_PROTECTED);
    $arrFinalTotalCell[] = 'T'.$row;
    $arrTotalSpots[] = 'S'.$zone_row.':S'.($row-1).'';
    // Printing zone wise weeks total
    $char = 85;
    $col = 65;  // After Z column
    $column = chr($char).$row;
    $col1 = chr($char).$zone_row;
    $col2 = chr($char).($row-1);
    foreach($arrWeeksTotal as $key => $value) {
        $objExcel->getActiveSheet()->getStyle($column)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objExcel->getActiveSheet()->setCellValue($column,'=SUM('.$col1.':'.$col2.')');
        if($char==90 && $col!=75) {
            $column = 'A'.chr($col).$row;
            $col1 = 'A'.chr($col).$zone_row;
            $col2 = 'A'.chr($col).($row-1);
            $col++;
        }else {
            $char++;
            $column = chr($char).$row;
            $col1 = chr($char).$zone_row;
            $col2 = chr($char).($row-1);
        }
    }

    $row += 2;
}

// Set start and end date at the top
$objExcel->getActiveSheet()->mergeCells('P1:Q1');
$objExcel->getActiveSheet()->getStyle('P1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objExcel->getActiveSheet()->setCellValue('P1',$startDate);
$objExcel->getActiveSheet()->mergeCells('T1:U1');
$objExcel->getActiveSheet()->getStyle('T1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objExcel->getActiveSheet()->setCellValue('T1',$endDate);

//Total Spots
$objExcel->getActiveSheet()->getStyle('W3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//$objExcel->getActiveSheet()->setCellValue('W3',$total_spots);
$objExcel->getActiveSheet()->setCellValue('W3','=SUM('.implode(',',$arrTotalSpots).')');
// Gross $
$objExcel->getActiveSheet()->mergeCells('AA3:AB3');
$objExcel->getActiveSheet()->getStyle('AA3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//$objExcel->getActiveSheet()->setCellValue('AA3','$'.number_format(trim($proposalLines['totals']['gross']),2));
$objExcel->getActiveSheet()->setCellValue('AA3','=DOLLAR(SUM('.implode(',',$arrFinalTotalCell).'))');
//Net $
$objExcel->getActiveSheet()->mergeCells('AA6:AB6');
$objExcel->getActiveSheet()->getStyle('AA6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//$objExcel->getActiveSheet()->setCellValue('AA6','$'.number_format(trim($proposalLines['totals']['gross']),2));
$objExcel->getActiveSheet()->setCellValue('AA6','=DOLLAR(AA3-AA4-AA5)');


// Second Table Month Wise information
$monthTotals = $proposalLines['brodmonthstotal'];
$rowNo = $row+3;

$objExcel->getActiveSheet()->getStyle('A'.$rowNo.':'.'B'.$rowNo)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => 'C0C0C0')));
$objExcel->getActiveSheet()->getStyle('A'.$rowNo.':'.'A'.($rowNo+4))->applyFromArray(array('font'  => array('bold'  => true)));
$objExcel->getActiveSheet()->mergeCells('A'.$rowNo.':'.'B'.$rowNo);
$objExcel->getActiveSheet()->getStyle('A'.$rowNo)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objExcel->getActiveSheet()->setCellValue('A'.$rowNo,'Totals');
$rowNo1 = $rowNo + 1;
$objExcel->getActiveSheet()->mergeCells('A'.$rowNo1.':'.'B'.$rowNo1);
$objExcel->getActiveSheet()->getStyle('A'.$rowNo1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objExcel->getActiveSheet()->setCellValue('A'.$rowNo1,'Spots');
$rowNo2 = $rowNo1 + 1;
$objExcel->getActiveSheet()->mergeCells('A'.$rowNo2.':'.'B'.$rowNo2);
$objExcel->getActiveSheet()->getStyle('A'.$rowNo2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objExcel->getActiveSheet()->setCellValue('A'.$rowNo2,'Gross $');
$rowNo3 = $rowNo2 + 1;
$objExcel->getActiveSheet()->mergeCells('A'.$rowNo3.':'.'B'.$rowNo3);
$objExcel->getActiveSheet()->getStyle('A'.$rowNo3)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objExcel->getActiveSheet()->setCellValue('A'.$rowNo3,'Net $');

foreach($monthTotals as $yearKey => $yearVal){
    $char_ascii = 67;
    $year = substr($yearKey,2,2);
    foreach($yearVal as $monthKey => $monthVal){
        $i=$rowNo;
        if($char_ascii==69){  // Merging columns(E,F,G,H,I,J)
            $char_ascii1 = $char_ascii + 5;
            for($j=$rowNo;$j<=$rowNo3;$j++){
                $objExcel->getActiveSheet()->mergeCells(chr($char_ascii).$j.':'.chr($char_ascii1).$j);
            }
        }else if($char_ascii==74){     //// Merging columns(K,L)
            $char_ascii++;
            $char_ascii1 = $char_ascii + 1;
            for($j=$rowNo;$j<=$rowNo3;$j++){
                $objExcel->getActiveSheet()->mergeCells(chr($char_ascii).$j.':'.chr($char_ascii1).$j);
            }
        }

        $column_row =  chr($char_ascii).$i;
        $objExcel->getActiveSheet()->getStyle($column_row)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => 'C0C0C0')));
        $objExcel->getActiveSheet()->getStyle($column_row)->applyFromArray(array('font'  => array('bold'  => true)));
        $objExcel->getActiveSheet()->getStyle($column_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objExcel->getActiveSheet()->setCellValue($column_row,$monthVal['monthabr'].'-'.$year);
        $i++;
        $column_row =  chr($char_ascii).$i;
        $objExcel->getActiveSheet()->getStyle($column_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objExcel->getActiveSheet()->setCellValue($column_row,$monthVal['spotsmonth']);
        $i++;
        $column_row =  chr($char_ascii).$i;
        $objExcel->getActiveSheet()->getStyle($column_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objExcel->getActiveSheet()->setCellValue($column_row,'$'.number_format(trim($monthVal['monthtotal']),2));
        $i++;
        $column_row =  chr($char_ascii).$i;
        $objExcel->getActiveSheet()->getStyle($column_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objExcel->getActiveSheet()->setCellValue($column_row,'$'.number_format(trim($monthVal['nettotal']),2));
        if($char_ascii==69){
            $char_ascii = $char_ascii + 5;
        }else if($char_ascii==75){
            $char_ascii++;
            $char_ascii = $char_ascii + 1;
        }else{
            $char_ascii++;
        }
    }     
}


// Create and Save file
$objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
$excelLocation = str_replace('service.php', 'excel/', __FILE__);
$objWriter->save($excelLocation.'service_'.$_GET['proposalid'].'.xls');

// Download file

echo $file = $excelLocation.'service_'.$_GET['proposalid'].'.xls';
if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exit;
}



?>
