<?php
  ini_set("display_errors",1);

  $userid = $_POST['userid'];
  $authtokin = $_POST['tokenid'];
  $calendar = $_POST['calendar'];

  //posted settings
  $name = $_POST['name'];
  $rows = $_POST['rows'];

  //if there is anything blank return an error
  if(empty($authtokin) || empty($userid)){
    exit('error');
  }

  include_once('../../config/mysqli.php');

  //Authentication
  require_once('../../classes/Auth.php');
  $auth = new Auth($con);
  $url = $_SERVER['PHP_SELF'];
  $key = $auth->checkAuth($url,$authtokin,$userid);


  if(!$key){
    print "Access denied - You are not authorized to access this page.";
    exit;
  }
  //set the token id for the user
  $tokenid = $key;

  //include database
  include_once('../../config/database.php');
	
	$ids = $rows;
	$title = '';
	$data = array();
	
	
	$dtime = date('Y-m-d H:i:s');
	$d = date('Y-m-d H:i:s');

	$sql = "SELECT * FROM proposals WHERE id IN ($ids)";
	$result = mysql_query($sql);
	
	
	while($row = mysql_fetch_array($result))
  	{

  		$title = mysql_real_escape_string($name);
  		$proposal = mysql_real_escape_string($row['proposal']);

  		$discountagency = $row['discountagency'];
  		$discountpackage = $row['discountpackage'];
  		$discountpackagetype = $row['discountpackagetype'];


  		$spots = $row['spots'];
  		$grossttl = $row['grossttl'];
  		$netttl = $row['netttl'];
  		$agcdisc = $row['agcdisc'];
  		$pkgsdisc = $row['pkgsdisc'];
  		$linesttl = $row['linesttl'];
  		$zones = $row['zones'];




		if($row['startdate'] == ""){
			$startdate = 'NULL';
		}else{
			$startdate = "'".$row['startdate']."'";
		}

		if($row['enddate'] == ""){
			$enddate = 'NULL';
		}else{
			$enddate = "'".$row['enddate']."'";
		}

  	$sql = "INSERT INTO proposals (userid, 
  										name, 
  										proposal, 
  										discountagency, 
  										discountpackage, 
  										discountpackagetype,
  										spots,
  										grossttl,
  										netttl,
  										agcdisc,
  										pkgsdisc,
  										linesttl,
  										startdate,
  										enddate,
  										zones,
  										calendar,
  										createdat, 
  										updatedat)
  		VALUES ({$userid},
  				'{$title}',
  				'{$proposal}',
  				'{$discountagency}',
  				'{$discountpackage}',
  				'{$discountpackagetype}',
  				'{$spots}',
  				'{$grossttl}',
  				'{$netttl}',
  				'{$agcdisc}',
  				'{$pkgsdisc}',
  				'{$linesttl}',
  				{$startdate},
  				{$enddate},
  				'{$zones}',
  				'{$calendar}',
  				'{$dtime}',
  				'{$dtime}')";
  		mysql_query($sql);
  	}
  	
	$re = mysql_insert_id();

	print $re;

	//LOG EVENT
	mysql_select_db("logs", $con);
	$sql = "INSERT INTO eventlogs (userid,eventslogid,request,result,createdat, updatedat)VALUES ({$userid}, 45,'{$rows}','{$re}','{$d}','{$d}')";
	mysql_query($sql);

?>