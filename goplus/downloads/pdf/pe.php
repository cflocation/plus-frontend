<?php


	//GETTING PROPOSAL DATA
	require_once('sd.php');

	// DECODE PROPOSAL DATA
	$resJson = json_decode($json_data);

	$corporation_id = $resJson->corporation[0]->id;


?>
<link rel="stylesheet" href="http://www.showseeker.com/inc/foundation/css/normalize.css">
<link rel="stylesheet" href="http://www.showseeker.com/inc/foundation/css/foundation.css">
<link type="text/css" rel="stylesheet" media="all" href="pdf/css/style.css" />

<style>
 .plist li{
            padding:0;
            margin:0;
			font-size:10px;
}
</style>
<div class="row">
	<ul class="small-block-grid-2" style="font-size:8pt;list-style-type:none">

<?php
foreach($resJson->proposal AS $zone){

	// LOOPING OVER PROPOSAL LINES
	$x = 1;
		foreach($zone->lines AS $line){

	//PRINTS PRORPOSAL LINES
		
			if ($line->live != "") {
				$extra = '<font color="#5801AF"><i>'.$line->live.' - </i></font>';	
			}
			else {
				$extra = "";
			}

			if ($line->premiere != "") {
				$extra1 = '<font color="#F00"><i>'.$line->premiere.' - </i></font>';	
			}
			else {
				$extra1 = "";
			}


			//Date Conversion for event handler

			$createDate = new DateTime($line->startdatetime);
			$proposalDate = $createDate->format('Y-m-d');

			$formattedTitle  =  $line->startdate . ' - <font class="network">'.$line->callsign .'</font> - ' . $line->starttime .' - ' .$line->endtime ." - ". $extra . $extra1 .rTrim($line->title,', ') . " (" . $line->linetype .")" ;

			//OUTPUT TO HTML

		    echo "<li>" . $formattedTitle . "</li>";


		}
}

?>
		</ul>
	</div>
</div>
<script src="http://www.showseeker.com/inc/foundation/js/vendor/jquery.js"></script>
<script src="http://www.showseeker.com/inc/foundation/js/foundation.min.js"></script>