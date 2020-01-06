<!DOCTYPE html>
<?php include_once("settings.php")?>
<html lang="en">
<head>
	<link href="https://showseeker.s3.amazonaws.com/public-site/assets/favicon.ico" name="favicon" rel="shortcut icon" type="image/png">
	<meta http-equiv="Expires" content="0">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="cache-control" value="no-cache, no-store, must-revalidate">
	<meta http-equiv="Expires" content="Mon, 01 Jan 1990 00:00:01 GMT">

	<title>ShowSeeker - SnapShot</title>
	<link rel="stylesheet" href="../../inc/fontawesome470/css/font-awesome.min.css?r=<?php print $uuid; ?>">
	<link rel="stylesheet" href="../css/custom-theme/jquery.ui.all.css">
	<link rel="stylesheet" href="../css/custom-theme/jquery-ui-1.8.21.custom.css">
	<link rel="stylesheet" href="../css/jquery.timepicker.css">
  	<link rel="stylesheet" href="../css/showseeker.css">
	<!--- CSS FOR GRIDS --->	
	<link rel="stylesheet" href="css/ezgrids.css?uid=<?php print $uuid; ?>">  	  	
	<link rel="stylesheet" href="css/panel.css?uid=<?php print $uuid; ?>">
	<link rel="stylesheet" href="css/slider.css?uid=<?php print $uuid; ?>">

	<!--- JQUERY INSTANCE & JS CODE--->
	<script src="js/market.zones.js?uid=<?php print $uuid; ?>"></script>
	<script src="js/programDescription.js?uid=<?php print $uuid; ?>"></script>
	<script src="js/grid.js?uid=<?php print $uuid; ?>"></script>
	<script src="js/print.js?uid=<<?php print $uuid; ?>"></script>	
	<script src="js/proposalWeeks.js?uid=<?php print $uuid; ?>"></script>
	<script src="js/selectAllPrograms.js?uid=<?php print $uuid; ?>"></script>

	<script src="../../js/jquery-1.7.2.min.js"></script>
	<script src="../../js/ui/minified/jquery.ui.core.min.js"></script>
	<script src="../../js/ui/jquery.ui.datepicker.js"></script>
	<script src="../../js/jquery.ui.timepicker.js"></script>	
	<script src="../../js/date.js"></script>
  	<script> var station = <?php print_r($station);?></script>	

</head>


<body style="overflow-x: hidden; overflow-y: auto;">
		
	<!--- CONTROLS --->	
	<div class="top">
		<?php include_once("controls.php")?>	
	</div>

	<!-- SIDE PANEL CONTROLS -->
	<div id="panel" class="panel">
		<?php include_once("includes/panel.php")?>	
	</div>
	<div style="height:5px"></div>

	<!-- NAVIGATION AREA -->
	<div class="wkNavigator">
		<span style="width: 38px; float: left; height: 25px;"></span>
		<?php foreach($allweeks as &$wk){	
			$pDate 		= date('Y-m-d',$wk);
			$tabDate	= date('M d',$wk);
			print('<div class=weekTab id=wkNavigation'.($ii).' onclick=printDate="'.$pDate.'"><b>'.$tabDate.'</b></div>');
			$ii++;
		}?>

	</div>

	<!-- IF THE SLECTED STATION IS PART OF THE SELECTED ZONE -->
	<?php 
	if ($isvalid > 0){ 
		include_once("builder.php");
	}
	?>
	<?php include_once("includes/dialogs.php")?>
</body>
</html>


<script src="js/controller.js?uid=<?php print $uuid; ?>"></script>
<script src="js/events.js?uid=<?php print $uuid; ?>"></script>
<script>
	document.domain = "showseeker.com";
	var apiKey = '<?php print ($apiKey)?>';
	$('#sTime').val('<?php print(date('G:i',strtotime($sTime)))?>');
	$('#eTime').val('<?php print(date('G:i',strtotime($eTime)))?>');	
	$('.cellContainer').css('height',<?php print($rulerHeight)?>);

	printDate	='<?php print(date('Y-m-d',strtotime($wRange["sDate"])))?>';
</script>