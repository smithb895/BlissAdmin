<?php
//Set no caching
/*header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");*/

if (isset($_SESSION['user_id']))
{
	include_once('/modules/login_connect.php');
	if ($_SESSION['tier'] == 1) {
		$pagetitle = "Manage admins";
		$delresult = "";
		//$dbhandle2 = new PDO("mysql:host=$adminsdb_address;dbname=$adminsdb_db", $adminsdb_user, $adminsdb_pass);
		//include_once('/modules/login_connect.php');
		if (isset($_POST["user"])){
			$aDoor = $_POST["user"];
			//print_r($aDoor);
			$N = count($aDoor);
			//echo "<br />";
			//echo $N;
			for($i=0; $i < $N; $i++)
			{
				//$query = "SELECT * FROM hive_admins WHERE id = ".$aDoor[$i].""; 
				//$res2 = mysql_query($query) or die(mysql_error());
				$query = $dbhandle2->prepare("SELECT hive_user FROM hive_admins WHERE id=?");
				$query->execute(array($aDoor[$i]));
				//while ($row2=mysql_fetch_array($res2)) {
				while ($row2=$query->fetch()) {
					//$query = "INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('DELETE ADMIN: ".$row2['login']."','{$_SESSION['login']}',NOW())";
					//$sql2 = mysql_query($query) or die(mysql_error());
					//$query = "DELETE FROM `hive_admins` WHERE id='".$aDoor[$i]."'";
					//$sql2 = mysql_query($query) or die(mysql_error());
					
					$query2 = $dbhandle2->prepare("INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES (?,?,NOW())");
					$query2->execute(array('DELETE ADMIN: '.$row2['login'],$_SESSION['login']));
					$query3 = $dbhandle2->prepare("DELETE FROM `hive_admins` WHERE id=?");
					$query3->execute(array($aDoor[$i]));
					//echo $aDoor[$i];
					
					$delresult .= '<div id="message-green">
					<table border="0" width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td class="green-left">Admin '.$row2['login'].' successfully removed!</td>
						<td class="green-right"><a class="close-green"><img src="images/table/icon_close_green.gif" alt="" /></a></td>
					</tr>
					</table>
					</div>';
				}		
				//echo($aDoor[$i] . " ");
			}
			//echo $_GET["deluser"];
		}
		
		//$query = "SELECT * FROM hive_admins ORDER BY id ASC"; 
		//$res = mysql_query($query) or die(mysql_error());
		//$number = mysql_num_rows($res);
		$query = $dbhandle2->prepare("SELECT id,hive_user,tier,lastlogin,locked,failed_attempts FROM hive_admins ORDER BY id ASC");
		$query->execute();
		
		$users="";
		//while ($row=mysql_fetch_array($res)) {
		while ($row=$query->fetch()) {
			$users .= "<tr>
						<td><input name=\"user[]\" value=\"".$row['id']."\" type=\"checkbox\"/></td>
						<td>".$row['id']."</td>
						<td>".$row['hive_user']."</td>
						<td>".$row['tier']."</td>
						<td>".$row['locked']."</td>
						<td>".$row['lastlogin']."</td>
					</tr>";
		}
		
		//$query = "INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('MANAGE ADMINS','{$_SESSION['login']}',NOW())";
		//$sql2 = mysql_query($query) or die(mysql_error());
		$query = $dbhandle2->prepare("INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('MANAGE ADMINS',?,NOW())");
		$query->execute(array($_SESSION['login']));
		
		
		
		

?>
<div id="dvPopup" style="display:none; width:900px; height: 450px; border:4px solid #000000; background-color:#FFFFFF;">
				<a id="closebutton" style="float:right;" href="#" onclick="HideModalPopup('dvPopup'); return false;"><img src="images/table/action_delete.gif" alt="" /></a><br />
				<?php include ('modules/register.php'); ?>
</div>
	<div id="page-heading">
		<h1><?php echo $pagetitle; ?></h1>
		<h1><?php echo "<title>".$pagetitle." - ".$sitename."</title>"; ?></h1>
	</div>
	<!-- end page-heading -->

	
	
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
			<?php echo $delresult; ?>
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
							<h5><a href="#" onclick="ShowModalPopup('dvPopup'); return false;">Add admin</a></h5>
							Add new administrator
						</div>
						<div class="clear"></div>
					</div>
				</div>
			</div>	
				
			<!--  start table-content  -->
			<div id="table-content">
			<form action="admin.php?view=admin" method="post">
				<table border="0" width="75%" cellpadding="0" cellspacing="0" id="product-table">
				<tr>
					<th class="table-header-repeat line-left"><a href="">Delete</a></th>
					<th class="table-header-repeat line-left" width="5%"><a href="">Id</a>	</th>
					<th class="table-header-repeat line-left minwidth-1" width="65%"><a href="">Login</a></th>
					<th class="table-header-repeat line-left" width="5%"><a href="">Tier</a></th>
					<th class="table-header-repeat line-left" width="5%"><a href="">Locked</a></th>
					<th class="table-header-repeat line-left minwidth-1" width="20%"><a href="">Last access</a></th>
				</tr>
				<?php echo $users; ?>				
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