<?php
$con=mysqli_connect("61b1ed95616f65903ff311cc9decad51aa4cac3d.rackspaceclouddb.com","vastdb","VastPlus#01","Customers");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

$videotext = addslashes($_POST[video_text]);
$videotitle = addslashes($_POST[video_title]);

$sql="UPDATE tutorials SET video_link_id ='$_POST[video_link_id]', video_section ='$_POST[video_section]', video_title ='$videotitle', video_text ='$videotext', video_length ='$_POST[video_length]' where vid ='$_POST[vid]'";
//echo $sql;
$result=mysqli_query($con,$sql);
header('Location: tutorials.php');				
mysqli_close($con);
?>