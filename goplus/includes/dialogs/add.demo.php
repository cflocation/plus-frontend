<center>
	<br>
	<div class="row">
		<label class="label" for="customDemo1">Name:</label>
		<input id="customDemo1" type="text" maxlength="40" class="ratingsTextInput rounded-corners">
	</div>
	<br /><br />	
	<div class="row">
		<button id="saveCustomDemoBtn" class="btn-green"><i class="fa fa-floppy-o"></i> Save Demo</button>
		<button id="cancelCustomeDemos" class="btn-red" onclick="$('#dialog-window').dialog('destroy');"><i class="fa fa-times"></i> Cancel</button>
	</div>
</center>
<script>$('#saveCustomDemoBtn,#cancelCustomeDemos').button();</script>