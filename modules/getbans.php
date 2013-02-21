<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);

include_once('/../config.php');
include_once('/../session.php');
//include_once('hive_connect.php');
//include_once('rcon.php');
require('functions.php');
include_once('bans_connect.php');
include_once('login_connect.php');
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
	if ((strlen($searchString) < 3) && ($_POST['type'] != "admin")) {
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
	
	// Ugly, but it works... :-/
	$querySearchCount = $dbhandle3->prepare("SELECT COUNT(*) FROM `bans` WHERE ".$column." LIKE ".$queryString);
	$querySearchCount->execute();
	$count = $querySearchCount->fetchColumn();
	if ($count > 0) {
		$querySearchBansDB = $dbhandle3->prepare("SELECT * FROM `bans` WHERE ".$column." LIKE ".$queryString." ORDER BY `ID` DESC LIMIT ".$start.",".$per_page);
		$querySearchBansDB->execute();
		
		$msg = fetchBanRows($querySearchBansDB,$count,$cur_page,$page,$per_page);
	} else {
		$msg = '<tr><td colspan="8">No results found.</td></tr>';
	}
	
} elseif (isset($_POST['delban'])) {
	// If deleting a ban..
	$bansRemoved = 0;
	$bansToDelete = preg_replace('#[^0-9+]#', '', $_POST['delban']); // array
	for ($i=0; $i< count($bansToDelete); $i++) {
		// Get GUID or IP for ban to be removed
		$queryBan = $dbhandle3->prepare('SELECT `GUID_IP` FROM `bans` WHERE `ID`=?');
		$queryBan->execute(array($bansToDelete[$i]));
		$banGUID_IP = $queryBan->fetchColumn();
		
		// Log ban removal
		$queryLog = $dbhandle2->prepare("INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES (?,?,NOW())");
		$queryLog->execute(array('UNBAN: '.$banGUID_IP,$_SESSION['login']));
		
		// Remove ban
		$queryDelBan = $dbhandle3->prepare('UPDATE `bans` SET `ACTIVE`=0 WHERE `ID`=?');
		$queryDelBan->execute(array($bansToDelete[$i]));
		$bansRemoved += $queryDelBan->rowCount();
		
	}
	$msg = "Successfully removed $bansRemoved bans.<script>
						setTimeout(function() {
							$('#popup_msg').slideUp('fast');
							$('#popup_msg').html('');
							fetchDBRows('bans','none','none',1);
						}, 4000);
					</script>
			";
} elseif (isset($_POST['addban'])) {
	// If adding a ban..
	$bansAdded = 0;
	if (preg_match('#[^0-9a-f\.+]#', $_POST['addban'])) {
		die('Invalid characters in GUID/IP');
	}
	$banGUID_IP = $_POST['addban'];
	if (strlen($banGUID_IP) > 32) {
		die('GUID/IP is too long.  Max 32 characters.');
	}
	if (isset($_POST['reason'])) {
		$banREASON = preg_replace('#[^0-9a-z\., \[\]\{\}\(\)\-+]#i', '', $_POST['reason']);
		if (strlen($banREASON) > 64) {
			die('Ban reason is too long.  Must be 64 characters or less.');
		}
	} else {
		$banREASON = "Appeal at $siteForums";
	}
	switch ($_POST['banlength']) {
		case 1: // perm
			$banLENGTH = '-1';
			break;
		case 2: // 10 mins
			$banLENGTH = time() + (10 * 60);
			break;
		case 3: // 1 hour
			$banLENGTH =  time() + (60 * 60);
			break;
		case 4: // 1 day
			$banLENGTH = time() + (24 * 60 * 60);
			break;
		case 5: // 1 week
			$banLENGTH = time() + (7 * 24 * 60 * 60);
			break;
		case 6: // 1 month
			$banLENGTH = time() + (30 * 24 * 60 * 60);
			break;
		default:
			$banLENGTH = '-1';
			break;
	}
	
	/*
	if (isset($_POST['banlength'])) {
		$banLENGTH = preg_replace('#[^\-0-9+]#', '', $_POST['banlength']);
		if (strlen($banLENGTH) > 15) {
			die('Ban length too long.  Max 15 characters.');
		}
		
	} else {
		$banLENGTH = '-1';
	}
	*/
	
	//$banGUID_IP = '"'.$banGUID_IP.'"';
	// Check to make sure ban doesn't exists already
	$queryCheckExisting = $dbhandle3->prepare('SELECT `ID`,`ACTIVE`,`NUMBANS` FROM `bans` WHERE `GUID_IP`=?');
	$queryCheckExisting->bindValue(1, $banGUID_IP, PDO::PARAM_STR);
	$queryCheckExisting->execute();
	$banExistsResult = $queryCheckExisting->fetchAll(PDO::FETCH_ASSOC);
	
	
	
	if (count($banExistsResult) > 0) {
	//while ($banExists=$queryCheckExisting->fetch(PDO::FETCH_ASSOC)) {
		// Reban
		if ($banExistsResult[0]['ACTIVE'] == 0) {
			// log the ban
			$queryLog = $dbhandle2->prepare("INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES (?,?,NOW())");
			$queryLog->execute(array('BAN: '.$banGUID_IP,$_SESSION['login']));
			
			$banNumber = $banExistsResult[0]['NUMBANS'] + 1; // +1 to times player has been banned, for reban
			$queryReban = $dbhandle3->prepare("UPDATE `bans` SET `ACTIVE`=1,`NUMBANS`=? WHERE `ID`=?");
			$queryReban->bindParam(1, $banNumber, PDO::PARAM_INT);
			$queryReban->bindParam(2, $banExistsResult[0]['ID'], PDO::PARAM_INT);
			$queryReban->execute();
			
			$msg = "Successfully rebanned GUID/IP!
					<script>
						setTimeout(function() {
							$('#popup_msg').slideUp('fast');
							$('#popup_msg').html('');
							fetchDBRows('bans','none','none',1);
						}, 4000);
					</script>
					";
		} else {
			$msg = "That GUID/IP is already banned!";
		}
	} else {
		// log the ban
		$queryLog = $dbhandle2->prepare("INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES (?,?,NOW())");
		$queryLog->execute(array('BAN: '.$banGUID_IP,$_SESSION['login']));
		
		$queryAddBan = $dbhandle3->prepare("INSERT INTO `bans` (`GUID_IP`,`LENGTH`,`REASON`,`ADMIN`,`DATE_TIME`) VALUES (:guid_ip, :length, :reason, :admin, NOW())");
		$queryAddBan->bindParam(':guid_ip', $banGUID_IP, PDO::PARAM_STR);
		$queryAddBan->bindParam(':length', $banLENGTH, PDO::PARAM_INT);
		$queryAddBan->bindParam(':reason', $banREASON, PDO::PARAM_STR);
		$queryAddBan->bindParam(':admin', $_SESSION['login'], PDO::PARAM_STR);
		$queryAddBan->execute();
		//$bansAdded++;
		
		$msg = "Successfully added ban!
				<script>
					setTimeout(function() {
						$('#popup_msg').slideUp('fast');
						$('#popup_msg').html('');
						fetchDBRows('bans','none','none',1);
					}, 4000);
				</script>
				";
	}
	
	/*
	for ($i=0; $i< count($bansToDelete); $i++) {
		// Get GUID or IP for ban to be removed
		$queryBan = $dbhandle3->prepare('SELECT `GUID_IP` FROM `bans` WHERE `ID`=?');
		$queryBan->execute(array($bansToDelete[$i]));
		$banGUID_IP = $queryBan->fetchColumn();
		
		// Log ban removal
		$queryLog = $dbhandle2->prepare("INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES (?,?,NOW())");
		$queryLog->execute(array('UNBAN: '.$banGUID_IP,$_SESSION['login']));
		
		// Remove ban
		$queryDelBan = $dbhandle3->prepare('UPDATE `bans` SET `active`=0 WHERE `ID`=?');
		$queryDelBan->execute(array($bansToDelete[$i]));
		$bansAdded += $queryDelBan->rowCount();
		
	}
	*/
	//$msg = "<tr><td colspan='8'>Successfully added $bansAdded bans.</td></tr>";
} else {
	/* -----Total count--- */
	$query_pag_num = 'SELECT COUNT(*) FROM `bans`'; // Total records
	$queryHandle2 = $dbhandle3->prepare($query_pag_num);
	$queryHandle2->execute();
	$count = $queryHandle2->fetchColumn();
	if ($count > 0) {
		$queryHandle = $dbhandle3->prepare("SELECT * FROM `bans` ORDER BY `ID` DESC LIMIT :start,:per_page");
		$queryHandle->bindParam(':start', $start, PDO::PARAM_INT);
		$queryHandle->bindParam(':per_page', $per_page, PDO::PARAM_INT);
		$queryHandle->execute();
		
		$msg = fetchBanRows($queryHandle,$count,$cur_page,$page,$per_page);
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