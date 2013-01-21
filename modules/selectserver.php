<?php
include_once('/config.php');

global $iid;
global $serverip;
global $serverport;
global $rconpassword;
global $map;
global $world;
global $selectserver;

if (isset($_GET['selectserver'])) {
	$selectserver = preg_replace('#[^0-9+]#', '', $_GET['selectserver']);
}


?>