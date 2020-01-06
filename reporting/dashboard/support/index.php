<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);
	$con=mysqli_connect("db4.showseeker.net","vastdb","jK6YK71tJ","logs");
	$result ="SELECT * FROM supportItems order by platform, priority, status,  createdat desc"; 

	if(isset($_GET['uid'])) {
		$uid = $_GET['uid'] ;
		$result ="SELECT * FROM supportItems where assignedTo = '$uid' order by platform, priority, status,  createdat desc"; 
	}

	$shows = mysqli_query($con, $result );
	$tPlat = "" ; 
?> 
<head>
	<title>ShowSeeker - Technical Support - Listing of Tickets </title>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
	<link rel="stylesheet" href="//cdn.jsdelivr.net/foundation/5.5.0/css/foundation.css">
	<link rel="stylesheet" href="//cdn.jsdelivr.net/foundation/5.5.0/css/foundation.min.css">
	<script src="//cdn.jsdelivr.net/foundation/5.5.0/js/vendor/modernizr.js"></script>
</head>
<body>
<nav class="top-bar" data-topbar>
  <ul class="title-area">
    <li class="name">
      <h1><a href="#">ShowSeeker  - System Dashboard</a></h1>
    </li>
  </ul>
  <section class="top-bar-section">
    <ul class="right">
      <li class="has-dropdown">
        <a href="#">System Menu</a>
        <ul class="dropdown">
          <li><a href="../index.php">Main Menu</a></li>
          <li><a href="../system_check.php">System Check</a></li>
          <li><a href="../system_stats.php">System Stats</a></li>
          <li><a href="../grids_mailer_check.php">Ez-Grids - Email Check</a></li>
          <li><a href="../tutorials.php">Tutorials - Logs</a></li>
          <li><a href="index.php">Support Tickets</a></li>
        </ul>
      </li>
    </ul>
</nav>
<h3 class="text-center">ShowSeeker - Support Tickets </h3>
<div class="row">
  <div class="small-6 columns text-left"><a href="closed_index.php" class="button tiny">Closed Tickets</a></div>
  <div class="small-6 columns text-right"><a href="create_ticket.php" class="button tiny">Create Ticket</a></div>
</div>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<td align="center" width="10%"><strong><u>Date</u></strong></td>
			<td align="center" width="7%"><strong><u>A/E</u></strong></td>
			<td align="center" width="7%"><strong><u>Assigned</u></strong></td>
			<td align="center" width="10%"><strong><u>Customer</u></strong></td>
			<td align="center" width="10%"><strong><u>Browser</u></strong></td>
			<td align="center" width="35%"><strong><u>Ticket Information</u></strong></td>
			<td align="center" width="5%"><strong><u>Priority</u></strong></td>
			<td align="center" width="5%"><strong><u>Status</u></strong></td>				
		</tr>
	</thead>


		<tbody>	
		  <?php while($row = mysqli_fetch_array($shows)) : 
		  
		$pCode = $row['priority'];   
			switch ($pCode) {
			case "1":
				$pTitle =  "Critical";
				$pBG = "style='background-color:#FF7070'" ;
				break;
			case "2":
				$pTitle =  "Alert";
				$pBG = "style='background-color:#FF9651'" ;
				break;
			case "3":
				$pTitle =  "High";
				$pBG = "style='background-color:#FFDB3A'" ;
				break;
			case "4":
				$pTitle =  "Medium";
				$pBG = "style='background-color:#9BB754'" ;
				break;
			case "5":
				$pTitle =  "Low";
				$pBG = "style='background-color:#C0C0C0'" ;
				break;
			}

		$sCode = $row['status'];   
		switch ($sCode) {
			case "1":
				$sTitle =  "Open";
				break;
			case "2":
				$sTitle =  "Pending";
				$pBG = "style='background-color:#6CA1B5'" ;
				break;
			case "3":
				$sTitle =  "QA";
				$pBG = "style='background-color:#56B73E'" ;
				break;
			case "4":
				$sTitle =  "Closed";
				$pBG = "style='background-color:#DBDBDB'" ;
				break;
		}

		$sAe = $row['ae'];   
		switch ($sAe) {
			case "2970":
				$sAeName =  "Asif";
				break;
			case "149":
				$sAeName =  "Barbara";
				break;
			case "147":
				$sAeName =  "Dave";
				break;
			case "160":
				$sAeName =  "Ivan";
				break;
			case "2347":
				$sAeName =  "Kara";
				break;
			case "152":
				$sAeName =  "Mark E";
				break;
			case "2136":
				$sAeName =  "Mark N";
				break;
			case "2435":
				$sAeName =  "Parresh";
				break;
			case "220":
				$sAeName =  "Tiffany";
				break;

		}

		$sAssignedTo = $row['assignedTo'];   
		switch ($sAssignedTo) {
			case "2970":
				$sAssignedName =  "Asif";
				break;
			case "149":
				$sAssignedName =  "Barbara";
				break;
			case "147":
				$sAssignedName =  "Dave";
				break;
			case "160":
				$sAssignedName =  "Ivan";
				break;
			case "2347":
				$sAssignedName =  "Kara";
				break;
			case "152":
				$sAssignedName =  "Mark E";
				break;
			case "2136":
				$sAssignedName =  "Mark N";
				break;
			case "2435":
				$sAssignedName =  "Parresh";
				break;
			case "220":
				$sAssignedName =  "Tiffany";
				break;

		}




		$solCheck = $row['solution'];

		if ($solCheck !=NULL ) {
			$solTitle = "<br /><br /><b>Solution: </b><br>" . $row['solution'] ; 
		}
		else {
			$solTitle = ""; 
		}
		$tPlatform = $row['platform'];
		  
if ($sCode != '4') {

		if ($tPlat != $tPlatform) {
			echo "<tr style='background-color:#333'><td colspan='7'><center><font color='#FFF'><strong>" . $row['platform'] ."</strong></font></center></td></tr>" ; 
		}
		 
		  
		//TIMES BEATIFICATION

		$openTicket = "<strong>o:</strong> " . $row['createdat']; 
		
		$updateTicket = "<br><strong>u:</strong> " . $row['updatedat']; 
		if (($row['updatedat'] == "0000-00-00 00:00:00")) { 
			$updateTicket = '' ; 
			}

		$closeTicket = "<br><strong>c:</strong> " . $row['closedat'];
		if (($row['closedat']  == "0000-00-00 00:00:00")) { 
			$closeTicket  = '' ; 
			}		  

		  


?>

		

		  <tr valign="top" <?php echo $pBG ?>>
			<td align="center" ><?php echo $openTicket ; ?><?php echo $updateTicket ; ?><?php echo $closeTicket; ?></td>
			<td align="center"><?php echo $sAeName; ?></td>
			<td align="center"><?php echo $sAssignedName; ?></td>
			<td align="center"><?php echo $row['customer']; ?></td>
			<td align="center"><?php if (isset ($row['browser'])) {echo $row['browser']; } ?></td>
			<td><b>Issue: </b><br /><?php echo $row['issue']; ?><?php echo $solTitle;?></td>
			<td align="center"> <?php echo $pTitle; ?> </td>
			<td align="center" style="background-color:#FFF"><a href="edit_ticket.php?sid=<?php echo $row['SID']; ?>"><?php echo $sTitle; ?></a></td>
		  </tr>
		  <?php 
		  $tPlat = $tPlatform ;
			};
		  endwhile; 
?>
		</tbody>
</table>



<script src="//cdn.jsdelivr.net/foundation/5.5.0/js/foundation.min.js"></script>
<script>
	$(document).foundation();
</script>