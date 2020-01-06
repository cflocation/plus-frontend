<?php
	session_start();
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);
	$con = mysqli_connect("db4.showseeker.net","vastdb","jK6YK71tJ","ShowSeeker");

	$proposal_sql = "SELECT id, userid, proposal, createdat from proposals order by createdat desc limit 0, 200"; 
	$proposals = mysqli_query($con, $proposal_sql);

	$shows = array();

	while ($row1 = mysqli_fetch_array($proposals)) {  
	
		$userid=$row1['userid'];
		$user_sql = "SELECT tokenid from users where id = $userid" ; 

		$users = mysqli_query($con, $user_sql);

		$row2 = mysqli_fetch_array($users) ;
		$tokenid = $row2['tokenid'] ; 
		
		$proposalid = $row1['id'] ;
		$makeDate = $row1['createdat'] ;

		$call = "http://services.showseeker.com/userproposal.php?proposalid={$proposalid}&userid={$userid}&tokenid={$tokenid}";
		$json_data		= file_get_contents($call);
		$resJson = json_decode($json_data);

	foreach($resJson->proposal AS $zone){
		foreach($zone->lines AS $line){
				$st = $line->search ;
			if ($st == 'Package' ) {
				 
				$sn = $makeDate . " - " . $line->title . " - Proposal ID: <a href='http://162.209.2.199/projects/proposal-calendars/html_report.php?proposalid=$proposalid&userid=$userid&tokenid=$tokenid&clientid=0&headerid=0&sort1=startdate&sort2=starttime&sort3=network&logos=true&description=true&includenew=true&hiderates=false&showratecard=false&onlyfixed=false&addterms=false&repfirmid=0&agencyid=0' target='_blank'>" . $proposalid . "</a>" ; 
				$shows[] =  $sn ; 
			}
		}
	}
}
?>

<head>
<title>ShowSeeker - Sport Package Usage</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<link rel="stylesheet" href="http://www.showseeker.com/inc/foundation/css/normalize.css">
<link rel="stylesheet" href="http://www.showseeker.com/inc/foundation/css/foundation.css">
<script src="http://www.showseeker.com/inc/foundation/js/vendor/modernizr.js"></script>
</head>

<div class="row">
	<table align="center"><tr valign="top"><td>
			<thead>
				<tr>
				<th><center>Package - Title - Proposal ID</center></th>
				</tr>
			  </thead>
			<tbody>
				<?php
					sort($shows);
					$final = array_unique($shows) ;
					foreach ($final as $key => $val) {
						echo "<tr><td>" . $val . "<td/></tr>";
					}
				?>
			</tbody>
	</table>
</div>
<script src="http://www.showseeker.com/inc/foundation/js/foundation.min.js"></script>
<script>
	$(document).foundation();
</script>