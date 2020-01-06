<?php
ini_set("display_startup_errors","on");
ini_set("display_errors","on");
error_reporting(E_ALL);


	//GETTING PROPOSAL DATA
	
	require_once('sd.php');

	//PHP - PDF CLASSES
	require_once('pdf/tcpdf/tcpdf_config.php');
	require_once('pdf/tcpdf/tcpdf.php');
	

	//HEADER AND FOOTER CLASS
	require_once('pdf/includes/calendar.header.footer.php');	

	
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
	$pdf = new MYPDF('L', PDF_UNIT, 'Letter', true, 'UTF-8', false);
	$pdf->SetMargins(2, 5, 2);
	
	$pdf->SetHeaderMargin(3);

	//page header
	//$html = "<table><tr><td style=\"width:60%\">&nbsp;</td><td align=\"center\"><b>{$resJson->corporation[0]->name}</b><br/>{$resJson->user[0]->firstname} {$resJson->user[0]->lastname}<br/>{$resJson->user[0]->officeaddress}<br/>{$resJson->user[0]->officecity}, {$resJson->user[0]->officestate} {$resJson->user[0]->officezipcode}<br/>{$resJson->user[0]->phone}</td></tr></table>";

	//$headerFlags = array('showratecard'=>$showratecard,'hiderates'=>$hiderate);
	
	//$pdf->SetHeaderData($resJson->corporation[0]->logo, $headerFlags, $resJson->proposalinfo->name, $html);
	
	$pdf->AddPage();
	$greyBottomBorder = array('B' => array('width' => 0.5, 'cap' => 'square', 'join' => 'miter', 'dash' => 0, 'color' => array(192, 192, 192)));

	$pageLengh	= 255;
	$start		= 46;
	$y = $start+7;

		foreach($resJson->proposal AS $zone){
			$pdf->writeHTMLCell(0, 6, '', 5, '', $greyBottomBorder, 0, 0, true, 'R', false);
			


		// LOOPING OVER PROPOSAL LINES
		foreach($zone->lines AS $line){
			
		//PRINTS PRORPOSAL LINES
		require_once('pdf/includes/pdf.proposal.lines.php');	

		}




}





	$pdf->writeHTML($page1, false, false, true, false);
	
	$pdf->deletePage(2);



	$fileName = str_replace(' ','',$resJson->proposalinfo->name)."-".date('m-d-y').".pdf";	
	
	$pdf->Output('/var/www/html/showseeker/files/tmp/'.$fileName, 'I');
	
exit;	
	
?>