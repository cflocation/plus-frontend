<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "entry.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>

<?php
$con=mysqli_connect("61b1ed95616f65903ff311cc9decad51aa4cac3d.rackspaceclouddb.com","vastdb","VastPlus#01","Customers");
//Check connection
if (mysqli_connect_errno())
  {
  echo "Bad Connection " . mysqli_connect_error();
  }
$vid = $_GET['vid'] ;
$result = mysqli_query($con,"SELECT * FROM tutorials where vid = $vid");  
?> 
<head>
  <title>ShowSeeker - Tutorial Editor</title>  
  <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
  <link type="text/css" href="css/jquery-ui-1.8.5.custom.css" rel="Stylesheet" /> 
  <script src="//code.jquery.com/jquery-1.9.1.js"></script>
  <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
  <script src="js/jquery-1.4.2.min.js" type="text/javascript"></script>
  <script src="js/jquery-ui-1.8.5.custom.min.js" type="text/javascript"></script>
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/foundation.css">
</head>
<body>
	<br>

<form name="input" action="tutorial_update.php" method="post">
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" width="5%"><strong><u>Video ID</u></strong></td>
    <td align="center" width="10%"><strong><u>Video Link ID</u></strong></td>
    <td align="center" width="10%"><strong><u>Video Section</u></strong></td>
    <td align="center" width="15%"><strong><u>Video Title</u></strong></td>
    <td align="center" width="60%"><strong><u>Video Text</u></strong></td>
    <td align="center" width="5%"><strong><u>Video Length</u></strong></td>       
  </tr>
  <?php while($row = mysqli_fetch_array($result)) : ?>
  <tr>
    <td align="center" width="5%"><?php echo $row['vid']; ?></td>
    <td align="center" width="10%"><input type="text" name="video_link_id" value="<?php echo $row['video_link_id']; ?>"></td>
    <td align="center" width="10%"><input type="text" name="video_section" value="<?php echo $row['video_section']; ?>"></td>
    <td align="center" width="15%"><input type="text" name="video_title" value="<?php echo $row['video_title']; ?>"></td>
    <td width="60%"><textarea name="video_text" rows=6 cols=50 ><?php echo $row['video_text']; ?></textarea></td>
    <td align="center" width="5%"><input type="text" name="video_length" value="<?php echo $row['video_length']; ?>"></td>       
  </tr> 
  <?php endwhile; ?>

</table>
<p align="center"><input type="submit" formaction="tutorial_update.php" formmethod="POST" value="Submit"></p>
<input type="HIDDEN" id="vid" name="vid" value=<?php echo $vid; ?>>
</form>
