<table cellspacing="10" border="0">
	<tr>
		<td>
				<input class="rounded-corners input-half" type="search" id="genre-filter" placeholder="Enter a genre to search" style="width:230px">
		</td>
		<td>
			<button class="btn-green" onclick="searchShowSeeker();"><i class="fa fa-search"></i> Search</button>
			<button class="btn-red" id="reset-genres-filter" ><i class="fa fa-refresh"></i> Reset</button>
		</td>
	</tr>

	<tr>
		<td bgcolor="white">
			<div class="gridwrapper">
				<div id="datagrid-genre" style="width:230px; height:370px"></div>
			</div>
		</td>
		<td bgcolor="white">
			<div class="gridwrapper">
				<div id="genre-selected" style="width:230px; height:370px"></div>
			</div>
		</td>
	</tr>

</table>