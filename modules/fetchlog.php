<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);

require('../session.php');
require('../config.php');
// $localserverlog <- server_console.log
// $localbansfile  <- bans.txt
//$localserverlog = 'C:\www\hive_admin\logs\server_console.log';


if (strlen($localserverlog) < 1) {
	die('No local log file specified.  Check your config.php');
}

$return = '';
$output = read_file($localserverlog,200);

foreach ($output as $outputline) {
	$return .= $outputline.'<br />';
}

echo $return;

function read_file($file, $lines) {
    //global $fsize;
    if (!$handle = fopen($file, "r")) {
		die('Error reading server_console.log');
	}
    $linecounter = $lines;
    $pos = -2;
    $beginning = false;
    $text = array();
    while ($linecounter > 0) {
        $t = " ";
        while ($t != "\n") {
            if(fseek($handle, $pos, SEEK_END) == -1) {
                $beginning = true; 
                break; 
            }
            $t = fgetc($handle);
            $pos --;
        }
        $linecounter --;
        if ($beginning) {
            rewind($handle);
        }
        $text[$lines-$linecounter-1] = fgets($handle);
        if ($beginning) break;
    }
    fclose ($handle);
    return array_reverse($text);
}


?>