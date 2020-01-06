<?php
class database {
	
	private $_link;
	private $_host;
	private $_username;
	private $_password;
	private $_database_name;
	private $_error;
	
	function database($host,$username,$password,$database_name) {
		$this->_host = $host;
		$this->_username = $username;
		$this->_password = $password;
		$this->_database_name = $database_name;
	}
	
	function connect() {
		$this->_link  = mysql_connect($this->_host,$this->_username,$this->_password);
		
		if(!$this->_link) {
			$a = array('false','Could not connect to database');
		} 
		if (!mysql_select_db($this->_database_name,$this->_link)) {
  			$a = array('false','Database '. $this->_database_name .' not found');
		} else {
			$a = array('true');
		}
		return $a;
	}
	
	function execute($query,$retAffRows=false) {
		$res = mysql_query($query,$this->_link);
		//var_dump($res);
		//exit;
		
		if(!$res) {
			$this->_error = mysql_errno($this->_link);
			return false;
		} else {
			return ($retAffRows)?mysql_affected_rows($this->_link):true;
		}
	}
	
	function fetch_result($query) {
		if(!mysql_query($query,$this->_link)) {
			$this->_error = mysql_errno($this->_link);
			return false;
		} else {
			$res = array();
			$result = mysql_query($query,$this->_link);
			while($res1 = mysql_fetch_assoc($result))
			{
				$res[] = $res1;
			}
			return $res;
		}
	}
	
	function getError() {
		return $this->_error;
	}
	
	function insert($query,$retInsertId=false) {
		$res = mysql_query($query,$this->_link);
		
		if(!$res) {
			$this->_error = mysql_errno($this->_link);
			return false;
		}
		else {
			return ($retInsertId)?mysql_insert_id($this->_link):true;
		}
	}
	
}

	$db         = new database($dbHost , $dbUserName, $dbPassWord, $dbName);
	$connect    = $db->connect();
	
	if($connect[0] == 'false') {
		echo json_encode(array('could not connect to database'));
		die;
	} 
	else {}

?>