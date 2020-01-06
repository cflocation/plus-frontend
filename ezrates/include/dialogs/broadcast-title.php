<br>
<div class="row">
    <div class="small-12">
      <select id="broadcast-title-selector"></select>
    </div>
</div>


<div class="row">
    <div class="small-12">
      <input type="text"  placeholder="Alternate Show Title" id="broadcast-title-text"></input>
	</div>
</div>


<center>
	<button onclick="addBroadcastRateToGrid();" type="submit" class="button tiny green"> Save to Ratecard </button>
</center>


<script type="text/javascript">
  var starttime = $('#ratecard-broadcast-start-time').val();
  starttime = Date.parse(starttime).toString("HH:mm:ss");
  starttimeID = Date.parse(starttime).toString("HHmmss");

  var endtime = $('#ratecard-broadcast-end-time').val();
  endtime = Date.parse(endtime).toString("HH:mm:ss");
  endtimeID = Date.parse(endtime).toString("HHmmss");


  var days = $('#ratecard-broadcast-days').val();

  var jsondata = '';

	$.post("services/ratecards.php", {
		eventtype: "addbroadcastrate", 
		ratecardid:ratecardid,
		starttime:starttime,
		endtime:endtime,
		days:days
		}).done(function(data){
		var json = jQuery.parseJSON(data);
		jsondata = json;

		console.log(jsondata);

		$('#broadcast-title-selector').append($("<option></option>").attr("value",0).text("Select Title"));
		$.each(json.titles, function(i, value) {
			$('#broadcast-title-selector').append($("<option></option>").attr("value",value).text(value));
		});
	});





	function addBroadcastRateToGrid(){
		var rate = $('#pricing-daypart-broadcast').val();
		var fixed = $('#pricing-fixed-broadcast').val();
		var fname = $('#broadcast-title-selector option:selected').text()

		var daysid = jsondata.days.replace(/,/g , "");
		var id = jsondata.networkid + starttimeID + endtimeID + daysid;

		if(isNaN(parseFloat(rate))){
			rate = 0;
		}

		if(isNaN(parseFloat(fixed))){
			fixed = 0;
		}

		var row = {};
		row.id = id;
		row.rate = parseFloat(rate);
		row.ratefixed = parseFloat(fixed);
		row.fname = fname;
		row.starts = jsondata.starttime;
		row.stops = jsondata.endtime;
		row.weekdays = jsondata.days;
		row.callsign = jsondata.callsign;;
		row.networkid = jsondata.networkid;

		datagridPricingBroadcast.addRow(row);
	}

</script>

