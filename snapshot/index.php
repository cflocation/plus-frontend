<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	 <link href="https://showseeker.s3.amazonaws.com/public-site/assets/favicon.png" name="favicon" rel="shortcut icon" type="image/png">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="cache-control" content="no-cache"> <!-- tells browser not to cache -->
	<meta http-equiv="expires" content="0"> <!-- says that the cache expires 'now' -->
	<meta http-equiv="pragma" content="no-cache"> <!-- says not to use cached stuff, if there is any -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<title>ShowSeeker | SnapShot</title>
	
	<?php $uuid = uniqid(); ?>
	
	<link rel="stylesheet" href="css/custom-theme/jquery.ui.all.css?r=<?php print $uuid; ?>">
	<link rel="stylesheet" href="css/custom-theme/jquery-ui-1.8.21.custom.css?r=<?php print $uuid; ?>">
	<link rel="stylesheet" href="css/jquery.timepicker.css?r=<?php print $uuid; ?>">
	<link rel="stylesheet" href="../inc/slickgrids/slick.grid.css?r=<?php print $uuid; ?>">
	<link rel="stylesheet" href="../inc/slickgrids/grids.css?r=<?php print $uuid; ?>">
	<link rel="stylesheet" href="../inc/superfish/css/superfish.css?r=<?php print $uuid; ?>">
	<link rel="stylesheet" href="../inc/superfish/css/superfish-navbar.css?r=<?php print $uuid; ?>">

	<!-- site and override styles-->
	<link rel="stylesheet" href="../inc/fontawesome4/css/font-awesome.min.css?r=<?php print $uuid; ?>">
  	<link rel="stylesheet" href="css/showseeker.css?r=<?php print $uuid;?>">
  	<link rel="stylesheet" href="css/ss.css?r=<?php print $uuid; ?>">
  	<link rel="stylesheet" href="css/dialogs.css?r=<?php print $uuid; ?>">
  	<link rel="stylesheet" href="css/standard.css?r=<?php print $uuid; ?>">
  	<link rel="stylesheet" href="css/snapshot.css?r=<?php print $uuid; ?>">
</head>
<body>




<div id="data-writer"  style="display:none;">Writing data to server</div>

<div id="fullwrapper" style="visibility:hidden;">
	<div id="sidebar-type-header" class="orange" align="center">
		<img src="i/snapshot_tiny.png">
	</div>
	
	<div id="topbar mainnav">
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
	<div id="footer">SnapShot - Software developed by Visual Advertising Sales Technology. U.S. Patent No. 7,742, 946 N.Z. Patent No. 537510 Copyright © VAST 2003 - <?php echo date("Y") ?>.</div>
</div>




<!-- / Dialod Overlays / -->
<div id="dialogs">
	<!-- search -->
	<div id="dialog-networks" 	title="Select Networks"><?php include 'includes/dialogs/networks.php'; ?></div>
	<div id="dialog-daysofweek" title="Select Days" style="display:none;"><?php include 'includes/dialogs/days.php'; ?></div>
	<div id="dialog-premiere" 	title="Select Premiere/Finale" style="display:none;"><?php include 'includes/dialogs/premiere.php'; ?></div>
	<div id="dialog-genre" 		title="Select Genres"><?php include 'includes/dialogs/genre.php'; ?></div>
	<div id="dialog-title" 		title="Title Search"><?php include 'includes/dialogs/title.php'; ?></div>
	<div id="dialog-keyword" 	title="Keyword Search"><?php include 'includes/dialogs/keyword.php'; ?></div>
	<div id="dialog-dayparts" 	title="Dayparts"><?php include 'includes/dialogs/dayparts.php'; ?></div>
	<div id="dialog-demographics" title="Networks Demographics"><?php include 'includes/dialogs/demographics.php'; ?></div>		
	<div id="dialog-quarters" 	title="Quarters"><?php include 'includes/dialogs/quarters.php'; ?></div>		


	<!-- global dialog window -->
	<div id="dialog-window" title="ShowSeeker SnapShot" style="display:none;"></div>
	<div id="dialog-window-db" title="ShowSeeker SnapShot" style="display:none;"></div>
	<div id="dialog-comp-view" title="ShowSeeker SnapShot" style="display: none;"><?php include 'includes/dialogs/comp.view.php';?></div>
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
<script src="../js/ui/minified/jquery.ui.tabs.min.js"></script>

