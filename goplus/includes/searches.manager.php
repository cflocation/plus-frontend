<div class="mpanel rounded-corners" style="float:left;">
	<div class="mheader">Checked Searches</div>
	<div class="mbody">
<!--
		<button onclick="dialogCopySearch();"><i class="icon-copy"></i> Copy</button>
		<button onclick="">Rename</button>
-->
		<button onclick="javascript:void(0);" id="share-searches-btn"><i class="fa fa-share-alt"></i> Share</button>
		<button class="btn-red" onclick="deleteSavedSearches();"><i class="fa fa-trash-o"></i> Delete</button>
	</div>
</div>	

<!--
<div class="mpanel rounded-corners" style="float:left;">
	<div class="mheader">Archived Titles</div>


	<div class="mbody" id="searches-manager-archived">
		<a class="btn-blue" href="javascript:void(0);" id="titles-byzone-btn">Titles by Zone</a><a class="btn-blue" href="javascript:void(0);" id="all-titles-btn">All Titles</a>
	</div>

</div>	
-->

<br style="clear:both;">
<br>

<div class="resultswrapper rounded-corners">
<div class="headers results" id="header-search-results">
	
	<span id="wrapper-result-icon" onclick="toggleResultsGrid();">
		<i class="fa fa-floppy-o fa-lg"></i>
	</span>&nbsp;&nbsp;Saved Searches
	
	<div style="float:right;padding-right:10px;" id="label-count"></div>
</div>

<div id="wrapper-search-results">
	<div class="gridwrapper">
			<div id="saved-searches-datagrid" style="height:200px;"></div>
	</div>
</div>

</div>