
	<div class="lbCell" id="callsigncornerb"></div>
	<?php 
	for($j=1; $j<=7; $j++){

		$add_days 	= 7.01*($i)+($j-1);
		$hday 		= strtotime($wRange['sDate']) + (24*3600*$add_days);

		print('<div class="headerCell">'.date('M', $hday).' '.date('j',$hday).' '.date('D',$hday).'</div>');
		 
		}?>
	<div class="rbCell netLogoBackground" style=background:url('https://d2k1589u5uya8b.cloudfront.net/images/networklogos/png/25/<?php print($station);?>.png')></div>	