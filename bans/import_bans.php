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
include_once("/../modules/bans_connect.php");

// Test line, make sure to comment out foreach loop and mysql stuph when testing
//$line = '12345678901234567890abcdefabcdab 123456789 This is a test ban';


// Set pathname of bans.txt
$bansFile = $localbansfile;

// Load file into an array, each line is element
$bansArray = file($bansFile);

//  Prepare SQL statement
$qryCount = $dbhandle3->query('SELECT count(`ID`) FROM `bans`');
//$qryCount->execute();
$totalBans = $qryCount->fetchColumn();

$qry = $dbhandle3->prepare('INSERT INTO `bans` (`ID`, `GUID_IP`, `LENGTH`, `REASON`, `ADMIN`) VALUES (:id, :guid_ip, :length, :reason, :admin)');

// iterate through array
$bansInserted = 0;
foreach ($bansArray as $line)
{
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
		$setREASON = "Appeal at anzuswargames.info/forums";
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
		echo "Successfully imported 1 ban!<br />";
		$bansInserted++;
	}
	
}
echo "Successfully imported a total of $bansInserted bans!<br />";
?>