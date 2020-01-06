<div  id="popupDisclaimer" style="height: 95px; position:absolute; top:0px; right: 0px;" onclick="$(this).hide();">
	<div id="disclaimerMain" style="height: 95px; width: 125px;">
		<div style="position: absolute; top:0; left:0; width: 125px; height: 15px;">
			<span class="hander" style="float: right;"> <i class="fa fa-window-close fa-lg" style="color: red;"></i></span>
		</div>
		<a href="#" onclick="dialogDisclaimerNew(231)" id="">
			<img id='arrowRotate' src="i/dialogs/nfl18.png" style="width:125px; height:95px;">
		</a>
	</div>
</div>

<div style="width:670px;">
	<div class="mpanel rounded-corners" style="float:left;">
		<div class="mheader"><i class="fa fa-file"></i> Create New Proposal</div>
		<div class="mbody">
			<input class="rounded-corners input-half scxImporterOff" name="proposal-name" id="proposal-name" type="text"> 
			<button class="btn-green" onclick="datagridProposal.emptyGrid(); proposalCreateNew($('#proposal-name').val(),'blank','Plus');" id="unsavedLoadIndex"><i class="fa fa-file"></i> Create</button>
			<button class="btn-blue"  onclick="javascript:dialogScxImport(); mixTrack('Proposal - SCX Import');" data-toggle="tooltip" title="SCX File Import" style="display: none;" id="scxImporter"><i class="fa fa-upload fa-lg"></i></button>
		</div>		
	</div>
	<div class="mpanel rounded-corners" style="float:left;">
		<div class="mheader"><i class="fa fa-check"></i> Checked Proposals</div>
		<div class="mbody">
			<div id="proposal-manager-buttons">
				<a class="btn-blue" href="javascript:dialogCloneProposal(); mixTrack('Proposal - Copy');">
					<i class="fa fa-files-o fa-lg"></i> Copy
				</a><a class="btn-blue" href="javascript:proposalRenameChecked(); mixTrack('Proposal - Rename');">
					<i class="fa fa-pencil fa-lg"></i> Rename
				</a><a class="btn-blue" href="javascript:mergeProposal(); mixTrack('Proposal - Merge');">
					<i class="fa fa-clipboard fa-lg"></i> Merge
				</a><a class="btn-blue" href="javascript:proposalShare('Proposal');">
					<i class="fa fa-share-alt fa-lg"></i> Share
				</a><a class="btn-red" href="javascript:proposalDeleteCheckedConfirmation(); mixTrack('Proposal - Delete');">
					<i class="fa fa-trash fa-lg"></i>
				</a>
			</div>
		</div>
	</div>
</div>

<br style="clear:both;"><br style="clear:both;">
<div class="proposalmanagerwrapper rounded-corners">
	<div class="headers proposalmanager">
		<i class="fa fa-th-list"></i> 		
		<span id="label-user-name">
			Proposal List
		</span>
		&nbsp;&nbsp;
		<div  style="float:right; width: 200px; height: 30px; line-height: 30px;" align="right">
			<span id="proposalCount"></span>&nbsp;&nbsp;&nbsp;&nbsp;
			<button title="Search Proposal" onclick="datagridProposalManager.toggleFilterRow(); mixTrack('Proposal - Filter');" class="btn-blue"> <i class="fa fa-filter hander"></i> Filter</button>
		</div>
	</div>

	<div class="gridwrapper">
		<div id="proposal-list" style="height:200px;"></div>
	</div>
</div>

<div id="inlineFilterPanel" style="display:none;background:#32639a;padding:3px;color:#fff;">Show Proposals with Name including: <input type="text" id="txtSearch2"></div>