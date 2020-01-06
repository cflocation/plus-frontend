<div class="gridwrapper">
    <div id="datagrid-network-selector" style="max-height:400px;"></div>
</div>

<script type="text/javascript">
var datagridNetworkSelector = new DatagridNetworkSelector();
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
			//console.log(nets[0]);
			datagridNetworkSelector.populateDatagrid(nets);
			datagridNetworkSelector.renderGrid();
		});
});



</script>