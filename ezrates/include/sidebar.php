
<div id="sidebar-tab-1" style="display:none;">



<form data-abide id="form-new-ratecard" onsubmit="return false;">

  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="ratecard-market" class="right inline">Market:</label>
        </div>

        <div class="row collapse">
        <div class="small-7 columns">
            <select id="ratecard-market"></select>
        </div>
        <div class="small-2 columns">
            <a href="javascript:loadDialogWindow('zone-list','All Zones',400,500);" class="button postfix radius"><i class="fa fa-search fa-lg"></i></a>
        </div>
      </div>
      
      </div>
    </div>
  </div>

<div class="row padder">
  <div id="sidebar-tab-1-sub-2" style="display:none;">
    <div data-alert class="alert-box red_alert radius">
      <i class="fa fa-exclamation-triangle fa-lg"></i> Dayparts required.<br><br>Add dayparts by <a href="javascript:menuSelect('tab-5','menu-5');datagridDaypartSelected.renderGrid();">clicking here</a> or <a href="javascript:reloadMarketZones();">reload</a>
    </div>
  </div>
</div>



  <div id="sidebar-tab-1-sub" style="display:none;">



  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label class="right inline">Dayparts:</label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
           <a class="button tiny" href="javascript:menuSelect('tab-5','menu-5');datagridDaypartSelected.renderGrid();"><i class="fa fa-clock-o"></i> Open Daypart Manager</a>
        </div>
        </div>
      </div>
    </div>
  </div>




  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label class="right inline">Group:</label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
          <ul class="button-group">
            <li><a href="javascript:datagridRatecards.groupByColumn('name');" class="button" style="padding:8px;">Name</a></li>
            <li><a href="javascript:datagridRatecards.groupByColumn('zone');datagridRatecards.collapseAllGroups();" class="button" style="padding:8px;">Zone</a></li>
            <li><a href="javascript:datagridRatecards.groupByColumn('off');" class="button" style="padding:8px;">Off</a></li>
          </ul>
        </div>
        </div>
      </div>
    </div>
  </div>



  <div class="row padder" style="display: none;">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label class="right inline"> </label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
          <ul class="button-group">
            <li><a href="" class="button green" style="padding:8px;"><i class="fa fa-plus-circle"></i> Add New Group</a></li>
          </ul>
        </div>
        </div>
      </div>
    </div>
  </div>



  <div style="background-color: #C8FEC9!important;">
    



  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="" class="right inline"></label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
            <h4>Create new Ratecard</h4>
        </div>
        </div>
      </div>
    </div>
  </div>



  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="ratecard-zone" class="right inline">Zone:</label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
            <select required id="ratecard-zone"></select>
        </div>
        </div>
      </div>
    </div>
  </div>


  <!-- div class="row padder" id="new-group-input">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="ratecard-name" class="right inline">Name:</label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
            <input type="text"  placeholder="Group name" required id="ratecard-name"></input>
        </div>
        </div>
      </div>
    </div>
  </div -->
  
	<div class="row padder" style="display: none;" id="new-group-input">
		<div class="small-12">
			<div class="row">
				<div class="small-3 columns">
					<label for="ratecard-market" class="right inline">Name:</label>
				</div>
		
				<div class="row collapse">
					<div class="small-6 columns">
						<input type="text"  placeholder="Group name" required id="ratecard-name"></input>
					</div>
					<div class="small-3 columns">
						<a href="#" onclick="$('#existing-group-input,#new-group-input').toggle();" class="button postfix radius"><i class="fa fa-arrow-circle-left fa-lg"></i> Back</a>
					</div>
				</div>
			</div>
		</div>
	</div>  
	
	
	
	<div class="row padder" id="existing-group-input">
		<div class="small-12">
			<div class="row">
				<div class="small-3 columns">
					<label for="ratecard-market" class="right inline">Name:</label>
				</div>
				<div class="row collapse">
					<div class="small-6 columns">
						<select name="rc-name" id="rc-name"><option value="0">Group Name</option></select>
					</div>
					<div class="small-3 columns">
						<a href="#" onclick="$('#existing-group-input,#new-group-input').toggle();$('#rc-name').val(0);" class="button postfix radius">Add New</a>
					</div>
				</div>
			</div>
		</div>
	</div>  



  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="ratecard-start-date" class="right inline">Dates:</label>
        </div>

        <div class="row collapse">
        <div class="small-4 columns">
          <input required id="ratecard-start-date" type="text"/>
        </div>

        <div class="small-1 columns">
           <label for="ratecard-end-date" class="right inline"> to &nbsp;</label>
        </div>

        <div class="small-4 columns">
          <input required id="ratecard-end-date" type="text"/>
        </div>

        </div>
      </div>
    </div>
  </div>


  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="ratecard-special" class="right inline">Priority:</label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
            <select id="ratecard-special">
              <option value="0">NO</option>
              <option value="1">YES</option>
            </select>
        </div>
        </div>
      </div>
    </div>
  </div>




  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label class="right inline"></label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
         <button type="submit" class="button tiny"><i class="fa fa-plus-circle fa-lg"></i> Create Ratecard</button>
         <a href="#" class="button tiny alert"><i class="fa fa-refresh"></i> Reset</a>
        </div>
        </div>
      </div>
    </div>
  </div>



  </div>



