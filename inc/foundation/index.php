<?php
ini_set('display_errors','1');
ini_set('max_execution_time',0);
//Fetching data in json format

$starttime = isset($_GET['starttime']) ? $_GET['starttime'] : '';
$endtime = isset($_GET['endetime']) ? $_GET['endetime'] : '';
$timezone = isset($_GET['timezone']) ? $_GET['timezone'] : '';
$startdate = isset($_GET['startdate']) ? $_GET['startdate'] : '';
$enddate = isset($_GET['enddate']) ? $_GET['enddate'] : '';
$stationnum = isset($_GET['fq']) ? $_GET['fq'] : '';

$json_data	= file_get_contents("http://solr.showseeker.net:8983/solr/gracenote/select/?q=*%3A*&version=2.2&start=0&indent=on&wt=json&fq=-sort:%22Paid%20Programming%22&fq=projected:0&fq=-genre1:%22consumer%22&fq=-genre2:%22consumer%22&rows=5000&fq=tz_start_".$timezone.":[".date('Y-m-d',strtotime($startdate))."T00:00:00Z%20TO%20".date('Y-m-d',strtotime($enddate))."T23:59:59Z]&fq=start_".$timezone.":[".$starttime."%20TO%20".$endtime."]&fq=".$stationnum."+&fl=id,epititle,genre1,genre2,descembed,showtype,stationnum,projected,callsign,search,stars,showid,isnew,tmsid,stationnum,title,new,live,stationname,duration,premierefinale,tz_start_".$timezone.",start_".$timezone.",day_".$timezone.",tz_end_".$timezone."");

// json decode proposal data
$arrData = json_decode($json_data,true);


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
//Arrange json decoded data in one array i.e. $arrStation
if(isset($arrData) && !empty($arrData)) {
    $arrStation = array();
    foreach($arrData['response']['docs'] as $key => $response) {

        $start_time = explode('T',$response['tz_start_'.$timezone]);
        $startT = explode(':',trim($start_time[1]));
        if(trim($startT[1])>30 && trim($startT[1])!=00) {
            $time1 = trim($startT[0]).':30';
        }else if(trim($startT[1])<30 && trim($startT[1])!=00) {
            $time1 = (trim($startT[0])-1).':00';
        }else {
            $time1 = trim($startT[0]).':'.trim($startT[1]);
        }
        $time1 = trim($startT[0]).':'.trim($startT[1]);

        $end_time = explode('T',$response['tz_end_'.$timezone]);
        $endT = explode(':',trim($end_time[1]));
        if(trim($endT[0])!=00) {
            if(trim($endT[1])>30 && trim($endT[1])!=00) {
                $time2 = trim($endT[0]).':30';
            }else if(trim($endT[1])<30 && trim($endT[1])!=00) {
                $time2 = (trim($endT[0])-1).':00';
            }else {
                $time2 = trim($endT[0]).':'.trim($endT[1]);
            }
        }else {
            $time2 = '24:00';
        }
        $timeDiff = getTimeDiff($time1,$time2);
        $diffTime = explode(':',$timeDiff);
        $rowspan = trim($diffTime[0])*2;
        if(trim($diffTime[1])!='00') {
            $rowspan += 1;
        }
        $arrStation[$start_time[0]][date('h:i A',strtotime($time1))][] = date('h:i A',strtotime($time1));
        $arrStation[$start_time[0]][date('h:i A',strtotime($time1))][] = date('h:i A',strtotime($time2));
        $arrStation[$start_time[0]][date('h:i A',strtotime($time1))]['title'] = isset($response['title']) ? $response['title'] : '';
        $arrStation[$start_time[0]][date('h:i A',strtotime($time1))]['desc'] = isset($response['descembed']) ? $response['descembed'] : '';
        $arrStation[$start_time[0]][date('h:i A',strtotime($time1))]['live'] = isset($response['live']) ? $response['live'] : '';
        $arrStation[$start_time[0]][date('h:i A',strtotime($time1))]['isnew'] = isset($response['isnew']) ? $response['isnew'] : '';
        $arrStation[$start_time[0]][date('h:i A',strtotime($time1))]['premierefinale'] = isset($response['premierefinale']) ? $response['premierefinale'] : '';
        $arrStation[$start_time[0]][date('h:i A',strtotime($time1))]['row'] = $rowspan;
    }
}

//echo '<pre>';
//print_r($arrStation);
//exit;

