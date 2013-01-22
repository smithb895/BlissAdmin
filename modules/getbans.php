<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);

include_once('/../config.php');
//include_once('hive_connect.php');
//include_once('rcon.php');
include_once('bans_connect.php');
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

//$query_pag_data = "SELECT msg_id,message from messages LIMIT $start, $per_page";
//$result_pag_data = mysql_query($query_pag_data) or die('MySql Error' . mysql_error());

$queryHandle = $dbhandle3->prepare("SELECT * FROM bans ORDER BY `ID` DESC LIMIT :start,:per_page");
$queryHandle->bindParam(':start', $start, PDO::PARAM_INT);
$queryHandle->bindParam(':per_page', $per_page, PDO::PARAM_INT);
$queryHandle->execute();

$msg = "";
//while ($row = mysql_fetch_array($result_pag_data)) {
//$row=$queryHandle->fetch(PDO::FETCH_ASSOC);
//var_dump($row);
while ($row=$queryHandle->fetch(PDO::FETCH_ASSOC)) {
	//$htmlmsg=htmlentities($row['message']); //HTML entries filter
	//$msg .= "<li><b>" . $row['msg_id'] . "</b> " . $htmlmsg . "</li>";
	if ($row['ID'] % 2) {
		$msg .= '<tr class="alternate-row">';
	} else {
		$msg .= '<tr>';
	}
	$msg .= '
						<td><input name="delban[]" value="'.$row["ID"].'" type="checkbox"/></td>
						<td>'.$row["ID"].'</td>
						<td>'.$row["GUID_IP"].'</td>
						<td>'.$row["LENGTH"].'</td>
						<td>'.$row["REASON"].'</td>
						<td>'.$row["ADMIN"].'</td>
						<td>'.$row["DATE_TIME"].'</td>
						<td>'.$row["ACTIVE"].'</td>
					</tr>';
}
//$msg = "<div class='data'><ul>" . $msg . "</ul></div>"; // Content for Data
/* -----Total count--- */
$query_pag_num = 'SELECT COUNT(*) FROM `bans`'; // Total records
$queryHandle2 = $dbhandle3->prepare($query_pag_num);
$queryHandle2->execute();
$count = $queryHandle2->fetchColumn();
//$result_pag_num = mysql_query($query_pag_num);
//$row = mysql_fetch_array($result_pag_num);
//$count = $row['count'];
$no_of_paginations = ceil($count / $per_page);
/* -----Calculating the starting and endign values for the loop----- */
if ($cur_page >= 7) {
	$start_loop = $cur_page - 3;
	if ($no_of_paginations > $cur_page + 3)
		$end_loop = $cur_page + 3;
	else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
		$start_loop = $no_of_paginations - 6;
		$end_loop = $no_of_paginations;
	} else {
		$end_loop = $no_of_paginations;
	}
} else {
	$start_loop = 1;
	if ($no_of_paginations > 7)
		$end_loop = 7;
	else
		$end_loop = $no_of_paginations;
}
/* ----------------------------------------------------------------------------------------------------------- */
$msg .= "<tr class='pagination'><td colspan='8'><ul>";

// FOR ENABLING THE FIRST BUTTON

if ($first_btn && $cur_page > 1) {
	$msg .= "<li p='1' class='active'>First</li>";
} else if ($first_btn) {
	$msg .= "<li p='1' class='inactive'>First</li>";
}

// FOR ENABLING THE PREVIOUS BUTTON
if ($previous_btn && $cur_page > 1) {
	$pre = $cur_page - 1;
	$msg .= "<li p='$pre' class='active'>Previous</li>";
} else if ($previous_btn) {
	$msg .= "<li class='inactive'>Previous</li>";
}
for ($i = $start_loop; $i <= $end_loop; $i++) {

	if ($cur_page == $i)
		$msg .= "<li p='$i' id='current_page' class='active'>{$i}</li>";
	else
		$msg .= "<li p='$i' class='active'>{$i}</li>";
}

// TO ENABLE THE NEXT BUTTON
if ($next_btn && $cur_page < $no_of_paginations) {
	$nex = $cur_page + 1;
	$msg .= "<li p='$nex' class='active'>Next</li>";
} else if ($next_btn) {
	$msg .= "<li class='inactive'>Next</li>";
}

// TO ENABLE THE END BUTTON
if ($last_btn && $cur_page < $no_of_paginations) {
	$msg .= "<li p='$no_of_paginations' class='active'>Last</li>";
} else if ($last_btn) {
	$msg .= "<li p='$no_of_paginations' class='inactive'>Last</li>";
}
$goto = "<input type='text' class='goto' size='1' style='margin-top:-1px;margin-left:60px;'/><input type='button' id='go_btn' class='go_button' value='Go'/>";
$total_string = "<span class='total' a='$no_of_paginations'>Page <b>" . $cur_page . "</b> of <b>$no_of_paginations</b></span>";
$msg = $msg . "</ul>" . $goto . $total_string . "</td></tr>";  // Content for pagination

//$msg = "Hello";
echo $msg;



?>