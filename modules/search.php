<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);

if (isset($_SESSION['login']))
{
	if (isset($_POST['type'])){
		if (preg_match('#[^0-9a-z+]#i', $_POST['type'])) {
			die('Invalid POST value');
		}
		$pagetitle = "Search for ".$_POST['type'];
	} else {
		$pagetitle = "New search";
	}
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
		<div id="content-table-inner">	
		<!--  start content-table-inner ...................................................................... START -->
		<?php
		include ('searchbar.php');
		?><br/><?php
		if (!empty($_POST))
		{
			//echo $_POST['search']."<br />".$_POST['type'];
			error_reporting (E_ALL ^ E_NOTICE);
			$search = substr($_POST['search'], 0, 64);
			$search = preg_replace("/[^\w\x7F-\xFF\s]/", " ", $search);
			$good = trim(preg_replace("/\s(\S{1,2})\s/", " ", preg_replace("[ +]", "  "," $search ")));
			$good = preg_replace("[ +]", " ", $good);
			$logic = "OR";		

			?>
			<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
			<?php
			switch ($_POST['type']) {
				case 'player':
					$tableheader = header_player(0);
					echo $tableheader;
					//$playerquery = "select profile.name, survivor.* from profile, survivor as survivor where profile.unique_id = survivor.unique_id and name LIKE '%". str_replace(" ", "%' OR name LIKE '%", $good). "%' ORDER BY last_updated DESC"; 
					//$result = mysql_query($playerquery) or die(mysql_error());
					$playerquery = "select profile.name, survivor.* from profile, survivor as survivor where profile.unique_id = survivor.unique_id and name LIKE ? ORDER BY last_updated DESC";
					$queryInput = '%'.str_replace(" ", '% OR name LIKE %', $good).'%';
					$queryHandle = $dbhandle->prepare($playerquery);
					$queryHandle->execute(array($queryInput));
					$tablerows = "";
					//while ($row=mysql_fetch_array($result)) {
					while ($row=$queryHandle->fetch(PDO::FETCH_ASSOC)) {
						$tablerows .= row_player($row);
					}
					echo $tablerows;
				break;
				case 'item':
					$tableheader = header_player(0);
					echo $tableheader;
					//$query = "SELECT * from (SELECT profile.name, survivor.* from profile, survivor as survivor where profile.unique_id = survivor.unique_id) as T where inventory LIKE '%". str_replace(" ", "%' OR backpack LIKE '%", $good). "%'"." ORDER BY last_updated DESC";
					//$result = mysql_query($query) or die(mysql_error());
					$query = "SELECT * from (SELECT profile.name, survivor.* from profile, survivor as survivor where profile.unique_id = survivor.unique_id) as T where inventory LIKE ? ORDER BY last_updated DESC";
					$queryInput = '%'.str_replace(" ", '% OR backpack LIKE %', $good).'%';
					$queryHandle = $dbhandle->prepare($query);
					$queryHandle->execute(array($queryInput));
					$tablerows = "";
					//while ($row=mysql_fetch_array($result)) {
					while ($row=$queryHandle->fetch(PDO::FETCH_ASSOC)) {
						$tablerows .= row_player($row);
					}
					echo $tablerows;
					break;
				case 'vehicle':
					$chbox = "";
					$tableheader = header_vehicle(0, $chbox);
					echo $tableheader;
					//$query = "select iv.id, v.class_name, 0 owner_id, iv.worldspace, iv.inventory, iv.instance_id, iv.parts, fuel, oc.type, damage from instance_vehicle iv inner join vehicle v on iv.world_vehicle_id = v.id inner join object_classes oc on v.class_name = oc.classname where v.class_name like '%". str_replace(" ", "%' OR otype LIKE '%", $good). "%'";
					//$res = mysql_query($query) or die(mysql_error());
					$query = "select iv.id, v.class_name, 0 owner_id, iv.worldspace, iv.inventory, iv.instance_id, iv.parts, fuel, oc.type, damage from instance_vehicle iv inner join vehicle v on iv.world_vehicle_id = v.id inner join object_classes oc on v.class_name = oc.classname where v.class_name like ?";
					$queryInput = '%'.str_replace(" ", '% OR otype LIKE %', $good).'%';
					$queryHandle = $dbhandle->prepare($query);
					$queryHandle->execute(array($queryInput));
					$chbox = "";
					//while ($row=mysql_fetch_array($res)) {
					while ($row=$queryHandle->fetch(PDO::FETCH_ASSOC)) {
							$tablerows .= row_vehicle($row, $chbox);
					}
					echo $tablerows;
					break;
				case 'container':
					$chbox = "";
					$tableheader = header_vehicle(0, $chbox);
					echo $tableheader;
					//$query = "select * from instance_deployable id inner join deployable d on id.deployable_id = d.id inner join object_classes oc on d.class_name = oc.classname where d.class_name = 'TentStorage' and id.inventory Like '%". str_replace(" ", "%' OR id.inventory LIKE '%", $good). "%'";
					$query = "select * from instance_deployable id inner join deployable d on id.deployable_id = d.id inner join object_classes oc on d.class_name = oc.classname where d.class_name = 'TentStorage' and id.inventory Like ?";
					$queryInput = '%'.str_replace(" ", '% OR id.inventory LIKE "%', $good).'%';
					$queryHandle = $dbhandle->prepare($query);
					$queryHandle->execute(array($queryInput));
					$chbox = "";
					//while ($row=mysql_fetch_array($res)) {
					while ($row=$queryHandle->fetch(PDO::FETCH_ASSOC)) {
							$tablerows .= row_vehicle($row, $chbox);
					}
					echo $tablerows;
					break;
				default:
					$tableheader = header_player(0);
					echo $tableheader;
					//$playerquery = "select profile.name, survivor.* from profile, survivor as survivor where profile.unique_id = survivor.unique_id and name LIKE '%". str_replace(" ", "%' OR name LIKE '%", $good). "%' ORDER BY lastupdate DESC";
					//$result = mysql_query($playerquery) or die(mysql_error());
					$playerquery = "select profile.name, survivor.* from profile, survivor as survivor where profile.unique_id = survivor.unique_id and name LIKE ? ORDER BY lastupdate DESC";
					$queryInput = '%'.str_replace(" ", '% OR name LIKE %', $good).'%';
					$queryHandle = $dbhandle->prepare($playerquery);
					$queryHandle->execute(array($queryInput));
					$tablerows = "";
					//while ($row=mysql_fetch_array($result)) {
					while ($row=$queryHandle->fetch(PDO::FETCH_ASSOC)) {
						$tablerows .= row_player($row);
					}
					echo $tablerows;
				};
			?>
			</table>
			<?php
		}
		else
		{

		}
		?>		
		<!--  end content-table-inner ............................................END  -->
		</div>
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
	header('Location: index.php');
}
?>