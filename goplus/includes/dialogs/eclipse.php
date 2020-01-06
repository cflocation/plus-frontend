<BR>
Spot Length: 
<span id="bookend-mode">
	<input type="radio" id="defaut-15" name="bookend-mode-option" value="15"/><label for="defaut-15">:15</label>
	<input type="radio" id="defaut" name="bookend-mode-option" value="30" checked="checked"/><label for="defaut">:30</label>
	<input type="radio" id="defaut-60" name="bookend-mode-option" value="60"/><label for="defaut-60">:60</label>
	<input type="radio" id="bookend-15" name="bookend-mode-option" value="B30"/><label for="bookend-15">:15/:15</label>
</span>
<p>
	<BR>
	<center>
		<button id="eclipse-download-btn" class="btn-green" onclick="downloadEclipse(); $('#dialog-window').dialog('destroy');">Download File</button>
	</center>
</p>
<script>
	$('#bookend-mode').buttonset();
	$('#eclipse-download-btn').button();
</script>