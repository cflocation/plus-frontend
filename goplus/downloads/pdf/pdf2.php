<?php
	ini_set("display_startup_errors","on");
	ini_set("display_errors","on");
	error_reporting(E_ALL);


	//GETTING PROPOSAL DATA
	require_once 'service_data.php';

	
	//PHP - PDF CLASSES
	require_once('pdf/tcpdf/tcpdf_config.php');
	require_once('pdf/tcpdf/tcpdf.php');
	

	//HEADER AND FOOTER CLASS
	require_once('pdf/includes/header.footer.php');	
	

	

	
	
	
	// DECODE PROPOSAL DATA
	$resJson = json_decode($json_data);


	//CORPORATION ID
	$corporation_id = $resJson->corporation[0]->id;
	
	
	if(is_array($resJson)){
		echo 'Error while collecting proposal Data';
		exit;
	}


		
	foreach($resJson->networks as $key=>$value)
		$resJson->networks[$value->stationnum] = (array)$value;
	

	// PAGE SET UP

	
	// page size and margins	
	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'Letter', true, 'UTF-8', false);
	
	$pdf->SetMargins(PDF_MARGIN_LEFT, 50, PDF_MARGIN_RIGHT);
	
	$pdf->SetHeaderMargin(5);

	//page header
	$html = "<table><tr><td style=\"width:60%\">&nbsp;</td><td align=\"center\"><b>{$resJson->corporation[0]->name}</b><br/>{$resJson->user[0]->firstname} {$resJson->user[0]->lastname}<br/>{$resJson->user[0]->officeaddress}<br/>{$resJson->user[0]->officecity}, {$resJson->user[0]->officestate} {$resJson->user[0]->officezipcode}<br/>{$resJson->user[0]->phone}</td></tr></table>";


	$headerFlags = array('showratecard'=>$showratecard,'hiderates'=>$hiderate);
	
	$pdf->SetHeaderData($resJson->corporation[0]->logo, $headerFlags, $resJson->proposalinfo->name, $html);
	

	$pdf->AddPage();
	$greyBottomBorder = array('B' => array('width' => 0.5, 'cap' => 'square', 'join' => 'miter', 'dash' => 0, 'color' => array(192, 192, 192)));


	
	
	$pageLengh	= 255;
	$start		= 46;
	
	
	//LOOPING OVER PROPOSAL ZONES
	
	foreach($resJson->proposal AS $zone){

		$y = $start;
		
		if($y>=$pageLengh){
			$y = $start = 46;
			$pdf->AddPage();
		}
		
		
		$pdf->SetFont('helvetica', 'BI', 9);
		
		$pdf->writeHTMLCell(0, 6, '', $y, 'Zone: '.$zone->zone->zonename , $greyBottomBorder, 0, 0, true, 'R', false);
		
		$totalSpots = 0;
		$totalAmt   = 0;

	
		// LOOPING OVER PROPOSAL LINES
		foreach($zone->lines AS $line){
			
				//PRINTS PRORPOSAL LINES
				require_once('pdf/includes/proposal.lines.php');	

		}

				//PRINTS ZONE TOTALS (SPOTS AND DOLLARS)
				require_once('pdf/includes/zone.totals.php');					
	}
	
	
	
	
	//-------  BREAKDOWN TOTALS BY BROADCAST MONTH -----------------------------------------------------------------------
	require_once('pdf/includes/monthly.totals.php');	




	// 
	$pdf->SetFont('helvetica', 'B', 9);
	$pdf->SetTextColor(0, 0, 0);
	$border = array('B' => array('width' => 0.5, 'cap' => 'square', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));

	//------ 
	
	$y = $y+8;

	if(($y)>=$pageLengh){
		$y = $start = 46;
		$pdf->AddPage();
	}
	
	
	// GRAND TOTALS AND SIGNATURE
		require_once('pdf/includes/grand.totals.php');	
	
	


	//	TERMS AND CONDITONS
		require_once('pdf/includes/terms.php');		
	
	
	$fileName = str_replace(' ','',$resJson->proposalinfo->name)."-".date('m-d-y').".pdf";	
	
	$pdf->Output('/var/www/html/showseeker/files/tmp/'.$fileName, 'I');
	
exit;	
	
?>