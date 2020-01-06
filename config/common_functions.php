<?php
	
	function isApiKeyValid($corporationId, $apiKey)
	{
		$query =" SELECT id FROM ShowSeeker.corporations WHERE id={$corporationId} AND apikey = '{$apiKey}'";
		$res = mysql_query($query);
		return (mysql_num_rows($res) != 1)?false:true;
	}

	function getBreakStationId($networkid)
	{
		return $networkid;
		// returning same id as this mapping is no longer needed and all refrences to this function have to be removed. 

		$query = "SELECT altid FROM networkmapping WHERE id=$networkid ";
		$res   = mysql_query($query);
		if(mysql_num_rows($res) >0 )
		{
			$row   = mysql_fetch_object($res);
			$altid = $row->altid;
			if($altid != 0)
				return $altid;
			else
				return $networkid;
		} else
		{
			return $networkid;
		}


		/*

		switch ($networkid) {
			case 14771: $breakNetworkId = 21762; break;
			case 10021: $breakNetworkId = 31556; break;
			case 14321: $breakNetworkId = 14753; break;
			case 10986: $breakNetworkId = 10987; break;
			case 11867: $breakNetworkId = 34240; break;
			case 11207: $breakNetworkId = 11208; break;
			case 10138: $breakNetworkId = 27203; break;
			case 11221: $breakNetworkId = 19933; break;
			case 21484: $breakNetworkId = 21744; break;
			case 11163: $breakNetworkId = 19002; break;
			case 11097: $breakNetworkId = 24533; break;
			case 11158: $breakNetworkId = 19543; break;
			case 11180: $breakNetworkId = 50000; break;
			case 10149: $breakNetworkId = 10150; break;
			case 10093: $breakNetworkId = 12499; break;
			case 10051: $breakNetworkId = 24483; break; //Added by Asif For BET - 05/25/2014
			case 18793: $breakNetworkId = 18279; break; //Added by Asif For DXD - 05/28/2014
			case 10918: $breakNetworkId = 10919; break; //Added by Asif For LIFE - 06/05/2014
			case 10918: $breakNetworkId = 10919; break; //Added by Asif For LIFE - 06/05/2014
			case 10035: $breakNetworkId = 21760; break; //Added by Asif For LIFE - 06/05/2014

			default: $breakNetworkId = $networkid; break;
		}

		return $breakNetworkId;	*/	
	}

	function getTimezoneIdentifier($tz)
	{
		switch($tz)
		{
			case 'ast': return "US/Alaska";   break;
			case 'cst': return "US/Central";  break;
			case 'est': return "US/Eastern";  break;
			case 'mdt': 
			case 'mst': return "US/Mountain"; break;
			case 'pst': return "US/Pacific";  break;

			default : return false; break;
		}
	}
?>