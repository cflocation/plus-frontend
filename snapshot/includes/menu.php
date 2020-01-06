<!-- div class="mainHeader" style="height:32px;" -->
<div class="sf-menu-bg" style="height:32px;">
<ul class="sf-menu">

	<!-- CLOSE SIDE PANEL -->
	<li style="background-color: #000000;">
		<a onmouseover="$('ul.sf-menu').hideSuperfishUl();" onclick="toggleSidebar();" href="javascript:void(0)"><span id="collapse-settings"><i class="fa fa-arrow-circle-left fa-lg"></i></span></a>
	</li>	
	
	<!-- PROPOSALS MANAGER -->
	<li id="menu_proposal_manager">
		<a onmouseover="$('ul.sf-menu').hideSuperfishUl();" onclick="panelManager('close');menuSelect('proposal-manager');sidebarOpen();datagridProposalManager.renderGrid();" href="javascript:void(0)">Manager</a>
	</li>	
	
	<!-- BUILD PROPOSAL -->
	<li id="menu_build_proposal">
		<a onmouseover="$('ul.sf-menu').hideSuperfishUl();" onclick="panelManager('close');menuSelect('proposal-build');sidebarOpen();datagridProposal.renderGrid();datagridSearchResults.renderGrid();" id="menu-proposal-window" href="javascript:void(0)">Search Results</a>
	</li>	
	
	<!-- DOWNLOAD -->
	<li id="menu_downloads" onmouseover="$('ul.sf-menu').hideSuperfishUl();">
		<a onclick="sidebarClose();menuSelect('proposal-download');setupDownloadPage();" href="javascript:void(0)">Download</a>
	</li>	



	<!-- HELP -->
	<li style="background-color: #ff9933;">
		<a href="javascript:void(0)"><smaller>Help & Tutorials</smaller></a>
		<ul style="margin-left:-62px!important;">
			<li class="package-menu"><a href="../goplus/services/fdownload.php?filename=http://showseeker.s3.amazonaws.com/tutorials/manuals/SnapShot_User_Guide.pdf" id="snapshot-manual" target="_blank">Instructions for using SnapShot</a></li>
			<li><a href="javascript:dialogContact();">Contact Us</a></li>
		</ul>
	</li>	

	<!-- CONFIGURATIONS -->
	<li><div style="width: 52px;">&nbsp;</div></li>

	<!-- MESSAGES -->
	<li style="background-color: #333333;">
		<a href="javascript:void(0)"><i class="fa fa-user"></i> <i class="fa fa-bars"></i></a>
		<ul style="margin-left:-145px!important;">
			<li class="package-menu"><a href="/plus">ShowSeeker</a></li>
			<li class="package-menu"><a href="javascript:logout();">Logout</a></li>
		</ul>
	</li>	

	</ul>
</div>





