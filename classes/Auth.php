<?php
	ini_set("display_errors",1);
	class Auth{

		//build the basic information about the logged in user
		public function __construct($con){
			$this->con = $con;
			$this->now = date('Y-m-d H:i:s');
		}

		public function checkAuth($url,$authtokin,$userid){
			//get the user token id for the hash table
			$sql = "SELECT tokenid FROM users WHERE id = $userid LIMIT 1";
			$result = mysqli_query($this->con, $sql);
			$row = $result->fetch_assoc();
			$usertokin = $row['tokenid'];

			//if the key is valid return true
			$key = md5($userid.$usertokin.$url);
			
			if(trim($key) == trim($authtokin)){
				return $usertokin;
			}

			//or return false
			return false;
		}

		public function checkAuthGoplus($url,$authtokin,$userid){
			//get the user token id for the hash table
			$sql = "SELECT apiKey FROM User WHERE id = $userid LIMIT 1";
			$result = mysqli_query($this->con, $sql);
			$row = $result->fetch_assoc();
			$usertokin = $row['apiKey'];

			//if the key is valid return true
			$key = md5($userid.$usertokin.$url);
			
			if(trim($key) == trim($authtokin)){
				return $usertokin;
			}

			//or return false
			return false;
		}
	}
?>