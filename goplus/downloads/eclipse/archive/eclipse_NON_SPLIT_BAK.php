<?php 
	require_once 'commons/initialize.php';
	define("XML_FOLDER","xmls/");
	
	ini_set("display_errors","on");
	error_reporting(E_ALL);
	
	if(isset($_GET['proposalid']))
	{
		$proposalid		= trim(urldecode($_GET['proposalid']));
		$customer		= htmlspecialchars(trim(urldecode($_GET['customer'])));
		$salesperson	= htmlspecialchars(trim(urldecode($_GET['salesperson'])));
		$agency			= htmlspecialchars(trim(urldecode($_GET['agency'])));
		$ucBookend		= htmlspecialchars(trim(urldecode($_GET['ucBookend'])));
		$ulLength		= htmlspecialchars(trim(urldecode($_GET['ulLength'])));
		
		$sql1 =" SELECT * FROM proposals WHERE id=$proposalid ";  
		$prpResult = $db->fetch_result($sql1);
		$proposal = $prpResult[0];
		
		$proposalLines = json_decode($proposal['proposal']);
		
		$xml = new DOMDocument();
		$xml->preserveWhiteSpace = false;
		$xml->formatOutput = true;
		$xmlOrders = $xml->createElement("Orders");
		$xmlOrder = $xml->createElement("Order");
		
		$xmlOrder->appendChild($xml->createElement("ulOrderNumber","")); //
		$xmlOrder->appendChild($xml->createElement("szPONumber",""));
		$xmlOrder->appendChild($xml->createElement("szContractNumber",$proposal['id']));
		$xmlOrder->appendChild($xml->createElement("szReferenceNumber",""));
		$xmlOrder->appendChild($xml->createElement("ucRegionBuy","N"));
		$xmlOrder->appendChild($xml->createElement("ucOrderType","I")); //T
		$xmlOrder->appendChild($xml->createElement("ucAnnacab","")); //N
		$xmlOrder->appendChild($xml->createElement("ucSendEDIInvoice","")); //Y
		$xmlOrder->appendChild($xml->createElement("szEDIOrderContract",""));
		$xmlOrder->appendChild($xml->createElement("szEDICustNumber","")); //715911
		$xmlOrder->appendChild($xml->createElement("szEDIProductCode",""));
		$xmlOrder->appendChild($xml->createElement("szEDIEstimateNumber","")); //n/a
		$xmlOrder->appendChild($xml->createElement("szEDIExternalCode","")); //SpotCable Order XML
		$xmlOrder->appendChild($xml->createElement("szContractEntrySystem",""));
		$xmlOrder->appendChild($xml->createElement("szMemo",""));
		
		$xmlCustomer = $xml->createElement("Customer");
		$xmlszCustNumber = $xml->createElement("szCustNumber",$customer); //000011050
		$xmlCustomer->appendChild($xmlszCustNumber);
		$xmlOrder->appendChild($xmlCustomer);
		
		$xmlRemitToAddress = $xml->createElement("RemitToAddress");
		$xmlszName = $xml->createElement("szName","");
		$xmlRemitToAddress->appendChild($xmlszName);
		$xmlOrder->appendChild($xmlRemitToAddress);
		
		$xmlSalesperson = $xml->createElement("Salesperson");
		$xmlszCode = $xml->createElement("szCode",$salesperson); //BJ
		$xmlSalesperson->appendChild($xmlszCode);
		$xmlOrder->appendChild($xmlSalesperson);
		
		$xmlSalesOffice = $xml->createElement("SalesOffice");
		$xmlszOffice= $xml->createElement("szOffice","");
		$xmlSalesOffice->appendChild($xmlszOffice);
		$xmlOrder->appendChild($xmlSalesOffice);
		
		$xmlAgency = $xml->createElement("Agency");
		$xmlszAgencyCustNumber= $xml->createElement("szAgencyCustNumber",$agency); //000011026
		$xmlAgency->appendChild($xmlszAgencyCustNumber);
		$xmlOrder->appendChild($xmlAgency);
		
		$xmlOrder->appendChild($xml->createElement("RevenueType",""));
		$xmlOrder->appendChild($xml->createElement("szTypeCode",""));
		
		$xmlDiscount = $xml->createElement("Discount");
		$xmlDiscount->appendChild($xml->createElement("szablAgencyDiscount",""));
		$xmlDiscount->appendChild($xml->createElement("szablRepDiscount",""));
		$xmlOrder->appendChild($xmlDiscount);
		
		$xmlOrderLines = $xml->createElement("OrderLines");
		
		/* print '<pre>';
		print_r($proposalLines);
		exit; */
		$lineNum = 1;
		for($i=0;$i<count($proposalLines);$i++)
		{
			$xmlOrderLine = $xml->createElement("OrderLine");
			$xmlOrderLine->appendChild($xml->createElement("ucBookend",$ucBookend)); //N
			$xmlOrderLine->appendChild($xml->createElement("abLineNumber",""));
			$xmlOrderLine->appendChild($xml->createElement("ulLineNumber",$lineNum++));
			$xmlOrderLine->appendChild($xml->createElement("ulStartDate",date("m/d/Y",strtotime($proposalLines[$i]->startdate))));
			$xmlOrderLine->appendChild($xml->createElement("ulEndDate",date("m/d/Y",strtotime($proposalLines[$i]->enddate))));
			$xmlOrderLine->appendChild($xml->createElement("szCopyRotation","R")); //R
			$xmlOrderLine->appendChild($xml->createElement("szRotationGroup","")); //R
			$xmlOrderLine->appendChild($xml->createElement("ulWeeksOn","1"));//$proposalLines[$i]->weeks
			$xmlOrderLine->appendChild($xml->createElement("ulWeeksSkip","")); //0
			$xmlOrderLine->appendChild($xml->createElement("ucBuyType","U")); //U
			
			if((strtotime($proposalLines[$i]->endtime)-strtotime($proposalLines[$i]->starttime))<0)
			$showLength = (strtotime($proposalLines[$i]->endtime." +1 day")-strtotime($proposalLines[$i]->starttime))/(60*60);
			else
			$showLength = (strtotime($proposalLines[$i]->endtime)-strtotime($proposalLines[$i]->starttime))/(60*60);
			
			
			if($proposalLines[$i]->linetype=='Fixed')	$xmlOrderLine->appendChild($xml->createElement("ulPriority","9")); //0
			else if($showLength<=2)	$xmlOrderLine->appendChild($xml->createElement("ulPriority","8")); //0
			else if($showLength<=3)	$xmlOrderLine->appendChild($xml->createElement("ulPriority","7")); //0
			else if($showLength<=4)	$xmlOrderLine->appendChild($xml->createElement("ulPriority","6")); //0
			else if($showLength<=5)	$xmlOrderLine->appendChild($xml->createElement("ulPriority","5")); //0
			else if($showLength<=6)	$xmlOrderLine->appendChild($xml->createElement("ulPriority","4")); //0
			else if($showLength<=18)	$xmlOrderLine->appendChild($xml->createElement("ulPriority","3")); //0
			//else if($ulLength<=18)	$xmlOrderLine->appendChild($xml->createElement("ulPriority","2")); //0
			else $xmlOrderLine->appendChild($xml->createElement("ulPriority","3")); //0
			
			$xmlOrderLine->appendChild($xml->createElement("ulStartTime",date("H:i:s",strtotime($proposalLines[$i]->starttime))));
			$xmlOrderLine->appendChild($xml->createElement("ulStopTime",date("H:i:s",strtotime($proposalLines[$i]->endtime))));
			
			$xmlInventoryType = $xml->createElement("InventoryType");
			$xmlInventoryType->appendChild($xml->createElement("szInvTypeCode","DEFAULT")); //DEFAULT
			$xmlOrderLine->appendChild($xmlInventoryType);
			$xmlOrderLine->appendChild($xml->createElement("ulLength",$ulLength)); //60
			
			$xmlQuantityType = $xml->createElement("QuantityType");
			
			if($proposalLines[$i]->linetype=='Rotator')		$xmlQuantityType->appendChild($xml->createElement("ucQuantityType","W")); //W
			else if($proposalLines[$i]->linetype=='Line')		$xmlQuantityType->appendChild($xml->createElement("ucQuantityType","L")); //W
			else if($proposalLines[$i]->linetype=='Fixed')		$xmlQuantityType->appendChild($xml->createElement("ucQuantityType","D")); //W
			
			
			$xmlQuantityType->appendChild($xml->createElement("usSpotsWeek",$proposalLines[$i]->spotsweek));
			
			if($proposalLines[$i]->linetype=='Fixed')
			{
				$weekDaysArr = (is_array($proposalLines[$i]->day))?$proposalLines[$i]->day:explode(',',$proposalLines[$i]->day);
				$xmlQuantityType->appendChild($xml->createElement("usMondayQty",in_array('2',$weekDaysArr)?$proposalLines[$i]->spotsweek:"0"));
				$xmlQuantityType->appendChild($xml->createElement("usTuesdayQty",in_array('3',$weekDaysArr)?$proposalLines[$i]->spotsweek:"0"));
				$xmlQuantityType->appendChild($xml->createElement("usWednesdayQty",in_array('4',$weekDaysArr)?$proposalLines[$i]->spotsweek:"0"));
				$xmlQuantityType->appendChild($xml->createElement("usThursdayQty",in_array('5',$weekDaysArr)?$proposalLines[$i]->spotsweek:"0"));
				$xmlQuantityType->appendChild($xml->createElement("usFridayQty",in_array('6',$weekDaysArr)?$proposalLines[$i]->spotsweek:"0"));
				$xmlQuantityType->appendChild($xml->createElement("usSaturdayQty",in_array('7',$weekDaysArr)?$proposalLines[$i]->spotsweek:"0"));
				$xmlQuantityType->appendChild($xml->createElement("usSundayQty",in_array('1',$weekDaysArr)?$proposalLines[$i]->spotsweek:"0"));
			} else
			{
				$weekDaysArr = (is_array($proposalLines[$i]->day))?$proposalLines[$i]->day:explode(',',$proposalLines[$i]->day);
				$xmlQuantityType->appendChild($xml->createElement("ucMonday",in_array('2',$weekDaysArr)?"Y":"N"));
				$xmlQuantityType->appendChild($xml->createElement("ucTuesday",in_array('3',$weekDaysArr)?"Y":"N"));
				$xmlQuantityType->appendChild($xml->createElement("ucWednesday",in_array('4',$weekDaysArr)?"Y":"N"));
				$xmlQuantityType->appendChild($xml->createElement("ucThursday",in_array('5',$weekDaysArr)?"Y":"N"));
				$xmlQuantityType->appendChild($xml->createElement("ucFriday",in_array('6',$weekDaysArr)?"Y":"N"));
				$xmlQuantityType->appendChild($xml->createElement("ucSaturday",in_array('7',$weekDaysArr)?"Y":"N"));
				$xmlQuantityType->appendChild($xml->createElement("ucSunday",in_array('1',$weekDaysArr)?"Y":"N"));
			}
			$xmlOrderLine->appendChild($xmlQuantityType);
			
			$xmlOrderLineRNS = $xml->createElement("OrderLineRNS");
			$xmlOrderLineRN = $xml->createElement("OrderLineRN");
			
			$codesQryRes = mysql_query("SELECT networkCode, networkCodeHD FROM Customers.GCIMapping WHERE tmsID = '{$proposalLines[$i]->stationnum}'");
			$codesObj = mysql_fetch_object($codesQryRes);
			
			$xmlOrderLineRN->appendChild($xml->createElement("szNetwork",trim($codesObj->networkCode)));
			//$xmlOrderLineRN->appendChild($xml->createElement("szNetwork",$proposalLines[$i]->callsign));
			
			$xmlOrderLineRN->appendChild($xml->createElement("szRegionCode",mysql_fetch_object(mysql_query(" SELECT altcode FROM ShowSeeker.zones WHERE id={$proposalLines[$i]->zoneid} "))->altcode)); //ANC
			$xmlOrderLineRN->appendChild($xml->createElement("dRate",$proposalLines[$i]->rate));
			$xmlOrderLineRNS->appendChild($xmlOrderLineRN);
			$xmlOrderLine->appendChild($xmlOrderLineRNS);
			
			$xmlOrderLines->appendChild($xmlOrderLine);
			//************* HD VERSION BOC
			if(trim($codesObj->networkCodeHD)!="")
			{
				$xmlOrderLine = $xml->createElement("OrderLine");
				$xmlOrderLine->appendChild($xml->createElement("ucBookend",$ucBookend)); //N
				$xmlOrderLine->appendChild($xml->createElement("abLineNumber",""));
				$xmlOrderLine->appendChild($xml->createElement("ulLineNumber",$lineNum++));
				$xmlOrderLine->appendChild($xml->createElement("ulStartDate",date("m/d/Y",strtotime($proposalLines[$i]->startdate))));
				$xmlOrderLine->appendChild($xml->createElement("ulEndDate",date("m/d/Y",strtotime($proposalLines[$i]->enddate))));
				$xmlOrderLine->appendChild($xml->createElement("szCopyRotation","R")); //R
				$xmlOrderLine->appendChild($xml->createElement("szRotationGroup","")); //R
				$xmlOrderLine->appendChild($xml->createElement("ulWeeksOn","1")); //$proposalLines[$i]->weeks));
				$xmlOrderLine->appendChild($xml->createElement("ulWeeksSkip","")); //0
				$xmlOrderLine->appendChild($xml->createElement("ucBuyType","U")); //U
				
				if((strtotime($proposalLines[$i]->endtime)-strtotime($proposalLines[$i]->starttime))<0)
				$showLength = (strtotime($proposalLines[$i]->endtime." +1 day")-strtotime($proposalLines[$i]->starttime))/(60*60);
				else
				$showLength = (strtotime($proposalLines[$i]->endtime)-strtotime($proposalLines[$i]->starttime))/(60*60);
				
				
				if($proposalLines[$i]->linetype=='Fixed')	$xmlOrderLine->appendChild($xml->createElement("ulPriority","9")); //0
				else if($showLength<=2)	$xmlOrderLine->appendChild($xml->createElement("ulPriority","8")); //0
				else if($showLength<=3)	$xmlOrderLine->appendChild($xml->createElement("ulPriority","7")); //0
				else if($showLength<=4)	$xmlOrderLine->appendChild($xml->createElement("ulPriority","6")); //0
				else if($showLength<=5)	$xmlOrderLine->appendChild($xml->createElement("ulPriority","5")); //0
				else if($showLength<=6)	$xmlOrderLine->appendChild($xml->createElement("ulPriority","4")); //0
				else if($showLength<=18)	$xmlOrderLine->appendChild($xml->createElement("ulPriority","3")); //0
				//else if($ulLength<=18)	$xmlOrderLine->appendChild($xml->createElement("ulPriority","2")); //0
				else $xmlOrderLine->appendChild($xml->createElement("ulPriority","3")); //0
				
				$xmlOrderLine->appendChild($xml->createElement("ulStartTime",date("H:i:s",strtotime($proposalLines[$i]->starttime))));
				$xmlOrderLine->appendChild($xml->createElement("ulStopTime",date("H:i:s",strtotime($proposalLines[$i]->endtime))));
				
				$xmlInventoryType = $xml->createElement("InventoryType");
				$xmlInventoryType->appendChild($xml->createElement("szInvTypeCode","DEFAULT")); //DEFAULT
				$xmlOrderLine->appendChild($xmlInventoryType);
				$xmlOrderLine->appendChild($xml->createElement("ulLength",$ulLength)); //60
				
				$xmlQuantityType = $xml->createElement("QuantityType");
				
				if($proposalLines[$i]->linetype=='Rotator')		$xmlQuantityType->appendChild($xml->createElement("ucQuantityType","W")); //W
				else if($proposalLines[$i]->linetype=='Line')		$xmlQuantityType->appendChild($xml->createElement("ucQuantityType","L")); //W
				else if($proposalLines[$i]->linetype=='Fixed')		$xmlQuantityType->appendChild($xml->createElement("ucQuantityType","D")); //W
				
				
				$xmlQuantityType->appendChild($xml->createElement("usSpotsWeek",$proposalLines[$i]->spotsweek));
				
				if($proposalLines[$i]->linetype=='Fixed')
				{
					$weekDaysArr = (is_array($proposalLines[$i]->day))?$proposalLines[$i]->day:explode(',',$proposalLines[$i]->day);
					$xmlQuantityType->appendChild($xml->createElement("usMondayQty",in_array('2',$weekDaysArr)?$proposalLines[$i]->spotsweek:"0"));
					$xmlQuantityType->appendChild($xml->createElement("usTuesdayQty",in_array('3',$weekDaysArr)?$proposalLines[$i]->spotsweek:"0"));
					$xmlQuantityType->appendChild($xml->createElement("usWednesdayQty",in_array('4',$weekDaysArr)?$proposalLines[$i]->spotsweek:"0"));
					$xmlQuantityType->appendChild($xml->createElement("usThursdayQty",in_array('5',$weekDaysArr)?$proposalLines[$i]->spotsweek:"0"));
					$xmlQuantityType->appendChild($xml->createElement("usFridayQty",in_array('6',$weekDaysArr)?$proposalLines[$i]->spotsweek:"0"));
					$xmlQuantityType->appendChild($xml->createElement("usSaturdayQty",in_array('7',$weekDaysArr)?$proposalLines[$i]->spotsweek:"0"));
					$xmlQuantityType->appendChild($xml->createElement("usSundayQty",in_array('1',$weekDaysArr)?$proposalLines[$i]->spotsweek:"0"));
				} else
				{
					$weekDaysArr = (is_array($proposalLines[$i]->day))?$proposalLines[$i]->day:explode(',',$proposalLines[$i]->day);
					$xmlQuantityType->appendChild($xml->createElement("ucMonday",in_array('2',$weekDaysArr)?"Y":"N"));
					$xmlQuantityType->appendChild($xml->createElement("ucTuesday",in_array('3',$weekDaysArr)?"Y":"N"));
					$xmlQuantityType->appendChild($xml->createElement("ucWednesday",in_array('4',$weekDaysArr)?"Y":"N"));
					$xmlQuantityType->appendChild($xml->createElement("ucThursday",in_array('5',$weekDaysArr)?"Y":"N"));
					$xmlQuantityType->appendChild($xml->createElement("ucFriday",in_array('6',$weekDaysArr)?"Y":"N"));
					$xmlQuantityType->appendChild($xml->createElement("ucSaturday",in_array('7',$weekDaysArr)?"Y":"N"));
					$xmlQuantityType->appendChild($xml->createElement("ucSunday",in_array('1',$weekDaysArr)?"Y":"N"));
				}
				$xmlOrderLine->appendChild($xmlQuantityType);
				
				$xmlOrderLineRNS = $xml->createElement("OrderLineRNS");
				$xmlOrderLineRN = $xml->createElement("OrderLineRN");
				$xmlOrderLineRN->appendChild($xml->createElement("szNetwork",trim($codesObj->networkCodeHD)));
				$xmlOrderLineRN->appendChild($xml->createElement("szRegionCode",mysql_fetch_object(mysql_query(" SELECT altcode FROM ShowSeeker.zones WHERE id={$proposalLines[$i]->zoneid} "))->altcode)); //ANC
				$xmlOrderLineRN->appendChild($xml->createElement("dRate",$proposalLines[$i]->rate));
				$xmlOrderLineRNS->appendChild($xmlOrderLineRN);
				$xmlOrderLine->appendChild($xmlOrderLineRNS);
				
				$xmlOrderLines->appendChild($xmlOrderLine);
			}
			// **************** HD VERSION EOC
		}
		$xmlOrder->appendChild($xmlOrderLines);
		
		
		$xmlOrders->appendChild($xmlOrder);
		$xml->appendChild($xmlOrders);
		
		
		$name = str_replace(" ",".",strtolower(urldecode($proposal['name']))).".xml"; 
		//header('Content-Disposition: attachment;filename=' . $name);
		//header('Content-Type: text/xml');
		$xml->save(XML_FOLDER.$name);
		//echo $xml->saveXML();
		echo XML_FOLDER.$name;
		return;
	}
	
	//<!-------------- remove this part ------------------------------------>
	$sql2 =" SELECT name, id FROM proposals WHERE userid=742 and deletedat IS NULL ";  
	$proposalList = $db->fetch_result($sql2);
