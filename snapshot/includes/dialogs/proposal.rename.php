<center>
	<p>Type in your new SnapShot name and click Save Changes.</p>
	<input class="rounded-corners forms" id="proposal-rename" type="text" style="width:300px;">
	<p><button onclick="renameCheckedProposalEvent()" class="btn-green"><i class="fa fa-floppy-o"></i> Save Changes</button> <button onclick='$("#dialog-window").dialog("destroy");' class="btn-red"><i class="fa fa-times-circle"></i> Cancel</button></p>
</center>


<script type="text/javascript">
	$("button").button();
</script>