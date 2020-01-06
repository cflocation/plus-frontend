<?php
    function getRoles($userid){
      global $con;
    	$sql = "SELECT roleid FROM userroles WHERE userid = $userid";
    	$result = mysqli_query($con, $sql);
    
    	while ($row = $result->fetch_assoc()) {
      		$data[] = $row;
    	}
    	return  $data;
  	}


    function findUserRole($roles,$role){
      foreach ($roles as &$value) {
        if($value['roleid'] == $role){
          return TRUE;
        }
      }
      return FALSE;
    }
?>