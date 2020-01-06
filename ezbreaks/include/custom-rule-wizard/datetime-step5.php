<?php
session_start();
include_once('../../../config/database.php');

$userid = $_SESSION['userid'];
$corporationid = $_SESSION['corporationid'];
?>
<p style="margin-top:5px;" id="custom-rule-wizard-task1-step5-title">Select the Times</p>
<div class="row padder">
  <div class="small-7">
    <div class="row collapse">
      <div class="small-3 columns">
        <label for="custom-rule-wizard-task1-starttime" class="right inline"><p>Rule Start Time:</p></label>
      </div>

      <div class="row collapse">
      <div class="small-4 columns">
        <input id="custom-rule-wizard-task1-starttime" type="text"/>
      </div>
      </div>
    </div>
  </div>
</div>
<div class="row padder">
  <div class="small-7">
    <div class="row collapse">
      <div class="small-3 columns">
        <label for="custom-rule-wizard-task1-endtime" class="right inline"><p>Rule End Time:</p></label>
      </div>

      <div class="row collapse">
      <div class="small-4 columns">
        <input id="custom-rule-wizard-task1-endtime" type="text"/>
      </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$("#custom-rule-wizard-task1-starttime,#custom-rule-wizard-task1-endtime").timepicker({timeFormat: "hh:mm tt"});
</script>