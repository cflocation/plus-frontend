<?php
ini_set('max_execution_time',0);
//Fetching data in json format
$json_data	= file_get_contents("http://solr.showseeker.net:8983/solr/gracenote/select/?q=*%3A*&version=2.2&start=0&indent=on&wt=json&fq=-sort:%22Paid%20Programming%22&fq=projected:0&fq=-genre1:%22consumer%22&fq=-genre2:%22consumer%22&rows=5000&fq=tz_start_ast:[2014-02-13T00:00:00Z%20TO%202014-04-12T23:59:59Z]&fq=start_ast:[06:00:00%20TO%2023:58:00]&fq=stationnum:12499+&fl=id,epititle,genre1,genre2,descembed,showtype,stationnum,projected,callsign,stars,showid,isnew,tmsid,stationnum,title,new,live,stationname,duration,premierefinale,tz_start_ast,start_ast,day_ast,tz_end_ast");

// json decode proposal data
$arrData = json_decode($json_data,true);

//echo '<pre>';
//print_r($arrData);exit;

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
function getTimeDiff($dtime,$atime){
     $nextDay=$dtime>$atime?1:0;
     $dep=EXPLODE(':',$dtime);
     $arr=EXPLODE(':',$atime);
     $diff=ABS(MKTIME($dep[0],$dep[1],0,DATE('n'),DATE('j'),DATE('y'))-MKTIME($arr[0],$arr[1],0,DATE('n'),DATE('j')+$nextDay,DATE('y')));
     $hours=FLOOR($diff/(60*60));
     $mins=FLOOR(($diff-($hours*60*60))/(60));
     $secs=FLOOR(($diff-(($hours*60*60)+($mins*60))));
     IF(STRLEN($hours)<2){$hours="0".$hours;}
     IF(STRLEN($mins)<2){$mins="0".$mins;}
     IF(STRLEN($secs)<2){$secs="0".$secs;}
     RETURN $hours.':'.$mins.':'.$secs;
}

//Arrange json decoded data in one array i.e. $arrStation
if(isset($arrData) && !empty($arrData)){
    $arrStation = array();
    foreach($arrData['response']['docs'] as $key => $response){
        $start_ast = explode('T',$response['tz_start_ast']);
        $startT = explode(':',trim($start_ast[1]));
        if(trim($startT[1])>30 && trim($startT[1])!=00){
            $time1 = trim($startT[0]).':30';
        }else if(trim($startT[1])<30 && trim($startT[1])!=00){
            $time1 = (trim($startT[0])-1).':00';
        }else{
            $time1 = trim($startT[0]).':'.trim($startT[1]);
        }
        $time1 = trim($startT[0]).':'.trim($startT[1]);
        $end_ast = explode('T',$response['tz_end_ast']);
        $endT = explode(':',trim($end_ast[1]));
        if(trim($endT[0])!=00){
            if(trim($endT[1])>30 && trim($endT[1])!=00){
                $time2 = trim($endT[0]).':30';
            }else if(trim($endT[1])<30 && trim($endT[1])!=00){
                $time2 = (trim($endT[0])-1).':00';
            }else{
                $time2 = trim($endT[0]).':'.trim($endT[1]);
            }
        }else{
            $time2 = '24:00';
        }
        $timeDiff = getTimeDiff($time1,$time2);        
        $diffTime = explode(':',$timeDiff);
        $rowspan = trim($diffTime[0])*2;        
        if(trim($diffTime[1])!='00'){
            $rowspan += 1;
        }
        $arrStation[$start_ast[0]][date('h:i A',strtotime($time1))][] = date('h:i A',strtotime($time1));
        $arrStation[$start_ast[0]][date('h:i A',strtotime($time1))][] = date('h:i A',strtotime($time2));
        $arrStation[$start_ast[0]][date('h:i A',strtotime($time1))]['title'] = $response['title'];
        $arrStation[$start_ast[0]][date('h:i A',strtotime($time1))]['row'] = $rowspan;
    }
}

//echo '<pre>';
//print_r($arrStation);
//
//exit;

$arrTitle = array();
$arrMondays = array();
?>

<!doctype html>
<html>
<head>
  <title>ShowSeeker</title>
 <link rel="stylesheet" type="text/css" href="tabs.css">
