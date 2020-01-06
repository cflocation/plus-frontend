<div class="resultswrapper rounded-corners">
	<div class="headers results" id="header-search-results">
		<span id="wrapper-result-icon" onclick="setPanel('panel1');">
			<i class="fa fa-minus-square fa-lg hander"  onClick="mixTrack('Proposal - Search - Expand/Collapse');"></i>
			<i class="fa fa-plus-square fa-lg hander" style="display:none;"></i>			
		</span>&nbsp;<span id="marathonsMode" style="display: none;">Marathons </span> Search Results: 
		<span style="padding-left:1px;" id="label-count"></span>
		
		<span class="nowrapall" style="" id="search-results-filtered">- Filtered ( <span id="filtered-count"></span> )</span>
		
		<!--- div class="nowrapall" style="float:right; display: none;" id="search-results-ratings"></div --->
		<div class="nowrapall" style="float:right;" id="searchResultsBar">
    		
			<!--- span id="surveyName" class="rtgSettings"></span> 
			<span id="rtgInfoPipe" class="rtgSettings" style="color:#FFC871;"></span> 
			<span id="rtgAreas" class="rtgSettings"></span> 
			<span> &nbsp; </span ---> 
			<button class="btn-lilac rtgSettings" disabled="true"  id="ezratings-search-results-btn" onclick="getProposalLineRatings();"  style="display: none;">
				<i class="fa fa-line-chart" id="ratingsIcon"></i><i class="fa fa-spinner fa-spin fa-fw" id="gettingRatingsFlag" style="display: none;"></i>
			</button>
			<input type="checkbox" name="toggle-results" id="toggle-results" checked="checked" value="fixed" onclick="setToggleResult();  mixTrack('Search - Grouping Button');" class="serchResults" />
			<label class="btn-blue serchResults" id="toggle-results-label" for="toggle-results">Grouped</label>
			<button class="btn-red-group serchResults"  id="clear-search-results-btn"><i class="fa fa-trash-o"></i></button>
		</div>
		
		<div class="nowrapall" style="float:right; height: 20px;" id="searchResultsBar">
    		<input type="search" style="width: 160px; line-height: 14px;" placeholder="Filter by Net, Title, Day" class="input-half rounded-corners" id="filterGridShows">
        </div>
	</div>
	<div id="panel1">
		<div class="gridwrapper">
			<div id="search-results" style="height:200px;"></div>
		</div>
	</div>
</div>

<!-- br style="clear:all" -->
<div style="height: 4px; width: 100%;"></div>

<div class="proposalwrapper rounded-corners">

	<div class="headers proposal" id="header-proposal">
		<span id="wrapper-proposal-icon" onclick="setPanel('panel2');">
			<i class="fa fa-minus-square fa-lg hander" onClick="mixTrack('Proposal - Expand/Collapse');"></i>
			<i class="fa fa-plus-square fa-lg hander" style="display:none;" ></i>
		</span>
		&nbsp;
		<span class="proposal-header-info">
			<button class="btn-red" onclick="clearProposal();  mixTrack('Proposal - Clear');">
				<i class="fa fa-times"></i>&nbsp;Clear
			</button>
		</span>
		<span class="label-proposal-name" id="label-proposal-name">
			No Proposal Loaded
		</span>			
		<div class="proposal-header-info nowrapall" style="position: absolute; right: 10px;" id="proposal-buttons"><button class="btn-blue" onclick="datagridProposal.expandCollapseAllGroups(); mixTrack('Proposal - Expand/Collapse');" title="Expand/Collapse Proposal Lines"><i class="fa fa-expand fa-lg" style="display:none;"></i><i class="fa fa-compress fa-lg"></i></button><button class="btn-blue"  id="customcolumnsbtn" title="Columns Settings" onclick="mixTrack('Proposal - Columns Settings');"><i class="fa fa-columns fa-lg" id="columnctrl"></i></button><button class="btn-blue" onclick="javascript:quickPDF()" title="Quick Print"><i class="fa fa-print fa-lg"></i></button><button id="flight-calendar-btn" class="btn-blue" onclick="dialogFlight(); mixTrack('Proposal - Flight Calendar'); " title="Flight Calendar"><i class="fa fa-calendar fa-lg"></i> <span id="calcount"></span></button><button class="btn-blue" onclick="dialogDuplicateLines(); mixTrack('Proposal - Duplicate Lines');" title="Duplicate Lines"><i class="fa fa-clone fa-lg"></i></button><button class="btn-blue" onclick="dialogEditLines(); mixTrack('Proposal - Sp/Rate');" title="Update Spots and Rate">Sp/Rate</button><button class="btn-blue" onclick="dialogSpotLength(); mixTrack('Proposal - Spot Length');" title="Spots Length"><i class="fa fa-hourglass-start fa-lg"></i> Sp Len</button><button class="btn-blue" id="edit-line-title-btn" onClick="mixTrack('Proposal - Titles');" title="Update selected line(s) Title">Titles</button><button class="btn-red-group"  id="delete-proposal-lines-btn" title="Delete Proposal Lines" onClick="mixTrack('Proposal - Delete Lines');"><i class="fa fa-trash-o fa-lg"></i></button>
		</div>
	</div>
	<div id="panel2">
		<div class="gridwrapper">
			<div id="proposal-build-grid" style="height:200px;"></div>
		</div>
	</div>

