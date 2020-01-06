<?php
	class Share{
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


		//proposal
		public function proposal($items, $type, $message, $users){
			//set the message
			$message = $this->con->real_escape_string($message);

			foreach ($items as &$item) {
				$subject = $item->name;
				$subject = $this->con->real_escape_string($subject);
				
				$id = $item->id;
				
				$sql = "SELECT * FROM proposals WHERE id = ".$id." LIMIT 1";
				$result = mysqli_query($this->con, $sql);

				//get the proposal info from the database
				$data = $result->fetch_assoc();

				//proposal
				$content = $this->con->real_escape_string($data['proposal']);


				foreach ($users as &$user) {
					$userid = $user->id;
					$sql = "INSERT INTO messages (sendid, userid, type, subject, message, content, createdat, updatedat)
					VALUES ($this->userid, $userid,'{$type}','{$subject}','{$message}','{$content}','{$this->now}','{$this->now}')";
					$result = mysqli_query($this->con, $sql);
				}

			}
		}




	}
?>