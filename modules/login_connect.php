<?php
//require("/session.php");
require("/config.php");

//function hiveconnect($hive_address,$hive_db,$hive_user,$hive_pass) {
$dbhandle2 = new PDO("mysql:host=$adminsdb_address;dbname=$adminsdb_db", $adminsdb_user, $adminsdb_pass);
//return $dbhandle;
//}

?>
