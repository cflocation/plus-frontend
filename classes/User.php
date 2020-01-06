<?php
	class User{
		protected $con;
		protected $userid;
		protected $tokenid;
		protected $userinfo;
		protected $roles;
		protected $corporationid;
		protected $settings;
		protected $marketid;
		protected $showmarkets;

		//build the basic information about the logged in user
		public function __construct($con, $userid, $tokenid){
			$this->con = $con;
			$this->userid = $userid;
			$this->tokenid = $tokenid;
			

			$sql = "SELECT	officedefaults.userid AS userid,
							officedefaults.officeid AS defaultoffice,
							users.corporationid,  
							users.firstname, 
							users.lastname, 
							users.altid1,
							regions.iseeker, 
							regions.name,
							regions.id AS regionsid,
							corporations.ratecards,
							corporations.ratecardmode,
							corporations.showmarkets
			FROM 			officedefaults
			INNER JOIN 		users 
			ON 				users.id = officedefaults.userid
			INNER JOIN 		corporations 
			ON 				users.corporationid = corporations.id
			INNER JOIN 		offices 
			ON 				officedefaults.officeid = offices.id
			INNER JOIN 		regions 
			ON 				offices.regionid = regions.id
			WHERE 			users.id = $this->userid 
			AND 				users.tokenid = '$this->tokenid'";

			$result = mysqli_query($this->con, $sql);
			$row = $result->fetch_assoc();

			//set the user id
			$this->userid = $row['userid'];

			//set the user information 
			$this->userinfo = $row;

			//set the corporation id
			$this->corporationid = $row['corporationid'];

			//show the markets
			$this->showmarkets = $row['showmarkets'];

			//show the markets
			$this->markets = $this->getmarkets();

			
			//set the user roles
			$this->roles = $this->getroles();

			//set the user settings
			$this->settings = $this->setsettings();
		}



		//if the user is a national rep then lets make the sql pass back all the zones for the corporation id
		public function getmarkets(){
			
			$sql = "select * from userroles where userid = {$this->userid} and roleid = 15";
			
			$result = mysqli_query($this->con, $sql); 
			
			if($result->num_rows == 0){
			
				$sql = "SELECT  		regions.id, 
										regions.name
					
					FROM 				useroffices
					
					INNER JOIN 			offices 
					ON 					offices.id = useroffices.officeid
					
					INNER JOIN 			marketzones 
					ON 					marketzones.marketid = offices.regionid
					
					INNER JOIN 			regions 
					ON 					regions.id = marketzones.marketid
					
					WHERE 				userid = $this->userid
					
					AND 				regions.deletedat IS NULL
					GROUP BY 			regions.id
					ORDER BY  			regions.name";

		    }
		    else{//NATIONAL REP
				$sql = "SELECT  		regions.id, 
										regions.name
					
					FROM 				users  

					INNER JOIN 			corporations
					ON					users.corporationid = corporations.id
					
					INNER JOIN			regions
					ON					regions.corporationid = corporations.id
					
					WHERE 				users.id = $this->userid
					
					AND 				regions.deletedat IS NULL
					ORDER BY  			regions.name";			    
			    
			    
		    }

			$result = mysqli_query($this->con, $sql);
	
			while ($row = $result->fetch_assoc()) {
	   			$data[] = $row;
	    	}		    
		    
		    
	    	return $data;
		}




		//get the interface buttons for the logged in user
		public function buttons(){
			$sql = "SELECT exports.name, exports.id
			FROM corporationexports
			INNER JOIN exports ON exports.id = corporationexports.exportid
			WHERE  corporationexports.corporationid = $this->corporationid";

			$result = mysqli_query($this->con, $sql);

			while ($row = $result->fetch_assoc()) {
	   			$row['icon'] = "#download-images-".$row['name'];
	    		$re[] = $row;
	    	}

			return $re;
		}


		//return the number of messages for hte user
		public function messagecnt(){
			$sql = "SELECT * FROM messages WHERE userid = $this->userid AND hasread = 0";
			$result = mysqli_query($this->con, $sql);

			return $result->num_rows;
		}

		//get the user settings
		public function getsettings(){
			return $this->settings;
		}

		//get the user information
		public function getuserinfo(){
			return $this->userinfo;
		}


		//is the user inside a specific role if so lets retun 1
		public function inrole($roleid){
			foreach ($this->roles as &$value) {
    			if($value['roleid'] == $roleid){
    				return 1;
    			}
			}
			return 0;
		}


		//setup the user settings
		private function setsettings(){
			$sql = "SELECT settings FROM user_settings WHERE userid = $this->userid";
			$result = mysqli_query($this->con, $sql);


			//if no result then lets return blank
			if($result->num_rows == 0){
				$re = array("lastZoneId"=>0,"lastMarketId"=>0, "resultsGroup" =>'off',"hiddenColumns"=>[]);
				return $re;
			}


			$row = $result->fetch_assoc();

			$data = json_decode($row['settings']);

			if(!property_exists($data, 'lastZoneId')){
				$data->lastZoneId = 0;
			}

			if(!property_exists($data, 'lastMarketId')){
				$data->lastMarketId = 0;
			}

			if(!property_exists($data, 'resultsGroup')){
				$data->resultsGroup = 'off';
			}
			if(!property_exists($data, 'hiddenColumns')){
				$data->hiddenColumns = [];
			}

			return $data;
		}


		//get the list of the roles for the user and set it in the class file
		private function getroles(){
			$sql = "SELECT roleid FROM userroles WHERE userid = $this->userid";
			$result = mysqli_query($this->con, $sql);

	   		while ($row = $result->fetch_assoc()) {
	   			$data[] = $row;
	    	}
	    	return $data;
		}


		//get the list of the roles for the user and set it in the class file
		public function getrolelist(){
			$sql = "SELECT roleid, roles.alias, roles.path
			FROM userroles
			INNER JOIN roles ON roles.id = userroles.roleid 
			WHERE userid = $this->userid
			ORDER BY roles.alias";
			$result = mysqli_query($this->con, $sql);

	   		while ($row = $result->fetch_assoc()) {
	   			$data[] = $row;
	    	}
	    	return $data;
		}




		//save the user settings to the database and pass back the result of the save
		public function saveusersettingsOK($settings){
			$sql = "
			INSERT INTO 
			user_settings (userid, settings)
			values ($this->userid, '$settings') 
			ON DUPLICATE KEY UPDATE settings = '$settings';
			";

			$result = mysqli_query($this->con, $sql);

			return $result;
		}



		//users by office
		public function getUsersByOffice(){
			$officeid = $this->userinfo['defaultoffice'];

			$sql = 'SELECT 				regions.id AS regionid, 
										users.corporationid, 
										users.id AS id, 
										users.firstname, 
										users.lastname, 
										users.email, 
										users.title, 
										users.phone, 
										offices.name AS office, 
										regions.name,
										regions.name as market, 
										officedefaults.officeid, 
										offices.regionid
			
					FROM 				users 
					
					INNER  JOIN 		officedefaults 
					ON 					users.id = officedefaults.userid

					INNER JOIN 			offices 
					ON 					officedefaults.officeid = offices.id
					
					INNER JOIN 			regions 
					ON 					offices.regionid = regions.id
					
					WHERE 				users.deletedat 	IS NULL 
					AND					offices.deletedat 	IS NULL
					AND					regions.deletedat 	IS NULL
					AND 				officedefaults.officeid = '.$officeid.'
					
					GROUP BY 			users.id
					ORDER BY 			regions.name, offices.name, users.firstname, users.lastname ASC';


			$result = mysqli_query($this->con, $sql);

	   		while ($row = $result->fetch_assoc()) {
	   			$data[] = $row;
	    	}
	    	return $data;
		}



		//users by market
		public function getUsersByMarket(){
			$marketid = $this->userinfo['regionsid'];

			$sql = 'SELECT 				regions.id AS regionid, 
										users.corporationid, 
										users.id AS id, 
										users.firstname, 
										users.lastname, 
										users.email, 
										users.title, 
										users.phone, 
										offices.name AS office, 
										regions.name, 
										regions.name as market, 
										officedefaults.officeid, 
										offices.regionid
					
					FROM 				users 
					
					INNER JOIN 			officedefaults 
					ON 					users.id = officedefaults.userid
					
					INNER JOIN 			offices 
					ON 					offices.id = officedefaults.officeid
					
					INNER JOIN 			regions 
					ON 					offices.regionid = regions.id
					
					WHERE 				regions.id = '.$marketid.' 
					AND 				regions.deletedat 	IS NULL
					AND					offices.deletedat 	IS NULL
					AND 				users.deletedat IS NULL

					
					GROUP BY users.id
					ORDER BY regions.name, offices.name, users.firstname, users.lastname ASC';

			$result = mysqli_query($this->con, $sql);

	   		while ($row = $result->fetch_assoc()) {
	   			$data[] = $row;
	    	}
	    	return $data;
		}



		//users by corporation
		public function getUsersByCorporation(){
			$corporationid = $this->userinfo['corporationid'];
			//SQL
			$sql = 'SELECT regions.id AS regionid, 
			users.corporationid, 
			users.id AS id, 
			users.firstname, 
			users.lastname, 
			users.email, 
			users.title, 
			users.phone, 
			offices.name AS office, 
			regions.name, 
			regions.name as market, 
			useroffices.officeid, 
			offices.regionid
			FROM useroffices RIGHT OUTER JOIN users ON useroffices.userid = users.id
			INNER JOIN offices ON offices.id = useroffices.officeid
			INNER JOIN regions ON offices.regionid = regions.id
			WHERE users.deletedat IS NULL AND users.corporationid = '.$corporationid.'
			GROUP BY users.id
			ORDER BY regions.name, offices.name, users.firstname, users.lastname ASC';

			$result = mysqli_query($this->con, $sql);

	   		while ($row = $result->fetch_assoc()) {
	   			$data[] = $row;
	    	}
	    	return $data;
		}

		
		public function getUserToken(){
	
			return $this->tokenid;
		}
		
		

		//save the user settings to the database and pass back the result of the save
		public function saveusersettings($settings){
			$usrSettings = json_decode($settings);
			$usrZone = $usrSettings->lastZoneId;
			$usrMkt = $usrSettings->lastMarketId;			
			$markets = $this->validateMarket($usrMkt,$usrZone,$this->userid);
			
			if($markets < 1){
				return false;	
			}
			
			$sql = "
			INSERT INTO 
			user_settings (userid, settings)
			values ($this->userid, '$settings') 
			ON DUPLICATE KEY UPDATE settings = '$settings';
			";

			$result = mysqli_query($this->con, $sql);

			return $result;
		}		
		public function validateMarket($marketId,$zoneId,$userId){
			$sql = 	"select regions.name, zones.name
					from regions
					inner join marketzones
					on regions.id = marketzones.marketid
					inner join zones
					on marketzones.zoneid = zones.id
					inner join corporations
					on regions.corporationid = corporations.id
					inner join users 
					on corporations.id = users.corporationid
					where users.id = {$userId}
					and regions.id = {$marketId}
					and zones.id = {$zoneId}";
					
			$result = mysqli_query($this->con, $sql);
			
			return  $result->num_rows;
		}		
		


	}

/*
	ROLES
	"2","Super Admin"
	"3","Corporate Admin"
	"4","Market Admin"
	"5","Office Admin"
	"6","Users Admin"
	"17","Ratecards (Global)"
	"16","Ratecards"
	"15","National Rep"
	"14","User"
*/
?>