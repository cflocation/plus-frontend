<?php
	class Url{

		//init class file
		public function __construct(){
			$this->now = date('Y-m-d H:i:s');
		}


		public function urlPost($uri,$params){
			$postData = '';
			foreach($params as $k => $v){
		  		$postData .= $k . '='.$v.'&';
			}
			rtrim($postData, '&');
			$ch = curl_init(); 
			curl_setopt($ch,CURLOPT_URL,$uri);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($ch,CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_POST, count($postData));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);   
			$output=curl_exec($ch);
			curl_close($ch);
			return $output;
		}


	}
?>