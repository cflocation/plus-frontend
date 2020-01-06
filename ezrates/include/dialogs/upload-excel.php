  <br>
  <center>
    <form id="form-upload-excel" enctype="multipart/form-data">
      <span style="border:1px solid #efefef;padding:10px;"><input style="width:320px;" type="file" name="file" id="file"></span>
      <button style="display:none;" id="btn-upload-excel" type="submit" class="button tiny green"><i class="fa fa-chevron-circle-up fa-lg"></i> Upload File</button>
      <br>
      <span id="output_process"></span>

    </form>
  </center>

  <script type="text/javascript">

    $("#file").change(function() {
      var ext = $('#file').val().split('.').pop().toLowerCase();
      if($.inArray(ext, ['xls']) == -1) {
        $("#btn-upload-excel").css('display', 'none');
        $("#output_process").html("<div data-alert class='alert-box red_alert radius'><i class='fa fa-exclamation-triangle fa-lg'></i>Invalid, Please select a valid file type.</div>");
      }else{
        $("#btn-upload-excel").css('display', 'inline');
        $("#output_process").html("");
      }
    });

  

    $('#form-upload-excel').submit(function() {
      var form = new FormData($(this)[0]);


      $.ajax({
          url: 'services/uploadfile.php',
          type: 'POST',
          async: false,
          cache: false,
          contentType: false,
          processData: false,
          data: form,
          beforeSend: function () {
              $("#output_process").html("Uploading, please wait....");
          },
          success: function () { 
              $("#output_process").html("Upload success.");
          },
          complete: function (x) {
              var filename = x.responseText;
              parseFile(filename);
              $("#output_process").html("upload complete.");
          },
          error: function () {
              //location.reload();
          }
      }).done(function(x) {
          //alert('Event created successfully..');
      }).fail(function() {
          alert("fail!");
      });

      return false;      
    });


    function parseFile(f){
      $.getJSON("services/upload.php?file="+f+'&cardid='+ratecardid, function(data){
        //datagridPricing = new DatagridPricing();
        closeAllDialogs();
        loadRatecardByID(ratecardid);
       });
    }

  </script>