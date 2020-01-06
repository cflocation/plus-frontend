  <br>


<div class="row">
  <div class="small-6 columns"><input required onkeyup="searchTitles()"  type="text"  placeholder="Start typing show title" required id="hot-title"></input></div>
  <div class="small-6 columns"><select id="hot-network"></select></div>
</div>





<div class="row">
  <div class="small-6 columns"><div class="gridwrapper"><div id="datagrid-hot-titles" style="height:380px;"></div></div></div>
  <div class="small-6 columns"><div class="gridwrapper"><div id="datagrid-hot-titles-selected" style="height:380px;"></div></div></div>
</div>


 


<br>
<center>
  <br>
  <button onclick="addShow();" type="submit" class="button tiny green"><i class="fa fa-floppy-o fa-lg"></i> Add Shows to Hot Programming</button>
</center>


<script src='js/DatagridHotTitles.js'></script>
<script src='js/DatagridHotTitlesSelected.js'></script>
<script type="text/javascript">
  

  var datagridHotTitles = new DatagridHotTitles();
  var datagridHotTitlesSelected = new DatagridHotTitlesSelected();
  var ready = true;


  function searchTitles(){
    var title = $('#hot-title').val();
    
    //console.log(title);
    if(title.length > 2 && ready == true){
       doSearch(title);
    }else{
      datagridHotTitles.emptyGrid();
    }
  }


  $.getJSON("services/zones.php?eventtype=zonenetworks&zoneid="+zoneid, function(data){
    $('#hot-network')[0].options.length = 0;
    $('#hot-network').append($("<option></option>").attr("value", 0).text('All Networks'));
    $.each(data.data, function(i, value) {
        var x = value.callsign;
        $('#hot-network').append($("<option></option>").attr("value", value.id).text(x));
    })
  });




  function doSearch(title){
    ready = false;
    $.getJSON("services/titles.php?eventtype=title&str="+encodeURIComponent(title), function(data){
      ready = true;
      if(data == 0){
          datagridHotTitles.emptyGrid();
      }else{
          datagridHotTitles.populateDatagrid(data);
          datagridHotTitles.renderGrid();
      }
    });
  }


  function addShow(){
    var networkid = $('#hot-network').val();
    var network = $('#hot-network option:selected').text();
    var shows = datagridHotTitlesSelected.gridData();
    datagridHotProgramming.addShows(shows,networkid,network);
    closeAllDialogs()
    saveHotProgramming();
  }


  $("#datagrid-hot-titles-selected")
  .bind("dropstart", function (e, dd) {

  }).bind("drop", function (e, dd) {
    var shows = datagridHotTitles.selectedRows();
    datagridHotTitlesSelected.addRows(shows);
  });


</script>

