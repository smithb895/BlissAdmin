<?php
/*  Mission File Parser
 * (For Bliss HIVE ONLY)
 * Desc: This will take your mission.sqm file and 
 * add any vehicles to your vehicle table and 
 * all spawn points to your world_vehicle table.
 * Original by: Planek
 * Rewritten by: Anzu
 *	Anzu's Changes:
 *		-Converted script to a function
 *		-Replaced deprecated mysql functions with PDO methods
 *		-Converted script to work with regular editor-generated mission.sqm
 *		-Trimmed up code, made a bit more efficient :)
 * You can go through and edit
 * Edit this info to match your database config//
 *
 */
error_reporting(E_ALL);
ini_set('display_errors',1);

//require('config.php');
//require('hive_connect.php');

//$fileName = 'FILENAME.txt'; //path to your mission.sqm file
//  $worldID  <- Check your world table for your specific world ID.
///////////////////

// DO NOT EDIT ANYTHING BELOW THIS LINE //
/*--------------------------------------*/
function import_mission_spawns($dbhandle,$fileName,$worldID) {
	$chance = 0.99; //Set a default chance for all vehicles spawns created
	// Prepare queries
	$queryGetClassNameID = $dbhandle->prepare("SELECT id FROM vehicle WHERE class_name=? LIMIT 1");
	$queryInsertNewClassname = $dbhandle->prepare("INSERT INTO `vehicle` (`id`,`class_name`,`damage_min`,`damage_max`,`fuel_min`,`fuel_max`,`limit_min`,`limit_max`) VALUES (?,?,'0.100','0.700','0.200','0.800','0','10')");
	$queryInsertSpawn = $dbhandle->prepare("INSERT INTO `world_vehicle` (`id`,`vehicle_id`,`world_id`,`worldspace`,`chance`) VALUES (?,?,?,?,?)");
	// Load lines from file into array
	$rows = file($fileName);
	//array_shift($rows);
	$vehiclecount = 0;
	$id = $dbhandle->query("SELECT id FROM world_vehicle ORDER BY id DESC LIMIT 1")->fetchColumn() + 1;
	//$query->closeCursor();
	//echo "<br />classCount | id | vehicle_id | worldID | pos | chance<br />";
	
	for($i=0;$i<count($rows);$i++) {
		if (strpos($rows[$i],'position[]={') !== false) {
			preg_match('#[0-9\.,]+#', $rows[$i], $matches);
			$pos_array = explode(',',$matches[0]);
			
			if (strpos($rows[$i+1],'azimut=') !== false) {
				preg_match('#[0-9\.]+#', $rows[$i+1], $matches);
				$direction = round($matches[0]);
				preg_match('#"[a-z0-9_]+"#i', $rows[$i+4], $matches);
				$classname = preg_replace('#"#', '', $matches[0]);
			} else {
				$direction = 0;
				preg_match('#"[a-z0-9_]+"#i', $rows[$i+3], $matches);
				$classname = preg_replace('#"#', '', $matches[0]);
			}
			$pos = '['.$direction.',['.$pos_array[0].','.$pos_array[2].','.$pos_array[1].']]';
			//Class Check (Will insert a new classname if it doesnt exist)
			$existing = array();
			$query = $dbhandle->query("SELECT class_name FROM vehicle");
			/*$qryresult = $query->fetchAll(PDO::FETCH_ASSOC);
			foreach ($qryresult as $row) {
				$existing[] = strtolower($row['class_name']);
			}*/
			while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
				$existing[] = strtolower($row['class_name']);					
			}
			$matchFound = 0;
			foreach ($existing as $exist) {
				if ($exist == strtolower($classname)) {
					$matchFound = 1;
				}
			}
			$query = $dbhandle->query("SELECT id FROM vehicle ORDER BY id DESC LIMIT 1");
			$classCount = $query->fetchColumn();
			$classCount++;
			if($matchFound == 0) {
				$queryInsertNewClassname->execute(array($classCount,$classname));
				//echo "<br />Will insert new classname into vehicle table<br />";
				//echo "New classname $classname found";
			}
			//Class Check End
			$queryGetClassNameID->execute(array($classname));
			$vehicle_id = $queryGetClassNameID->fetchColumn();
			$queryInsertSpawn->execute(array($id,$vehicle_id,$worldID,$pos,$chance));
			//echo "<br />$classCount | $id | $vehicle_id | $worldID | $pos | $chance - $classname<br />";
			$vehiclecount++;
			$id++;
		}
		
		if (strpos($rows[$i],'_this = createVehicle [') !== false) {
			$strings = explode("\"",$rows[$i]);
			$firstOpenBracket = strpos($rows[$i], "[");
			$secondOpenBracket = strpos($rows[$i], "[", $firstOpenBracket + strlen("]"));
			$firstCloseBracket = strpos($rows[$i], "]");
			if (strpos($rows[$i+2],'_this setDir') !== false) {
				$firstSpace = strpos($rows[$i+2]," ");
				$secondSpace = strpos($rows[$i+2]," ",$firstSpace+strlen(" "));
				$thirdSpace = strpos($rows[$i+2]," ",$secondSpace+strlen(" "));
				$forthSpace = strpos($rows[$i+2]," ",$thirdSpace+strlen(" "));
				$period = strpos($rows[$i+2],".");
				$direction = substr($rows[$i+2],$forthSpace+1, $period-$forthSpace-1);
			}
			$pos = "[$direction," . substr($rows[$i],$secondOpenBracket, $firstCloseBracket-$secondOpenBracket+1) . "]";
			$pos = str_replace(array(' '), '',$pos);
			$newPos = explode(",",$pos);
			if (count($newPos) == 3) {
				$pos = "[$direction," . substr($rows[$i],$secondOpenBracket, $firstCloseBracket-$secondOpenBracket) . ",0]]";
				$pos = str_replace(array(' '), '',$pos);
			}
			//Class Check (Will insert a new classname if it doesnt exist)
			$query = $dbhandle->query("SELECT * FROM vehicle");
			$userDataClassNameQuery;
			$userDataVehicleIDs;
			while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
				$userDataClassNameQuery[] = $row['class_name'];
			}
			$matchFound = 0;
			for($j=0;$j<count($userDataClassNameQuery)-1;$j++) {
				if ($strings[1] == $userDataClassNameQuery[$j]) {
					$matchFound = 1;
				}
			}/***********************/
			$query = $dbhandle->query("SELECT id FROM vehicle");
			while ($row = $query->fetch(PDO::FETCH_NUM)) {
				$userDataVehicleIDs[] = $row[0];
			}
			$classCount = max($userDataVehicleIDs) + 1;
			if($matchFound == 0) {
				$query = $dbhandle->prepare("INSERT INTO `vehicle` (`id`,`class_name`,`damage_min`,`damage_max`,`fuel_min`,`fuel_max`,`limit_min`,`limit_max`) VALUES (?,?,'0.100','0.700','0.200','0.800','0','10')");
				$query->execute(array($classCount,$strings[1]));
			}
			//Class Check End
			$query = $dbhandle->prepare("SELECT * FROM vehicle WHERE class_name=?");
			$userDataIDQuery = $query->execute(array($strings[1]))->fetch(PDO::FETCH_ASSOC);
			$vehicle_id = $userDataIDQuery['id'];
			
			$query = $dbhandle->prepare("INSERT INTO `world_vehicle` (`id`,`vehicle_id`,`world_id`,`worldspace`,`chance`) VALUES (?,?,?,?,?)");
			$query->execute(array($id,$vehicle_id,$worldID,$pos,$chance));
			$vehiclecount++;
			$id++;
		}
	}
}
?>