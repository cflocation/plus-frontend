<?php
			$h = 15;
			$y = $start+7;
			
			//CALCULATES IF THERE IS ENOUGH ROOM IN THE PAGE TO DRAW A NEW PROPSOSAL LINE
			if(($y+$h)>=$pageLengh){
				$y = $start = 46;
				$pdf->AddPage();
			}
			
			//
			$rw 	= 1;
			$rcw 	= 1;

			$x 	= PDF_MARGIN_LEFT;
			$w 	= $h;


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
			
			//$pdf->MultiCell($w, $h, $formattedTitle, 0, 'L',0,0,$x,$y_pos,true,0,true,true,$h,'M');
?>