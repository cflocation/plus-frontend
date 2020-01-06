  <div class="row padder">
    <div class="small-12">
      <div class="row collapse">
        <div class="small-3 columns">
          <label for="sidebar-custom-titles-network" class="right inline">Network:</label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
            <select id="sidebar-custom-titles-network" onchange="getSelectedNetworkInstances('custom-titles');"></select>
        </div>
      </div>
      </div>
    </div>
  </div>

<div class="row padder">
  <div class="small-12">
    <div class="row collapse">
      <div class="small-3 columns">
        <label for="sidebar-custom-titles-instances" class="right inline">Instance(s):</label>
      </div>
      <div class="row collapse">
      <div class="small-9 columns">
        <a href="#" id="sidebar-custom-titles-instances" data-dropdown="sidebar-custom-titles-instances-choice-list" class="tiny button radius">Select Instance(s)</a><br/>
        <ul id="sidebar-custom-titles-instances-choice-list" data-dropdown-content class="large f-dropdown"></ul>
      </div>
    </div>
    </div>
  </div>
</div>








  <div class="row padder">
    <div class="small-12">
      <div class="row collapse">
        <div class="small-3 columns">
          <label for="sidebar-custom-titles-start-date" class="right inline">Dates:</label>
        </div>

        <div class="row collapse">
        <div class="small-4 columns">
          <input id="sidebar-custom-titles-start-date" type="text"/>
        </div>

        <div class="small-1 columns">
          <span class="postfix prefix">to</span>
        </div>

        <div class="small-4 columns">
          <input id="sidebar-custom-titles-end-date" type="text"/>
        </div>

        </div>
      </div>
    </div>
  </div>










  <div class="row padder">
    <div class="small-12">
      <div class="row collapse">
        <div class="small-3 columns">
          <label for="sidebar-custom-titles-start-time" class="right inline">Time:</label>
        </div>

        <div class="row collapse">
        <div class="small-4 columns">
          <input id="sidebar-custom-titles-start-time" type="text"/>
        </div>

        <div class="small-1 columns">
          <span class="postfix prefix">to</span>
        </div>

        <div class="small-4 columns">
          <input id="sidebar-custom-titles-end-time" type="text"/>
        </div>

        </div>
      </div>
    </div>
  </div>











  <div class="row padder">
    <div class="small-12">
      <div class="row collapse">
        <div class="small-3 columns">
          <label class="right inline"></label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
          <a href="javascript:getNetworkShowSchedule();" class="button tiny green radius"><i class="fa fa-refresh"></i>  Get Schdule</a>         
        </div>
        </div>
      </div>
    </div>
  </div>


  <div id="sidebar-tab-4-sub" style="display:none;">
    <div class="row padder">
    <div class="small-12">
      <div class="row collapse">
        <div class="small-3 columns">
          <label class="right inline">Group:</label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
          <ul class="button-group radius">
            <li><a href="javascript:datagridCustomTitles.groupByColumn('off');" class="button" style="padding:8px;">Off</a></li>
            <li><a href="javascript:datagridCustomTitles.groupByColumn('date');" class="button" style="padding:8px;">Date</a></li>
            <li><a href="javascript:datagridCustomTitles.groupByColumn('grouptitle');" class="button" style="padding:8px;">Date & Time</a></li>
          </ul>
        </div>
        </div>
      </div>
    </div>
    </div>
  </div>



  <div id="sidebar-tab-4-sub-2" style="display:none;">
    <div class="row padder">
      <div class="small-12">
        <div class="row collapse">
          <div class="small-3 columns">
            <label class="right inline"></label>
          </div>
          <div class="row collapse">
            <div class="small-9 columns">
              <button type="submit" class="button tiny radius" onclick="javascript:addEditCustomTitle();"><i class="fa fa-plus-circle fa-lg"></i> Add/Edit Custom Title For Selected</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>



