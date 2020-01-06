<?php
session_start();

/** Error reporting */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('America/Los_Angeles');

require_once '../../inc/phpexcel/PHPExcel.php';
require_once '../../inc/days.php';

$cardid  = $_GET["id"];
$userid  = $_SESSION['userid'];
$tokenid = $_SESSION['tokenid'];
$cols    = explode(",", $_GET['cols']);

$togglerate      = $cols[0];
$togglefixedrate = $cols[1];

if(!isset($cardid) || !isset($userid) || !isset($tokenid)){
	print -1;
	return;
}

$url     = "http://plus.showseeker.com/ezrates/services/loadratecard.php?zoneid=&userid={$userid}&tokenid={$tokenid}type=1&startdate=&cardid={$cardid}&group=0";
$content = file_get_contents($url);
$data    = json_decode($content);

$header = $data->responseHeader;
$zone   = $header->zone;
$zoneid = $header->zoneid;
$name   = $header->name;

$dayparts = $data->dayparts;
$rates    = $data->response;

$filename = $zone.".".preg_replace("/[^A-Za-z0-9]/", "", $name).'.xls';
$filename = str_replace(" ", ".", $filename);
$filename = str_replace("/", "-", $filename);
$filename = strtolower($filename);


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

$worksheet = $objPHPExcel->setActiveSheetIndex(0);
$worksheet->setCellValueByColumnAndRow(0, 1, 'Network');

$col      = 1;
$dayParts = [];
$networks = [];

foreach ($dayparts as &$value) {
	$start = date("g:iA",strtotime("2000-01-01 ".$value->starttime));
	$end   = date("g:iA",strtotime("2000-01-01 ".$value->endtime));
	$days  = formatDaysByNumners($value->days);
	$key   = $value->key;

	$keydaypart   = "daypart|".$key;
	$keyfixed     = "fixed|".$key;
	$daypart      = "Daypart"."\r".$start."\r".$end."\r".$days;
	$daypartfixed = "Fixed"."\r".$start."\r".$end."\r".$days;

	if($togglerate == "true"){
		$worksheet->setCellValueByColumnAndRow($col, 1, $daypart);
		$worksheet->getStyleByColumnAndRow($col, 1)->getAlignment()->setWrapText(true)->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$worksheet->getStyleByColumnAndRow($col, 1)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB( PHPExcel_Style_Color::COLOR_DAYPART);
		$col++;

		$dayParts[] = ["key"=>$keydaypart,"daypart"=>$daypart,"hash"=>md5(preg_replace('/[\x00-\x1F\x7F-\xA0\xAD]/u', '',trim($daypartfixed)))];
	}

	if($togglefixedrate == "true"){
		$worksheet->setCellValueByColumnAndRow($col, 1, $daypartfixed);
		$worksheet->getStyleByColumnAndRow($col, 1)->getAlignment()->setWrapText(true)->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$worksheet->getStyleByColumnAndRow($col, 1)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB( PHPExcel_Style_Color::COLOR_FIXED);
	    $col++;

		$dayParts[] = ["key"=>$keyfixed,"daypart"=>$daypartfixed,"hash"=>md5(preg_replace('/[\x00-\x1F\x7F-\xA0\xAD]/u', '',trim($daypartfixed)))];
    }
}

$row = 2;
foreach ($rates as &$value) {
	//set the network callsign in the excel spreadsheet
	$network    = $value[0]->callsign;
	$networkid  = $value[0]->networkid;
	$networks[] = ["Network"=>$network,"NetworkId"=>$networkid];
	
	$worksheet->setCellValueByColumnAndRow(0, $row, $network);

	$col = 1;
	foreach ($value as &$daypartvalue) {		
			$daypartRate      = $daypartvalue->rate;
			$daypartFixedRate = $daypartvalue->ratefixed;
			$keys             = [];

			if($togglerate == "true"){
				$worksheet->setCellValueByColumnAndRow($col, $row, (string)$daypartRate);
				$worksheet->getStyleByColumnAndRow($col, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);

				$col++;
			}

		if($togglefixedrate == "true"){
			$worksheet->setCellValueByColumnAndRow($col, $row, (string)$daypartFixedRate);
			$worksheet->getStyleByColumnAndRow($col, $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			
			$col++;
		}
	}
	
	$row++;
}


$worksheet->freezePane('A2');
$worksheet->freezePane('B2');
$worksheet->getColumnDimension('A')->setWidth(25);
$worksheet->setTitle('Ratecard');

$objPHPExcel->createSheet(1)
				->fromArray(["Network", "NetworkId"], NULL, 'A1')
				->fromArray($networks, NULL, 'A2')
				->setTitle('Networks')
				->setSheetState(PHPExcel_Worksheet::SHEETSTATE_VERYHIDDEN);

$objPHPExcel->createSheet(3)
				->fromArray(["key", "daypart", "hash"], NULL, 'A1')
				->fromArray($dayParts, NULL, 'A2')
				->setTitle('Dayparts')
				->setSheetState(PHPExcel_Worksheet::SHEETSTATE_VERYHIDDEN);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//$objWriter->save('php://output');
$objWriter->save(str_replace('.php', '.xls', '../../tmp/ratecards/'.$filename));

header('Content-Type:application/json');
print json_encode(["file"=>$filename]);
