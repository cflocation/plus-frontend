<div class="sf-menu-bg" style="height:32px;">
<ul class="sf-menu">

	<!-- CLOSE SIDE PANEL -->
	<li style="background-color: #000000;">
		<a onmouseover="$('ul.sf-menu').hideSuperfishUl();" onclick="toggleSidebar(); mixTrack('Sidebar - Close Sidebar');" href="javascript:void(0)"><span id="collapse-settings"><i class="fa fa-arrow-circle-left fa-lg"></i></span></a>
	</li>	
	
	<!-- PROPOSALS MANAGER -->
	<li id="menu_proposal_manager" style="background-color: #184a74;">
		<a onmouseover="$('ul.sf-menu').hideSuperfishUl();" onclick="panelManager('close');menuSelect('proposal-manager');sidebarOpen();swapSettingsPanel('search',false);" href="javascript:void(0)">Proposal Manager</a>
	</li>	
	
	<!-- BUILD PROPOSAL -->
	<li id="menu_build_proposal" style="background-color: #184a74;">
		<a onmouseover="$('ul.sf-menu').hideSuperfishUl();" onclick="panelManager('close');menuSelect('proposal-build');sidebarOpen();" id="menu-proposal-window" href="javascript:void(0)">Build Proposal</a>
	</li>	
	
	<!-- DOWNLOAD -->
	<li id="menu_downloads" style="background-color: #184a74;" onmouseover="$('ul.sf-menu').hideSuperfishUl();">
		<a onclick="sidebarClose();menuSelect('proposal-download');setupDownloadPage();panelManager('close');swapSettingsPanel('search',false);checkSpots();" href="javascript:void(0)">Download</a>
	</li>	

	<!-- PROJECTED CALENDARS -->
	<li style="background-color: #184a74;">
		<a href="javascript:void(0)">Calendars</a>
		<ul>
			<li class="package-menu"><a onclick="openEZCalendar('projected');toggleProposalHeader = false;toggleProposalGrid();  mixTrack('Calendar - Projected Button');" href="javascript:void(0)">Projected</a></li>
			<li class="package-menu"><a onclick="openEZCalendar('packages');toggleProposalHeader = false;toggleProposalGrid();  mixTrack('Calendar - Packages Button');" href="javascript:void(0)">Packages</a></li>
			<li class="package-menu"><a onclick="openEZCalendar('live');toggleProposalHeader = false;toggleProposalGrid();  mixTrack('Calendar - Live Sports Button');" href="javascript:void(0)">Live Sports</a></li>
			<li class="package-menu"><a onclick="openEZCalendar('premieres');toggleProposalHeader = false;toggleProposalGrid();  mixTrack('Calendar - Premieres');" href="javascript:void(0)">Premieres</a></li>
			<li class="package-menu"><a onclick="openEZCalendar('all');toggleProposalHeader = false;toggleProposalGrid();  mixTrack('Calendar - Show All Button'); " href="javascript:void(0)">Show All</a></li>
		</ul>
	</li>	

	<!-- SPORT PACKAGES -->
	<li style="background-color: #184a74;">
		<a href="javascript:void(0)">Packages</a>		
		<ul style="margin-left:-100px!important;" id="package-menu">
			<li style="background-color: maroon;display:none;" id="pkgs-custom-pkg-list"><a href="javascript:dialogRegionalPackages();" style="color:white; border: solid 1px #a1a1a1;">Custom Packages</a></li>
		</ul>
	</li>

	<!-- SAVED SEARCHES -->
	<li id="menu_saved" style="background-color: #184a74;">
		<a onmouseover="$('ul.sf-menu').hideSuperfishUl();" onclick="menuSelect('saved-searches');sidebarOpen();panelManager('close');searchGenres(); mixTrack('Saved Searches');" href="javascript:void(0)"><i class="fa fa-heart"></i></a>
	</li>	

	<!-- HELP -->
	<li style="background-color: #ff9933;">
		<a href="javascript:void(0)"><smaller>Help & Tutorials</smaller></a>
		<ul style="margin-left:-100px;">
			<li class="package-menu">
				<a href="javascript:void(0)">Video Tutorials</a>
				<ul style="margin-left:-350px!important;">
					<li class="package-menu"><a href="javascript:void(0)" onclick="openTutorial('../tutorials/?v=2',true); mixTrack('Tutorials - Full Basic Training'); return false;" >Full Basic Training Modules</a></li>
					<li class="package-menu">
						<a href="javascript:void(0)">Mini Modules</a>
						<ul style="margin-left:-295px!important;">
							<li class="package-menu"><a href="javascript:void(0)" onclick="openTutorial('../tutorials/play.php?id=7',true); mixTrack('Tutorials - Title & Keyword Search'); return false;">Title & Keyword Search</a></li>
							<li class="package-menu"><a href="javascript:void(0)" onclick="openTutorial('../tutorials/play.php?id=6',true); mixTrack('Tutorials - Rotators & Avails'); return false;">Rotators & Avails</a></li>
							<li class="package-menu"><a href="javascript:void(0)" onclick="openTutorial('../tutorials/play.php?id=4',true);  mixTrack('Tutorials - Ez Grids'); return false;">E-z Grids</a></li>
							<li class="package-menu"><a href="javascript:void(0)" onclick="openTutorial('../tutorials/play.php?id=5',true); mixTrack('Tutorials - Projected Calendar'); return false;">Projected Calendar</a></li>
							<li class="package-menu"><a href="javascript:void(0)" onclick="openTutorial('../tutorials/play.php?id=8',true); mixTrack('Tutorials - Help & Tutorials'); return false;">Help & Tutorials</a></li>
							<li class="package-menu"><a href="javascript:void(0)" onclick="openTutorial('../tutorials/play.php?id=9',true); mixTrack('Tutorials - Copy, Rename'); return false;">Copy, Rename, Merge & Share</a></li>
						</ul>
					</li>
					<li class="package-menu"><a href="javascript:void(0)" onclick="openTutorial('../tutorials/training-refresher.php?v=2',true);  mixTrack('Tutorials - Refresher'); return false;" target="_blank">ShowSeeker PLUS Refresher</a></li>
					<li class="package-menu"><a href="javascript:void(0)" onclick="openTutorial('../tutorials/ratecard_module.php?v=2',true);   mixTrack('Tutorials - Rate Card'); return false;" id="ratecard-access-btn" target="_blank" style="display:none;">Rate Card Module</a></li>
				</ul>			
			</li>

			<li class="package-menu">
				<a href="javascript:void(0)">User Guides</a>
				<ul style="margin-left:-350px!important;">
					<li class="package-menu"><a href="javascript:void(0)" onclick="openTutorial('includes/guides/userguides/ShowSeekerUserGuide02032017.pdf'); mixTrack('Tutorials - Full Basic User Guide');">Full Basic User Guide</a></li>
					<li class="package-menu"><a href="javascript:void(0)" onclick="javascript:window.open('https://showseeker.s3.amazonaws.com/tutorials/manuals/ShowSeeker_v1.5_New-Functionality_0418.pdf'); return false;" >New Features <span style="font-size: smaller; color:lime"> 04/06/18</span></a></li>
					<li class="package-menu">
						<a href="javascript:void(0)">Mini-Guide Shortcuts</a>		
						<ul style="margin-left:-295px!important;">
							<li class="package-menu"><a href="javascript:void(0)" onclick="openTutorial('includes/guides/userguides/ShowSeeker_UserGuide_Mini_Avails_0316.pdf'); mixTrack('Tutorials - Mini - Avails'); return false;">Avails</a></li>	
							<li class="package-menu"><a href="javascript:void(0)" onclick="openTutorial('includes/guides/userguides/ShowSeeker_UserGuide_Mini_Downloads_0316.pdf');  mixTrack('Tutorials - Mini - Download Tab'); return false;" >Download Tab</a></li>
							<li class="package-menu"><a href="javascript:void(0)" onclick="openTutorial('includes/guides/userguides/ShowSeeker_UserGuide_Mini_EzGrids_0316.pdf');  mixTrack('Tutorials - Mini - Ez Grids'); return false;" target="_blank">E-z Grids</a></li>
							<li class="package-menu"><a href="javascript:void(0)" onclick="openTutorial('includes/guides/userguides/ShowSeeker_UserGuide_Mini_Projected_Calendar_0316.pdf'); mixTrack('Tutorials - Mini - Projected Calendar'); return false;" target="_blank">Projected Calendar</a></li>
							<li class="package-menu"><a href="javascript:void(0)" onclick="openTutorial('includes/guides/userguides/ShowSeeker_UserGuide_Mini_Rotators_0417.pdf');  mixTrack('Tutorials - Mini - Rotators'); return false;" target="_blank">Rotators</a></li>
							<li class="package-menu"><a href="javascript:void(0)" onclick="openTutorial('includes/guides/userguides/ShowSeeker_UserGuide_Mini_Saved_Searches_0316.pdf'); mixTrack('Tutorials - Mini - Saved Searches'); return false;" target="_blank">Saved Searches</a></li>
							<li class="package-menu"><a href="javascript:void(0)" onclick="openTutorial('includes/guides/userguides/ShowSeeker_UserGuide_Mini_Sharing_Proposals_0316.pdf'); mixTrack('Tutorials - Mini - Share Function'); return false;" target="_blank">Share Function</a></li>
							<li class="package-menu"><a href="javascript:void(0)" onclick="openTutorial('includes/guides/userguides/ShowSeeker_UserGuide_Mini_Standard_Broadcast_Calendars_0316.pdf'); mixTrack('Tutorials - Mini - SC-BC Calendars'); return false;" target="_blank">Standard/Broadcast Calendars</a></li>
						</ul>
					</li>
					<li class="package-menu"><a href="includes/guides/download.php?filename=Ez_Grids_Color_Coding.pdf" onclick="mixTrack('Tutorials - Color Guide');">ShowSeeker Color Guide</a></li>
					<li class="package-menu"><a href="includes/guides/download.php?filename=adsails_import.pdf" style="display:none;" onclick="mixTrack('Tutorials - Adsails Guide');" id="adSailsGuide">Adsails Import Guide</a></li>
					<li class="package-menu" ><a href="includes/guides/download.php?filename=SnapShotInstructions2017.pdf" id="snapshot-manual" style="display:none;" target="_blank" onclick="mixTrack('Tutorials - Snapshot');">SnapShot</a></li>
					<li class="package-menu"><a href="includes/guides/download.php?filename=ShowSeeker_Rate_Card_Training_ModuleRev2.pdf" style="display:none;" id="rate-card-manual" onclick="mixTrack('Tutorials - Rate Card Administration');">Rate Card Administration Guide</a></li>
				</ul>
			</li>
			
			<li class="package-menu"><a href="javascript:openFAQ();" onclick="mixTrack('Help - FAQs');">FAQs</a></li>
			<li class="package-menu"><a href="javascript:dialogNewsletters();" onclick="mixTrack('Help - Newsletters');">Newsletters</a></li>
			<li class="package-menu"><a href="javascript:dialogContact();" onclick="mixTrack('Help - Contact Us');">Contact Us</a></li>
			<li class="package-menu"><a href="javascript:void(0)" onclick="mixTrack('Help - Browser Info');">Browser Info</a>
				<ul><li><a href="javascript:void(0)" style="font-size:8pt;"><?php
				$browser = getBrowser();
				print_r($browser['name']);
				print('<br>');
				if($browser['name'] != 'IE11'){
					print_r($browser['version']);
					print('<br>');
				}
				print_r($browser['platform']);
				print('<br>');				
				print_r($browser['userAgent']);?></a></li></ul>
			</li>
			<li class="package-menu"><a href="javascript:window.location.reload();" onclick="mixTrack('Help - Reload');">Reload (F5)</a></li>
		</ul>
	</li>	

	<!-- CONFIGURATIONS -->
	<li style="background-color: #74068f;">
		<a href="javascript:void(0);"><i class="fa fa-cog"></i></a>
		<ul style="margin-left:-135px;">
			<li class="package-menu">
				<a href="javascript:void(0)">Freeze Column</a>
				<ul style="margin-left:-350px;" id="freezeColsCtrl">
					<li class="package-menu" id="freezeTitle"><a href="javascript:datagridProposal.freezeByColumn(2); mixTrack('Settings - Freeze - Title');">Title</a></li>
					<li class="package-menu" id="freezeSearch"><a href="javascript:datagridProposal.freezeByColumn(3); mixTrack('Settings - Freeze - Search Criteria');">Search Criteria</a></li>
					<li class="package-menu" id="freezeStatus"><a href="javascript:datagridProposal.freezeByColumn(4); mixTrack('Settings - Freeze - Status');">Status</a></li>
					<li class="package-menu" id="freezeDay"><a href="javascript:datagridProposal.freezeByColumn(5); mixTrack('Settings - Freeze - Day');">Day</a></li>
					<li class="package-menu" id="freezeStart"><a href="javascript:datagridProposal.freezeByColumn(6); mixTrack('Settings - Freeze - Start Date');">Start Date</a></li>
					<li class="package-menu" id="freezeEnd"><a href="javascript:datagridProposal.freezeByColumn(7); mixTrack('Settings - Freeze - End Date');">End Date</a></li>
					<li class="package-menu" id="freezeStartTime"><a href="javascript:datagridProposal.freezeByColumn(8); mixTrack('Settings - Freeze - Start Time');">Start Time</a></li>
					<li class="package-menu" id="freezeEndTime"><a href="javascript:datagridProposal.freezeByColumn(9); mixTrack('Settings - Freeze - End Time');">End Time</a></li>
					<li class="package-menu" id="freezeWeeks"><a href="javascript:datagridProposal.freezeByColumn(10); mixTrack('Settings - Freeze - Weeks');">Weeks</a></li>
					<li class="package-menu" id="freezeSpotWeek"><a href="javascript:datagridProposal.freezeByColumn(11); mixTrack('Settings - Freeze - Spots/Week');">Spots/Week</a></li>
					<li class="package-menu" id="freezeSpotLen"><a href="javascript:datagridProposal.freezeByColumn(12); mixTrack('Settings - Freeze - Spot Length');">Spot Length</a></li>
					<li class="package-menu" id="freezeRC"><a href="javascript:datagridProposal.freezeByColumn(13); mixTrack('Settings - Freeze - Card');">Card</a></li>
					<li class="package-menu" id="freezeRate"><a href="javascript:datagridProposal.freezeByColumn(14); mixTrack('Settings - Freeze - Rate');">Rate</a></li>
					<li class="package-menu" id="freezeSpots"><a href="javascript:datagridProposal.freezeByColumn(15); mixTrack('Settings - Freeze - Spots');">Spots</a></li>
					<li class="package-menu" id="freezeCost"><a href="javascript:datagridProposal.freezeByColumn(16); mixTrack('Settings - Freeze - Cost');">Cost</a></li>
				</ul>
			</li>

			<li class="package-menu">
				<a href="javascript:void(0)">Auto Split Lines</a>
				<ul style="margin-left:-350px;">
					<li class="package-menu" id="autoSplitLines_true"><a href="javascript:userSettings.autoSplitLines=true;saveUserSettings(); mixTrack('Settings - Auto Split - On');">On</a></li>
					<li class="package-menu" id="autoSplitLines_false"><a href="javascript:userSettings.autoSplitLines=false;saveUserSettings(); mixTrack('Settings - Auto Split - Off');">Off</a></li>
				</ul>
				<!-- li class="package-menu" id="include_projected">
					<a href="javascript:includeProjected();">Include Projected</a>
					<ul style="margin-left:-350px;">
						<li class="package-menu" id="projected_true"><a href="javascript:userSettings.projected=true;saveUserSettings();">On</a></li>
						<li class="package-menu" id="projected_false"><a href="javascript:userSettings.projected=false;saveUserSettings();">Off</a></li>
					</ul>
				</li -->
			</li>
		</ul>
	</li>

	<!-- MESSAGES -->
	<li style="background-color: #333333;">
		<a href="javascript:void(0)"><i class="fa fa-user"></i><span id="message-count">0</span></a>
		<ul style="margin-left:-135px!important;">
			<li class="package-menu"><a href="/snapshot" style="display:none;" id="snapshotLink" onclick="mixTrack('SnapShot-Redirection')"> SnapShot</a></li>
			<li class="package-menu"><a href="../admin" style="display:none;" id="adminLink" onclick="mixTrack('Admin-Redirection')"> User Admin</a></li>
			<li class="package-menu"><a href="/ezrates" style="display:none;" id="rcm-redirect"  onclick="mixTrack('RCM-Redirection')" target="_blank" > Rate Card Manager</a></li>
			<li class="package-menu"><a href="/packagebuilder" style="display:none;" id="customPackageBuilderLink" target="winCustomPackageTool" onclick="mixTrack('CPB-Redirection')"> Custom Package Builder</a></li>
			<li class="package-menu"><a href="javascript:dialogMessages(); mixTrack('Settings - Messages');"> Messages</a></li>
			<li class="package-menu"><a href="javascript:resetPassword(); mixTrack('Settings - Change Strong Password');"> Change Password</a></li>
			<li class="package-menu"><a href="javascript:logout(); mixTrack('Settings - Logout');"> Logout</a></li>
		</ul>
	</li>	

	</ul>
