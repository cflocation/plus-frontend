<?php
ini_set('max_execution_time','0');
ini_set("display_errors",1);
require_once 'Classes/PHPExcel.php';

//Fetching data in json format
$json_data	= file_get_contents("http://solr.showseeker.net:8983/solr/gracenote/select?q=*%3A*&rows=5000&fq=stationnum%3A12499&wt=json&indent=true&fl=title,genre,showid,premierefinale,epititle,stationnum,showtype,rating,new,day_pst,tz_start_pst,tz_end_pst,reduceddesc,callsign&sort=tz_start_pst%20asc");
// json decode proposal data
$arrData = json_decode($json_data,true);
//echo '<pre>';
//print_r($arrData);exit;

//Calculating difference between two time
function getTimeDiff($dtime,$atime) {
    $nextDay=$dtime>$atime?1:0;
    $dep=EXPLODE(':',$dtime);
    $arr=EXPLODE(':',$atime);
    $diff=ABS(MKTIME($dep[0],$dep[1],0,DATE('n'),DATE('j'),DATE('y'))-MKTIME($arr[0],$arr[1],0,DATE('n'),DATE('j')+$nextDay,DATE('y')));
    $hours=FLOOR($diff/(60*60));
    $mins=FLOOR(($diff-($hours*60*60))/(60));
    $secs=FLOOR(($diff-(($hours*60*60)+($mins*60))));
    IF(STRLEN($hours)<2) {
        $hours="0".$hours;
    }
    IF(STRLEN($mins)<2) {
        $mins="0".$mins;
    }
    IF(STRLEN($secs)<2) {
        $secs="0".$secs;
    }
    RETURN $hours.':'.$mins.':'.$secs;
}

function last_monday($date) {
    if (!is_numeric($date))
        $date = strtotime($date);
    if (date('w', $date) == 1)
        return $date;
    else
        return strtotime(
                'last monday',
                $date
        );
}

function getPrevKey($key, $hash = array())
{
    $keys = array_keys($hash);
    $found_index = array_search($key, $keys);
    if ($found_index === false || $found_index === 0)
        return false;
    return $keys[$found_index-1];
}

//Arrange json decoded data in one array i.e. $arrStation
if(isset($arrData) && !empty($arrData)) {
    $arrStation = array();
    foreach($arrData['response']['docs'] as $key => $response) {

        $preKey = getPrevKey($key,$arrData['response']['docs']);
        if(isset($arrData['response']['docs'][$preKey]['tz_end_ast'])) {
            $pre_end_ast = explode('T',$arrData['response']['docs'][$preKey]['tz_end_ast']);
        }else if(isset($arrData['response']['docs'][$preKey]['tz_end_pst'])) {
            $pre_end_ast = explode('T',$arrData['response']['docs'][$preKey]['tz_end_pst']);
        }else if(isset($arrData['response']['docs'][$preKey]['tz_end_mtc'])) {
            $pre_end_ast = explode('T',$arrData['response']['docs'][$preKey]['tz_end_mtc']);
        }else if(isset($arrData['response']['docs'][$preKey]['tz_end_mst'])) {
            $pre_end_ast = explode('T',$arrData['response']['docs'][$preKey]['tz_end_mst']);
        }
        $pre_endT = explode(':',trim($pre_end_ast[1]));
        if(trim($pre_endT[1])>=30 && trim($pre_endT[1])!=00) {
            $pre_time2 = trim($pre_endT[0]).':30';
        }else if(trim($pre_endT[1])<30 && trim($pre_endT[1])!=00) {
            $pre_time2 = trim($pre_endT[0]).':00';
        }else {
            $pre_time2 = trim($pre_endT[0]).':'.trim($pre_endT[1]);
        }



        if(isset($response['tz_start_ast'])) {
            $start_ast = explode('T',$response['tz_start_ast']);
        }else if(isset($response['tz_start_pst'])) {
            $start_ast = explode('T',$response['tz_start_pst']);
        }else if(isset($response['tz_start_mtc'])) {
            $start_ast = explode('T',$response['tz_start_mtc']);
        }else if(isset($response['tz_start_mst'])) {
            $start_ast = explode('T',$response['tz_start_mst']);
        }
        
        $startT = explode(':',trim($start_ast[1]));
        if(trim($startT[1])>=30 && trim($startT[1])!=00) {
            $time1 = trim($startT[0]).':30';
        }else if(trim($startT[1])<30 && trim($startT[1])!=00) {
            $time1 = trim($startT[0]).':30';
        }else {
            $time1 = trim($startT[0]).':'.trim($startT[1]);
        }

        if($pre_time2==$time1){
            $time1 = date("H:i", strtotime('+30 minutes', strtotime($time1)));
        }
        
        if(isset($response['tz_end_ast'])) {
            $end_ast = explode('T',$response['tz_end_ast']);
        }else if(isset($response['tz_end_pst'])) {
            $end_ast = explode('T',$response['tz_end_pst']);
        }else if(isset($response['tz_end_mtc'])) {
            $end_ast = explode('T',$response['tz_end_mtc']);
        }else if(isset($response['tz_end_mst'])) {
            $end_ast = explode('T',$response['tz_end_mst']);
        }
        $endT = explode(':',trim($end_ast[1]));
        //if(trim($endT[0])!=00 && trim($endT[1])!=00) {
            if(trim($endT[1])>=30 && trim($endT[1])!=00) {
                $time2 = trim($endT[0]).':30';
            }else if(trim($endT[1])<30 && trim($endT[1])!=00) {
                $time2 = trim($endT[0]).':00';
            }else {
                $time2 = trim($endT[0]).':'.trim($endT[1]);
            }
//        }else {
//            $time2 = '23:30';
//        }
        $timeDiff = getTimeDiff($time1,$time2);
        $diffTime = explode(':',$timeDiff);
        $rowspan = trim($diffTime[0])*2;
        if(trim($diffTime[1])!='00') {
            $rowspan += 1;
        }
        $arrStation[$start_ast[0]][date('h:i A',strtotime($time1))][] = date('h:i A',strtotime($time1));
        $arrStation[$start_ast[0]][date('h:i A',strtotime($time1))][] = date('h:i A',strtotime($time2));
        $arrStation[$start_ast[0]][date('h:i A',strtotime($time1))]['title'] = isset($response['title']) ? $response['title'] : '';
        $arrStation[$start_ast[0]][date('h:i A',strtotime($time1))]['desc'] = isset($response['descembed']) ? $response['descembed'] : '';
        $arrStation[$start_ast[0]][date('h:i A',strtotime($time1))]['live'] = isset($response['live']) ? $response['live'] : '';
        $arrStation[$start_ast[0]][date('h:i A',strtotime($time1))]['isnew'] = isset($response['isnew']) ? $response['isnew'] : '';
        $arrStation[$start_ast[0]][date('h:i A',strtotime($time1))]['premierefinale'] = isset($response['premierefinale']) ? $response['premierefinale'] : '';
        $arrStation[$start_ast[0]][date('h:i A',strtotime($time1))]['row'] = $rowspan;
    }
}
//echo '<pre>';
//print_r($arrStation);
//exit;

