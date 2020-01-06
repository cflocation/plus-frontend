<?php 	
require_once 'commons/initialize.php';
require_once 's3/upload.php';
define("XML_FOLDER","xmls/");

ini_set("display_startup_errors",0);
ini_set("display_errors",0);
//error_reporting(E_ALL);


if(isset($_GET['proposalid'])){
	$userId		    = trim(urldecode($_GET['userid']));
	$apiKey		    = trim(urldecode($_GET['key']));
	$proposalid		= trim(urldecode($_GET['proposalid']));
	$customer		= htmlspecialchars(trim(urldecode($_GET['customer'])));
	$salesperson	= htmlspecialchars(trim(urldecode($_GET['salesperson'])));
	$agency			= htmlspecialchars(trim(urldecode($_GET['agency'])));
	$ucBookend		= htmlspecialchars(trim(urldecode($_GET['ucBookend'])));
	$ulLength		= htmlspecialchars(trim(urldecode($_GET['ulLength'])));
	$revenueType	= (isset($_GET['revenuetype']))?htmlspecialchars(trim(urldecode($_GET['revenuetype']))):'';

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://plusapi.showseeker.com/proposal/load/{$proposalid}",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYHOST=> false,
		CURLOPT_SSL_VERIFYPEER=> false,
		CURLOPT_HTTPHEADER => array(
			"Api-key: {$apiKey}",
			"cache-control: no-cache",
			"User: {$userId}"
		),
	));

	$response = curl_exec($curl);
	$err      = curl_error($curl);
	curl_close($curl);
	$proposal = json_decode($response);

	$xml = new DOMDocument();
	$xml->preserveWhiteSpace = false;
	$xml->formatOutput       = true;
	
	$xmlOrders = $xml->createElement("Orders");
	$xmlOrder  = $xml->createElement("Order");
	
	$xmlOrder->appendChild($xml->createElement("ulOrderNumber","")); //
	$xmlOrder->appendChild($xml->createElement("szPONumber",""));
	$xmlOrder->appendChild($xml->createElement("szContractNumber",$proposal->id));
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

	$xmlCustomer     = $xml->createElement("Customer");
	$xmlszCustNumber = $xml->createElement("szCustNumber",$customer); //000011050
	$xmlCustomer->appendChild($xmlszCustNumber);
	$xmlOrder->appendChild($xmlCustomer);
	
	$xmlRemitToAddress = $xml->createElement("RemitToAddress");
	$xmlszName         = $xml->createElement("szName","");
	$xmlRemitToAddress->appendChild($xmlszName);
	$xmlOrder->appendChild($xmlRemitToAddress);
	
	$xmlSalesperson = $xml->createElement("Salesperson");
	$xmlszCode      = $xml->createElement("szCode",$salesperson); //BJ
	$xmlSalesperson->appendChild($xmlszCode);
	$xmlOrder->appendChild($xmlSalesperson);
	
	$xmlSalesOffice = $xml->createElement("SalesOffice");
	$xmlszOffice    = $xml->createElement("szOffice","");
	$xmlSalesOffice->appendChild($xmlszOffice);
	$xmlOrder->appendChild($xmlSalesOffice);
	
	$xmlAgency             = $xml->createElement("Agency");
	$xmlszAgencyCustNumber = $xml->createElement("szAgencyCustNumber",$agency); //000011026
	$xmlAgency->appendChild($xmlszAgencyCustNumber);
	$xmlOrder->appendChild($xmlAgency);
	
	$xmlOrder->appendChild($xml->createElement("RevenueType",$revenueType));
	$xmlOrder->appendChild($xml->createElement("szTypeCode",""));
	
	$xmlDiscount    = $xml->createElement("Discount");
	$agencyDiscount = ($proposal->agencyDiscount==1)?'0.15':'';
	$xmlDiscount->appendChild($xml->createElement("szablAgencyDiscount",$agencyDiscount));
	$xmlDiscount->appendChild($xml->createElement("szablRepDiscount",""));
	$xmlOrder->appendChild($xmlDiscount);
	
	$xmlOrderLines = $xml->createElement("OrderLines");

	//start adding lines to the XML
	$lineNum = 1;
	foreach($proposal->lines as $zone){
		foreach ($zone->lines as $line) {
    		$qry = "SELECT gci.networkCode, IF((SELECT z.hd FROM ShowSeeker.Zone as z WHERE z.id={$line->zoneId})>0,gci.networkCodeHD,'') AS networkCodeHD FROM Customers.GCIMapping as gci WHERE gci.tmsID = '{$line->stationId}'";
			$codesObj  = mysql_fetch_object(mysql_query($qry));
			$dateParts = getLineDateParts($line);  //will return line breakup, that if line needs splits it will return multiple start and end dates for each part

			foreach($dateParts As $part){
				if($part["totSpots"] > 0){
					$xmlOrderLines->appendChild(getOrderLineXml($xml,$lineNum++,$line,$part,$codesObj,$ulLength,$ucBookend));
				}
			}
		}
	}

	$xmlOrder->appendChild($xmlOrderLines);
	$xmlOrders->appendChild($xmlOrder);
	$xml->appendChild($xmlOrders);
	
	$name = str_replace(" ","_",strtolower(preg_replace('/[^\w\s]+/u','' ,trim(urldecode($proposal->name))))).".xml";
	$xml->save(XML_FOLDER.$name);
	
		//setup the s3 call
		$s3FileName = $name;
		$s3FilePath = realpath(XML_FOLDER.$name); //"/var/www/html/www.showseeker.com/goplus/downloads/xmls/$s3FileName";
		$s3Type = "eclipse";
		$s3UserId = $userId;

		if(checkS3()){
			//upload the file and get the full path
			$s3filePath = uploadToS3($s3FilePath,$s3FileName,$s3Type,$s3UserId);
	
			//unlink the local file
			unlink($s3FilePath); 
	
			print $s3filePath;
		}
		else{
			print 'https://plus.dev.showseeker.com/goplus/downloads/'.XML_FOLDER.$name;
		}
		
		return;		
	

}


