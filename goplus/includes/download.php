<div class="leftpanel dlproposal rounded-corners" style="height: 580px;">

	<div class="downloadwrapper rounded-corners">
		<div class="headers-small download">
			Proposal
		</div>
		
		<div class="mbody">
			<select size="10" onchange="downloadSetProposal();" id="download-proposal-list" class="input-full rounded-corners"></select>
		</div>
	</div>
	
	<p></p>
	
	<div class="downloadwrapper rounded-corners">
		<div class="headers-small download">
			Customize Proposal Title
			<div style="float:right;">
				<i onclick="loadHeaders(); mixTrack('Download - Proposal Title');"  class="fa fa-folder-open hander fa-lg"></i>&nbsp;
			</div>
		</div>
		
		<div class="mbody">
			<i onclick="downloadHeaderRemove();" id="download-remove-header" class="fa fa-times-circle fa-lg hander hander" style="color:red;display:none;"></i>&nbsp;
			<span id="download-panel-header">No custom title</span>
		</div>
	</div>



	<div id="cleint-download-selector-wrapper">
		<p></p>
		
		<div class="downloadwrapper rounded-corners">
			<div class="headers-small download">Client
				<div style="float:right;"><i onclick="loadManager();" class="fa fa-folder-open hander fa-lg"></i>&nbsp;</div>
			</div>
			<div class="mbody">
				<i onclick="downloadClientRemove();" id="download-remove-client" class="fa fa-times-circle fa-lg hander" style="color:red;display:none;"></i>&nbsp;<span id="download-panel-client">No client loaded</span>
			</div>
		</div>
	
	</div>


	<!-- zolo media -->
	<div id="cleint-download-selectorlist-wrapper" style="display:none;">
		<p></p>
		<div class="downloadwrapper rounded-corners">
			<div class="headers-small download">
				Client
			</div>
			<div class="mbody">
				<select id="client-download-selectorlist" size="1" class="input-full rounded-corners"></select>
			</div>
		</div>
	</div>



	<div id="agency-download-selectorlist-wrapper" style="display:none;">
		<p></p>
		<div class="downloadwrapper rounded-corners">
			<div class="headers-small download">
				Agency
			</div>
			<div class="mbody">
				<select id="agency-download-selectorlist" size="1" class="input-full rounded-corners"></select>
			</div>
		</div>
	</div>



	<div id="repfirm-download-selectorlist-wrapper" style="display:none;">
		<p></p>
		<div class="downloadwrapper rounded-corners">
			<div class="headers-small download">
				RepFirm
			</div>
			<div class="mbody">
				<select id="repfirm-download-selectorlist" size="1" class="input-full rounded-corners"></select>
			</div>
		</div>
	</div>
	<!-- end zolo media -->




	<!-- proposal items -->
	<div id="proposal-download-list-1" style="display:none;">
		<p></p>
		<div class="downloadwrapper rounded-corners">
			<div id="proposal-download-label-1" class="headers-small download"></div>
			<div class="mbody">
				<select id="proposal-download-selector-1" size="1" class="input-full rounded-corners"></select>
			</div>
		</div>
	</div>



	<div id="proposal-download-list-2" style="display:none;">
		<p></p>
		<div class="downloadwrapper rounded-corners">
			<div id="proposal-download-label-2" class="headers-small download"></div>
			<div class="mbody">
				<select id="proposal-download-selector-2" size="1" class="input-full rounded-corners"></select>
			</div>
		</div>
	</div>



	<div id="proposal-download-list-3" style="display:none;">
		<p></p>
		<div class="downloadwrapper rounded-corners">
			<div id="proposal-download-label-3" class="headers-small download"></div>
			<div class="mbody">
				<select id="proposal-download-selector-3" size="1" class="input-full rounded-corners"></select>
			</div>
		</div>
	</div>
	<!--  end proposal items-->


</div>



