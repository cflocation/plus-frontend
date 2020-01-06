  <br>


<div class="row">
  <div class="small-12 columns">
    <select id="showids"></select>
  </div>
</div>


<div class="row" id="btn-set-show-id" style="display:none;">
  <div class="small-12 columns">
    <center><button onclick="setShowID();" type="submit" class="button tiny green"><i class="fa fa-floppy-o fa-lg"></i> Set Show ID</button></center>
  </div>
</div>


<div class="row" id="message-no-title" style="display:none;">
  <div class="small-12 columns">
<center>
  <br>
  <b>No titles found?</b>
</center>
  </div>
</div>

<script type="text/javascript">

  var rows = datagridShowList.selectedRows();
  var row = rows[0];


  function getShowListFromID() {
      var title = row['title'];


      $.getJSON("services/shows.php?eventtype=listshowsfromid&title="+encodeURIComponent(title), function(data) {
        if(data == 0){
          $('#message-no-title').css('display', 'inline');
          return;
        }

        $.each(data, function(i, value) {
            var title = value.title + " (id: "+value.id+")";
            $('#showids').append($("<option></option>").attr("value", value.id).text(title));
        })
        $('#btn-set-show-id').css('display', 'inline');
      });
  }


  function setShowID(){
     var showcardid = $('#showids').val();
     var futonid = row['id'];


      $.post("services/shows.php", {
          eventtype: "setshowid",
          showcardid: showcardid,
          futonid: futonid
      }).done(function(data) {
          closeAllDialogs();
          datagridShowList.updateRow(showcardid);
      });
  }


  $(document).ready(function() {
    getShowListFromID();
  });



</script>

