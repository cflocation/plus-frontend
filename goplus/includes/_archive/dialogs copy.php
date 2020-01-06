<?php 
	ini_set("display_errors",1);
	$proposalid = $_GET['proposalid'];	
	$evt 		= $_GET['evt'];
	$type 		= ($_GET['downloadformat'] != 'undefined' ?  $_GET['downloadformat'] :  $_GET['type']);	
	$showid 	= '0';
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
    case 'add-demo':
			include_once('dialogs/add.demo.php');
			return;
			break;
    case 'api':
			include_once('dialogs/api.php');
			return;
			break;
    case 'avgBooks':
			include_once('dialogs/avg.books.php');
			return;
			break;			
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
    case 'discardRatings':
			include_once('dialogs/discard.ezratings.php');
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
    case 'edit-spotLength':
			include_once('dialogs/edit.spot.length.php');
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
    case 'linebyday':
			include_once('dialogs/spots.by.day.inline.php');
			return;
			break;
    case 'mediamathjson':
			include_once('dialogs/mediamathjson.php');
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
	case 'password-reset':
			include_once('dialogs/disclaimer.password.php');
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
	case 'ratings-report':
			include_once('dialogs/ratings.reports.php');
			return;
			break;
	case 'regional-packages':
			include_once('dialogs/regional.packages.php');
			return;
			break;
	case 'custom-package-missing-network':
			include_once('dialogs/custom-package-missing-network.php');
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
    case 'save-ratings':
			include_once('dialogs/save.ratings.params.php');
			return;
			break;
    case 'scx-import':
			include_once('dialogs/scx.import.php');
			return;
			break;
    case 'scx-import-report':
			include_once('dialogs/scx.import.report.php');
			return;
			break;
    case 'share':
			include_once('dialogs/share.php');
			return;
			break;
    case 'spotsbyday':
			include_once('dialogs/spots.by.day.php');
			return;
			break;
    case 'titles':
			include_once('dialogs/archived.titles.php');
			return;
			break;
    case 'toggle-columns':
			include_once('dialogs/toggle.columns.php');
			return;
			break;
    case 'xml':
			include_once('dialogs/xml.php');
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









