<?php	
	session_start();
	if(!isset($_SESSION['userid'])){
		header( 'Location: login.php' ) ;
	}
	$uuid = uniqid();
?>







<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="shortcut icon" href="/icon.gif" type="image/x-icon" /> 
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="cache-control" content="no-cache"> <!-- tells browser not to cache -->
	<meta http-equiv="expires" content="0"> <!-- says that the cache expires 'now' -->
	<meta http-equiv="pragma" content="no-cache"> <!-- says not to use cached stuff, if there is any -->
	
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<title>ShowSeeker Plus - Proposal Manager</title>
	<link rel="stylesheet" href="css/custom-theme/jquery.ui.all.css?r=<?php print $uuid; ?>">
	<link rel="stylesheet" href="css/custom-theme/jquery-ui-1.8.21.custom.css?r=<?php print $uuid; ?>">
	<link rel="stylesheet" href="css/jquery.timepicker.css?r=<?php print $uuid; ?>">
	<link rel="stylesheet" href="slickgrids/slick.grid.css?r=<?php print $uuid; ?>">
	<link rel="stylesheet" href="slickgrids/grids.css?r=<?php print $uuid; ?>">
	<link rel="stylesheet" href="superfish/css/superfish.css?r=<?php print $uuid; ?>">
	<link rel="stylesheet" href="superfish/css/superfish-navbar.css?r=<?php print $uuid; ?>">	
	<!-- site and override styles-->
	<link rel="stylesheet" href="fontawesome/css/font-awesome.min.css?r=<?php print $uuid; ?>">
  	<link rel="stylesheet" href="css/showseeker.css?r=<?php print $uuid; ?>">


</head>
<?php include ('pizza/loader.php');?>
<body>


<div id="data-writer"  style="display:none;">Writing data to server</div>
<div id="fullwrapper" style="visibility:hidden;">



	<div id="sidebar-type-header" class="header orange">
		<i class="icon-search"></i>&nbsp;Search Settings
		<div id="client-icon-wrapper" style="float:right;" onclick="loadManager();" class="hander"><i style="color:white;text-shadow:0;" class="icon-book"></i></div>
	</div>



<div id="topbar">
	<?php include("superfish/menu.php"); ?>
</div>


	<div id="sidebar2">
		<?php include 'includes/sidebar.php'; ?>
	</div>


	<div id="container">

	<br style="clear:both;">

	<div id="ss-menu" class="container-content" style="overflow:hidden;">

	<div id="proposal-manager">
		<?php include 'includes/proposal.manager.php'; ?>
	</div>


	<div id="proposal-build" style="display:none;">
		<?php include 'includes/proposal.build.php'; ?>
	</div>


	<div id="proposal-download" style="display:none;">
		<?php include 'includes/download.php'; ?>
	</div>


	<div id="saved-searches" style="display:none;">
		<?php include 'includes/searches.manager.php'; ?>
	</div>




	</div>

	</div>

</div>




