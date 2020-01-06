<nav class="top-bar subnav" data-topbar>
  <ul class="title-area">
    <li class="name"><h1><span class="slick-group-toggle expanded" style="margin-left:0px; display: none;" id="collapse-global" onclick="toggleGlobalCollapse();" onmouseover="this.style.cursor='pointer';"></span> Ratecards for <span id="label-market-title"><b>No Market Loaded</b></span></h1></li>
  </ul>

  <section class="top-bar-section">
    <ul class="right" id="top-bar-ratecard-options" style="display:none;">
      <li id="menu-2"><a href="javascript:saveRatecardChanges('publish-group');"><img src="/images/tiny-s.png"> Publish</li>
    	<li><a href="javascript:copySelectedRatecards();"><i class="fa fa-files-o"></i> Copy</a></li>
    	<li><a href="javascript:editSelectedRatecards();"><i class="fa fa-pencil-square-o"></i> Edit Ratecards</a></li>
    	<li><a href="javascript:confirmRatecardDelete();" style="color:red"><i class="fa fa-trash-o"></i> Delete</a></li>
    </ul>
  </section>
</nav>


<div class="gridwrapper">
    <div id="datagrid-ratecards" style="height:200px;"></div>
</div>