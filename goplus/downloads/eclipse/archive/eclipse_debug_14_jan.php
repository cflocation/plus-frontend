<?php 
	require_once 'commons/initialize.php';
	define("XML_FOLDER","xmls/");
	
	//ini_set("display_errors","on");
	//ini_set("display_startup_errors","on");
	//error_reporting(E_ALL);
	
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
		$totalSpots = getTotalSpots($proposalLines);
		
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
		
		//***********************************************************************************************
		$netTotal = 0;
		$count = 0;
		$sumTotal = 0;
		$minFactor=null;
		$singleSpotCount = 0;
		$maxSingleSptRt = 0;
		for($i=0;$i<count($proposalLines);$i++)
		{
			$sumTotal += $proposalLines[$i]->total;
			$weeksSplit = getWeeks($proposalLines[$i]);
			foreach($weeksSplit as $k=>$sWeek)
			{
				if($sWeek['spots']>0)
				{
					$discountedRate[$i][$k] = getDiscountedRate($proposal['discountpackage'],$proposal['discountpackagetype'],$proposal['discountagency'],$proposalLines[$i]->rate,$totalSpots);
					$netTotal += $discountedRate[$i][$k]*$sWeek['spots'];
					$minFactor = ($minFactor==null)?$discountedRate[$i][$k]:min($discountedRate[$i][$k],$minFactor);
					
					if($sWeek['spots']==1)
					{
						$maxSingleSptRt = ($maxSingleSptRt==null)?$discountedRate[$i][$k]:max($discountedRate[$i][$k],$maxSingleSptRt);
					}
					if($sWeek['spots']==1 && $discountedRate[$i][$k]>0) $singleSpotCount++;
					$count++;
				}
			}
		}
		$expectedTotal = getShowseekerNetTotal($sumTotal,$proposal['discountpackage'],$proposal['discountpackagetype'],$proposal['discountagency']);
		$diff = round($expectedTotal-$netTotal,2);
		
		$NewTotal = 0;
		if($diff!=0)
		{
			for($i=0;$i<count($proposalLines);$i++)
			{
				$weeksSplit = getWeeks($proposalLines[$i]);
				foreach($discountedRate[$i] as $k=>&$r)
				{
					if($singleSpotCount>0 && $maxSingleSptRt==$r && abs($diff)!= 0)
					{
						$r =($diff>0)?$r-$diff:$r+$diff;
						$diff = 0;
					} else if($singleSpotCount==0 && $minFactor>abs($diff) && abs($diff)!= 0)
					{
						$d = ($diff/$weeksSplit[$k]['spots']);
						$r = ($diff>0)?$r+$d:$r-$d;
						$r = round($r,2);
						$diff = 0;
					} else if($singleSpotCount==0 && abs($diff)!= 0)
					{
						$tdiff = $minFactor;
						$tdiff = ($diff<0)?$tdiff*-1:$tdiff;
						$d = ($tdiff/$weeksSplit[$k]['spots']);
						$r = ($tdiff>0)?$r-$d:$r+$d;
						$r = round($r,2);
						$diff = ($diff>0)?$diff-$minFactor:$diff+$minFactor;
					}
					$NewTotal += ($r*$weeksSplit[$k]['spots']);
				}
			}
		}
		//***********************************************************************************************
		
		
		$lineNum = 1;
		for($i=0;$i<count($proposalLines);$i++)
		{
			$codesObj = mysql_fetch_object(mysql_query("SELECT gci.networkCode
			, IF((SELECT z.hd FROM ShowSeeker.zones as z WHERE z.id={$proposalLines[$i]->zoneid})>0,gci.networkCodeHD,'') AS networkCodeHD
			FROM Customers.GCIMapping as gci WHERE gci.tmsID = '{$proposalLines[$i]->stationnum}'"));
			
			$weeksSplit = getWeeks($proposalLines[$i]);
			
			foreach($weeksSplit as $k=>$sWeek)
			{
				if($sWeek['spots']>0)
				{
					//########################
					if($lineNum>=32 && $lineNum<=36)
					{
						echo "<pre><hr/>".$lineNum;
						print_r($sWeek);
						echo "<hr/>";
						print_r($proposalLines[$i]);
						echo "<hr/></pre>";
					}
					//########################
					//$discRate = getDiscountedRate($proposal['discountpackage'],$proposal['discountpackagetype'],$proposal['discountagency'],$proposalLines[$i]->rate,$totalSpots);
					$xmlOrderLines->appendChild(getOrderLineXml($xml, $lineNum++,$ucBookend,$proposalLines[$i]->linetype,$sWeek['startdate'],$sWeek['enddate'],$proposalLines[$i]->starttime,$proposalLines[$i]->endtime,$ulLength,$sWeek['spots'],$proposalLines[$i]->day,$codesObj->networkCode, $proposalLines[$i]->zoneid, $discountedRate[$i][$k]));
				}
			}
			if(trim($codesObj->networkCodeHD)!="")
			{
				foreach($weeksSplit as $sWeek)
				{
					if($sWeek['spots']>0)
					{
						$xmlOrderLines->appendChild(getOrderLineXml($xml, $lineNum++,$ucBookend,$proposalLines[$i]->linetype,$sWeek['startdate'],$sWeek['enddate'],$proposalLines[$i]->starttime,$proposalLines[$i]->endtime,$ulLength,$sWeek['spots'],$proposalLines[$i]->day,$codesObj->networkCodeHD, $proposalLines[$i]->zoneid, 0));
					}
				}
			}
		}
		$xmlOrder->appendChild($xmlOrderLines);
		$xmlOrders->appendChild($xmlOrder);
		$xml->appendChild($xmlOrders);
		
		$name = str_replace(" ",".",strtolower(preg_replace('/[^\w\s]+/u','' ,urldecode($proposal['name'])))).".xml";
	$name = "asifdebuggingprogress.xml";
		$xml->save(XML_FOLDER.$name);
		echo XML_FOLDER.$name;
		return;
	}

function getOrderLineXml($xml, $lineNum, $ucBookend, $linetype, $startdate,$enddate,$starttime,$endtime, $ulLength,$spotsweek,$days,$networkCode,$zoneid,$rate)
{
	$xmlOrderLine = $xml->createElement("OrderLine");
	$xmlOrderLine->appendChild($xml->createElement("ucBookend",$ucBookend));
	$xmlOrderLine->appendChild($xml->createElement("abLineNumber",""));
	$xmlOrderLine->appendChild($xml->createElement("ulLineNumber",$lineNum));
	$xmlOrderLine->appendChild($xml->createElement("ulStartDate",date("m/d/Y",strtotime($startdate))));
	$xmlOrderLine->appendChild($xml->createElement("ulEndDate",date("m/d/Y",strtotime($enddate))));
	$xmlOrderLine->appendChild($xml->createElement("szCopyRotation","R"));
	$xmlOrderLine->appendChild($xml->createElement("szRotationGroup","")); //R
	$xmlOrderLine->appendChild($xml->createElement("ulWeeksOn","1"));
	$xmlOrderLine->appendChild($xml->createElement("ulWeeksSkip","")); //0
	$xmlOrderLine->appendChild($xml->createElement("ucBuyType","U"));
	
	if((strtotime($endtime)-strtotime($starttime))<0)
	$showLength = (strtotime($endtime." +1 day")-strtotime($starttime))/(60*60);
	else
	$showLength = (strtotime($endtime)-strtotime($starttime))/(60*60);
	
	
	if($linetype=='Fixed')	$xmlOrderLine->appendChild($xml->createElement("ulPriority","9"));
	else if($showLength<=2)	$xmlOrderLine->appendChild($xml->createElement("ulPriority","8"));
	else if($showLength<=3)	$xmlOrderLine->appendChild($xml->createElement("ulPriority","7"));
	else if($showLength<=4)	$xmlOrderLine->appendChild($xml->createElement("ulPriority","6"));
	else if($showLength<=5)	$xmlOrderLine->appendChild($xml->createElement("ulPriority","5"));
	else if($showLength<=6)	$xmlOrderLine->appendChild($xml->createElement("ulPriority","4"));
	else if($showLength<=18)	$xmlOrderLine->appendChild($xml->createElement("ulPriority","3"));
	else $xmlOrderLine->appendChild($xml->createElement("ulPriority","3"));
	
	$xmlOrderLine->appendChild($xml->createElement("ulStartTime",date("H:i:s",strtotime($starttime))));
	$xmlOrderLine->appendChild($xml->createElement("ulStopTime",date("H:i:s",strtotime($endtime))));
	
	$xmlInventoryType = $xml->createElement("InventoryType");
	$xmlInventoryType->appendChild($xml->createElement("szInvTypeCode","DEFAULT"));
	$xmlOrderLine->appendChild($xmlInventoryType);
	$xmlOrderLine->appendChild($xml->createElement("ulLength",$ulLength));
	
	$xmlQuantityType = $xml->createElement("QuantityType");
	
	if($linetype=='Rotator')		$xmlQuantityType->appendChild($xml->createElement("ucQuantityType","W"));
	else if($linetype=='Line')		$xmlQuantityType->appendChild($xml->createElement("ucQuantityType","W")); //Was L previously, changed to W on 1-1-2014
	else if($linetype=='Fixed')		$xmlQuantityType->appendChild($xml->createElement("ucQuantityType","D"));
	
	
	$xmlQuantityType->appendChild($xml->createElement("usSpotsWeek",$spotsweek));
	
	if($linetype=='Fixed')
	{
		$weekDaysArr = (is_array($days))?$days:explode(',',$days);
		$xmlQuantityType->appendChild($xml->createElement("usMondayQty",in_array('2',$weekDaysArr)?$spotsweek:"0"));
		$xmlQuantityType->appendChild($xml->createElement("usTuesdayQty",in_array('3',$weekDaysArr)?$spotsweek:"0"));
		$xmlQuantityType->appendChild($xml->createElement("usWednesdayQty",in_array('4',$weekDaysArr)?$spotsweek:"0"));
		$xmlQuantityType->appendChild($xml->createElement("usThursdayQty",in_array('5',$weekDaysArr)?$spotsweek:"0"));
		$xmlQuantityType->appendChild($xml->createElement("usFridayQty",in_array('6',$weekDaysArr)?$spotsweek:"0"));
		$xmlQuantityType->appendChild($xml->createElement("usSaturdayQty",in_array('7',$weekDaysArr)?$spotsweek:"0"));
		$xmlQuantityType->appendChild($xml->createElement("usSundayQty",in_array('1',$weekDaysArr)?$spotsweek:"0"));
	} else
	{
		$weekDaysArr = (is_array($days))?$days:explode(',',$days);
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
	
	$xmlOrderLineRN->appendChild($xml->createElement("szNetwork",trim($networkCode)));
	
	$xmlOrderLineRN->appendChild($xml->createElement("szRegionCode",mysql_fetch_object(mysql_query(" SELECT altcode FROM ShowSeeker.zones WHERE id={$zoneid} "))->altcode));
	$xmlOrderLineRN->appendChild($xml->createElement("dRate",$rate));
	$xmlOrderLineRNS->appendChild($xmlOrderLineRN);
	$xmlOrderLine->appendChild($xmlOrderLineRNS);
	return $xmlOrderLine;
}

 

function getWeeks($proposalLine)
{
	$weeksArr = array();

	if(date("N",strtotime($proposalLine->startdate))==1)
		$itWeek = strtotime($proposalLine->startdate);
	 else
		$itWeek = strtotime($proposalLine->startdate." previous monday");
	for($i=0;$i<$proposalLine->weeks;$i++)
	{
		$itStartMonday = mktime(0,0,0,date('m',$itWeek),date('d',$itWeek)+($i*7),date('Y',$itWeek));
		$itEndSunday = mktime(0,0,0,date('m',$itWeek),date('d',$itWeek)+($i*7)+6,date('Y',$itWeek));
		
		$propName = date("\wmdY",$itStartMonday);
		$weeksArr[] = array(
						'startdate'=> date("Y/m/d",max($itStartMonday,strtotime($proposalLine->startdate))) //($itStartMonday<strtotime($proposalLine->startdate))?$proposalLine->startdate:date("Y/m/d",$itStartMonday)
						,'enddate'=> date("Y/m/d",min($itEndSunday,strtotime($proposalLine->enddate))) //($itEndSunday<strtotime($proposalLine->enddate))?$proposalLine->enddate:date("Y/m/d",$itEndSunday)
						,'propName'=> $propName
						,'spots'=> $proposalLine->$propName 
					);
	}
	return $weeksArr;
}

function getDiscountedRate($discountpackage,$discountpackagetype,$discountagency,$rate,$totalSpots)
{
	$pDiscount = 0;
	$dRate = $rate;
	if($discountpackagetype==1)
	{
		$pDiscount = $discountpackage;
	}
	else if($discountpackagetype==2)
	{
		$pDiscount = ($discountpackage*100)/($rate*$totalSpots);
	}
		$pd = ($pDiscount/100)*$rate;
		$dRate = $rate - $pd;
	
	if($discountagency==1)
	{
		$dRate = $dRate - (0.15*$dRate);
	}
	return round($dRate,2);
}

function getTotalSpots($proposalLines)
{
	$totalSpots = 0;
	foreach($proposalLines AS $pl)
		$totalSpots += $pl->spots;

	return $totalSpots;
}

function getShowseekerNetTotal($gross,$discountpackage,$discountpackagetype,$discountagency)
{
	$tTotal = $gross;
	if($discountpackagetype==1)
	{
		$tTotal = $gross-($gross*$discountpackage*0.01);
	} else if($discountpackagetype==2)
	{
		$tTotal = $gross-$discountpackage;
	}
	
	if($discountagency==1)
	{
		$tTotal = $tTotal-( round($tTotal)*0.15);
	}
	return $tTotal;
}

?>

