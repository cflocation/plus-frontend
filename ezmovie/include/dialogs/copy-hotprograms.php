<form data-abide id="form-copy-hot" onsubmit="return false;">

<div style="padding:5px;">



 <div class="row">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="ratecard-zone-hot-copy" class="right inline">Zones:</label>
        </div>
        <div class="small-9 columns">
          <select required size="11" multiple="multiple" style="height:170px;" id="ratecard-zone-hot-copy"></select>
        </div>
      </div>
    </div>
 </div>





 <div class="row">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="" class="right inline"></label>
        </div>
        <div class="small-9 columns">
            <button onclick="copyEvent();closeAllDialogs();" type="submit" class="button tiny green"><i class="fa fa-files-o"></i> Copy Ratecards</button>
            <button onclick="closeAllDialogs();" class="button tiny darkred center"><i class="fa fa-times-circle fa-lg"></i> Close</button>
        </div>
      </div>
    </div>
 </div>




</div>

</form>

<script type="text/javascript">
	$(function() {

	  $("#ratecard-zone > option").each(function() {
      	if(this.value != zoneid){
	    	 $('#ratecard-zone-hot-copy').append($("<option></option>").attr("value", this.value).text(this.text));
      }});

	  $('#ratecard-zone-hot-copy').val('');



  });


  function copyEvent() {

    if(copyHotProgrammingType == 1){
        var programs = datagridHotProgramming.getData();
    }else{
        var programs = datagridHotProgramming.selectedRows();
    }

    var destinationids = $('#ratecard-zone-hot-copy').val();

    $.post("services/ratecards.php", {
        eventtype: "copyhotprograms",
        programs: programs,
        destinationids: destinationids,
    }).done(function(data) {
    	loadDialogWindow('event-finished', 'Programs Copied', 380, 150);
    	
    });
  }



</script>