<?php
session_start();
ini_set("display_errors",0);

include_once('../include/database.php');

$userId = $_POST['userId'];
$apiKey = $_POST['apiKey'];

$sql = "SELECT     User.id, User.corporationId, User.apiKey AS tokenId, User.firstName, User.lastName,
                   md5(CONCAT(User.id,User.apiKey)) AS apiKey, 
				   Corporation.name AS corporation
		FROM       User
		INNER JOIN Corporation ON Corporation.id=User.corporationId
		WHERE      User.id={$userId}
		AND        md5(CONCAT(User.id,User.apiKey)) = '{$apiKey}' 
		LIMIT  1";
$res = mysql_query($sql);
$cnt = mysql_num_rows($res);

if($cnt > 0){
	$row = mysql_fetch_assoc($res);
	
	$_SESSION['userid']        = $row['id'];
    $_SESSION['name']          = $row['firstName'];
    $_SESSION['corporationid'] = $row['corporationId'];
    $_SESSION['corporation']   = $row['corporation'];
    $_SESSION['tokenid']       = $row['tokenId'];
    $_SESSION['apikey']        = $row['apiKey'];
    $_SESSION['roles']         = getRoles($row['id']);
    $expire                    = time()+60*60*24*30;
    
    setcookie("userid", $row['id'], $expire,  "/");
    setcookie("tokenid", $row['tokenId'], $expire,  "/");
    setcookie("apikey", $row['apiKey'], $expire,  "/");

	$row['location'] = '/plus';
	header('Content-Type:application/json');
	print json_encode($row);
} else {
	header('Content-Type:application/json');
	print json_encode(array('id'=>0));
}






function getRoles($userId){
    $sql    = "SELECT roleId as roleid FROM UserRole WHERE userId = $userId";
    $result = mysql_query($sql);

    //loop over and add to list
    while($row = mysql_fetch_assoc($result)) {
        $data[] = $row;
    }
    return  $data;
}