<?php
ini_set('max_execution_time','0');
ini_set("display_errors",1);
require_once 'Classes/PHPExcel.php';


$styleHead1 = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '000000'),
        'size'  => 10
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


$objExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
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
    $objBold = $objRichText->createTextRun(" \n".$user_name."");
    $objBold->getFont()->setBold(true)->setSize(13);
    $objNormal = $objRichText->createTextRun("\n".$user_off_address." \n".$user_city.", ".$user_state." ".$user_zip." \n".$user_phone."");
}else{
    $objNormal = $objRichText->createTextRun(" ");
}

//// Display excel report top left cornar logo
$objExcel->getActiveSheet()->mergeCells('A1:B1');
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setPath($logo);
$objDrawing->setHeight(180);
$objDrawing->setWidth(180);
$objDrawing->setCoordinates('A1');
$objDrawing->setOffsetX(1);
$objDrawing->setWorksheet($objExcel->getActiveSheet());

// Display user name and address in excel report
$objExcel->getActiveSheet()->mergeCells('C1:E1');
$objExcel->getActiveSheet()->getStyle('C1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objExcel->getActiveSheet()->setCellValue('C1',$objRichText);
$objExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(80);
$objExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setWrapText(true);
$objExcel->getActiveSheet()->setCellValue('C1',$objRichText);

// Set bottom border to header row
$objExcel->getActiveSheet()->getStyle('A1:E1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
//Display Header line (proposal info) of excel report
$objExcel->getActiveSheet()->mergeCells('A2:B2');
$objExcel->getActiveSheet()->getStyle('A2:B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
if(isset($proposalLines['proposalinfo'])){
    $proposalinfo = trim($proposalLines['proposalinfo']['name']);
}else{
    $proposalinfo = '';
}

$objExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
$objExcel->getActiveSheet()->getStyle('A2:E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objExcel->getActiveSheet()->setCellValue('A2',$proposalinfo);
$objExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleHead1);
$zoneName = isset($arrProposal[0]['zone']['zonename']) ? $arrProposal[0]['zone']['zonename'] : '';
$objExcel->getActiveSheet()->getStyle('C2:E2')->applyFromArray($styleHead1);
$objExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objExcel->getActiveSheet()->setCellValue('C2','Zone :');
$objExcel->getActiveSheet()->mergeCells('D2:E2');
$objExcel->getActiveSheet()->getStyle('D2:E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objExcel->getActiveSheet()->setCellValue('D2',$zoneName);

// Set columns heading with grey background
$objExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(25);
$objExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
$objExcel->getActiveSheet()->getStyle('A3:E3')->applyFromArray($styleHead2);
$objExcel->getActiveSheet()->getStyle('A3:E3')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => '333333')));
$objExcel->getActiveSheet()->getStyle('A3:E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objExcel->getActiveSheet()->getStyle('A3:E3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objExcel->getActiveSheet()->setCellValue('A3','Network');
$objExcel->getActiveSheet()->setCellValue('B3','Program');
$objExcel->getActiveSheet()->setCellValue('C3','DayPart');
$objExcel->getActiveSheet()->setCellValue('D3','Dates');
$objExcel->getActiveSheet()->setCellValue('E3','Rate');

// FreezePane rows (Assign vertical scrolling from this column and row)
$objExcel->getActiveSheet()->freezePane('A4');

//Display first zone details
$row = 4;
foreach($proposalLines['proposal'][0]['lines'] as $zoneKey => $zoneValue){
    $objExcel->getActiveSheet()->getStyle('A'.$row.':'.'E'.$row)->applyFromArray(array('font'  => array('color' => array('rgb' => '000000'),'size'  => 10)));
    $objExcel->getActiveSheet()->getStyle('A'.$row.':'.'E'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objExcel->getActiveSheet()->getStyle('A'.$row.':'.'E'.$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(40);
    //Network
    $objExcel->getActiveSheet()->setCellValue('A'.$row,$zoneValue['callsign']);
    //Program
    $titleFormat = isset($zoneValue['titleFormat']) ? $zoneValue['titleFormat'] : '';
    $objExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setWrapText(true);
    $objExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objExcel->getActiveSheet()->setCellValue('B'.$row,$titleFormat);
    //DayPart
    $days = isset($zoneValue['dayFormat']) ? $zoneValue['dayFormat'] : '';
    $dayPart = trim($days).' '.date('H:i A',strtotime($zoneValue['starttime'])).'-'.date('H:i A',strtotime($zoneValue['endtime']));
    $objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    $objExcel->getActiveSheet()->setCellValue('C'.$row,$dayPart);
    //Dates
    $dates = str_replace('/','-',$zoneValue['startdate']).' - '.str_replace('/','-',$zoneValue['enddate']);
    $objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
    $objExcel->getActiveSheet()->setCellValue('D'.$row,$dates);
    //Rate
    $objExcel->getActiveSheet()->setCellValue('E'.$row,'=DOLLAR('.$zoneValue['rate'].')');

    $row++;
}
$row++;

// Printing actual proposal data (Zone wise)
foreach($arrProposal as $proposalKey => $proposalValue) {
    
    if($proposalKey!=0){        
        $objExcel->getActiveSheet()->getStyle('A'.$row.':E'.$row.'')->getFont()->setBold(true);
        $zoneName = isset($proposalValue['zone']['zonename']) ? $proposalValue['zone']['zonename'] : '';
        $objExcel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objExcel->getActiveSheet()->setCellValue('A'.$row,'Zone : ');
        $objExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objExcel->getActiveSheet()->setCellValue('B'.$row,$zoneName);
        $row++;
        //Column Heading row
        $objExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(25);
        $objExcel->getActiveSheet()->getStyle('A'.$row.':E'.$row.'')->applyFromArray($styleHead2);
        $objExcel->getActiveSheet()->getStyle('A'.$row.':E'.$row.'')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => '333333')));
        $objExcel->getActiveSheet()->getStyle('A'.$row.':E'.$row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objExcel->getActiveSheet()->getStyle('A'.$row.':E'.$row.'')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objExcel->getActiveSheet()->setCellValue('A'.$row,'Network');
        $objExcel->getActiveSheet()->setCellValue('B'.$row,'Program');
        $objExcel->getActiveSheet()->setCellValue('C'.$row,'DayPart');
        $objExcel->getActiveSheet()->setCellValue('D'.$row,'Dates');
        $objExcel->getActiveSheet()->setCellValue('E'.$row,'Rate');
        $row++;
        
        foreach($proposalValue['lines'] as $zoneKey => $zoneValue){
            $objExcel->getActiveSheet()->getStyle('A'.$row.':'.'E'.$row)->applyFromArray(array('font'  => array('color' => array('rgb' => '000000'),'size'  => 10)));
            $objExcel->getActiveSheet()->getStyle('A'.$row.':'.'E'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objExcel->getActiveSheet()->getStyle('A'.$row.':'.'E'.$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(40);
            //Network
            $objExcel->getActiveSheet()->setCellValue('A'.$row,$zoneValue['callsign']);
            //Program
            $titleFormat = isset($zoneValue['titleFormat']) ? $zoneValue['titleFormat'] : '';
            $objExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setWrapText(true);
            $objExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objExcel->getActiveSheet()->setCellValue('B'.$row,$titleFormat);
            //DayPart
            $days = isset($zoneValue['dayFormat']) ? $zoneValue['dayFormat'] : '';
            $dayPart = trim($days).' '.date('H:i A',strtotime($zoneValue['starttime'])).'-'.date('H:i A',strtotime($zoneValue['endtime']));
            $objExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
            $objExcel->getActiveSheet()->setCellValue('C'.$row,$dayPart);
            //Dates
            $dates = str_replace('/','-',$zoneValue['startdate']).' - '.str_replace('/','-',$zoneValue['enddate']);
            $objExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
            $objExcel->getActiveSheet()->setCellValue('D'.$row,$dates);
            //Rate
            $objExcel->getActiveSheet()->setCellValue('E'.$row,'=DOLLAR('.$zoneValue['rate'].')');
            $row++;
        }
        $row++;
    }
}


// Create and Save file
$objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
$excelLocation = str_replace('service3.php', 'excel/', __FILE__);
$objWriter->save($excelLocation.'service3_'.$_GET['proposalid'].'.xls');

// Download file
echo $file = $excelLocation.'service3_'.$_GET['proposalid'].'.xls';
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