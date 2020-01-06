<?php
session_start();
include_once('../../../config/database.php');

$userid = $_SESSION['userid'];
$corporationid = $_SESSION['corporationid'];
?>

<p style="margin-top:5px;" id="custom-rule-wizard-step6-title">What would you want to do?</p>
	<ol id="selectable-step6">
	<li class="ui-widget-content ui-selected" data-choice="manual">Create rule set manually</li>
	<li class="ui-widget-content" data-choice="template">
		Use a templete 
		<select disabled="disabled" name="customRuleWizardStep6Templateid" id="custom-rule-wizard-step6-templateid">
		</select>
	</li>	
	<li class="ui-widget-content" data-choice="previousrule">
		Use a previously applied rule set
		<select disabled="disabled" name="customRuleWizardStep6Prevruleid" id="custom-rule-wizard-step6-prevruleid">
		</select>
	</li>
</ol>
<script type="text/javascript">
$( "#selectable-step6" ).selectable();
$("#selectable-step6 li").click(function() 
{
	$(this).addClass("ui-selected").siblings().removeClass("ui-selected");
	if($('li.ui-selected','#selectable-step6').length > 0)
	{
		$('#custom-rule-wizard-step6-templateid,#custom-rule-wizard-step6-prevruleid').attr('disabled','disabled').css('opacity','0.45');		
		if($('li.ui-selected','#selectable-step6').data('choice') == 'template')
			$('#custom-rule-wizard-step6-templateid').removeAttr('disabled').css('opacity','1');
		if($('li.ui-selected','#selectable-step6').data('choice') == 'previousrule')
			$('#custom-rule-wizard-step6-prevruleid').removeAttr('disabled').css('opacity','1');
	}
});
</script>