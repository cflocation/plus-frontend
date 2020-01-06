<table cellspacing="10" border="0">
	<tr>
		<td valign="middle">
				<input placeholder="Enter keywords to search" class="rounded-corners forms" id="searchinputkeywords" type="text" style="width:220px;">
				<button class="btn-blue" onclick="keywordsAddWord();"><i class="fa fa-plus-circle"></i> Add</button>
				<button class="btn-green" onclick="keywordsAddWord();searchShowSeeker();"><i class="fa fa-search"></i> Search</button>
				<button class="btn-red" onclick="keywordsResetList();"><i class="fa fa-refresh"></i> Reset</button>
		</td>
	</tr>
	<tr>
		<td bgcolor="white">
			<div class="gridwrapper">
				<div id="keywords-entered" style="width:620px; height:450px"></div>
			</div>
		</td>
	</tr>

</table>