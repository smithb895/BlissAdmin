<?php
if (isset($_SESSION['user_id']))
{
	include_once('/modules/login_connect.php');
	if ($_SESSION['tier'] < 3) {
		$pagetitle = "Manage VIPS (Under Construction)";
		include_once('/modules/login_connect.php');
		//$query = "INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('Manage VIPS','{$_SESSION['login']}',NOW())";
		//$sql2 = mysql_query($query) or die(mysql_error());
		$query = $dbhandle2->prepare("INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('Manage VIPS',?,NOW())");
		$query->execute(array($_SESSION['login']));
		
		
		$query2 = $dbhandle->prepare("SELECT cl.description AS loadout_name,clp.*,p.name FROM cust_loadout cl JOIN cust_loadout_profile clp ON cl.id=clp.cust_loadout_id JOIN profile p ON p.unique_id=clp.unique_id;");
		$query2->execute();
		
		$vips = '';
		while ($row=$query2->fetch(PDO::FETCH_ASSOC)) {
			$vips .= "<tr>
						<td><input name=\"vip[]\" value=\"".$row['unique_id']."\" type=\"checkbox\"/></td>
						<td>".$row['unique_id']."</td>
						<td>".$row['name']."</td>
						<td>".$row['cust_loadout_id']."</td>
						<td>".$row['loadout_name']."</td>
					</tr>";
		}
		
?>
<div id="dvPopup" style="display:none; width:900px; height: 450px; border:4px solid #000000; background-color:#FFFFFF;">
				<a id="closebutton" style="float:right;" href="#" onclick="HideModalPopup('dvPopup'); return false;"><img src="images/table/action_delete.gif" alt="" /></a><br />
				<?php include ('modules/addvip.php'); ?>
</div>
	<div id="page-heading">
<?php
		echo "<title>".$pagetitle." - ".$sitename."</title>";
		echo "<h1>".$pagetitle."</h1>";
?>
	</div>
	<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
		<tr>
			<th rowspan="3" class="sized"><img src="images/shared/side_shadowleft.jpg" width="20" height="300" alt="" /></th>
			<th class="topleft"></th>
			<td id="tbl-border-top">&nbsp;</td>
			<th class="topright"></th>
			<th rowspan="3" class="sized"><img src="images/shared/side_shadowright.jpg" width="20" height="300" alt="" /></th>
		</tr>
		<tr>
			<td id="tbl-border-left"></td>
			<td>
			<!--  start content-table-inner ...................................................................... START -->
			<div id="content-table-inner">
			
				<div id="related-activities">
					<div id="related-act-top">
						<img width="271" height="43" alt="" src="images/forms/header_related_act.gif">
					</div>
					<div id="related-act-bottom">
						<div id="related-act-inner">
							<div class="left"><a href="#" onclick="ShowModalPopup('dvPopup'); return false;">
								<img width="21" height="21" alt="" src="images/forms/icon_plus.gif"></a>
							</div>
							<div class="right">
								<h5><a href="#" onclick="ShowModalPopup('dvPopup'); return false;">Add VIP</a></h5>
								Add new VIP
							</div>
							<div class="clear"></div>
						</div>
					</div>
				</div>
				
				<!--  start table-content  -->
				<div id="table-content">
				<form action="admin.php?view=vip" method="post">
					<table border="0" width="75%" cellpadding="0" cellspacing="0" id="product-table">
					<tr>
						<th class="table-header-repeat line-left"><a href="">Delete</a></th>
						<th class="table-header-repeat line-left" width="10%"><a href="">PlayerID</a></th>
						<th class="table-header-repeat line-left minwidth-1" width="30%"><a href="">Name</a></th>
						<th class="table-header-repeat line-left" width="10%"><a href="">Loadout ID</a></th>
						<th class="table-header-repeat line-left minwidth-1" width="50%"><a href="">Loadout Name</a></th>
					</tr>
					<?php echo $vips; ?>				
					</table>
					<input type="submit" class="submit-login"  />
					</div>
				</form>
				<!--  end table-content  -->
				
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
	} else {
		echo '
		<div id="page-heading">
			<h1>Access Denied</h1>
		</div>';
	}
} else {
	header('Location: admin.php');
}
?>