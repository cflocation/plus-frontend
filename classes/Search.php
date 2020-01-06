<?php
	class Search{
		protected $con;
		protected $userid;
		protected $tokenid;
		protected $now;


		//build the basic information about the logged in user
		public function __construct($con, $userid, $tokenid){
			$this->con = $con;
			$this->userid = $userid;
			$this->tokenid = $tokenid;
			$this->now = date('Y-m-d H:i:s');
		}


		public function getSavedSearches(){
			$sql = "SELECT name, type, search, remindedat, reminder, notes, filters, createdat, updatedat, id FROM savesearches WHERE usersid = $this->userid AND deletedat IS NULL ORDER BY createdat DESC";
			
			$result = mysqli_query($this->con, $sql);
			
			if($result->num_rows == 0){
				return array();
			}
			while ($row = $result->fetch_assoc()) {
				$row['name'] = urldecode($row['name']);
				$n = str_replace('%', '', $row['name']);
				$row['name'] = $n;
	   			$data[] = $row;
	    	}
	    	
	    	return $data;
		}


		public function getNetworkHistory($callsign,$date,$tz){
			$file = $date.".json";
			$uri = "https://showseeker.s3.amazonaws.com/history/".$tz."/".$callsign."/".$file."";
			$data = file_get_contents($uri);
			return $data;
		}


	}
?>