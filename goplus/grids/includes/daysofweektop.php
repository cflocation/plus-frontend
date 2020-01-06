 <div>
	<div class="lCell" id="callsigncorner"></div>
	<?php 
	for($j=1; $j<=7; $j++){

		$add_days 	= 7.1*($i)+($j-1);
		$hday 		= strtotime($wRange['sDate']) + (24*3600*$add_days);

		print('<div class="headerCell">'.date('M', $hday).' '.date('j',$hday).' '.date('D',$hday).'</div>');
		 
		}?>

	<div class="rCell">
		<img src="/plus/i/thumbnails/<?php print($networkInfo[0]['filename']);?>" height="23" width="23">
	</div>	
</div>