<!-- / Dialod Overlays / -->
<div id="dialogs">

	<!-- messages -->
	<div id="dialog-message" title="ShowSeeker Plus" style="display:none;"></div>


	<!-- beta -->
	<div id="dialog-beta" title="ShowSeeker Plus" style="display:none;"><?php include 'includes/dialog.beta.php'; ?></div>


	<!-- network list -->
	<div id="dialog-networks" title="Select Networks"><?php include 'includes/dialog.networks.php'; ?></div>

	<!-- days of week -->
	<div id="dialog-daysofweek" title="Select Days" style="display:none;"><?php include 'includes/dialog.daysofweek.php'; ?></div>

	<!-- premiere -->
	<div id="dialog-premiere" title="Select Premiere/Finale" style="display:none;"><?php include 'includes/dialog.premiere.php'; ?></div>

	<!-- genre -->
	<div id="dialog-genre" title="Select Genres"><?php include 'includes/dialog.genres.php'; ?></div>

	<!-- title -->
	<div id="dialog-title" title="Title Search - Drag choices from left column to the right column and then click Search Showseeker."><?php include 'includes/dialog.title.php'; ?></div>

	<!-- keyword -->
	<div id="dialog-keyword" title="Keyword Search"><?php include 'includes/dialog.keyword.php'; ?></div>

	<!-- actor -->
	<div id="dialog-actor" title="Actor Search  - Drag choices from left column to the right column and then click Search Showseeker."><?php include 'includes/dialog.actor.php'; ?></div>

	<!-- rename proposal -->
	<div id="dialog-proposal-rename" title="Rename Proposal"><?php include 'includes/dialog.rename.proposal.php'; ?></div>

	<!-- now searching -->
	<div id="dialog-searching" title="Searching ShowSeeker Plus"><?php include 'includes/dialog.searching.php'; ?></div>

	<!-- loading -->
	<div id="dialog-loading" title="ShowSeeker Plus"><?php include 'includes/loading.php'; ?></div>

	<!-- creating rotators overlay -->
	<div id="dialog-create-rotators" title="Rotators"><?php include 'includes/dialog.create.rotators.php'; ?></div>

	<!-- spots rate -->
	<div id="dialog-edit-lines" title="Spots & Rates"><?php include 'includes/dialog.edit.lines.php'; ?></div>

	<!-- duplicate lines -->
	<div id="dialog-duplicate-lines" title="Duplicate Lines"><?php include 'includes/dialog.copy.php'; ?></div>

	<!-- clone proposal -->
	<div id="dialog-clone-proposal" title="Copy Proposal"><?php include 'includes/dialog.clone.php'; ?></div>



	<!-- clone proposal 2-->
	<div id="dialog-clone2-proposal" title="Copy Proposal"><?php include 'includes/dialog.clone2.php'; ?></div>



	<!-- save proposal -->
	<div id="dialog-save-proposal" title="Save Proposal"><?php include 'includes/save.proposal.php'; ?></div>

	<!-- saving proposal -->
	<div id="dialog-saving-proposal" title="Save Proposal"><?php include 'includes/dialog.saving.proposal.php'; ?></div>

	<!-- creating avails -->
	<div id="dialog-creating-avails" title="Creating Lines"><?php include 'includes/dialog.creating.avails.php'; ?></div>
	
	<!-- dayparts -->
	<div id="dialog-dayparts" title="Dayparts"><?php include 'includes/dayparts.php'; ?></div>

	<!-- savesearch -->
	<div id="dialog-save-search" title="Save Search"><?php include 'includes/dialog.save.search.php'; ?></div>

	<!-- copy search -->
	<div id="dialog-copy-search" title="Save Search"><?php include 'includes/dialog.copy.search.php'; ?></div>


	<!-- flight calendar -->
	<div id="dialog-flight" title="Select the weeks from the calendar"><?php include 'includes/dialog.flight.php'; ?></div>


	<!-- flight calendar -->
	<div id="dialog-managers" title="Client Manager"></div>


	<!-- more info -->
	<div id="dialog-moreinfo" title="More Information"></div>


	<!-- avails -->
	<div id="dialog-avails" title="Avails"><?php include 'includes/dialog.avails.php'; ?></div>


	<!-- messages -->
	<div id="dialog-messages" title="Message Center" style="display:none;"></div>


	<!-- share -->
	<div id="dialog-share" title="Share Item" style="display:none;"></div>


	<!-- headers -->
	<div id="dialog-headers" title="Customize Proposal Title" style="display:none;"></div>


	<!-- user -->
	<div id="dialog-user" title="Update Account" style="display:none;"></div>

	<!-- password -->
	<div id="dialog-password" title="Change Password" style="display:none;"></div>


	<!-- modal message -->
	<div id="dialog-modal-message" title="ShowSeeker Plus" style="display:none;"></div>


	<!-- contact -->
	<div id="dialog-contact" title="Contact Us" style="display:none;"><?php include 'includes/dialog.contact.php'; ?></div>


	<!-- newsletter -->
	<div id="dialog-newsletters" title="ShowSeeker +" style="display:none;"><?php include 'includes/dialog.newsletters.php'; ?></div>

	<!-- newsletter -->
	<div id="dialog-load-newsletter" title="ShowSeeker +" style="display:none;"></div>

	<!-- dialog duplicate zones wait -->
	<div id="dialog-duplicate-lines-wait" title="ShowSeeker Plus" style="display:none;"><?php include 'includes/dialog.duplicate.wait.php'; ?></div>


	<!-- import proposals -->
	<div id="dialog-import-proposals" title="Import Proposals" style="display:none;"></div>



	<!-- image selector -->
	<div id="dialog-image-selector" title="Download Images" style="display:none;"></div>


	<!-- power point image selector -->
	<div id="dialog-image-ppt-selector" title="Select Images for your PowerPoint Download" style="display:none;"></div>



	<!-- download file -->
	<div id="dialog-download-file" title="Download Proposal" style="display:none;"></div>


	<!-- dialog chante line titles -->
	<div id="dialog-line-title" title="Change Line Titles" style="display:none;"><?php include 'includes/dialog.line.title.php'; ?></div>



	<!-- dialog eclipse -->
	<div id="dialog-eclipse" title="Download Eclipse" style="display:none;"><?php include 'includes/dialog.eclipse.php'; ?></div>




	<!-- dialog Projected -->
	<div id="dialog-projected" title="Note" style="display:none;"><?php include 'includes/dialog.projected.php'; ?></div>

	<!-- dialog PGA -->
	<div id="dialog-pga" title="Note PGA 2015" style="display:none;"><?php include 'includes/dialog.pga.php'; ?></div>


	<!-- Avails - quarters -->
	<div id="dialog-avails-quarters" title="Select Quarters" style="display:none;"><?php include 'includes/dialog.avails.quarters.php'; ?></div>
	<div id="dialog-avails-dayparts" title="Select Dayparts" style="display:none;"><?php include 'includes/dialog.avails.dayparts.php'; ?></div>
	<div id="dialog-avails-dayparts-60" title="Select Dayparts 60 Minute" style="display:none;"><?php include 'includes/dialog.avails.dayparts.60.php'; ?></div>
	<div id="dialog-avails-dayparts-30" title="Select Dayparts 30 Minute" style="display:none;"><?php include 'includes/dialog.avails.dayparts.30.php'; ?></div>

	<!-- zoom -->
	<div id="dialog-zoom" title="Browser Zoom Detected" style="display:none;">
		<br>
		We have detected that you have used the "zoom" function in your browser which is not compatible with ShowSeeker Plus.<br><br>

		This may have been accessed by using the control key on your keyboard in combination with the scroll wheel on your mouse.  
		Please roll back on the scroll wheel to the 100% view using the control key.
	</div>




	<!--- send reminder -->
	<div id="dialog-send-reminder" title="Sending Reminder" style="display:none;">
		<center>
			<h3>Sending Reminder Please Wait</h3>
			<br>
			<img src="i/ajax.gif">
		</center>
	</div>



	<!-- import proposals -->
	<div id="dialog-import-loading" title="Importing Proposals" style="display:none;">
	<center>
		<h3>Importing... Please Wait</h3>
		<br>
		<img src="i/ajax.gif">
	</center>
	</div>

