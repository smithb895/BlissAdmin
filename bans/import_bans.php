<?php
// v Remove these lines when you know it works v
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
// ^ Remove these lines when you know it works ^

/* Author: Anzu
 * Description: This code will take a banlist file,
 * and split it up in to an array with each line as an element.
 * Then, it will split each line into 3 parts, GUID/IP, length,
 * and reason.  It will then insert these 3 parts from each 
 * line into a table in a database.
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

// Load file into an array, each line is element
$bansArray = file($bansFile);

//  Prepare SQL statement
$qryCount = $dbhandle3->query('SELECT count(`ID`) FROM '.$banTableName);
//$qryCount->execute();
$totalBans = $qryCount->fetchColumn();

$qry = $dbhandle3->prepare('INSERT INTO '.$banTableName.' (`ID`, `GUID_IP`, `LENGTH`, `REASON`, `ADMIN`, `DATE_TIME`) VALUES (:id, :guid_ip, :length, :reason, :admin, CURRENT_TIMESTAMP())');
$qryReban = $dbhandle3->prepare('UPDATE '.$banTableName.' SET `ACTIVE`=1 WHERE `ID`=? LIMIT 1');
$qryUnban = $dbhandle3->prepare('UPDATE '.$banTableName.' SET `ACTIVE`=0 WHERE `ID`=? LIMIT 1');
$queryCheckExisting = $dbhandle3->prepare('SELECT * FROM '.$banTableName.' WHERE `GUID_IP` LIKE ? AND `ACTIVE`=0');
$queryActiveBansDB = $dbhandle3->prepare('SELECT * FROM '.$banTableName.' WHERE `ACTIVE`=1');
$queryActiveBansDB->execute();


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
		//preg_match("/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$/", $line, $matchIP);
		preg_match('#^(([0-9]{1,3}\.){3})([0-9]{1,3})#', $line, $matchIP);
		// Load IP into variable
		$setGUIDIP = $matchIP[0];
	} else {
		// If it does contain an MD5, use that as the GUID
		preg_match('#[0-9a-f]{32}#', $line, $matchGUID);
		$setGUIDIP = $matchGUID[0];
	}
	
	//  This will catch lines without a reason or time specified
	if ((strlen($line) - strlen($setGUIDIP)) < 3) {
		$setREASON = "Appeal at $siteForums";
		$setTIME = "-1";
	} else {
		// This regex will find the ban time length in each line
		preg_match('#[ ][\-0-9]*[ ]#', $line, $matchT);
		$setTIME = trim($matchT[0]);
	
		// Add length of GUID/IP and ban time length, and cut that # of chars from the line,
		// the remaining chars will be the reason
		$_offset = strlen($setGUIDIP) + strlen($setTIME) + 2; // +2 for the spaces
		$setREASON = substr($line, $_offset);
		
		// Try to read the admin initial to determine who added the ban
		if (preg_match('#[{\(\[]([a-zA-Z0-9]{1,3})[\)\]}]#', $setREASON, $matchADMIN)) {
			$setADMIN = $matchADMIN[0];
		} else {
			$setADMIN = "";
		}
	}
	
	// To echo test results
	//echo "$setGUIDIP $setTIME $setREASON ADMIN: $setADMIN<br />";
	
	$queryCheckExisting->execute(array($setGUIDIP));
	while ($bansRow = $queryCheckExisting->fetch(PDO::FETCH_ASSOC)) {
		$qryReban->execute(array($bansRow['ID']));
		$rebans++;
	}
	
	
	$totalBans++;
	$qry->bindParam(':id', $totalBans, PDO::PARAM_INT);
	$qry->bindParam(':guid_ip', $setGUIDIP, PDO::PARAM_STR);
	$qry->bindParam(':length', $setTIME, PDO::PARAM_INT);
	$qry->bindParam(':reason', $setREASON, PDO::PARAM_STR);
	$qry->bindParam(':admin', $setADMIN, PDO::PARAM_STR);
	$qry->execute();
	// If INSERT fails, decrement totalbans back down
	if ($qry->errorCode() != 0) {
		$totalBans -= 1;
	} else {
		//echo "Successfully imported 1 ban!<br />";
		$bansInserted++;
	}
	
	$bansTXT[] = $setGUIDIP;
}

while ($bansRow = $queryActiveBansDB->fetch(PDO::FETCH_ASSOC)) {
	if (!in_array($bansRow['GUID_IP'], $bansTXT)) {
		$qryUnban->execute(array($bansRow['ID']));
		$unbanned[] = $bansRow['GUID_IP'];
		$unbans++;
	}
}
echo "Done.<br /><br />";
if ($bansInserted > 0) {
	echo "Successfully imported a total of $bansInserted bans!<br />Please wait while banlist refreshes...<br /><br />
			<script type='text/javascript'>
				setTimeout(function() {
					//window.location.reload(1);
					fetchDBRows('bans','none','none',1);
				}, 4000);
			</script>
		";
} else {
	echo "No new bans inserted!<br /><br />";
}
if ($unbans > 0) {
	echo "NOTICE: Unbanned a total of $unbans players!<br />Please wait while banlist refreshes...<br /><br />
			<script type='text/javascript'>
				setTimeout(function() {
					//window.location.reload(1);
					fetchDBRows('bans','none','none',1);
				}, 4000);
			</script>
	";
	/*
	foreach ($unbanned as $unban) {
		echo "$unban<br />";
	}
	*/
}
if ($rebans > 0) {
	echo "NOTICE: Rebanned a total of $rebans players!<br />Please wait while banlist refreshes...<br /><br />
			<script type='text/javascript'>
				setTimeout(function() {
					//window.location.reload(1);
					fetchDBRows('bans','none','none',1);
				}, 4000);
			</script>
	";
}
?>