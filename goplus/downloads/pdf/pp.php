<?php
	require_once('sd.php');
	
		$proposalid		= urldecode(trim($_GET['proposalid']));
		$userid			= urldecode(trim($_GET['userid']));
		$tokenid		= urldecode(trim($_GET['tokenid']));
		$sort1			= urldecode(trim($_GET['sort1']));
		$sort2			= urldecode(trim($_GET['sort2']));
		$sort3			= urldecode(trim($_GET['sort3']));				

		$hiderate		= urldecode(trim($_GET['hiderates']));
		$includelogos	= urldecode(trim($_GET['logos']));
		$includedesc	= urldecode(trim($_GET['description']));
		$includenew		= urldecode(trim($_GET['includenew']));
		$includetc		= urldecode(trim($_GET['addterms']));
		$onlyfixed		= urldecode(trim($_GET['onlyfixed']));
		$showratecard	= urldecode(trim($_GET['showratecard']));

	$resJson = json_decode($json_data);

	$month =  date("n");
	$year = date("y");

	//PHP - PDF CLASSES
	require_once('pdf/tcpdf/tcpdf_config.php');
	require_once('pdf/tcpdf/tcpdf.php');
	
	
	// PAGE SIZE AND MARGINS	
	$pdf = new TCPDF('L', PDF_UNIT, 'Letter', true, 'UTF-8', false);
	$pdf->SetMargins(7, 10, 2);
	$pdf->SetHeaderMargin(5);
	
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('ShowSeeker PDF Calendar Builder');
	$pdf->SetTitle('ShowSeeker Proposal');
	$pdf->SetSubject('PDF Calendar Builder');


	//TOC OF PAGE

	$pdf->AddPage();
	$html = "<table><tr><td style=\"width:60%\">&nbsp;</td><td align=\"center\"><b>{$resJson->corporation[0]->name}</b><br/>{$resJson->user[0]->firstname} {$resJson->user[0]->lastname}<br/>{$resJson->user[0]->officeaddress}<br/>{$resJson->user[0]->officecity}, {$resJson->user[0]->officestate} {$resJson->user[0]->officezipcode}<br/>{$resJson->user[0]->phone}</td></tr></table>";
	
	$pdf->writeHTMLCell(0, 0, '0', '20', $html, 0, 1, 0, true, L, true);
	//Company Logo
	$pdf->Image($resJson->corporation[0]->logo,100, 90);
	//ShowSeeker Logo
	$pdf->Image('/var/www/html/www.showseeker.com/images/logo200.png',15, 10);


$months = count ($resJson->calmonthstotal->{2014});
$u = 0;
while ($u < $months) {
	$monthNumber = $resJson->calmonthstotal->{2014}[$u]->monthnumber;

	$callCall = "http://services.showseeker.com/downloads/c.php?m=".$monthNumber."&y=2014&proposalid={$proposalid}&userid={$userid}&tokenid={$tokenid}&sort1={$sort1}&sort2={$sort2}&sort3={$sort3}";
	$page = file_get_contents($callCall);
	$pdf->setPrintHeader(false);
	$pdf->AddPage();
	$pdf->writeHTML($page, false, false, true, false);


$u ++;
}




	//LISTING OF PROPOSAL PAGE

	$callItems = "http://services.showseeker.com/downloads/pe.php?proposalid={$proposalid}&userid={$userid}&tokenid={$tokenid}&sort1={$sort1}&sort2={$sort2}&sort3={$sort3}";
	$page4 = file_get_contents($callItems);
	$pdf->setPrintHeader(false);
	$pdf->AddPage();
	$pdf->writeHTML($page4, false, false, true, false);


	$fileName = str_replace(' ','','filename')."-".date('m-d-y').".pdf";	
	$pdf->Output('/var/www/html/showseeker/files/tmp/'.$fileName, 'I');
	
exit;	
	
?>

