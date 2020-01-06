<?php


	include_once('../database.php');
	$eventtype = $_POST['eventtype'];
	$d = date('Y-m-d H:i:s');


	if($eventtype == "delete"){
		$userid 		= $_POST['userid'];
		$corporationid 	= $_POST['corporationid'];
		
		if(isset($_POST['adminid'])){
			$adminid= $_POST['adminid'];
			$sql 	= "UPDATE User SET deletedAt = '$d' WHERE id IN ($userids)";
		}	
		else{
			$sql = "UPDATE User SET deletedAt = '$d' WHERE id = $userid";
		}
		mysql_query($sql);
		print 1;
	}

	if($eventtype == "deletegroup"){

		$userids= $_POST['userids'];

		if(isset($_POST['adminid'])){
			$adminid= $_POST['adminid'];
			$sql = "UPDATE User SET deletedAt = '$d' WHERE id IN ($userids)";
		}	
		else{
			$sql = "UPDATE User SET deletedAt = '$d' WHERE id IN ($userids)";
		}
		
		mysql_query($sql);
		
		
		$sql = "DELETE FROM UserRole WHERE userid IN (".$userids.") and roleId = 19";
		mysql_query($sql);
		
		
		print 1;
	}	
	
	
	if($eventtype == "undodelete"){

		$userids= $_POST['userids'];

		$sql = "UPDATE User SET deletedAt = NULL WHERE id IN ($userids)";
		mysql_query($sql);
		
		print 1;
	}	
			
	


	if($eventtype == "deletealtaddress"){
		$userid 	= $_POST['userid'];
		$sql = "DELETE FROM UserAddress WHERE userId = $userid";
		mysql_query($sql);
		
		$sql = "DELETE FROM AddressDefault WHERE userId = $userid";
		mysql_query($sql);

		print 1;
	}


	if($eventtype == "update"){
		$id = $_POST['id'];

		//user table
		$firstname 		= mysql_real_escape_string($_POST['firstname']);
		$lastname 		= mysql_real_escape_string($_POST['lastname']);
		$title 			= $_POST['title'];
		$email 			= $_POST['email'];
		$phone 			= $_POST['phone'];
		$fax 			= $_POST['fax'];
		$cell 			= $_POST['cell'];
		$active 		= $_POST['active'];
		$override 		= $_POST['override'];
		$useroffices 	= $_POST['useroffices'];	
		$createdBy 		= $_POST['createdBy'];
		$ezgrids		= 0;
		$usergroups 	= array(15);
		
		if(isset($_POST['usergroups'])){
			$usergroups = $_POST['usergroups'];
		}

		foreach($usergroups as $val){
			if($val == '19'){
				$ezgrids	= 1;
			}
		}

		$sql = "UPDATE User SET firstName = '$firstname', 
				lastName 	= '$lastname', 
				title 		= '$title', 
				email 		= '$email', 
				phone 		= '$phone', 
				fax 			= '$fax', 
				mobile 		= '$cell', 
				active 		= $active, 
				updatedAt 	= '$d',
				createdBy	= '$createdBy'
				WHERE id = $id";
		mysql_query($sql);


		//user default address
		$address  	= $_POST['address'];
		$address2 	= $_POST['address2'];
		$city 	  	= $_POST['city'];
		$state 	  	= $_POST['state'];
		$zip 	  	= $_POST['zip'];

		$sql 		= "INSERT INTO UserAddress (userId,address,address2,city,stateId,zip) VALUES('{$id}','{$address}','{$address2}','{$city}','{$state}','{$zip}') 
					ON DUPLICATE KEY UPDATE address=VALUES(address), address2=VALUES(address2), city=VALUES(city), stateId=VALUES(stateId), zip=VALUES(zip) ";
		mysql_query($sql);


		//other offices
		$sql 			= "DELETE FROM UserOffice WHERE userId = $id";
		mysql_query($sql);

		//default office
		$defaultoffice 	= $_POST['defaultoffice'];
		$sql 			= "INSERT INTO UserOffice (userId, officeId, `default`, createdAt, updatedAt) VALUES ($id, $defaultoffice, 1, '$d', '$d')";
		mysql_query($sql);

		//loop over the records to add them in
		foreach ($useroffices as &$value) {
			$sql = "INSERT INTO UserOffice (userId, officeId, createdAt, updatedAt) VALUES ($id, $value, '$d', '$d') ON DUPLICATE KEY UPDATE officeId=VALUES(officeId)";
			mysql_query($sql);
		}

		//usergroups
		$usergroups = $_POST['usergroups'];
		$sql = "DELETE FROM UserRole WHERE userId = $id";
		mysql_query($sql);


		//loop over the records to add them in
		foreach ($usergroups as &$value) {
			$sql = "INSERT INTO UserRole (userId, roleId, createdAt, updatedAt) VALUES ($id, $value, '$d', '$d')";
			mysql_query($sql);
		}

		/*
		//ezbreaks premissions
		$ezbreakgroups = $_POST['ezbreakgroups'];
		$ezbreakgroups = implode('|', $ezbreakgroups);

		$sql = "INSERT INTO permissionbreakuser (userid, groups, createdat, updatedat) VALUES ($id, '$ezbreakgroups', '$d', '$d') ON DUPLICATE KEY UPDATE groups=VALUES(groups), updatedat=VALUES(updatedat);";
		$re  = mysql_query($sql,$oldDb);
		//permissionbreakuser

		if($ezgrids == 0){
			$ez = ezGridsDel($id);
		}
		if($ezgrids == 1){
			$ez = ezGridsAdd($id);
		}*/
	}



	if($eventtype == "create"){

		$corporationid = $_POST['corporationid'];

		//user table
		$password 	= trim($_POST['password']);
		$firstname 	= mysql_real_escape_string(ucwords(trim($_POST['firstname'])));
		$lastname 	= mysql_real_escape_string(ucwords(trim($_POST['lastname'])));
		$title 		= $_POST['title'];
		$email 		= trim($_POST['email']);
		$phone 		= trim($_POST['phone']);
		$fax 		= $_POST['fax'];
		$cell 		= $_POST['cell'];
		$active 	= $_POST['active'];
		//$usergroups = $_POST['usergroups'];
		$override 	= $_POST['override'];
		$ezgrids	= 0;
		$createdBy 	= $_POST['createdBy'];

		/*foreach($usergroups as $val){
			if($val == '19')
				$ezgrids	= 1;
		}*/

		//check email
		$sql 		= "SELECT * FROM User WHERE email = '$email' and corporationid = 46";
		$result 	= mysql_query($sql);
		$num_rows 	= mysql_num_rows($result);
		

		if($num_rows > 0 && $override > 0){
			
			$sql = "UPDATE 		User 
					SET 		override 	= '1', 
								updatedAt 	= '$d' 
					WHERE 		email 		= '$email'";;
			mysql_query($sql);
		}
		elseif($num_rows > 0){
			print 0;
			return;			
		}

		$uuid = gen_uuid();

		$initials = substr($firstname,0,1).substr($lastname,0,1);
		$sql = "INSERT INTO User (apiKey, corporationId, firstName, lastName, title, email, phone, fax, mobile, active, createdAt, updatedAt,initials,createdBy) 
		VALUES ('$uuid', $corporationid, '$firstname', '$lastname', '$title', '$email', '$phone', '$fax', '$cell', $active, '$d', '$d','$initials','$createdBy')";
		mysql_query($sql);

		//get the new ID of the user
		$userid = mysql_insert_id();


		//user default address
		$address = $_POST['address'];
		$address2 = $_POST['address2'];
		$city = $_POST['city'];
		$state = $_POST['state'];
		$zip = $_POST['zip'];

		$sql = "INSERT INTO UserAddress (userId, address, address2, city, stateId, zip) VALUES ($userid, '$address', '$address2', '$city', '$state', '$zip')";
		mysql_query($sql);

		//loop over the records to add them in
		$useroffices = $_POST['useroffices'];
		foreach ($useroffices as &$value) {
			$sql = "INSERT INTO UserOffice (userId, officeId, createdAt, updatedAt) VALUES ($userid, $value, '$d', '$d')";
			mysql_query($sql);
		}

		//default office
		$defaultoffice = $_POST['defaultoffice'];
		$sql = "UPDATE UserOffice SET `default`=1 WHERE officeId=$defaultoffice AND userId=$userid ";
		mysql_query($sql);

		//loop over the records to add them in
		$usergroups = $_POST['usergroups'];
		foreach ($usergroups as &$value) {
			$sql = "INSERT INTO UserRole (userId, roleId, createdAt, updatedAt) VALUES ($userid, $value, '$d', '$d')";
			mysql_query($sql);
		}
		
		if($ezgrids == 1){
			$ez = ezGridsAdd($userid);
		}

		/*$sql = "INSERT INTO permissionbreakuser (userid, createdat, updatedat) VALUES ($userid, '$d', '$d')";
		mysql_query($sql, $oldDb);*/
		
		$sql = "SELECT email,id,firstName FROM User WHERE id=$userid";
		header("Content-type: application/json; charset=utf-8");
		print json_encode(mysql_fetch_assoc(mysql_query($sql)));
		exit;
	}





	function ezGridsAdd($userid){
			
		
		$sql = "INSERT INTO UserRole (userId,roleId) select '".$userid."', '19'";
		$result = mysql_query($sql);


		$sql = "	SELECT 		Market.id AS marketid
					FROM 		User
					INNER JOIN  userOffice 
					ON 			User.id = userOffice.userId
					INNER JOIN  Office 
					ON 			userOffice.officeId = Office.id
					INNER JOIN 	Market 
					ON 			Office.regionId = Market.id
					WHERE 		User.id = ". $userid;

		$result 		= mysql_query($sql);
		$marketid	= 0;
		
		while($row = mysql_fetch_assoc($result)) {
			$marketid = $row['marketid'];
		}	


		$sql 			= "INSERT INTO  ez_grids_users (userid,marketid) SELECT '".$userid."', '".$marketid."'";
		$result 		= mysql_query($sql);

		
	}


	function ezGridsDel($userid){
					
		$sql = "DELETE FROM UserRole WHERE userId = $userid and roleId = 19";
		$result = mysql_query($sql);

		$sql = "	SELECT 		Market.id AS marketid
					FROM 		User
					INNER JOIN  userOffice 
					ON 			User.id = userOffice.userId
					INNER JOIN  Office 
					ON 			userOffice.officeId = Office.id
					INNER JOIN 	Market 
					ON 			Office.regionId = Market.id
					WHERE 		User.id = ". $userid;

		$result 	= mysql_query($sql);
		$marketid	= 0;
		
		while($row = mysql_fetch_assoc($result)) {
			$marketid = $row['marketid'];
		}	

		$sql 			= "DELETE FROM  ez_grids_users where userid = ".$userid." and marketid = ".$marketid;
		print_r($sql);
		$result 		= mysql_query($sql);

		
	}








	return;
	$id = $_GET['id'];
	$t = $_GET['t'];


	include_once('../config/database.php');


	if($t == 'all'){
		$sql = "SELECT users.firstname AS firstname, users.lastname AS lastname, users.id AS userid 
		FROM users 
		WHERE corporationid = ".$id." AND users.deletedat IS NULL
		ORDER BY users.firstname ASC";
	}

	if($t == 'market'){
		$sql = "SELECT offices.name AS office, useroffices.userid AS userid, users.firstname AS firstname, users.lastname AS lastname
		FROM offices
		INNER JOIN useroffices ON offices.id = useroffices.officeid
		INNER JOIN users ON useroffices.userid = users.id
		WHERE offices.regionid = ".$id." AND users.deletedat IS NULL";
	}

	if($t == 'office'){
		$sql = "SELECT offices.name AS office, useroffices.userid AS userid, users.firstname AS firstname, users.lastname AS lastname
		FROM offices
		INNER JOIN useroffices ON offices.id = useroffices.officeid
		INNER JOIN users ON useroffices.userid = users.id
		WHERE useroffices.officeid = ".$id." AND  users.deletedat IS NULL";
	}
	
	$result = mysql_query($sql);
    
    $cnt = mysql_num_rows($result);

    if($cnt == 0){	
    	$re[] = 0;
    	print json_encode($re);
    	return;
    }

	while($row = mysql_fetch_assoc($result)) {
		$rows[] = $row;
	}

    print json_encode($rows);







function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

?>



