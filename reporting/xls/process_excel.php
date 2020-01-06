<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);
	ini_set('memory_limit', '1024M'); set_time_limit('700');
	$con = mysqli_connect("db4.showseeker.net","vastdb","jK6YK71tJ","Customers");
	$con1 = mysqli_connect("db4.showseeker.net","vastdb","jK6YK71tJ","ShowSeeker");
	
	$now = date('Y-m-d H:i:s');
	$time_start = microtime(true); 

	$ps = 0;
	$ms = array();
	$ns = array();
	

	if (isset($_GET['file']))
	{ $filename = $_GET['file'] ; 	}

	if (isset($_GET['sender']))
	{ $sender = $_GET['sender'] ; 	}

	set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
	include 'PHPExcel/IOFactory.php';
	$inputFileName = 'excels/'. $filename;
	$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
	require_once 'Classes/PHPExcel.php';
	$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
	$cacheSettings = array( ' memoryCacheSize ' => '12MB');
	PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

//FILE CHECK AND OPEN
//echo '<u>Uploaded File:</u><b> ',pathinfo($inputFileName,PATHINFO_BASENAME),'</b> - ',$inputFileType,' file<br />';
	$objReader = PHPExcel_IOFactory::createReader($inputFileType);
	$objPHPExcel = $objReader->load($inputFileName);

//ROW COUNT
	$lRow = $objPHPExcel->getActiveSheet()->getHighestRow();
	$lastRow = $lRow -1 ;
//echo "<u>Row Count:</u> <b>" .  $lastRow . "</b>";

//CHECK TO SEE IF CELL VALUE IS NUMBERIC (MOST LIKELY A SYSCODE)
	//echo is_numeric ( $objPHPExcel->getActiveSheet()->getCell('H1')->getFormattedValue() ); 

//FILE PROCESS - OBTAIN SYSCODES 

	$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	$highestColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();  
	$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

	$syscodes = array();

for($z = 6; $z < $highestColumnIndex; $z++) {
	$column_letter = PHPExcel_Cell::stringFromColumnIndex($z) ;
	
	//$objPHPExcel->getActiveSheet()->getStyle('G1:CD1')->getNumberFormat();
	
	$syscode_raw = $objPHPExcel->getActiveSheet()->getCell($column_letter.'1')->getValue() ; 
	$syscode_final = str_pad($syscode_raw, 4, "00", STR_PAD_LEFT); 
	$syscodes[] = $syscode_final;
	}
	
	$syscode_count = count ($syscodes);
//echo "<br><u>Syscodes:</u><b> " . $syscode_count . "</b><hr>";

