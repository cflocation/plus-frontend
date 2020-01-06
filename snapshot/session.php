<?php
session_start();
include_once('../config/database.php');

ini_set("display_errors", 1);
error_reporting(E_ALL);

$userId = $_POST['id'];
$apiKey = $_POST['apiKey'];

$sql    = "SELECT users.id AS id, users.firstname AS firstname, users.lastname AS lastname, corporations.id AS corporationid, corporations.apikey AS apikey, corporations.name AS corporation, users.tokenid, users_default.location
			FROM users
			INNER JOIN userroles ON userroles.userid = users.id
			INNER JOIN corporations ON corporations.id = users.corporationid
			LEFT OUTER JOIN users_default ON users_default.usersid = users.id 
			WHERE users.id = '$userId' AND users.deletedat is null AND corporations.deletedat is null LIMIT 1";
$result = mysql_query($sql);
$cnt    = mysql_num_rows($result);
$row    = mysql_fetch_assoc($result);


if($cnt > 0){
	$_SESSION['userid']        = $row['id'];
	$_SESSION['name']          = $row['firstname'];
	$_SESSION['corporationid'] = $row['corporationid'];
	$_SESSION['corporation']   = $row['corporation'];
	$_SESSION['tokenid']       = $row['tokenid'];
	$_SESSION['roles']         = getRoles($row['id']);
	$_SESSION['apikey']        = $apiKey;

    $expire	= time()+60*60*24*30*5;
    
    setcookie("userid", $row['id'], $expire,  "/");
    setcookie("tokenid", $row['tokenid'], $expire,  "/");
    setcookie("apikey", $apiKey, $expire,  "/");

    print json_encode(array("result"=>true));
} else {
    print json_encode(array("result"=>false));
}
return;





function getRoles($userid){
	$sql    = "SELECT roleid FROM userroles WHERE userid = $userid";
	$result = mysql_query($sql);
	while($row = mysql_fetch_assoc($result)) {
		$data[] = $row;
	}
	return  $data;
}