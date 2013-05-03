<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);
//include_once('/config.php');

if (isset($_SESSION['login']))
{
require_once('/modules/hive_connect.php');
require_once('/modules/login_connect.php');
$pagetitle = "Items check";

//$query = "INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('ITEMS CHECK','{$_SESSION['login']}',NOW())";
//$sql2 = mysql_query($query) or die(mysql_error());
$query = $dbhandle2->prepare("INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('ITEMS CHECK',?,NOW())");
$query->execute(array($_SESSION['login']));

?>

<div id="page-heading">
<?php
	//ini_set('max_execution_time', 300);
	echo "<title>".$pagetitle." - ".$sitename."</title>";
	echo "<h1>".$pagetitle."</h1>";
	
	error_reporting (E_ALL ^ E_NOTICE);
	
	//$items_ini = parse_ini_file("/items.ini", true);
	$xml = file_get_contents('/items.xml', true);
	require_once('/modules/xml2array.php');
	$items_xml = XML2Array::createArray($xml);
	//var_dump($items_xml);
	// Fix for lowercase items created by adding items from hive admin page
	/*function array_map_r( $func, $arr ) {
		$newArr = array();
		foreach( $arr as $key => $value ) {
			$newArr[ $key ] = ( is_array( $value ) ? array_map_r( $func, $value ) : ( is_array($func) ? call_user_func_array($func, $value) : $func( $value ) ) );
		}
		return $newArr;
	}*/
	//$items_xml = array_map_r('strtolower', $items_xml_case);
	
	
	//print_r($items_xml);
	//$query = "SELECT * FROM survivor";
	//$countquery = "SELECT count(*) FROM profile p LEFT JOIN survivor s ON p.unique_id=s.unique_id WHERE s.is_dead=0";
	$query = "SELECT p.name,s.*,w.name AS worldname,i.id AS instance_id FROM profile p LEFT JOIN survivor s ON p.unique_id=s.unique_id JOIN world w ON w.id=s.world_id JOIN instance i ON w.id=i.world_id WHERE s.is_dead=0";
	/*if ($cntres = $dbhandle->query($countquery)) {
		$number = $cntres->fetchColumn();
		$res = $dbhandle->query($query);
	}*/
	//print_r($res);
	//$res = mysql_query($query) or die(mysql_error());
	//$number = mysql_num_rows($res);
	$rows = null;
	//$query = $dbhandle->query("select p.name, s.* from profile p left join survivor s on p.unique_id = s.unique_id where s.is_dead = 0");
	$res = $dbhandle->query($query);
	$allrows = $res->fetchAll(PDO::FETCH_ASSOC);
	$number = count($allrows);
	$itemscount = 0;
	if ($number == 0) {
	  echo "<CENTER>Не найдено</CENTER>";
	} else {
	  //while ($row=mysql_fetch_array($res)) {
	  //$allrows = $res->fetchAll(PDO::FETCH_ASSOC);
	  //while ($row=$res->fetch(PDO::FETCH_ASSOC)) {
	  foreach ($allrows as $row) {
		//print_r($row);
		$Worldspace = str_replace("[", "", $row['worldspace']);
		$Worldspace = str_replace("]", "", $Worldspace);
		$Worldspace = str_replace(",", ",", $Worldspace);
		$Worldspace = explode(",", $Worldspace);
		
		$Worldname = ucfirst($row['worldname']);
		
		$Inventory = $row['inventory'];	
		$Inventory = str_replace(",", ",", $Inventory);
		$Inventory  = json_decode($Inventory);
		
		$Backpack  = $row['backpack'];
		$Backpack = str_replace(",", ",", $Backpack);
		$Backpack  = json_decode($Backpack);

		$Unknown = null;
		$Unknown = array();
		if (is_array($Inventory[0])){
			if (is_array($Inventory[1])){
				$Inventory = (array_merge($Inventory[0], $Inventory[1]));
			}
		} else {
			if (is_array($Inventory[1])){
				$Inventory = $Inventory[1];
			}			
		}				
		
		$bpweaponscount = count($Backpack[1][0]);
		$bpweapons = array();
		for ($m=0; $m<$bpweaponscount; $m++){
			for ($mi=0; $mi<$Backpack[1][1][$m]; $mi++){
				$bpweapons[] = $Backpack[1][0][$m];
			}
			//if(array_key_exists(0,$Backpack[1][$m])){
			//	$bpweapons[] = $Backpack[1][$m][0];
			//}
		}		
		$bpitemscount = count($Backpack[2][0]);
		$bpitems = array();
		for ($m=0; $m<$bpitemscount; $m++){
			for ($mi=0; $mi<$Backpack[2][1][$m]; $mi++){
				$bpitems[] = $Backpack[2][0][$m];
			}
		}
		if (is_array($bpweapons)) {
			$Backpack = (array_merge($bpweapons, $bpitems));
		} else {
			$Backpack = $bpitems;
		}
		if (is_array($Inventory)) {
			$Inventory = (array_merge($Inventory, $Backpack));
		} else {
			$Inventory = $Backpack;
		}		
							
		for ($i=0; $i<count($Inventory); $i++){
			if(array_key_exists($i,$Inventory)){
				$curitem = $Inventory[$i];
				if (is_array($curitem)){$curitem = $Inventory[$i][0];}
				//$items_xml_lowercase = array_change_key_case($items_xml['items'], CASE_LOWER);
				if(!array_key_exists('s'.strtolower($curitem),$items_xml['items'])){
				//if (! (in_array(strtolower($curitem), array_map('strtolower', array_keys($items_xml['items']))))) {
					if ($curitem != "") {
						$Unknown[] = $curitem;
					}
				}
				/*$item_names = array_keys($items_xml['items']);
				foreach ($item_names as $oneitem) {
					if (!is_array($oneitem)) {
						if (strcasecmp('s'.$curitem,$oneitem) != 0) {
							$Unknown[] = $curitem;
						}
					}
				}*/
			}
		}
		
		$name = $row['name'];	
		$icon1 = '<a href="admin.php?view=actions&deletecheck='.$row['id'].'"><img src="'.$path.'images/icons/player_dead.png" title="Delete '.$name.'" alt="Delete '.$name.'"/></a>';		
		if ($row['is_dead'] == 1) {
				$status = '<img src="'.$path.'images/icons/player_dead.png" title="'.$name.' is Dead" alt="'.$name.' is Dead"/>';
		}
		if ($row['is_dead'] == 0) {
				$status = '<img src="'.$path.'images/icons/player.png" title="'.$name.' is Alive" alt="'.$name.' is Alive"/>';
		}
		if (count($Unknown)>0){
			$rows .= "<tr>
				<td align=\"center\" class=\"gear_preview\"><a href=\"amin.php?view=actions&deletecheck=".$row['unique_id']."\">".$icon1."</td>
				<td align=\"center\" class=\"gear_preview\">".$status."</td>
				<td align=\"center\" class=\"gear_preview\"><a href=\"admin.php?view=info&show=1&id=".$row['unique_id']."&cid=".$row['id']."\">".$name."</a></td>
				<td align=\"center\" class=\"gear_preview\"><a href=\"admin.php?view=info&show=1&id=".$row['unique_id']."&cid=".$row['id']."\">".$row['unique_id']."</a></td>
				<td align=\"center\" class=\"gear_preview\"><a href=\"admin.php?view=tavianamap&show=0&instance_id=".$row['instance_id']."\">".$Worldname."</a></td>
				<td align=\"center\" class=\"gear_preview\">";
				foreach($Unknown as $uitem => $uval)
				{
					$rows .= $uval."; ";
					$itemscount++;
				}
			$rows .= "</td></tr>";
		}
		
		
	  }
	}								

			
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

			<!--  start table-content  -->
			<div id="table-content">
			<!--  start message-red -->
			<?php
			if ($itemscount>0){
			?>
				<div id="message-red">
					<table border="0" width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td class="red-left">WARNING! <?php echo $itemscount;?> unknown items found!</td>
						<td class="red-right"><a class="close-red"><img src="<?php echo $path;?>images/table/icon_close_red.gif"   alt="" /></a></td>
					</tr>
					</table>
				</div>			
			<!--  end message-red -->
			<!--  start product-table ..................................................................................... -->

				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
				<tr>
					<th class="table-header-repeat line-left minwidth-1" width="5px"><a href="">Remove</a>	</th>
					<th class="table-header-repeat line-left minwidth-1" width="5px"><a href="">Player Status</a></th>
					<th class="table-header-repeat line-left minwidth-1" width="5px"><a href="">Player Name</a>	</th>
					<th class="table-header-repeat line-left minwidth-1" width="5px"><a href="">Player ID</a></th>
					<th class="table-header-repeat line-left minwidth-1" width="5px"><a href="">Map</a></th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">Unknown items</a></th>
				</tr>
				<?php
					echo $rows;
				?>				
				</table>
				<!--  end product-table................................... --> 

			<?php
			} else {
			?>
				<div id="message-red">
					<table border="0" width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td class="red-left">No unknown items found!</td>
						<td class="red-right"><a class="close-red"><img src="<?php echo $path;?>images/table/icon_close_red.gif"   alt="" /></a></td>
					</tr>
					</table>
				</div>				
			</div>
			<?php } ?>
			<!--  end content-table  -->					
			
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