$arrTitle = array();
$arrMondays = array();
?>
<!doctype html>
<html>
    <head>
        <title>ShowSeeker</title>

        <link rel="stylesheet" href="css/foundation.css">
           <!-- Included JS Files (Compressed) -->
        <script src="js/foundation.js"></script>
        <!-- Initialize JS Plugins -->
        <script src="js/script.js"></script>
    </head>
    <body>
  <div class="row">

    <div class="twelve columns">
        <?php
                //Calculate only mondays in a month
                if(isset($arrData['responseHeader']['params']['fq']['4'])) {
                    $monthData = array();
                    $mData1 = explode('[',$arrData['responseHeader']['params']['fq']['4']);
                    $mData2 = explode('TO',$mData1[1]);
                    $mData3 = explode('T',$mData2[0]);
                    $date1 = trim($mData3[0]);
                    $mData4 = explode('T',$mData2[1]);
                    $date2 = trim($mData4[0]);
                    $arrWeekMondays = array();  // Array for storing mondays dates
                    $index=1;
                    if (date('N', strtotime($date1)) != 1){
                        $arrWeekMondays[0] = date('Y-m-d', last_monday($date1));  // Get first week monday date
                    }
                    for ($i = strtotime($date1); $i <= strtotime($date2); $i = strtotime('+1 day', $i)) {
                        if (date('N', $i) == 1) { //Monday == 1
                            $arrWeekMondays[$index] = date('Y-m-d', $i); //date('l Y-m-d', $i) //get the date only if it's a Monday except first monday
                            $index++;
                        }

                    }
                }

                //Calculate start and end time
                if(isset($arrData['responseHeader']['params']['fq']['5'])) {
                    $startT1 = explode('[',$arrData['responseHeader']['params']['fq']['5']);
                    $startT2 = explode('TO',trim($startT1[1]));
                    $startT3 = explode(':',trim($startT2[0]));
                    if(trim($startT3[0])==00){
                        $startT4 = 24;
                    }
                    $startT = trim($startT3[0]);
                    $startTime = trim($startT3[0]).':'.trim($startT3[1]);
                    $endT1 = explode(':',trim($startT2[1]));
                    $endT = trim($endT1[0]);
                    $endTime = trim($endT1[0]).':'.trim($endT1[1]);
                    $startMin = $startT3[1];
                    $endMin = $endT1[1];
                    $timeDiff = strtotime($endTime) - strtotime($startTime);
                    $hour = floor($timeDiff / 3600);
                    $min  = floor(($timeDiff - $hour * 3600) / 60);
                    $sec = $timeDiff - $hour * 3600 - $min * 60;
                    $endTimeCount = $hour*2;
                    if($min<=30){
                        $endTimeCount += 1;
                    }else if($min>30){
                        $endTimeCount += 2;
                    }
                }
                ?>

        <dl class="tabs">
            <!-- Printing Mondays - Tabs heading -->
                <?php
                //echo '<pre>';
                //print_r($arrWeekMondays);exit;
                if(isset($arrWeekMondays) && !empty($arrWeekMondays)) {
                    $mondayCount = 0;
                    foreach($arrWeekMondays as $mondayKey => $mondays) {
                        $recordCount = 0;
                        $month = substr(date('F',strtotime($mondays)),0,3);
                        $nextDay = date('Y-m-d', strtotime($mondays));
                        for($d=0;$d<7;$d++) {
                            if(isset($arrStation[$nextDay])) {   // Checking if this week having empty value
                            $recordCount++;
                        }
                            $nextDay = date('Y-m-d', strtotime($nextDay."+ 1 days"));
                            //echo $mondays.'__'.$nextDay.'__'.$recordCount.'<br/>';
                        }
                        if($recordCount>0) {
                            $arrMondays[] = $mondays;
                            $mondayCount++;
                            if($mondayCount==1){
                                echo '<dd class="active"><a href="#tab'.$mondayCount.'">'.$month.' '.date('d',strtotime($mondays)).'</a></dd>';
                            }else{
                                echo '<dd><a href="#tab'.$mondayCount.'">'.$month.' '.date('d',strtotime($mondays)).'</a></dd>';
                            }
                        }
                    }
                }
                ?>
        </dl>

        <ul class="tabs-content">
        <?php $tooltip = 1; ?>
            <?php if(isset($arrMondays) && !empty($arrMondays)) { ?>
                <?php foreach($arrMondays as $mondayKey => $mondays) { ?>
                    <?php $arrWeeklyDays = array(); ?>   <!-- This array will store each week monday to sunday dates  -->
                        <?php if($mondayKey==0){
                            $active = 'class="active"';
                        }else{
                            $active = '';
                        } ?>
                        <li <?php echo $active; ?> id="tab<?php echo $mondayKey+1; ?>Tab">
                            <table>
                                <thead>
                             <!-- Printing Week days from monday to sunday in first row (at the top)  -->
                            <tr>
                                <th width="170"></th>
                                <?php $arrWeeklyDays[] = $mondays; ?>
                                <?php $month = substr(date('F',strtotime($mondays)),0,3); ?>
                                <?php $day = substr(date('D',strtotime($mondays)),0,3); ?>
                                <th width="170"><?php echo $month.' '.date('d', strtotime($mondays)).' - '.$day; ?></th>
                                <?PHP $nextDay = date('Y-m-d', strtotime($mondays."+ 1 days")); ?>
                                <?php for($d=1;$d<7;$d++) { ?>
                                <?php $arrWeeklyDays[] = $nextDay; ?>
                                <?php $month = substr(date('F',strtotime($nextDay)),0,3); ?>
                                <?php $day = substr(date('D',strtotime($nextDay)),0,3); ?>
                                <th width="170"><?php echo $month.' '.date('d', strtotime($nextDay)).' - '.$day; ?></th>
                                <?PHP $nextDay = date('Y-m-d', strtotime($nextDay."+ 1 days")); ?>
                                <?php } ?>
                                <th width="170"></th>
                            </tr>
                             </thead>
                             <tbody>
                            <!-- Declare $arrTitle array with each day index -->
                            <?php foreach($arrWeeklyDays as $weekKey => $weekVal) { ?>
                                <?php $arrTitle[$weekVal] = ''; ?>
                            <?php } ?>
                            <!-- Calculation for printing actual data -->
                            <?php $incrementTime = $startTime; ?>
                            <?php $incrementTime = date("h:i A", strtotime('-60 minutes', strtotime($incrementTime)))?>
                            <?php for($i=0;$i<$endTimeCount;$i++) { ?>
                                <?php $incrementTime = date("h:i A", strtotime('+30 minutes', strtotime($incrementTime))) ?>
                                <?php $RowTime = date("h:i A", strtotime('+30 minutes', strtotime($incrementTime))); ?>
                                <tr>
                                    <td align="center"><?php echo $RowTime; ?></td> <!-- Left side time -->
                                    <?php foreach($arrWeeklyDays as $weekKey => $weekVal) { ?>
                                        <?php if(isset($arrStation[$weekVal]) && !empty($arrStation[$weekVal])) { ?>
                                            <?php if(isset($arrStation[$weekVal][$RowTime]) && !empty($arrStation[$weekVal][$RowTime])) { ?> <!-- If this week day contain this time data -->
                                                <?php if($arrStation[$weekVal][$RowTime][1]=='12:00 AM' || $arrStation[$weekVal][$RowTime][1]=='01:00 AM' || $arrStation[$weekVal][$RowTime][1]=='02:00 AM') {
                                                    $arrTitle[$weekVal]='11:59 PM';
                                                }else {
                                                    $arrTitle[$weekVal]=$arrStation[$weekVal][$RowTime][1];
                                                }
                                                ?>
                                                <?php
                                                $clsName = '';
                                                if($arrStation[$weekVal][$RowTime]['premierefinale']!='') {
                                                    $clsName = 'has_prefin';
                                                }else if($arrStation[$weekVal][$RowTime]['isnew']!='') {                                                   
                                                    $clsName = 'has_isnew';
                                                }else if($arrStation[$weekVal][$RowTime]['live']!='') {                                                    
                                                    $clsName = 'has_live';
                                                }
                                                ?>
                                                <?php if(($arrStation[$weekVal][$RowTime][0])!=$arrStation[$weekVal][$RowTime][1]) { ?>
                                                        <td valign="top"  rowspan="<?php echo $arrStation[$weekVal][$RowTime]['row']; ?>">
                                                            <input type="checkbox" /><span class="has-tip tip-top <?php echo $clsName;?>" title="<?php echo $arrStation[$weekVal][$RowTime]['desc']; ?>">&nbsp;<?php echo $arrStation[$weekVal][$RowTime]['title']; ?></span>
                                                        </td>
                                                <?php }else { ?>
                                                        <td valign="top">
                                                            <input type="checkbox" /><span class="has-tip tip-top" title="<?php echo $arrStation[$weekVal][$RowTime]['desc']; ?>">&nbsp;<?php echo $arrStation[$weekVal][$RowTime]['title']; ?></span>
                                                        </td>
                                                <?php } ?>
                                    <?php $tooltip++;
                                       }else if(isset($arrTitle[$weekVal]) && $arrTitle[$weekVal]!='') { ?>
                                                    <?php if(strtotime($arrTitle[$weekVal])<=strtotime($RowTime)) {?>
                                                        <td></td>
                                                    <?php } ?>
                                            <?php }else if(!isset($arrStation[$weekVal][$RowTime])) { ?> <!-- If this week day doesnt contain this time data -->
                                                    <td></td>
                                            <?php } ?>
                                        <?php }else { ?>
                                            <td></td>
                                        <?php } ?>
                                    <?php } ?>
                                    <td  align="center"><?php echo $RowTime; ?></td> <!-- Right side time -->
                                </tr>
                            <?php } ?>
                                </tbody>
                        </table>
                   
            <?php } ?>
        <?php } ?>

        </ul>
    </div>
  </div>
    </body>
</html>