for($y = 0; $y < $syscode_count; $y++) {

//echo $syscodes[$y]; 

//NEED TO ENSURE THAT THE SYSCODE IS ACTIVE AND A ZONE FOR IT EXISTS
		$zone_checks = "SELECT id FROM zones WHERE syscode = '$syscodes[$y]' AND isdma = 'NO' AND deletedat IS NULL"  ; 
		$zones = mysqli_query($con1, $zone_checks);
		$zone_check =mysqli_fetch_array($zones);
		$rowcount=mysqli_num_rows($zones);

if ($rowcount == 0) {	
				//$syscode_log = "INSERT IGNORE INTO charter_ratecards_logs (syscode, callsign, filename, createdat) VALUES ('$syscodes[$y]', 'JUNK', '$filename', '$now')" ; 
				//$sys_sql = mysqli_query($con, $syscode_log);
				$ms[] = $syscodes[$y];
			}
else {

		$dup_check = "SELECT syscode FROM charter_ratecards where syscode = '$syscodes[$y]' limit 0, 1"  ; 
		$dups = mysqli_query($con, $dup_check);
		$duplicate =mysqli_fetch_array($dups);
		$dupcount=mysqli_num_rows($dups);
		if (!($rowcount == 0)) {	

				//ARCHIVE CURRENT SYSCODE RATES
				$archive_sql = "INSERT INTO charter_ratecards_archive SELECT * FROM charter_ratecards WHERE syscode =  '$syscodes[$y]'" ;
				$archive_rates = mysqli_query($con, $archive_sql);

				//DELETE ITEMS AFTER COPY
				$delete_sql= "DELETE FROM charter_ratecards WHERE syscode =  '$syscodes[$y]'" ;
				$delete_rates = mysqli_query($con, $delete_sql);
		}

	$x = 2; 
while($x <=  $lastRow) {

		$daypart =		$sheetData[$x]['C'] ; 
		$network =		$sheetData[$x]['E'] ; 
		$cost =			$sheetData[$x]['G'] ; 
		$fc = substr($cost,1);

		//COLLECT DATES AND UPDATE
		
		$start_cell = ('A'.$x);
		$stop_cell =  ('B'.$x);
	
			$data1 = $objPHPExcel->getActiveSheet()->getCell($start_cell)->getFormattedValue();
			$date1 = DateTime::createFromFormat ("m-d-y" , $data1);
			//$date_start = $date1->format('Y-m-d');
			$date_start = date_format ( new DateTime($data1) , 'Y-m-d' );


			$data2 = $objPHPExcel->getActiveSheet()->getCell($stop_cell)->getFormattedValue();
			$date2 = DateTime::createFromFormat ("m-d-y" , $data2);
			//$date_end = $date2->format('Y-m-d');
		$date_end= date_format ( new DateTime($data2) , 'Y-m-d' );


		//BREAK APART DAYPART AND TIME

			$pieces = explode(" ", $daypart);
		$days =  $pieces[0] ; 
		$times = $pieces[1]; 

		if ($days == "M-Su" ) { $day_numbers = "1,2,3,4,5,6,7"; }
		if ($days == "M-F" ) { $day_numbers = "1,2,3,4,5"; }
		if ($days == "M" )  { $day_numbers = "1"; }
		if ($days == "Tu" ) { $day_numbers = "2"; }
		if ($days == "W" )  { $day_numbers = "3"; }
		if ($days == "Th" ) { $day_numbers = "4"; }
		if ($days == "F" ) { $day_numbers = "5"; }
		if ($days == "Sa" ) { $day_numbers = "6"; }
		if ($days == "Su" ) { $day_numbers = "7"; }

		$time = explode("-", $times);
		$start_time =	$time[0] ; 
		$stop_time =	$time[1]; 

			    $letter1   = 'a';
				$pos = strpos($start_time, $letter1);

if ($pos === false) {
				//echo "";
			}
else {
				 $new = $start_time.'m';
				 $final_start_time =  date("H:i", strtotime($new));
			}

			    $letter2   = 'p';
				$pos2 = strpos($start_time, $letter2);

if ($pos2 === false) {
				//echo "";
			}
else {
				 $new2 = $start_time.'m';
				 $final_start_time =  date("H:i", strtotime($new2));
			}


			    $letter3   = 'p';
				$pos3 = strpos($stop_time, $letter3);

if ($pos3 === false) {
				//echo "";
			}
else {
				 $new3 = $stop_time.'m';
				 $final_stop_time =  date("H:i", strtotime($new3));
			}

				$pos4 = strpos($stop_time, $letter1);

if ($pos4 === false) {
				//echo "";
			}
else {
				 $new = $stop_time.'m';
				 $final_stop_time =  date("H:i", strtotime($new));
			}


if ( $start_time === "12a") { $final_start_time = "00:00:00"; }
if ( $start_time === "12p") { $final_start_time = "12:00:00"; }
if ( $start_time === "Mid") { $final_start_time = "00:00:00"; }
if ( $start_time === "12m") { $final_start_time = "00:00:00"; }

if ( $stop_time === "12m")  { $final_stop_time = "23:59:00";  }		

		$network_check = "SELECT networkid FROM syscode_mappings WHERE charter_mapping = '$network' "  ; 
		$network_sql = mysqli_query($con1, $network_check);
		$row=mysqli_fetch_array($network_sql);
		$network_id = $row['networkid'];
	//ECHO VALUES, BEFORE SQL INSERT
if (!($network_id =='')) {
		$ratecard = "INSERT INTO charter_ratecards (syscode, start_date, end_date, start_time, end_time, daypart, days, network_id, rate, createdat) VALUES ('$syscodes[$y]', '$date_start', '$date_end', '$final_start_time', '$final_stop_time', '$daypart', '$day_numbers', '$network_id', '$fc', '$now')" ; 
		$excel_sql = mysqli_query($con, $ratecard);

	}
else {
		//$ratecard_log = "INSERT IGNORE INTO charter_ratecards_logs (syscode, callsign, filename, createdat) VALUES ('$syscodes[$y]', '$network', '$filename', '$now')" ; 
		//$log_sql = mysqli_query($con, $ratecard_log);
		$ns[] = $network;
	}
	//COUNTERS
		$x++;
		}
$ps ++;
} //END IF STATMENT, SQL THAT CHECKS FOR SYSCODE IN DB
}
//TIME CALCS
	$time_processed =  (microtime(true) - $time_start);
	$time_final =  substr($time_processed, 0, 5);
//NETWORK SKIPS
	$net = (array_unique(($ns)));
	$nets = implode(" ",$net);
	$callsign_skip =  str_replace(" ",", ",$nets);
//SYSCODE SKIPS
	$sys = (array_unique(($ms)));
	$sysc = implode(" ",$sys);
	$syscode_skip =  str_replace(" ",", ",$sysc);
//REPORT INFO FOR DB AND PDF
	$report_sql = "INSERT INTO charter_ratecards_reports (syscodes, rows, processed, filename, syscode_skip, callsign_skip, process_time, sender, createdat) VALUES ('$syscode_count', '$lastRow', '$ps', '$filename', '$syscode_skip', '$callsign_skip', '$time_final', '$sender', '$now')"; 
	$reports = mysqli_query($con, $report_sql);
	$rid = $con->insert_id;


$pathout = "pdf_report.php?rid=$rid";
header("location:$pathout");
?>