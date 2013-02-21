<?php
error_reporting (E_ALL ^ E_NOTICE);

/* QUERY:
	$query = "
		SELECT 
			iv.*, 
			v.class_name 
		FROM 
			instance_vehicle iv 
		INNER JOIN 
			vehicle v
		ON 
			iv.world_vehicle_id = v.id 
		WHERE 
			instance_id = ?
	";
	$qryInput = $iid;
*/

//$res = mysql_query($query) or die(mysql_error());
//$pnumber = mysql_num_rows($res);
//$qryHandle = $dbhandle->prepare($query);
//$qryHandle->execute(array($qryInput));
//$res = $qryHandle->fetchAll(PDO::FETCH_ASSOC);
//$pnumber = count($res);

$qryCount = $dbhandle->prepare("SELECT COUNT(*) FROM vehicle v JOIN world_vehicle wv ON v.id=wv.vehicle_id JOIN instance_vehicle iv ON iv.world_vehicle_id=wv.id WHERE instance_id=?");
$qryCount->bindParam(1, $qryInput, PDO::PARAM_INT);
$qryCount->execute();
$pnumber = $qryCount->fetchColumn();


if(isset($_GET['page'])) {
	$pageNum = $_GET['page'];
}
$offset = ($pageNum - 1) * $rowsPerPage;
$maxPage = ceil($pnumber/$rowsPerPage);			

for($page = 1; $page <= $maxPage; $page++) {
   if ($page == $pageNum) {
	  $nav .= " $page "; // no need to create a link to current page
   } else {
	  $nav .= "$self&page=$page";
   }
}

		
$query = $query." LIMIT ".$offset.",".$rowsPerPage;
//$res = mysql_query($query) or die(mysql_error());
//$number = mysql_num_rows($res);
$qryHandle = $dbhandle->prepare($query);
$qryHandle->bindParam(1, $qryInput, PDO::PARAM_INT);
$qryHandle->execute();
$res = $qryHandle->fetchAll(PDO::FETCH_ASSOC);
$number = count($res);

$tableheader = header_vehicle(0, $chbox);


//while ($row=mysql_fetch_array($res)) {
foreach ($res as $row) {
	$tablerows .= row_vehicle($row, $chbox);
}
include ('paging.php');
?>