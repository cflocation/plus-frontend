<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    session_start();
		session_set_cookie_params(3600000,"/");

	//data connection
    include_once('../../../config/database.php');

    //form vars
    $id = $_GET['id'];
    $tokenId = $_GET['tokenId'];


            //$sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
            $sql = "SELECT users.id AS id, users.firstname AS firstname, users.lastname AS lastname, 
            		corporations.id AS corporationid, 
            		corporations.apikey AS apikey, corporations.name AS corporation, 
            		users.tokenid, users_default.location
            		
            FROM users
            
            INNER JOIN userroles ON userroles.userid = users.id
            
            INNER JOIN corporations ON corporations.id = users.corporationid
			
			       LEFT OUTER JOIN users_default ON users_default.usersid = users.id 
            
            WHERE 	users.id = '$id' 
            		AND users.tokenid = '$tokenId'
            		AND users.deletedat is null
            		AND corporations.deletedat is null

            LIMIT 1";


            $result = mysql_query($sql);
            $cnt = mysql_num_rows($result);
            $row = mysql_fetch_assoc($result);



          

            if($cnt > 0){
                  $_SESSION['userid'] = $row['id'];
                  $_SESSION['name'] = $row['firstname'];
                  $_SESSION['corporationid'] = $row['corporationid'];
                  $_SESSION['corporation'] = $row['corporation'];
                  $_SESSION['tokenid'] = $row['tokenid'];
                  $_SESSION['apikey'] = $row['tokenid'];
                  $_SESSION['roles'] = getRoles($row['id']);


                  $expire	=time()+60*60*24*30;
                  setcookie("userid", $row['id'], $expire,  "/");
                  setcookie("tokenid", $row['tokenid'], $expire,  "/");
                  setcookie("apikey", $row['apikey'], $expire,  "/");

   
                  print_r($row);

                  if($row['location'] != ""){
                    header('Location:http://www.showseeker.com/'.$row['location']);
                    return;
                  }
            }


      function getRoles($userid){
        $sql = "SELECT roleid FROM userroles WHERE userid = $userid";
        $result = mysql_query($sql);
        
        //loop over and add to list
        while($row = mysql_fetch_assoc($result)) {
          $data[] = $row;
        }
        return  $data;
      }


?>