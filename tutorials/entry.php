<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_local = "61b1ed95616f65903ff311cc9decad51aa4cac3d.rackspaceclouddb.com";
$database_local = "ShowSeeker";
$username_local = "vastdb";
$password_local = "VastPlus#01";
$local = mysql_pconnect($hostname_local, $username_local, $password_local) or trigger_error(mysql_error(),E_USER_ERROR); 
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['email'])) {
  $loginUsername=$_POST['email'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "tutorials.php";
  $MM_redirectLoginFailed = "entry.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_local, $local);
  
  $LoginRS__query=sprintf("SELECT email, password FROM users WHERE corporationid = '14' AND email=%s AND password=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $local) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link href="https://showseeker.s3.amazonaws.com/public-site/assets/favicon.ico" name="favicon" rel="shortcut icon" type="image/png">
<!-- title>ShowSeeker Tutorials Editor - Login</title -->
<title>Tutorials Editor - Login | ShowSeeker</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/foundation.css">
</head>

<body>
<p align="center"><strong>ShowSeeker - Tutorials Editor - Login</strong></p>	
<form ACTION="<?php echo $loginFormAction; ?>" id="form1" name="form1" method="POST">
  <p>&nbsp;</p>
  <table width="55%" border="1" align="center">
    <tr>
      <td align="center"><label for="email">Email:</label>
      <input type="text" name="email" id="email"></td>
    </tr>
    <tr>
      <td align="center"><label for="password2">Password:</label>
      <input type="password" name="password" id="password2"></td>
    </tr>
    <tr>
      <td align="center"><input type="submit" name="submit" id="submit" value="Submit"></td>
    </tr>
  </table>
</form>
</body>
</html>