</div>
<!-- / End Dialod Overlays / -->






<script type="text/javascript">
	var userid = '<?php print $_SESSION['userid'];?>';
	var tokinid = '<?php print $_SESSION['tokenid'];?>';

	window.onerror = function(msg, url, line) {
		$.post("services/log.error.php", { msg: escape(msg), url: escape(url), line: line, userid: userid, tokinid: tokinid},function(data) {
		
		});

	};


</script>


<!-- javascripts 
<script src="js/minified/jquery.js?r=<?php print $uuid; ?>"></script>
-->


<script src="slickgrids/lib/firebugx.js"></script>
<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/ui/minified/jquery.ui.core.min.js"></script>
<script src="js/ui/minified/jquery.ui.widget.min.js"></script>
<script src="js/ui/minified/jquery.ui.button.min.js"></script>
<script src="js/ui/jquery.ui.datepicker.js"></script>
<script src="js/ui/minified/jquery.ui.mouse.min.js"></script>
<script src="js/ui/minified/jquery.ui.draggable.min.js"></script>
<script src="js/ui/minified/jquery.ui.position.min.js"></script>
<script src="js/ui/minified/jquery.ui.resizable.min.js"></script>
<script src="js/ui/minified/jquery.ui.dialog.min.js"></script>
<script src="js/ui/minified/jquery.ui.resizable.min.js"></script>
<script src="js/ui/minified/jquery.ui.sortable.min.js"></script>


<script src="js/jquery.event.drag-2.0.min.js"></script>
<script src="js/jquery.event.drop-2.0.min.js"></script>


<script src="js/external/jquery.ui.timepicker.js?r=<?php print $uuid; ?>"></script>
<script src="js/external/date.js?r=<?php print $uuid; ?>"></script>
<script src="js/external/accounting.min.js?r=<?php print $uuid; ?>"></script>
<script src="js/external/detect-zoom.js?r=<?php print $uuid; ?>"></script>
<script src="js/external/keyboard.js?r=<?php print $uuid; ?>"></script>

<!--
<script src="js/ui/minified/jquery.ui.tabs.min.js?r=<?php print $uuid; ?>"></script>
-->

<!-- menu -->
<script src="superfish/js/superfish.js?r=<?php print $uuid; ?>"></script>
<script src="superfish/js/supersubs.js?r=<?php print $uuid; ?>"></script>