</div>

</form>


</div>












<!-- pricing -->
<div id="sidebar-tab-2" style="display:none;">

<div id="sidebar-tab-2-controls">

  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="pricing-filter" class="right inline">Columns:</label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
            <ul  class="button-group" id="pricing-filter">
              <li><a id="button-toggle-dayparts" href="javascript:datagridPricing.toggleGrid('rate');" style="padding:8px;background-color:#0F8012" class="button">Daypart</a></li>
              <li><a id="button-toggle-fixed" href="javascript:datagridPricing.toggleGrid('fixed');" style="padding:8px;" class="button">Fixed</a></li>
              <li><a id="button-toggle-fixedpct" href="javascript:datagridPricing.toggleGrid('fixedpct');" style="padding:8px;" class="button">Percent</a></li>
            </ul>
        </div>
        </div>
      </div>
    </div>
  </div>



  <div class="row padder">
    <div id="sidebar-tab-2-sub-2" style="display:none;">
      <div data-alert class="alert-box red_alert radius">
        <i class="fa fa-exclamation-triangle fa-lg"></i> You should select one column</a>
      </div>
    </div>
  </div>



<span id="form-pricing-rate-wrapper">
  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="pricing-daypart" class="right inline">Daypart:</label>
        </div>

        <div class="row collapse">
        <div class="small-7 columns">
            <input required onkeypress="return isNumberKey(event)"  type="text"  placeholder="Daypart Rate" required id="pricing-daypart" style="background-color: #FBFFE3!important"></input>
        </div>
        <div class="small-2 columns">
             <button onclick="datagridPricing.setRate($('#pricing-daypart').val())" type="submit" class="button postfix radius">Apply</button>
        </div>
      </div>
      </div>
    </div>
  </div>
</span>



<span id="form-pricing-fixed-wrapper">
      <div class="row padder">
      <div class="small-12">
        <div class="row">
          <div class="small-3 columns">
            <label for="pricing-fixed" class="right inline">Fixed:</label>
          </div>

          <div class="row collapse">
          <div class="small-7 columns">
              <input required onkeypress="return isNumberKey(event)"  type="text"  placeholder="Fixed Rate" required id="pricing-fixed" style="background-color: #E9FFE6!important"></input>
          </div>
          <div class="small-2 columns">
               <button onclick="datagridPricing.setRateFixed($('#pricing-fixed').val(),'fixed')"  type="submit" class="button postfix radius">Apply</button>
          </div>
        </div>
        </div>
      </div>
    </div>
