<?phpfunction createTotals(&$currentSlide, $arrBcTotals, $arrTotals,$corpid,$includetc,$calendar){	$shape = $currentSlide->createTableShape(3);	$shape->setHeight(400);	$shape->setWidth(600);	$shape->setOffsetX(175);	$shape->setOffsetY(80);				$row = $shape->createRow();		$row->setHeight(30);		$cell = $row->nextCell();	$cell->setColSpan(3);		if($corpid != 15 && $corpid != 14  && $corpid != 30){			$cell->createTextRun($calendar.' Calendar Totals')			  ->getFont()			  ->setBold(true)			  ->setSize(16)			  ->setColor( new PHPPowerPoint_Style_Color( '55555555' ) );	}	else{//CHARTER MEDIA		$cell->createTextRun($calendar.' Calendar Totals')			  ->getFont()			  ->setBold(true)			  ->setSize(16)			  ->setColor( new PHPPowerPoint_Style_Color( '0000629B' ) );		}	$cell->getActiveParagraph()->getAlignment()->setHorizontal(PHPPowerPoint_Style_Alignment::HORIZONTAL_CENTER);	$bdr = noBorder($row); 		$row = $shape->createRow();	$row->getFill()->setFillType(PHPPowerPoint_Style_Fill::FILL_GRADIENT_LINEAR)	               ->setRotation(90)	               ->setStartColor(new PHPPowerPoint_Style_Color('0000629B'))	               ->setEndColor(new PHPPowerPoint_Style_Color('0000629B'));		$row->setHeight(25);		$cell = $row->nextCell();	$cell->setWidth(200);	$cell->createTextRun('Month')		  ->getFont()		  ->setBold(true)		  ->setSize(14)		  ->setColor(new PHPPowerPoint_Style_Color('FFFFFFFF'));	$cell->getActiveParagraph()->getAlignment()->setHorizontal(PHPPowerPoint_Style_Alignment::HORIZONTAL_CENTER);	$cell = $row->nextCell();	$cell->setWidth(200);	$cell->createTextRun('Spots')		  ->getFont()		  ->setBold(true)		  ->setSize(14)		  ->setColor(new PHPPowerPoint_Style_Color('FFFFFFFF'));	$cell->getActiveParagraph()->getAlignment()->setHorizontal(PHPPowerPoint_Style_Alignment::HORIZONTAL_CENTER);	$cell = $row->nextCell();	$cell->setWidth(200);	$cell->createTextRun('Cost')		  ->getFont()		  ->setBold(true)		  ->setSize(14)		  ->setColor(new PHPPowerPoint_Style_Color('FFFFFFFF'));	$cell->getActiveParagraph()->getAlignment()->setHorizontal(PHPPowerPoint_Style_Alignment::HORIZONTAL_RIGHT);	// remove borders	$nobdr = noBorder($row); 		$allspots = 0;    foreach($arrBcTotals as $yearKey => $yearVal){             $year = substr($yearKey,2,2);             foreach($yearVal as $monthKey => $monthVal){	       // if($monthVal['spotsmonth'] != 0){				$row = $shape->createRow();					$row->setHeight(13);						$cell = $row->nextCell();				$cell->createTextRun($monthVal['monthnumber'].'-'.$year)->getFont()->setBold(true)->setSize(11);				$cell->getActiveParagraph()->getAlignment()->setHorizontal(PHPPowerPoint_Style_Alignment::HORIZONTAL_CENTER);						$cell = $row->nextCell();				$cell->createTextRun($monthVal['spotsmonth'])->getFont()->setBold(true)->setSize(11);				$cell->getActiveParagraph()->getAlignment()->setHorizontal(PHPPowerPoint_Style_Alignment::HORIZONTAL_CENTER);				$allspots =  $allspots + $monthVal['spotsmonth'];							        				$cell = $row->nextCell();				$cell->createTextRun('$ '. number_format($monthVal['monthtotal'],2,'.',','))->getFont()->setBold(true)->setSize(11);				$cell->getActiveParagraph()->getAlignment()->setHorizontal(PHPPowerPoint_Style_Alignment::HORIZONTAL_RIGHT);									$btm = createBottomLn($row);			//}        }    }	$btm = createBottomLn($row);	$row = $shape->createRow();		$row->setHeight(15);		$rowbkground = createBkgdColor($row);		$cell = $row->nextCell();		$cell = $row->nextCell();			$cell->createTextRun('Spots')->getFont()->setBold(true)->setSize(12);	$cell->getActiveParagraph()->getAlignment()->setHorizontal(PHPPowerPoint_Style_Alignment::HORIZONTAL_RIGHT);					$cell = $row->nextCell();		$cell->createTextRun($allspots)->getFont()->setBold(true)->setSize(12);	$cell->getActiveParagraph()->getAlignment()->setHorizontal(PHPPowerPoint_Style_Alignment::HORIZONTAL_RIGHT);			$btm = createBottomLn($row);	$row = $shape->createRow();		$row->setHeight(15);		$rowbkground = createBkgdColor($row);		$cell = $row->nextCell();		$cell = $row->nextCell();			$cell->createTextRun('Gross')->getFont()->setBold(true)->setSize(12);	$cell->getActiveParagraph()->getAlignment()->setHorizontal(PHPPowerPoint_Style_Alignment::HORIZONTAL_RIGHT);					$cell = $row->nextCell();		$cell->createTextRun('$ '. number_format($arrTotals['gross'],2,'.',','))->getFont()->setBold(true)->setSize(12);	$cell->getActiveParagraph()->getAlignment()->setHorizontal(PHPPowerPoint_Style_Alignment::HORIZONTAL_RIGHT);			$btm = createBottomLn($row);	$row = $shape->createRow();		$row->setHeight(15);	$rowbkground = createBkgdColor($row);			$cell = $row->nextCell();		$cell = $row->nextCell();		$cell->createTextRun('Net Total')->getFont()->setBold(true)->setSize(12);	$cell->getActiveParagraph()->getAlignment()->setHorizontal(PHPPowerPoint_Style_Alignment::HORIZONTAL_RIGHT);			$cell = $row->nextCell();		$cell->setColSpan(1);			$cell->createTextRun('$ '. number_format($arrTotals['net'],2,'.',','))->getFont()->setBold(true)->setSize(12);	$cell->getActiveParagraph()->getAlignment()->setHorizontal(PHPPowerPoint_Style_Alignment::HORIZONTAL_RIGHT);				$btm = createBottomLn($row);	$row = $shape->createRow();	$nobdr = noBorder($row);		$row = $shape->createRow();	$cell = $row->nextCell();	$nobdr = noBorder($row); 		$cell->createTextRun('Signature')->getFont()->setBold(true)->setSize(14);;		$cell->getActiveParagraph()->getAlignment()->setHorizontal(PHPPowerPoint_Style_Alignment::HORIZONTAL_RIGHT);	$cell = $row->nextCell();	$cell->setColSpan(2);	$cell->getBorders()->getBottom()->setLineWidth(2)->setLineStyle(PHPPowerPoint_Style_Border::LINE_SINGLE);	$cell->getBorders()->getBottom()->setColor(new PHPPowerPoint_Style_Color('33333333'));		$row = $shape->createRow();	$cell = $row->nextCell();	$nobdr = noBorder($row); 		$cell->createTextRun('Date')->getFont()->setBold(true)->setSize(14);;		$cell->getActiveParagraph()->getAlignment()->setHorizontal(PHPPowerPoint_Style_Alignment::HORIZONTAL_RIGHT);	$cell = $row->nextCell();	$cell->setColSpan(2);	$cell->getBorders()->getTop()->setLineWidth(2)->setLineStyle(PHPPowerPoint_Style_Border::LINE_SINGLE);	$cell->getBorders()->getTop()->setColor(new PHPPowerPoint_Style_Color('33333333'));		$cell->getBorders()->getBottom()->setLineWidth(2)->setLineStyle(PHPPowerPoint_Style_Border::LINE_SINGLE);	$cell->getBorders()->getBottom()->setColor(new PHPPowerPoint_Style_Color('33333333'));		if($includetc == 'true'){				$row = $shape->createRow();		$cell = $row->nextCell();			$cell->setColSpan(3);		$cell->createBreak();					$cell->createTextRun('See Attached Terms and Conditions')->getFont()->setBold(true)->setSize(14);;			$cell->getActiveParagraph()->getAlignment()->setHorizontal(PHPPowerPoint_Style_Alignment::HORIZONTAL_LEFT);		$cell->getBorders()->getTop()->setLineStyle(PHPPowerPoint_Style_Border::LINE_NONE);		$cell->getBorders()->getRight()->setLineStyle(PHPPowerPoint_Style_Border::LINE_NONE);		$cell->getBorders()->getBottom()->setLineStyle(PHPPowerPoint_Style_Border::LINE_NONE);						$cell->getBorders()->getLeft()->setLineStyle(PHPPowerPoint_Style_Border::LINE_NONE);			}	return $shape;}?>