<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);

	$con=mysqli_connect("db4.showseeker.net","vastdb","jK6YK71tJ","logs");
	$result ="SELECT * FROM supportItems order by createdat desc"; 
	$shows = mysqli_query($con, $result );

	$con1=mysqli_connect("db4.showseeker.net","vastdb","jK6YK71tJ","ShowSeeker");
	$result1 ="SELECT name FROM corporations order by name"; 
	$corps = mysqli_query($con1, $result1 );
?> 

<head>
  <title>ShowSeeker - Create a Ticket</title>
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
          <li><a href="support/index.php">Supprt Tickets</a></li>
        </ul>
      </li>
    </ul>
</nav>
<h3 class="text-center">ShowSeeker - Create a Ticket</h3>
<p class="text-right"><a href="index.php" class="button tiny">List Tickets</a></p>
<form name="input" action="process_ticket.php" method="post" accept-charset="UTF-8"
enctype="application/x-www-form-urlencoded" data-abide>
<div class="row">
	<div class="large-4 columns"><strong>Date:<br><input type="text" id="createdat" name="createdat" READONLY value="<?php echo date("Y-m-d H:i:s"); ; ?>"></strong></div>
</div>
<div class="row">
	<div class="large-4 columns"><strong>Your Name: </strong><br>
		<select name="ae">
            <option>Please Select your Name:</option>
            <option value="2970">Asif</option>
            <option value="149">Barbara</option>
            <option value="147">Dave</option>
            <option value="160">Ivan</option>
            <option value="2347">Kara</option>
            <option value="152">Mark E</option>
            <option value="2136">Mark N</option>
            <option value="2435">Parresh</option>            
            <option value="220">Tiffany</option>               
        </select>
	</div>
	<div class="large-4 columns"><strong>Assign To: </strong><br>
		<select name="assignedTo">
            <option>Please Select :</option>
            <option value="2970">Asif</option>
            <option value="149">Barbara</option>
            <option value="147">Dave</option>
            <option value="160">Ivan</option>
            <option value="2347">Kara</option>
            <option value="152">Mark E</option>
            <option value="2136">Mark N</option>
            <option value="2435">Parresh</option>            
            <option value="220">Tiffany</option>            
        </select>
	</div>


	<div class="large-4 columns"><strong>Customer - Company Name:</strong><br>
		<select name="customer" required data-invalid aria-invalid="true">
			<?php while($row1 = mysqli_fetch_array($corps)) : 
				echo "<option>" . $row1['name']. "</option>" ;
				endwhile; 
			?>
		</select>
		<small class="error">Name is required and must be a string.</small>
	</div>
</div>  
  
<div class="row">
	<div class="large-4 columns"><strong>Browser:</strong><br>
			<select name="browser" required data-invalid aria-invalid="true">
				<option>Which Browser?</option>
				<option value="All">All</option>
				<option value="Chrome">Chrome</option>
				<option value="Firefox">Firefox</option>
				<option value="IE 8">IE 8</option>
				<option value="IE 9">IE 9</option>
				<option value="IE 10">IE 10</option>
				<option value="IE 11">IE 11</option>
				<option value="Safari">Safari</option>
				<option value="Mobile - Android">Mobile - Android</option>
				<option value="Mobile - iPad">Mobile - iPad</option>
				<option value="Mobile - iPhone">Mobile - iPhone</option>
				<option value="Mobile - Windows">Mobile - Windows</option>
			</select>
		 <small class="error">Browser not selected.</small>
	</div>
	<div class="large-4 columns"><strong>Platform:</strong>
		<select name="platform" required data-invalid aria-invalid="true">
            <option>Please Select Platform:</option>
            <option value="General">General</option>
            <option value="Admin.showseeker.com">Admin.showseeker.com</option>
            <option value="Chocolate.showseeker.com">Chocolate.showseeker.com</option>
            <option value="Exports.showseeker.com">Exports.showseeker.com</option>
            <option value="Ez-Breaks">Ez-Breaks</option>
            <option value="Ez-Grids">Ez-Grids</option>
            <option value="LoveIt.tv">LoveIt.tv</option>
            <option value="Plus.showseeker.com">Plus.showseeker.com</option>
            <option value="Managed.showseeker.com">Managed.showseeker.com</option>
            <option value="Max.showseeker.com">Max.showseeker.com</option>
            <option value="Nodex.showseeker.com">Nodex.showseeker.com</option>
        </select>
	 <small class="error">Platform not selected.</small>
	</div>
	<div class="large-4 columns"><strong>Priority:</strong>
        <select  name="priority">
            <option value="1">Critical!!</option>
            <option value="2">Alert!</option>
            <option value="3">High</option>
            <option value="4">Medium</option>
            <option value="5">Low</option>
        </select>
	</div>
</div>

<div class="row">
  <div class="large-12 columns"><strong>Description of Issue:</strong><br><textarea name="issue" cols="50" rows="6" id="issue" required pattern="[a-zA-Z]+"> </textarea><small class="error">Description  is required.</small></div>
</div>

<div class="row">
  <div class="large-12 columns"><center><input type="submit" class="button tiny" value="Enter Ticket"></center></div>
</div>

</form>

<script src="//cdn.jsdelivr.net/foundation/5.5.0/js/foundation.min.js"></script>
<script>
	$(document).foundation();
</script>