</span>




<span id="form-pricing-fixedpct-wrapper" style="display:none;">
    <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="pricing-fixedpct" class="right inline">Fixed/Pct:</label>
        </div>

        <div class="row collapse">
        <div class="small-7 columns">
            <input required onkeypress="return isNumberKey(event)"  type="text"  placeholder="Fixed Rate" required id="pricing-fixedpct" style="background-color: #F5F3F6!important"></input>
        </div>
        <div class="small-2 columns">
             <button onclick="datagridPricing.setRateFixed($('#pricing-fixedpct').val(),'pct')" type="submit" class="button postfix radius">Apply</button>
        </div>
      </div>
      </div>
    </div>
  </div>
</span>




  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label class="right inline"></label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
          <button id="button-save-ratecard-changes" onclick="saveRatecardChanges();" type="submit" class="button tiny green" style="padding:8px;"><i class="fa fa-floppy-o fa-lg"></i> Save Ratecard Changes</button>
        </div>
        </div>
      </div>
    </div>
  </div>



  <div class="row padder">
    <div id="sidebar-tab-2-percent-message" style="display:none;">
      <div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 10px; font-size:12px; line-height:18px;">
          <b>Note:</b> If you apply a percentage for fixed programming to the daypart rate, the percentage amount may change after refreshing the screen.  This is due to rounding to the nearest dollar since we don’t show dollars and cents in ShowSeeker. <br>(Example:  You apply a 30% “bump” to the daypart.  Your Percent columns may end up showing 25% to 40% after the system rounds the rate in the Fixed column.)
      </div>
    </div>
  </div>



</div>


<div class="row padder">
  <div id="sidebar-tab-2-error-published" style="display:none;">
    <div data-alert class="alert-box red_alert radius">
      <i class="fa fa-exclamation-triangle fa-lg"></i> You are viewing a published ratecard. If you would like to make changes please load your <a href="javascript:reloadRatecardByID();"><i class="fa fa-folder-open"></i> working ratecard</a> then publish it to the server.
    </div>
  </div>
</div>


<div class="row padder">
  <div id="sidebar-tab-3-error-save" style="display:none;">
    <div data-alert class="alert-box red_alert radius">
      <i class="fa fa-exclamation-triangle fa-lg"></i> Don't forget to save your changes.
    </div>
  </div>
</div>




</div>

<!-- end pricing -->








<!-- pricing broadcast-->
<div id="sidebar-tab-2-broadcast" style="display:none;">


  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="pricing-title-alt-selector-broadcast" class="right inline">Filter:</label>
        </div>

        <div class="row collapse">
        <div class="small-9 columns">
            <input type="text" placeholder="Search for Title" id="pricing-filter-broadcast"></input>
        </div>
      </div>
      </div>
    </div>
  </div>


<div id="sidebar-tab-2-broadcast-controls">

<span id="form-pricing-rate-wrapper">
  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="pricing-daypart-broadcast" class="right inline">Daypart:</label>
        </div>

        <div class="row collapse">
          <div class="small-1  columns">
            <span class="prefix">$</span>
          </div>
        <div class="small-8 columns">
            <input required onkeypress="return isNumberKey(event)"  type="text"  placeholder="Daypart Rate" required id="pricing-daypart-broadcast" style="background-color: #FBFFE3!important"></input>
        </div>
      </div>
      </div>
    </div>
  </div>
</span>







<span id="form-pricing-fixed-wrapper">
      <div class="row padder">
      <div class="small-12">
        <div class="row">
          <div class="small-3 columns">
            <label for="pricing-fixed-broadcast" class="right inline">Fixed:</label>
          </div>

          <div class="row collapse">
          <div class="small-1  columns">
            <span class="prefix">$</span>
          </div>
          <div class="small-8 columns">
              <input required onkeypress="return isNumberKey(event)"  type="text"  placeholder="Fixed Rate" required id="pricing-fixed-broadcast" style="background-color: #E9FFE6!important"></input>
          </div>
        </div>
        </div>
      </div>
    </div>
