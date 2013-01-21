<?php
//require("/session.php");
require("/../config.php");

try {
	$dbhandle3 = new PDO("mysql:host=$bansdb_address;dbname=$bansdb_db", $bansdb_user, $bansdb_pass);
} catch (PDOException $err) {
	die($err->getMessage());
}

?>
