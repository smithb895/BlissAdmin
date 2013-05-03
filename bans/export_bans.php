<?php
// v Remove these lines when you know it works v
error_reporting(E_ALL);
ini_set('display_errors', 1);
// ^ Remove these lines when you know it works ^

/* Author: Anzu
 * Description: This script will take the bans
 * from the BansDB and compare them to the bans
 * in the bans.txt.  It will then append any bans
 * from the BansDB to the bans.txt that don't exist 
 * in it already.
*/

require("/../config.php");
require("/../session.php");
include_once("/../modules/bans_connect.php");

// Test line, make sure to comment out foreach loop and mysql stuph when testing
//$line = '12345678901234567890abcdefabcdab 123456789 This is a test ban';


// Set pathname of bans.txt
if (isset($_GET['banlist'])) {
	$banlist = preg_replace('#[^0-9]#', '', $_GET['banlist']);
} else {
	$banlist = 0;
}
$bansFile = $banlists[$banlist];
$banTableName = '`'.strtolower($banlistnames[$banlist]).'`';
$outputBansFile = $bansFile;
// Load file into an array, each line is element
$bansArray = file($bansFile);

//  Prepare SQL statement
$qryCount = $dbhandle3->query('SELECT count(`ID`) FROM '.$banTableName);
//$qryCount->execute();
$totalBans = $qryCount->fetchColumn();

//$qry = $dbhandle3->prepare('INSERT INTO `bans` (`ID`, `GUID_IP`, `LENGTH`, `REASON`, `ADMIN`, `DATE_TIME`) VALUES (:id, :guid_ip, :length, :reason, :admin, CURRENT_TIMESTAMP())');
//$qryReban = $dbhandle3->prepare('UPDATE `bans` SET `ACTIVE`=1 WHERE `ID`=? LIMIT 1');
//$qryUnban = $dbhandle3->prepare('UPDATE `bans` SET `ACTIVE`=0 WHERE `ID`=? LIMIT 1');
//$queryCheckExisting = $dbhandle3->prepare('SELECT * FROM `bans` WHERE `GUID_IP` LIKE ? AND `ACTIVE`=0');
$queryBansDB = $dbhandle3->prepare('SELECT `GUID_IP`,`LENGTH`,`REASON`,`ACTIVE` FROM '.$banTableName);
$queryBansDB->execute();


// iterate through array
$bansInserted = 0;
$unbans = 0;
$rebans = 0;
$bansTXT = array();
foreach ($bansArray as $line) {
	// This will determine if first part of line is GUID or IP
	// Load first 32 chars of line into $testGUID_IP
	$testGUID_IP = substr($line, 0, 32);
	// Check to see if a valid MD5 hash exists in those 32 chars
	if (!preg_match('#[0-9a-f]{32}#', $testGUID_IP)) {
		// If it doesn't contain an MD5, then check for an IP
		preg_match('#^(([0-9]{1,3}\.){3})([0-9]{1,3})#', $line, $matchIP);
		// Load IP into variable
		$setGUIDIP = $matchIP[0];
	} else {
		// If it does contain an MD5, use that as the GUID
		preg_match('#[0-9a-f]{32}#', $line, $matchGUID);
		$setGUIDIP = $matchGUID[0];
	}
	
	$bansTXT[] = $setGUIDIP;
}


$newBansTXT = $bansArray;
while ($bansRow = $queryBansDB->fetch(PDO::FETCH_ASSOC)) {
	if ($bansRow['ACTIVE'] == 0) {
		// Removing a ban
		if (in_array($bansRow['GUID_IP'], $bansTXT)) {
			// Escape periods in IP for preg
			$preg_exp = '#'.preg_replace('#[\.+]#', '\.', $bansRow['GUID_IP']).'#';
			// Filter out line containing banned GUID/IP, for removing ban from bans.txt
			$newBansTXT = preg_grep($preg_exp, $newBansTXT, PREG_GREP_INVERT);
			$unbans++;
		}
	} else {
		// Adding a ban
		if (!in_array($bansRow['GUID_IP'], $bansTXT)) {
			$newBansTXT[] = $bansRow['GUID_IP'].' '.$bansRow['LENGTH'].' '.$bansRow['REASON'].PHP_EOL;
			$bansInserted++;
		}
	}
}
//$newBansTXT[] = PHP_EOL;
if (($bansInserted > 0) || ($unbans > 0)) {
	file_put_contents($outputBansFile,$newBansTXT);
}

if ($bansInserted > 0) {
	echo "Successfully added a total of $bansInserted bans to the bans.txt!<br />
			<script type='text/javascript'>
				setTimeout(function() {
					//window.location.reload(1);
					fetchDBRows('bans','none','none',1);
				}, 4000);
			</script>
		";
} else {
	echo "No new bans to add!<br />";
}
if ($unbans > 0) {
	echo "Removed a total of $unbans bans from bans.txt!<br />
			<script type='text/javascript'>
				setTimeout(function() {
					//window.location.reload(1);
					fetchDBRows('bans','none','none',1);
				}, 4000);
			</script>
		";
}
/*
foreach ($newBansTXT as $line) {
	echo "$line<br />";
}
*/




?>