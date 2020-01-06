<div style="padding:5px;">

 <div class="row">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="ratecard-zone-copy" class="right inline">Zone:</label>
        </div>
        <div class="small-9 columns">
          <select size="11" multiple="multiple" style="height:170px;" id="ratecard-zone-copy"></select>
        </div>
      </div>
    </div>
 </div>



<div class="row">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="ratecard-name-copy" class="right inline">Group:</label>
        </div>
        <div class="small-9 columns">
          <input type="text" id="ratecard-name-copy" placeholder="Group Name">
        </div>
      </div>
    </div>
 </div>



 <div class="row">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="ratecard-special-copy" class="right inline">Priority:</label>
        </div>
        <div class="small-9 columns">
       		<select id="ratecard-special-copy">
              <option value="0">NO</option>
              <option value="1">YES</option>
            </select>
        </div>
      </div>
    </div>
 </div>



 <div class="row">
    <div class="small-12">
      <div class="row">
        <div class="small-3 columns">
          <label for="ratecard-start-date" class="right inline">Dates:</label>
        </div>
        <div class="small-4 columns">
			<input required id="ratecard-start-date-copy" type="text"/>
        </div>
        <div class="small-1 columns">
           <label for="ratecard-end-date" class="right inline"> to &nbsp;</label>
        </div>
        <div class="small-4 columns">
          <input required id="ratecard-end-date-copy" type="text"/>
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
            <button onclick="copyRatecardsEvent();" type="submit" class="button tiny green"><i class="fa fa-files-o"></i> Copy Ratecards</button>
            <button onclick="closeAllDialogs();" class="button tiny darkred center"><i class="fa fa-times-circle fa-lg"></i> Close</button>
        </div>
      </div>
    </div>
 </div>




</div>



<script type="text/javascript">
	$(function() {
		var row = datagridRatecards.selectedRows();
    var copystartdate = Date.parse(row[0].startdate + " 00:00:00").toString("MM/dd/yy");
    var copyenddate = Date.parse(row[0].enddate + " 00:00:00").toString("MM/dd/yy");


	  $("#ratecard-zone > option").each(function() {
      if(this.value != ""){
	     $('#ratecard-zone-copy').append($("<option></option>").attr("value", this.value).text(this.text));
      }
		});

	  $('#ratecard-zone-copy').val(row[0].zoneid);
		$('#ratecard-name-copy').val(row[0].name + ' - copy');

	  if(row[0].broadcast == 1){
	   $("#ratecard-zone-copy").prop('disabled', true);
	  }



    //Build the calendars
    $("#ratecard-start-date-copy,#ratecard-end-date-copy").datepicker(
      {
        numberOfMonths: 1,
        dateFormat: "mm/dd/y"
      }
    );

		$("#ratecard-start-date-copy,#ratecard-end-date-copy").datepicker("option", "firstDay", 1 );
		$("#ratecard-start-date-copy,#ratecard-end-date-copy").datepicker( "option", "showTrailingWeek", false );
		$("#ratecard-start-date-copy,#ratecard-end-date-copy").datepicker( "option", "showOtherMonths", true );
		$("#ratecard-start-date-copy,#ratecard-end-date-copy").datepicker( "option", "selectOtherMonths", true );
		$("#ratecard-start-date-copy").datepicker("setDate",copystartdate);
    $("#ratecard-end-date-copy").datepicker("setDate",copyenddate);
  });


  function copyRatecardsEvent() {
    var row = datagridRatecards.selectedRows();
    var sourceid = row[0].id;
    var destinationids = $('#ratecard-zone-copy').val();

    var startdate = $('#ratecard-start-date-copy').val();
    var enddate = $('#ratecard-end-date-copy').val();
    var special = $('#ratecard-special-copy').val();
    var name = $('#ratecard-name-copy').val();

    startdate = Date.parse(startdate + " 00:00:00").toString("yyyy/MM/dd");
    enddate = Date.parse(enddate + " 00:00:00").toString("yyyy/MM/dd");


    $.post("services/ratecards.php", {
        eventtype: "copyratecard",
        sourceid: sourceid,
        destinationids: destinationids,
        startdate: startdate,
        enddate: enddate,
        name: name,
        special: special
    }).done(function(data) {
        getMarketZones(marketsid);
        closeAllDialogs()
    });


    console.log(name);
  }



</script>