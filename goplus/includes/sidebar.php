<div id="side-menu"><!-- start main wrapper -->

	<div id="market-selector-block" style="display:none;">
		<div class="row">	
			<label class="label" for="market-selector">Regions:</label>
			<select class="selectorw rounded-corners" id="market-selector" onchange="marketSelected($(this).val())"></select>
		</div>
	</div>
	
	<div class="row" style="display: none;">		
			<label class="label" for="dma-selector">DMAs:</label>
			<select class="selectorw rounded-corners" id="dma-selector"></select>
	</div>

	<div class="row">
		<label class="label" for="zone-selector"><a href="#" id="zoneSearh" style="color:#044398; display: none;"><i class="fa fa-search"></i></a> Zone:</label>
		<select class="selectorw rounded-corners" id="zone-selector">
			<option value="0">Select a Zone</option>
		</select>
	</div>

	<div id="ratecard-block" style="display:none;">
		<div class="row">
			<label class="label" for="ratecard-selector" style="color:green;"><i class="fa fa-usd"></i> Ratecard:</label>
			<select class="selectorw rounded-corners" id="ratecard-selector"></select>
		</div>
	</div>

	<div class="row" id="rotator-type" style="display: none;">
		<div class="row">		
			<label class="label" for="line-mode">Line Type:</label>
			<span id="line-mode">
				<label for="weekly">Week</label>
				<input type="radio" id="weekly" name="line-period-selector"  value="week" checked="checked" onclick="mixTrack('Sidebar - Rotator - Week');">
				
				<input type="radio" id="daily"  name="line-period-selector"  value="day" onclick="mixTrack('Sidebar - Rotator - Day');">
				<label for="daily">Day</label>
				
				<input type="radio" id="yes"    name="line-period-selector"  value="yes" onclick="mixTrack('Sidebar - Rotator - Line Order');">
				<label for="yes" id="lblyes">Line Order</label>

				<span> &nbsp; </span>
				<span id="lineTypeInfo"><i class="fa fa-question-circle fa-lg hander" style="color: black;"></i></span>
			</span>
		</div>
	</div>

	<div class="row" id="sidebar-row-networks">
		<label class="label" for="sn-nets">Networks:</label>
		<span id="sb-nets" class="ui-buttonset">
		<input type="radio" id="btn-networks" onclick="dialogNetworkList();" name="btnNetworks" value="all"><label id="btn-networks-label" for="btn-networks">Select Networks</label><button class="btn-green" id="gridsOpenBtn" onclick="openEZGrids();" >Show Grid</button>
		</span>		
	</div>

	<div class="row" id="sidebar-row-calendar">
		<label class="label" for="calendar-mode">Calendar:</label>
		<span id="calendar-mode">
			<input type="radio" id="broadcast" name="calendar-mode-selector" onchange="setCalendarType();toggleTotalsView($(this).prop('checked'),'bc')" checked="checked" value="broadcast"><label for="broadcast">Broadcast</label><input type="radio" id="standard" name="calendar-mode-selector" onchange="setCalendarType();toggleTotalsView($(this).prop('checked'),'std')" value="standard"  onclick="mixTrack('Sidebar - Calendar: - Standard');"><label for="standard">Standard</label>
		</span>
	</div>

	<div class="row" id="sidebar-row-dates">
		<label class="label" for="date-start">Date Range:</label>
		<input class="input-half rounded-corners cal" type="text" id="date-start"> to <input class="input-half rounded-corners cal" type="text" id="date-end">
	</div>

	<div class="row" id="sidebar-row-times">
		<label class="label" for="time-start">Time Range:</label>
		<input class="input-half rounded-corners" type="text" id="time-start"> to <input class="input-half rounded-corners" type="text" id="time-end">
	</div>

	<div class="row" id="sidebar-row-days">
		<label class="label" for="daypart-params">Days of Week:</label>
		<span id="daypart-params" class="ui-buttonset">
			<input type="radio" id="btn-daysofweek" onclick="isresetting=false;dialogDayOfWeek();  mixTrack('Sidebar - Select Days');" name="btnDaysofweek" value="all" ><label id="btn-daysofweek-label" for="btn-daysofweek">Select Days</label><button id="btn-dayparts" onclick="dialogDayparts(); mixTrack('Sidebar - Dayparts Button');">Dayparts</button>
		</span>
	</div>

	<!-- start fixed panel -->
	<div id="fixed-panel">
		<div class="row" id="sidebar-row-ezsearch" style="background-color:#e6e5e5">
			<label class="label" for="search-mode">
				<i><b>E-z Search:</b></i>
			</label>
			<span id="search-mode">
				<input class="searchmode" type="radio" id="search-off" name="search-mode-option" value="off" checked="checked" style="display:none;" ><input class="searchmode" type="radio" id="search-title" name="search-mode-option" value="title" onclick="searchType='title';dialogTitle(1);$('#dialog-genre').dialog('close'); mixTrack('Sidebar - Title Button');"><label for="search-title" id="title-srch-lbl">Title</label><input class="searchmode" type="radio" id="search-keyword" name="search-mode-option" value="keyword" onclick="searchType='keyword';dialogKeyword();$('#dialog-genre').dialog('close'); mixTrack('Sidebar - Keyword Button');"><label for="search-keyword" id="keyword-srch-lbl">Keyword</label><input class="searchmode" type="radio" id="search-actors" name="search-mode-option" value="actors" onclick="searchType='actor';dialogActor();$('#dialog-genre').dialog('close'); mixTrack('Sidebar - Actor Button');"><label for="search-actors">Actor</label>
			</span>
		</div>

		<div class="row" id="sidebar-row-sports">
			<label class="label" for="sports-mode">Sports:</label>
			<span id="sports-mode">
				<input type="checkbox" name="sports-mode" id="sports-all" value="all" onclick="mixTrack('Sidebar - Sports Only');"><label for="sports-all">Sports Only</label><input type="checkbox" name="sports-mode" id="sports-live" value="live" onclick="mixTrack('Sidebar - Sports Live');"><label for="sports-live">Sports Live</label>
			</span>
		</div>

		<div class="row" id="sidebar-row-select">
			<label class="label" for="premiere-genre">Select:</label>
			<span id="premiere-genre" class="ui-buttonset">
				<input type="radio" id="btn-premiere" onclick="dialogPremiere(); mixTrack('Sidebar - Prem/Fin');" name="btnPremiere" value="0"><label id="btn-premiere-label" for="btn-premiere">Prem/Fin</label><input type="radio" id="btn-genre" onclick="dialogGenre();$('#dialog-title').dialog('close'); mixTrack('Sidebar - Genres Button');" name="btnGenre" value="0"><label id="btn-genre-label" for="btn-genre">Genres</label>
			</span>
		</div>

		<div class="row" id="sidebar-row-filter">
			<label class="label" for="showtype-mode">Filter:</label>
			<span id="showtype-mode">
				<input type="checkbox" id="showtype-movies" name="showtype-mode-selector" value="movies" onclick="mixTrack('Sidebar - Movies');"><label for="showtype-movies">Movies</label><input type="checkbox" id="showtype-live" name="showtype-mode-selector" value="live" onclick="mixTrack('Sidebar - Live');"><label for="showtype-live">Live</label><input type="checkbox" id="showtype-new" name="showtype-mode-selector" value="new" onclick="mixTrack('Sidebar - New');"><label for="showtype-new">New</label>
			</span>
		</div>

		<div class="row" id="sidebar-row-more">
			<label class="label" for="more-filters">More:</label>
			<span  class="ui-buttonset" id="more-filters"><input type="checkbox" id="more-marathons" name="more-selector" value="marathons" onclick="checkMarathons();  mixTrack('Sidebar - Marathons Button');"><label for="more-marathons" id="more-marathons-label">Marathons</label><input type="checkbox" id="more-demographics" name="more-demographics" value="demos" onclick="mixTrack('Sidebar - Nets by Demo Button');"><label for="more-demographics" id="more-demographics-label">Nets by Demo</label></span>
		</div>

		<div class="row">
			<label class="label" for="btn-rotators">Rotators:</label>
			<span class="ui-buttonset" id="btn-rotators"><button onclick="swapSettingsPanel('rotator',false);toggleLineTypeSelector(0);editRotator=false;schedulerCountWeeksFromDates();resetmini();resetEditRotatorItems();$('#schedule-spots').val(1);menuSelect('proposal-build'); mixTrack('Sidebar - Add Rotators');" class="sb-rotators" id="btn-add-rotator"> Add</button><button onclick="swapSettingsPanel('avails',false); mixTrack('Sidebar - Avails');">Avails</button><button class="btn-lilac" id="ezRatingsBtn" onclick="loadEzRatings(); mixTrack('Sidebar - Ratings');" style="display: none;">Ratings</button>
			</span>
		</div>

		<div class="row">
			<center>
				<button id="reset-all" class="btn-red  sb-reset" onclick="reset();datagridSearchResults.emptyGrid(); mixTrack('Sidebar - Reset All');"><i class="fa fa-refresh"></i> Reset All</button>
				<button id="reset-filters" class="btn-red2 sb-reset" onclick="resetfilters();datagridSearchResults.emptyGrid();  mixTrack('Sidebar - Reset Filters');"><i class="fa fa-refresh"></i> Filters</button>
				<button  class="btn-blue sb-reset" onclick="dialogSaveSearch(); mixTrack('Sidebar - Save Button');"><i class="fa fa-heart"></i> Save</button>
			</center>
		</div>

		<div class="row">
			<center>
				<button class="btn-green" id="ShowSeeker" onclick="mixTrack('Sidebar - Search Button');" style="width:92%"><i class="fa fa-search"></i> Search ShowSeeker</button>
			</center>
		</div>
	</div><!-- end fixed panel -->




	<!-- start rotator panel -->
	<div id="rotator-panel" style="display:none;">
		
		<div class="row" id="sidebar-row-weeks">
			<label class="label" for="schedule-weeks">Weeks:</label>
			<input class="input-half rounded-corners" type="text" id="schedule-weeks" onkeypress="return isValidNumberOnKeyPress(event,this.value);" maxlength="2">
		</div>
	
		<div class="row" id="sidebar-row-spots">
			<label class="label" for="schedule-spots">Spots:</label>
			<input class="input-half rounded-corners" onkeypress="return isValidNumberOnKeyPress(event,this.value);"  type="text" id="schedule-spots" value='' maxlength="3">
			<button class="btn-blue" onclick="dialogLineByDay();" style="display: none;" id="spotByDayButton"><i class="fa fa-calendar"></i></button>
		</div>
		
		<div class="row" id="sidebar-row-rate">
			<label class="label" for="schedule-rate">Rate:</label>
			<input class="input-half rounded-corners" onkeypress="return isNumberKey(event, this.value);"  type="text" id="schedule-rate" value='' maxlength="9">
		</div>
	
		<span class = "header-rotator-create">
			<div class="row">
				<center>		
					<button id="reset-all" class="btn-red" onclick="reset(); mixTrack('Sidebar - Reset Rotator');"><i class="fa fa-refresh"></i> Reset</button>
					<button class="btn-green" onclick="proposalAddRotator();needSaving=true; mixTrack('Sidebar - Create Rotator');" id="create-rotator-btn"><i class="fa fa-plus-circle"></i> Create Rotator</button>
				</center>
			</div>

			<div class="row">
				<center>
					<button class="btn-blue" onclick="swapSettingsPanel('search',false);mixTrack('Sidebar - Back to ShowSeeker');resetEditRotatorItems_OLD();"><i class="fa fa-arrow-circle-left"></i> Back to ShowSeeker</button>		
				</center>
			</div>
		</span>

		<span class="header-rotator-edit" style="display:none;">
			<div class="row">
				<center>
					<button id="reset-all" class="btn-red" onclick="reset();"><i class="fa fa-refresh"></i> Reset</button>
					<button class="btn-green" onclick="datagridProposal.confirmEdit(); mixTrack('Sidebar - Update Rotator');"><i class="fa fa-floppy-o"></i> <span id="update-line-span">Update Rotator</span></button>
				</center>
			</div>
			<div class="row">
				<center>
					<button  onclick="returnFromEditMode();"> <i class="icon-circle-arrow-left"></i> Back to Add New Rotator</button>
				</center>
			</div>
			<div class="row">
				<center>
					<button class="btn-blue" onclick="swapSettingsPanel('search',false);resetEditRotatorItems_OLD();"><i class="fa fa-arrow-circle-left"></i> Back to ShowSeeker</button>
				</center>
			</div>
		</span>
	</div><!-- end rotator panel -->


	<!--   start avails panel -->
	<div id="avails-panel" style="display:none;">
	
		<div id="sidebar-avails-group"><!-- avails group -->
	
			<div class="row" style="background-color:#e6e5e5">
				<label class="label" for="avails-daypart-selector"><i><b>Avails Mode:</b></i></label>
				<span id="avails-daypart-selector">
					<input type="radio" onclick="dialogAvailsDayparts()" id="avails-daypart-dayparts" name="avails-dayparts-selector" value="dayparts" checked="checked"><label for="avails-daypart-dayparts">Dayparts</label><input type="radio" onclick="dialogAvailsDayparts60()" id="avails-daypart-60" name="avails-dayparts-selector" value="dayparts-60"><label for="avails-daypart-60">60 Min</label><input type="radio" onclick="dialogAvailsDayparts30()" id="avails-daypart-30" name="avails-dayparts-selector" value="dayparts-30"><label for="avails-daypart-30">30 Min</label>
				</span>
			</div>

			<div class="row">
				<label class="label" for="avails-mode">Quarters:</label>
				<button id="btn-quarters" onclick='dialogAvailsQuarters();'>Select Quarters</button>
			</div>
	
			<div class="row">
				<label class="label" for="avails-mode">Detect Titles:</label>
				<span id="avails-detect">
					<input type="radio" id="avails-detect-off" name="avails-detect-selector" value="off" ><label for="avails-detect-off">Off</label><input type="radio" id="avails-detect-on" name="avails-detect-selector" value="on" checked="checked"><label for="avails-detect-on">On</label>
				</span>
			</div>
	
			<div class="row">
				<center>	
					<button id="reset-all" class="btn-red" onclick="resetAvails();"><i class="fa fa-refresh"></i> Reset</button>
					<button class="btn-green" onclick="proposalAddAvail($('input:radio[name=avails-dayparts-selector]:checked').val());needSaving=true; mixTrack('Sidebar - Create Avails');"><i class="fa fa-plus-circle"></i> Create Avails</button>
				</center>
			</div>
	
		</div><!-- end avails group -->
		
		<div class="row">
			<center>
				<button class="btn-blue" onclick="swapSettingsPanel('search',false);resetmini();"><i class="fa fa-chevron-circle-left"></i> Back to ShowSeeker</button>	
			</center>
		</div>
	</div>
	<!-- end avails panel -->

</div><!-- end main wrapper -->


<!-- start info panel -->
<div id="info-panel" style="display:none;">
	<!--<div id="info-panel-wrapper"></div>-->

	<div id="showcard"></div>

</div><!-- end info panel -->






