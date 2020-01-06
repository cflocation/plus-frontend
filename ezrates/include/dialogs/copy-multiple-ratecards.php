<div style="padding:5px;">

	<!-- div class="row padder">
		<div class="small-12">
			<div class="row">
				<div class="small-3 columns">
					<label for="ratecard-name-copy" class="right inline">Group:</label>
				</div>
				<div class="row collapse">	        
					<div class="small-9 columns">
						<input type="text" id="ratecard-name-copy" placeholder="Group Name">
					</div>
				</div>
			</div>
		</div>
	</div -->	
	 
	<div class="row padder" style="display: none;" id="new-group-input-copy">
		<div class="small-12">
			<div class="row">
				<div class="small-3 columns">
					<label for="ratecard-market" class="right inline">Group:</label>
				</div>
				<div class="row collapse">
					<div class="small-6 columns">
						<input type="text" id="ratecard-name-copy" placeholder="Group Name">
					</div>
					<div class="small-3 columns">
						<a href="#" onclick="$('#existing-group-input-copy,#new-group-input-copy').toggle();" class="button postfix radius" style="color: white !important;"><i class="fa fa-arrow-circle-left fa-lg" style="color: white !important;"></i> Back</a>
					</div>
				</div>
			</div>
		</div>
	</div>  
	
	
	
	<div class="row padder"  id="existing-group-input-copy">
		<div class="small-12">
			<div class="row">
				<div class="small-3 columns">
					<label for="ratecard-market" class="right inline">Group:</label>
				</div>
				<div class="row collapse">
					<div class="small-6 columns">
						<select name="ratecard-group-name" id="ratecard-group-name"><option value="0">Group Name</option></select>
					</div>
					<div class="small-3 columns">
						<a href="#" onclick="$('#existing-group-input-copy,#new-group-input-copy').toggle();$('#ratecard-group-name').val(0);" class="button postfix radius" style="color: white !important;">Add New</a>
					</div>
				</div>
			</div>
		</div>
	</div>  	 
	
	<div class="row padder">
		<div class="small-12">
			<div class="row">
				<div class="small-3 columns">
					<label for="ratecard-special-copy" class="right inline">Priority:</label>
				</div>
				<div class="row collapse">
					<div class="small-9 columns">
						<select id="ratecard-special-copy">
							<option value="0">NO</option>
							<option value="1">YES</option>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	
	 <div class="row padder">
	    <div class="small-12">
	      <div class="row">
		      <div class="row collapse">
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
	 </div>
	
	<div class="row padder">
		<div class="small-12">
			<div class="row">
				<div class="small-3 columns">
					<label for="" class="right inline"></label>
				</div>
				<div class="row collapse">
					<div class="small-9 columns">
						<button onclick="copyMultipleRatecardsEvent();" type="submit" class="button tiny green"><i class="fa fa-files-o"></i> Copy Ratecards</button>
						<button onclick="closeAllDialogs();" class="button tiny darkred center"><i class="fa fa-times-circle fa-lg"></i> Close</button>
					</div>
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
		getGroups();
		/*$("#ratecard-zone > option").each(function() {
			if(this.value != ""){
				$('#ratecard-zone-copy').append($("<option></option>").attr("value", this.value).text(this.text));
			}
		});*/

		$('#ratecard-zone-copy').val(row[0].zoneid);
		$('#ratecard-name-copy').val(row[0].name + ' - copy');
		
		if(row[0].broadcast == 1){
			$("#ratecard-zone-copy").prop('disabled', true);
		}

		//Build the calendars
		$("#ratecard-start-date-copy,#ratecard-end-date-copy").datepicker({numberOfMonths: 1,dateFormat: "mm/dd/y"});
		$("#ratecard-start-date-copy,#ratecard-end-date-copy").datepicker("option", "firstDay", 1 );
		$("#ratecard-start-date-copy,#ratecard-end-date-copy").datepicker("option", "showTrailingWeek", false );
		$("#ratecard-start-date-copy,#ratecard-end-date-copy").datepicker("option", "showOtherMonths", true );
		$("#ratecard-start-date-copy,#ratecard-end-date-copy").datepicker("option", "selectOtherMonths", true );
		$("#ratecard-start-date-copy").datepicker("setDate",copystartdate);
		$("#ratecard-end-date-copy").datepicker("setDate",copyenddate);
		
		

  });


  function copyMultipleRatecardsEvent() {
    var row 			= datagridRatecards.selectedRows();
    //var sourceid 		= row[0].id;
    //var destinationids 	= $('#ratecard-zone-copy').val();
    var startdate 		= $('#ratecard-start-date-copy').val();
    var enddate 		= $('#ratecard-end-date-copy').val();
    var special 		= $('#ratecard-special-copy').val();
    
    if($('#ratecard-name-copy').is(':visible')){
		var name 	= $('#ratecard-name-copy').val();
    }
    else{
		var name 	= $('#ratecard-group-name').val();
    }


    startdate 			= Date.parse(startdate + " 00:00:00").toString("yyyy/MM/dd");
    enddate 			= Date.parse(enddate + " 00:00:00").toString("yyyy/MM/dd");

    $.post("services/ratecards.multiple.copy.php", {
	    ratecards: JSON.stringify(row),
        eventtype: "copymultipleratecard",
        startdate: startdate,
        enddate: enddate,
        name: name,
        special: special
    }).done(function(data) {
        getMarketZones(marketsid);
        closeAllDialogs()
    });
  }
  
  
	function getGroups(){
		var url = "services/groups.php?eventtype=list&marketid="+marketsid;
		
		$.getJSON(url, function(data){
			$.each(data,function(i,value){
				$.each(value,function(j,val){
				$('#ratecard-group-name').append($('<option></option>').attr('value', val.name).text(val.name));
				});
			});
		});
	}
</script>