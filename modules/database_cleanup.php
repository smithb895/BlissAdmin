<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
/*
Database Cleanup Functions
Author: Anzu
*/
require_once('../config.php');
require_once('hive_connect.php');
global $dbhandle;

if (isset($_GET['cleanup_type'])) {
	//$cleanup_type = preg_replace('#[^0-9]#', '', $_GET['cleanup_type']);
	$cleanup_type = $_GET['cleanup_type'];
	if (preg_match('#[^0-9]#', $cleanup_type)) {
		die('Invalid cleanup type specified');
	}
	switch ($cleanup_type) {
		case 0:	// Clean dead players
			$dbhandle->exec('SET FOREIGN_KEY_CHECKS=0');
			$query = $dbhandle->prepare('DELETE FROM survivor WHERE is_dead=1');
			$query->execute();
			$dbhandle->exec('SET FOREIGN_KEY_CHECKS=1');
			break;
		case 1:	// Remove fully damaged vehicles
			$query = $dbhandle->prepare('DELETE FROM instance_vehicle WHERE damage=1');
			$query->execute();
			break;
		case 2:	// Remove wire fencing
			$query = $dbhandle->prepare('DELETE FROM instance_deployable WHERE deployable_id=3');
			$query->execute();
			break;
		case 3:	// Remove empty tents
			
			break;
		case 4:	// Delete ALL vehicles for instance
			$query = $dbhandle->prepare('DELETE FROM instance_vehicle WHERE instance_id=?');
			break;
		default:
			//echo 
			break;
	}
} else {
	die('Invalid cleanup type specified');
}

function deleteAllVehicles() {
	//$query = $dbhandle->prepare();
	//$query->execute();
}
function deleteEmptyTents() {
	
}




?>