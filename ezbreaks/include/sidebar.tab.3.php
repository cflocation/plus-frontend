<div class="row padder">
    <div class="small-12">
      <div class="row collapse">
        <div class="small-3 columns">
          <label for="sidebar-item-group-breaks" class="right inline">Group:</label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
          <ul id="sidebar-item-group-breaks" class="button-group radius">
            <li><a id="sidebar-item-group-breaks-1" href="javascript:datagridCustomBreaks.groupByColumn('off');toggleOn('sidebar-item-group-breaks',1);" class="button tiny" style="padding:8px;">Off</a></li>
            <li><a id="sidebar-item-group-breaks-2" href="javascript:datagridCustomBreaks.groupByColumn('breaklabel');toggleOn('sidebar-item-group-breaks',2);" class="button tiny" style="padding:8px;">Label</a></li>
            <li><a id="sidebar-item-group-breaks-3" href="javascript:datagridCustomBreaks.groupByColumn('callsign');toggleOn('sidebar-item-group-breaks',3);" class="button tiny" style="padding:8px;">Network</a></li>
            <li><a id="sidebar-item-group-breaks-4" href="javascript:datagridCustomBreaks.collapseAllGroups();" class="button tiny" style="padding:8px;"><i class="fa fa-bars"></i></a></li>
          </ul>
        </div>
        </div>
      </div>
    </div>
  </div>


<div class="row padder">
  <div class="small-12">
    <div class="row collapse">
      <div class="small-3 columns">
        <label for="sidebar-custom-breaks-label" class="right inline">Label:</label>
      </div>

      <div class="row collapse">
      <div class="small-9 columns">
        <input id="sidebar-custom-breaks-label" type="text"/>
      </div>
    </div>
    </div>
  </div>
</div>

<div class="row padder">
    <div class="small-12">
      <div class="row collapse">
        <div class="small-3 columns">
          <label for="sidebar-custom-breaks-network" class="right inline">Network:</label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
            <select id="sidebar-custom-breaks-network" onchange="getSelectedNetworkInstances('custom-breaks');"></select>
        </div>
      </div>
      </div>
    </div>
  </div>

  <div class="row padder">
  <div class="small-12">
    <div class="row collapse">
      <div class="small-3 columns">
        <label for="sidebar-custom-breaks-instances" class="right inline">Instance(s):</label>
      </div>
      <div class="row collapse">
      <div class="small-9 columns">
        <a href="#" id="sidebar-custom-breaks-instances" data-dropdown="sidebar-custom-breaks-instances-choice-list" class="tiny button radius">Select Instance(s)</a><br/>
        <ul id="sidebar-custom-breaks-instances-choice-list" data-dropdown-content class="large f-dropdown"></ul>
      </div>
    </div>
    </div>
  </div>
</div>



  <div class="row padder">
    <div class="small-12">
      <div class="row collapse">
        <div class="small-3 columns">
          <label for="sidebar-custom-breaks-start-time" class="right inline">Type:</label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
            <ul id="sidebar-item-group-break" class="button-group radius">
              <li><a id="sidebar-item-group-break-1" onclick="javascript:chooseCustomBreakType('Yes');toggleOn('sidebar-item-group-break',1);" href="#" style="padding:8px;" class="button">Single Break</a></li>
              <li><a id="sidebar-item-group-break-2" onclick="javascript:chooseCustomBreakType('No');toggleOn('sidebar-item-group-break',2);" href="#" style="padding:8px;" class="button">No Break</a></li>
              <li><a id="sidebar-item-group-break-3" onclick="javascript:chooseCustomBreakType('Template');toggleOn('sidebar-item-group-break',3);" href="#" style="padding:8px;" class="button">Template</a></li>
            </ul>
            <input type="hidden" id="sidebar-custom-breaks-break-hidden"/>
        </div>
      </div>
      </div>
    </div>
  </div>

  <div id="sidebar-tab-3-length" style="display:none;">
    <div class="row padder">
      <div class="small-12">
        <div class="row collapse">
          <div class="small-3 columns">
            <label for="sidebar-custom-breaks-length" class="right inline">Length:</label>
          </div>
          <div class="row collapse">
          <div class="small-9 columns">
              <input type="text"  placeholder="30" required id="sidebar-custom-breaks-length" value="30"></input>
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="sidebar-tab-3-template" style="display:none;">
    <div class="row padder">
      <div class="small-12">
        <div class="row collapse">
          <div class="small-3 columns">
            <label for="sidebar-custom-breaks-template" class="right inline">Template:</label>
          </div>
          <div class="row collapse">
          <div class="small-9 columns">
              <select id="sidebar-custom-breaks-template">
              </select>
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>



  <div class="row padder">
    <div class="small-12">
      <div class="row collapse">
        <div class="small-3 columns">
          <label for="sidebar-custom-breaks-start-date" class="right inline">Date:</label>
        </div>

        <div class="row collapse">
        <div class="small-4 columns">
          <input id="sidebar-custom-breaks-start-date" type="text"/>
        </div>

        <div class="small-1 columns">
          <span class="postfix prefix">to</span>
        </div>

        <div class="small-4 columns">
          <input id="sidebar-custom-breaks-end-date" type="text"/>
        </div>

        </div>
      </div>
    </div>
  </div>






  <div class="row padder">
    <div class="small-12">
      <div class="row collapse">
        <div class="small-3 columns">
          <label for="sidebar-custom-breaks-start-time" class="right inline">Time:</label>
        </div>

        <div class="row collapse">
        <div class="small-4 columns">
          <input id="sidebar-custom-breaks-start-time" type="text"/>
        </div>

        <div class="small-1 columns"  id="sidebar-custom-breaks-to-label">
          <span class="postfix prefix">to</span>
        </div>

        <div class="small-4 columns"  id="sidebar-custom-breaks-enddate-label">
          <input id="sidebar-custom-breaks-end-time" type="text"/>
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
         <button id="sidebar-custom-breaks-add-button" type="submit" class="button tiny radius" onclick="createCustomBreakRule();"><i class="fa fa-plus-circle fa-lg"></i> Create Custom Rule</button>
         <button id="sidebar-custom-breaks-edit-button" class="button tiny darkred radius" type="submit" onclick="panelEditCustomRule(0);"><i class="fa fa-pencil fa-lg"></i>Edit Rule</button>
         
         <button id="sidebar-custom-breaks-update-button" type="submit" class="button tiny radius" onclick="updateCustomBreakRule();" style="display:none;"><i class="fa fa-plus-circle fa-lg"></i> Update Custom Rule</button>
         <button id="sidebar-custom-breaks-close-button" class="button tiny darkred radius" type="submit" onclick="panelEditCustomRule(1);" style="display:none;"><i class="fa fa-pencil fa-lg"></i>Close</button>
          <input type="hidden" id="sidebar-custom-rule-edit-hidden-id" value=""/>
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
          <a  id="sidebar-custom-breaks-reset-button" href="javascript:resetCustomBreakRuleForm();" class="button tiny alert radius"><i class="fa fa-refresh"></i> Reset</a>
        </div>
        </div>
      </div>
    </div>
  </div>





