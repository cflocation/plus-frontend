<?php
	$con=mysqli_connect("db4.showseeker.net","vastdbuser","jK6YK71tJ","Customers");
	$result1 = mysqli_query($con,"SELECT * FROM tutorials where video_section ='Settings Bar' order by vid asc"); 
	$result2 = mysqli_query($con,"SELECT * FROM tutorials where video_section ='Top Menu Bar' order by vid asc");
?>
<div class="row">
	<div class="small-6 large-6 columns">
	<u><h3>Settings Bar:</h3></u>
	<?php while($row1 = mysqli_fetch_array($result1)) : ?>
		   <?php echo "<h4><a href=index.php?video_id=".$row1['vid'].">".$row1['video_title']." - (" .$row1['video_length']  ." minutes)</a></h4><h5>".$row1['video_text']."</h5>" ?>
	<?php endwhile?>
	</div>
	<div class="small-6 large-6 columns">
	<u><h3>Top Menu Bar:</h3></u>
	<?php while($row2 = mysqli_fetch_array($result2)) : ?>
		   <?php echo "<h4><a href=index.php?video_id=".$row2['vid'].">".$row2['video_title']." - (" .$row2['video_length']  ." minutes)</a></h4><h5>".$row2['video_text'] ."</h5>"?>
	<?php endwhile?>
	</div>
</div>
<div class="row">
	<div class="small-4 small-centered columns"><strong>Problems with Viewing Videos?</strong><br><a href="https://showseeker.s3.amazonaws.com/tutorials/index.htm">Click here </a>for an alternate location</div>
</div>