<br>
<div class="gridwrapper">
    <div id="datagrid-zone-list" style="height:400px;"></div>
</div>


<script src='js/DatagridZones.js'></script>

<script type="text/javascript">
	var datagridZones = new DatagridZones();

	$.getJSON("services/zones.php?eventtype=list", function(data){
		datagridZones.populateDatagrid(data.data);
		datagridZones.renderGrid();
	});
</script>

