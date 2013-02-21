<?php
/*
 * -={AWG}=- DayZ Hive Admin
 * Author: Anzu
 * Description: This script will fetch server instance 
 * information, given an instance number, and return 
 * the response in JSON.
 *
*/
require_once('../config.php');
require_once('hive_connect.php');
//require('dayz_servers.php');
global $DayZ_Servers;


if (isset($_GET['instance'])) {
	//echo "test1";
	$instanceID = (int) $_GET['instance'];
	//echo $instanceID;
	if (strlen($instanceID) < 8) {
		//echo "Test2";
		foreach ($DayZ_Servers as $server) {
			$_instance = $server->getMissionInstance();
			if ($_instance == $instanceID) {
				//echo "Test3";
				$serverip = $server->getServerIP();
				$serverport = $server->getServerPort();
				$mapname = $server->getServerMap();
				$worldid = $server->getWorldID();
				$servername = $server->getServerName();
				$return = array($servername,$mapname,$worldid,$serverip,$serverport);
				echo json_encode($return);
				//echo "Test";
			}
		}
	}
}











?>