<div id="titleUpdating" style="display:none;">Updating Please Wait</div>
<table cellspacing="10" border="0" id="saved-title-tbl">
	<tr>
		<td>
			<input class="rounded-corners forms clearable" placeholder="Enter a Filter term" name="searchinput" id="searchinput" type="text" style="width:300px; height: 18px !important;" maxlength="100">
		</td>
		<td>
			<button id="dialog-title-search-btn" class="btn-green" onclick="searchShowSeekerTitleEvent();"><i class="fa fa-search"></i> Search</button>
			<button id="dialog-title-save-btn-reminder" style="display:none;" class="btn-blue" onclick='dialogSaveSearch("reminder");'><i class="fa fa-heart"></i> Save as Reminder</button>
			<button class="btn-red" id="reset-titles-filter"><i class="fa fa-refresh"></i> Reset</button>
		</td>
	</tr>
	<tr>
		<td bgcolor="white"><div class="gridwrapper"><div id="titles-available" style="width:300px; height:450px;"></div></div></td>
		<td bgcolor="white"><div class="gridwrapper"><div id="titles-selected" style="width:300px; height:450px;"></div></div></td>
	</tr>
</table>