<div style="width: 789px;">

	<p style="width: 100%; line-height: 25px;">
		<input type="search" id="custompkg-search-text" class="rounded-corners input-half" style="width:210px;" onkeyup="searchCustompackage();" placeholder="Search Package">
	</p>

	<div id="datagrid-custom-packages"  style="width:775px; height: 395px; overflow: hidden;">
		<div class="loading-message">
			<i class="fa fa-spinner fa-spin fa-fw fa-3x"></i> 
			<br><br>
			Loading....
		</div>
	</div>
</div>
<script type="text/javascript">
	loadCustomPackageList();
</script>
