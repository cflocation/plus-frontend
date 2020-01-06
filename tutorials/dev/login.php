<!DOCTYPE html>
<?php
    session_set_cookie_params(3600000);
    session_start();

	if ($_SERVER['REQUEST_METHOD']== "POST") {
		$email = $_POST['email'];
		$password = $_POST['password'];

		include_once('../config/database.php');

		$sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
		$result = mysql_query($sql);
    	$row = mysql_fetch_assoc($result);
		
        $num_rows = mysql_num_rows($result);


        if($num_rows > 0){
            $_SESSION['userid'] = $row['id'];
            header('Location: index.php');
            return;
            //header('Location: index.php');
        }



		print_r($row);
	}

?>


<html>
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ShowSeeker Plus - Tutorials Login</title>
    
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css" />
    <link rel="stylesheet" href="../css/style.css" />

	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>



</head>
<body>


<form data-ajax="false" action="login.php" method="post">
<div id="index" data-role="page" id="corporation-options" data-dom-cache='true'>


    <div data-role="header" data-position="fixed">
        <h1>Welcome to ShowSeeker User Tutorials</h1>
    </div>

    <div data-role="content">

        <div data-role="collapsible-set" data-theme="b" data-content-theme="d">

            <div data-collapsed="false" data-theme="b" data-role="collapsible">
                <h3>Login</h3>
                <ul data-role="listview">
                <li>
                    <label for="email">Email Address:</label>
                    	<input data-clear-btn="true" name="email" id="email" value="" type="text">
                    <label for="password">Password:</label>
                    	<input data-clear-btn="true" name="password" id="password" value="" type="password">
                    	<input data-role="button"  type="submit" data-theme="a" data-inline="true" value=" Login ">
                </li>
                </ul>
            </div>


        </div>
    </div>
</div>
</form>




</body>
</html>