</span>



  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="ratecard-broadcast-start-time" class="right inline">Times:</label>
        </div>

        <div class="row collapse">
        <div class="small-4 columns">
          <input id="ratecard-broadcast-start-time" type="text"/>
        </div>

        <div class="small-1 columns">
           <label for="ratecard-broadcast-end-time" class="right inline"> to &nbsp;</label>
        </div>

        <div class="small-4 columns">
          <input id="ratecard-broadcast-end-time" type="text"/>
        </div>

        </div>
      </div>
    </div>
  </div>




  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="ratecard-broadcast-days" class="right inline">Days:</label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
            <select size="11" multiple="multiple" id="ratecard-broadcast-days" style="height:170px;">
              <option selected="selected" value="1,2,3,4,5,6,7">Monday-Sunday</option>
              <option value="1,7">Saturday-Sunday</option>
              <option value="2,3,4,5,6">Monday-Friday</option>
              <option value="2">Monday</option>
              <option value="3">Tuesday</option>
              <option value="4">Wednesday</option>
              <option value="5">Thursday</option>
              <option value="6">Friday</option>
              <option value="7">Saturday</option>
              <option value="1">Sunday</option>
            </select>
        </div>
      </div>
      </div>
    </div>
  </div>


<span id="form-pricing-rate-wrapper">
  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="pricing-title-selector-broadcast" class="right inline">Title:</label>
        </div>

        <div class="row collapse">
        <div class="small-9 columns">
            <select id="pricing-title-selector-broadcast"></select>
        </div>
      </div>
      </div>
    </div>
  </div>
</span>


<span id="form-pricing-rate-wrapper">
  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="pricing-title-alt-selector-broadcast" class="right inline">Alt&nbsp;Title:</label>
        </div>

        <div class="row collapse">
        <div class="small-9 columns">
            <input type="text" placeholder="Alternate Title" id="pricing-title-alt-selector-broadcast"></input>
        </div>
      </div>
      </div>
    </div>
  </div>
</span>






<!--
  <center>
    <button onclick="saveRatecardChanges();" type="submit" class="button tiny green"><i class="fa fa-floppy-o fa-lg"></i> Save Changes</button>
  </center>
-->



  <div class="row" id="sidebar-group-price-broadcast">
    <div class="small-12">
      <div class="row padder">
        <div class="small-3 columns">
          <label for="" class="right inline"></label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
          <button id="button-save-ratecard-changes" onclick="addBroadcastRate();$('#pricing-title-alt-selector-broadcast').val('');" type="submit" class="button tiny"><i class="fa fa-plus-circle fa-lg"></i> Create New Line</button>
          <button onclick="panelEditBroadcastLine(0);" type="submit" class="button tiny darkred"><i class="fa fa-pencil fa-lg"></i> Edit Line</button>
        </div>
        </div>
      </div>
    </div>

    <div class="small-12">
      <div class="row padder">
        <div class="small-3 columns">
          <label for="" class="right inline"></label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
          <button onclick="saveRatecardChanges();" type="submit" class="button tiny green"><i class="fa fa-floppy-o fa-lg"></i> Save Changes</button>
          <a href="javascript:resetSidebar();" class="button tiny alert"><i class="fa fa-refresh"></i> Reset</a>
        </div>
        </div>
      </div>
    </div>
  </div>







  <div class="row" id="sidebar-group-edit-broadcast" style="display:none;">
    <div class="small-12">
      <div class="row padder">
        <div class="small-3 columns">
          <label for="button-update-daypart-to-market" class="right inline"></label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
            <button onclick="editBroadcastLine();" class="button tiny center"><i class="fa fa-floppy-o fa-lg"></i> Update Line</button>
            <button onclick="panelEditBroadcastLine(1);resetSidebar();" class="button tiny darkred"><i class="fa fa-times-circle fa-lg"></i> Close</button>
        </div>
        </div>
      </div>
    </div>
  </div>

