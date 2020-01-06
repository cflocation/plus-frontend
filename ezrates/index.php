<?php	
	session_start();
	/*if(!isset($_SESSION['userid'])){
		header( 'Location: /login.php?logout=true' ) ;
	}*/
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
    <!-- link rel="shortcut icon" href="/icon.gif" type="image/x-icon" / -->
	 <link href="https://showseeker.s3.amazonaws.com/public-site/assets/favicon.png" name="favicon" rel="shortcut icon" type="image/png">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="cache-control" content="no-cache"> <!-- tells browser not to cache -->
    <meta http-equiv="expires" content="0"> <!-- says that the cache expires 'now' -->
    <meta http-equiv="pragma" content="no-cache"> <!-- says not to use cached stuff, if there is any -->
    <link rel="stylesheet" href="../inc/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="slickgrids/slick.grid.css">
    <link rel="stylesheet" href="slickgrids/grids.css">
    <link rel="stylesheet" href="../inc/foundation/css/normalize.css">
    <link rel="stylesheet" href="../inc/foundation/css/foundation.css">
    <script src='../inc/foundation/js/vendor/modernizr.js'></script>
    <link rel="stylesheet" href="../inc/timepicker/jquery.timepicker.css">
    <link rel="stylesheet" href="../css/drk-theme/jquery-ui-1.10.4.custom.min.css">

    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/copyright.css">    

    <title>Rates | ShowSeeker</title>
</head>



	<body>



<nav id="main-nav" class="top-bar" data-topbar>
  <ul class="title-area">
    <li class="name" style="width: 290px;"><img src="../images/logosm_ezrates.png" style="padding-left:5px;"></li>
  </ul>

  <section class="top-bar-section">
    <ul class="left">
      <!--<li><a style="background-color:black;" href="javascript:void(0);"><i class="fa fa-arrow-circle-left fa-lg"></i></a></li>-->
      <li id="menu-1"><a href="javascript:checkApplicationStatus(1);">Ratecard Manager</a></li>
      <li id="menu-2"><a href="javascript:checkApplicationStatus(2);">Pricing</a></li>
      <li id="menu-3"><a href="javascript:checkApplicationStatus(3);">Hot Programming</a></li>
      <li><a target="_blank" href="http://www.showseeker.com/tutorials/ratecard_index.php"><i class="fa fa-question-circle fa-lg"></i> Help</a></li>
    </ul>


    <ul class="right">
    <li class="has-dropdown">
        <a href="#"><i class="fa fa-user fa-lg"></i> <span id="spn-sess-usrname"><?php print $_SESSION['name']; ?></span>&nbsp;&nbsp;<span class="label round success" id="spn-sess-corp"><?php print $_SESSION['corporation']; ?></span></a>
        <ul class="dropdown right">

          <!--
          <li id="menu-5"> <a href="javascript:menuSelect('tab-5','menu-5');datagridDaypartSelected.renderGrid();">Markets</a></li>
          <li id="menu-4"><a href="javascript:menuSelect('tab-4','menu-4');">Master Daypart List</a></li>
          <li><a href="#">ShowSeeker Plus</a></li>
        -->
          
          <li><a href="/login.php?logout=true">Logout</a></li>
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
        <div id="tab-1"><?php include("include/page.ratecard.manager.php"); ?></div>
        <div id="tab-2" style="display:none;"><?php include("include/page.ratecard.pricing.php"); ?></div>
        <div id="tab-3" style="display:none;"><?php include("include/page.hotprogramming.php"); ?></div>
        <div id="tab-4" style="display:none;"><?php include("include/page.ratecard.dayparts.php"); ?></div>
        <div id="tab-5" style="display:none;"><?php include("include/page.markets.php"); ?></div>
      </div>
    </div>
  </section>


  <div id="dialog-window" style="display:none;"></div>
  <div id="dialog-window-alt" style="display:none;"></div>
	
  <div id="footer">Software developed by Visual Advertising Sales Technology. U.S. Patent No. 7,742, 946 N.Z. Patent No. 537510 Copyright © VAST 2003 - <?php echo date("Y") ?>.</div>


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
  <script src='../js/ui/minified/jquery.ui.resizable.min.js'></script>
  <script src='../js/ui/minified/jquery.ui.dialog.min.js'></script>
  <script src='../js/ui/minified/jquery.ui.resizable.min.js'></script>
  <script src='../js/ui/minified/jquery.ui.sortable.min.js'></script>
  <script src='../inc/timepicker/jquery.ui.timepicker.js'></script>
  <script src='../inc/foundation/js/foundation.min.js'></script>


  <script src='../inc/timepicker/jquery.ui.timepicker.js'></script>
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
  <script src='slickgrids/slick.formatters.js'></script>
  <script src='slickgrids/slick.editors.js'></script>
  <script src='slickgrids/slick.grid.js'></script>
  <script src='slickgrids/slick.dataview.js'></script>
  <script src='slickgrids/slick.groupitemmetadataprovider.js'></script>

  <script src='js/DatagridDayparts.js'></script>
  <script src='js/DatagridDaypartsSelected.js'></script>
  <script src='js/DatagridRatecards.js'></script>
  <script src='js/DatagridPricing.js'></script>
  <script src='js/DatagridPricingBroadcast.js'></script>
  <script src='js/DatagridHotProgramming.js'></script>
  <script src='js/showseeker.js'></script>
  <script src='js/sidebar.js'></script>
  <script src='js/menu.js'></script>
  <script src='js/dialogs.js'></script>
  <script src='js/windowmanager.js'></script>
  <script src='js/index.js'></script>


  <script>
    $(document).foundation();
  </script>
	</body>
</html>