//************************************* function definitions *************************************************************
//function to split lines if needed.
function getLineDateParts($line){
	$parts = [];

	if($line->lineType == 1){
		//fixed position line, no splits required
		$start   = date("m/d/Y",strtotime($line->weeks[0]->week));
		$end     = date("m/d/Y",strtotime("$start +6 days"));
		$parts[] = array("start"=>$start, "end"=>$end, "spots"=>$line->weeks[0]->spot, "totSpots"=>$line->weeks[0]->spot, "weeksOn"=>1, "weeksOff"=>0);

		return $parts;
	}

	if($line->lineType == 4){
		//line by day, split it for every active week
		foreach ($line->weeks as $week){
			$start   = date("m/d/Y",strtotime($week->week));
			$end     = date("m/d/Y",strtotime("$start +6 days"));
			$parts[] = array("start"=>$start, "end"=>$end, "spots"=>$week->spots, "totSpots"=>array_sum((array)$week->spots), "weeksOn"=>1, "weeksOff"=>0);
		}
		return $parts;
	}

	//Not a fixed position line or line by day. may or may not need splitting based on the pattern
	$hiddenWksArr = [];
	$spotsArr     = [];
	
	foreach ($line->weeks as $idx=>$week) {
		if($week->active == 0){
			$hiddenWksArr[] = $idx;
		}
		$spotsArr[] = $week->spot;
	}

	if(count($hiddenWksArr)==0 && (count(array_unique($spotsArr))==1 || $line->lineType == 5)){
		//there is no hidden week, and all spots have same weeks or its a line order(weekly spots do no matter), no split required
		$start   = date("m/d/Y",strtotime($line->weeks[0]->week));
		$end     = date("m/d/Y",strtotime(end($line->weeks)->week . "+6 days"));
		$spots   = ($line->lineType == 5) ? array_sum($spotsArr) : $line->weeks[0]->spot;
		$parts[] = array("start"=>$start, "end"=>$end, "spots"=>$spots, "totSpots"=>$spots*count($line->weeks), "weeksOn"=>count($line->weeks), "weeksOff"=>0);

		return $parts;
	}


	//splits are required
	$pattArr   = [];
	$pattIdx   = 0;
	$prevSpots = null;

	foreach ($line->weeks as $idx=>$week) {
		$currSpots = (($week->active==1)?$week->spot:'0');
		
		if($currSpots !== $prevSpots && $prevSpots !== null && $currSpots !== 0){
			$pattIdx += 1;
		}
		
		$pattArr[$pattIdx][] = $currSpots;
		$prevSpots           = $currSpots;
	}

	$partIdx    = -1;
	$wksElapsed = 0;
	
	foreach ($pattArr as $idx => $patt){
		if($idx != 0 && $pattArr[$idx-1]==$patt){
			//Pattern same as the last one, these can be combined
			$wksElapsed += count($patt);
			$end         = date("m/d/Y",strtotime($line->weeks[$wksElapsed-1]->week . "+6 days"));
			
			$parts[$partIdx]["end"] = $end;
		} else {
			$partIdx        += 1;
			$start           = date("m/d/Y",strtotime($line->weeks[$wksElapsed]->week));
			$wksElapsed     += count($patt);
			$end             = date("m/d/Y",strtotime($line->weeks[$wksElapsed-1]->week . "+6 days"));

			$activeWeeks = array_sum($patt)/$patt[0];
			$offWeeks    = count($patt)-$activeWeeks;
			$parts[$partIdx] = array("start"=>$start, "end"=>$end, "spots"=>$patt[0], "totSpots"=>$patt[0]*$activeWeeks, "weeksOn"=>$activeWeeks, "weeksOff"=>$offWeeks);
		}
	}

	return $parts;	
}




