<?php	ini_set("display_errors",0);	$userid = $_GET['userid'];	$authtokin = $_GET['tokenid'];	//$authorized = $_GET['authorized'];	//if there is anything blank return an error	if(empty($authtokin) || empty($userid)){		exit('error');	}		include_once('../../config/mysqli.php');			//Authentication		require_once('../../classes/Auth.php');		$auth = new Auth($con);		//include_once('../../config/mysqli.php');		$sql	 	= "select id, longname as name from demographics order by id";		$result	= mysqli_query($con, $sql);		$demos 	= array();		while($row = mysqli_fetch_assoc($result)){			$demos[] = $row;		}					print_r(json_encode($demos));				?>