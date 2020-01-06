<center>
	<br>
	<div class="row">
		<label class="label" for="customDemo1">Name:</label>
		<input id="paramsName" type="text" maxlength="40" class="ratingsTextInput rounded-corners">
	</div>
	<br><br>	
	<div class="row">
		<button id="saveRatingsParams" class="btn-green" onclick="saveRatigsParams();"><i class="fa fa-floppy-o"></i> Save Settings</button>
		<button id="cancelRatingsParams" class="btn-red" onclick="$('#dialog-window').dialog('destroy');"><i class="fa fa-times"></i> Cancel</button>
	</div>
	
	<br/>
	
	<div id="inProgress" style="display: none;" class="spinnerArea">
		<i class="fa fa-spinner fa-spin fa-fw fa-3x"></i>
	</div>
	<div id="saveRtgMsg" style="display: none; border: solid 1px #aaa; padding: 4px;">
		<i class="fa fa-exclamation-triangle"></i> The name should be smaller than 40 letters.
	</div>
	<div id="saveNoNameMsg" style="display: none; border: solid 1px #aaa; padding: 4px;">
		<i class="fa fa-exclamation-triangle"></i> Please type a Name before saving your settings.
	</div>	
</center>

<script>
	$('#saveRatingsParams,#cancelRatingsParams').button();
</script>