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
		<label class="label" for="zone-selector">Zone:</label>
		<select class="selectorw rounded-corners" id="zone-selector" onchange="zoneSelected()">
			<option value="0">Select a Zone</option>
		</select>
	</div>

	<div id="ratecard-block" style="display:none;">
		<div class="row">
			<label class="label" for="ratecard-selector" style="color:green;"><i class="fa fa-usd"></i> Ratecard:</label>
			<select class="selectorw rounded-corners" id="ratecard-selector"></select>
		</div>
	</div>

	<div class="row" id="sidebar-row-networks">
		<label class="label" for="sn-nets">Networks:</label>
		<span id="sb-nets" class="ui-buttonset">
		<input type="radio" id="btn-networks" onclick="dialogNetworkList()" name="btnNetworks" value="all"><label id="btn-networks-label" for="btn-networks">Select Networks</label><button id="ez-grids-mod" class="btn-green" onclick="openEZGrids();">Show Grid</button>
		</span>		
	</div>

	<div class="row" id="sidebar-row-calendar" style="display:none;">
		<label class="label" for="calendar-mode">Calendar:</label>
		<span id="calendar-mode">
			<input type="radio" id="broadcast" name="calendar-mode-selector" onchange="setCalendarType();toggleTotalsView($(this).prop('checked'),'bc')" checked="checked" value="broadcast" ><label for="broadcast">Broadcast</label><input type="radio" id="standard" name="calendar-mode-selector" onchange="setCalendarType();toggleTotalsView($(this).prop('checked'),'std')" value="standard" ><label for="standard">Standard</label>
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
			<input type="radio" id="btn-daysofweek" onclick="isresetting=false;dialogDayOfWeek();" name="btnDaysofweek" value="all" ><label id="btn-daysofweek-label" for="btn-daysofweek">Select Days</label><button id="btn-dayparts" onclick="dialogDayparts();">Dayparts</button>			
		</span>
	</div>

	<div class="row" id="sidebar-row-quarters">
		<label class="label" for="quater-params">Quarters:</label>
		<span id="quater-params">
			<button id="btn-quarters" onclick="dialogQuarters();">Quarters</button>
		</span>
	</div>



	<!-- start fixed panel -->
	<div id="fixed-panel">
		<div class="row" id="sidebar-row-ezsearch" style="background-color:#e6e5e5">
			<label class="label" for="search-mode">
				<i><b>E-z Search:</b></i>
			</label>
			<span id="search-mode">
				<input class="searchmode" type="radio" id="search-off" name="search-mode-option" value="off" checked="checked" style="display:none;" ><input class="searchmode" type="radio" id="search-title" name="search-mode-option" value="title" onclick="searchType='title';dialogTitle(1);$('#dialog-genre').dialog('close');" ><label for="search-title" id="title-srch-lbl">Title</label>
			</span>
		</div>

		<div class="row" id="sidebar-row-sports">
			<label class="label" for="sports-mode">Sports:</label>
			<span id="sports-mode">
				<input type="checkbox" name="sports-mode" id="sports-all" value="all"><label for="sports-all">Sports Only</label><input type="checkbox" name="sports-mode" id="sports-live" value="live"><label for="sports-live">Sports Live</label>
			</span>
		</div>

		<div class="row" id="sidebar-row-select">
			<label class="label" for="premiere-genre">Select:</label>
			<span id="premiere-genre" class="ui-buttonset">
				<input type="radio" id="btn-premiere" onclick="dialogPremiere();" name="btnPremiere" value="0"><label id="btn-premiere-label" for="btn-premiere">Prem/Fin</label><input type="radio" id="btn-genre" onclick="dialogGenre();$('#dialog-title').dialog('close');" name="btnGenre" value="0"><label id="btn-genre-label" for="btn-genre">Genres</label>
			</span>
		</div>

		<div class="row" id="sidebar-row-filter">
			<label class="label" for="showtype-mode1">Filter:</label>
			<span id="showtype-mode1">
				<input type="checkbox" id="showtype-movies" name="showtype-mode-selector" value="movies"><label for="showtype-movies">Movies</label><input type="checkbox" id="showtype-live" name="showtype-mode-selector" value="live"><label for="showtype-live">Live</label><input type="checkbox" id="showtype-new" name="showtype-mode-selector" value="new"><label for="showtype-new">New</label>
			</span>
		</div>



		<div class="row">
			<label class="label" for="reset-mode">Reset:</label>			
			<span id="reset-mode">			
				<button id="reset-all"     class="btn-red  sb-reset" onclick="reset();datagridSearchResults.emptyGrid();"><i class="fa fa-refresh"></i> All</button>
				<button id="reset-filters" class="btn-red2 sb-reset" onclick="resetfilters();datagridSearchResults.emptyGrid();"><i class="fa fa-refresh"></i> Filters</button>
			</span>
		</div>

		<div class="row">
			<center>
				<button class="btn-green" id="ShowSeeker" style="width:92%"><i class="fa fa-search"></i> Search SnapShot</button>
			</center>
		</div>
	</div>
	<!-- end fixed panel -->




</div><!-- end main wrapper -->


<!-- start info panel -->
<div id="info-panel" style="display:none;">
	<div id="info-panel-wrapper"></div>
</div><!-- end info panel -->