</div>


<?php
function getBrowser() { 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)){
        $platform = 'windows';
    }
    
    
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)){ 
        $bname = 'Internet Explorer'; 
        $ub = "MSIE"; 
    } 
    elseif(preg_match('/Firefox/i',$u_agent)){ 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    } 
    elseif(preg_match('/Chrome/i',$u_agent)) { 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    } 
    elseif(preg_match('/Safari/i',$u_agent)) { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    } 
    elseif(preg_match('/Opera/i',$u_agent)) { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } 
    elseif(preg_match('/Netscape/i',$u_agent)) { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } 
    elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; rv:11.0') !== false) {
        $bname = 'IE 11'; 
        $ub = "IE 11"; 
	}
    else{
        $bname = $u_agent;
        $ub =  	$u_agent;
	   
    }
    
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    
    $pattern = '#(?<browser>' . join('|', $known) .')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';

    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
    
    // see how many we have
    $i = count($matches['browser']);

    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
	        
			if ( ! isset($matches['version'][1]) ) {
	            $version= 0;
			}
			else	        
	            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }
    
    // check if we have a number
    if ($version==null || $version==""){
	    $version="";
	}
    
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'   => $pattern
    );
} 

function getToken($id,$email){
		
	$ch 		= curl_init();
	$timeout 	= 5;		
	$url 		= 'https://plusapi.showseeker.com/user/passwordreset/passwordtoken';

	$data = array("userId" => "{$id}");  
	$data_string = json_encode($data);

        
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_string)));
	$data = curl_exec($ch);		

	curl_close($ch);

	return $data;		
	
}
?>



