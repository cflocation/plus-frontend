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
		
		print_r($proposalLines);
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
		//$totalPDiscount = ($discountpackage/100) *($rate*$totalSpots); //##
	}
	else if($discountpackagetype==2)
	{
		$pDiscount = ($discountpackage*100)/($rate*$totalSpots);
		//$totalPDiscount = $discountpackage; //##
	}
		$pd = ($pDiscount/100)*$rate;
		$dRate = $rate - $pd;
		//$pdr = $totalPDiscount /$totalSpots; //##
		//$dRate = $rate - $pdr;  //##
	
	if($discountagency==1)
	{
		$dRate = $dRate - (0.15*$dRate);
		//$adr = 0.15 * (($rate*$totalSpots)-$totalPDiscount);
		//$tArr = explode('.',$adr);
		//$adr = $tArr[0].".".substr($tArr[1],0,1);
		//$aDiscount = $adr/$totalSpots;  //##
		//$aDiscount = (floor((0.15 * (($rate*$totalSpots)-$totalPDiscount))*10)/10)/$totalSpots;  //##
		//$dRate = $dRate - $aDiscount;  //##
	}
	
	//$net = ($rate*$totalSpots)-$totalPDiscount-$adr;

	//$tArr = explode('.',$dRate);
	//$newR = $tArr[0].".".substr($tArr[1],0,2);
	//return $newR;
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