?>
<form method="get" action="">
	<table border="0">
		<tr>
			<td>Proposalid :</td>
			<td>
				<Select name="proposalid" id="proposalid">
					<option value=""></option>
					<?php foreach($proposalList as $prop){ ?>
						<option value="<?php echo $prop['id']; ?>" <?php echo (isset($_GET['proposalid']) && $_GET['proposalid']==$prop['id'])?'selected="selected"':'';?>><?php echo urldecode($prop['name']); ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>customer :</td>
			<td> <input type="text" name="customer" id="customer" value="<?php echo (isset($_GET['customer']))?$_GET['customer']:'';?>"/></td>
		</tr>
		<tr>
			<td>salesperson :</td>
			<td>  <input type="text" name="salesperson" id="salesperson" value="<?php echo (isset($_GET['salesperson']))?$_GET['salesperson']:'';?>"/></td>
		</tr>
		<tr>
			<td>agency :</td>
			<td>   <input type="text" name="agency" id="agency" value="<?php echo (isset($_GET['agency']))?$_GET['agency']:'';?>"/></td>
		</tr>
		<tr>
			<td>ucBookend :</td>
			<td>   <input type="text" name="ucBookend" id="ucBookend" value="<?php echo (isset($_GET['ucBookend']))?$_GET['ucBookend']:'';?>"/></td>
		</tr>
		<tr>
			<td>ulLength :</td>
			<td>   <input type="text" name="ulLength" id="ulLength" value="<?php echo (isset($_GET['ulLength']))?$_GET['ulLength']:'';?>"/></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>  
				<input type="submit" name="submit" value="Go"/>
			</td>
		</tr>
	</table>
</form>
<hr/hr
