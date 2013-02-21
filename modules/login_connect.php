<?php
//require("/session.php");
require("/../config.php");

//function hiveconnect($hive_address,$hive_db,$hive_user,$hive_pass) {
try {
	$dbhandle2 = new PDO("mysql:host=$adminsdb_address;dbname=$adminsdb_db", $adminsdb_user, $adminsdb_pass);
} catch (PDOException $err) {
	die($err->getMessage());
}
//return $dbhandle;
//}

?>
