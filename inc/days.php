<?php
	function formatDaysByNumners($days){
		$ndays = array();
		$re;

		$days = explode(',', $days);
		$cnt = count($days);

		if($cnt == 7){
			return 'M-Su';
		}

		foreach ($days as &$val) {
			if($val == 1){
				array_push($ndays,7);
			}else{
				array_push($ndays,$val-1);
			}
		}

		if($cnt == 1){
			return daysAbbrSmallDayFix($days[0]);
		}

		$x = count($ndays)-1;
		$diff = $ndays[$x] - $ndays[0];
		$ndayscnt = count($ndays);

  		if($ndayscnt - $diff == 1){
  			$re = daysAbbrSmallDayFix($ndays[0])."-".daysAbbrSmallDayFix($ndays[$ndayscnt-1]);
  			return $re;
  		}

  		$daylist = array();
  		foreach ($ndays as &$val) {
  			$d = daysAbbrSmallDayFix($val);
  			array_push($daylist,$d);
  		}

  		return implode(",", $daylist);

	}


	function daysAbbrSmallDayFix($val){
		switch ($val){
		case 1:
		  return "M";
		  break;
		case 2:
		  return "T";
		  break;
		case 3:
		  return "W";
		  break;
		case 4:
		  return "Th";
		  break;
		case 5:
		  return "F";
		  break;
		case 6:
		  return "Sa";
		  break;
		 case 7:
		  return "Su";
		  break;
		}
	}

	//print formatDaysByNumners('2,4,5');
	return;
?>