</div>

<!-- br style="clear:all" -->
<div style="height: 4px; width: 100%;" id="rtgs-usr-message"></div>

<div class="totalwrapper rounded-corners" id="totals-wrapper">
	<div class="headers total" id="header-total">
		<span id="wrapper-total-icon" onclick="setPanel('panel3');">
			<i class="fa fa-minus-square fa-lg hander" style="display:none;"></i>
			<i class="fa fa-plus-square fa-lg hander" onClick="mixTrack('Proposal - Totals - Expand/Collapse');"></i>
		</span>
		&nbsp;&nbsp;
		<span id="label-total-name">Totals Broadcast Calendar</span>
		<div style="float:right;padding-right: 20px;">
			<span class="btn-blue" id="label-bc-cal" onClick="mixTrack('Proposal - BC Button');">BC</span>
			<span class="btn-blue" id="label-sc-cal" onClick="mixTrack('Proposal - SC Button');" style="display: none">SC</span>
			<span class="rounded-corners" id="wrapper-discount-agency">
				<span id="agcy-lbl">Agency Commission</span>
				<input onclick="discountAgency(); mixTrack('Proposal - Agency Commission'); needSaving=true;" type="checkbox" id="discount-agency" name="discount-agency" value="1"/>
			</span>
			<span class="rounded-corners" id="wrapper-discount-package">
				<span id="pck-lbl">Package Discounts</span>
				<span id="discount-mode">
					<label for="discount-percent">%</label>
					<input type="radio" id="discount-percent" onClick="mixTrack('Proposal - % Discount');" name="discount-mode-selector" checked="checked" value="1"/>
					<label for="discount-amount">$</label>
					<input type="radio" id="discount-amount" onClick="mixTrack('Proposal - $ Discount');" name="discount-mode-selector" value="2"/>
				</span>
				<input class="rounded-corners" type="text" id="proposal-discount-package" onkeypress="return isNumberKey(event);" value="0"  style="width:50px;border:1px solid grey;"> 
				<button id="btn-discount-apply" class="btn-green" onclick="discountSet(); mixTrack('Proposal - Apply Discount'); needSaving=true;">
					Apply
				</button>
			</span>
			<span>&nbsp;</span>
			<span id="totals-spots"></span>
			<span id="totals-gross"></span>
			<span id="totals-package"></span>
			<span id="totals-agency"></span>
			<span id="totals-net"></span>
		</div>
	</div>

	<div id="panel3" style="display: none">
		<div class="gridwrapper" id="totals-wrapper">
			<div id="total-fixed-datagrid" style="height:150px;"></div>
		</div>
	</div>
</div>

<br style="clear:all">
<br style="clear:all">