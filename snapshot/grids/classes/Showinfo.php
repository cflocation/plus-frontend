<?php
	
class Showinfo{
	
	public function __construct(){
	}	
	
	
	public function getShowInfo($id,$altId,$isjson){
		
		$curl_arr 	= array();
		$master 	= curl_multi_init();

		$solrUrl = "http://snapshot.prod.showseeker.com:8983/solr/snapshot/select/?q=*%3A*&version=2.2&start=0&indent=on&wt=json&rows=1";
		$solrUrl = $solrUrl."&fl=id,callsign,showtype,showid,tmsid,live,genre,stars,descembed,orgairdate,title,premierefinale,new,epititle,duration&";
		$solrUrl = $solrUrl."fq=id:{$id}";
		$solrUrl = preg_replace("/ /", "%20",$solrUrl);

		$curl_arr[0] = curl_init($solrUrl); //
		curl_setopt($curl_arr[0], CURLOPT_RETURNTRANSFER, true);//
		curl_multi_add_handle($master, $curl_arr[0]); //

		do {
			curl_multi_exec($master,$running);
		} while($running > 0);

		$results = curl_multi_getcontent( $curl_arr[0] );
		
		$dataArr = json_decode($results)->response->docs;

		if(count($dataArr)==0){

			$solrUrl = "http://snapshot.prod.showseeker.com:8983/solr/snapshot/select/?q=*%3A*&version=2.2&start=0&indent=on&wt=json&rows=1";
			$solrUrl = $solrUrl."&fl=id,callsign,showtype,showid,tmsid,live,genre,stars,descembed,orgairdate,title,premierefinale,new,epititle,duration&";
			$solrUrl = $solrUrl.'fq=id:"'.$altId.'"';
			$solrUrl = preg_replace("/ /", "%20",$solrUrl);
	
			/*print_r('<pre>');
			print_r($solrUrl);
			exit;*/
	
			$curl_arr[0] = curl_init($solrUrl); //
			curl_setopt($curl_arr[0], CURLOPT_RETURNTRANSFER, true);//
			curl_multi_add_handle($master, $curl_arr[0]); //
	
			do {
				curl_multi_exec($master,$running);
			} while($running > 0);
	
			$results = curl_multi_getcontent( $curl_arr[0] );
			
			$dataArr = json_decode($results)->response->docs;

		}
		
		

		if($isjson)
			return json_encode($dataArr);

		return  $dataArr;
		
	}
}