<div class="ui-widget" id="saveerror">	<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"> 		<p>			<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 			<strong>Alert:</strong> Unsaved Proposal.		</p>	</div></div><div id="div-center">	<center>		<p>			<input name="proposal-save-name-input" id="proposal-save-name-input" type="text" style="width:300px;text-align:center;">		</p>	</center></div><center>	<button id="proposal-save-name" onclick="proposalCreateNewEvent('Create');" class="btn-green">Save Proposal</button>	&nbsp;&nbsp;&nbsp;	<button onclick="saveProposalDestroy();" class="btn-red" id="discard-save-btn">Discard</button></center><script>	$('#proposal-save-name,#discard-save-btn').button();			$("#dialog-window").dialog({	   closeOnEscape: false,	   open: function(event, ui) { $(".ui-dialog-titlebar-close", ui.dialog | ui).hide(); }	});	</script>