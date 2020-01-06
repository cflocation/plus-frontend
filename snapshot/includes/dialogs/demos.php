<div class="gridwrapper" style="width:100%; height:auto; overflow: hidden;">	<div id="tabs" style="height:180px">			<ul class="tab">			<li style="display: none;"><a href="#campaign">SnapShot</a></li>			<li><a href="#demographics">Demos</a></li>			<li style="display: none;"><a href="#product">Product</a></li>			<li style="display: none;"><a href="#config"><i class="fa fa-cog lg"></i></a></li>		</ul>	  		<div id="campaign">			<div class="spacing"></div>			<div class="ftext">Campaign Name:</div>			<input type="text" id="campaign-name" class="finput">			<div class="spacing"></div>			<span style="font-size:8pt; font-style:italic;">Also known as Proposal Name</span>		</div>		<div id="demographics">						<div class="ftext">Group :</div>			<select  class="fselect" id="group" name="group">				<option value="Households" selected="selected">Households</option>					<option value="Adults">Adults</option>					<option value="Men">Men</option>				<option value="Women">Women</option>				<option value="Teens">Teens</option>				<option value="Children">Children</option>				<option value="Persons">Persons</option>				<option value="Homes">Homes</option>				<option value="WWomen">WWomen</option>			</select>						<div class="spacing"></div>						<div class="ftext">Age From :</div>						<select  class="fselect" id="agefrom" name="agefrom">				<option value="0" selected="selected">0</option>					<option value="2">2</option>				<option value="6">6</option>				<option value="12">12</option>				<option value="18">18</option>				<option value="21">21</option>				<option value="25">25</option>				<option value="35">35</option>				<option value="45">45</option>				<option value="50">50</option>				<option value="55">55</option>				<option value="65">65</option>			</select>						<div class="spacing"></div>						<div class="ftext">Age To :</div>			<select  class="fselect" id="ageto" name="ageto">				<option value="5">5</option>					<option value="11">11</option>				<option value="17">17</option>				<option value="20">20</option>				<option value="24">24</option>				<option value="34">34</option>				<option value="44">44</option>				<option value="49">49</option>				<option value="54">54</option>				<option value="64">64</option>				<option value="99" selected="selected">99</option>			</select>					</div>	  		<div id="product">			<div class="spacing"></div>			<div class="ftext">Product:</div>			<input type="text" id="product-name" class="finput">		</div>				<div id="config">			<div class="spacing"></div>			<div class="spacing"></div>			<span id="showtype-mode">				<input type="checkbox" id="grouped" name="grouped" value="0" class="finput-checkbox">				<label for="grouped">Grouped</label>			</span>			&nbsp; 			<span style="font-size:8pt; font-style:italic;">( Check to collapse the events. )</span>		</div>	</div>	<div style="position:absolute; bottom:20px; margin-left:40%;">		<center>			<img src="i/ajax.gif" id="scx-wait-img" style=" display: none;"> 			<BR><BR>			<span style="display: none;"  id="scx-wait-msg">Downloading please wait ...</span>		</center>	</div>			<input type="button" class="btn-green" id="download-scx" onclick="downloadScx(); $(this).attr('disabled','disabled');" value="Download" style="position:absolute; bottom:20px; margin-left:39%; width: 100px; height: 38px; font-size: larger;">	</div><p style="font-size: larger; display: none;">	<label for="grouped">Grouped:<input type="checkbox" id="scxgrouped" name="scxgrouped" value="0" style=""> </label><span style="font-size: smaller">(Check to collapse the events.)</span></p><script>	$("#tabs").tabs({ selected: 1 });		$("#download-scx").button();	$("#download-proposal-list").val();	$("#campaign-name").val($("#download-proposal-list option:selected").text());		function downloadScx(){		var x 	= buildDownloadURL();				$('#download-scx,#scx-wait-msg,#scx-wait-img').toggle();						var group 		=  	$('#group').val();		var agefrom 	= 	$('#agefrom').val();		var ageto		=	$('#ageto').val();		var campaign	=	$('#campaign-name').val();		var product		=	$('#product-name').val();		var groupedLines=   false;				if($('#scxgrouped').is(':checked')){			groupedLines = true;		}		x = x + '&group='+group;		x = x + '&agefrom='+agefrom;		x = x + '&ageto='+ageto;		x = x + '&campaign='+encodeURIComponent(campaign);		x = x + '&product='+product;		x = x + '&grouped='+groupedLines;				var url 	= 'https://snapshotdownloads.showseeker.com/scx'+x;		url     	= 'https://snapshotdownloads.showseeker.com/scx'+x;		var json = '../goplus/services/jsonbridge.php?url='+encodeURIComponent(url);				//get the json result for the data		$.getJSON(json, function(data) {    		try{    			var pslInfo = datagridProposalManager.getProposalInfo(proposalid);    			var d 		= buildMixDownloadParams('SCX', pslInfo.name, filename);    			usrIp('SnapShot - Download',d);    		}catch(e){}    		    					var filename = data.filename;			var link = '../goplus/services/fdownload.php?filename='+filename;			$("#dialog-window").dialog("destroy");			window.location.href = link;		});	}	</script>