function getOrderLineXml($xml,$lineNum,$line,$part,$codesObj,$ulLength,$ucBookend){
	$xmlOrderLine = $xml->createElement("OrderLine");
	$xmlOrderLine->appendChild($xml->createElement("ucBookend",$ucBookend));
	$xmlOrderLine->appendChild($xml->createElement("abLineNumber",""));
	$xmlOrderLine->appendChild($xml->createElement("ulLineNumber",$lineNum));
	$xmlOrderLine->appendChild($xml->createElement("ulStartDate",$part["start"]));
	$xmlOrderLine->appendChild($xml->createElement("ulEndDate",$part["end"]));
	$xmlOrderLine->appendChild($xml->createElement("szCopyRotation","R"));
	$xmlOrderLine->appendChild($xml->createElement("szRotationGroup","")); //R
	$xmlOrderLine->appendChild($xml->createElement("ulWeeksOn",$part["weeksOn"]));
	$xmlOrderLine->appendChild($xml->createElement("ulWeeksSkip",$part["weeksOff"])); //0
	$xmlOrderLine->appendChild($xml->createElement("ucBuyType","U"));

	$priorityCode = determinePriorityCode($line->lineType,$line->startDate,$line->endDate, $ulLength,$part['spots'],$line->day,$line->rate);
	$xmlOrderLine->appendChild($xml->createElement("ulPriority",$priorityCode));

	$xmlOrderLine->appendChild($xml->createElement("ulStartTime",date("H:i:s",strtotime($line->startDate))));
	$xmlOrderLine->appendChild($xml->createElement("ulStopTime",date("H:i:s",strtotime($line->endDate))));
	
	$xmlInventoryType = $xml->createElement("InventoryType");
	$xmlInventoryType->appendChild($xml->createElement("szInvTypeCode","DEFAULT"));
	$xmlOrderLine->appendChild($xmlInventoryType);
	$xmlOrderLine->appendChild($xml->createElement("ulLength",$ulLength));
	
	$xmlQuantityType = $xml->createElement("QuantityType");
	if($line->lineType == 1 || $line->lineType == 4){
		$xmlQuantityType->appendChild($xml->createElement("ucQuantityType","D")); //Spots per day
	} else if($line->lineType == 5){
		$xmlQuantityType->appendChild($xml->createElement("ucQuantityType","L")); //Spots per Line
	} else {
		$xmlQuantityType->appendChild($xml->createElement("ucQuantityType","W")); //Spots per week
	}

	if($line->lineType == 5){
		$xmlQuantityType->appendChild($xml->createElement("usSpotsLine",$part["spots"]));
	} else if($line->lineType == 4){
		$xmlQuantityType->appendChild($xml->createElement("usSpotsWeek",$part["totSpots"]));		
	} else if($line->lineType != 1){
		$xmlQuantityType->appendChild($xml->createElement("usSpotsWeek",$part["spots"]));
	}

	if($line->lineType == 1){
		$xmlQuantityType->appendChild($xml->createElement("usMondayQty",$line->day==2?$part["spots"]:"0"));
		$xmlQuantityType->appendChild($xml->createElement("usTuesdayQty",$line->day==3?$part["spots"]:"0"));
		$xmlQuantityType->appendChild($xml->createElement("usWednesdayQty",$line->day==4?$part["spots"]:"0"));
		$xmlQuantityType->appendChild($xml->createElement("usThursdayQty",$line->day==5?$part["spots"]:"0"));
		$xmlQuantityType->appendChild($xml->createElement("usFridayQty",$line->day==6?$part["spots"]:"0"));
		$xmlQuantityType->appendChild($xml->createElement("usSaturdayQty",$line->day==7?$part["spots"]:"0"));
		$xmlQuantityType->appendChild($xml->createElement("usSundayQty",$line->day==1?$part["spots"]:"0"));
	} else if($line->lineType == 4){
		$weekDays = array(2 => "usMondayQty", 3 => "usTuesdayQty", 4 => "usWednesdayQty", 5 => "usThursdayQty", 6 => "usFridayQty", 7 => "usSaturdayQty", 1 => "usSundayQty");
		foreach ($weekDays as $key => $usDay)
			$xmlQuantityType->appendChild($xml->createElement($usDay,in_array($key,$line->day)?$part["spots"]->$key:"0"));
	} else {
		$xmlQuantityType->appendChild($xml->createElement("ucMonday",in_array(2,$line->day)?"Y":"N"));
		$xmlQuantityType->appendChild($xml->createElement("ucTuesday",in_array(3,$line->day)?"Y":"N"));
		$xmlQuantityType->appendChild($xml->createElement("ucWednesday",in_array(4,$line->day)?"Y":"N"));
		$xmlQuantityType->appendChild($xml->createElement("ucThursday",in_array(5,$line->day)?"Y":"N"));
		$xmlQuantityType->appendChild($xml->createElement("ucFriday",in_array(6,$line->day)?"Y":"N"));
		$xmlQuantityType->appendChild($xml->createElement("ucSaturday",in_array(7,$line->day)?"Y":"N"));
		$xmlQuantityType->appendChild($xml->createElement("ucSunday",in_array(1,$line->day)?"Y":"N"));
	}
	$xmlOrderLine->appendChild($xmlQuantityType);

	$xmlOrderLineRNS = $xml->createElement("OrderLineRNS");
	$xmlOrderLineRN  = $xml->createElement("OrderLineRN");
	
	
	if(in_array($line->zoneId, array('156')) && in_array($line->stationId, array(11062,16617,18480,19192))){ 
		#force add HD line for juneau for Root,,DEST,LMN
		$xmlOrderLineRN->appendChild($xml->createElement("szNetwork",trim($codesObj->networkCodeHD)));	
	} else {
		$xmlOrderLineRN->appendChild($xml->createElement("szNetwork",trim($codesObj->networkCode)));	
	}
	

	$xmlOrderLineRN->appendChild($xml->createElement("szRegionCode",$line->zoneAltCode));
	$xmlOrderLineRN->appendChild($xml->createElement("dRate",$line->rate));
	$xmlOrderLineRNS->appendChild($xmlOrderLineRN);
	$xmlOrderLine->appendChild($xmlOrderLineRNS);

	return $xmlOrderLine;
}



