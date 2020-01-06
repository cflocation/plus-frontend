<?php 
$userId  = $_GET['id'];
$tokenId = $_GET['tokenId'];

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://plusapi.showseeker.com/user/apikey",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode(array("userId"=>$userId,"tokenId"=>$tokenId)),
  CURLOPT_HTTPHEADER => array("cache-control: no-cache","content-type: application/json"),
));

$response = curl_exec($curl);
$err      = curl_error($curl);
curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  $resp = json_decode($response);
  if($resp && $resp->cnt > 0){
  	print '<script type="text/javascript">
  			localStorage.setItem("userId", '.$resp->id.');
            localStorage.setItem("apiKey", "'.$resp->apiKey.'");
            window.location.href="../index.php?admin=on";
  		</script>';
  	} else {
  		print "Invalid User!";
  	}
}