<script src="../js/jquery.event.drag-2.0.min.js"></script>
<script src="../js/jquery.event.drop-2.0.min.js"></script>
<script src="../js/date.js?r=<?php print $uuid; ?>"></script>

<!-- menu -->
<script src="../inc/superfish/js/superfish.js?r=<?php print $uuid; ?>"></script>
<script src="../inc/superfish/js/supersubs.js?r=<?php print $uuid; ?>"></script>

<!-- slickgrids -->
<script src="../inc/slickgrids/slick.core.js?r=<?php print $uuid; ?>"></script>
<script src="../inc/slickgrids/plugins/slick.cellrangedecorator.js?r=<?php print $uuid; ?>"></script>
<script src="../inc/slickgrids/plugins/slick.cellrangeselector.js?r=<?php print $uuid; ?>"></script>
<script src="../inc/slickgrids/plugins/slick.cellselectionmodel.js?r=<?php print $uuid; ?>"></script>
<script src="../inc/slickgrids/plugins/slick.rowselectionmodel.js?r=<?php print $uuid; ?>"></script>
<script src="../inc/slickgrids/plugins/slick.rowmovemanager.js?r=<?php print $uuid; ?>"></script>
<script src="../inc/slickgrids/plugins/slick.checkboxselectcolumn.js?r=<?php print $uuid; ?>"></script>
<script src="js/slick.formatters.js?r=<?php print $uuid; ?>"></script>
<script src="js/slick.editors.js?r=<?php print $uuid; ?>"></script>
<script src="../inc/slickgrids/slick.grid.js?r=<?php print $uuid; ?>"></script>
<script src="../inc/slickgrids/slick.dataview.js?r=<?php print $uuid; ?>"></script>
<script src="../inc/slickgrids/slick.groupitemmetadataprovider.js?r=<?php print $uuid; ?>"></script>
<script src="../inc/slickgrids/lib/firebugx.js"></script>


<script src="js/dialogs.js?r=<?php print $uuid; ?>"></script>
<script src="js/formatters.js?r=<?php print $uuid; ?>"></script>
<script src="js/functions.js?r=<?php print $uuid; ?>"></script>
<script src="js/searches.js?r=<?php print $uuid; ?>"></script>
<script src="js/showseeker.js?r=<?php print $uuid; ?>"></script>
<script src="js/sidebar.js?r=<?php print $uuid; ?>"></script>
<script src="js/solr.search.js?r=<?php print $uuid; ?>"></script>
<script src="js/datagrid.genres.js?r=<?php print $uuid; ?>"></script>
<script src="js/datagrid.networks.js?r=<?php print $uuid; ?>"></script>
<script src="js/datagrid.proposal.js?r=<?php print $uuid; ?>"></script>
<script src="js/datagrid.proposal.manager.js?r=<?php print $uuid; ?>"></script>
<script src="js/datagrid.search.results.js?r=<?php print $uuid; ?>"></script>
<script src="js/datagrid.titles.js?r=<?php print $uuid; ?>"></script>
<script src="js/datasource.js?r=<?php print $uuid; ?>"></script>
<script src="js/proposal.manager.js?r=<?php print $uuid; ?>"></script>
<script src="js/fixed.position.js?r=<?php print $uuid; ?>"></script>
<script src="js/proposal.save.js?r=<?php print $uuid; ?>"></script>
<script src="js/window.manager.js?r=<?php print $uuid; ?>"></script>
<script src="js/download.js?r=<?php print $uuid; ?>"></script>
<script src="js/event.triggers.js?r=<?php print $uuid; ?>"></script>
<script src="js/zonesManager.js?r=<?php print $uuid; ?>"></script>
<script src="js/param.validator.js?r=<?php print $uuid; ?>"></script>
<script src="js/quarters.js?r=<?php print $uuid; ?>"></script>
<script src="js/multiselect.js?r=<?php print $uuid; ?>"></script>
<script src="js/proposal.columns.js?r=<?php print $uuid; ?>"></script>


<script src="js/log.events.js?r=<?php print $uuid; ?>"></script>
<script src="js/mixPanel.js?r=<?php print $uuid; ?>"></script>
 
</body>
</html>
