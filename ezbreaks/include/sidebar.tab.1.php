  <form data-abide id="form-new-ratecard" onsubmit="return false;">

    <div class="row padder">
      <div class="small-12">
        <div class="row collapse">
          <div class="small-3 columns">
            <label for="sidebar-breaks-group" class="right inline">Networks:</label>
          </div>

          <div class="row collapse">
          <div class="small-9 columns">
              <select onchange="getNetworksForGroup($(this).val(),true)" id="sidebar-breaks-group"></select>
          </div>
        </div>
        </div>
      </div>
    </div>


  
  <div id="sidebar-tab-1-sub" style="display:none;">

  <div class="row padder">
    <div class="small-12">
      <div class="row collapse">
        <div class="small-3 columns">
          <label for="network-start-date" class="right inline">Dates:</label>
        </div>

        <div class="row collapse">
        <div class="small-4 columns">
          <input id="network-start-date" type="text"/>
        </div>


        <div class="small-1 columns">
          <span class="postfix prefix">to</span>
        </div>



        <div class="small-4 columns">
          <input id="network-end-date" type="text"/>
        </div>

        </div>
      </div>
    </div>
  </div>



    <div class="row padder">
    <div class="small-12">
      <div class="row collapse">
        <div class="small-3 columns">
          <label for="sidebar-item-group-networks" class="right inline">Group:</label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
          <ul id="sidebar-item-group-networks" class="button-group radius">
            <li><a id="sidebar-item-group-networks-1" href="javascript:datagridNetworks.groupByColumn('off');toggleOn('sidebar-item-group-networks',1);" class="button tiny" style="padding:8px;">Off</a></li>
            <li><a id="sidebar-item-group-networks-2" href="javascript:datagridNetworks.groupByColumn('tzname');toggleOn('sidebar-item-group-networks',2);" class="button tiny" style="padding:8px;">Timezone</a></li>
            <li><a id="sidebar-item-group-networks-3" href="javascript:datagridNetworks.groupByColumn('name');toggleOn('sidebar-item-group-networks',3);" class="button tiny" style="padding:8px;">Network</a></li>
            <li><a id="sidebar-item-group-networks-3" href="javascript:datagridNetworks.collapseAllGroups();" class="button tiny" style="padding:8px;"><i class="fa fa-bars"></i></a></li>


            

          </ul>
        </div>
        </div>
      </div>
    </div>
  </div>

<!--
  <div class="row padder">
    <div class="small-12">
      <div class="row collapse">
        <div class="small-3 columns">
          <label for="sidebar-item-timezone" class="right inline">Timezone:</label>
        </div>

        <div class="row collapse">
        <div class="small-9 columns">
          <select onchange="setupGroupbyTimezone()" id="sidebar-item-timezone"></select>
        </div>
      </div>
      </div>
    </div>
  </div>
-->




<!--
  <div class="row padder">
    <div class="small-12">
      <div class="row collapse">
        <div class="small-3 columns">
          <label for="sidebar-item-network" class="right inline">Network:</label>
        </div>

        <div class="row collapse">
        <div class="small-9 columns">
          <select id="sidebar-item-network"></select>
        </div>
      </div>
      </div>
    </div>
  </div>
-->

<!--
  <div class="row padder">
    <div class="small-12">
      <div class="row collapse">
        <div class="small-3 columns">
          <label for="sidebar-item-breaks" class="right inline">Breaks:</label>
        </div>

        <div class="row collapse">
        <div class="small-9 columns">
          <select id="sidebar-item-breaks"></select>
        </div>
      </div>
      </div>
    </div>
  </div>
-->

<!--
  <div class="row padder">
    <div class="small-12">
      <div class="row collapse">
        <div class="small-3 columns">
          <label for="sidebar-item-instance" class="right inline">Instance:</label>
        </div>

        <div class="row collapse">
        <div class="small-9 columns">
          <input id="sidebar-item-instance" type="text"/>
        </div>
      </div>
      </div>
    </div>
  </div>
-->



<!--
  <div class="row" id="sidebar-network-edit-button" style="display:none;">
    <div class="small-12">
      <div class="row padder">
        <div class="small-3 columns">
          <label for="" class="right inline"></label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
          <button onclick="updateNetworkinGroup();" type="submit" class="button green tiny radius"><i class="fa fa-floppy-o fa-lg"></i> Save Changes</button>
          <button onclick="resetEditNetworkBreak();" type="submit" class="button red tiny radius"><i class="fa fa-times-circle fa-lg"></i> Cancel</button>
        </div>
        </div>
      </div>
    </div>
  </div>
-->



<!--
<span id="sidebar-network-add-buttons">
  <div class="row padder">
    <div class="small-12">
      <div class="row collapse">
        <div class="small-3 columns">
          <label for="" class="right inline"></label>
        </div>
        <div class="row">
        <div class="small-9 columns">
          <button onclick="addNetworktoGroup();" type="submit" class="button tiny radius green"><i class="fa fa-plus-circle fa-lg"></i> Add Network</button>
        </div>
        </div>
      </div>
    </div>
  </div>
</span>
-->



    </div>
  </form>