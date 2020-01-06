<?php
	session_set_cookie_params(3600000,"/");
	session_start();
	ini_set("display_errors",1);
	$uuid = uniqid();
	$adminid = isset($_GET['adminid'])?$_GET['adminid']:0;
	if($_SERVER['SERVER_NAME'] == 'managed.showseeker.com'){
		header('Location: https://plus.showseeker.com/plus');
	}	
	include('updatedAt.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Plus | ShowSeeker</title>	
	<meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://showseeker.s3.amazonaws.com/public-site/assets/favicon.png" name="favicon" rel="shortcut icon" type="image/png">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<meta http-equiv="expires" content="0"> <!-- says that the cache expires 'now' -->
	<meta http-equiv="pragma" content="no-cache"> <!-- says not to use cached stuff, if there is any -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	

	<link rel="stylesheet" href="css/custom-theme/jquery.ui.all.css?r=<?php print $uuid; ?>">
	<link rel="stylesheet" href="css/custom-theme/jquery-ui-1.8.21.custom.css?r=<?php print $uuid; ?>">
	<link rel="stylesheet" href="css/jquery.timepicker.css?r=<?php print $uuid; ?>">
	<link rel="stylesheet" href="../inc/slickgrids/slick.grid.css?r=<?php print $uuid; ?>">
	<link rel="stylesheet" href="../inc/slickgrids/grids.css?r=<?php print $uuid; ?>">
	<link rel="stylesheet" href="../inc/slickgrids/controls/slick.columnpicker.css" type="text/css"/>
	<link rel="stylesheet" href="../inc/superfish/css/superfish.css?r=<?php print $uuid; ?>">
	<link rel="stylesheet" href="../inc/superfish/css/superfish-navbar.css?r=<?php print $uuid; ?>">

	<!-- site and override styles-->
	<link rel="stylesheet" href="../inc/fontawesome470/css/font-awesome.min.css?r=<?php print $uuid; ?>">
  	<link rel="stylesheet" href="css/showseeker.css?r=<?php print $uuid; ?>">
  	<link rel="stylesheet" href="css/ss.css?r=<?php print $uuid; ?>">
  	<link rel="stylesheet" href="css/dialogs.css?r=<?php print $uuid; ?>">
  	<link rel="stylesheet" href="css/standard.css?r=<?php print $uuid; ?>">
  	<link rel="stylesheet" href="css/showcard.css?r=<?php print $uuid; ?>">
  	<link rel="stylesheet" href="css/ppt.css?r=<?php print $uuid; ?>">
  	<link rel="stylesheet" href="css/spots.by.day.css?r=<?php print $uuid; ?>">
  	<link rel="stylesheet" href="css/banner.css?r=<?php print $uuid; ?>">
  	<link rel="stylesheet" href="css/ratings.css?r=<?php print $uuid; ?>">
  	
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
	
	  ga('create', 'UA-93179105-2', 'auto');
	  ga('send', 'pageview');
	</script>
	<script src="js/mixPanel.js"></script>	
	<script src="js/log.events.js"></script>
	<script>var adminid= <?php print $adminid; ?></script>
</head>
<body>
<div id="data-writer"  style="display:none;">Writing data to server</div>

<div id="fullwrapper" style="visibility:hidden;">
	<div id="sidebar-type-header" class="header orange">
		<i class="fa fa-search"></i>&nbsp;Search Settings
		<!-- div id="client-icon-wrapper" style="float:right;" onclick="loadManager();" class="hander"><i class="fa fa-book"></i></div -->
	</div>
	<div id="topbar">
		<?php include("includes/menu.php"); ?>
	</div>

	<div id="sidebar2">
		<?php include 'includes/sidebar.php'; ?>
	</div>
	<div id="container">
		<div id="ss-menu" class="container-content" style="overflow:hidden;">
			<div id="proposal-manager"><?php include 'includes/proposal.manager.php'; ?></div>
			<div id="proposal-build" style="display:none;"><?php include 'includes/proposal.build.php'; ?></div>
			<div id="proposal-download" style="display:none;"><?php include 'includes/download.php'; ?></div>
			<div id="saved-searches" style="display:none;"><?php include 'includes/searches.manager.php'; ?></div>
		</div>
	</div>
	<div id="footer">GOPlus v1.5.42 - Software developed by Visual Advertising Sales Technology. U.S. Patent No. 7,742, 946 N.Z. Patent No. 537510 Copyright © VAST 2003 - <?php echo date("Y") ?>.</div>
</div>

<!-- / Dialod Overlays / -->
<div id="dialogs">
	<!-- search -->
	<div id="dialog-networks" 	title="Select Networks"><?php include 'includes/dialogs/networks.php'; ?></div>
	<!-- div id="dialog-zones" 		title="Search Zone"><?php include 'includes/dialogs/zones.php'; ?></div -->
	<div id="dialog-daysofweek" title="Select Days" style="display:none;"><?php include 'includes/dialogs/days.php'; ?></div>
	<div id="dialog-premiere" 	title="Select Premiere/Finale" style="display:none;"><?php include 'includes/dialogs/premiere.php'; ?></div>
	<div id="dialog-genre" 		title="Select Genres"><?php include 'includes/dialogs/genre.php'; ?></div>
	<div id="dialog-tvr" 		title="Select Tv Ratings"><?php include 'includes/dialogs/tvr.php'; ?></div>
	<div id="dialog-title" 		title="Title Search"><?php include 'includes/dialogs/title.php'; ?></div>
	<div id="dialog-keyword" 	title="Keyword Search"><?php include 'includes/dialogs/keyword.php'; ?></div>
	<div id="dialog-actor" 		title="Actor Search"><?php include 'includes/dialogs/actor.php'; ?></div>
	<div id="dialog-dayparts" 	title="Dayparts"><?php include 'includes/dialogs/dayparts.php'; ?></div>
	<div id="dialog-demographics" title="Networks Demographics"><?php include 'includes/dialogs/demographics.php'; ?></div>	
	<div id="dialog-decades" 	title="Movies by Decade"><?php include 'includes/dialogs/decades.php'; ?></div>		


	<!-- Avails - quarters -->
	<div id="dialog-avails-quarters" title="Select Quarters" style="display:none;"><?php include 'includes/dialogs/avails.quarters.php'; ?></div>
	<div id="dialog-avails-dayparts" title="Select Dayparts" style="display:none;"><?php include 'includes/dialogs/avails.dayparts.php'; ?></div>
	<div id="dialog-avails-dayparts-60" title="Select Dayparts 60 Minute" ><?php include 'includes/dialogs/avails.dayparts.60.php'; ?></div>
	<div id="dialog-avails-dayparts-30" title="Select Dayparts 30 Minute" style="display:none;"><?php include 'includes/dialogs/avails.dayparts.30.php'; ?></div>

	<!-- Media/Image selector -->
	<div id="dialog-image-ppt-selector" title="PowerPoint Download Settings" style="display:none;"></div>
	<div id="dialog-image-selector" title="Download Images" style="display:none;"></div>
	<div id="dialog-disclaimer" title="ShowSeeker" style="display: none;"><?php include 'includes/dialogs/disclaimer.php';?></div>
	<div id="dialog-ratings" 	title="Ratings" style="display: none;"><?php include 'includes/ratings.php';?></div>

	<!-- global dialog window -->
	<div id="dialog-window" title="ShowSeeker Plus" style="display:none;"></div>
	<div id="dialog-window-db" title="ShowSeeker Plus" style="display:none;"></div>
	<div id="dialog-spots-by-day" title="Spots By Day" style="display:none;"></div>
	<div id="dialog-comp-view" title="ShowSeeker" style="display: none;"><?php include 'includes/dialogs/comp.view.php';?></div>
</div>
<!-- / End Dialod Overlays / -->


<script src="../js/jquery-1.7.2.min.js?r=<?php print $uuid; ?>"></script>
<script src="../inc/timepicker/jquery.ui.timepicker.js?r=<?php print $uuid; ?>"></script>
<script src="../js/accounting.min.js"></script>
<script src="../js/ui/minified/jquery.ui.core.min.js"></script>
<script src="../js/ui/minified/jquery.ui.widget.min.js"></script>
<script src="../js/ui/minified/jquery.ui.button.min.js"></script>
<script src="../js/ui/jquery.ui.datepicker.js"></script>
<script src="../js/ui/minified/jquery.ui.mouse.min.js"></script>
<script src="../js/ui/minified/jquery.ui.draggable.min.js"></script>
<script src="../js/ui/minified/jquery.ui.position.min.js"></script>
<script src="../js/ui/minified/jquery.ui.resizable.min.js"></script>
<script src="../js/ui/minified/jquery.ui.dialog.min.js"></script>
<script src="../js/ui/minified/jquery.ui.resizable.min.js"></script>
<script src="../js/ui/minified/jquery.ui.sortable.min.js"></script>
<script src="../js/ui/minified/jquery.ui.accordion.min.js"></script>
<script src="../js/ui/minified/jquery.ui.tabs.min.js"></script>

<script src="../js/jquery.event.drag-2.0.min.js"></script>
<script src="../js/jquery.event.drop-2.0.min.js"></script>
<script src="../js/date.js?r=<?php print $uuid; ?>"></script>

<!-- menu -->
<script src="../inc/superfish/js/superfish.js?r=<?php print $uuid; ?>"></script>
<script src="../inc/superfish/js/supersubs.js?r=<?php print $uuid; ?>"></script>

<!-- slickgrids -->
<script src="../inc/slickgrids/plugins/slick.cellrangedecorator.js?r=<?php print $uuid; ?>"></script>
<script src="../inc/slickgrids/plugins/slick.cellrangeselector.js?r=<?php print $uuid; ?>"></script>
<script src="../inc/slickgrids/plugins/slick.cellselectionmodel.js?r=<?php print $uuid; ?>"></script>
<script src="../inc/slickgrids/plugins/slick.rowselectionmodel.js?r=<?php print $uuid; ?>"></script>
<script src="../inc/slickgrids/plugins/slick.rowmovemanager.js?r=<?php print $uuid; ?>"></script>
<script src="../inc/slickgrids/plugins/slick.checkboxselectcolumn.js?r=<?php print $uuid; ?>"></script>
<script src="../inc/slickgrids/controls/slick.columnpicker.js?r=<?php print $uuid; ?>"></script>
<script src="js/slick.formatters.js?r=<?php print $uuid; ?>"></script>
<script src="js/slick.autotooltips.js?r=<?php print $uuid; ?>"></script>
<script src="js/slick.editors.js?r=<?php print $uuid; ?>"></script>

<script src="../inc/slickgrids/slick.core.js?r=<?php print $uuid; ?>"></script>
<script src="../inc/slickgrids/slick.dataview.js?r=<?php print $uuid; ?>"></script>
<script src="../inc/slickgrids/slick.grid.js?r=<?php print $uuid; ?>"></script>
<script src="../inc/slickgrids/slick.grid.v2.js?r=<?php print $uuid; ?>"></script>


<!-- script src="http://mleibman.github.io/SlickGrid/slick.grid.js"></script -->

<script src="../inc/slickgrids/slick.groupitemmetadataprovider.js?r=<?php print $uuid; ?>"></script>
<script src="../inc/slickgrids/lib/firebugx.js"></script>


<script src="js/dialogs.js?r=<?php print $uuid; ?>"></script>
<script src="js/formatters.js?r=<?php print $uuid; ?>"></script>
<script src="js/functions.js?ver=<?php print $uuid; ?>"></script>
<script src="js/functionsGoPlus.js?ver=<?php print $uuid; ?>"></script>
<script src="js/functions.sc.js?ver=<?php print $uuid; ?>"></script>

<script src="js/functions.v1.5.js?ver=<?php print $uuid; ?>"></script>

<script src="js/saved.searches.js?r=<?php print $uuid; ?>"></script>
<script src="js/searches.js?r=<?php print $uuid; ?>"></script>
<script src="js/showseeker.js?r=<?php print $uuid; ?>"></script>
<script src="js/show.info.js?r=<?php print $uuid; ?>"></script>
<script src="js/sidebar.js?r=<?php print $uuid; ?>"></script>
<script src="js/solr.search.js?r=<?php print $uuid; ?>"></script>
<script src="js/datagrid.clients.js?r=<?php print $uuid; ?>"></script>
<script src="js/datagrid.genres.js?r=<?php print $uuid; ?>"></script>
<script src="js/datagrid.headers.js?r=<?php print $uuid; ?>"></script>
<script src="js/datagrid.import.js?r=<?php print $uuid; ?>"></script>
<script src="js/datagrid.messages.js?r=<?php print $uuid; ?>"></script>
<script src="js/datagrid.networks.js?r=<?php print $uuid; ?>"></script>
<script src="js/datagrid.proposal.js?r=<?php print $uuid; ?>"></script>
<script src="js/datagrid.proposal.manager.js?r=<?php print $uuid; ?>"></script>
<script src="js/datagrid.search.results.js?r=<?php print $uuid; ?>"></script>
<script src="js/datagrid.saved.searches.js?r=<?php print $uuid; ?>"></script>
<script src="js/datagrid.surveys.js?r=<?php print $uuid; ?>"></script>
<script src="js/datagrid.titles.js?r=<?php print $uuid; ?>"></script>
<script src="js/datagrid.totals.bc.js?r=<?php print $uuid; ?>"></script>
<script src="js/datagrid.totals.sc.js?r=<?php print $uuid; ?>"></script>
<script src="js/datagrid.users.js?r=<?php print $uuid; ?>"></script>

<!-- script src="js/datagrid.zones.js?r=<?php print $uuid; ?>"></script -->

<script src="js/datagrid.custom.package.js?r=<?php print $uuid; ?>"></script>
<script src="js/datasource.js?r=<?php print $uuid; ?>"></script>
<script src="js/datasource.totals.js?r=<?php print $uuid; ?>"></script>
<script src="js/line.effective.days.js?r=<?php print $uuid; ?>"></script>
<script src="js/proposal.manager.js?r=<?php print $uuid; ?>"></script>
<script src="js/fixed.position.js?r=<?php print $uuid; ?>"></script>
<script src="js/flight.dates.calendar.js?r=<?php print $uuid; ?>"></script>
<script src="js/proposal.add.rotator.js?r=<?php print $uuid; ?>"></script>
<script src="js/proposal.save.js?r=<?php print $uuid; ?>"></script>
<script src="js/proposal.extras.js?r=<?php print $uuid; ?>"></script>
<script src="js/window.manager.js?r=<?php print $uuid; ?>"></script>
<script src="js/packages.js?r=<?php print $uuid; ?>"></script>
<script src="js/packages.menu.js?r=<?php print $uuid; ?>"></script>
<script src="js/external.bridge.js?r=<?php print $uuid; ?>"></script>
<script src="js/ratecards.js?r=<?php print $uuid; ?>"></script>
<script src="js/download.js?r=<?php print $uuid; ?>"></script>
<script src="js/event.triggers.js?r=<?php print $uuid; ?>"></script>

<script src="js/zonesManager.js?r=<?php print $uuid; ?>"></script>
<script src="js/param.validator.js?r=<?php print $uuid; ?>"></script>

<script src="js/lines.by.day.js?r=<?php print $uuid; ?>"></script>

<!-- showcards -->
<script src="js/showcard.js?r=<?php print $uuid; ?>"></script>
<script src="js/showcardapi.js?r=<?php print $uuid; ?>"></script>

<script src="js/proposal.columns.js?r=<?php print $uuid; ?>"></script>
<script src="js/rotator.params.js?r=<?php print $uuid; ?>"></script>
<script src="js/search.marathons.js?r=<?php print $uuid; ?>"></script>
<script src="js/ratings.js?v=<?php print $uuid; ?>"></script>
<script src="js/ratings.events.js?v=<?php print $uuid; ?>"></script>
<script src="js/ratings.functions.js?v=<?php print $uuid; ?>"></script>
<script src="js/ip.js?v=<?php print $uuid; ?>"></script>
</body>
</html>
