<?php
$con=mysqli_connect("61b1ed95616f65903ff311cc9decad51aa4cac3d.rackspaceclouddb.com","vastdb","VastPlus#01","Yoda");
//Check connection
if (mysqli_connect_errno())
  {
  echo "Bad Connection " . mysqli_connect_error();
  }
$result = mysqli_query($con,"SELECT * FROM tutorials where video_section ='Settings Bar' order by vid asc"); 
$result1 = mysqli_query($con,"SELECT * FROM tutorials where video_section ='Top Menu Bar' order by vid asc");

if (isset($_GET['video_id']))
    {
    $vidcheck = $_GET['video_id'] ;
	}

?> 

<div id="side-menu"><!-- start main wrapper -->
<div id="ratecard-bar">
	<div class="row">
		<h4>ShowSeeker Plus - Video Tutorials</h4>
	</div>
		<div class="row">
		<?php
		echo "<h2>Settings Bar:</h2>";
		echo "<ul>";
		while ($row = mysqli_fetch_assoc($result)) {

		   $linkpage = "index.php?video_id={$row['vid']}";
		  
			
		   if ($vidcheck == ($row['vid'])) {
			    echo "<li><a style='color:#000000; background-color:#FFF38C;' href='$linkpage'>{$row['video_title']}</a></li>";
			}
			else {
				echo "<li><a href='$linkpage'>{$row['video_title']}</a></li>";
			}
		}
		echo '</ul>';
		?>
		</div>
		<div class="row">
		<?php
		echo "<h2>Top Menu Bar:</h2>";
		echo "<ul>";
		while ($row = mysqli_fetch_assoc($result1)) {
			$linkpage = "index.php?video_id={$row['vid']}";
		  
		    if ($vidcheck == ($row['vid'])) {
			    echo "<li><a style='color:#000000; background-color:#FFF38C;' href='$linkpage'>{$row['video_title']}</a></li>";
			}
			else {
				echo "<li><a href='$linkpage'>{$row['video_title']}</a></li>";
			}

		}
		echo '</ul>';
		?>
		</div>
	
</div>
<div id="help-bar" style="display:none">
	<div class="row">
		This is where we can have help
	</div>
</div>
</div><!-- end main wrapper -->