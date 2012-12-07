<?php
/* Author: Anzu
 * Description: Checks to see if client IP is 
 * in the blocklist.  If it is, it redirects 
 * them to another page.
*/


require_once('/modules/login_connect.php');

$blocked_ips = array();
$client_ip = getClientIP();

$queryCheckIP = 'SELECT `ip` FROM `blocked_ips`';
$result = $dbhandle2->query($queryCheckIP);
foreach ($result as $row) {
	$blocked_ips[] = $row['ip'];
}

if (in_array($client_ip, $blocked_ips)) {
	header('Location: http://google.com');
	exit;
}


function getClientIP() {
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}


?>