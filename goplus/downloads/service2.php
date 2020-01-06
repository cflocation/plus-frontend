<?php
ini_set('max_execution_time','0');
ini_set("display_errors",1);
require_once 'Classes/PHPExcel.php';


$styleHead1 = array(
    'font'  => array(
        'bold'  => true,
        'italic' => true,
        'color' => array('rgb' => '000000'),
        'size'  => 13,
        'name'  => 'Verdana'
    ));
$styleHead2 = array(
        'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => 'FFFFFF'),
                'size'  => 11
));

//Getting variable values
$proposalid	= urldecode(trim($_GET['proposalid']));
$userid		= urldecode(trim($_GET['userid']));
$tokenid	= urldecode(trim($_GET['tokenid']));

//Fetching data in json format
$json_data	= file_get_contents("http://services.showseeker.com/proposal.php?proposalid={$proposalid}&userid={$userid}&tokenid={$tokenid}");

// json decode proposal data
$proposalLines = json_decode($json_data,true);

// passed proposal data to array variable
$arrProposal = $proposalLines['proposal'];

// create PHPExcel object
$objExcel = new PHPExcel();

// Set document properities
$objExcel->getProperties()->setCreator("Service Report 2")
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

// Assing style to header text
$objRichText = new PHPExcel_RichText();
if(isset($proposalLines['corporation'])){
    $corporation = trim($proposalLines['corporation'][0]['name']);
    $logo = trim($proposalLines['corporation'][0]['logo']);
    $imgPath = str_replace('/services/downloads','',getcwd());
    $logo = str_replace('http://ww2.showseeker.com',$imgPath.'/showseeker', $logo);
}else{
    $corporation = '';
    $logo = '';
}
// Collect User info
$objBold = $objRichText->createTextRun(trim($corporation));
$objBold->getFont()->setBold(true)->setSize(14);
if(isset($proposalLines['user'])){
    $user_name = $proposalLines['user'][0]['firstname'].' '.$proposalLines['user'][0]['lastname'];
    $user_off_address = isset($proposalLines['user'][0]['officeaddress']) ? $proposalLines['user'][0]['officeaddress'] : '';
    $user_state = isset($proposalLines['user'][0]['officestate']) ? $proposalLines['user'][0]['officestate'] : '';
    $user_city = isset($proposalLines['user'][0]['officecity']) ? $proposalLines['user'][0]['officecity'] : '';
    $user_zip = isset($proposalLines['user'][0]['officezipcode']) ? $proposalLines['user'][0]['officezipcode'] : '';
    $user_phone = isset($proposalLines['user'][0]['phone']) ? $proposalLines['user'][0]['phone'] : '';
    $objNormal = $objRichText->createTextRun("\n".$user_name." \n".$user_off_address." \n".$user_city.", ".$user_state." ".$user_zip." \n".$user_phone."");
}else{
    $objNormal = $objRichText->createTextRun(" ");
}

// Display excel report top left cornar logo
$objExcel->getActiveSheet()->mergeCells('A1:D1');
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setPath($logo);
$objDrawing->setHeight(180);
$objDrawing->setWidth(180);
$objDrawing->setCoordinates('A1');
$objDrawing->setOffsetX(1);
$objDrawing->setWorksheet($objExcel->getActiveSheet());

