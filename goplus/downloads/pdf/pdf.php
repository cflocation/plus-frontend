<?php
	ini_set("display_startup_errors","on");
	ini_set("display_errors","on");
	error_reporting(E_ALL);


	//GETTING PROPOSAL DATA
	require_once 'service_data.php';

	
	//------------------------------------------ COMMON AREA BOC -------------------------------------------------------------------------------
	require_once('pdf/tcpdf/tcpdf_config.php');
	require_once('pdf/tcpdf/tcpdf.php');
	
	
	
	class MYPDF extends TCPDF {
	
		//Page header
		public function Header() {
		
			$this->Image($this->header_logo,'','',0,15,'','','',false,300,'right',false, false, 0);
			
			$this->SetFont('helvetica', '', 8);
			
			$this->writeHTMLCell(0, 0, '', '', $this->header_string , 0, 0, 0, true, 'R', false);
			
			$this->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => '#000000'));
			$this->SetY(23);
			$this->Cell(0, 0, '', 'T', 0, 'C');
			
			$this->SetFont('helvetica', 'BI', 10);
			$this->writeHTMLCell(0, 0, '', 25, $this->header_title , 0, 0, 0, true, 'C', false);
			
			$this->SetFont('helvetica', 'B', 8);
			$this->SetFillColor(0, 0, 0);
			$this->SetTextColor(255, 255, 255);
			
			$headerFlags = $this->header_logo_width;
			
			$rw 	= ($headerFlags['hiderates']=='true')?1:0;
			$rcw 	= ($headerFlags['showratecard']=='true')?0:1;
			$x 	= $this->original_lMargin;
			
			$y 	= 32; 
			//$w 	= (2*($rw+$rcw))+20;
			$w 	= 15;			
			$h		= 10;
			
			$this->MultiCell($w, $h, 'Network', 1, 'C',1,0,$x,$y,true,0,false,true,$h,'M');
			
			$x += $w; 
			$w	=	(3*$rw)+$rcw+31;
			$this->MultiCell($w, $h, 'DayPart', 1, 'C',1,0,$x,$y,true,0,false,true,$h,'M');
			
			$x += $w; 
			$w	=	(4*$rw)+(2*$rcw)+50;
			$this->MultiCell($w, $h, 'Show - Description', 1, 'L',1,0,$x,$y,true,0,false,true,$h,'M');
			
			$x += $w; 
			$w	=	(4*$rw)+$rcw+13;
			$this->MultiCell($w, $h, 'Start', 1, 'C',1,0,$x,$y,true,0,false,true,$h,'M');
			
			$x += $w; 
			$w	=	(4*$rw)+$rcw+13;
			$this->MultiCell($w, $h, 'End', 1, 'C',1,0,$x,$y,true,0,false,true,$h,'M');
			
			$x += $w; 
			$w	=	(4*$rw)+$rcw+7;
			$this->MultiCell($w, $h,  'Wks', 1, 'C',1,0,$x,$y,true,0,false,true,$h,'M');
			
			$x += $w; 
			$w	=	(4*$rw)+$rcw+10;
			$this->MultiCell($w, $h, 'Spots    Wk', 1, 'C',1,0,$x,$y,true,0,false,true,$h,'M');

			if($headerFlags['showratecard']=='true'){
				$x += $w; 
				$w	=	(4*$rw)+12;	
				$this->MultiCell($w, $h, 'RC', 1, 'C',1,0,$x,$y,true,0,false,true,$h,'M');
			}
			
			if($headerFlags['hiderates']=='false'){
				$x += $w; 
				$w	=	$rw+$rcw+12;	
				
				$this->MultiCell($w, $h, 'Rate', 1, 'C',1,0,$x,$y,true,0,false,true,$h,'M');
			}
			
			$x += $w; 
			$w	=(5*$rw)+$rcw+10;	
			$this->MultiCell($w, $h, 'Total   Spots', 1, 'C',1,0,$x,$y,true,0,false,true,$h,'M');
			
			if($headerFlags['hiderates']=='false'){
				$x += $w; 
				$w	=	$rcw+18;		
				$this->MultiCell($w, $h, 'Total', 1, 'C',1,0,$x,$y,true,0,false,true,$h,'M');
			}

		}
		

		// Page footer
		public function Footer() {
			$this->SetY(-15);
			$this->SetFont('helvetica', 'B', 10);
			$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
		}
		
		
		

	}
	//------------------------------------------ COMMON AREA EOC -------------------------------------------------------------------------------
	

	
	
	
	// decode proposal data
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

	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'Letter', true, 'UTF-8', false);
	
	$pdf->SetMargins(PDF_MARGIN_LEFT, 50, PDF_MARGIN_RIGHT);
	
	$pdf->SetHeaderMargin(5);

	$html = "<table><tr><td style=\"width:60%\">&nbsp;</td><td align=\"center\"><b>{$resJson->corporation[0]->name}</b><br/>{$resJson->user[0]->firstname} {$resJson->user[0]->lastname}<br/>{$resJson->user[0]->officeaddress}<br/>{$resJson->user[0]->officecity}, {$resJson->user[0]->officestate} {$resJson->user[0]->officezipcode}<br/>{$resJson->user[0]->phone}</td></tr></table>";

	$headerFlags = array('showratecard'=>$showratecard,'hiderates'=>$hiderate);
	
	$pdf->SetHeaderData($resJson->corporation[0]->logo, $headerFlags, $resJson->proposalinfo->name, $html);
	
	//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	$pdf->AddPage();
	$greyBottomBorder = array('B' => array('width' => 0.5, 'cap' => 'square', 'join' => 'miter', 'dash' => 0, 'color' => array(192, 192, 192)));


	//----------------------------------------------------------------------------------------------
	
	$pageLengh	= 255;
	$start		= 46;
	
	
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

		foreach($zone->lines AS $line){
			
			$h = 15;
			$y = $start+7;
			
			if(($y+$h)>=$pageLengh){
			
				$y = $start = 46;
				$pdf->AddPage();
			}
			
			$rw 	= ($headerFlags['hiderates']=='true')?1:0;
			$rcw 	= ($headerFlags['showratecard']=='true')?0:1;
			$x 	= PDF_MARGIN_LEFT;
			$w 	= $h;
			
			$dayPart  = (is_array($line->dayFormat))?implode('-',$line->dayFormat):$line->dayFormat;
			$dayPart .= " ".$lineStartTime = str_replace(':00','',date('g:i',strtotime($line->starttime)).substr(date('A',strtotime($line->starttime)),0,1));
			$dayPart .= "-".$lineEndTime =  str_replace(array(':00'),array(''),date('g:i',strtotime($line->endtime)).substr(date('A',strtotime($line->endtime)),0,1));

			$totalSpots += $line->spots;
			$totalAmt 	+= $line->total;

			$pdf->SetFont('helvetica', '', 8);
			
			
			//STATION LOGO OR CALLSIGN
			if($includelogos == 'true')
				$pdf->Image($resJson->networks[$line->stationnum]['100x100'],PDF_MARGIN_LEFT,$y,$w,$h,'','','C',true,300,'M',false, false, 0);
			else
				$pdf->MultiCell($w+3, $h, $line->callsign, 0, 'C',0,0,PDF_MARGIN_LEFT,$y,true,0,false,true,$h,'M');
			
			$x += $w; 
			
			$w	=	(3*$rw)+$rcw+31;		
			
			
			

			//LINE DAYPART
			if($line->premiere != "" || $line->isnew != "")
				$pdf->MultiCell($w, $h/2, $dayPart, 0, 'C',0,0,$x,$y,true,0,false,true,$h/2,'B');
			else
				$pdf->MultiCell($w, $h, $dayPart, 0, 'C',0,0,$x,$y,true,0,false,true,$h,'M');
						
			if($line->premiere != "" && $line->showtype == "MV"){ //Movie Premiere
				$pdf->SetFont('helvetica', 'I', 8);
				$pdf->SetTextColor(230, 0, 0);
				$pdf->MultiCell($w, $h/2, "Movie Premiere", 0, 'C',0,0,$x,$y+($h/2),true,0,false,true,$h/2,'T');
				$pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('helvetica', '', 8);
			}
			else if($line->premiere != ""){				// PREMIERES AND FINALES
				$pdf->SetFont('helvetica', 'I', 8);
				$pdf->SetTextColor(230, 0, 0);
				$pdf->MultiCell($w, $h/2, $line->premiere, 0, 'C',0,0,$x,$y+($h/2),true,0,false,true,$h/2,'T');
				$pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('helvetica', '', 8);
			}
			else if($line->isnew != "" && $includenew == 'true'){// NEW IDENTIFIER
				$pdf->SetFont('helvetica', 'IB', 8);
				$pdf->SetTextColor(0, 100, 0);
				$pdf->MultiCell($w, $h/2, "New", 0, 'C',0,0,$x,$y+($h/2),true,0,false,true,$h/2,'T');
				$pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('helvetica', '', 8);
			}
			
			$pdf->SetFont('helvetica', '', 8);
			
			$formattedTitle  = "<b>".rTrim($line->title,', ')."</b> <span style=\"font-style: italic;color:#3366FF\">{$line->epititle}</span>";
			$formattedTitle  = ($includedesc == 'true' && $line->linetype =='Fixed'  && array_key_exists('desc60', $line) )?$formattedTitle.' '.$line->desc60:$formattedTitle;
			$formattedTitle  = ($line->live != "")?$formattedTitle." <span style=\"color:#571B7E;font-weight:bold;font-style: italic;\">Live</span>":$formattedTitle;

			if(array_key_exists('desc60', $line))
				$titlelen = strlen ($line->title.$line->epititle.$line->live.$line->desc60);
			else
				$titlelen = strlen ($line->title.$line->epititle.$line->live);

			$txtlines =  ceil($titlelen / 35) + 1;
			$y_pos = $y + ($h / $txtlines) - 2;
			
			$x += $w; 
			$w	=	(4*$rw)+(2*$rcw)+50;	
			

			$pdf->MultiCell($w, $h, $formattedTitle, 0, 'L',0,0,$x,$y_pos,true,0,true,true,$h,'M');
			//$pdf->writeHTMLCell($w, $h, $x,$y, $formattedTitle, 0,0,false,true,'L',true);			
			
			$pdf->SetFont('helvetica', '', 8);
			
			$x += $w; 
			$w	=	(4*$rw)+$rcw+13;		
			$pdf->MultiCell($w, $h, date('m/d/y',strtotime($line->startdate))	, 0, 'C',0,0,$x,$y,true,0,false,true,$h,'M');
			
			$x += $w; 
			$w	=	(4*$rw)+$rcw+13;		
			$pdf->MultiCell($w, $h, date('m/d/y',strtotime($line->enddate)), 0, 'C',0,0,$x,$y,true,0,false,true,$h,'M');
			
			$x += $w; 
			$w	=	(4*$rw)+$rcw+7;		
			$pdf->MultiCell($w, $h,  $line->weeks, 0, 'C',0,0,$x,$y,true,0,false,true,$h,'M');
			
			$x	+= $w; 
			$w	=	(4*$rw)+$rcw+10;	
			$pdf->MultiCell($w, $h, $line->spotsweek, 0, 'C',0,0,$x,$y,true,0,false,true,$h,'M');
			
			
			if($headerFlags['showratecard'] == 'true'){
				$pdf->SetFont('helvetica', 'IB', 8);
				$pdf->SetTextColor(0, 100, 0);
				$x += $w; 
				$w	=	(4*$rw)+12;	$pdf->MultiCell($w, $h, "$".number_format(floatval($line->ratevalue),2), 0, 'C',0,0,$x,$y,true,0,false,true,$h,'M');
				$pdf->SetFont('helvetica', '', 8);
				$pdf->SetTextColor(0, 0, 0);
			}
			
			if($headerFlags['hiderates'] == 'false'){
				$x += $w; 
				$w	=	$rw+$rcw+12;	
				$pdf->MultiCell($w, $h, "$".number_format(floatval($line->rate),2), 0, 'C',0,0,$x,$y,true,0,false,true,$h,'M');
			}
			
			$x += $w; 
			$w	=	(5*$rw)+$rcw+10;		
			$pdf->MultiCell($w, $h, $line->spots, 0, 'C',0,0,$x,$y,true,0,false,true,$h,'M');

			if($headerFlags['hiderates'] == 'false'){
				$x += $w; 
				$w	=	$rcw+18;		
				$pdf->MultiCell($w, $h, "$".number_format($line->total,2), 0, 'C',0,1,$x,$y,true,0,false,true,$h,'M');
			}
			
			$y = $y+$h;
			
			if(($y)>=$pageLengh)	{
				$y = $start = 46;
				$pdf->AddPage();
			}

			$pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(192, 192, 192)));
			$pdf->SetY($y);
			$pdf->Cell(0, 0, '', 'T', 0, 'C');
			
			$start = $start+$h+2; 			

		}


		// --------------------- Zone Total --------- BOC ----------
		$pdf->SetFont('helvetica', 'B', 8);
		$pdf->MultiCell(10, 10, "Total", 0, 'C',0,0,PDF_MARGIN_LEFT+147,$y,true,0,false,true,10,'M');
		$pdf->SetFont('helvetica', '', 8);
		$x = ($headerFlags['hiderates']=='false')?PDF_MARGIN_LEFT+157:PDF_MARGIN_LEFT+177;
		$pdf->MultiCell(10, 10, $totalSpots, 0, 'C',0,0,$x,$y,true,0,false,true,10,'M');


		if($headerFlags['hiderates'] == 'false'){
			$pdf->MultiCell(20, 10, number_format($totalAmt,2), 0, 'C',0,1,PDF_MARGIN_LEFT+167,$y,true,0,false,true,10,'M');
		}
		
		$y = $y+8;
		if($y >= $pageLengh){
			$y = $start = 46;
			$pdf->AddPage();
		}
		
		$pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
		$pdf->SetY($y);
		$pdf->SetX(PDF_MARGIN_LEFT+157);
		$pdf->Cell(30, 0, '', 'T', 0, 'R');
		
		$totalSpots = 0;
		$totalAmt   = 0;
		// --------------------- Zone Total --------- EOC ----------
		$start += 25;
	}
	
	//---------------------------------------------------------------------------------------------------
	$y = $y+8;
	if(($y)>=$pageLengh){
		$y = $start = 46;
		$pdf->AddPage();
	}

	$pdf->SetFont('helvetica', 'B', 9);
	$pdf->SetFillColor(0, 0, 0);
	$pdf->SetTextColor(255, 255, 255);
	$pdf->MultiCell(45, 7, "Breakdown by Month", 1, 'L',1,0,PDF_MARGIN_LEFT+82 ,$y,true,0,false,true,7,'M');
	$pdf->MultiCell(20, 7, "Month", 1, 'C',1,0,PDF_MARGIN_LEFT+127,$y,true,0,false,true,7,'M');
	$pdf->MultiCell(20, 7, "Spots", 1, 'C',1,0,PDF_MARGIN_LEFT+147,$y,true,0,false,true,7,'M');
	$pdf->MultiCell(20, 7, "Cost", 1, 'C',1,0,PDF_MARGIN_LEFT+167,$y,true,0,false,true,7,'M');


	//---------------------------------------------------------------------------------------------------
	$y = $y+8;

	if(($y)>=$pageLengh){
		$y = $start = 46;
		$pdf->AddPage();
	}

	//$border = array('B' => array('width' => 0.5, 'cap' => 'square', 'join' => 'miter', 'dash' => 0, 'color' => array(192, 192, 192)));

	foreach($resJson->brodmonthstotal as $yr=>$yrarr){

		foreach($yrarr as $month){
			$pdf->SetFont('helvetica', '', 9);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->MultiCell(20, 6, $y, $greyBottomBorder, 'C',0,0,PDF_MARGIN_LEFT+127,$y,true,0,false,true,6,'M');
			$pdf->MultiCell(20, 6, $month->spotsmonth, $greyBottomBorder, 'C',0,0,PDF_MARGIN_LEFT+147,$y,true,0,false,true,6,'M');
			$pdf->MultiCell(20, 6, "$".number_format($month->monthtotal,2)	, $greyBottomBorder, 'C',0,0,PDF_MARGIN_LEFT+167,$y,true,0,false,true,6,'M');
		
			$y = $y+6;
			if(($y)>=$pageLengh){
				$y = $start = 46;
				$pdf->AddPage();
			} 
		}
	}

	//---------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------
	$pdf->SetFont('helvetica', 'B', 9);
	$pdf->SetTextColor(0, 0, 0);
	$border = array('B' => array('width' => 0.5, 'cap' => 'square', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));

	//------ 
	
	$y = $y+8;

	if(($y)>=$pageLengh){
		$y = $start = 46;
		$pdf->AddPage();
	}
	
	
	$pdf->MultiCell(45, 7, "Gross", 0, 'R',0,0,PDF_MARGIN_LEFT+92 ,$y,true,0,false,true,7,'M');

	//$gross = ($headerFlags['hiderates'])?0:$resJson->totals->gross;
	$gross = $resJson->totals->gross;


	$pdf->MultiCell(50, 7, "$".number_format($gross,2)	, $border, 'R',0,0,PDF_MARGIN_LEFT+137,$y,true,0,false,true,7,'M');

	//------ 
	
	if($resJson->totals->packagediscount>0){
		$y = $y+8;
		if(($y)>=$pageLengh)	{
			$y = $start = 46;
			$pdf->AddPage();
		}
		
		$pdf->MultiCell(45, 7, "Pkg Discount", 0, 'R',0,0,PDF_MARGIN_LEFT+92 ,$y,true,0,false,true,7,'M');

		//$packagediscount = ($headerFlags['hiderates'])?0:$resJson->totals->packagediscount;
		$packagediscount =  $resJson->totals->packagediscount;
		$pdf->MultiCell(50, 7, "$".number_format($packagediscount,2)	, $border, 'R',0,0,PDF_MARGIN_LEFT+137,$y,true,0,false,true,7,'M');

	}
	
	if($resJson->totals->agencydiscount>0){
		$y = $y+8;

		if(($y)>=$pageLengh){
			$y = $start = 46;
			$pdf->AddPage();
		}
		
		$pdf->MultiCell(45, 7, "Agcy Discount", 0, 'R',0,0,PDF_MARGIN_LEFT+92 ,$y,true,0,false,true,7,'M');
		//$agencydiscount = ($headerFlags['hiderates'])?0:$resJson->totals->agencydiscount;
		$agencydiscount =  $resJson->totals->agencydiscount;		
		$pdf->MultiCell(50, 7, "$".number_format($agencydiscount,2)	, $border, 'R',0,0,PDF_MARGIN_LEFT+137,$y,true,0,false,true,7,'M');
	}
	
	$y = $y+8;
	if(($y)>=$pageLengh){
		$y = $start = 46;
		$pdf->AddPage();
	}

	$pdf->MultiCell(45, 7, "Net Total", 0, 'R',0,0,PDF_MARGIN_LEFT+92 ,$y,true,0,false,true,7,'M');
	//$net = ($headerFlags['hiderates'])?0:$resJson->totals->net;
	$net =  $resJson->totals->net;	
	$pdf->MultiCell(50, 7, "$".number_format($net,2)	, $border, 'R',0,0,PDF_MARGIN_LEFT+137,$y,true,0,false,true,7,'M');


	$y = $y+8;
	if(($y)>=$pageLengh){
		$y = $start = 46;
		$pdf->AddPage();
	}

	$pdf->MultiCell(17, 7, "Signature", 0, 'R',0,0,PDF_MARGIN_LEFT ,$y,true,0,false,true,7,'L');
	$pdf->MultiCell(80, 7, "", $border, 'R',0,0,PDF_MARGIN_LEFT+17 ,$y,true,0,false,true,7,'L');
	$pdf->MultiCell(45, 7, "Date"	, 0, 'R',0,0,PDF_MARGIN_LEFT+92 ,$y,true,0,false,true,7,'M');
	$pdf->MultiCell(50, 7, date('m-d-Y'), $border, 'R',0,0,PDF_MARGIN_LEFT+137,$y,true,0,false,true,7,'M');


	if($includetc == 'true' && $corporation_id == 25){
		$pdf->SetPrintHeader(false);
		$pdf->SetMargins(10, 10, PDF_MARGIN_RIGHT);	
		$pdf->SetHeaderMargin(0);		
		$pdf->AddPage();		
		include_once('pdf/terms/midhudson.php');
		$pdf->writeHTML($html, true, false, true, false, '');			

	}
	elseif($includetc == 'true' && $corporation_id == 4){	
		$pdf->SetPrintHeader(false);
		$pdf->SetMargins(6, 6, 6, true);		
		$pdf->SetHeaderMargin(0);
		$pdf->SetFooterMargin(0);		
		$pdf->AddPage();
		include_once('pdf/terms/suddenlink.php');
		$pdf->writeHTML($html, true, false, true, false, '');			
	}
	
	
	
	$fileName = str_replace(' ','',$resJson->proposalinfo->name)."-".date('m-d-y').".pdf";	
	
	$pdf->Output('/var/www/html/showseeker/files/tmp/'.$fileName, 'F');
	
	
echo '{"filename":"'.$fileName.'"}';
exit;	
	
?>