function determinePriorityCode($linetype,$starttime,$endtime, $ulLength,$spotsweek,$days,$rate){
	
	if($linetype==1){
		return 50;
	}

	$sTime = strtotime(date("H:i:s",strtotime($starttime)));
	$eTime = strtotime(date("H:i:s",strtotime($endtime)));

	if(($eTime-$sTime)<0){
		$showLength = (strtotime(date("H:i:s",strtotime($endtime))." +1 day")-$sTime)/(60*60);
	} else {
		$showLength = ($eTime-$sTime)/(60*60);
	}

	if(count($days)==1 && $showLength <= 2){
		return 50;
	} else if(count($days)==1 && $showLength <= 3){
		return 40;
	} else if( $sTime >= strtotime("06:00 AM") && $eTime <= strtotime("06:00 PM") && $showLength < 12 ){
		//10/11/2016 ref email subject "Priority Codes" Sent: Tuesday, October 11, 2016 12:09 PM FROM; Bryan Larson
		return 40;
	} else if(( $sTime >=strtotime("06:00 PM") && $sTime <=strtotime("10:00 PM")	&& $eTime >= strtotime("06:00 PM") && $eTime <= strtotime("10:00 PM")) 
			||( $sTime >=strtotime("10:00 PM") && $sTime <=strtotime("11:59 PM")	&& $eTime >= strtotime("10:00 PM") && $eTime <= strtotime("11:59 PM"))){
		return 30;
	}

	if($showLength == 24){
		return 10;
	} else {
		return 20;
	}
}


//AMAZON S3 CHECK
function checkS3(){
	$r = true;
	$host = 'showseeker.s3.amazonaws.com';
	if($socket =@ fsockopen($host, 80, $errno, $errstr, 30)) {
		fclose($socket);
	} 
	else {
		$r = false;
	}		
	return $r;
}