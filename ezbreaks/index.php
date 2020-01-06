<?php
	session_start();
  ini_set('session.gc_maxlifetime', 2160000000);
	$uuid = uniqid();

  include_once('../config/mysqli.php');
  include_once('../inc/roles.php');
 
  if(isset($_GET['autoload']) && trim($_GET['autoload'])!=""){
      $autoloadViewer = true;
      list($autoLoadInstanceId, $autoLoadDate, $autoLoadWindow) = explode('|',base64_decode($_GET['autoload']));
  } else if(isset($_SESSION['autoload']) && trim($_SESSION['autoload'])!=""){
      $autoloadViewer = true;
      list($autoLoadInstanceId, $autoLoadDate, $autoLoadWindow) = explode('|',base64_decode($_SESSION['autoload']));
      unset($_SESSION['autoload']);
  }

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
    <link rel="stylesheet" href="../inc/foundation5/css/normalize.css">
    <link rel="stylesheet" href="../inc/foundation5/css/foundation.css">
  
    <!-- <link rel="stylesheet" href="../inc/timepicker/jquery.timepicker.css"> -->
    <link rel="stylesheet" href="../css/drk-theme/jquery-ui-1.10.4.custom.min.css">
    <link rel="stylesheet" href="../css/jquery-ui-timepicker-addon.css">
    <link rel="stylesheet" href="css/jquery.steps.css">

    <link rel="stylesheet" href="../css/skin.css">
    <link rel="stylesheet" href="../css/copyright.css">    
    <!-- title>ShowSeeker - EZ-Breaks</title -->
    <title>Breaks | ShowSeeker</title>
</head>

	<body>



<nav id="main-nav" class="top-bar mainnav" data-topbar>
  <ul class="title-area">
    <li class="name" style="width: 300px;"><img src="../images/logosm_ezbreaks.png" style="padding-left:5px;"></li>
  </ul>

  <section class="top-bar-section" data-topbar data-options="is_hover: false">
    <ul class="left">
      <!--<li><a style="background-color:black;" href="javascript:void(0);"><i class="fa fa-arrow-circle-left fa-lg"></i></a></li>-->
      <li id="menu-1"><a href="javascript:checkApplicationStatus(1);">Group Viewer</a></li>
      <li id="menu-2"><a href="javascript:checkApplicationStatus(2);">Viewer</a></li>
      <li id="menu-7" ><a href="javascript:checkApplicationStatus(7);">Breaks</a></li>
      <li id="menu-14" ><a href="javascript:checkApplicationStatus(14);">Queue</a></li>
      <li id="menu-5"><a href="javascript:checkApplicationStatus(5);">Schedule</a></li>
      <li id="menu-12"><a href="javascript:checkApplicationStatus(12);">Update Schedule</a></li>
      
      

    <?php //if($superadmin): ?>
      <li class="has-dropdown" style="display: none;" id="menu-item-superadmin">
        <a href="#" id="is_superadmin_true">Admin</a>
        <ul class="dropdown">
          <li id="menu-11"><a href="javascript:checkApplicationStatus(13);">Changes</a></li>
          <li id="menu-11"><a href="javascript:checkApplicationStatus(11);">Access</a></li>
          <li id="menu-8"><a href="javascript:checkApplicationStatus(1);dialogAddNetwork(0);">Add Network</a></li>
          <li id="menu-4"><a href="javascript:checkApplicationStatus(4);">Custom Titles</a></li>
          <li id="menu-3"><a href="javascript:checkApplicationStatus(3);">Custom Breaks</a></li>
          <!-- <li id="menu-9"><a href="javascript:checkApplicationStatus(9);">Custom Rules</a></li>
          <li id="menu-6"><a href="javascript:doEspnExcelUpload();">Update ESPN Schedule</a></li> -->
        </ul>
      </li>
    <?php //endif; ?>
    <li id="menu-13" style=""><a href="javascript:dialogHelp(1);" style="background-color: #ff9933;">Help &amp; Tutorial</a></li>
      
    
    </ul>


    <ul class="right">
    <li class="has-dropdown">
        <a href="#"><i class="fa fa-user fa-lg"></i> <span id="spn-sess-usrname"></span>&nbsp;&nbsp;<span class="label round success" id="spn-sess-corp"></span></a>
        <ul class="dropdown right">
          <li><a href="/login.php?logout=true">Logout</a></li>
        </ul>
      </li>

    </ul>


  </section>
