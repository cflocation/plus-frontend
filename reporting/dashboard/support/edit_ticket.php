<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);
	$sid = $_GET['sid'] ;

	$con=mysqli_connect("db4.showseeker.net","vastdb","jK6YK71tJ","logs");
	$result = mysqli_query($con,"SELECT * FROM supportItems where sid = $sid");
	$row = mysqli_fetch_assoc($result);


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




?> 
<head>
	<title>ShowSeeker - Technical Support - Ticket Editor</title>  
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
	<link rel="stylesheet" href="//cdn.jsdelivr.net/foundation/5.5.0/css/foundation.css">
	<link rel="stylesheet" href="//cdn.jsdelivr.net/foundation/5.5.0/css/foundation.min.css">
	<script src="//cdn.jsdelivr.net/foundation/5.5.0/js/vendor/modernizr.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
</head>
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
          <li><a href="index.php">Main Menu</a></li>
          <li><a href="system_check.php">System Check</a></li>
          <li><a href="system_stats.php">System Stats</a></li>
          <li><a href="grids_mailer_check.php">Ez-Grids - Email Check</a></li>
          <li><a href="tutorials.php">Tutorials - Logs</a></li>
          <li><a href="index.php">Support Tickets</a></li>
        </ul>
      </li>
    </ul>
</nav>
<body>

<h3 class="text-center">Showseeker - Edit Support Ticket </h3>
<p align="right"><strong><a class="button tiny" href="index.php">List Tickets</a></strong></p>

<form name="input" action="update_ticket.php" method="post">

<div class="row">
	<div class="large-4 columns"><strong>Date: </strong><input type="text" READONLY value="<?php echo $row['createdat']; ?>"></div>
	<div class="large-4 columns"><strong>A/E:</strong><input type="text" READONLY value="<?php echo $sAeName; ?>"></div>
	<div class="large-4 columns"><strong>Assigned To:</strong><input type="text" READONLY value="<?php echo $sAssignedName; ?>"></div>
</div>  
<br />

<div class="row">
	<div class="large-3 columns"><strong>Customer - Company Name:</strong><input type="text" name="customer" READONLY  id="customer" value="<?php echo $row['customer']; ?>"></div>
	<div class="large-3 columns"><strong>Platform:</strong> <input type="text" name="platform" id="platform" READONLY value="<?php echo $row['platform']; ?>"></div>
	<div class="large-2 columns"><strong>Priority:</strong>         
	<select name="priority">
            <option value="1"<?php if ($row['priority'] == '1') { echo ' selected="selected"'; }  ?>>Critical!!</option>           
            <option value="2"<?php if ($row['priority'] == '2') { echo ' selected="selected"'; }  ?>>Alert!</option>           
            <option value="3"<?php if ($row['priority'] == '3') { echo ' selected="selected"'; }  ?>>High</option>           
            <option value="4"<?php if ($row['priority'] == '4') { echo ' selected="selected"'; }  ?>>Medium</option>           
            <option value="5"<?php if ($row['priority'] == '5') { echo ' selected="selected"'; }  ?>>Low</option>           
    </select>
	</div>
	<div class="large-2 columns"><strong>Browser:</strong>
		<select name="browser">
            <option value="All"<?php if ($row['browser'] == 'All') { echo ' selected="selected"'; }  ?>>All</option>           
            <option value="Chrome"<?php if ($row['browser'] == 'Chrome') { echo ' selected="selected"'; }  ?>>Chrome</option>           
            <option value="Firefox"<?php if ($row['browser'] == 'Firefox') { echo ' selected="selected"'; }  ?>>Firefox</option>           
            <option value="IE 8"<?php if ($row['browser'] == 'IE 8') { echo ' selected="selected"'; }  ?>>IE 8</option>   
            <option value="IE 9"<?php if ($row['browser'] == 'IE 9') { echo ' selected="selected"'; }  ?>>IE 9</option>   
            <option value="IE 10"<?php if ($row['browser'] == 'IE 10') { echo ' selected="selected"'; }  ?>>IE 10</option>   
            <option value="IE 11"<?php if ($row['browser'] == 'IE 11') { echo ' selected="selected"'; }  ?>>IE 11</option>   
            <option value="Safari"<?php if ($row['browser'] == 'Safari') { echo ' selected="selected"'; }  ?>>Safari</option>   
            <option value="Mobile - Android"<?php if ($row['browser'] == 'Mobile - Android') { echo ' selected="selected"'; }  ?>>Mobile - Android</option>   
            <option value="Mobile - iPad"<?php if ($row['browser'] == 'Mobile - iPad') { echo ' selected="selected"'; }  ?>>Mobile - iPad</option>   
            <option value="Mobile - iPhone"<?php if ($row['browser'] == 'Mobile - iPhone') { echo ' selected="selected"'; }  ?>>Mobile - iPhone</option>   
            <option value="Mobile - Windows"<?php if ($row['browser'] == 'Mobile - Windows') { echo ' selected="selected"'; }  ?>>Mobile - Windows</option>   
	    </select>
	</div>
	<div class="large-2 columns"><strong>Status:</strong>
		<select name="status">
            <option value="1"<?php if ($row['status'] == '1') { echo ' selected="selected"'; }  ?>>Open</option>           
            <option value="2"<?php if ($row['status'] == '2') { echo ' selected="selected"'; }  ?>>Pending</option>           
            <option value="3"<?php if ($row['status'] == '3') { echo ' selected="selected"'; }  ?>>QA</option>   
            <option value="4"<?php if ($row['status'] == '4') { echo ' selected="selected"'; }  ?>>Closed</option>   
	    </select>

	</div>

</div>

<div class="row">
  <div class="large-12 columns"><strong>Description of Issue:</strong><br><textarea name="issue" cols="50" rows="6" id="issue"><?php echo $row['issue']; ?> </textarea></div>
</div>

<div class="row">
  <div class="large-12 columns"><strong>Solution:</strong><br><textarea name="solution" cols="50" rows="6" class="default-value" id="solution"><?php echo $row['solution']; ?></textarea></div>
</div>
<input type="HIDDEN" id="sid" name="sid" value=<?php echo $sid; ?>>
<p align="center"><input type="submit" class="button tiny" value="Update"></p>
</form>

<p align="left"><a href="delete_ticket.php?SID=<?php echo $sid; ?>"><i class="fa fa-trash fa-2x"></i></a></p>


<script src="//cdn.jsdelivr.net/foundation/5.5.0/js/foundation.min.js"></script>




<script>
	$(document).foundation();


</script>