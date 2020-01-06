<?php
	if (isset($_GET['o'])) 	{
		$o = $_GET['o'] ; 	
		}

	//PHP - PDF CLASSES
	require_once('tcpdf/tcpdf_config.php');
	require_once('tcpdf/tcpdf.php');

	// PAGE SIZE AND MARGINS	
	$pdf = new TCPDF('L', PDF_UNIT, 'Letter', true, 'UTF-8', false);
	$pdf->SetMargins(5, 5, 5);
	$pdf->SetHeaderMargin(5);
	$pdf->SetFooterMargin(5);
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('ShowSeeker Mobile Logs');
	$pdf->SetTitle('ShowSeeker Mobile Logs');
	$pdf->SetSubject('Mobile Logs');
	$pdf->setPrintFooter(false);

	$callItems = "http://managed.showseeker.com/reporting/mobile/index.php?t=pdf&o=$o";
	$page = file_get_contents($callItems);
	$pdf->AddPage();
	$pdf->writeHTML($page, false, false, true, false);


	$fileName = str_replace(' ','','filename')."-".date('m-d-y').".pdf";	
	$pdf->Output('/var/www/html/showseeker/files/tmp/'.$fileName, 'I');


?>