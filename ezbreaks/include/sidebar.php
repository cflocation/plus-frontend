<div id="sidebar-tab-1" style="display:none;">
  <?php include("include/sidebar.tab.1.php"); ?>
</div>


<!-- Tab 2 -  Breaks Viewer-->
<div id="sidebar-tab-2" style="display:none;">

  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label class="right inline">Group:</label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
          <ul  id="sidebar-item-group-viewer" class="button-group radius">
            <li><a id="sidebar-item-group-viewer-1" href="javascript:datagridViewer.groupByColumn('off');toggleOn('sidebar-item-group-viewer',1);" class="button" style="padding:8px;">Off</a></li>
            <li><a id="sidebar-item-group-viewer-2" href="javascript:datagridViewer.groupByColumn('date');toggleOn('sidebar-item-group-viewer',2);" class="button" style="padding:8px;">Date</a></li>
            <li><a id="sidebar-item-group-viewer-3" href="javascript:datagridViewer.groupByColumn('grouptitle');toggleOn('sidebar-item-group-viewer',3);" class="button" style="padding:8px;">Date & Time</a></li>
          </ul>
        </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Tab 3 - Custom Breaks -->
<div id="sidebar-tab-3" style="display:none;">
  <?php include("include/sidebar.tab.3.php"); ?>   
</div>


<!-- Tab 3 - Custom Titles -->
<div id="sidebar-tab-4" style="display:none;">
    <?php include("include/sidebar.tab.4.php"); ?>
</div>

<!-- Tab 5 - Download Scheduler -->
<div id="sidebar-tab-5" style="display:none;">
  <?php include("include/sidebar.tab.5.php"); ?>
</div>


<!-- tab 7 -->
<div id="sidebar-tab-7" style="display:none;">
  <?php include("include/sidebar.tab.7.php"); ?>
</div>

<!-- tab 9 -->
<div id="sidebar-tab-9" style="display:none;">
    <div class="row padder">
    <div class="small-12">
      <div class="row collapse">
        <div class="small-3 columns">
          <label class="right inline"></label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
          <button id="sidebar-custom-breaks-wizard-button" type="submit" class="button tiny radius" onclick="showCustomRuleWizard();"><i class="fa fa-plus-circle fa-lg"></i> New Custom Rule Set</button>
        </div>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- tab 11 -->
<div id="sidebar-tab-11" style="display:none;">
  <?php include("include/sidebar.tab.11.php"); ?>
</div>

<!-- tab 12 -->
<div id="sidebar-tab-12" style="display:none;">
  <?php include("include/sidebar.tab.12.php"); ?>
</div>
