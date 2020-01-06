<?php
	class Zones{
		protected $con;
		protected $userid;
		protected $tokenid;
		
		public function __construct($con, $userid, $tokenid){
			$this->con = $con;
			$this->userid = $userid;
			$this->tokenid = $tokenid;
		}

		//if the user is a national rep then lets make the sql pass back all the zones for the corporation id
		public function getzones($marketid,$nationalrep,$showmarkets,$corporationid){

			//if there is a userzone it will overwrite the others 
			$sql 					= "SELECT * from userzones where userid =".$this->userid."";
			$userzonesresult 	= mysqli_query($this->con, $sql);
										
			if($userzonesresult->num_rows > 0){
				$sql=  "SELECT 			zones.id as id, 
										zones.name, 
										zones.isdma, 
										(SELECT COUNT(networkid) FROM zonenetworks WHERE zoneid = zones.id AND deletedat IS NULL) AS netcnt 
							FROM 		userzones
							INNER JOIN 	zones 
							ON 			userzones.zoneid = zones.id AND userzones.deletedat is null AND zones.deletedat is null
							WHERE 		userzones.userid =".$this->userid." 
							AND  		zones.deletedat is null
							ORDER BY 	zones.name";			
			}
			else if($nationalrep == 1 || $showmarkets == 0){
				$sql = "SELECT DISTINCT zones.id, 
										zones.isdma, 
										zones.name, 
										(SELECT COUNT(networkid) FROM zonenetworks WHERE zoneid = zones.id AND deletedat IS NULL) AS netcnt 
						FROM 			regions
						 
						INNER JOIN 		marketzones 
						ON  			regions.id = marketzones.marketid and regions.deletedat is null 
						
						INNER JOIN 		zones
						ON 				marketzones.zoneid = zones.id and zones.deletedat is null 

						WHERE 			regions.corporationid = $corporationid 
						AND				regions.deletedat is null
						AND  			zones.deletedat is null
						ORDER BY 		zones.name";
			}
			else{
				$sql = "SELECT 			zones.id, 
										zones.name , 
										zones.isdma, 
										(SELECT COUNT(networkid) FROM zonenetworks WHERE zoneid = zones.id AND deletedat IS NULL) AS netcnt
						FROM 			marketzones
						INNER JOIN 		zones ON zones.id = marketzones.zoneid
						WHERE 			marketid = $marketid
						AND  			zones.deletedat is null
						ORDER BY 		zones.name";
			}


			$result = mysqli_query($this->con, $sql);
			
			//if no result then lets return blank
			if($result->num_rows == 0){
				return;
			}

			while ($row = $result->fetch_assoc()) {
	   			$data[] = $row;
	    	}
	    	return $data;
		}


		//get the networks list for a zone
		public function getzonenetworks($zoneid){
			$sql = "SELECT 
			zones.id AS zoneid,
			zones.name AS zonename,
			zones.syscode,
			zones.dmaid,
			zones.broadcast,
			zones.timezoneid AS tzid,
			timezones.name AS tzname,
			timezones.abbreviation AS tzabbreviation,
			zonenetworks.networkid AS id,
			tms_networks.callsign,
			tms_networks.name,
			coalesce(logos.filename,'default.gif') AS filename
			FROM zones
			INNER JOIN timezones ON timezones.id = zones.timezoneid
			INNER JOIN zonenetworks ON zones.id = zonenetworks.zoneid
			INNER JOIN tms_networks ON zonenetworks.networkid = tms_networks.networkid
			LEFT JOIN networklogos ON networklogos.networkid = tms_networks.networkid
			LEFT JOIN logos ON logos.id = networklogos.logoid
			WHERE zones.id = $zoneid AND zonenetworks.deletedat IS NULL AND zones.deletedat IS NULL
			ORDER BY tms_networks.callsign";

			$result = mysqli_query($this->con, $sql);

			//if no result then lets return blank
			if($result->num_rows == 0){
				return array();
			}

			//loop the results and populate the network list
			while ($row = $result->fetch_assoc()) {
	   			$data[] = $row;
	    	}
	    	return $data;
		}


		//get the information about the zone
		public function getzoneinfo($zoneid){
			$sql = "SELECT 
			zones.id,
			zones.name AS zonename,
			zones.syscode,
			zones.dmaid,
			zones.broadcast,
			zones.timezoneid AS tzid,
			timezones.name AS tzname,
			timezones.abbreviation AS tzabbreviation
			FROM zones
			INNER JOIN timezones ON timezones.id = zones.timezoneid
			WHERE zones.id = $zoneid";

			$result = mysqli_query($this->con, $sql);

			$row = $result->fetch_assoc();

			return $row;
		}





		//get the networks list for a zone
		public function getnetworksbytimezone($tzabr){

			$sql = "SELECT zonenetworks.networkid, tms_networks.name, tms_networks.callsign, timezones.abbreviation, timezones.name
			FROM zonenetworks
			INNER JOIN zones ON zones.id = zonenetworks.zoneid
			INNER JOIN tms_networks ON tms_networks.networkid = zonenetworks.networkid
			INNER JOIN timezones ON timezones.id = zones.timezoneid
			WHERE timezones.abbreviation = '$tzabr' AND tms_networks.dmanumber = 0
			GROUP BY zonenetworks.networkid
			ORDER BY tms_networks.callsign";

			$result = mysqli_query($this->con, $sql);

			//if no result then lets return blank
			if($result->num_rows == 0){
				return array();
			}

			//loop the results and populate the network list
			while ($row = $result->fetch_assoc()) {
	   			$data[] = $row;
	    	}
	    	return $data;
		}


		//get the networks list for a zone
		public function getnetworklistnodma(){

			$sql = "SELECT zonenetworks.networkid, tms_networks.name, tms_networks.callsign, timezones.abbreviation, timezones.name
			FROM zonenetworks
			INNER JOIN zones ON zones.id = zonenetworks.zoneid
			INNER JOIN tms_networks ON tms_networks.networkid = zonenetworks.networkid
			INNER JOIN timezones ON timezones.id = zones.timezoneid
			WHERE tms_networks.dmanumber = 0
			GROUP BY zonenetworks.networkid
			ORDER BY tms_networks.callsign";

			$result = mysqli_query($this->con, $sql);

			//if no result then lets return blank
			if($result->num_rows == 0){
				return array();
			}

			//loop the results and populate the network list
			while ($row = $result->fetch_assoc()) {
	   			$data[] = $row;
	    	}
	    	return $data;
		}


	}
?>