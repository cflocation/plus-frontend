<br/>
<div class="row">
    <div class="small-12">
      <input type="text"  placeholder="Recepients" id="changes-email-forward-recepients"/>
    </div>
</div>


<div class="row">
    <div class="small-12">
      <input type="text"  placeholder="Subject" id="changes-email-forward-subject"/>
	</div>
</div>

<div class="row">
    <div class="small-12">
      <textarea placeholder="Content" id="changes-email-forward-content" style="width: 648px; height: 200px;"></textarea>
	</div>
</div>

<br/>
<center>
	<button onclick="sendChangesEmailReply();" type="submit" class="button tiny green"><i class="fa fa-mail"></i> Send Email </button>
</center>

<script type="text/javascript">
	CKEDITOR.disableAutoInline = true;
	$( document ).ready( function() {
		
		$.post("services/tracker.php",
		{
        	eventtype: "getemaildetails",
        	id:datagridChanges.selectedRowIds()[0]
    	},
        function(data)
    	{
    		$('#changes-email-forward-content').html("<br/><br/><hr/>"+data.body);
    		//$('#changes-email-forward-recepients').val(data.emailfrom);
    		$('#changes-email-forward-subject').val("FW: "+data.subject);

    		CKEDITOR.config.contentsCss = '/ezbreaks/js/ckeditor/contents.css';
			CKEDITOR.basePath = '/ezbreaks/js/ckeditor/';
			CKEDITOR.replace( 'changes-email-forward-content', {uiColor: '#174A74'	});
    	}, "json");






		
	});
</script>