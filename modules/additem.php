<?php
if (isset($_SESSION['user_id']))
{
	$pagetitle = "Add item";
	//$query = "INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('MANAGE VIPS','{$_SESSION['login']}',NOW())";
	//$sql2 = mysql_query($query) or die(mysql_error());
	$query = $dbhandle2->prepare("INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('MANAGE VIPS',?,NOW())");
	$query->execute(array($_SESSION['login']));
?>

<div id="page-heading">
<?php
	echo "<title>".$pagetitle." - ".$sitename."</title>";
	echo "<h1>".$pagetitle."</h1>";
?>
</div>
<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
	<tr>
		<th rowspan="3" class="sized"></th>
		<th class="topleft"></th>
		<td id="tbl-border-top">&nbsp;</td>
		<th class="topright"></th>
		<th rowspan="3" class="sized"></th>
	</tr>
	<tr>
		<td id="tbl-border-left"></td>
		<td>
		<!--  start content-table-inner ...................................................................... START -->
		<div id="content-table-inner">		
			
	
			<div class="clear"></div>

		</div>
		<!--  end content-table-inner ............................................END  -->
		</td>
		<td id="tbl-border-right"></td>
	</tr>
	<tr>
		<th class="sized bottomleft"></th>
		<td id="tbl-border-bottom">&nbsp;</td>
		<th class="sized bottomright"></th>
	</tr>
	</table>
	<div class="clear">&nbsp;</div>
<?php
}
else
{
	header('Location: admin.php');
}
?>