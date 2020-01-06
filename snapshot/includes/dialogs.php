<?php 
	ini_set("display_errors",1);
	$proposalid = $_GET['proposalid'];	
	$evt 			= $_GET['evt'];
	$type 		= ($_GET['downloadformat'] != 'undefined' ?  $_GET['downloadformat'] :  $_GET['type']);	
	$showid 		= '0';
	$iseeker 	= 'No';
	
	if(isset($_GET['showid'])){
		$showid 	= $_GET['showid'];
	}
		
	if(isset($_GET['iseeker'])){
		$iseeker = $_GET['iseeker'];
	}

	//if dynamic the load it
	if($type == 1){
		include_once('dialogs/dynamic.php');
		return;
	}


switch ($evt) {
    /*case 'avail-dayparts':
			include_once('dialogs/avails.dayparts.php');
			return;break;
    case 'avail-dayparts-30':
			include_once('dialogs/avails.dayparts.30.php');
			return;break;
    case 'avail-dayparts-60':
			include_once('dialogs/avails.dayparts.60.php');
			return;break;*/
    case 'client-manager':
			include_once('dialogs/manager.client.php');
			return;
			break;
    case 'calendar-flight':
			include_once('dialogs/calendar.flight.php');
			return;
			break;
	case 'contact':
			include_once('dialogs/contact.php');
			return;
			break;
	case 'custom-title':
			include_once('dialogs/manager.header.php');
			return;
			break;
    case 'demos':
			include_once('dialogs/demos.php');
			return;
			break;
    case 'demographics':
			include_once('dialogs/demographics.php');
			return;
			break;			
    case 'duplicate-line':
			include_once('dialogs/duplicate.line.php');
			return;
			break;
    case 'duplicate-line-wait':
			include_once('dialogs/duplicate.line.wait.php');
			return;
			break;			
    case 'eclipse':
			include_once('dialogs/eclipse.php');
			return;
			break;
    case 'edit-line':
			include_once('dialogs/edit.proposal.line.php');
			return;
			break;
    case 'edit-line-rate':
			include_once('dialogs/rate.php');
			return;
			break;
    case 'edit-line-spots':
		include_once('dialogs/spots.php');
			return;
			break;
    case 'edit-line-title':
			include_once('dialogs/titles.php');
			return;
			break;
    case 'messages':
			include_once('dialogs/messages.php');
			return;
			break;
	case 'moreinfo':
			include_once 'dialogs/more.info.php';
			return;
			break;
    case 'movies':
			include_once('dialogs/moviesbydecade.php');
			return;
			break;
    case 'newsletters':
			include_once('dialogs/newsletters.php');
			return;
			break;
	case 'ppt-images':
			include_once('dialogs/ppt.php');
			return;
			break;	
    case 'proposal-clone':
			include_once('dialogs/proposal.clone.php');
			return;
        	break;
    case 'proposal-download':
			include_once('dialogs/download.proposal.php');
			return;
			break;
    case 'proposal-merge':
			include_once('dialogs/proposal.merge.php');
			return;
      	break;
    case 'proposal-rename':
			include_once('dialogs/proposal.rename.php');
			return;
      	break;
    case 'proposal-save':
			include_once('dialogs/save.proposal.php');
			return;
			break;
	case 'resetPassword':
			include_once('dialogs/password.php');
			return;
			break;
    case 'save-search':
			include_once('dialogs/save.search.php');
			return;
			break;
    case 'share':
			include_once('dialogs/share.php');
			return;
			break;
    case 'titles':
			include_once('dialogs/archived.titles.php');
			return;
			break;
    case 'xmlerrors':
			include_once('dialogs/xml.errors.php');
			return;
			break;
    default:
			return;
}

?>









