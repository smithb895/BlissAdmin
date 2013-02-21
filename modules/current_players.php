<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);

include_once('../config.php');
include_once('hive_connect.php');
include_once('rcon.php');
global $DayZ_Servers;
//global $serverip;
//global $serverport;
//global $rconpassword;


if (isset($_POST['selectserver'])) {
	//$selectserver = preg_replace('#[^0-9]#', '', $_POST['selectserver']);
	$selectserver = $_POST['selectserver'];
	foreach ($DayZ_Servers as $server) {
		$_instance = $server->getMissionInstance();
		if ($_instance == $selectserver) {
			$serverip = $server->getServerIP();
			$serverport = $server->getServerPort();
			$rconpassword = $server->getRconPassword();
		}
	}
} else {
	die('Failure determining which server');
}

$cmd = "Players";
$answer = rcon($serverip,$serverport,$rconpassword,$cmd);
$queryGetPlayerData = $dbhandle->prepare('SELECT p.name,s.id,s.unique_id,s.worldspace,s.survivor_kills,s.bandit_kills,s.zombie_kills,s.start_time,s.last_updated,timestampdiff(hour, s.start_time, s.last_updated) as hours_old FROM profile p, survivor s WHERE p.unique_id = s.unique_id AND p.name LIKE ? ORDER BY s.last_updated DESC LIMIT 1');

if ($answer != ""){
	$k = strrpos($answer, "---");
	$l = strrpos($answer, "(");
	$out = substr($answer, $k+4, $l-$k-5);
	$array = preg_split ('/$\R?^/m', $out);
	for ($i=0; $i<count($array); $i++) {
		$array[$i] = preg_replace('#((\x42\x45)(.{10}))+#', '', $array[$i]); // Remove BattlEye header and flags from beginning of packet
	}
	
	//echo $answer."<br /><br />";
	//foreach ($array as $item) {
		//echo "$item<br />";
		//echo preg_replace('#((\x42\x45)(.{10}))+#', '', $item)."<br /><br />";
	//}
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
	for ($i=0; $i<count($players); $i++){
		if(strlen($players[$i][4])>1){
			$k = strrpos($players[$i][4], " (Lobby)");
			$playername = str_replace(" (Lobby)", "", $players[$i][4]);
			
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
			//print_r($players[$i]);
			//echo "<br />";
			//foreach ($players[$i] as $player) {
			//	echo "$player<br />";
			//}
			//echo "$player_ip<br />";
			//echo "$player_guid<br />";
			//echo "$player_ping<br />";
			// Remove invalid chars from beggining and end of the name
			$playername = preg_replace('#^[^0-9a-zA-Z_ \.,\[\]\{\}\!@\#\(\)\-\$\^&\*\+\\\?=~;:\|]#', '', $playername);
			$playername = preg_replace('#[^0-9a-zA-Z_ \.,\[\]\{\}\!@\#\(\)\-\$\^&\*\+\\\?=~;:\|]$#', '', $playername);
			//$playername = preg_replace('#^[^0-9a-z_ \.,\[\]\{\}\!@\#\(\)\-\$\^&\*\+\\\?=~;:\|+](.*)[^0-9a-z_ \.,\[\]\{\}\!@\#\(\)\-\$\^&\*\+\\\?=~;:\|+]$#i', '', $playername);
			$validchars = '#[^0-9a-zA-Z_ \.,\[\]\{\}\!@\#\(\)\-\$\^&\*\+\\\?=~;:\|+]#i';
			$safe = '';
			if (preg_match($validchars, $playername)) {
				$namesplit = preg_split($validchars, $playername);
				for ($i=0; $i<count($namesplit); $i++) {
					if ($i != 0) {
						$safe .= ' AND p.name LIKE %'.$namesplit[$i].'%';
					} else {
						$safe = '%'.$namesplit[$i].'%';
					}
				}
			} else {
				//$safe = '"%'.$playername.'%"';
				$safe = '%'.$playername.'%';
			}
			$queryParam = $safe;
			$queryGetPlayerData->execute(array($queryParam));
			$res = $queryGetPlayerData->fetch(PDO::FETCH_ASSOC);
			
			$player_ip = explode(':',$players[$i][1])[0];
			$player_guid = preg_replace('#(\(OK\))#', '', $players[$i][3]);
			$player_ping = $players[$i][2];
			$player_rowid = $res['id'];
			$pos_array = preg_split('#(,)#', preg_replace('#[^0-9\.,+]#', '', $res['worldspace']));
			if (count($pos_array) > 2) {
				$pos_x = round($pos_array[1], -2) / 100;
				$pos_y = round($pos_array[2], -2) / 100;
				switch (strlen($pos_x)) {
					case 1:
						$pos_x = '00'.$pos_x;
						break;
					case 2:
						$pos_x = '0'.$pos_x;
						break;
					default:
						break;
				}
				switch (strlen($pos_y)) {
					case 1:
						$pos_y = '00'.$pos_y;
						break;
					case 2:
						$pos_y = '0'.$pos_y;
						break;
					default:
						break;
				}
				$player_pos = "$pos_x,$pos_y";
			} else {
				$player_pos = 'unknown';
			}
			
			$hours_old = $res['hours_old'];
			$days_old = round(($hours_old / 24), 0);
			$remainder = round(($hours_old % 24), 0);
			if ($days_old < 1) {
				$player_age = "$hours_old hrs";
			} else {
				$player_age = "$days_old days";
				if ($remainder > 0) {
					$player_age .= "<br />$remainder hrs";
				}
			}
		}
		if ($i % 2) {
			echo '<tr class="alternate-row">';
		} else {
			echo '<tr>';
		}
		echo '
				<td><a href="admin.php?view=info&show=1&id='.$res['unique_id'].'&cid='.$player_rowid.'" alt=View player info>'.$playername.'</a></td>
				<td><a href="admin.php?view=info&show=1&id='.$res['unique_id'].'&cid='.$player_rowid.'" alt=View player info>'.$res['unique_id'].'</a></td>
				<td>'.$player_guid.'</td>
				<td>'.$player_ip.'</td>
				<td>'.$res['zombie_kills'].'</td>
				<td>'.$res['bandit_kills'].'</td>
				<td>'.$res['survivor_kills'].'</td>
				<td>'.$player_pos.'</td>
				<td>'.$player_age.'</td>
				<td>'.$res['last_updated'].'</td>
				<td>'.$player_ping.'</td>
			</tr>';
		//echo "<br /><br />";
	}
}



