
	<div class="lCell" id="callsigncorner"></div>
	<?php 
	for($j=1; $j<=7; $j++){

		$add_days 	= 7.01*($i)+($j-1);
		$hday 		= strtotime($wRange['sDate']) + (24*3600*$add_days);

		print('<div class="headerCell">'.date('M', $hday).' '.date('j',$hday).' '.date('D',$hday).'</div>');
		 
		}?>
	<div class="rCell">
		<img src="http://showseeker.s3.amazonaws.com/images/netwroklogo/75/<?php print($station);?>.png" height="23" width="23">
	</div>	