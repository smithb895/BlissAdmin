<?php
	//ini_set( "display_errors", 0);
	//error_reporting (E_ALL ^ E_NOTICE);		<th class="table-header-repeat line-left" width="15%"><a href="">IP Address</a></th>
		//<th class="table-header-repeat line-left" width="5%"><a href="">Ping</a></th>
if (!isset($_SESSION['user_id'])) {
	header('Location: admin.php');
}
if (isset($_GET['instance'])) {
	if (preg_match('#[^0-9+]#', $_GET['instance'])) {
		die("Invalid instance number");
	} else {
		$instance = $_GET['instance'];
	}
	foreach ($DayZ_Servers as $server) {
		if ($instance == $server->getMissionInstance()) {
			$iid = $instance;
			$serverip = $server->getServerIP();
			$serverport = $server->getServerPort();
			$rconpassword = $server->getRconPassword();
			$map = $server->getServerMap();
			$world = $server->getWorldID();
		}
	}
}

$cmd = "Players";

$answer = rcon($serverip,$serverport,$rconpassword,$cmd);
$tableheader = header_player(0);


if ($answer != ""){
	$k = strrpos($answer, "---");
	$l = strrpos($answer, "(");
	$out = substr($answer, $k+4, $l-$k-5);
	$array = preg_split ('/$\R?^/m', $out);
	
	//echo $answer."<br /><br />";
	
	$players = array();
	for ($j=0; $j<count($array); $j++){
		$players[] = "";
	}
	for ($i=0; $i < count($array); $i++)
	{
		$m = 0;
		for ($j=0; $j<5; $j++){
			$players[$i][] = "";
		}
		$pout = preg_replace('/\s+/', ' ', $array[$i]);
		for ($j=0; $j<strlen($pout); $j++){
			$char = substr($pout, $j, 1);
			if($m < 4){
				if($char != " "){
					$players[$i][$m] .= $char;
				}else{
					$m++;
				}
			} else {
				$players[$i][$m] .= $char;
			}
		}
	}
	
	$pnumber = count($players);
	//echo count($players)."<br />";
	for ($i=0; $i<count($players); $i++){
		//echo $players[$i][4]."<br />";
		if(strlen($players[$i][4])>1){
			$k = strrpos($players[$i][4], " (Lobby)");
			$playername = str_replace(" (Lobby)", "", $players[$i][4]);
			
			//$search = substr($playername, 0, 5);
			$paren_num = 0;
			$chars = str_split($playername);
			$new_string = '';
			foreach($chars as $char) {
				if($char=='[') $paren_num++;
				else if($char==']') $paren_num--;
				else if($paren_num==0) $new_string .= $char;
			}
			$playername = trim($new_string);


			//echo $playername."<br />";
			//$search = preg_replace("/[^\w\x7F-\xFF\s]/", " ", $playername);
			//$good = trim(preg_replace("/\s(\S{1,2})\s/", " ", preg_replace("[ +]", "  "," $search ")));
			//$good = trim(preg_replace("/\([^\)]+\)/", "", $good));
			$good = preg_replace("[ +]", " ", $playername);
			
			// Revised query cleanup
			/*
			$safename = '';
			$validchars = '#[^0-9a-z_ \.,@\-+]#i';
			if (preg_match($validchars, $good)) {
				$namesplit = preg_split($validchars, $good);
				for ($i=0; $i< count($namesplit); $i++) {
					if (strlen($namesplit[$i]) > 2) {
						if ($i != 0) {
							$safename .= ' AND p.name LIKE %'.$namesplit[$i].'%';
						} else {
							$safename = '%'.$namesplit[$i].'%';
						}
					}
				}
				$good = $safename;
			}
			*/
			$queryInput = stringSplitSQL($good, 'name');
			
			//$queryInput = $good;
			//echo $good."<br />";
			//$query = "select * from (SELECT profile.name, survivor.* from profile, survivor as survivor where profile.unique_id = survivor.unique_id) as T where name LIKE '%". str_replace(" ", "%' OR name LIKE '%", $good). "%' ORDER BY last_updated DESC LIMIT 1";
			// Improved query below runs more than twice as fast as above (1.4sec VS 0.5sec)
			//$query = "SELECT p.name,s.*,w.name as worldname,i.id as instance_id FROM profile p JOIN survivor s ON p.unique_id=s.unique_id JOIN world w ON w.id=s.world_id JOIN instance i ON i.world_id=w.id WHERE p.name LIKE ? AND i.id=? ORDER BY s.last_updated DESC LIMIT 1";
			$query = "SELECT p.name,s.*,w.name as worldname,i.id as instance_id FROM profile p JOIN survivor s ON p.unique_id=s.unique_id JOIN world w ON w.id=s.world_id JOIN instance i ON i.world_id=w.id WHERE p.name LIKE ".$queryInput." AND i.id=".$instance." ORDER BY s.last_updated DESC LIMIT 1";
			//$queryInput = '%'.str_replace(" ", '% OR name LIKE %', $good).'%';
			//echo $queryInput."<br />";
			$queryHandle = $dbhandle->prepare($query);
			//$queryHandle->execute(array($queryInput,$instance));
			$queryHandle->execute();
			//echo $playername."<br />";
			$res = null;
			//$res = mysql_query($query) or die(mysql_error());
			$res = $queryHandle->fetch(PDO::FETCH_ASSOC);
			$name = $res['name'];
			$id = $res['unique_id'];
			$dead = "";
			$x = 0;
			$y = 0;
			$InventoryPreview = "";
			$BackpackPreview = "";
			//$ip = $players[$i][1];
			//$ping = $players[$i][2];
			//$name = $players[$i][4];
			$uid = "";
			
			$tablerows .= row_online_player($res, $players[$i]);
			//while ($row=mysql_fetch_array($res)) {
			//while ($row=$queryHandle->fetch(PDO::FETCH_ASSOC)) {
			//	$tablerows .= row_online_player($row, $players[$i]);
			//}
		}
	}
}

?>