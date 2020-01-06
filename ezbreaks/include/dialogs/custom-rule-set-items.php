<br>
<div class="gridwrapper">
    <div id="custom-breakrule-wizard-breakitems-grid" style="height:400px;"></div>
</div>


<script type="text/javascript">
	datagridCustomrulewizard = new DatagridCustomrulewizard();

	$.getJSON("services/customrulewizard.php?eventtype=viewrulesetitems&rulesetid=<?php print $_GET['e']; ?>", function(data){
		datagridCustomrulewizard.populateDatagrid(data.data);
		datagridCustomrulewizard.refreshSorting();
	});
</script>