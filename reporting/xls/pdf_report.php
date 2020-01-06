<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);
	$con = mysqli_connect("db4.showseeker.net","vastdb","jK6YK71tJ","Customers");
	if (isset($_GET['rid'])) { 
		$rid = $_GET['rid'] ;
		$report_sql = "select * from charter_ratecards_reports where RID = $rid" ; 
		$reports = mysqli_query($con, $report_sql);
		$report_data = mysqli_fetch_array($reports);
		$codes = $report_data['syscodes'];
		$filename = $report_data['filename'];
		$rows =$report_data['rows'];
		$ms = $report_data['syscode_skip'];
		$ns =$report_data['callsign_skip'];
		$t = $report_data['process_time'];
		$sender = $report_data['sender'];
		$time = $t /60 ;
		$now = date('m-d-Y');
		$total_records = $rows * $codes;
		$ps = $report_data['processed'];
	//PHP - PDF CLASSES
	require_once('Classes/tcpdf/tcpdf_config.php');
	require_once('Classes/tcpdf/tcpdf.php');
	// PAGE SIZE AND MARGINS	
		$pdf = new TCPDF('L', PDF_UNIT, 'Letter', true, 'UTF-8', false);
		$pdf->SetMargins(5, 5, 5);
		$pdf->SetHeaderMargin(5);
		$pdf->SetFooterMargin(5);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('ShowSeeker Ratecard Report');
		$pdf->SetTitle('ShowSeeker Ratecard Report');
		$pdf->SetSubject('Ratecard Report');
		$pdf->setPrintFooter(false);
		$pdf->setPrintHeader(false);
		$pdf->AddPage();
	$report_title = "<table align=\"center\"><tr><td align=\"center\" style=\"font-size:22pt;\"><b>Ratecard Processing Report</b> <br> Date: $now <br/>Filename: $filename</td></tr></table>";
	$report_stats = "<table><tr><td style=\"width:60%\">&nbsp;</td><td align=\"center\" style=\"font-size:16pt;\"><u>Statistics</u><br/></td></tr><tr><td style=\"width:60%\">&nbsp;</td><td align=\"left\" style=\"font-size:13pt;\">Total Syscodes: $codes <br/>Total Rows: $rows <br/>Total Records: $total_records <hr>Processed Syscodes: $ps <br/>Missing Syscodes: $ms <br/>Missing Networks: $ns <br /><hr /> <b style=\"font-size:10pt;\">ShowSeeker has also received this report, and will be following up in regards to any missing syscodes or networks from this specific ratecard file processing.</b></td></tr></table>";


		$pdf->writeHTMLCell(205, 40, '35', '50', $report_title, 0, 1, 0, true, '', true);
		$pdf->writeHTMLCell(165, 40, '0', '100', $report_stats, 0, 1, 0, true, '', true);
	
		$pdf->Image('Classes/chartermedia.jpg', '10', '10', '55', '', '', '', '', '1', '300', '', false, false, '0', false, false, false);
		$pdf->Image('Classes/logo500.jpg', '210', '5', '55', '', '', '', '', '1', '300', '', false, false, '0', false, false, false);
		$pdf_filename = "ShowSeeker_Ratecard_Report_".date('m-d-Y_hia').".pdf";
		$pdf->Output('reports/'.$pdf_filename, 'F');
	
	$pathout = "mailer.php?sender=$sender&report=$pdf_filename";
	header("location:$pathout");
	
	
	}
?>