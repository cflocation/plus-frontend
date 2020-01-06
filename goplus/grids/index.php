<!DOCTYPE html>
<?php 
	date_default_timezone_set('America/Los_Angeles');
	$uuid = uniqid();	
?>
<html lang="en">
<head>
	<link href="https://showseeker.s3.amazonaws.com/public-site/assets/favicon.ico" name="favicon" rel="shortcut icon" type="image/png">
	<meta http-equiv="Expires" content="0">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="cache-control" value="no-cache, no-store, must-revalidate">
	<meta http-equiv="Expires" content="Mon, 01 Jan 1990 00:00:01 GMT">

	<title>Grids | ShowSeeker</title>
	


	<link rel="stylesheet" href="css/ui/jquery-ui.structure.min.css">
	<link rel="stylesheet" href="css/ui/jquery-ui.theme.min.css">
	<link rel="stylesheet" href="../css/custom-theme/jquery-ui-1.8.21.custom.css?r=<?php print $uuid; ?>">			
	<link rel="stylesheet" href="../../inc/fontawesome470/css/font-awesome.min.css?r=<?php print $uuid; ?>">
	<link rel="stylesheet" href="../css/showcard.css?r=<?php print $uuid; ?>">
  	<link rel="stylesheet" href="../css/showseeker.css?r=<?php print $uuid; ?>">
	<link rel="stylesheet" href="css/panel.css?uid=<?php print $uuid; ?>">
	<link rel="stylesheet" href="css/slider.css?uid=<?php print $uuid; ?>">	
	<link rel="stylesheet" href="css/ezgrids.css?uid=<?php print $uuid; ?>">  	
	<link rel="stylesheet" href="css/showCounts.css?uid=<?php print $uuid; ?>">  	
	
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
	<script src="js/lib/jquery.ui.datepicker.js"></script>	
	<script src="../../js/date.js"></script>	
</head>


<body style="overflow-x: hidden; overflow-y: auto; width: 100%; margin:0 auto; }">
	<div id="page-wrap">

		<!-- SHOWCARD PANEL -->
		<div id="showcard" 		class="showCard"></div>
	
		<!--- MAIN CONTROLS --->	
		<div class="top">
			<?php include_once("includes/controls.php")?>
		</div>
		
		<div class="top2">
			<?php include_once("includes/settings.php")?>
		</div>
	
		<!-- SIDE PANEL CONTROLS -->
		<div id="panel" class="panel">
			<?php include_once("includes/panel.php")?>	
		</div>
	
		<!-- VERTICAL SEPARATOR -->
		<div style="height:5px"></div>
	
		<!-- TABS CONTAINER -->
		<div id="tabs">
			<!-- LOADING OVELAY -->
			<div id="overlay">
				<div class="loading"><i>ShowSeeker Grids</i> Loading ...<br/><br/><img src="../i/ajax.gif"></div>
			</div>				
			<!-- OUT OF PROGRAMMING -->
			<div id="noprogramming" style="display: none; font-size: 14pt; color: #aaa;"><center><br><br><p>No schedules found in the selected flight dates.</p></center></div>	

			<!-- SCHEDULES SECTION -->		
			<div id="ezgridSchedules" class="programmingArea"></div>
		</div>	
			
		<?php include_once("includes/dialogs.php")?>
	</div>
	
	<script src="js/market.zones.js?uid=<?php print $uuid; ?>"></script>	
	<script src="js/build.schedules.js?uid=<?php print $uuid; ?>"></script>
	<script src="js/controller.js?uid=<?php print $uuid; ?>"></script>
	<script src="js/events.js?uid=<?php print $uuid; ?>"></script>
	<script src="js/grid.js?uid=<?php print $uuid; ?>"></script>
	<script src="js/print.js?uid=<<?php print $uuid; ?>"></script>
	<script src="js/proposalWeeks.js?uid=<?php print $uuid; ?>"></script>
	<script src="js/ratings.js?uid=<?php print $uuid; ?>"></script>
	<script src="js/addShows.js?uid=<?php print $uuid; ?>"></script>
	<script src="js/showDescription.js?uid=<?php print $uuid; ?>"></script>
	<script src="js/showcard.js?uid=<?php print $uuid; ?>"></script>
	<script src="js/dropdownlist.js?uid=<?php print $uuid; ?>"></script>
	<script src="js/lib/enscroll-0.6.2.min.js?uid=<?php print $uuid; ?>"></script>

</body>
</html>


