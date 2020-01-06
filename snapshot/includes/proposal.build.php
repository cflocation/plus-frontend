<div class="resultswrapper rounded-corners">
	<div class="headers results" id="header-search-results">
		<span id="wrapper-result-icon" onclick="setPanel('panel1');">
			<i class="fa fa-minus-square fa-lg hander"></i>
			<i class="fa fa-plus-square fa-lg hander" style="display:none;"></i>			
		</span>&nbsp;Search Results: 
		<span style="padding-left:5px;" id="label-count"></span>
		
		<!-- div class="nowrapall row padder" style="float:right;" id="search-result-buttons">
			<ul id="sidebar-group-shows" class="button-group radius">
				<li><a id="sidebar-group-shows-1" href="javascript:datagridShows.groupByColumn('off');toggleOn('sidebar-group-shows',1);" class="button tiny" style="padding:8px;margin-bottom:0 !important;">Off</a></li>
				<li><a id="sidebar-group-shows-2" href="javascript:datagridShows.groupByColumn('startdate');toggleOn('sidebar-group-shows',2);" class="button tiny drkgrey" style="padding:8px;margin-bottom:0 !important;">Date</a></li>
				<li><a id="sidebar-group-shows-3" href="javascript:datagridShows.groupByColumn('starttime');toggleOn('sidebar-group-shows',3);" class="button tiny" style="padding:8px;margin-bottom:0 !important;">Time</a></li>
				<li><a id="sidebar-group-shows-4" href="javascript:datagridShows.groupByColumn('title');toggleOn('sidebar-group-shows',4);" class="button tiny" style="padding:8px;margin-bottom:0 !important;">Title</a></li>
				<li><a id="sidebar-group-shows-5" href="javascript:datagridShows.toggleGroupsExpandCollapse();" class="button tiny" style="padding:8px;margin-bottom: 0 !important;"><i class="fa fa-bars"></i></a></li>
			</ul>
		</div -->
		<div class="nowrapall" style="float:right;" id="search-results-buttons">
			Grouped: 
			<span id="grouping-btn" class="button-group radius">
				<input type="radio" id="grouping-off" 	name="showtype-mode-selector" value="Off" checked="checked" onclick="datagridSearchResults.groupByColumn('off')"><label for="grouping-off">Off</label>
				<input type="radio" id="grouping-date" 	name="showtype-mode-selector" value="date" onclick="datagridSearchResults.groupByColumn('startdate');"><label for="grouping-date">Date</label>
				<input type="radio" id="grouping-time" 	name="showtype-mode-selector" value="time" onclick="datagridSearchResults.groupByColumn('starttime');"><label for="grouping-time">Time</label>
				<input type="radio" id="grouping-title" name="showtype-mode-selector" value="title" onclick="datagridSearchResults.groupByColumn('title');"><label for="grouping-title">Title</label>
				<input type="radio" id="grouping-expand" name="showtype-mode-selector" value="Expand"><label for="grouping-expand"><i class="fa fa-compress" style="display: none;"> </i><i class="fa fa-expand"> </i></label>
			</span>
			<button class="btn-red"  id="clear-search-results-btn"><i class="fa fa-trash-o"></i></button>
		</div>
		
		
		<!-- div class="nowrapall" style="float:right;" id="search-results-buttons">
			<input type="checkbox" name="toggle-results" id="toggle-results" checked="checked" value="fixed" onclick="setToggleResult();" />
			<label class="btn-blue" id="toggle-results-label" for="toggle-results">Grouped</label>
		</div -->
	</div>
	<div id="panel1">
		<div class="gridwrapper">
			<div id="search-results" style="height:200px;"></div>
		</div>
	</div>
</div>

<br style="clear:all">

<div class="proposalwrapper rounded-corners">

	<div class="headers proposal" id="header-proposal">
		<span id="wrapper-proposal-icon" onclick="setPanel('panel2');">
			<i class="fa fa-minus-square fa-lg hander"></i>
			<i class="fa fa-plus-square fa-lg hander" style="display:none;"></i>
		</span>
		&nbsp;
		<span class="proposal-header-info">
			<button class="btn-red" onclick="clearProposal();">
				<i class="fa fa-times"></i>&nbsp;Clear
			</button>
		</span>
		<span class="label-proposal-name" id="label-proposal-name">
			No SnapShot File Loaded
		</span>			
		<div class="proposal-header-info nowrapall" style="float:right" id="proposal-buttons">
			<button class="btn-red"  id="delete-proposal-lines-btn" title="Delete Proposal Lines"><i class="fa fa-trash-o"></i></button>
		</div>
	</div>


	<div id="panel2">
		<div class="gridwrapper">
			<div id="proposal-build-grid" style="height:200px;"></div>
		</div>
	</div>

</div>



<br style="clear:all">
<br style="clear:all">