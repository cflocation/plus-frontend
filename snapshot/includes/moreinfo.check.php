<?php
	$showid = $_GET['id'].'0000';
	$showid = str_replace("EP","SH",$showid);

	//mysql connector
	include_once('../../config/database.php');

	mysql_select_db("On", $con);

	$sql = "SELECT * FROM onShowcardId WHERE TMSId = '".$showid."'";

	$result = mysql_query($sql);
	$num_rows = mysql_num_rows($result);

	print $num_rows;
	return;

	$sql = "SELECT futoncritic_showinfo.official_url,  futoncritic_showinfo.description
	FROM futoncritic_showwatchcatalog
	INNER JOIN futoncritic_showinfo ON futoncritic_showwatchcatalog.showatch_url = futoncritic_showinfo.showatch_url 
	WHERE futoncritic_showwatchcatalog.showid = '$showid'";

	print $sql;

?>

