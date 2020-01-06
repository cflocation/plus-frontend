<?php
	header('Content-type: application/json');

	$url = $_GET["url"];
	$tz  = $_GET["tz"];
	$arr = array();
	$re;

	//remove uneeded data
	$data = file_get_contents($url);
	$json = json_decode($data);
	$show = $json->KEYS;
	
	foreach($show as &$showid){
		foreach($json->DATA->$showid->MARATHONIDS as &$marathonid){
			$arr[]=$marathonid;
		}
	}

	//split the array into smaller pieces
	$chuncked = array_chunk($arr,100);
	
	$i = 0;
	foreach($chuncked as &$chunk){
		$shows = doSearch($chunk,$tz);
		
			if ($i == 0) {
				$re = $shows;
			}else{
				foreach($shows->response->docs as &$show){
					array_push($re->response->docs, $show);
				}
			}
		$i++;
	}
	
	print json_encode($re);	
	return;
	
	


	//print_r(json_encode($response));


	function doSearch($rows,$tz){
		$arr = '';
		$options = array(
		    'hostname' => 'solr.showseeker.net',
		    'port'     => 8983,
		);
		
		//set the basic solr info
		$client = new SolrClient($options);
		$query 	= new SolrQuery();
		$query->setQuery('*:*');;
		$query->setRows(8000);
		$query->addField('day_'.$tz);
		$query->addField('callsign');
		$query->addField('descembed');
		$query->addField('duration');
		$query->addField('epititle');
		$query->addField('genre1');
		$query->addField('genre2');
		$query->addField('id');
		$query->addField('isnew');
		$query->addField('new');
		$query->addField('live');
		$query->addField('search');
		$query->addField('showid');
		$query->addField('stars');
		$query->addField('start_'.$tz);
		$query->addField('stationname');
		$query->addField('stationnum');
		$query->addField('rating');
		$query->addField('showtype');
		$query->addField('premierefinale');;
		$query->addField('tmsid');
		$query->addField('title');
		$query->addField('tvrating');
		$query->addField('tz_end_'.$tz);
		$query->addField('tz_start_'.$tz);

		foreach($rows as &$row){	
			$arr.='id:'.$row.' ';
		}

		$arr = rtrim($arr," ");

		$query->addFilterQuery($arr);
		$query_response = $client->query($query);
		$response = $query_response->getResponse();

		return $response;
	}

?>