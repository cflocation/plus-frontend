<?phpfunction termsandconditions(&$table,$cols){		$table->addRow(380);		$cellStyleTC=array('gridSpan' => $cols,'valign'=>'center');		$table->addCell(15400,$cellStyleTC)->addText('Terms and Conditions', array('size'=>9, 'underline' => PHPWord_Style_Font::UNDERLINE_SINGLE),array('align'=>'left'));				$table->addRow(300);		$cellStyleTC=array('gridSpan' => $cols,'valign'=>'center');		$table->addCell(15400,$cellStyleTC)->addText('', array('bold'=>true, 'size'=>8),array('align'=>'left'));		$table->addRow(280);		$cellStyleTC=array('gridSpan' => $cols,'valign'=>'center');		$table->addCell(15400,$cellStyleTC)->addText('1. Payment Terms: Deposit due at signing and balance due montly NET30.', array('size'=>8),array('align'=>'left','indent'=>1));				$table->addRow(280);		$cellStyleTC=array('gridSpan' => $cols,'valign'=>'center');		$table->addCell(15400,$cellStyleTC)->addText('2. Past due accounts will be billed a finance fee of 2% monthly.', array('size'=>8),array('align'=>'left','indent'=>1));				$table->addRow(280);		$cellStyleTC=array('gridSpan' => $cols,'valign'=>'center');		$table->addCell(15400,$cellStyleTC)->addText('3. Any and all collection fees will be passed on to the client.', array('size'=>8),array('align'=>'left','indent'=>1));		$table->addRow(280);		$cellStyleTC=array('gridSpan' => $cols,'valign'=>'center');		$table->addCell(15400,$cellStyleTC)->addText('4. Cable reserves the right to withdrawal commercials at any time for nonpayment.', array('size'=>8),array('align'=>'left','indent'=>1));				$table->addRow(280);		$cellStyleTC=array('gridSpan' => $cols,'valign'=>'center');		$table->addCell(15400,$cellStyleTC)->addText('5. This contract is voided after 30 days from signing and deposits are non-refundable.', array('size'=>8),array('align'=>'left','indent'=>1));				$table->addRow(280);		$cellStyleTC=array('gridSpan' => $cols,'valign'=>'center');		$table->addCell(15400,$cellStyleTC)->addText('6. After airing has begun, there are no refunds and total amount of contract will be due.', array('size'=>8),array('align'=>'left','indent'=>1));				$table->addRow(280);		$cellStyleTC=array('gridSpan' => $cols,'valign'=>'center');		$table->addCell(15400,$cellStyleTC)->addText('7. Some additional terms and restrictions may apply.', array('size'=>8),array('align'=>'left','indent'=>1));										$table->addRow(300);		$cellStyleTC=array('gridSpan' => $cols,'valign'=>'center');		$table->addCell(15400,$cellStyleTC)->addText('', array('size'=>10),array('align'=>'left'));				$table->addRow(280);		$cellStyleTC=array('gridSpan' => $cols,'valign'=>'center');		$table->addCell(15400,$cellStyleTC)->addText('The undersigned do hereby agree that by executing this contract, the parties understand that they are legally', array('size'=>8),array('align'=>'left'));						$table->addRow(280);		$cellStyleTC=array('gridSpan' => $cols,'valign'=>'center');		$table->addCell(15400,$cellStyleTC)->addText('bound by the terms and conditions of this agreement.', array('size'=>8),array('align'=>'left'));		}?>