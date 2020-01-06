<br>
<center>
	<div id="content">
		<h5>Loading... Please Wait<h5>
		<img src="/images/ajax.gif">
	</div>
</center>
<script type="text/javascript">
$(document).ready(function() {
    var mailid 	= datagridChanges.selectedRowIds()[0];
	$.ajax({
		url:'services/tracker.php'
		,type:'post'
		,data:'eventtype=getcontent&emailid='+mailid
		,dataType:'html'
		,success:function(data)
		{
			$('#content').html(data).css("text-align","justify");
		}
	});
});



</script>