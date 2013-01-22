<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

//if (isset($_SESSION['user_id'])) {
	include_once('/../config.php');
	require('functions.php');
	include_once('bans_connect.php');
	
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
	
	if (isset($_POST['type'])) {
		if (preg_match('#[^0-9a-z_+]#i', $_POST['type'])) {
			die('Invalid POST value');
		}
		$searchType = $_POST['type'];
	} else {
		die('Search type not specified');
	}
	if (isset($_POST['search'])) {
		$searchString = $_POST['search'];
		if (preg_match('#[^\w\.+]#i', $searchString)) {
			die('Invalid characters in search string');
		}
		if ($searchType != 'admin') {
			if (strlen($searchString) < 3) {
				die('<tr><td colspan="8">Search string too short (must be at least 3 chars).</td></tr>');
			}		
		}
		switch ($searchType) {
			case 'guidip':
				$column = '`GUID_IP`';
				break;
			case 'known_names':
				$column = '`KNOWN_NAMES`';
				break;
			case 'admin':
				$column = '`ADMIN`';
				break;
			case 'reason':
				$column = '`REASON`';
				break;
			default:
				$column = '`GUID_IP`';
				break;
		}
		$queryString = stringSplitSQL($searchString, $column);
		
		// Bans search qry template
		//$querySearchBans = $dbhandle3->prepare("SELECT * FROM bans WHERE ? LIKE ? ORDER BY `ID` DESC LIMIT ?,?");
		
		// Ugly, but it works... :-/
		$querySearchBans = $dbhandle3->prepare("SELECT * FROM bans WHERE ".$column." LIKE ".$queryString." ORDER BY `ID` DESC LIMIT ".$start.",".$per_page);
		$queryHandle2 = $dbhandle3->prepare("SELECT COUNT(*) FROM `bans` WHERE ".$column." LIKE ".$queryString);

	
		// Argh, this method was giving me all sorts of trouble so i resorted to above
		//$querySearchBans->bindParam(1, $column, PDO::PARAM_STR);
		//$querySearchBans->bindParam(2, $queryString, PDO::PARAM_STR);
		//$querySearchBans->bindParam(3, $start, PDO::PARAM_INT);
		//$querySearchBans->bindParam(4, $per_page, PDO::PARAM_INT);
		//$querySearchBans->execute();
		// Results count qry

		//$queryHandle2->bindParam(':column', $column);
		//$queryHandle2->bindParam(':querystring', $queryString);
		
		$queryHandle2->execute();
		$count = $queryHandle2->fetchColumn();
		//$count = 1;
		if ($count > 0) {
			$querySearchBans->execute();
			$msg = fetchBanRows($querySearchBans,$count,$cur_page,$page,$per_page);
		} else {
			//$msg = $queryString;
			$msg = '<tr><td colspan="8">No results found.</td></tr>';
		}
		//$msg = $queryString;
		echo $msg;
		//echo $queryString;
	} else {
		die('No search string specified.');
	}
//} else {
//	header('Location: admin.php');
//}
?>