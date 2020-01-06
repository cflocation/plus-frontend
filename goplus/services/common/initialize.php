<?php
	//$dbHost		= '61b1ed95616f65903ff311cc9decad51aa4cac3d.rackspaceclouddb.com';
	$dbHost		= 'db4.showseeker.net';
	$dbUserName	= 'vastdbuser';
	$dbPassWord	= 'jK6YK71tJ';
	$dbName		= 'ShowSeeker';
	
	date_default_timezone_set ('America/Los_Angeles');
	

	function json_format($arg){
		return json_encode($arg);
	}
	
	function character_encoding_safe_json($argDataArr){
		array_walk_recursive($argDataArr, function(&$item, $key) {
			if(is_string($item)) {
				$item = htmlentities($item);
			}
		});
		return html_entity_decode(json_encode($argDataArr));
	}
	
	
	function is_token_valid($argUserId,$argToken){
		global $db;
		$result = $db->fetch_result("SELECT count(id) as cnt FROM users WHERE id =$argUserId");
		return ($result[0]['cnt']==1)?true:false;
	}

	function getUsersAvailableAppVersionInfo($userId, $platform){
		global $db;

		$tableName = ($platform =="ipad")?"iseeker_ipad_versions":"iseeker_iphone_versions";
		$fkeyname  = ($platform =="ipad")?"iseekeripadversionid":"iseekeriphoneversionid";
		$filePath  = "http://services.showseeker.com/release/";
		$filePath .= ($platform =="ipad")?"ipad/":"iphone/";
		$sql = "SELECT ver.version AS latestVersion, CONCAT('{$filePath}',ver.linkfile) as link FROM {$tableName} AS ver inner join users AS u on u.{$fkeyname} = ver.id WHERE u.id={$userId} AND ver.deletedat IS NULL";
		$result = $db->fetch_result($sql);

		if(!(is_array($result)) || count($result) != 1)
			return array('latestVersion'=>"", 'link'=>"");
		else 
			return $result[0];
	}

	function execute_mysqli_logquery($logging){
		GLOBAL $dbUserName, $dbPassWord;
		$con = mysqli_connect("db4.showseeker.net",$dbUserName,$dbPassWord,"logs");
		mysqli_query($con, $logging);
	}

	require_once 'common/database.php';
?>