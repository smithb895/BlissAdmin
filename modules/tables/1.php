<?php
	error_reporting (E_ALL ^ E_NOTICE);
	
	//$res = mysql_query($query) or die(mysql_error());
	//$pnumber = mysql_num_rows($res);
	$queryHandle = $dbhandle->prepare($query);
	if (empty($qryInput)) {
		$queryHandle->execute();
	} else {
		$queryHandle->execute(array($qryInput));
	}
	$res = $queryHandle->fetchAll(PDO::FETCH_NUM);
	$pnumber = count($res);

	if(isset($_GET['page']))
	{
		$pageNum = $_GET['page'];
	}
	$offset = ($pageNum - 1) * $rowsPerPage;
	$maxPage = ceil($pnumber/$rowsPerPage);			

	for($page = 1; $page <= $maxPage; $page++)
	{
	   if ($page == $pageNum)
	   {
		  $nav .= " $page "; // no need to create a link to current page
	   }
	   else
	   {
		  $nav .= "$self&page=$page";
	   }
	}

	$query = $query." LIMIT ".$offset.",".$rowsPerPage;
	//$res = mysql_query($query) or die(mysql_error());
	//$number = mysql_num_rows($res);
	$queryHandle = $dbhandle->prepare($query);
	if (empty($qryInput)) {
		$queryHandle->execute();
	} else {
		$queryHandle->execute(array($qryInput));
	}
	$res = $queryHandle->fetchAll(PDO::FETCH_NUM);
	$number = count($res);

	$tableheader = header_player(0);
		
	//while ($row=mysql_fetch_array($res)) {
	while ($row=$queryHandle->fetch(PDO::FETCH_ASSOC)) {
		$tablerows .= row_player($row);
	}
	include ('paging.php');
?>