<!-- slickgrids -->
<script src="slickgrids/slick.core.js?r=<?php print $uuid; ?>"></script>
<script src="slickgrids/plugins/slick.cellrangedecorator.js?r=<?php print $uuid; ?>"></script>
<script src="slickgrids/plugins/slick.cellrangeselector.js?r=<?php print $uuid; ?>"></script>
<script src="slickgrids/plugins/slick.cellselectionmodel.js?r=<?php print $uuid; ?>"></script>
<script src="slickgrids/plugins/slick.rowselectionmodel.js?r=<?php print $uuid; ?>"></script>
<script src="slickgrids/plugins/slick.rowmovemanager.js?r=<?php print $uuid; ?>"></script>
<script src="slickgrids/plugins/slick.checkboxselectcolumn.js?r=<?php print $uuid; ?>"></script>
<script src="slickgrids/slick.formatters.js?r=<?php print $uuid; ?>"></script>
<script src="slickgrids/slick.editors.js?r=<?php print $uuid; ?>"></script>
<script src="slickgrids/slick.grid.js?r=<?php print $uuid; ?>"></script>
<script src="slickgrids/slick.dataview.js?r=<?php print $uuid; ?>"></script>
<script src="slickgrids/slick.groupitemmetadataprovider.js?r=<?php print $uuid; ?>"></script>

<script src="js/showseeker/functions.js?r=<?php print $uuid; ?>"></script>
<script src="js/showseeker/formatters.js?r=<?php print $uuid; ?>"></script>
<script src="js/showseeker/dialogs.js?r=<?php print $uuid; ?>"></script>

<script src="js/showseeker/showseeker.js?r=<?php print $uuid; ?>"></script>
<script src="js/showseeker/sidebar.js?r=<?php print $uuid; ?>"></script>
<script src="js/showseeker/search.js?r=<?php print $uuid; ?>"></script>

<script src="js/showseeker/datagrid.networks.js?r=<?php print $uuid; ?>"></script>
<script src="js/showseeker/datagrid.genres.js?r=<?php print $uuid; ?>"></script>
<script src="js/showseeker/datagrid.titles.js?r=<?php print $uuid; ?>"></script>
<script src="js/showseeker/datagrid.proposal.manager.js?r=<?php print $uuid; ?>"></script>
<script src="js/showseeker/datagrid.search.results.js?r=<?php print $uuid; ?>"></script>
<script src="js/showseeker/datagrid.proposal.js?r=<?php print $uuid; ?>"></script>
<script src="js/showseeker/datagrid.saved.searches.js?r=<?php print $uuid; ?>"></script>
<script src="js/showseeker/datagrid.totals.js?r=<?php print $uuid; ?>"></script>
<script src="js/showseeker/datagrid.users.js?r=<?php print $uuid; ?>"></script>
<script src="js/showseeker/datagrid.messages.js?r=<?php print $uuid; ?>"></script>
<script src="js/showseeker/datagrid.clients.js?r=<?php print $uuid; ?>"></script>
<script src="js/showseeker/datagrid.headers.js?r=<?php print $uuid; ?>"></script>
<script src="js/showseeker/datagrid.import.js?r=<?php print $uuid; ?>"></script>

<script src="js/showseeker/datasource.js?r=<?php print $uuid; ?>"></script>
<script src="js/showseeker/datasource.totals.js?r=<?php print $uuid; ?>"></script>
<script src="js/showseeker/proposal.manager.js?r=<?php print $uuid; ?>"></script>
<script src="js/showseeker/fixed.position.js?r=<?php print $uuid; ?>"></script>
<script src="js/showseeker/proposal.add.rotator.js?r=<?php print $uuid; ?>"></script>
<script src="js/showseeker/window.manager.js?r=<?php print $uuid; ?>"></script>
<script src="js/showseeker/packages.js?r=<?php print $uuid; ?>"></script>
<script src="js/showseeker/packages.menu.js?r=<?php print $uuid; ?>"></script>

<script src="js/showseeker/external.bridge.js?r=<?php print $uuid; ?>"></script>
<script src="js/showseeker/ratecards.js?r=<?php print $uuid; ?>"></script>
<script src="js/showseeker/download.js?r=<?php print $uuid; ?>"></script>
<script src="js/showseeker/zones.by.market.js?r=<?php print $uuid; ?>"></script>


<?php if($_GET['debug'] == true): ?>
	<script type="text/javascript" src="https://getfirebug.com/firebug-lite-debug.js"></script>
<?php endif; ?>

</body>
</html>
