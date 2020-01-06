<div id="ezrating-container"  style="height: 98%;">

	<div id="tab-ezrating" style="height: 76%">
		<ul>
			<li id="ezrating-tab-survey"><a href="#ezrating-1">Survey</a></li>
			<li id="ezrating-tab-demo"><a href="#ezrating-2">Demo</a></li>
			<li id="ezrating-tab-fav" class="ratingsFav"><a href="#ezrating-4">Saved</a></li>
		</ul>
		
		<div id="ezrating-1" style="width: 490px;">
			<?php include_once('ratings/survey.html') ?>
		</div>
		<div id="ezrating-2">
			<?php include_once('ratings/demo.html') ?>
		</div>
		<div id="ezrating-4">
			<?php include_once('ratings/saved.settings.html') ?>
		</div>
	</div>
	
	<br>

	<div id="ratings-summary" style="height: 22%; border: 1px solid #dddddd;">
		<div class="headers-small download">
			Summary
		</div>
		<table class="summarySelection" width="100%" cellspacing="0" cellspacing="0">
			<tr>
				<td width="65%" height="85px">
					<table width="100%" cellpadding="0" cellpadding="0" style="font-size:12px;">
						<tr>
							<td valign="middle" height="20" width="18%" style="padding-left: 4px;">Market:</td>
							<td valign="middle" id="rtgSummaryMkt"></td>
						</tr>
						<tr  id="row-survey" class="activeTab">
							<td valign="middle" height="20" width="18%" style="padding-left: 4px;">Survey:</td>
							<td valign="middle" id="rtgSummarySurvey"></td>
						</tr>
						<tr id="row-demos">
							<td valign="middle" height="20" width="18%" style="padding-left: 4px;">Demos:</td>
							<td valign="middle" id="rtgSummaryDemo" valign="bottom"></td>
						</tr>
					</table>
				</td>
				<td valign="bottom">
					<div align="right" style="padding-right: 5px; padding-bottom: 5px;">
						<button id="summaryBack" class="btn-dark-grey" style="display:none">Back</button>
						<button id="submitRatings" class="btn-green"  style="display:none"disabled="true">Apply</button>
						<button id="saveRatingsParams" class="btn-blue" style="display:none">Save</button>
						<button id="summaryNext" class="btn-green">Next</button>
					</div>
				</td>
			</tr>
		</table>
	</div>
</div>