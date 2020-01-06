<div class="row padder">
    <div class="small-12">
      <div class="row collapse">
        <div class="small-3 columns">
          <label for="sidebar-update-scheduler-types" class="right inline">Type:</label>
        </div>
        <div class="row collapse">
        <div class="small-9 columns">
          <ul id="sidebar-update-scheduler-types" class="button-group radius">
            <li><a id="sidebar-update-scheduler-types-1" href="javascript:chooseUpdateScheduleType(1);" class="button tiny" style="padding:8px;">Weekly</a></li>
            <li><a id="sidebar-update-scheduler-types-2" href="javascript:chooseUpdateScheduleType(2);" class="button tiny" style="padding:8px;">Daily</a></li>
          </ul>
          <input type="hidden" id="sidebar-hidden-update-scheduler-types" value=""/>
        </div>
        </div>
      </div>
    </div>
  </div>



<div class="row padder">
  <div class="small-12">
    <div class="row collapse">
      <div class="small-3 columns">
        <label for="sidebar-update-scheduler-groups" class="right inline">Groups:</label>
      </div>

      <div class="row collapse">
      <div class="small-9 columns">
        <a href="#" id="sidebar-update-scheduler-groups" data-dropdown="sidebar-update-scheduler-groups-choice-list" class="tiny button radius">Select Group(s)</a><br/>
        <ul id="sidebar-update-scheduler-groups-choice-list" data-dropdown-content class="large f-dropdown" style="width:400px;">
          <li><input id="sidebar-update-scheduler-groups-selectall" type="checkbox" value="ALL"><label for="sidebar-update-scheduler-groups-selectall"><strong>Select All</strong></label></li>
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
        <label for="sidebar-update-scheduler-network" class="right inline">Networks:</label>
      </div>

      <div class="row collapse">
      <div class="small-9 columns">
        <a href="#" id="sidebar-update-scheduler-network" data-dropdown="sidebar-update-scheduler-networks-choice-list" class="tiny button radius">Select Network(s)</a><br/>
          <ul id="sidebar-update-scheduler-networks-choice-list" data-dropdown-content class="large f-dropdown" style="width:400px;">
          <li><input id="sidebar-update-scheduler-network-selectall" type="checkbox" value="" data-callsign="" checked="checked"><label for="sidebar-update-scheduler-network-selectall">Select All</label></li>
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
        <label for="sidebar-update-scheduler-week-days" class="right inline">Days:</label>
      </div>

      <div class="row collapse">
      <div class="small-9 columns">
          <a href="#" id="sidebar-update-scheduler-week-days" data-dropdown="sidebar-update-scheduler-weekday-choice-list" class="tiny button radius">Select Day(s)</a><br/>
          <ul id="sidebar-update-scheduler-weekday-choice-list" data-dropdown-content class="f-dropdown">
            <li><input id="sidebar-update-scheduler-week-days-1" type="checkbox" value="1"><label for="sidebar-update-scheduler-week-days-1">Monday</label></li>
            <li><input id="sidebar-update-scheduler-week-days-2" type="checkbox" value="2"><label for="sidebar-update-scheduler-week-days-2">Tuesday</label></li>
            <li><input id="sidebar-update-scheduler-week-days-3" type="checkbox" value="3"><label for="sidebar-update-scheduler-week-days-3">Wednesday</label></li>
            <li><input id="sidebar-update-scheduler-week-days-4" type="checkbox" value="4"><label for="sidebar-update-scheduler-week-days-4">Thursday</label></li>
            <li><input id="sidebar-update-scheduler-week-days-5" type="checkbox" value="5"><label for="sidebar-update-scheduler-week-days-5">Friday</label></li>
            <li><input id="sidebar-update-scheduler-week-days-6" type="checkbox" value="6"><label for="sidebar-update-scheduler-week-days-6">Saturday</label></li>
            <li><input id="sidebar-update-scheduler-week-days-7" type="checkbox" value="7"><label for="sidebar-update-scheduler-week-days-7">Sunday</label></li>
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
        <label for="sidebar-update-scheduler-time" class="right inline">Time:</label>
      </div>

      <div class="row collapse">
      <div class="small-9 columns">
        <input id="sidebar-update-scheduler-time" type="text"/>
      </div>
    </div>
    </div>
  </div>
</div>



<div class="row padder" id="sidebar-update-scheduler-numweeks">
  <div class="small-12">
    <div class="row collapse">
      <div class="small-3 columns">
        <label for="sidebar-update-scheduler-days" class="right inline">Weeks:</label>
      </div>

      <div class="row collapse">
      <div class="small-9 columns">
        <select id="sidebar-update-scheduler-days" placeholder="Number of days in break file">
            <?php foreach(range(1, 4) as $i){ ?>
              <option value="<?php print $i; ?>"><?php print $i; ?></option>
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
        <label for="sidebar-update-scheduler-weekstart" class="right inline">Week start:</label>
      </div>

      <div class="row collapse">
      <div class="small-9 columns">
        <select id="sidebar-update-scheduler-weekstart" placeholder="Number of days in break file">
            <?php foreach(range(1, 7) as $i){ ?>
              <option value="<?php print $i; ?>"><?php print date("l",strtotime("Sunday +$i days")); ?></option>
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
        <label for="sidebar-update-scheduler-emails" class="right inline">Emails:</label>
      </div>

      <div class="row collapse">
      <div class="small-9 columns">
        <textarea id="sidebar-update-scheduler-emails" placeholder="Email"></textarea>
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
         <button type="submit" class="button tiny radius"  id="sidebar-update-scheduler-submit" onclick="saveUpdateSchedule();"><i class="fa fa-plus-circle fa-lg"></i> Create Schedule</button>
         <a href="javascript:resetUpdateSchedulerForm();" class="button tiny alert radius"><i class="fa fa-refresh"></i> Reset</a>
      </div>
    </div>
    </div>
  </div>
</div>




