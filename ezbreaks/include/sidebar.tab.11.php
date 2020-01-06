<div class="row padder">
<div class="small-12">
  <div class="row collapse">
    <div class="small-3 columns">
      <label for="sidebar-access-corporation" class="right inline">Company:</label>
    </div>
    <div class="row collapse">
    <div class="small-9 columns">
        <select id="sidebar-access-corporation" onchange="getPermissionGroups($(this).val());"></select>
    </div>
  </div>
  </div>
</div>
</div>


<div class="row padder">
<div class="small-12">
  <div class="row collapse">
    <div class="small-3 columns">
      <label for="sidebar-access-group" class="right inline">Group:</label>
    </div>
    <div class="row collapse">
    <div class="small-9 columns">
        <select id="sidebar-access-group" onchange="loadPermissionsByGroup($(this).val());"></select>
    </div>
  </div>
  </div>
</div>
</div>