<?php
	ini_set("display_errors",1);
	session_start();
	$nonceIn = time();
	$messageUrl = "https://plus.showseeker.com/auth/message/";




	//if we dont have what we need to log the user in then return -1
	if(!isset($_GET["key"]) || !isset($_GET["nonce"]) || !isset($_GET["signature"])){
		print $messageUrl."?code=3";
		return;
	}

	//if the post takes more then 100 secons it fails
	$timer = $nonceIn - $_GET["nonce"];
	if($timer > 80){
		print $messageUrl."?code=2";
		//header('Location:'.$messageUrl."?code=2");
		return;
	}

	//setup the shared key and corp id
	$secret = "66ed2238cafb47c491ba6f0d9bf4e215";
	$corpId = 15;

	//set the pass varibles
	$email = $_GET['key'];
	$signature = $_GET['signature'];
	$nonce = $_GET["nonce"];

	//build a valid key based on the input
	$message = (string)$email.(string)$nonce;
	$signatureValid = strtoupper(hash("sha256",$secret.$message));


	$domain = explode("@", $email);
	$email2 = $domain[0]."@charter.com";
	$domain = $domain[1];

	if($signature != $signatureValid){
		print $messageUrl."?code=1";
		//header('Location:'.$messageUrl."?code=1");
		return;
	}

	//if the doamin is not one of these return
	$domainList = array("charter.com","spectrum.com","showseeker.com");
	if(!in_array($domain, $domainList)){
		print $messageUrl."?code=4";
		//header('Location:'.$messageUrl."?code=4");
		return;
	}

	//include the database
	include '../../../config/mysqli.php';

    $sql = "SELECT 
    users.id AS id,
    users.firstname AS firstname,
    users.lastname AS lastname, 
    corporations.id AS corporationid, 
    corporations.apikey AS apikey,
    corporations.name AS corporation,
    users.tokenid, 
    users_default.location	
    FROM users
    INNER JOIN userroles ON userroles.userid = users.id
    INNER JOIN corporations ON corporations.id = users.corporationid
	LEFT OUTER JOIN users_default ON users_default.usersid = users.id 
    WHERE (users.email = '$email' OR users.email = '$email2')
    AND corporations.id = '$corpId'
	AND users.deletedat is null
	AND corporations.deletedat is null
    LIMIT 1";

	$result = mysqli_query($con,$sql);
	$row = mysqli_fetch_assoc($result);

    if($result->num_rows > 0){
    	$id = $row['id'];
    	$tokenid = $row['tokenid'];

		$url = "https://plus.showseeker.com/goplus/auth/?id=$id&tokenId=$tokenid";
		//header('Location:'$url);
       	print_r($url);
       	return;
    }
    else{
    	print $messageUrl."?code=5";
    	//header('Location:'.$messageUrl."?code=5");
       	return;
    }

	function getRoles($con,$userid){
		$sql = "SELECT roleid FROM userroles WHERE userid = $userid";
		$result = mysqli_query($con,$sql);

		//loop over and add to list
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		return  $data;
	}

?>

