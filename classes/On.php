<?php
	class On{
		protected $con;
		protected $userid;
		protected $tokenid;
		protected $now;
		protected $baseurl = 'https://showseeker.s3.amazonaws.com/on/';
		#protected $baseurl = 'http://50.57.74.41/';

		//https://showseeker.s3.amazonaws.com/on/
		//build the basic information about the logged in user
		#			AND onGalleryMedia.height = 360 AND onGalleryMedia.width = 240
		public function __construct($con, $userid, $tokenid){
			$this->con = $con;
			$this->userid = $userid;
			$this->tokenid = $tokenid;
			$this->now = date('Y-m-d H:i:s');
		}


		public function getImageSizeShowID($showid){
			//select the database
			mysqli_select_db($this->con,"On");
			
			$showid = $this->validShowid($showid);

			$sql = "SELECT * FROM GalleryTVBanner WHERE TMSId = '$showid' AND action != 'delete' AND process = 1 GROUP BY width, height ORDER BY lastModified";

			$result = mysqli_query($this->con, $sql);


			if($result->num_rows == 0){
				return '/images/default-360-240.png';
			}

			while ($row = $result->fetch_assoc()) {
				$baseimg = $row['URI'];
				$path = $this->setpath($row['category']);
				$url =  $this->baseurl.$path.$baseimg;
				$row['fullPath'] = $url;
				$re[] = $row;
			}
			return $re;
		}
		
		
		public function getCover($showid,$width,$height){

			mysqli_select_db($this->con,"On");
			
			$showid = $this->validShowid($showid);

			$sql = "SELECT 			* 
					FROM 			GalleryTVBanner 
					WHERE TMSId  = 	'$showid' 
					AND action 	!= 	'delete' 
					AND process  = 	1 
					AND width 	 = 	$width 
					AND height   = 	$height 
					AND category IN ('Banner','Box Art','Poster Art') 
					ORDER BY 		lastModified LIMIT 1";
				
			mysqli_select_db($this->con,"On");
			
			$result 	= mysqli_query($this->con, $sql);
			$row_cnt 	= $result->num_rows;

			if($row_cnt == 0){
				$img = '<img width=170 src=i/default.png>';
				return array("cover"=>$img);
			}

			$row 	 = $result->fetch_assoc();
			$baseimg = $row['URI'];
			$path 	 = $this->setpath($row['category']);
			$url 	 = $this->baseurl.$path.$baseimg;
			$row['fullPath'] = $url;
			
			$img = '<img width=170 src='.$row['fullPath'].'>';

			$re = array("cover"=>$img);
			
			return $re;
		}


		public 	function getCoverImage($id){
			$baseurl = 'https://plusapi.showseeker.com/show/image/240/360/';
			$showimg = $baseurl.$id.'/null';
			$jsondat = file_get_contents($showimg);
			$jdec = json_decode($jsondat);

			if($jdec->result == true){
				$img = '<img width=170 src='.$jdec->cover.'>';
				$re = array("cover"=>$img);
				return $re;				
			}
			else{
				$img = '<img width=170 src=i/default.png>';
				return array("cover"=>$img);
			}
		}		
		
		
		
		

		public function getmovieimagebyshowid($showid){
			//select the database
			mysqli_select_db($this->con,"Yoda");
			
			//$showid = $this->validShowid($showid);

			$sql = "SELECT * FROM themoviedb WHERE tmsid = '$showid'";
			$result = mysqli_query($this->con, $sql);


			if($result->num_rows == 0){
				return '/images/default-360-240.png';
			}

			while ($row = $result->fetch_assoc()) {
				#$baseimg = $row['URI'];
				#$path = $this->setpath($row['category']);
				#$url =  $this->baseurl.$path.$baseimg;
				#uri = "https://image.tmdb.org/t/p/w130/"+poster
				$row['w185'] = "https://image.tmdb.org/t/p/w185".$row['poster'];
				$row['w130'] = "https://image.tmdb.org/t/p/w130".$row['poster'];
				$re[] = $row;
			}
			
			return $re;
		}





		public function getOnData($showid){
			//select the database
			mysqli_select_db($this->con,"On");

			//setup the derfault image
			$img = '/images/default-360-240.png';

			//if it is a short showid then lets make it the proper length
			if(strlen($showid) == 10){
				$showid = $showid.'0000';
			}

			//swap the EP with SH since there is no EP in the database
			$showid = str_replace("EP","SH",$showid);

			$sql = "SELECT * FROM GalleryTVBanner WHERE TMSId = '$showid' AND action != 'delete'";

			$result = mysqli_query($this->con, $sql);


			if($result->num_rows == 0){
				return '/images/default-360-240.png';
			}

			while ($row = $result->fetch_assoc()) {
				$baseimg = $row['URI'];
				$path = $this->setpath($row['category']);
				$url =  $this->baseurl.$path.$baseimg;
				$row['fullPath'] = $url;
				$re[] = $row;
			}
			return $re;
		}


		//create valid showid from whatever is passd in
		private function validShowid($showid){
			$type = substr($showid, 0, 2);
			
			if($type == "MV" || $type == "SP"){
				if(strlen($showid) == 10){
					$showid = $showid.'0000';
				}
				return $showid;
			}

			if($type == "SH" || $type == "EP"){
				$showid = str_replace("EP","SH",$showid);
				$tmp = substr($showid, 0, 10);
				$showid = $tmp.'0000';
				return $showid;
			}
		}


		//get the priper image path if needed
		private function setpath($i){
			switch ($i) {
		    case 'Poster Art':
				return 'photos/movieposters/';
		        break;
		    case 'Box Art':
		       	return 'photos/dvdboxart/';
		       	break;
		    case 'Banner':
		       	return 'photos/tvbanners/';
		    case 'Logo':
		   		return 'db_photos/sportslogos/';
		       	break;
		       	
			}
		}



		//get local show
		private function getlocalshow($tmsid){
			$content = file_get_contents('http://i.showseeker.com/getshowinfo.php?showid='.$tmsid.'');
			$result = json_decode($content);
			return $result;
		}





	}
?>