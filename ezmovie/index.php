<?php
  session_start();
  $uuid = uniqid();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="cache-control" content="no-cache"> <!-- tells browser not to cache -->
    <meta http-equiv="expires" content="0"> <!-- says that the cache expires 'now' -->
    <meta http-equiv="pragma" content="no-cache"> <!-- says not to use cached stuff, if there is any -->
	 <link href="https://showseeker.s3.amazonaws.com/public-site/assets/favicon.png" name="favicon" rel="shortcut icon" type="image/png">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="slickgrids/slick.grid.css">
    <link rel="stylesheet" href="slickgrids/grids.css">
    <link rel="stylesheet" href="../inc/foundation/css/normalize.css">
    <link rel="stylesheet" href="../inc/foundation/css/foundation.css">
    <link rel="stylesheet" href="../css/drk-theme/jquery-ui-1.10.4.custom.min.css">
    <link rel="stylesheet" href="../css/jquery-ui-timepicker-addon.css">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Movie Manager | ShowSeeker</title>
</head>



<body>

	<nav id="main-nav" data-options="is_hover: false" class="top-bar" data-topbar>
		<ul class="title-area">
			<li class="name" style="width: 290px;"><img src="../images/logosm_ezimages.png" style="padding-left:5px;"></li>
		</ul>
	
	  	<section class="top-bar-section">
		    <ul class="left">
		      <li id="menu-1"><a href="javascript:checkApplicationStatus(1);">Movie List</a></li>
		    </ul>
		
		    <ul class="right">
			    <li class="has-dropdown">
				<a href="#"><i class="fa fa-user fa-lg"></i> <span id="spn-sess-usrname"><?php print (isset($_SESSION['name']))?$_SESSION['name']:''; ?></span>&nbsp;&nbsp;<span class="label round success" id="spn-sess-corp"><?php print (isset($_SESSION['corporation']))?$_SESSION['corporation']:''; ?></span></a>
		        	<ul class="dropdown right">
						<li id="li-logout-link"><a href="/login.php?logout=true">Logout</a></li>
					</ul>
				</li>
		    </ul>
		</section>
	</nav>

	<br>


	<section class="sidebar">
		<br>
		<?php include("include/sidebar.php"); ?>
	</section>


	<section class="main">
		<div id="mainwrapper">
			<br style="clear:both;">
			<div id="ss-menu" class="container-content" style="overflow:hidden;">
				<div id="tab-1"><?php include("include/tab1.php"); ?></div>
			</div>
		</div>
	</section>


	<div id="dialog-window" style="display:none;"></div>
	<div id="dialog-window-alt" style="display:none;"></div>




	<!-- Latest compiled and minified JavaScript -->
	<script src='../js/jquery-1.7.2.min.js'></script>
	<script src='../js/jquery.event.drag-2.0.min.js'></script>
	<script src='../js/jquery.event.drop-2.0.min.js'></script>
	<script src='../js/ui/minified/jquery.ui.core.min.js'></script>
	<script src='../js/ui/minified/jquery.ui.widget.min.js'></script>
	<script src='../js/ui/jquery.ui.datepicker.js'></script>
	<script src='../js/ui/minified/jquery.ui.mouse.min.js'></script>
	<script src='../js/ui/minified/jquery.ui.draggable.min.js'></script>
	<script src='../js/ui/minified/jquery.ui.position.min.js'></script>
	<script src='../js/ui/minified/jquery.ui.slider.min.js'></script>
	<script src='../js/ui/minified/jquery.ui.resizable.min.js'></script>
	<script src='../js/ui/minified/jquery.ui.dialog.min.js'></script>
	<script src='../js/ui/minified/jquery.ui.resizable.min.js'></script>
	<script src='../inc/timepicker/jquery.ui.timepicker.js'></script>
	<script src='../inc/foundation/js/foundation.min.js'></script>
	<script src='../inc/foundation/js/foundation/foundation.slider.js'></script>
	<script src='../js/jquery-ui-timepicker-addon.js'></script>
	
	<script src='../js/date.js'></script>
	
	<script src='slickgrids/lib/firebugx.js'></script>
	<script src='slickgrids/slick.core.js'></script>
	<script src='slickgrids/plugins/slick.cellrangedecorator.js'></script>
	<script src='slickgrids/plugins/slick.cellrangeselector.js'></script>
	<script src='slickgrids/plugins/slick.cellselectionmodel.js'></script>
	<script src='slickgrids/plugins/slick.rowselectionmodel.js'></script>
	<script src='slickgrids/plugins/slick.rowmovemanager.js'></script>
	<script src='slickgrids/plugins/slick.checkboxselectcolumn.js'></script>
	<script src='slickgrids/plugins/slick.cellexternalcopymanager.js'></script>
	<script src='slickgrids/slick.formatters.js?v=20171215'></script>
	<script src='slickgrids/slick.editors.js'></script>
	<script src='slickgrids/slick.grid.js'></script>
	<script src='slickgrids/slick.dataview.js'></script>
	<script src='slickgrids/slick.groupitemmetadataprovider.js'></script>
	
	<script src='../inc/foundation/js/vendor/modernizr.js'></script>
	<script src='js/DatagridShowList.js?v=20171215'></script>
	
	<script src='../js/login.session.js' id="login-session-js" data-app="movies"></script>
	
	<script src='js/showseeker.js?v=20180915'></script>
	<script src='js/sidebar.js?v=20171215'></script>
	<script src='js/menu.js?v=20171215'></script>
	<script src='js/dialogs.js?v=20171215'></script>
	<script src='js/windowmanager.js?v=20171215'></script>
	
	
	<script>$(document).foundation();</script>
	
</body>

</html>