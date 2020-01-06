<center>
	<p>You are attempting to download a custom package where some or all networks are not available in the zone you have chosen. Either change your zone, or continue and download package with available networks.</p>
	<p>
		<button onclick="injectCustomPackage(<?php print intval($_GET['showid']); ?>);" class="btn-green"><i class="fa fa-check-circle"></i> Continue</button>
		<button onclick='$("#dialog-window").dialog("destroy");' class="btn-red"><i class="fa fa-times-circle"></i> Cancel</button>
	</p>
</center>
<script type="text/javascript">
	$("button").button();
</script>