<?php
include_once('../config/database.php');

ini_set("display_errors", 'On');
error_reporting(E_ALL);

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
	header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	header('Access-Control-Allow-Credentials: true');
	header('Access-Control-Max-Age: 86400');
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}


$corpId = 27; //$_GET['corpId'];
$sql    = "SELECT zonenetworks.networkid 
			FROM zones 
			INNER JOIN zonenetworks ON zonenetworks.zoneid=zones.id
			WHERE isdma='NO' "; //zones.corporationid={$corpId} AND zones.id=9112 //AND 
$res    = mysql_query($sql);
$nets   = array();

while ($row=mysql_fetch_object($res))
	$nets[] = intval($row->networkid);

header('Content-Type: application/json');
print json_encode($nets);