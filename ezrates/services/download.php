<?php

session_start();

$cardid = $_GET["id"];
$userid = $_SESSION['userid'];
$tokenid = $_SESSION['tokenid'];
$cols = explode(",", $_GET['cols']);

$togglerate = $cols[0];
$togglefixedrate = $cols[1];


if(!isset($cardid) || !isset($userid) || !isset($tokenid)){
	print -1;
	return;
}

//$url     = "https://plusapi.showseeker.com/proxy/http://plus.showseeker.com/ezrates/services/loadratecard.php?zoneid=&userid={$userid}&tokenid={$tokenid}type=1&startdate=&cardid={$cardid}&group=0";
$url     = "http://plus.showseeker.com/ezrates/services/loadratecard.php?zoneid=&userid={$userid}&tokenid={$tokenid}type=1&startdate=&cardid={$cardid}&group=0";

    set_time_limit(0);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);

	$data = json_decode($response);

$header = $data->responseHeader;
$zone   = $header->zone;
$zoneid = $header->zoneid;
$name   = $header->name;

$dayparts = $data->dayparts;
$rates    = $data->response;

$filename = $zone.".".ereg_replace("[^A-Za-z0-9]", "", $name).'.xls';
$filename = str_replace(" ", ".", $filename);
$filename = str_replace("/", "-", $filename);
$filename = strtolower($filename);


/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

/** Include PHPExcel */
require_once '../../inc/phpexcel/PHPExcel.php';
require_once '../../inc/days.php';




// Create new PHPExcel object

$objPHPExcel = new PHPExcel();

// Set document properties

$objPHPExcel->getProperties()->setCreator("ShowSeeker")
							 ->setLastModifiedBy("ShowSeeker")
							 ->setTitle($filename)
							 ->setSubject("Ratecard for ".$zone)
							 ->setDescription("Use this ratecard to update your rates for the zone then upload this card to the server and we will update the rates for you.")
							 ->setKeywords($cardid)
							 ->setCategory("Ratecards");


// Add some data
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(25, 1, 'Station');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'Network');
$col = 1;

foreach ($dayparts as &$value) {
	$start = date("g:iA",strtotime("2000-01-01 ".$value->starttime));
	$end = date("g:iA",strtotime("2000-01-01 ".$value->endtime));
	$days = formatDaysByNumners($value->days);
	$key = $value->key;

	$keydaypart = "daypart|".$key;
	$keyfixed = "fixed|".$key;

	$daypart = "Daypart"."\r".$start."\r".$end."\r".$days;
	$daypartfixed = "Fixed"."\r".$start."\r".$end."\r".$days;

	if($togglerate == "true"){
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $daypart);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col, 1)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col, 1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col, 1)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB( PHPExcel_Style_Color::COLOR_DAYPART);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 150, $keydaypart);
		$col++;
	}


	if($togglefixedrate == "true"){
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $daypartfixed);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col, 1)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col, 1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col, 1)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB( PHPExcel_Style_Color::COLOR_FIXED);
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 150, $keyfixed);
	    $col++;
    }

}




$row = 2;
foreach ($rates as &$value) {
	//set the network callsign in the excel spreadsheet
	$network = $value[0]->callsign;
	$networkid = $value[0]->networkid;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $network);
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(25, $row, $networkid);


	$col = 1;
	foreach ($value as &$daypartvalue) {		
			$daypartRate = $daypartvalue->rate;
			$daypartFixedRate = $daypartvalue->ratefixed;
			$keys = array();

			if($togglerate == "true"){
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $daypartRate);
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				//$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB( PHPExcel_Style_Color::COLOR_DAYPART);
				$col++;
			}

		if($togglefixedrate == "true"){
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $daypartFixedRate);
			//$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col, $row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB( PHPExcel_Style_Color::COLOR_FIXED);
			$col++;
		}

	}
	
	$row++;

	
}



$objPHPExcel->getActiveSheet()->freezePane('A2');
$objPHPExcel->getActiveSheet()->freezePane('B2');
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setVisible(false);
$objPHPExcel->getActiveSheet()->getRowDimension(150)->setVisible(false);


// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('sheet1');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$callStartTime = microtime(true);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace('.php', '.xls', '../../tmp/ratecards/'.$filename));


$re = array("file"=>$filename);
print json_encode($re);



?>




