<?php
	ini_set('display_errors','1');
	ini_set('max_execution_time',0);
	
	include_once('sourcedata/index.php');
	include_once('functions/format.schedules.php');	
	include_once('functions/format.times.php');		
	
	$starttime 	= 	$_GET['starttime'];
	$endtime 	= 	$_GET['endetime'];
	$timezone 	= 	$_GET['timezone'];
	$startdate 	= 	$_GET['startdate'];
	$enddate 	= 	$_GET['enddate'];
	$stationnum = 	$_GET['station'];



	//Fetching data in json format and json decode proposal data
	$arrData = query($enddate,$endtime,$startdate,$starttime,$stationnum,$timezone);


	$arrStation = schedules($arrData,$timezone);


	//echo '<pre>';
	//print_r($arrStation);
	//exit;

	$arrTitle 	= array();
	$arrMondays = array();

?>

<!doctype html>
<html>
<head>
     <title>ShowSeeker</title>

     <link rel="stylesheet" href="css/foundation.css">
     <link rel="stylesheet" href="css/ezgrids/index.css">
     
    <script>	
    	//var userid = 	'<?php //print $_SESSION['userid'];?>';
		//var tokinid = 	'<?php //print $_SESSION['tokenid'];?>'; 
    	var userid 			= 	'160';
		var tokenid 		= 	'C409825FE36C8DE68738CF1769C7693C'; 
		var network_id 	=	'<?php print $stationnum;?>';
		
    </script>    
     
     <!-- Included JS Files (Compressed) -->
     <script src="js/foundation.js"></script>
     
     <!-- Initialize JS Plugins -->
     <script src="js/script.js"></script>

     <!-- Initialize JS Plugins -->
     <script src="js/services/hours.js"></script>
     <script src="js/services/proposals.js"></script>
     <script src="js/services/zones.js"></script>


		<script src="js/jquery.ui.core.min.js"></script>
		<script src="js/jquery.ui.datepicker.js"></script>
		<script src="js/jquery.ui.timepicker.js"></script>	
		<script src="js/date.js"></script>	

     
</head>
 
