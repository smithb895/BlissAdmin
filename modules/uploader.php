<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

require('../session.php');
require('../config.php');
require('hive_connect.php');
require('import_vehicles.php');

$worldID = preg_replace('#[^0-9]#', '', $_POST['worldID']);

$target_path = 'uploads/';

$target_path = $target_path . basename($_FILES['missionfile']['name']);

if (move_uploaded_file($_FILES['missionfile']['tmp_name'], $target_path)) {
	echo "The file " . basename($_FILES['missionfile']['name']) . " hase been uploaded to $target_path<br />";
	echo "Importing mission.sqm into database...<br />";
	import_mission_spawns($dbhandle,$target_path,$worldID);
	echo 'Done :)<br />';
	//echo $_POST['instance'];
} else {
	echo "There was an error uploading the file, please try again in a few moments";
}





?>