<div class="leftpanel dlmid rounded-corners" style="width:390px; height: 580px;">


	<div class="leftpanel">

		<div class="downloadwrapper rounded-corners">
			<div class="headers-small download">
				Sorting
			</div>
			
			<div class="mbody">

				<div id="marathon-sorting-text" style="display:none;">
					<center><b>You're in Marathons Mode Sorting</b></center>
					<br>
				</div>

				<select onchange="downloadSortOne();" id="download-sort-1" class="input-full rounded-corners">
					<option value='network'>Network</option>
					<option value='startdate' selected="selected">Date</option>
					<option value='starttime'>Time</option>
					<option value='title'>Program Title</option>
				</select>
				
				<p></p>
				
				<select id="download-sort-2" class="input-full rounded-corners">
					<option value='network'>Network</option>
					<option value='startdate'>Date</option>
					<option value='starttime' selected="selected">Time</option>
					<option value='title'>Program Title</option>
				</select>
				
				<p></p>
				
				<select id="download-sort-3" class="input-full rounded-corners">
					<option value='network' selected="selected">Network</option>
					<option value='startdate'>Date</option>
					<option value='starttime'>Time</option>
					<option value='title'>Program Title</option>
				</select>
				
				<center>
					<p><button onclick="resetSorting();" class="btn-blue">Default Sorting</button></p>
				</center>

			</div>
		</div>



		<p></p>

		<div class="downloadwrapper rounded-corners">
			
			<div class="headers-small download">
				Output Options
			</div>
			
			<div class="mbody">

				<input id="download-include-logos" type="checkbox" checked="checked" value="1" />
				<label for="download-include-logos">Logos</label>
				<br>

				<input id="download-include-description" checked="checked" type="checkbox" value="1" />
				<label for="download-include-description">Description</label>
				
				<br>
				<input id="download-show-episode" type="checkbox" value="1"  checked="true" />
				<label for="download-show-episode">Episode Title</label>

				<br>
				<input id="download-include-new" checked="checked" type="checkbox" value="1" />
				<label for="download-include-new">New</label>

				<br>
				<input id="download-hide-rates" type="checkbox" value="1" />
				<label for="download-hide-rates">Hide Rates</label>

				<span id="download-show-rates-wrapper">
					<br>
					<input id="download-show-rates" type="checkbox" value="1" />
					<label for="download-show-rates">Ratecard</label>
				</span>
				
				<span id="download-show-ratings-wrapper" style="display: none;">
					<br>
					<input id="download-show-ratings" type="checkbox" value="1" checked="true" />
					<label for="download-show-ratings">Ratings</label>
				</span>

				<span id="download-show-rates-inlineRtg" style="display: none;">
					<br>
					<input id="download-show-inlineRtg" type="checkbox" value="1" checked="true" />
					<label for="download-show-inlineRtg">Inline Ratings</label>
				</span>



				<!-- br>

				<input id="download-only-fixed" type="checkbox" value="1" />
				<label for="download-only-fixed">Select Only Fixed Output</label -->

				<br>
				<span id="termsblock" style="display:none;">
					<input id="download-add-terms" type="checkbox" value="1" />
					<label for="download-add-terms">Add Terms & Conditions</label>
				</span>
			</div>
		</div>
	</div>


	<div class="leftpanel" style="width:55px;">
		<a href="javascript:dialogDownloadFile('xls');">
			<img class="fadelink" width="57" border="0" src="i/logos/xls/xls.jpg" title="Standard format with logos">
		</a>
		<a href="javascript:dialogDownloadFile('xls2');">
			<img class="fadelink" width="57" border="0" src="i/logos/xls/xlsreport.png" title="Report format no logos or descriptions">
		</a>
		<a href="javascript:dialogDownloadFile('xls-spec');">
			<img class="fadelink" width="57" border="0" src="i/logos/detail.png" title="Detailed in column format">
		</a>
		<a href="javascript:dialogDownloadFile('word');">
			<img class="fadelink" width="57" border="0" src="i/logos/word.png">
		</a>
		<a href="javascript:dialogDownloadFile('wordnorates');">
			<img class="fadelink" width="57" border="0" src="i/logos/wordnr.png">
		</a>
		<a href="javascript:dialogImagePPTSelector();">
			<img class="fadelink" width="57" border="0" src="i/logos/powerpoint.png">
		</a>	
		<a href="javascript:dialogDownloadFile('pdf');">
			<img class="fadelink" width="57" border="0" src="i/logos/PDF5.png">
		</a>
		
	</div>


	<div class="leftpanel" style="width:55px;">
		<span id="download-images-adsails" style="display:none;">
			<a href="javascript:dialogDownloadFile('adsails');">
				<img class="fadelink" width="57" border="0"  src="i/logos/adsails-logo.jpg">
			</a>
		</span>
		<span id="download-images-novar" style="display:none;">
			<a href="javascript:dialogDownloadFile('novar');">
				<img class="fadelink" width="57" border="0" src="i/logos/novar.png">
			</a>
		</span>
		<span id="download-images-eclipse" style="display:none;">
			<a href="javascript:dialogEclipse();">
				<img class="fadelink" width="57" border="0"  src="i/logos/eclipse-logo.png">
			</a>
		</span>
		<a href="javascript:dialogDownloadFile('strata');">
			<img class="fadelink" width="57" border="0"  src="i/logos/strata-logo.jpg">
		</a>
		<a href="javascript:downloadProposalXML('xml');">
			<img class="fadelink" width="57" border="0"  src="i/logos/xml.png">
		</a>
		<a href="javascript:dialogDownloadFile('avails');">
			<img class="fadelink" width="57" border="0" src="i/logos/avails.png">
		</a>
		<!--  span id="iseeker-images-download">
			<a href="javascript:dialogImageSelector();">
				<img class="fadelink" width="57" border="0" src="i/logos/images.png">
			</a>
		</span -->
		<!-- span id="ezratings-downloads" style="display: none;">
			<a href="javascript:dialogRatingsReport();">
				<img class="fadelink" width="57" border="0" src="i/logos/ratings_report4.gif">
			</a>
		</span -->
		<span id="api-images-download" style="display: none;">
			<a href="javascript:dialogDownloadApi('api');">
				<img class="fadelink" width="57" border="0" src="i/logos/api_icon.gif">
			</a>
		</span>
	</div>


