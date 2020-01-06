<form method="POST" name="displayNetworkGrid" id="ezgridsform" action="index.php">				
	<input type="Hidden" name="timezone" 	id="timezone" 	value="<?php print($tzmapped);?>">
	<input type="Hidden" name="userid" 		id="userid" 	value="<?php print($userid);?>">
	<input type="Hidden" name="marketid" 	id="marketid" 	value="<?php print($marketid);?>">
	<input type="Hidden" name="apiKey" 		id="apiKey" 	value="<?php print($apiKey);?>">
	
	<div class="proposalmanager">	

		<div class="inshowcontrols">
			<span class="labels hander menu-btn" id="menu">
				<i class="fa fa-bars fa-lg" ></i>
			</span>
		</div>

		<div class="inshowcontrols" style="width:10px;">&nbsp;</div>

		<div class="inshowcontrols">
			<span class="labels" style="color:white">SnapShot</span>
			<input name="proposal" id="proposal" value="" class="pslinputs" style="width:177px">
			<input type="button" id="createproposal" value="Create" class="add">
			<span class="waiting-msg" style="display: none;" id="waitingmsg">Wait ...</span>
		</div>

		<div class="inshowcontrols" style="width:10px;">&nbsp;</div>
	
		<div class="inshowcontrols">
			<span class="labels" style="color:white">Select</span>
			<select onChange="selectedProposal()"  class="pslinputs" style="width:170px;"  id="proposalList" name="proposalList" >
				<option value=""></option>
			<select>
		</div>
		
		<div class="inshowcontrols" style="width:10px;">&nbsp;</div>	
		
		<div class="inshowcontrols" >
			<span  name="printPdfGrid" id="printPdfGrid" class="add" style="width: 120px;" onmousemove="style:cursor='pointer';">Print Current View</span>
		</div>
		
		<div class="inshowcontrols" style="width:10px;">&nbsp;</div>
		
		<div class="inshowcontrols">
			<span class="labels" style="color: white;">Add</span>
			
			<select id="highlightThisType" onchange="checkAllState();"  class="selectors" style="width:82px;">
				<?php foreach ($programtypes as &$value) { 
					print("<option value='".$value."'>".$value."</option>");
				}?>
			</select>
			<span class="mycheckbox"><input type="checkbox" name="selectAllBoxes" id="selectAllBoxes"  value="0" onclick="selectAllCheckBoxes()"  name="selectAll" 
					title="Check to select all the programs from the current Tab"></span>			
		</div>		
	</div>


	<div class="params">
		
		<div class="outer">
			<div class="middle">
				<div class="inner">

					<div class="inshowcontrols">
						<span class="labels">Zone</span>
						<select name="zones" id="zones" class="selectorw rounded-corners" style="width:120px;"></select>
					</div>
					
					<div class="inshowcontrols separators" style="width:20px; display: none;">&nbsp;</div>
					
					<div class="inshowcontrols">
						<span class="labels">Network</span>
						<select name="station" id="station" class="selectors"></select>	
					</div>
					
					<div class="inshowcontrols separators" style="width:20px; display: none;">&nbsp;</div>
					
					<div class="inshowcontrols">
					
						<span class="labels">Dates</span>
						
						<input name="startDate" id="startDate" class="ui-calendars" type="text" value="<?php print(date('m/d/Y',strtotime($startDate)))?>" readonly="yes">
						
						<span>-</span>
						
						<input name="endDate" 	id="endDate" 	class="ui-calendars" type="text" value="<?php print(date('m/d/Y',strtotime($endDate)))?>" readonly="yes">
					
					</div>	
			
					<div class="inshowcontrols separators" style="width:20px; display: none;">&nbsp;</div>
				
					<div class="inshowcontrols">
						<span class="labels">Time</span>	
						<select id="sTime" name="sTime"  class="selectors">
							<?php foreach ($hours as $key => $value) { 
								print("<option value=".$value['HOUR_FIXED'].">".$value['HOUR_DISPLAY']."</option>");
							}?>
						</select>
						<span class="labels"> - </span>
						<select id="eTime" name="eTime" class="selectors">
							<?php foreach ($hours as $key => $value) { 
								print("<option value=".$value['HOUR_FIXED'].">".$value['HOUR_DISPLAY']."</option>");
							}?>
						</select>
					</div>
					
					<div class="inshowcontrols separators" style="width:98px; display: none;">&nbsp;</div>
					<div class="inshowcontrols" style="width:25px;">&nbsp;</div>
					<div class="inshowcontrols" >
						<input class="update" type="button" value="Update SnapShot" id="updategrid">
					</div>

				</div>
			</div>
		</div>
	</div>
</form>
