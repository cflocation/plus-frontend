<table>
	<tbody>
		<tr>
			<td class="inshowcontrols">

			<div class="inshowcontrols" id="menu">
				<span class="labels hander menu-btn">
				<i class="fa fa-bars fa-lg" ></i>
				</div>
			</span>
    		</td>
			<td class="inshowcontrols">

				<div class="inshowcontrols">
					<span class="labels" style="color:white">Proposal</span>
					<select onChange="selectedProposal()"  class="pslinputs selectorw rounded-corners" style="width:170px;"  id="proposalList" name="proposalList" >
						<option value="0"> -------- Select --------</option>
					<select>
					<button id="ezNewProposal" class="add-blue hander"><i class="fa fa-plus"></i> Add&nbsp;</button>
				</div>
			</td>
			<td class="inshowcontrols">

				<div class="inshowcontrols" >
					<select  class="selectorw rounded-corners pslinputs" style="width:141px;" id="pdfGridOption">
						<option value="1">PDF Current View</option>
						<option value="2">PDF All Weeks</option>				
					</select>
						<span name="printPdfGrid" id="printPdfGrid" class="add hander">&nbsp;<i class="fa fa-arrow-circle-down" aria-hidden="true"></i>&nbsp;</span>
				</div>
			</td>
			<td class="inshowcontrols">
			

			<div class="inshowcontrols">
				<div class="dropdown">
					<span onclick="openList()" class="dropbtn btn-blue hander">Specials &nbsp; <i class="fa fa-check-square-o fa-lg"></i> &nbsp;</span>
					<div id="showTypes" class="dropdown-content"></div>
				</div>
			</div>
			</td>
			<td class="inshowcontrols">
				
				<div class="inshowcontrols" style="display: none;" id="demosCtrl">
				<span class="labels" style="color: white;">Demos</span>
				<select id="rtgDemoSelector" class="selectorw rounded-corners" style="width: 80px;"><!--option value="0"></option--></select>
				</div>
			</td>
			<td class="inshowcontrols">

			   <div class="inshowcontrols" style="display: none; color: #fff;" id="surveyInfo">
					<span id="surveyName" class="rtgSettings"></span> 
					<span id="rtgInfoPipe" class="rtgSettings" style="color:#FFC871;"></span> 
					<span id="rtgAreas" class="rtgSettings"></span> 	
				</div>
				
			</td>
			<td class="inshowcontrols">

				<div class="inshowcontrols">
					<span id="ratings-status" style="color:#FFC871; display: none;">
						<i class="fa fa-spinner fa-spin fa-fw fa-lg" ></i>
					</span>
				</div>
			</td>
		</tr>
	</tbody>
</table>

