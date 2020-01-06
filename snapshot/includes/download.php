<div class="leftpanel dlmid rounded-corners" style="width:700px; height: 580px;">
	<div style="height: 580px; width:690px; ">
		<div style="width:520px; display: table; margin: 0 auto;">
			
		
			<div class="leftpanel">
				
				<div class="downloadwrapper rounded-corners">
					<div class="headers-small download">
						SnapShot File:
					</div>						
					<p class="active selected"><span style="padding-left: 5px; overflow-x: hidden; padding-right: 5px;" id="snapShotFileName"></span></p>
					<!-- p><div style="padding-left: 5px; overflow-x: hidden; padding-right: 5px; width: 235px;" id="snapShotFileName" class="slick-cell l1 r1 active selected"></div></p -->
				</div>
				
				<p></p>
				
				<div class="downloadwrapper rounded-corners">
					<div class="headers-small download">
						Sorting
					</div>
					<div class="mbody">
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
						<input id="download-include-logos" type="checkbox"  value="1" />
						<label for="download-include-logos">Include Logos</label>
						<br>
						<input id="download-include-description"  type="checkbox" value="1"  />
						<label for="download-include-description">Include Description</label>
						<br>
						<input id="download-include-episode" checked="checked" type="checkbox" value="1" />
						<label for="download-include-episode">Include Episode</label>
						<input id="download-include-new" checked="checked" type="checkbox" value="1" style="display: none" />
					</div>
				</div>
			</div>
	
	
			<div class="leftpanel" style="width:55px;">
				<a href="javascript:dialogDownloadFile('xls');">
					<img class="fadelink" width="57" border="0" src="i/logos/xls/xls.jpg" title="Standard format with logos">
				</a>
				<!-- a href="javascript:dialogDownloadFile('xls-spec');">
					<img class="fadelink" width="57" border="0" src="i/logos/detail.png" title="Detailed in column format">
				</a -->
			</div>
	
			<!-- div class="leftpanel" style="width:55px;">
				<a href="javascript:dialogDownloadFile('wordnorates');">
					<img class="fadelink" width="57" border="0" src="i/logos/word.png">
				</a>
			</div -->	

			<div class="leftpanel" style="width:55px;">
				<a href="javascript:dialogDownloadFile('pdf');">
					<img class="fadelink" width="57" border="0" src="i/pdf_download.png">
				</a>	
			</div>		
	
			<div class="leftpanel" style="width:55px;">
				<a href="javascript:dialogDownloadFile('strata');">
					<img class="fadelink" width="57" border="0"  src="i/logos/strata-logo.jpg">
				</a>
			</div>
	
		</div>
	</div>
</div>


