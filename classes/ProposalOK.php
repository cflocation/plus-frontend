<?php
	class Proposal{
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
		public function getproposals(){
			$sql = "SELECT
			proposals.id, 
			proposals.name, 
			proposals.total, 
			proposals.zones AS zone, 
			proposals.linesttl, 
			proposals.spots, 
			proposals.discountid,
			proposals.grossttl, 
			proposals.netttl, 
			proposals.amount,
			proposals.startdate AS fstart,
			proposals.enddate AS fend,
			proposals.createdat AS created,
			DATE_FORMAT(proposals.updatedat, '%m/%d/%Y %h:%i %p') AS updatedat
			FROM proposals 
			WHERE proposals.userid = $this->userid AND proposals.deletedat IS NULL
			ORDER BY proposals.createdat DESC";
			
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


		//get proposal by id
		public function getproposalbyid($id){
			$sql = "SELECT *
				FROM users INNER JOIN proposals ON users.id = proposals.userid
				WHERE users.id = $this->userid AND users.tokenid = '$this->tokenid' AND proposals.id = $id
				ORDER BY proposals.createdat DESC
				LIMIT 1";

			$result = mysqli_query($this->con, $sql);

			//GET THE PROPOSAL FOR THE RETURN
			$data = $result->fetch_assoc();
			
			//RETURN
	    	return $data;
		}



		//rename proposal by id
		public function renameproposal($id, $name){
			$name = $this->con->real_escape_string($name);
			$sql = "UPDATE proposals SET name = '$name' WHERE userid = $this->userid AND id = $id";
			$result = mysqli_query($this->con, $sql);
			
			//RETURN
	    	return $result;
		}




		//create proposal
		public function createproposals($proposal, $weeks, $name){
			$proposal = $this->con->real_escape_string($proposal);
			$sql = "INSERT INTO proposals (userid, name, proposal, weeks, createdat, updatedat)VALUES ($this->userid, '$name','$proposal','$weeks','$this->now','$this->now')";
			$result = mysqli_query($this->con, $sql);

			//RETURN
	    	return $this->con->insert_id;
		}



		//remove proposals
		public function deleteproposals($ids){
			//GET ROWS FROM THE POST
			$rows = json_decode($ids);

			//LOOP OVER THE RECORDS AND REMOVE THEM
			foreach ($rows as &$value) {
				$sql = "UPDATE proposals SET deletedat = '". date("Y-m-d H:i:s")."' WHERE id = ".$value." AND userid = ".$this->userid."";
				$result = mysqli_query($this->con, $sql);
			}

			//LOG EVENT
			$sql = "INSERT INTO eventlogs (userid,eventslogid,request,result,createdat, updatedat)VALUES ({$this->userid}, 3,'{$rows}','{$rows}','{$this->now}','{$this->now}')";
			$result = mysqli_query($this->con, $sql);

			//RETURN
	    	return $result;
		}



		//save proposal
		public function save($id, $proposal, $discountpackagetype, $discountpackage, $discountagency, $weeks, $proposalTotalInfoSpots, $proposalTotalInfoGross, $proposalTotalInfoNet, $proposalTotalInfoAgencyDisc,$proposalTotalInfoPackageDisc,$proposalTotalInfoLineCount,$proposalTotalInfoStartDate,$proposalTotalInfoEndDate,$proposalTotalInfoZones,$zones){
			$proposal = $this->con->real_escape_string($proposal);
		
			$sql = "UPDATE proposals SET 
			proposal='{$proposal}',
			spots='".$proposalTotalInfoSpots."',
			grossttl='".$proposalTotalInfoGross."',
			netttl='".$proposalTotalInfoNet."',
			agcdisc='".$proposalTotalInfoAgencyDisc."',
			pkgsdisc='".$proposalTotalInfoPackageDisc."',
			linesttl='".$proposalTotalInfoLineCount."',
			startdate=".$proposalTotalInfoStartDate.",
			enddate=".$proposalTotalInfoEndDate.",
			zones='".$zones."',
			weeks='".$weeks."',
			discountpackagetype='".$discountpackagetype."',
			discountpackage='".$discountpackage."',
			discountagency='".$discountagency."',
			updatedat='".$this->now."' 
			WHERE id = $id AND userid = $this->userid";

			$result = mysqli_query($this->con, $sql);
			
			//RETURN
	    	return $result;
		}



	}
?>