</div>


<div class="row padder">
  <div id="sidebar-tab-2-error-broadcast-published" style="display:none;">
    <div data-alert class="alert-box red_alert radius">
      <i class="fa fa-exclamation-triangle fa-lg"></i> You are viewing a published ratecard. If you would like to make changes please load your <a href="javascript:reloadRatecardByID();"><i class="fa fa-folder-open"></i> working ratecard</a> then publish it to the server.
    </div>
  </div>
</div>




</div>

<!-- end pricing broadcast -->
















<!-- sidebar hot -->

<div id="sidebar-tab-3" style="display:none;">



  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="hot-network-list" class="right inline">Network:</label>
        </div>

        <div class="row collapse">
        <div class="small-7 columns">
            <select id="hot-network-list"></select>
        </div>
        <div class="small-2 columns">
             <button onclick="setHotNetworkEvent();saveHotProgramming();"  type="submit" class="button postfix radius">Apply</button>
        </div>
      </div>
      </div>
    </div>
  </div>



  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="hot-daypart-rate" class="right inline">Daypart+:</label>
        </div>

        <div class="row collapse">
        <div class="small-9 columns">
            <input onkeypress="return isNumberKey(event)"  type="text"  placeholder="Daypart+ Rate" required id="hot-daypart-rate" style="background-color: #FBFFE3!important;"></input>
        </div>
      </div>
      </div>
    </div>
  </div>


  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="hot-premiere-rate" class="right inline">Premiere:</label>
        </div>

        <div class="row collapse">
        <div class="small-9 columns">
            <input onkeypress="return isNumberKey(event)"  type="text"  placeholder="Premiere Rate" required id="hot-premiere-rate" style="background-color: #E9FEE6!important"></input>
        </div>
      </div>
      </div>
    </div>
  </div>


  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="hot-finale-rate" class="right inline">Finale:</label>
        </div>

        <div class="row collapse">
        <div class="small-9 columns">
            <input onkeypress="return isNumberKey(event)"  type="text"  placeholder="Finale Rate" required id="hot-finale-rate" style="background-color: #F5F3F6!important"></input>
        </div>
      </div>
      </div>
    </div>
  </div>



  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="hot-new-rate" class="right inline">New:</label>
        </div>

        <div class="row collapse">
        <div class="small-9 columns">
            <input required onkeypress="return isNumberKey(event)"  type="text"  placeholder="New Rate" required id="hot-new-rate" style="background-color: #E2F0FE!important"></input>
        </div>
      </div>
      </div>
    </div>
  </div>




  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="hot-live-rate" class="right inline">Live:</label>
        </div>

        <div class="row collapse">
        <div class="small-9 columns">
            <input required onkeypress="return isNumberKey(event)"  type="text"  placeholder="Live Rate" required id="hot-live-rate" style="background-color: #FEE1D5!important"></input>
        </div>
      </div>
      </div>
    </div>
  </div>





  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="" class="right inline"></label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
          <button onclick="setHotRateEvent();saveHotProgramming();" class="button tiny"><i class="fa fa-usd"></i> Update Rates</button>
          <button onclick="hotProgrammingReset();" class="button tiny red"><i class="fa fa-refresh"></i> Reset</button>
        </div>
        </div>
      </div>
    </div>
  </div>




  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="" class="right inline"></label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
          <button onclick="saveHotProgramming();" class="button tiny green"><i class="fa fa-floppy-o fa-lg"></i> Save Changes</button>
        </div>
        </div>
      </div>
    </div>
  </div>



</div>


<!-- end sidebar hot -->

















<div id="sidebar-tab-5" style="display:none;">

