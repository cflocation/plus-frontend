<?phpclass MYPDF extends TCPDF {		//Page header		public function Header() {					//$this->SetY(5);			$this->SetFont('helvetica', 'B', 8);			$this->writeHTMLCell(0, 0, '', 5, 'Month: June 2014' , 0, 0, 0, true, 'L', false);			$this->writeHTMLCell(0, 0, '', 5, 'Proposal Title' , 0, 0, 0, true, 'C', false);			$this->Cell(0, 5, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');			//$this->Image($this->header_logo,'','',0,15,'','','',false,300,'right',false, false, 0);			//$this->SetFont('helvetica', '', 8);			//$this->writeHTMLCell(0, 0, '', '', $this->header_string , 0, 0, 0, true, 'R', false);			//$this->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => '#000000'));			//$this->SetY(23);			//$this->Cell(0, 0, '', 'T', 0, 'C');			//$this->SetFont('helvetica', 'BI', 10);						//$this->SetFont('helvetica', 'B', 8);			//$this->SetFillColor(0, 0, 0);			//$this->SetTextColor(255, 255, 255);			$headerFlags = $this->header_logo_width;			$rw 	= ($headerFlags['hiderates']=='true')?1:0;			$rcw 	= ($headerFlags['showratecard']=='true')?0:1;			$x 	= $this->original_lMargin;  						$y 	= 30; 			$w 	= 25;				$h  = 5;														}				// Page footer		public function Footer() {			//$this->SetY(-15);			//$this->SetFont('helvetica', 'B', 10);			//$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');		}					}	?>