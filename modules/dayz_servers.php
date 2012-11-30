<?php
//include('/modules/hive_connect.php');
class Dayz_Server {
	var $_servername;
	var $_serverip;
	var $_serverport;
	var $_rconpassword;
	var $_servermap;
	var $_worldid;
	var $_missioninstance;

	public function __construct($name,$ip,$port,$rconpass,$instance,$map) {
		global $DayZ_Servers;
		$DayZ_Servers[] = $this;
		$this->_servername = $name;
		$this->_serverip = $ip;
		$this->_serverport = $port;
		$this->_rconpassword = $rconpass;
		$this->_missioninstance = $instance;
		//$this->queryServerMap();
		$this->_servermap = $map;
	}
	/* Methods for getting server info */
	public function getServerName() {
		return $this->_servername;
	}
	public function getServerIP() {
		return $this->_serverip;
	}
	public function getServerPort() {
		return $this->_serverport;
	}
	public function getRconPassword() {
		return $this->_rconpassword;
	}
	public function getMissionInstance() {
		return $this->_missioninstance;
	}
	public function getServerMap() {
		return $this->_servermap;
	}
	public function getWorldID() {
		return $this->_worldid;
	}
	public function queryServerMap() {
		global $hostname;
		global $dbName;
		global $username;
		global $password;
		$_dbhandle = new PDO("mysql:host=$hostname;dbname=$dbName", $username, $password);
		$query = $_dbhandle->prepare("SELECT w.name,w.id FROM world w JOIN instance i ON w.id=i.world_id WHERE i.id=?");
		$query->execute(array($this->_missioninstance));
		$res = $query->fetch(PDO::FETCH_NUM);
		$this->_servermap = $res[0];
		$this->_worldid = $res[1];
	}
}
?>