<?php
	function solrUrlGenre($startDate,$endDate,$startTime,$endTime,$daysofweek,$networks){
		$url = 'http://solr.showseeker.net:8983/solr/gracenote/select/?q=*%3A*&version=2.2&rows=500&start=0&indent=on&wt=json&fq=-sort:"Paid Programming"&fq=projected:0&fq=-genre1:"consumer"&fq=-genre2:"consumer"&group=true&group.field=genre1&fl=genre1,genre2&sort=genre1 asc';
		$url.=solrNetworkFormatter($networks);
		$url.=daysOFWeek($daysofweek);
		return $url;
	}

	//networks
	function solrNetworkFormatter($networks){
		$re = "&fq=";
		foreach ($networks as &$value) {
    		$val = $value;
    		$re.="stationnum:$val+";
		}
		return $re;
	}

	//days
	function daysOFWeek($days){
		$days = explode(" ", $days);
		if(count($days) == 7){
			return;
		}
	}

?>