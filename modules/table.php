<?php
include_once('config.php');
include_once('/modules/functions.php');
if (isset($_SESSION['login']))
{	
	$pnumber = 0;
	$tableheader = '';
	$tablerows = '';
	$pageNum = 1;
	$maxPage = 1;
	$rowsPerPage = 30;
	$nav  = '';
	$self = 'admin.php?view=table&show='.$show;
	$paging = '';
	if (isset($_GET['instance'])) {
		if (preg_match('#[^0-9+]#', $_GET['instance'])) {
			die("Invalid instance number");
		} else {
			$instance = $_GET['instance'];
			$iid = $instance;
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
	
	$serverrunning = false;
	$delresult = "";
	$formhead = "";
	$formfoot = "";
	
	if (isset($_GET["show"])){
		$show = $_GET["show"];
	}else{
		$show = 0;
	}
	
	switch ($show) {
		case 0:
			$pagetitle = "Online players";
			break;
		case 1:
			$query = "select profile.name, survivor.* from profile, survivor as survivor where profile.unique_id = survivor.unique_id and survivor.is_dead = '0'"; 
			$pagetitle = "Alive players";
			break;
		case 2:
			$query = "select profile.name, survivor.* from profile, survivor as survivor where profile.unique_id = survivor.unique_id and survivor.is_dead = '1'"; 
			$pagetitle = "Dead players";
			break;
		case 3:
			$query = "select profile.name, survivor.* from profile, survivor as survivor where profile.unique_id = survivor.unique_id"; 
			$pagetitle = "All players";
			break;
		case 4:
			//$query = "SELECT iv.*, v.class_name FROM instance_vehicle iv inner join vehicle v on iv.world_vehicle_id = v.id WHERE instance_id = '" . $iid . "'";
			$query = "SELECT iv.*,v.class_name FROM vehicle v JOIN world_vehicle wv ON v.id=wv.vehicle_id JOIN instance_vehicle iv ON iv.world_vehicle_id=wv.id WHERE instance_id=?";
			$qryInput = $iid;
			$pagetitle = "All Ingame Objects";
			break;
		case 5:
			//$query = "SELECT * FROM spawns WHERE world = '" . $map . "'";
			//$query = "SELECT * FROM spawns WHERE world = ?";
			$query = "SELECT wv.*,v.class_name FROM world_vehicle wv JOIN vehicle v ON v.id=wv.vehicle_id WHERE wv.world_id=?";
			$qryInput = $world;
			$pagetitle = "Vehicle spawn locations";
			break;
		case 6:
			//$query = "SELECT * FROM spawns WHERE world = '" . $map . "'";
			$query = "SELECT * FROM spawns WHERE world = ?";
			$qryInput = $map;
			$pagetitle = "TEST Online Players";	
			break;
		default:
			$pagetitle = "Online players";
		};
		
	include ('tables/'.$show.'.php');

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
			<?php echo $delresult; ?>
			<!--  start table-content  -->
			<div id="table-content">
				<!--  start message-blue -->
				<div id="message-blue">
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td class="blue-left"><?php echo $pagetitle.": ".$pnumber; ?>. </td>
					<td class="blue-right"><a class="close-blue"><img src="images/table/icon_close_blue.gif"   alt="" /></a></td>
				</tr>
				</table>
				</div>
				<!--  end message-blue -->
				
				<!--  start paging..................................................... -->
				<?php echo $paging; ?>				
				<!--  end paging................ -->
				<br/>
				<br/>
				<!--  start product-table ..................................................................................... -->
				<?php echo $formhead;?>	
					<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
						<?php echo $tableheader; ?>
						<?php echo $tablerows; ?>				
					</table>
				<?php echo $formfoot;?>	
				<!--  end product-table................................... --> 
			</div>
			<!--  end content-table  -->
				
			<!--  start paging..................................................... -->
			<?php echo $paging; ?>				
			<!--  end paging................ -->
	
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