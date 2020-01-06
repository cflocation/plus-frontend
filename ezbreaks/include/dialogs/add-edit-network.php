<div class="row padder">
  <div class="small-12">
    <div class="row collapse">
      <div class="small-3 columns">
        <label for="sidebar-item-timezone" class="right inline">Timezone:</label>
      </div>

      <div class="row collapse">
      <div class="small-9 columns">
        <select id="sidebar-item-timezone"></select>
      </div>
    </div>
    </div>
  </div>
</div>


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


<div class="row padder">
  <div class="small-12">
    <div class="row collapse">
      <div class="small-3 columns">
        <label for="" class="right inline">&nbsp;</label>
      </div>
      <div class="row collapse">
      <div class="small-9 columns">
        <input type="checkbox" id="chk-live-grouping" value='Y' style="height: 0.756rem ! important;"/>
        <label for="chk-live-grouping" class="right inline">Group Live Shows</label>
      </div>
      </div>
    </div>
  </div>
</div>



<span id="sidebar-network-add-message">
  <div class="row padder">
    <div class="small-12">
      <div data-alert class="alert-box red_alert radius">
        <i class="fa fa-exclamation-triangle fa-lg"></i> Check the mapping table before adding newer networks
      </div>
    </div>
  </div>
</span>



<span id="sidebar-network-add-buttons">
  <div class="row padder">
    <div class="small-12">
      <div class="row collapse">
        <div class="small-3 columns">
          <label for="" class="right inline"></label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
          <button onclick="addNetworktoGroup();" type="submit" class="button tiny radius green"><i class="fa fa-plus-circle fa-lg"></i> Add Network</button>
        </div>
        </div>
      </div>
    </div>
  </div>
</span>




<span id="sidebar-network-edit-button" style="display:none;">
  <div class="row padder">
    <div class="small-12">
      <div class="row collapse">
        <div class="small-3 columns">
          <label for="" class="right inline"></label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
          <button onclick="updateNetworkinGroup();" type="submit" class="button green tiny radius"><i class="fa fa-floppy-o fa-lg"></i> Save Changes</button>
        </div>
        </div>
      </div>
    </div>
  </div>
</span>


<script type="text/javascript">
  populateTimezoneList(timezones);
  populateNetworkList(networks);
  populateBreaks(breaks);
  setLiveGrouping('Yes');

  if('<?php print $e;?>' == 'edit'){
    var row = datagridNetworks.selectedRows();

    var tzid = row[0].timezoneid;
    $('#sidebar-item-timezone').val(tzid);

    var tmsid = row[0].tmsid;
    $('#sidebar-item-network').val(tmsid);

    var breakid = row[0].breakid;
    $('#sidebar-item-breaks').val(breakid); 

    var instance = row[0].instancecode;
    $('#sidebar-item-instance').val(instance); 

    var livegrouping = row[0].livegrouping;
    setLiveGrouping(livegrouping);
     

    var x = ['sidebar-item-timezone','sidebar-item-network'];
    editFormElements(x,1);

    $('#sidebar-network-edit-button').css('display', 'inline');
    $('#sidebar-network-add-buttons').css('display', 'none');

  }

</script>




