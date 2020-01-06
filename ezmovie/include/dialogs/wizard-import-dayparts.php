<form data-abide id="form-copy-dayparts-validator">
<br>

  <div class="row">
    <div class="large-12 columns">
      <label for="import-daypart-markets-to">Markets<br><br>
          <select required size="12" multiple="multiple" id="import-daypart-markets-to" style="height:190px;"></select>
      </label>
    </div>
  </div>

<br>
  <center>
      <button id ="" class="button tiny center">Select All</button>
      <button type="submit" class="button tiny green"><i class="fa fa-arrow-circle-down fa-lg"></i> Copy Dayparts</button>
  </center>
</form>


<script type="text/javascript">
  $(function() {
    getMarketsForCopy();
    $('#form-copy-dayparts-validator').foundation({bindings:'events'});
  });


  function getMarketsForCopy(){
    $.getJSON("services/markets.php?eventtype=list", function(data){
    var selectedid = $('#markets-id').val();
    $.each(data.data, function(i, value) {
        if(value.id != selectedid){
          $('#import-daypart-markets-to').append($("<option></option>").attr("value", value.id).text(value.name));
        }
      });
    });
  }


  $('#form-copy-dayparts-validator')
    .on('invalid', function () {
      //var invalid_fields = $(this).find('[data-invalid]');
      //console.log(invalid_fields);
    })
    .on('valid', function (){
      var marketto = $('#import-daypart-markets-to').val();
      var rows = datagridDaypartSelected.selectedRows();
      copyMarketDaypartsEvent(marketto,rows);
  });

</script>