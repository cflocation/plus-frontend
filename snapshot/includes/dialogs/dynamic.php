<?php
	include_once('../../config/mysqli.php');
	$sql = "SELECT * FROM dialogs WHERE name = '$evt'";
	$result = mysqli_query($con, $sql);
	$row = $result->fetch_assoc();
?>



<?php if($row['showalert'] == 1): ?>
	<div class="ui-widget">
		<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"> 
			<p> 
				<i class="fa fa-exclamation-triangle fa-2x" style="float: left; margin-right: .3em;"></i> 
				<strong>Alert:</strong> <?php print $row['message']; ?>
			</p>
		</div>

		<p></p>
		<center>
			<?php if($row['event'] != ""): ?><button onclick="<?php print $row['event']; ?>$('#dialog-window').dialog('destroy');" class="btn-green"><?php print $row['eventlabel']; ?></button><?php endif; ?>
			<?php if($row['showclose'] == 1): ?><button onclick="$('#dialog-window').dialog('destroy');" class="btn-red"><i class="fa fa-times-circle"></i> Close</button><?php endif; ?>
			<?php if($row['showajax'] == 1): ?><br><img src="i/ajax.gif"><?php endif; ?>
		</center>
		
	</div>
<?php endif; ?>





<?php if($row['showalert'] == 0): ?>
	<center>
		<h3><?php print $row['message']; ?></h3>
		<?php if($row['showajax'] == 1): ?><img src="i/ajax.gif"><br><?php endif; ?>
		<?php if($row['showclose'] == 1): ?><br><button onclick="$('#dialog-window').dialog('destroy');" class="btn-red"><i class="fa fa-times-circle"></i> Close</button><?php endif; ?>
	</center>
<?php endif; ?>



<script>
	$("button").button();
</script>