// Display user name and address in excel report
$objExcel->getActiveSheet()->mergeCells('E1:J1');
$objExcel->getActiveSheet()->getStyle('E1:J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objExcel->getActiveSheet()->setCellValue('E1',$objRichText);
$objExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(80);
$objExcel->getActiveSheet()->getStyle('E1')->getAlignment()->setWrapText(true);
$objExcel->getActiveSheet()->setCellValue('E1',$objRichText);

// Set bottom border to header row
$objExcel->getActiveSheet()->getStyle('A1:J1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
//Display Header line (proposal info) of excel report
$objExcel->getActiveSheet()->mergeCells('A2:J2');
$objExcel->getActiveSheet()->getStyle('A2:J2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
if(isset($proposalLines['proposalinfo'])){
    $proposalinfo = trim($proposalLines['proposalinfo']['name']);
}else{
    $proposalinfo = '';
}
$objExcel->getActiveSheet()->setCellValue('A2',$proposalinfo);
$objExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleHead1);

// Set columns heading with grey background
$objExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(25);
$objExcel->getActiveSheet()->getStyle('A3:J3')->applyFromArray($styleHead2);
$objExcel->getActiveSheet()->getStyle('A3:J3')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => '333333')));
$objExcel->getActiveSheet()->getStyle('A3:J3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objExcel->getActiveSheet()->getStyle('A3:J3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objExcel->getActiveSheet()->setCellValue('A3','Network');
$objExcel->getActiveSheet()->setCellValue('B3','DayPart');
$objExcel->getActiveSheet()->setCellValue('C3','Show');
$objExcel->getActiveSheet()->setCellValue('D3','Start');
$objExcel->getActiveSheet()->setCellValue('E3','End');
$objExcel->getActiveSheet()->setCellValue('F3','Wks');
$objExcel->getActiveSheet()->setCellValue('G3',"Spots\nWk");
$objExcel->getActiveSheet()->getStyle('G3')->getAlignment()->setWrapText(true);
$objExcel->getActiveSheet()->getStyle('G3')->applyFromArray(array('font'  => array('size'  => 8)));
$objExcel->getActiveSheet()->setCellValue('H3','Rate');
$objExcel->getActiveSheet()->setCellValue('I3',"Total\nSpots");
$objExcel->getActiveSheet()->getStyle('I3')->getAlignment()->setWrapText(true);
$objExcel->getActiveSheet()->getStyle('I3')->applyFromArray(array('font'  => array('size'  => 8)));
$objExcel->getActiveSheet()->setCellValue('J3','Total');

// FreezePane rows (Assign vertical scrolling from this column and row)
$objExcel->getActiveSheet()->freezePane('A4');

$row = 4;
// Printing actual proposal data (Zone wise)
foreach($arrProposal as $proposalKey => $proposalValue) {
    $objExcel->getActiveSheet()->mergeCells('A'.$row.':C'.$row.'');
    $objExcel->getActiveSheet()->mergeCells('D'.$row.':J'.$row.'');
    $objExcel->getActiveSheet()->getStyle('D'.$row.':J'.$row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $zoneName = isset($proposalValue['zone']['zonename']) ? $proposalValue['zone']['zonename'] : '';
    $objExcel->getActiveSheet()->setCellValue('D'.$row,'Zone : '.$zoneName);
    $zone_row = $row;
    
    foreach($proposalValue['lines'] as $zoneKey => $zoneValue){
        $row++;
        $logo= '';
        if(isset($proposalLines['networks'])){
            foreach($proposalLines['networks'] as $network){
                if(trim($network['stationnum'])==$zoneValue['stationnum']){
                    $logo = trim($network['100x100']);
                    $imgPath = str_replace('/services/downloads','',getcwd());
                    $logo = str_replace('http://ww2.showseeker.com',$imgPath.'/showseeker', $logo);
                }
            }
        }
        //Display Proposal logos
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setPath($logo);
        $objDrawing->setHeight(50);
        $objDrawing->setWidth(50);
        $objDrawing->setCoordinates('A'.$row);
        $objDrawing->setOffsetX(1);
         $objDrawing->setOffsetY(2);
        $objDrawing->setWorksheet($objExcel->getActiveSheet());

        $objExcel->getActiveSheet()->getStyle('A'.$row.':'.'J'.$row)->applyFromArray(array('font'  => array('color' => array('rgb' => '000000'),'size'  => 10)));
        $objExcel->getActiveSheet()->getStyle('A'.$row.':'.'J'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objExcel->getActiveSheet()->getStyle('A'.$row.':'.'J'.$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(40);
        $objExcel->getActiveSheet()->setCellValue('A'.$row,'');

        $days = isset($zoneValue['dayFormat']) ? $zoneValue['dayFormat'] : '';
        $dayPart = trim($days).' '.date('H:i A',strtotime($zoneValue['starttime'])).'-'.date('H:i A',strtotime($zoneValue['endtime']));
        $objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $objExcel->getActiveSheet()->setCellValue('B'.$row,$dayPart);

        $titleFormat = isset($zoneValue['titleFormat']) ? $zoneValue['titleFormat'] : '';
        $objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
        $objExcel->getActiveSheet()->getStyle('C'.$row)->getFont()->setBold(true);
        $objExcel->getActiveSheet()->getStyle('C'.$row)->getAlignment()->setWrapText(true);
        $objExcel->getActiveSheet()->getStyle('C'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objExcel->getActiveSheet()->setCellValue('C'.$row,$titleFormat);

        $objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objExcel->getActiveSheet()->setCellValue('D'.$row,$zoneValue['startdate']);
        $objExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objExcel->getActiveSheet()->setCellValue('E'.$row,$zoneValue['enddate']);
        $objExcel->getActiveSheet()->setCellValue('F'.$row,$zoneValue['weeks']);
        $objExcel->getActiveSheet()->setCellValue('G'.$row,$zoneValue['spotsweek']);
        $objExcel->getActiveSheet()->setCellValue('H'.$row,$zoneValue['rate']);
        $objExcel->getActiveSheet()->setCellValue('I'.$row,$zoneValue['spots']);
        $objExcel->getActiveSheet()->setCellValue('J'.$row,'=(H'.$row.'*I'.$row.')');
    }
    $row ++;
    $objExcel->getActiveSheet()->getStyle('F'.$row.':'.'J'.$row)->getFont()->setBold(true);
    $objExcel->getActiveSheet()->getStyle('F'.$row.':'.'H'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objExcel->getActiveSheet()->mergeCells('F'.$row.':H'.$row);
    $objExcel->getActiveSheet()->setCellValue('F'.$row,'Total');
    $objExcel->getActiveSheet()->getStyle('I'.$row.':'.'J'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objExcel->getActiveSheet()->getStyle('I'.$row.':'.'J'.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objExcel->getActiveSheet()->setCellValue('I'.$row,'=SUM(I'.$zone_row.':I'.($row-1).')');
    $objExcel->getActiveSheet()->setCellValue('J'.$row,'=DOLLAR(SUM(J'.$zone_row.':J'.($row-1).'))');
    $row += 2;
}

//Display Month Breakdown Header
$row += 2;
$objExcel->getActiveSheet()->getStyle('E'.$row.':J'.$row.'')->applyFromArray($styleHead2);
$objExcel->getActiveSheet()->getStyle('E'.$row.':J'.$row.'')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => '000000')));
$objExcel->getActiveSheet()->getStyle('E'.$row.':J'.$row.'')->getFont()->setBold(true);
$objExcel->getActiveSheet()->mergeCells('E'.$row.':G'.$row.'');
$objExcel->getActiveSheet()->getStyle('E'.$row.':G'.$row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objExcel->getActiveSheet()->setCellValue('E'.$row,'Breakdown by Month');
$objExcel->getActiveSheet()->getStyle('H'.$row.':J'.$row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objExcel->getActiveSheet()->setCellValue('H'.$row,'Month');
$objExcel->getActiveSheet()->setCellValue('I'.$row,'Spots');
$objExcel->getActiveSheet()->setCellValue('J'.$row,'Cost');
$objExcel->getActiveSheet()->getColumnDimension('J')->setWidth(13);

$row++;
//Display Month Breakdown data
if(isset($proposalLines['brodmonthstotal'])){
    foreach($proposalLines['brodmonthstotal'] as $yearKey => $yearVal){
        $year = substr($yearKey,2,2);
        foreach($yearVal as $monthKey => $monthVal){
            $objExcel->getActiveSheet()->getStyle('H'.$row.':J'.$row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objExcel->getActiveSheet()->setCellValue('H'.$row,$monthVal['monthnumber'].'-'.$year);
            $objExcel->getActiveSheet()->setCellValue('I'.$row,$monthVal['spotsmonth']);
            $objExcel->getActiveSheet()->setCellValue('J'.$row,'=DOLLAR('.$monthVal['monthtotal'].')');
            $row++;
        }
    }
}

$row++;
//Display total Net and Gross values
if(isset($proposalLines['totals'])){
    $objExcel->getActiveSheet()->getStyle('F'.$row.':'.'J'.$row)->getFont()->setBold(true);
    $objExcel->getActiveSheet()->getStyle('F'.$row.':'.'H'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objExcel->getActiveSheet()->mergeCells('F'.$row.':H'.$row);
    $objExcel->getActiveSheet()->setCellValue('F'.$row,'Gross');
    $objExcel->getActiveSheet()->mergeCells('I'.$row.':J'.$row);
    $objExcel->getActiveSheet()->getStyle('I'.$row.':'.'J'.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objExcel->getActiveSheet()->getStyle('I'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objExcel->getActiveSheet()->setCellValue('I'.$row,'=DOLLAR('.$proposalLines['totals']['gross'].')');
    $row++;
    $objExcel->getActiveSheet()->getStyle('F'.$row.':'.'J'.$row)->getFont()->setBold(true);
    $objExcel->getActiveSheet()->getStyle('F'.$row.':'.'H'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objExcel->getActiveSheet()->mergeCells('F'.$row.':H'.$row);
    $objExcel->getActiveSheet()->setCellValue('F'.$row,'Net Total');
    $objExcel->getActiveSheet()->mergeCells('I'.$row.':J'.$row);
    $objExcel->getActiveSheet()->getStyle('I'.$row.':'.'J'.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objExcel->getActiveSheet()->getStyle('I'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objExcel->getActiveSheet()->setCellValue('I'.$row,'=DOLLAR('.$proposalLines['totals']['net'].')');
    $row++;

    // Display signature and date field
    $objExcel->getActiveSheet()->getStyle('A'.$row.':'.'J'.$row)->getFont()->setBold(true);
    $objExcel->getActiveSheet()->setCellValue('A'.$row,'Signature');
    $objExcel->getActiveSheet()->getStyle('B'.$row.':'.'E'.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objExcel->getActiveSheet()->getStyle('F'.$row.':'.'H'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objExcel->getActiveSheet()->mergeCells('F'.$row.':H'.$row);
    $objExcel->getActiveSheet()->setCellValue('F'.$row,'Date');
    $objExcel->getActiveSheet()->mergeCells('I'.$row.':J'.$row);
    $objExcel->getActiveSheet()->getStyle('I'.$row.':'.'J'.$row)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objExcel->getActiveSheet()->getStyle('I'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objExcel->getActiveSheet()->setCellValue('I'.$row,date('m-d-Y'));
}



// Create and Save file
$objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
$excelLocation = str_replace('service2.php', 'excel/', __FILE__);
$objWriter->save($excelLocation.'service2_'.$_GET['proposalid'].'.xls');

// Download file
echo $file = $excelLocation.'service2_'.$_GET['proposalid'].'.xls';
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