</head>
<body>
    <div id="div_tabs">
      <ul id="tabs">
          <?php
          //Calculate only mondays in a month
          if(isset($arrData['responseHeader']['params']['fq']['4'])){
            $monthData = array();
            $mData1 = explode('[',$arrData['responseHeader']['params']['fq']['4']);
            $mData2 = explode('TO',$mData1[1]);
            $mData3 = explode('T',$mData2[0]);
            $date1 = trim($mData3[0]);
            $mData4 = explode('T',$mData2[1]);
            $date2 = trim($mData4[0]);
            $arrWeekMondays = array();  // Array for storing mondays dates
            $index=1;
            $arrWeekMondays[0] = date('Y-m-d', last_monday($date1));  // Get first week monday date
            for ($i = strtotime($date1); $i <= strtotime($date2); $i = strtotime('+1 day', $i)) {
              if (date('N', $i) == 1){ //Monday == 1
                $arrWeekMondays[$index] = date('Y-m-d', $i); //date('l Y-m-d', $i) //get the date only if it's a Monday except first monday
                $index++;
              }

            }
          }
          ?>
          <!-- Printing Mondays - Tabs heading -->
          <?php if(isset($arrWeekMondays) && !empty($arrWeekMondays)){
              $mondayCount = 0;
              foreach($arrWeekMondays as $mondayKey => $mondays){
                  $recordCount = 0;
                  $month = substr(date('F',strtotime($mondays)),0,3);
                  if(isset($arrStation[$mondays])){
                      $recordCount++;
                  }
                  $nextDay = date('Y-m-d', strtotime($mondays."+ 1 days"));
                  for($d=1;$d<7;$d++){                      
                      $nextDay = date('Y-m-d', strtotime($nextDay."+ 1 days"));
                      if(isset($arrStation[$nextDay])){
                          $recordCount++;
                      }
                  }
                  if($recordCount>0){
                      $arrMondays[] = $mondays;
                      $mondayCount++;
                      echo '<li><a href="#" name="#tab'.$mondayCount.'">'.$month.' '.date('d',strtotime($mondays)).'</a></li>';
                  }
              }
          } ?>

      </ul>
    </div>
    <div id="content">  <!-- Printing tabs contents -->
      <?php if(isset($arrMondays) && !empty($arrMondays)){ ?>
        <?php foreach($arrMondays as $mondayKey => $mondays){ ?>
            <?php $arrWeeklyDays = array(); ?>   <!-- This array will store each week monday to sunday dates  -->
            <div id="tab<?php echo $mondayKey+1; ?>">
                <table class="table-area" cellspacing="0" cellpadding="0">
                    <!-- Printing Week days from monday to sunday in first row (at the top)  -->
                    <tr>
                        <td width="50px"></td>
                        <?php $arrWeeklyDays[] = $mondays; ?>
                        <?php $month = substr(date('F',strtotime($mondays)),0,3); ?>
                        <?php $day = substr(date('D',strtotime($mondays)),0,3); ?>
                        <th class="td_days"><?php echo $month.' '.date('d', strtotime($mondays)).' - '.$day; ?></th>
                        <?PHP $nextDay = date('Y-m-d', strtotime($mondays."+ 1 days")); ?>
                        <?php for($d=1;$d<7;$d++){ ?>
                            <?php $arrWeeklyDays[] = $nextDay; ?>
                            <?php $month = substr(date('F',strtotime($nextDay)),0,3); ?>
                            <?php $day = substr(date('D',strtotime($nextDay)),0,3); ?>
                             <th class="td_days"><?php echo $month.' '.date('d', strtotime($nextDay)).' - '.$day; ?></th>
                            <?PHP $nextDay = date('Y-m-d', strtotime($nextDay."+ 1 days")); ?>
                        <?php } ?>
                        <td width="50px"></td>
                    </tr>
                    <!-- Declare $arrTitle array with each day index -->
                    <?php foreach($arrWeeklyDays as $weekKey => $weekVal){ ?>
                            <?php $arrTitle[$weekVal] = ''; ?>
                    <?php } ?>
                   <!-- Calculation for printing actual data -->
                    <?php $startTime = '5:00 AM'; $printedTime='12:00 AM';?>
                    <?php for($i=0;$i<36;$i++){ ?>                         
                        <?php $startTime = date("h:i A", strtotime('+30 minutes', strtotime($startTime))) ?>
                        <?php $RowTime = date("h:i A", strtotime('+30 minutes', strtotime($startTime))); ?>
                        <tr>
                            <td style="font-size:11px;border-color: #cccccc;height:40px;" class="td_time"><?php echo $RowTime; ?></td> <!-- Left side time -->
                            <?php foreach($arrWeeklyDays as $weekKey => $weekVal){ ?>
                                <?php if(isset($arrStation[$weekVal]) && !empty($arrStation[$weekVal])){ ?>
                                        <?php if(isset($arrStation[$weekVal][$RowTime]) && !empty($arrStation[$weekVal][$RowTime])){ ?> <!-- If this week day contain this time data -->
                                                    <?php if($arrStation[$weekVal][$RowTime][1]=='12:00 AM' || $arrStation[$weekVal][$RowTime][1]=='01:00 AM' || $arrStation[$weekVal][$RowTime][1]=='02:00 AM'){
                                                        $arrTitle[$weekVal]='11:59 PM';
                                                    }else{
                                                        $arrTitle[$weekVal]=$arrStation[$weekVal][$RowTime][1]; 
                                                    }
                                                    ?>
                                                    <?php if(($arrStation[$weekVal][$RowTime][0])!=$arrStation[$weekVal][$RowTime][1]){ ?>
                                                            <td valign="top" style="font-size:11px;word-wrap:break-word;color:#0000FF;" rowspan="<?php echo $arrStation[$weekVal][$RowTime]['row']; ?>"><input type="checkbox" /><?php echo $arrStation[$weekVal][$RowTime]['title']; ?></td>
                                                    <?php }else{ ?>
                                                            <td valign="top" style="font-size:11px;word-wrap:break-word;color:#0000FF;"><input type="checkbox" /><?php echo $arrStation[$weekVal][$RowTime]['title']; ?></td>
                                                    <?php } ?>
                                        <?php }else if(isset($arrTitle[$weekVal]) && $arrTitle[$weekVal]!=''){ ?>
                                                <?php if(strtotime($arrTitle[$weekVal])<=strtotime($RowTime)){?>
                                                    <td></td>
                                                <?php } ?>
                                        <?php }else if(!isset($arrStation[$weekVal][$RowTime])){ ?> <!-- If this week day doesnt contain this time data -->
                                                <td></td>
                                        <?php } ?>
                                <?php }else{ ?>
                                    <td></td>
                                <?php } ?>
                            <?php } ?>
                            <td style="font-size:11px;border-color: #cccccc;height:40px;" class="td_time"><?php echo $RowTime; ?></td> <!-- Right side time -->
                        </tr>
                        <?php } ?>
                </table>
            </div>
        <?php } ?>
      <?php } ?>
        <br/>
        <br/>
  </div>
           

  <script src="jquery-1.7.2.min.js"></script>

  <script>
    function resetTabs(){
        $("#content > div").hide(); //Hide all content
        $("#tabs a").attr("id",""); //Reset id's
    }

    var myUrl = window.location.href; //get URL
    var myUrlTab = myUrl.substring(myUrl.indexOf("#")); // For localhost/tabs.html#tab2, myUrlTab = #tab2
    var myUrlTabName = myUrlTab.substring(0,4); // For the above example, myUrlTabName = #tab

    (function(){
        $("#content > div").hide(); // Initially hide all content
        $("#tabs li:first a").attr("id","current"); // Activate first tab
        $("#content > div:first").fadeIn(); // Show first tab content

        $("#tabs a").on("click",function(e) {
            e.preventDefault();
            if ($(this).attr("id") == "current"){ //detection for current tab
             return
            }
            else{
            resetTabs();
            $(this).attr("id","current"); // Activate this
            $($(this).attr('name')).fadeIn(); // Show content for current tab
            }
        });

        for (i = 1; i <= $("#tabs li").length; i++) {
          if (myUrlTab == myUrlTabName + i) {
              resetTabs();
              $("a[name='"+myUrlTab+"']").attr("id","current"); // Activate url tab
              $(myUrlTab).fadeIn(); // Show url tab content
          }
        }
    })()
  </script>
  <!-- BSA AdPacks code -->
</body>
</html>