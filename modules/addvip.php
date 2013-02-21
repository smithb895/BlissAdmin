<?php
/*
 * -={AWG}=- Hive Admin
 * Author: Anzu
 * Desc: This file handles adding and
 * removing VIPs.
 *
*/
error_reporting(E_ALL);
ini_set('display_errors',1);

require("/../config.php");
require('/../session.php');
require_once('login_connect.php');
require_once('hive_connect.php');


if (isset($_POST['playerid'])) {
	//echo "Playerid is set";
	$playerid = $_POST['playerid'];
	if (preg_match('#[^0-9a-z+]#i', $playerid)) {
		die('Invalid character in player ID.');
	}
	if (strlen($playerid) > 20) {
		die('Player ID too long.');
	}
	
	if (isset($_POST['loadoutid'])) {
		//echo "loadout id is set";
		// Give player loadout ID
		$loadoutid = $_POST['loadoutid'];
		if (preg_match('#[^0-9a-z+]#i', $loadoutid)) {
			die('Invalid character in loadout ID.');
		}
		if (strlen($loadoutid) > 4) {
			die('Loadout ID too long.');
		}
		
		$queryAddVIP = $dbhandle->prepare("INSERT INTO `cust_loadout_profile` VALUES (:loadoutid,:playerid)");
		$queryAddVIP->bindParam(':loadoutid', $loadoutid, PDO::PARAM_INT);
		$queryAddVIP->bindParam(':playerid', $playerid, PDO::PARAM_STR);
		$queryAddVIP->execute();
		
		if ($queryAddVIP->errorCode() != 0) {
			echo "
				<h4>ERROR: Unable to insert new VIP.  Player ID may already exist in VIP table.  Please wait while page refreshes...</h4><br />
				<script type='text/javascript'>
					setTimeout(function() {
						window.location.reload(1);
					}, 5000);
				</script>";
		} else {
			echo "
				<h4>Successfully inserted new VIP!  Please wait while page refreshes...</h4><br />
				<script type='text/javascript'>
					setTimeout(function() {
						window.location.reload(1);
					}, 5000);
				</script>";
		}
	} elseif ($_POST['delete'] == 1) {
		// Remove VIP profile
		die('Cant delete VIPs');
		//exit();
	} else {
		die('No loadout ID set');
	}
}
//echo "all done";














?>