<form data-abide id="form-new-ratecard2" onsubmit="return false;">
  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="markets-id" class="right inline">
              Market:
          </label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
            <select id="markets-id"></select>
        </div>
        </div>
      </div>
    </div>
  </div>


  <div id="sidebar-tab-5-sub" style="display:none;">


  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="ratecard-add-daypart" class="right inline">
            Dayparts:
          </label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
            <select id="ratecard-add-daypart"></select>
        </div>
        </div>
      </div>
    </div>
  </div>


  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="button-add-daypart-to-market" class="right inline"></label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
            <button id ="button-add-daypart-to-market" class="button tiny center" style="padding:8px;"><i class="fa fa-clock-o fa-lg"></i> Add Selected Daypart to Market</button>
        </div>
        </div>
      </div>
    </div>
  </div>


  <div id="sidebar-tab-5-new-datpart" style="background-color: #C8FEC9!important;">




  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="" class="right inline"></label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
            <h4 id="sidebar-tab-5-new-datpart-title">Create new Daypart</h4>
        </div>
        </div>
      </div>
    </div>
  </div>



  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="daypart-start-time" class="right inline">Times:</label>
        </div>

        <div class="row collapse">
        <div class="small-4 columns">
          <input id="daypart-start-time" type="text"/>
        </div>

        <div class="small-1 columns">
           <label for="daypart-end-time" class="right inline"> to &nbsp;</label>
        </div>

        <div class="small-4 columns">
          <input id="daypart-end-time" type="text"/>
        </div>

        </div>
      </div>
    </div>
  </div>


  <div class="row padder">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="daypart-days" class="right inline">Days:</label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
            <select size="11" multiple="multiple" id="daypart-days" style="height:170px;">
              <option selected="selected" value="1,2,3,4,5,6,7">Monday-Sunday</option>
              <option value="1,7">Saturday-Sunday</option>
              <option value="2,3,4,5,6">Monday-Friday</option>
              <option value="2">Monday</option>
              <option value="3">Tuesday</option>
              <option value="4">Wednesday</option>
              <option value="5">Thursday</option>
              <option value="6">Friday</option>
              <option value="7">Saturday</option>
              <option value="1">Sunday</option>
            </select>
        </div>
      </div>
      </div>
    </div>
  </div>



  <div class="row" id="sidebar-group-create-daypart-create">
    <div class="small-12 padder">
      <div class="row">
        <div class="small-3 columns">
          <label for="button-add-new-daypart-to-market" class="right inline"></label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
            <button id ="button-add-new-daypart-to-market" class="button tiny center"><i class="fa fa-plus fa-lg"></i> Create Daypart</button>
        </div>
        </div>
      </div>
    </div>
  </div>

</div>


<div>
  <br>
</div>


  <div class="row" id="sidebar-group-create-daypart">
    <div class="small-12 padder">
      <div class="row">
        <div class="small-3 columns">
          <label for="button-add-new-daypart-to-market" class="right inline"></label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
            <button onclick="checkApplicationStatus(1);" class="button green tiny center"><i class="fa fa-arrow-left fa-lg"></i> Back to Ratecards</button>
            <button onclick="panelEditDaypartLine(0);" type="submit" class="button tiny darkred"><i class="fa fa-pencil fa-lg"></i> Edit Line</button>
        </div>
        </div>
      </div>
    </div>
  </div>





  <div class="row" id="sidebar-group-edit-daypart" style="display:none;">
    <div class="small-12 padder">
      <div class="row">
        <div class="small-3 columns">
          <label for="button-update-daypart-to-market" class="right inline"></label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
            <button id ="button-update-daypart-to-market" class="button tiny center"><i class="fa fa-floppy-o fa-lg"></i> Update Daypart</button>
            <button onclick="panelEditDaypartLine(1)" class="button tiny darkred center"><i class="fa fa-times-circle fa-lg"></i> Close</button>
        </div>
        </div>
      </div>
    </div>
  </div>






  </div>
</form>
</div>






