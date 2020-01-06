<?php
	session_start();


	$cardid = $_GET['cardid'];
	$file = $_GET['file'];

	$d = date('Y-m-d H:i:s');

	//session ids
	$corporationid = $_SESSION['corporationid'];
	$userid = $_SESSION['userid'];


	//include database
	//include_once('../../config/database.php');
	include_once('../include/database.php');
	
	$sql    = "SELECT id, rateCardId As ratecardid, notes, published, working, rateCard AS ratecard, userId AS userid, createdAt AS createdat, updatedAt AS updatedat, deletedAt AS deletedat FROM RateCardCardVersion WHERE rateCardId = $cardid AND working = 1";
	$result = mysql_query($sql);
	$row    = mysql_fetch_assoc($result);

	$id         = $row['id'];
	$ratecardid = $row['ratecardid'];

	$rates = $row['ratecard'];
	$rates = json_decode($rates);


	/** Error reporting */
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);

	define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

	/** Include PHPExcel */
	//require_once '../../inc/phpexcel/PHPExcel.php';
	require_once '../../inc/days.php';


	$file = '/tmp/'.$file;

	require_once '../../inc/phpexcel/PHPExcel/IOFactory.php';
	$objPHPExcel = PHPExcel_IOFactory::load($file);

	$worksheet = $objPHPExcel->getActiveSheet();


	$re = array();
	$row = 2;
	$lastColumn = $worksheet->getHighestColumn();
	$lastColumn++;



	$lastRow = $worksheet->getHighestRow();
	for ($row = 2; $row <= $lastRow; $row++) {

	$station = array();

		for ($column = 'A'; $column != $lastColumn; $column++) {

			$key = $worksheet->getCell($column.'150')->getValue();
			$stationid = $worksheet->getCell("Z".$row)->getValue();
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

	print json_encode($newid);

	return;



	function findNetworkRates($rates,$stationid){
		foreach ($rates as &$value) {
			if($value->id == $stationid){
				return $value;
			}
		}
	}


?>



