<div id="newProposalUI" style="display:none;" title="ShowSeeker Plus">
	<p></p>
	<center>
		<div>
			<input id="newProposal" type="text" placeholder="Proposal Name" class="input-half rounded-corners" maxlength="75" style="width: 170px;">
		</div>
		<p></p>
		<div>
			<button id="newProposalBtn" class="btn-green hander"> <i class="fa fa-file"></i> Create&nbsp;</button>
			<button id="cancelNewProposalBtn" class="btn-red hander" onclick="$('#newProposalUI').dialog('close')"> <i class="fa fa-times-circle"></i> Cancel&nbsp;</button>			
		</div>	
		<div id="newPslStatus" style="display: none;"><br/><i class="fa fa-spinner fa-spin fa-fw fa-3x"></i></div>
	</center>
</div>


<div id="gridsMessages" style="display:none;" title="ShowSeeker Plus">
	<div class="ui-widget">
		<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
			<p> 
				<i class="fa fa-exclamation-triangle fa-lg" style="float: left; margin-right: .3em;"></i>
				<strong>Alert: </strong> <span id="gridsMsg"></span>
			</p>
		</div>
        <br>
        <p>
	        <center>
	        	<button class="btn-red" id="btnErrorMsg" onclick="closeDialog()"><i class="fa fa-times-circle"></i> Close</button>
	        </center>
		</p>
	</div>
</div>