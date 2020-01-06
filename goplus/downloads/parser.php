<?php 
	require_once 'commons/initialize.php';
	define("XML_FOLDER","xmls/");
	
	//ini_set("display_errors","on");
	//ini_set("display_startup_errors","on");
	//error_reporting(E_ALL);
	
	if(isset($_GET['proposalid']))
	{
		$proposalid		= trim(urldecode($_GET['proposalid']));
		$customer		= htmlspecialchars(trim(urldecode($_GET['customer'])));
		$salesperson	= htmlspecialchars(trim(urldecode($_GET['salesperson'])));
		$agency			= htmlspecialchars(trim(urldecode($_GET['agency'])));
		$ucBookend		= htmlspecialchars(trim(urldecode($_GET['ucBookend'])));
		$ulLength		= htmlspecialchars(trim(urldecode($_GET['ulLength'])));
		
		$sql1 =" SELECT * FROM proposals WHERE id=$proposalid ";  
		$prpResult = $db->fetch_result($sql1);
		$proposal = $prpResult[0];
		
		$arr = json_decode($proposal['proposal']);
		$ttl = 0;	
	}
?>


	<table border=2>
		<tr>
			<td><b>Title</b></td>
			<td>Rate</td>
			<td>Spots</td>
			<td>Total</td>
			<td>Running</td>
		</tr>




	<?php foreach ($arr as &$value): 
		$rate = $value->rate;
		$spots = $value->spots;
		$linetotal = $rate*$spots;
	?>

	<tr>
			<td><?php print $value->title; ?></td>
			<td><?php print $rate; ?></td>
			<td><?php print $spots; ?></td>
			<td><?php print $linetotal; ?></td>
			<td><?php print $ttl+=$linetotal; ?></td>
	</tr>


	<?php endforeach; ?>

	</table>