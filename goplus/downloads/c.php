<?php
include 'pdf/classes/calendar.php';
	$month = isset($_GET['m']) ? $_GET['m'] : NULL;
	$year  = isset($_GET['y']) ? $_GET['y'] : NULL;
	$calendar = Calendar::factory($month, $year);
	
	//GETTING PROPOSAL DATA
	require_once('sd.php');
	
	// DECODE PROPOSAL DATA
	$resJson = json_decode($json_data);
	
	$corporation_id = $resJson->corporation[0]->id;
	$x = 1;

	
foreach($resJson->proposal AS $zone){

	// LOOPING OVER PROPOSAL LINES
		
		foreach($zone->lines AS $line){
			
	//PRINTS PRORPOSAL LINES
		
			if ($line->live != "") {
				$extra = ' - <font color="#5801AF"><i>'.$line->live.'</i></font>';	
			}
			else {
				$extra = "";
			}

			if ($line->premiere != "") {
				$extra1 = ' - <font color="#F00"><i>'.$line->premiere.'</i></font>';	
			}
			else {
				$extra1 = "";
			}

			//DATE CONVERSION FOR EVENT HANDLER
			$createDate = new DateTime($line->startdatetime);
			$proposalDate = $createDate->format('Fd,Y');
			$pdfDate = strtotime($proposalDate);

			${'event'.$x} = $calendar->event()->condition('timestamp', $pdfDate)->title($line->callsign)->output('<font class="network">'.$line->callsign.'</font> - <font class="showtype1">'.$line->starttime.' - ' .$line->endtime . $extra . $extra1 .'</font> - '.$line->title);
			$x++;
		}
}
?>

<?php

// LOGIC TO DETERMINE IF 4 OR 5 WEEKS WILL BE SHOWN
	$m=1;
	$mc = $_GET['m'];
	if ($mc == 3 || $mc == 6 || $mc == 8 || $mc == 11 ) {
		$mr = 5;
	}
	else 
	{
		$mr = 4;
	}
	$z =  count($zone->lines);
	$y=1;
	while($y<=$z) {
		$calendar->attach( ${'event'.$y});
	$y++;
	} 
?>
<html>
	<head>
		<link type="text/css" rel="stylesheet" media="all" href="pdf/css/style.css" />
		<script src="http://www.showseeker.com/inc/foundation/js/vendor/jquery.js"></script>
	</head>
	<body style="margin:0px;">
		<div style="width:99%; padding:2px; margin:0px auto">
			<table name="proposalCalendar" class="calendar">
					<tr class="navigation">
						<th class="prev-month">&nbsp;</th>
						<th colspan="5" class="current-month"><?php echo $calendar->month() ?></th>
						<th class="next-month">&nbsp;</th>
					</tr>

					<tr class="weekdays" id="weekdays" align="center" style="max-height:85px;">
						<?php foreach ($calendar->days() as $day): ?>
							<th><?php echo $day ?></th>
						<?php endforeach ?>
					</tr>

					<?php foreach ($calendar->weeks() as $week): ?>
						<?php if ( $m <= $mr) {?>
						<tr id="others<?php echo $m;?>">
							<?php foreach ($week as $day): ?>
								<?php list($number, $current, $data) = $day;
								$classes = array();
								$output  = '';
								if (is_array($data))
								{
									$classes = $data['classes'];
									$title   = $data['title'];
									$output  = empty($data['output']) ? '' : '<br>'.implode('<br>', $data['output']).'';
								}
								?>
								<td class="day <?php echo implode(' ', $classes) ?>">
									<span class="date" title="<?php echo implode(' / ', $title) ?>"><?php echo $number ?></span>
									<div class="day-content" style="font-size:7pt;">
										<?php echo $output ?>
									</div>
								</td>
							<?php endforeach ?>
						</tr>
					<?php $m++; } ?>
					<?php endforeach ?>
			</table>
		</div>	
	</body>
</html>