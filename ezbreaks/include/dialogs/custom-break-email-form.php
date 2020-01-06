<br>
<div class="row">
    <div class="small-12">
      <input type="text"  placeholder="Recepients" id="custom-break-email-recepients"/>
    </div>
</div>


<div class="row">
    <div class="small-12">
      <input type="text"  placeholder="Subject" id="custom-break-email-subject"/>
	</div>
</div>

<div class="row">
    <div class="small-12">
      <textarea placeholder="Content" id="custom-break-email-content" style="width: 648px; height: 200px;"></textarea>
	</div>
</div>


<center>
	<button onclick="sendEmail();" type="submit" class="button tiny green"> Send Email </button>
</center>

<script type="text/javascript">
	CKEDITOR.disableAutoInline = true;
	$( document ).ready( function() {
		CKEDITOR.config.contentsCss = 'http://ww3.showseeker.com/ezbreaks/js/ckeditor/contents.css';
		CKEDITOR.basePath = 'http://ww3.showseeker.com/ezbreaks/js/ckeditor/';
		CKEDITOR.replace( 'custom-break-email-content', {uiColor: '#174A74'	});
	});
</script>