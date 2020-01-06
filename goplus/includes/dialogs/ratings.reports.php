<br>
<center>
	<table>
		<tr>
			<td height="25" valign="middle" style="padding: 5px;">
				<label for="timePeriodRanker" class="label">Time Period Ranker</label>
			</td>
			<td>
				<span id="timePeriodRanker">
					<input type="radio" id="timePeriodRankerOn"  name="timePeriodRanker"><label for="timePeriodRankerOn">On</label>
					<input type="radio" id="timePeriodRankerOff" name="timePeriodRanker" checked="true"><label for="timePeriodRankerOff">Off</label>
				</span>
			</td>
		</tr>
		<tr>
			<td height="25" valign="middle" style="padding: 5px;">
				<label for="daypartAnalysis" class="label">Daypart Analysis</label>
			</td>
			<td>
				<span id="daypartAnalysis">
					<input type="radio" id="daypartAnalysisOn"  name="daypartAnalysis"><label for="daypartAnalysisOn">On</label>
					<input type="radio" id="daypartAnalysisOff" name="daypartAnalysis" checked="true"><label for="daypartAnalysisOff">Off</label>
				</span>
			</td>
		</tr>
		<tr>
			<td height="25" valign="middle" style="padding: 5px;">
				<label for="netAnalysis" class="label">Network Analysis</label>
			</td>
			<td>
				<span id="netAnalysis">
					<input type="radio" id="netAnalysisOn"  name="netAnalysis"><label for="netAnalysisOn">On</label>
					<input type="radio" id="netAnalysisOff" name="netAnalysis" checked="true"><label for="netAnalysisOff">Off</label>
				</span>
			</td>
		</tr>
		<tr>
			<td height="25" valign="middle" style="padding: 5px;">
				<label for="audinceCompisition" class="label">Audience Composition</label>
			</td>
			<td>
				<span id="audinceCompisition">
					<input type="radio" id="audinceCompisitionOn"  name="audinceCompisition"><label for="audinceCompisitionOn">On</label>
					<input type="radio" id="audinceCompisitionOff" name="audinceCompisition" checked="true"><label for="audinceCompisitionOff">Off</label>
				</span>
			</td>
		</tr>
		<tr>
			<td height="75" valign="middle" style="padding: 5px;" colspan="2" align="center">
				<button class="btn-green" id="createReport"  style="padding: 4px;" onclick="javascript:downloadRatings();">Download</button>
			</td>
		</tr>
	</table>
	<table style="position: absolute; top:0; width: 96%;">
		<tr>
			<td height="25" valign="middle"  colspan="2" align="center">
				<span style="display: none; color: maroon;" id="ratingsDldMessage"><b><u>Please select a report type to continue.</u></b></span>
			</td>
		</tr>
	</table>
</center>


<script>
	$('#timePeriodRanker, #daypartAnalysis, #netAnalysis, #audinceCompisition').buttonset();
	$('#createReport').button();
	

	function downloadRatings(){
		var x = buildDownloadRatingsURL();
		var url = 'https://trailers.prod.showseeker.com/charts/x/ShowSeeker_Ratings_Report.php'+x.url;	

		if(x.count === 0 ){
			$('#ratingsDldMessage').show();
			setTimeout(function(){
				$('#ratingsDldMessage').hide();	
			}, 3000)
		}
		else{
			$.post("services/download.eclipse.php", {url:url},function(data){
				window.location.href = 'services/fdownload.php?filename='+JSON.parse(data).filename;
				closeAllDialogs();
				//logUserEvent(54,'{"Eclipse":"'+url+'"}',data,proposalid);
			});
		}
		return false;
	}	
	
	function buildDownloadRatingsURL(){
		var r = '?proposalid='+proposalid;
		var re = {};
		var timePeriod	= 0;
		var dayPart		= 0;
		var netAnalysis	= 0;
		var audienceComp= 0;
		var cnt = 0;
		
		
		if($('#timePeriodRankerOn').is(':checked')){
			timePeriod	= 1;
			cnt++;
		}
		if($('#daypartAnalysisOn').is(':checked')){
			dayPart		= 1;
			cnt++;
		}
		if($('#netAnalysisOn').is(':checked')){
			netAnalysis	= 1;
			cnt++;
		}
		if($('#audinceCompisitionOn').is(':checked')){
			audienceComp= 1;
			cnt++;
		}
		
		
		r += '&userId='+userid;	
		r += '&apiKey='+apiKey;
		r += '&timePeriod='+timePeriod;
		r += '&dayPart='+dayPart;
		r += '&netAnalysis='+netAnalysis;
		r += '&audienceComp='+audienceComp;
		
		re.url 	 = r;
		re.count = cnt;
		
		return re;
	}	
</script>

