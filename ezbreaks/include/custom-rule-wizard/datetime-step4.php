<?php
session_start();
include_once('../../../config/database.php');

$userid = $_SESSION['userid'];
$corporationid = $_SESSION['corporationid'];
?>
<p style="margin-top:5px;" id="custom-rule-wizard-task1-step3-title">Select the dates</p>
<div class="row padder">
  <div class="small-7">
    <div class="row collapse">
      <div class="small-3 columns">
        <label for="custom-rule-wizard-task1-startdate" class="right inline"><p>From Date:</p></label>
      </div>

      <div class="row collapse">
      <div class="small-4 columns">
        <input id="custom-rule-wizard-task1-startdate" type="text"/>
      </div>
      </div>
    </div>
  </div>
</div>
<div class="row padder">
  <div class="small-7">
    <div class="row collapse">
      <div class="small-3 columns">
        <label for="custom-rule-wizard-task1-enddate" class="right inline"><p>To Date:</p></label>
      </div>

      <div class="row collapse">
      <div class="small-4 columns">
        <input id="custom-rule-wizard-task1-enddate" type="text"/>
      </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">

//set the number of months
$("#custom-rule-wizard-task1-startdate,#custom-rule-wizard-task1-enddate").datepicker( {numberOfMonths: 1});

//make the calendar a broadcast one
$("#custom-rule-wizard-task1-startdate,#custom-rule-wizard-task1-enddate").datepicker("option", "firstDay", 1 );
$("#custom-rule-wizard-task1-startdate,#custom-rule-wizard-task1-enddate").datepicker( "option", "showTrailingWeek", true );
$("#custom-rule-wizard-task1-startdate,#custom-rule-wizard-task1-enddate").datepicker( "option", "showOtherMonths", true );
$("#custom-rule-wizard-task1-startdate,#custom-rule-wizard-task1-enddat").datepicker( "option", "selectOtherMonths", true );


</script>