<body>
   	
   <!-- separating html from code -->	
	<?php include_once('html/controls.php'); ?> 
   
   
	
	<div class="row">

    <div class="twelve columns">

        <?php        
                //Calculate only mondays in a month
                if(isset($arrData['responseHeader']['params']['fq']['4'])) {
                    $monthData 	= array();
                    $mData1 		= explode('[',$arrData['responseHeader']['params']['fq']['4']);
                    $mData2 		= explode('TO',$mData1[1]);
                    $mData3 		= explode('T',$mData2[0]);

                    $date1 		= trim($mData3[0]);

                    $mData4 		= explode('T',$mData2[1]);

                    $date2 		= trim($mData4[0]);

                    $arrWeekMondays = array();  // Array for storing mondays dates

                    $index			= 1;
                    
                    if (date('N', strtotime($date1)) != 1){
                        $arrWeekMondays[0] = date('Y-m-d', last_monday($date1));  // Get first week monday date
                    }

                    for ($i = strtotime($date1); $i <= strtotime($date2); $i = strtotime('+1 day', $i)) {
                        if (date('N', $i) == 1) { //Monday == 1
	                        //get the date only if it's a Monday except first monday
                            $arrWeekMondays[$index] = date('Y-m-d', $i); //date('l Y-m-d', $i) 
                            $index++;
                        }

                    }
                }


                //Calculate start and end time                
                 $startT1 = explode('[',$arrData['responseHeader']['params']['fq']['5']);
                 $startT2 = explode('TO',trim($startT1[1]));
                 $startT3 = explode(':',trim($startT2[0]));
             
                 if(trim($startT3[0])==00){
                     $startT4 = 24;
                 }
             
                 $startT 		= trim($startT3[0]);
                 $startTime 	= trim($startT3[0]).':'.trim($startT3[1]);
                 $endT1 		= explode(':',trim($startT2[1]));
                 $endT 			= trim($endT1[0]);
                 $endTime 		= trim($endT1[0]).':'.trim($endT1[1]);
                 $startMin 	= $startT3[1];
                 $endMin 		= $endT1[1];
                 $timeDiff 	= strtotime($endTime) - strtotime($startTime);
                 $hour 			= floor($timeDiff / 3600);
                 $min  			= floor(($timeDiff - $hour * 3600) / 60);
                 $sec 			= $timeDiff - $hour * 3600 - $min * 60;
                 $endTimeCount= $hour*2;

                 if($min<=30){
                     $endTimeCount += 1;
                 }else if($min>30){
                     $endTimeCount += 2;
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
                        <?php 
                        	if($mondayKey==0){
                            	$active = 'class="active"';
                        	}
                        	else{
                            $active = '';
                        	} 
                        ?>
                        
                        <li <?php echo $active; ?> id="tab<?php echo $mondayKey+1; ?>Tab">
                            <table>
                                <thead>
                             <!-- Printing Week days from monday to sunday in first row (at the top)  -->
                            <tr>
                                <th width="170"></th>
                                <?php $arrWeeklyDays[] 		= $mondays;
                                      $month 					= substr(date('F',strtotime($mondays)),0,3);
                                      $day 						= substr(date('D',strtotime($mondays)),0,3); 
                                ?>
                                <th width="170"><?php echo $month.' '.date('d', strtotime($mondays)).' - '.$day; ?></th>
                                
                                <?PHP $nextDay = date('Y-m-d', strtotime($mondays."+ 1 days"));
                                		  for($d=1;$d<7;$d++) {
                                				$arrWeeklyDays[] 	= $nextDay;
                                				$month 				= substr(date('F',strtotime($nextDay)),0,3);
                                				$day 					= substr(date('D',strtotime($nextDay)),0,3); 
                                ?>
                                
                                <th width="170"><?php echo $month.' '.date('d', strtotime($nextDay)).' - '.$day; ?></th>
                                
                                <?php 
                                			$nextDay 				= date('Y-m-d', strtotime($nextDay."+ 1 days"));
                                			} 
                                	?>
                                <th width="170"></th>
                            </tr>
                             </thead>
                             <tbody>

                            <!-- Declare $arrTitle array with each day index -->
                            <?php 
                            		foreach($arrWeeklyDays as $weekKey => $weekVal) {
                                		$arrTitle[$weekVal] 		= '';
                            		}
                            ?>

                            <!-- Calculation for printing actual data -->
                            <?php 
                            			$incrementTime 			= $startTime;
                            			$incrementTime 			= date("h:i A", strtotime('-60 minutes', strtotime($incrementTime)));
                            		
                            		for($i=0;$i<$endTimeCount;$i++) {
                                			$incrementTime 		= date("h:i A", strtotime('+30 minutes', strtotime($incrementTime)));
                                			$RowTime 				= date("h:i A", strtotime('+30 minutes', strtotime($incrementTime))); 
                            ?>
                                <tr>
                                    <td align="center"><?php echo $RowTime; ?></td> <!-- Left side time -->
                                    <?php foreach($arrWeeklyDays as $weekKey => $weekVal) {

                                        if(isset($arrStation[$weekVal]) && !empty($arrStation[$weekVal])) { 
                                            if(isset($arrStation[$weekVal][$RowTime]) && !empty($arrStation[$weekVal][$RowTime])) { ?> <!-- If this week day contain this time data -->
                                                <?php 
                                                
                                                if($arrStation[$weekVal][$RowTime][1]=='12:00 AM' || $arrStation[$weekVal][$RowTime][1]=='01:00 AM' || $arrStation[$weekVal][$RowTime][1]=='02:00 AM') {
                                                    $arrTitle[$weekVal]='11:59 PM';
                                                }else {
                                                    $arrTitle[$weekVal]=$arrStation[$weekVal][$RowTime][1];
                                                }

                                                $clsName = '';

                                                if($arrStation[$weekVal][$RowTime]['premierefinale']!='') {
                                                    $clsName = 'has_prefin';
                                                }else if($arrStation[$weekVal][$RowTime]['isnew']!='') {
                                                    $clsName = 'has_isnew';
                                                }else if($arrStation[$weekVal][$RowTime]['live']!='') {
                                                    $clsName = 'has_live';
                                                }

                                                	if(($arrStation[$weekVal][$RowTime][0])!=$arrStation[$weekVal][$RowTime][1]) { 
                                                ?>
                                                        <td valign="top"  rowspan="<?php echo $arrStation[$weekVal][$RowTime]['row']; ?>">
                                                            <!-- input type="checkbox" / -->
                                                            <span class="has-tip tip-top <?php echo $clsName;?>" title="<?php echo $arrStation[$weekVal][$RowTime]['desc']; ?>">
                                                            &nbsp;
                                                            <?php echo $arrStation[$weekVal][$RowTime]['title']; ?>
                                                            </span>
                                                        </td>
                                                <?php }else { ?>
                                                        <td valign="top">
                                                            <!-- input type="checkbox" / -->
                                                            <span class="has-tip tip-top" title="<?php echo $arrStation[$weekVal][$RowTime]['desc']; ?>">
                                                            &nbsp;<?php echo $arrStation[$weekVal][$RowTime]['title']; ?>
                                                            </span>
                                                        </td>
                                                <?php } 
                                                
                                    		$tooltip++;
                                       	}
                                       	else if(isset($arrTitle[$weekVal]) && $arrTitle[$weekVal]!='') { 
                                       		if(strtotime($arrTitle[$weekVal])<=strtotime($RowTime)) {?>
                                                        <td></td>
                                                    <?php } 
                                                   }
                                            else if(!isset($arrStation[$weekVal][$RowTime])) { 
                                   ?> <!-- If this week day doesnt contain this time data -->
                                                    <td></td>
                                            <?php } 
                                            }else { ?>
                                            <td></td>
                                        <?php } 
                                        } 
                                        ?>
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