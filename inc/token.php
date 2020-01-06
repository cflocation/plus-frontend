<?php
	if(!isset($_GET['userid']) || (isset($_GET['userid']) && $_GET['userid'] == '')) exit('-1');
	else $userid = trim($_GET['userid']);

	if(!isset($_GET['tokenid']) || (isset($_GET['tokenid']) && $_GET['tokenid'] == '')) exit('-1');
	else $tokenid = trim($_GET['tokenid']);

	$sql = "SELECT id as cnt FROM users WHERE id ='$userid' AND tokenid = '$tokenid'";
	$result = mysqli_query($con, $sql);
	
	if($result->num_rows == 0){
		exit('-1');
	}
?>