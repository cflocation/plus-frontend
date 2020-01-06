<div id="side-menu"><!-- start main wrapper -->





<div id="ratecard-bar">

	<div class="row">
		<label  class="label" for="ratecard-zone">Zone:</label>
		<select id="ratecard-zone" class="selector rounded-corners"></select>
	</div>

	<div class="row">
		<label class="label" for="ratecard-dayparts">Dayparts:</label>
		<select id="ratecard-dayparts" class="selector rounded-corners"></select>
	</div>

	<div class="row">
		<label class="label" for="ratecard-startdate">Dates:</label>
		<input class="input-half rounded-corners" type="text" name="ratecard-startdate" id="ratecard-startdate"> to <input name="ratecard-enddate" class="input-half rounded-corners" type="text" id="ratecard-enddate">
	</div>

	<div class="row">
		<label class="label" for="ratecard-group">Group:</label>
		<select id="ratecard-group" class="selector rounded-corners"></select>
		<a style="color:#078900;" href="javascript:rescanNetworks();"><i class="fa fa-plus-circle fa-lg"></i></a>
	</div>

	<div class="row">
		<center>
			<button class="btn-red" href="">Reset</button>
			<button class="btn-green" href=""><i class="fa fa-plus-circle fa-lg"></i> Create Ratecard</button>
		</center>
	</div>

</div>



<div id="daypart-bar">

	<div class="row">
		<label class="label" for="daypart-group">Group:</label>
		<select id="daypart-group" class="selector rounded-corners"></select>
		<a style="color:#078900;" href="javascript: loadDialogWindow('form-dialog-group','Create Daypart Group',290,200);"><i class="fa fa-plus-circle fa-lg"></i></a>
	</div>

	<div class="row">
		<label class="label" for="daypart-name">Name:</label>
		<input name="daypart-name" id="daypart-name" value="M-SU" type="text" class="input-wrapper rounded-corners" > 
	</div>


	<div class="row">
		<label class="label" for="daypart-start">Times:</label>
		<input class="input-half rounded-corners" type="text" id="daypart-start"> to <input class="input-half rounded-corners" type="text" id="daypart-end">
	</div>



	<div class="rowlarge">
		<label class="label" for="daypart-days"></label>
		<select size="11" multiple="multiple" class="selector rounded-corners" id="daypart-days" name="daypart-days">
			<option selected="selected" value="1,2,3,4,5,6,7">Monday-Sunday</option>
			<option value="7,1">Saturday-Sunday</option>
			<option value="2,3,4,5,6">Monday-Friday</option>
			<option value="2">Monday</option>
			<option value="3">Tuesday</option>
			<option value="4">Wednesday</option>
			<option value="5">Thursday</option>
			<option value="6">Friday</option>
			<option value="7">Saturday</option>
			<option value="1">Sunday</option>
		</select>
	</div>


	<div class="row">
		<center>
			<button class="btn-red" href="">Reset</button>
			<button class="btn-green" href=""><i class="fa fa-plus-circle fa-lg"></i> Create Daypart</button>
		</center>
	</div>


</div>









<div id="pricing-bar" style="display:none">
	
	<div class="row">
		<label class="label" for="zone-selector">Show Columns:</label>
		<span id="fixed-toggle">
		    <input checked="checked" onclick="datagridRatecardPricing.toggleGrid('fixed')" type="checkbox" id="fixed" name="fixed-toggle" value="1" /><label for="fixed">Fixed</label>
		    <input onclick="datagridRatecardPricing.toggleGrid('fixedpct')" type="checkbox" id="fixedpercent" name="fixed-toggle" value="3" /><label for="fixedpercent">Fixed %</label>
		</span>
	</div>




	<div class="row">
		<label class="label" for="zone-selector">Changes By:</label>
		<span id="rate-mode-toggle">
			<input type="radio" checked="checked" id="ratemodepct" name="rate-mode-toggle" value="pct" /><label for="ratemodepct">Percent</label>
		    <input type="radio" id="ratemodefixed" name="rate-mode-toggle" value="fixed" /><label for="ratemodefixed">Fixed</label>
		</span>
	</div>



	<div class="row">
		<label class="label" for="zone-selector">Daypart Rate:</label>
		<input class="input-half rounded-corners" type="text" id="rate-rate">
		<button class="btn-rate" onclick="setRateEvent($('#rate-rate').val(),$('input[name=rate-mode-toggle]:checked').val())">Set Rate</button>
	</div>


	<div id="sidebar-fixed">
		<div class="row">
			<label class="label" for="zone-selector">Fixed Rate:</label>
			<input class="input-half rounded-corners" type="text" id="rate-fixed">
			<button class="btn-ratefixed" onclick="setRateEventFixed($('#rate-fixed').val(),'fixed')">Set Fixed</button>
		</div>
	</div>


	<div id="sidebar-fixed-pct" style="display:none">
		<div class="row">
			<label class="label" for="zone-selector">Fixed %:</label>
			<input class="input-half rounded-corners" type="text" id="rate-percent">
			<button class="btn-ratefixedpct" onclick="setRateEventFixed($('#rate-percent').val(),'pct')">Set %</button>
		</div>
	</div>


</div>



<div id="hotprograms-bar" style="display:none">
	<div class="row">
		<label class="label" for="zone-selector">Daypart+:</label>
		<input class="input-half rounded-corners num" type="text" id="rate-hot-datpart" value="0">
		<button class="btn-rate" onclick="setHotRateEvent($('#rate-hot-datpart').val(),'boost')">Set Rate</button>
	</div>

	<div class="row">
		<label class="label" for="zone-selector">Premiere:</label>
		<input class="input-half rounded-corners num" type="text" id="rate-hot-premiere" value="0">
		<button class="btn-rate" onclick="setHotRateEvent($('#rate-hot-premiere').val(),'premiere')">Set Rate</button>
	</div>


	<div class="row">
		<label class="label" for="zone-selector">Finale:</label>
		<input class="input-half rounded-corners num" type="text" id="rate-hot-finale" value="0">
		<button class="btn-rate" onclick="setHotRateEvent($('#rate-hot-finale').val(),'finale')">Set Rate</button>
	</div>


	<div class="row">
		<label class="label" for="zone-selector">New:</label>
		<input class="input-half rounded-corners num" type="text" id="rate-hot-isnew" value="0">
		<button class="btn-rate" onclick="setHotRateEvent($('#rate-hot-isnew').val(),'isnew')">Set Rate</button>
	</div>


	<div class="row">
		<label class="label" for="zone-selector">Live:</label>
		<input class="input-half rounded-corners num" type="text" id="rate-hot-live" value="0">
		<button class="btn-rate" onclick="setHotRateEvent($('#rate-hot-live').val(),'live')">Set Rate</button>
	</div>

</div>




<div id="help-bar" style="display:none">
	<div class="row">
		This is where we can have help
	</div>
</div>


</div><!-- end main wrapper -->