<table cellspacing="10" border="0">
	<tr>
		<td>
				<input class="rounded-corners forms clearable" placeholder="Enter a Filter term" name="searchinput-actors" id="searchinput-actors" type="text" style="width:300px;">
		</td>
		<td>
			<button class="btn-green" onclick="searchShowSeekerActorEvent();"><i class="fa fa-search"></i> Search</button>
			<button class="btn-blue" onclick='dialogSaveSearch("reminder");'><i class="fa fa-heart"></i> Save</button>
			<button class="btn-red" onclick="datagridActorsSelected.empty();"><i class="fa fa-refresh"></i> Reset</button>
		</td>
	</tr>

	<tr>
		<td bgcolor="white"><div class="gridwrapper"><div id="actors-available" style="width:300px; height:450px"></div></div></td>
		<td bgcolor="white"><div class="gridwrapper"><div id="actors-selected" style="width:300px; height:450px"></div></div></td>
	</tr>

</table>