<br/>
<div class="row">
    <div class="small-12">
      <input type="text"  placeholder="Custom Show Title" id="custom-show-title-text"></input>
	</div>
</div>


<center>
	<button onclick="saveCustomTitle();" type="submit" class="button tiny green"> Save Custom Title </button>
</center>

<script type="text/javascript">
	var selectedRows = datagridCustomTitles.selectedRows();
	var suggestedCustomTitle = "";

	$.each(selectedRows, function( index, value ) {
  		if(value.customtitle != "NA" && suggestedCustomTitle == "")
  		{
  			suggestedCustomTitle = value.customtitle.split("||");
  			suggestedCustomTitle = suggestedCustomTitle[0].split("</b> :");
  			suggestedCustomTitle = suggestedCustomTitle[1];
  		}
	});

	if(suggestedCustomTitle == "")
		suggestedCustomTitle = selectedRows[0].title;

	$('#custom-show-title-text').val(suggestedCustomTitle);

</script>