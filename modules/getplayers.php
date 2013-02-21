<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);

include_once('/../config.php');
//include_once('hive_connect.php');
//include_once('rcon.php');
require('functions.php');
include_once('bans_connect.php');
//global $DayZ_Servers;
//global $serverip;
//global $serverport;
//global $rconpassword;

if (isset($_POST['page'])) {
	$page = preg_replace('#[^0-9+]#', '', $_POST['page']);
} else {
	$page = 1;
}
$cur_page = $page;
$page -= 1;
$per_page = 50;
$previous_btn = true;
$next_btn = true;
$first_btn = true;
$last_btn = true;
$start = $page * $per_page;



if (isset($_POST['search'])) {
	$searchString = $_POST['search'];
	if (preg_match('#[^\w\.\{\}\[\]\(\) +]#i', $searchString)) {
		die('<tr><td colspan="8">Invalid characters in search string.</td></tr>');
	}
	if (strlen($searchString) < 3) {
		die('<tr><td colspan="8">Search string too short (must be at least 3 chars).</td></tr>');
	}
	if (isset($_POST['type'])) {
		if (preg_match('#[^0-9a-z_+]#i', $_POST['type'])) {
			die('<tr><td colspan="8">Invalid POST value</td></tr>');
		}
		$searchType = $_POST['type'];
	} else {
		die('<tr><td colspan="8">Search type not specified</td></tr>');
	}
	switch ($searchType) {
		case 'guid':
			$column = '`GUID`';
			break;
		case 'known_names':
			$column = '`KNOWN_NAMES`';
			break;
		case 'known_ips':
			$column = '`KNOWN_IPS`';
			break;
		default:
			$column = '`GUID`';
			break;
	}
	$queryString = stringSplitSQL($searchString, $column);
	
	// Ugly, but it works... :-/
	$querySearchCount = $dbhandle3->prepare("SELECT COUNT(*) FROM `players` WHERE ".$column." LIKE ".$queryString);
	$querySearchCount->execute();
	$count = $querySearchCount->fetchColumn();
	if ($count > 0) {
		$querySearchPlayerDB = $dbhandle3->prepare("SELECT * FROM `players` WHERE ".$column." LIKE ".$queryString." ORDER BY `LAST_SEEN` DESC LIMIT ".$start.",".$per_page);
		$querySearchPlayerDB->execute();
		
		$msg = fetchPlayerDBRows($querySearchPlayerDB,$count,$cur_page,$page,$per_page);
	} else {
		$msg = '<tr><td colspan="8">No results found.</td></tr>';
	}
	
} else {
	/* -----Total count--- */
	$query_pag_num = 'SELECT COUNT(*) FROM `players`'; // Total records
	$queryHandle2 = $dbhandle3->prepare($query_pag_num);
	$queryHandle2->execute();
	$count = $queryHandle2->fetchColumn();
	if ($count > 0) {
		$queryHandle = $dbhandle3->prepare("SELECT * FROM `players` ORDER BY `LAST_SEEN` DESC LIMIT :start,:per_page");
		$queryHandle->bindParam(':start', $start, PDO::PARAM_INT);
		$queryHandle->bindParam(':per_page', $per_page, PDO::PARAM_INT);
		$queryHandle->execute();
		
		$msg = fetchPlayerDBRows($queryHandle,$count,$cur_page,$page,$per_page);
	} else {
		$msg = '<tr><td colspan="8">No results found.</td></tr>';
	}
}

//$result_pag_num = mysql_query($query_pag_num);
//$row = mysql_fetch_array($result_pag_num);
//$count = $row['count'];


//$msg = "Hello";
echo $msg;



?>