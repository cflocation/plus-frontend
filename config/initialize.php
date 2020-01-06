<?php
	//$dbHost	= '61b1ed95616f65903ff311cc9decad51aa4cac3d.rackspaceclouddb.com';
	$dbHost		= 'db4.showseeker.net';
	$dbUserName	= 'devDBAUserSSDBOR';
	$dbPassWord	= 'avcZ5j26yU4EyqB66RmfcjfuPGwDkBLUNnZe8MM2UBuw3k';
	$dbName		= 'ShowSeeker';
	
	date_default_timezone_set ('America/Los_Angeles');
	

	function json_format($arg)
	{
		return json_encode($arg);
	}
	
	function character_encoding_safe_json($argDataArr)
	{
		array_walk_recursive($argDataArr, function(&$item, $key) {
			if(is_string($item)) {
				$item = htmlentities($item);
			}
		});
		return html_entity_decode(json_encode($argDataArr));
	}
/*
function my_json_encode($arr)
{
	//convmap since 0x80 char codes so it takes all multibyte codes (above ASCII 127). So such characters are being "hidden" from normal json_encoding
	array_walk_recursive($arr, function (&$item, $key) { if (is_string($item)) $item = mb_encode_numericentity($item, array (0x80, 0xffff, 0, 0xffff), 'UTF-8'); });
	return mb_decode_numericentity(json_encode($arr), array (0x80, 0xffff, 0, 0xffff), 'UTF-8');
	
}*/
	
	require_once 'databasei.php';
?>