<div class="gridwrapper">
    <div id="datagrid-scheduler-network-choice" style="max-height:400px;"></div>
</div>

<script type="text/javascript">
var datagridSchedulerNetChoice = new DatagridSchedulerNetChoice();
$(document).ready(function() {
    

    var url = "services/groups.php?eventtype=groupnetlistforscheduler&id=5";
		$.getJSON(url, function(data) {
			var nets = [];
			$.each(data.data, function(i, value) {
	            
	            if(value.groupnets.length > 0) 
	            {
	            	$.each(value.groupnets, function(j, net) {
	            		nets.push(net);
			        });
			    }
		    });
			console.log(nets[0]);
			datagridSchedulerNetChoice.populateDatagrid(nets);
			datagridSchedulerNetChoice.renderGrid();
		});
});



</script>