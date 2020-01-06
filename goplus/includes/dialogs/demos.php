<div class="gridwrapper" style="width:100%; height:auto; overflow: hidden;">	<div id="tabs" style="height:250px">		<ul class="tab">			<li><a href="#demographics">Demos</a></li>			<li><a href="#campaign">Options</a></li>		</ul>		<div id="campaign">			<div class="spacing"></div>			<div class="ftext">* Campaign Name:</div>			<input type="text" id="campaign-name" class="finput">						<div class="spacing"></div>												<div class="ftext">Group Lines ? :</div>			<div class="finput" style="float: left;">				<input type="checkbox" value="0" id="groupedLines" style="display: none;">				<span class="hander" style="margin-left: 1px;">					<i class="fa fa-square-o fa-2x" style="color: #999999;" id="groupedLinesOptionOff"></i>					<i class="fa fa-check-square-o fa-2x" style="color: #999999; display: none;" id="groupedLinesOptionOn"></i>				</span>			</div>						<br style="clear: both;">			<div class="spacing"></div>			<span style="font-size:8pt; font-style:italic;">* Also known as Proposal Name</span>		</div>		<div id="demographics">				<div class="spacing"></div>						<div class="ftext">Group :</div>						<select  class="fselect" id="group" name="group">				<option value="Households" selected="selected">Households</option>					<option value="Adults">Adults</option>					<option value="Men">Men</option>				<option value="Women">Women</option>				<option value="Teens">Teens</option>				<option value="Children">Children</option>				<option value="Persons">Persons</option>				<option value="Homes">Homes</option>				<option value="WWomen">WWomen</option>			</select>						<div class="spacing"></div>						<div class="ftext">Age From :</div>						<select  class="fselect" id="agefrom" name="agefrom">				<option value="0" selected="selected">0</option>					<option value="2">2</option>				<option value="6">6</option>				<option value="12">12</option>				<option value="18">18</option>				<option value="21">21</option>				<option value="25">25</option>				<option value="35">35</option>				<option value="45">45</option>				<option value="50">50</option>				<option value="55">55</option>				<option value="65">65</option>			</select>						<div class="spacing"></div>						<div class="ftext">Age To :</div>						<select  class="fselect" id="ageto" name="ageto">				<option value="5">5</option>					<option value="11">11</option>				<option value="17">17</option>				<option value="20">20</option>				<option value="24">24</option>				<option value="34">34</option>				<option value="44">44</option>				<option value="49">49</option>				<option value="54">54</option>				<option value="50">64</option>				<option value="55" selected="selected">99</option>			</select>					</div>	</div>	<div style="position:absolute; bottom:20px; margin-left:40%;">		<center>			<img src="i/ajax.gif" id="scx-wait-img" style=" display: none;"> 			<br><br>			<span style="display: none;"  id="scx-wait-msg">Downloading please wait ...</span>		</center>	</div>		<input type="button" class="btn-green" id="download-scx" onclick="downloadScx(); $(this).attr('disabled','disabled');" value="Download" style="position:absolute; bottom:30px; margin-left:42%; width: 100px; height: 38px; font-size: larger;">	</div><script>			$("#tabs").tabs({ selected: 0 });	if(typeof myEzRating !== 'undefined'){		if(!$.isEmptyObject(myEzRating.ratingsData)){			if(myEzRating.ratingsData.demos.length > 0){				$("#tabs").tabs({ selected: 1 });				$("#demographics :input").prop('disabled',true);				$("#demographics").addClass('disabledDiv');				$('.tab li:first').addClass('disabledDiv');			}		}	}	$("#download-scx").button();	$("#download-proposal-list").val();	$("#campaign-name").val($("#download-proposal-list option:selected").text());	$('#groupedLinesOptionOff,#groupedLinesOptionOn').on('click',function(){		$('#groupedLinesOptionOff,#groupedLinesOptionOn').toggle();		if($('#groupedLinesOptionOn').is(':visible')){			$('#groupedLines').val('1');		}		else{			$('#groupedLines').val('0');		}	});	function downloadScx(){		var x 	= buildDownloadURL();		$('#download-scx,#scx-wait-msg,#scx-wait-img').toggle();						var group 		= $('#group').val();		var agefrom 	= $('#agefrom').val();		var ageto		= $('#ageto').val();		var campaign	= $('#campaign-name').val();		var product		= $('#product-name').val();		var grouped 	= $('#groupedLines').val();		x = x + '&group='+group;		x = x + '&agefrom='+agefrom;		x = x + '&ageto='+ageto;		x = x + '&campaign='+encodeURIComponent(campaign);		x = x + '&product='+product;		x = x + '&grouped='+grouped;				var url 	= 'https://downloadsapi.showseeker.com/';				if(parseInt(grouped) === 1){			url = url + 'scxgrouped'+x;		}		else{			url = url + 'scx'+x;		}		var json = 'services/jsonbridge.php?url='+encodeURIComponent(url);		//get the json result for the data		$.getJSON(json, function(data) {			var filename = data.filename;			var link 	 = 'services/fdownload.php?filename='+filename;						$("#dialog-window").dialog("destroy");			window.location.href = link;			logUserEvent(11,'Strata',filename,proposalid);			try{				var pslInfo = datagridProposalManager.getProposalInfo(proposalid);				var mixType	= 'SCX';				var d 		= buildMixDownloadParams(mixType, pslInfo.name, filename);				usrIp('Download',d);			}catch(e){}					});	}</script>