</div>







<div class="leftpanel dlright rounded-corners" style="width:260px; height: 580px;">

	<div class="downloadwrapper rounded-corners">
		<div class="headers-small download">To Email Your Customer</div>
		<div class="mbody">
			<table cellpadding="3">
				<tr>
					<td nowrap="nowrap" align="right">Company:</td>
					<td><input class="input-q rounded-corners" id="email-company" type="text"/></td>
				</tr>

				<tr>
					<td nowrap="nowrap" align="right">First Name:</td>
					<td><input class="input-q rounded-corners" id="email-first-name" type="text"/></td>
				</tr>

				<tr>
					<td nowrap="nowrap" align="right">Last Name:</td>
					<td><input class="input-q rounded-corners" id="email-last-name" type="text"/></td>
				</tr>

				<!-- tr>
					<td nowrap="nowrap" align="right">Traffic ID:</td>
					<td><input class="input-half rounded-corners"id="email-traffic-id" type="text"/></td>
				</tr>

				<tr>
					<td nowrap="nowrap" align="right">Strata ID:</td>
					<td><input class="input-half rounded-corners"id="email-strata-id" type="text"/></td>
				</tr -->

				<tr>
					<td nowrap="nowrap" align="right">Message:</td>
					<td><textarea class="rounded-corners input-wrapper" id="email-message" style="width:150px;height:70px;"></textarea></td>
				</tr>

				<tr>
					<td nowrap="nowrap" align="right">Subject Line:</td>
					<td><input class="input-q rounded-corners" id="email-subject" type="text"/></td>
				</tr>

				<tr>
					<td nowrap="nowrap" align="right">Email 1:</td>
					<td><input class="input-q rounded-corners" id="email-email1" type="text"/></td>
				</tr>

				<tr>
					<td nowrap="nowrap" align="right">Email 2:</td>
					<td><input class="input-q rounded-corners" id="email-email2" type="text"/></td>
				</tr>

				<tr>
					<td nowrap="nowrap" align="right"></td>
					<td>

						<input id="email-attach-word-document" type="checkbox" value="1" />
						<label for="email-attach-word-document">Attach Word Document</label>
							<br>
						<input id="email-attach-word-no-rates" type="checkbox" value="1" />
						<label for="email-attach-word-no-rates">Attach Word no Rates</label>
							<br>
						<input id="email-attach-excel-document" type="checkbox" value="1" />
						<label for="email-attach-excel-document">Attach Excel Document</label>
							<br>
						<input id="email-attach-pdf-document" type="checkbox" value="1" />
						<label for="email-attach-pdf-document">Attach PDF File</label>
							<br>
						<input id="email-attach-strata-document" type="checkbox" value="1" />
						<label for="email-attach-strata-document">Attach SCX File</label>
						<!-- br>
						<input id="email-attach-powerpoint-document" type="checkbox" value="1" />
						<label for="email-attach-powerpoint-document">Attach Powerpoint</label -->
					</td>
				</tr>

				<tr>
					<td colspan=2>
						<center><button onclick="sendEmail();">Send Email</button></center>

					</td>
				</tr>

			</table>
		</div>
	</div>

</div>











