<div class="row padder">
  <div class="small-12">
    <div class="row collapse">
      <div class="small-3 columns">
        <label for="sidebar-scheduler-groups" class="right inline">Groups:</label>
      </div>

      <div class="row collapse">
      <div class="small-9 columns">
        <a href="#" id="sidebar-scheduler-groups" data-dropdown="sidebar-groups-choice-list" class="tiny button radius">Select Group(s)</a><br/>
        <ul id="sidebar-groups-choice-list" data-dropdown-content class="large f-dropdown" style="width:400px;">
          <li><input id="sidebar-scheduler-groups-selectall" type="checkbox" value="ALL"><label for="sidebar-scheduler-groups-selectall"><strong>Select All</strong></label></li>
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
        <label for="sidebar-scheduler-network" class="right inline">Networks:</label>
      </div>

      <div class="row collapse">
      <div class="small-9 columns">
        <a href="#" id="sidebar-scheduler-network" data-dropdown="sidebar-networks-choice-list" class="tiny button radius">Select Network(s)</a><br/>
          <ul id="sidebar-networks-choice-list" data-dropdown-content class="large f-dropdown" style="width:400px;">
          <li><input id="sidebar-scheduler-network-selectall" type="checkbox" value="" data-callsign="" checked="checked"><label for="sidebar-scheduler-network-selectall">Select All</label></li>
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
        <label for="sidebar-scheduler-week-days" class="right inline">Days:</label>
      </div>

      <div class="row collapse">
      <div class="small-9 columns">
          <a href="#" id="sidebar-scheduler-week-days" data-dropdown="sidebar-weekday-choice-list" class="tiny button radius">Select Day(s)</a><br/>
          <ul id="sidebar-weekday-choice-list" data-dropdown-content class="f-dropdown">
            <li><input id="sidebar-scheduler-week-days-1" type="checkbox" value="1"><label for="sidebar-scheduler-week-days-1">Monday</label></li>
            <li><input id="sidebar-scheduler-week-days-2" type="checkbox" value="2"><label for="sidebar-scheduler-week-days-2">Tuesday</label></li>
            <li><input id="sidebar-scheduler-week-days-3" type="checkbox" value="3"><label for="sidebar-scheduler-week-days-3">Wednesday</label></li>
            <li><input id="sidebar-scheduler-week-days-4" type="checkbox" value="4"><label for="sidebar-scheduler-week-days-4">Thursday</label></li>
            <li><input id="sidebar-scheduler-week-days-5" type="checkbox" value="5"><label for="sidebar-scheduler-week-days-5">Friday</label></li>
            <li><input id="sidebar-scheduler-week-days-6" type="checkbox" value="6"><label for="sidebar-scheduler-week-days-6">Saturday</label></li>
            <li><input id="sidebar-scheduler-week-days-7" type="checkbox" value="7"><label for="sidebar-scheduler-week-days-7">Sunday</label></li>
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
        <label for="sidebar-scheduler-time" class="right inline">Time:</label>
      </div>

      <div class="row collapse">
      <div class="small-9 columns">
        <input id="sidebar-scheduler-time" type="text"/>
      </div>
    </div>
    </div>
  </div>
</div>

<div class="row padder">
  <div class="small-12">
    <div class="row collapse">
      <div class="small-3 columns">
        <label for="sidebar-scheduler-days" class="right inline">Days:</label>
      </div>

      <div class="row collapse">
      <div class="small-9 columns">
        <select id="sidebar-scheduler-days" placeholder="Number of days in break file">
            <?php foreach(range(1, 42) as $i){ ?>
              <option value="<?php print $i; ?>"><?php print $i?> Day<?php print ($i==1)?'':'s'; ?></option>
            <?php } ?>
        </select>
      </div>
    </div>
    </div>
  </div>
</div>

<div class="row padder">
  <div class="small-12">
    <div class="row collapse">
      <div class="small-3 columns">
        <label for="sidebar-scheduler-label" class="right inline">Label:</label>
      </div>

      <div class="row collapse">
      <div class="small-9 columns">
        <input id="sidebar-scheduler-label" placeholder="Label (optional)" type="text"/>
      </div>
    </div>
    </div>
  </div>
</div>

<div class="row padder">
  <div class="small-12">
    <div class="row collapse">
      <div class="small-3 columns">
        <label for="sidebar-scheduler-emails" class="right inline">Emails:</label>
      </div>

      <div class="row collapse">
      <div class="small-9 columns">
        <textarea id="sidebar-scheduler-emails" placeholder="Email"></textarea>
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
        <input type="hidden" id="sidebar-hidden-scheduleid" value=""/>
         <button type="submit" class="button tiny radius"  id="sidebar-scheduler-submit" onclick="saveSchedule();"><i class="fa fa-plus-circle fa-lg"></i> Create Schedule</button>
         <a href="javascript:resetSchedulerForm();" class="button tiny alert radius"><i class="fa fa-refresh"></i> Reset</a>
      </div>
    </div>
    </div>
  </div>
</div>




