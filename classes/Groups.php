<?php
	class Groups{
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


		//GET PROPOSALS
		public function listGroups(){
			$sql = "SELECT id, title, users, type FROM sharegroups WHERE userid = $this->userid AND deletedat IS NULL ORDER BY title";
			
			$result = mysqli_query($this->con, $sql);

			if($result->num_rows == 0){
				return array();
			}

			//IF NOT RESULTS RETURN EMPTY ARRAY
			while ($row = $result->fetch_assoc()) {
	   			$data[] = $row;
	    	}

	    	//RETURN
			return $data;
		}


		//create a new group
		public function createGroup($title, $ids, $type){
			$ids = rtrim(implode(',', $ids), ',');
			$title = mysqli_real_escape_string($this->con, $title);
			$sql = "INSERT INTO sharegroups (userid,title,users,type,createdat,updatedat) VALUES ($this->userid,'$title','$ids','$type','$this->now','$this->now')";
			$result = mysqli_query($this->con, $sql);
			$groupid = mysqli_insert_id($this->con);
			return $groupid;
		}


		//save group
		public function saveGroup($id, $ids){
			$ids = rtrim(implode(',', $ids), ',');
			$sql = "UPDATE sharegroups SET  users = '$ids' WHERE id = $id";
			$result = mysqli_query($this->con, $sql);
			return 1;
		}


		//members
		public function getMembers($id){
			$sql = "SELECT users FROM  sharegroups WHERE id = $id";
			$result = mysqli_query($this->con, $sql);
			$row = $result->fetch_assoc();

			return $row['users'];
		}


		//delete
		public function deleteGroup($id){
			$sql = "UPDATE sharegroups SET deletedat = '$this->now' WHERE id = $id";
			$result = mysqli_query($this->con, $sql);
			$row = $result->fetch_assoc();

			return 1;
		}

	}
?>