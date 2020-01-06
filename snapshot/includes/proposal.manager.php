
	
	<div class="mpanel rounded-corners" style="float:left;">
		<div class="mheader"><i class="fa fa-file"></i> File Options</div>
		<div class="mbody">
			<input class="rounded-corners input-half" name="proposal-name" id="proposal-name" type="text" style="width:200px;"> 
			<button class="btn-green" onclick="datagridProposal.emptyGrid();proposalCreateNew($('#proposal-name').val(),'blank');" id="unsavedLoadIndex">
				<i class="fa fa-file"></i> 
				Create
			</button>
			<button class="btn-blue" id="renameProposals" onclick="javascript:proposalRenameChecked();">
				<i class="fa fa-pencil"></i> 
				Rename
			</button>
			<button class="btn-red" id="deleteProposals">
				<i class="fa fa-trash"></i> 
				Delete
			</button>
		</div>
	</div>	
	
	<br style="clear:both;"><br style="clear:both;">	
	<div class="headers proposalmanager" >
		<i class="fa fa-th-list"></i> 
		<span id="label-user-name">
			Proposal List
		</span>
		&nbsp;&nbsp;
		<span style="color:#3b0e3c" id="proposalCount"></span>
		<!-- button style="float:right" class="btn-blue" id="backToShowSeeker"><i class="fa fa-search"> </i> Go to ShowSeeker</button -->
	</div>

	<div class="gridwrapper">
		<div id="proposal-list" style="height:200px;"></div>
	</div>