reset($arrStation);
$first_key = key($arrStation);
$arrWeekMondays = array();  // Array for storing mondays dates
$arrWeekMondays[0] = date('Y-m-d', last_monday($first_key));  // Get first week monday date
$index=1;
foreach($arrStation as $key => $value) {
    if(date('N',  strtotime($key)) == 1) { //Monday == 1
        $arrWeekMondays[$index] = date('Y-m-d', strtotime($key)); //date('l Y-m-d', $i) //get the date only if it's a Monday except first monday
        $index++;
    }
}
//echo '<pre>';
//print_r($arrWeekMondays);
//exit;

// create PHPExcel object
$objExcel = new PHPExcel();

// Set document properities
$objExcel->getProperties()->setCreator("Showseeker Report")
        ->setLastModifiedBy("Service User")
        ->setTitle("ShowSeeker")
        ->setSubject("Report")
        ->setDescription("Testing")
        ->setKeywords("Office 5 Excel")
        ->setCategory("Test Category");

$objExcel->setActiveSheetIndex(0);
$row = 1;
foreach($arrWeekMondays as $mondayKey => $mondays) {
      $arrWeeklyDays = array();
      $arrWeeklyDays[] = $mondays;
      $month = substr(date('F',strtotime($mondays)),0,3);
      $day = substr(date('D',strtotime($mondays)),0,3);
      $char = 66;
      $objExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(40);
      $objExcel->getActiveSheet()->getColumnDimension(chr($char))->setWidth(15);
      $objExcel->getActiveSheet()->getStyle(chr($char).$row)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => 'C5E1FC') ));
      $objExcel->getActiveSheet()->getStyle(chr($char).$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $objExcel->getActiveSheet()->getStyle(chr($char).$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objExcel->getActiveSheet()->setCellValue(chr($char).$row,$month.' '.date('d', strtotime($mondays)).' - '.$day);
       $nextDay = date('Y-m-d', strtotime($mondays."+ 1 days"));
      for($d=1;$d<7;$d++){
            $char++;
            $arrWeeklyDays[] = $nextDay;
            $month = substr(date('F',strtotime($nextDay)),0,3);
            $day = substr(date('D',strtotime($nextDay)),0,3);
            $objExcel->getActiveSheet()->getColumnDimension(chr($char))->setWidth(15);
            $objExcel->getActiveSheet()->getStyle(chr($char).$row)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => 'C5E1FC') ));
            $objExcel->getActiveSheet()->getStyle(chr($char).$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objExcel->getActiveSheet()->getStyle(chr($char).$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objExcel->getActiveSheet()->setCellValue(chr($char).$row,$month.' '.date('d', strtotime($nextDay)).' - '.$day);
            $nextDay = date('Y-m-d', strtotime($nextDay."+ 1 days"));
     }
     $incrementTime = '12:00 AM';     
     for($i=0;$i<48;$i++){
            $row++;
            $objExcel->getActiveSheet()->getStyle('A'.$row)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => 'C5E1FC') ));
            $objExcel->getActiveSheet()->getStyle('I'.$row)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => 'C5E1FC') ));
            $objExcel->getActiveSheet()->setCellValue('A'.$row,date("h:i A", strtotime($incrementTime)));
            $objExcel->getActiveSheet()->setCellValue('I'.$row,date("h:i A", strtotime($incrementTime)));
            $incrementTime = date("h:i A", strtotime('+30 minutes', strtotime($incrementTime)));
     }
     $row += 2;
}