</nav>

<br>


  <section class="sidebar ssforms">
    <br>
    <?php include("include/sidebar.php"); ?>
  </section>


  <section class="main">
    <div id="mainwrapper">
      <br style="clear:both;">
      <div id="ss-menu" class="container-content" style="overflow:hidden;">
        <div id="tab-1"><?php include("include/page.network.list.php"); ?></div>
        <div id="tab-2" style="display:none;"><?php include("include/page.viewer.php"); ?></div>
        <div id="tab-3" style="display:none;"><?php include("include/page.custom.breaks.php"); ?></div>
        <div id="tab-9" style="display:none;"><?php include("include/page.custom.breakrules.php"); ?></div>
        <div id="tab-4" style="display:none;"><?php include("include/page.custom.titles.php"); ?></div>
        <div id="tab-5" style="display:none;"><?php include("include/page.download.scheduler.php"); ?></div>
        <div id="tab-6" style="display:none;">Update ESPN Schedule</div>
        <div id="tab-7" style="display:none;"><?php include("include/page.breaks.php"); ?></div>
        <div id="tab-13" style="display:none;"><?php include("include/page.changes.php"); ?></div>
        <div id="tab-14" style="display:none;"><?php include("include/page.queue.php"); ?></div>

        <?php //if($superadmin): ?>
          <div id="tab-11" style="display:none;"><?php include("include/page.access.php"); ?></div>
        <?php //endif; ?>
        <div id="tab-12" style="display:none;"><?php include("include/page.update.scheduler.php"); ?></div>

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
  <script src='../js/ui/minified/jquery.ui.slider.min.js'></script>
  <script src='../js/ui/minified/jquery.ui.resizable.min.js'></script>
  <script src='../js/ui/minified/jquery.ui.dialog.min.js'></script>
  <script src='../js/ui/minified/jquery.ui.resizable.min.js'></script>
  <!--<script src='../js/ui/minified/jquery.ui.sortable.min.js'></script>-->
  <script src='../js/ui/minified/jquery.ui.selectable.min.js'></script>
  <script src='../inc/timepicker/jquery.ui.timepicker.js'></script>
  <script src='../js/jquery-ui-timepicker-addon.js'></script>


  <script src='../inc/foundation5/js/foundation.min.js'></script>
  

  <!--<script src='../inc/timepicker/jquery.ui.timepicker.js'></script> -->
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

  <script src='../inc/foundation/js/vendor/modernizr.js'></script>
  <script src='js/DatagridNetworks.js'></script>
  <script src='js/DatagridViewer.js'></script>
  <script src='js/DatagridCustomBreaks.js'></script>
  <script src='js/DatagridCustomTitles.js'></script>
  <script src='js/DatagridDownloadSchedule.js'></script>
  <script src='js/DatagridBreaks.js'></script>
  <script src='js/DatagridAccessNetworks.js'></script>
  <script src='js/DatagridDownloadUpdateSchedule.js'></script>
  <script src='js/DatagridNetworkSelector.js'></script>
  <script src='js/DatagridSchedulerNetChoice.js'></script>
  <script src='js/DatagridChanges.js'></script>
  <script src='js/DatagridQueue.js'></script>
  <script src='js/DatagridCustomBreakRulesets.js'></script>
  <script src='js/DatagridCustomrulewizard.js'></script>

  <script src='../js/login.session.js' id="login-session-js" data-app="breaks"></script>


  <script src='js/showseeker.js?r=<?php print $uuid; ?>'></script>
  <script src='js/sidebar.js'></script>
  <script src='js/menu.js'></script>
  <script src='js/dialogs.js'></script>
  <script src='js/windowmanager.js'></script>
  <script src='js/customrulewizard.js'></script>

  <script src="js/ckeditor/ckeditor.js"></script>
  <script src="js/ckeditor/adapters/jquery.js"></script>

  <script src="js/jqsteps/jquery.steps.min.js"></script>

  <script>
    $(document).foundation();
  </script>

  <?php if($autoloadViewer):?>
    <script type="text/javascript">
      var autoloadViewer     = true;
      var autoLoadInstanceId = '<?php print $autoLoadInstanceId; ?>';
      var autoLoadDate       = '<?php print $autoLoadDate; ?>';
      var autoLoadWindow     = '<?php print $autoLoadWindow; ?>';
    </script>
  <?php endif; ?>
	</body>
</html>