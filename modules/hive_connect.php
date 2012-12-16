<?php
//require("/session.php");
//require("/config.php");
@session_start();
if (!isset($_SESSION['user_id'])) {
	header('Location: loginform.php');
	die();
}

//function hiveconnect($hive_address,$hive_db,$hive_user,$hive_pass) {
try {
	$dbhandle = new PDO("mysql:host=$hostname;dbname=$dbName", $username, $password);
} catch (PDOException $err) {
	die($err->getMessage());
}
//return $dbhandle;
//}

?>