//echo '</table>';

/* Functions from rcon.php
function strToHex($string)
{
    $hex='';
    for ($i=0; $i < strlen($string); $i++)
    {
        $hex .= dechex(ord($string[$i]));
    }
    return $hex;
}

function hexToStr($hex)
{
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2)
    {
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
}

function computeUnsignedCRC32($str){
   sscanf(crc32($str), "%u", $var);
   $var = dechex($var + 0);
   return $var;
}
 
function dec_to_hex($dec)
{
    $sign = ""; // suppress errors
	$h = null;
    if( $dec < 0){ $sign = "-"; $dec = abs($dec); }

    $hex = Array( 0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5,
                  6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 'a',
                  11 => 'b', 12 => 'c', 13 => 'd', 14 => 'e',   
                  15 => 'f' );
       
    do
    {
        $h = $hex[($dec%16)] . $h;
        $dec /= 16;
    }
    while( $dec >= 1 );
   
    return $sign . $h;
} 

function get_checksum($cs)
{
    $var = computeUnsignedCRC32($cs);
	//echo "crchex: ".$var."<br/>";
	$x = ('0x');
	$a = substr($var, 0, 2);
	$a = $x.$a;
	$b = substr($var, 2, 2);
	$b = $x.$b;
	$c = substr($var, 4, 2);
	$c = $x.$c;
	$d = substr($var, 6, 2);
	$d = $x.$d;
	return chr($d).chr($c).chr($b).chr($a);
} 

function rcon($serverip,$serverport,$rconpassword,$cmd){
	$passhead = chr(0xFF).chr(0x00);
	$head = chr(0x42).chr(0x45);
	$pass = $passhead.$rconpassword;
	$answer = "";
	$checksum = get_checksum($pass);

	$loginmsg = $head.$checksum.$pass;

	$rcon = fsockopen("udp://".$serverip, $serverport, $errno, $errstr, 1);
	stream_set_timeout($rcon, 1);

	if (!$rcon) {
		echo "ERROR: $errno - $errstr<br />\n";
	} else {
		fwrite($rcon, $loginmsg);
		$res = fread($rcon, 16);
		
		$cmdhead = chr(0xFF).chr(0x01).chr(0x00);
		//$cmd = "Players";
		$cmd = $cmdhead.$cmd;
		$checksum = get_checksum($cmd);
		$cmdmsg = $head.$checksum.$cmd;
		$hlen = strlen($head.$checksum.chr(0xFF).chr(0x01));
		
		fwrite($rcon, $cmdmsg);
		$answer = fread($rcon, 102400);
		
		if ( strToHex(substr($answer, 9, 1)) == "0"){
			$count = strToHex(substr($answer, 10, 1));
			//echo $count."<br/>";
			for ($i = 0; $i < $count-1; $i++){
				$answer .= fread($rcon, 102400);
			}
		}
		//echo strToHex(substr($answer, 0, 16))."<br/>";
		//echo strToHex($answer)."<br/>";
		//echo $answer."<br/>";
		$cmd = "Exit";
		$cmd = $cmdhead.$cmd;
		$checksum = get_checksum($cmd);
		$cmdmsg = $head.$checksum.$cmd;
		fwrite($rcon, $cmdmsg);
	}

	return $answer;
}
*/





?>