$row = 2;
foreach($arrWeekMondays as $mondayKey => $mondays) {
      $arrWeeklyDays = array();
      $arrWeeklyDays[] = $mondays;
      $nextDay = date('Y-m-d', strtotime($mondays."+ 1 days"));
      for($d=1;$d<7;$d++){
            $arrWeeklyDays[] = $nextDay;
            $nextDay = date('Y-m-d', strtotime($nextDay."+ 1 days"));
     }
    foreach($arrWeeklyDays as $weekKey => $weekVal) {
            $arrTitle[$weekVal] = '';
    }
    $incrementTime = '12:00 AM';   
    for($i=0;$i<48;$i++){
        $RowTime = $incrementTime;
        $char = 66;
        $objExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(25);
        foreach($arrWeeklyDays as $weekKey => $weekVal) {
            if(isset($arrStation[$weekVal]) && !empty($arrStation[$weekVal])) {
                if(isset($arrStation[$weekVal][$RowTime]) && !empty($arrStation[$weekVal][$RowTime])) {   //If this week day contain this time data
                       $arrTitle[$weekVal]=$arrStation[$weekVal][$RowTime][1];
                        if($arrStation[$weekVal][$RowTime]['premierefinale']!='') {
                            $showColor = "#FF0000";
                        }else if($arrStation[$weekVal][$RowTime]['isnew']!='') {
                            $showColor = "#008000";
                        }else if($arrStation[$weekVal][$RowTime]['live']!='') {
                            $showColor = "#570087";
                        }else {
                            $showColor = "#0000FF";
                        }
                        $objExcel->getActiveSheet()->getStyle(chr($char).$row)->getAlignment()->setWrapText(true);
                        if(($arrStation[$weekVal][$RowTime][0])!=$arrStation[$weekVal][$RowTime][1]) {
                            if($arrStation[$weekVal][$RowTime]['row']>1){
                                $rowSpan = $arrStation[$weekVal][$RowTime]['row'];
                                $objExcel->getActiveSheet()->mergeCells(chr($char).$row.':'.chr($char).($row+$rowSpan));
                            }
                            $objExcel->getActiveSheet()->getStyle(chr($char).$row)->getFont()->setSize(10);
                            $objExcel->getActiveSheet()->getStyle(chr($char).$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                            $objExcel->getActiveSheet()->getStyle(chr($char).$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            $objExcel->getActiveSheet()->setCellValue(chr($char).$row,$arrStation[$weekVal][$RowTime]['title']);
                        }else {
                            $objExcel->getActiveSheet()->getStyle(chr($char).$row)->getFont()->setSize(10);
                            $objExcel->getActiveSheet()->setCellValue(chr($char).$row,$arrStation[$weekVal][$RowTime]['title']);
                        }
                }else if(isset($arrTitle[$weekVal]) && $arrTitle[$weekVal]!='') {
                        if(strtotime($arrTitle[$weekVal])<=strtotime($RowTime)) {
                                //Blank Cell
                        }
                }else if(!isset($arrStation[$weekVal][$RowTime])) {   // If this week day doesnt contain this time data
                    //Blank Cell
                }
            }else {
                //Blank Cell
            }
            $char++;
        }
        $incrementTime = date("h:i A", strtotime('+30 minutes', strtotime($incrementTime)));
        $row++;
    }
    $row += 2;
}


// Create and Save file
$objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
$excelLocation = str_replace('grid_excel.php', 'excel/', __FILE__);
$objWriter->save($excelLocation.'grid_excel.xls');

// Download file
echo $file = $excelLocation.'grid_excel.xls';
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
