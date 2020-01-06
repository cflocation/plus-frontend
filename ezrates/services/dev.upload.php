<?php
session_start();

/*
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL^E_DEPRECATED);
*/


require_once '../../inc/days.php';
require_once '../../inc/phpexcel/PHPExcel/IOFactory.php';
require_once '../include/database.php';

$cardid = $_GET['cardid'];
$file   = $_GET['file'];
$file   = '/tmp/'.$file;
$d      = date('Y-m-d H:i:s');

if(!isset($cardid) || !isset($_SESSION['userid']) || !isset($_SESSION['corporationid'])){
	header('Content-Type:application/json');
	print json_encode(["error"=>true, "message"=>"Invalid session, Login"]);
	return;
}


if(!(file_exists($file))){
	header('Content-Type:application/json');
	print json_encode(["error"=>true, "message"=>"File not found, please try again."]);
	return;
}

//session ids
$corporationid = $_SESSION['corporationid'];
$userid        = $_SESSION['userid'];

$sql    = "SELECT id, rateCardId As ratecardid, notes, published, working, rateCard AS ratecard, userId AS userid, createdAt AS createdat, updatedAt AS updatedat, deletedAt AS deletedat 
			FROM  RateCardCardVersion 
			WHERE rateCardId = $cardid 
			AND   working = 1";
$result = mysql_query($sql);
$row    = mysql_fetch_assoc($result);

$id         = $row['id'];
$ratecardid = $row['ratecardid'];
$rates      = $row['ratecard'];
$rates      = json_decode($rates);

$networks    = [];
$daypartKeys = [];
$objPHPExcel = PHPExcel_IOFactory::load($file);


//getall networks
$worksheet = $objPHPExcel->setActiveSheetIndex(1);
$maxRow    = $worksheet->getHighestRow();
for($r=2; $r<=$maxRow; $r++)
	$networks[trim($worksheet->getCell("A{$r}")->getValue())] = $worksheet->getCell("B{$r}")->getValue();


//get All daypart keys
$worksheet = $objPHPExcel->setActiveSheetIndex(2);
$maxRow    = $worksheet->getHighestRow();
for($r=2; $r<=$maxRow; $r++)
	$daypartKeys[trim($worksheet->getCell("C{$r}")->getValue())] = [
		"key"=>$worksheet->getCell("A{$r}")->getValue(),
		"daypart"=>$worksheet->getCell("B{$r}")->getValue(),
		"hash"=>$worksheet->getCell("C{$r}")->getValue()
	];


$re = array();
$row = 2;

$worksheet  = $objPHPExcel->setActiveSheetIndex(0);
$lastColumn = $worksheet->getHighestColumn();
$lastRow    = $worksheet->getHighestRow();
$lastColumn++;

for ($row = 2; $row <= $lastRow; $row++){
	$station   = array();
	$network   = $worksheet->getCell("A{$row}")->getValue();
	$stationid = false;
	
	if(!(isset($networks[$network]))){
		header('Content-Type:application/json');
		print json_encode(["error"=>true, "message"=>"Netowrk '{$network}' mapping error."]);
		exit;
	}
	
	for ($column = 'B'; $column != $lastColumn; $column++) {
		$stationid    = $networks[$network];
		$daypartName  = $worksheet->getCell($column.'1')->getValue();
		$dayPartHash  = md5(trim(preg_replace('/[\x00-\x1F\x7F-\xA0\xAD]/u', '', $daypartName)));//remove all non printable chars including tabs, spaces and newwlines
		$key          = false;

		if(!(isset($daypartKeys[$dayPartHash]))){
			header('Content-Type:application/json');
			print json_encode(["error"=>true, "message"=>"Daypart {$daypartName} mapping error."]);
			exit;
		}

		$key  = $daypartKeys[$dayPartHash]['key'];
		$cell = $worksheet->getCell($column.$row);
		
		if($key){
			$raterow = findNetworkRates($rates,$stationid);
			if($raterow){
				$raterow->$key = $cell->getValue();
			}
		}
	}
}

$ratecard = json_encode($rates);
//make the last record the NON working one
$sql = "UPDATE RateCardCardVersion SET working = 0, updatedAt = '$d' WHERE id = $id";
$result = mysql_query($sql);

//inset a clone of the current record
$sql = "INSERT INTO RateCardCardVersion (rateCardId, working, rateCard, userId, createdAt, updatedAt) VALUES ($ratecardid,1,'$ratecard',$userid,'$d','$d')";
$result = mysql_query($sql);
//get the new id
$newid = mysql_insert_id();

$re = array("id"=>$newid);


header('Content-Type:application/json');
print json_encode($re);
return;



function findNetworkRates($rates,$stationid){
	foreach ($rates as &$value) {
		if($value->id == $stationid){
			return $value;
		}
	}
}


