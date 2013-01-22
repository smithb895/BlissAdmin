<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);
if (isset($_SESSION['user_id']))
{	
	//if (isset($_GET["url"])){
		/*
		if (isset($_GET["keepalive"])) {
			$cmd = '';
			$answer = rcon_keepalive($serverip,$serverport);
		}
		*/
		if (isset($_GET["kick"])){
			$cmd = "kick ".$_GET["kick"];
			//$query = "INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('Player Kicked','{$_SESSION['login']}',NOW())";
			//$sql2 = mysql_query($query) or die(mysql_error());
			$query = $dbhandle2->prepare("INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('Player Kicked',?,NOW())");
			$query->execute(array($_SESSION['login']));
				
			$answer = rcon($serverip,$serverport,$rconpassword,$cmd);
			?>
			<script type="text/javascript">
				window.location = 'admin.php?view=table&show=0';
			</script>
			<?php
		}
		if (isset($_GET["ban"])){
			$cmd = "ban ".$_GET["ban"];
			//$query = "INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('Player Banned','{$_SESSION['login']}',NOW())";
			//$sql2 = mysql_query($query) or die(mysql_error());
			$query = $dbhandle2->prepare("INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('Player Banned',?,NOW())");
			$query->execute(array($_SESSION['login']));
				
			$answer = rcon($serverip,$serverport,$rconpassword,$cmd);
			?>
			<script type="text/javascript">
				window.location = 'admin.php?view=table&show=0';
			</script>
			<?php
		}	
		if (isset($_POST["say"])){
			$id = "-1";
			if (isset($_GET["id"])){
				$id = $_GET["id"];
			}
			$cmd = "Say ".$id." ".$_POST["say"];
			//$query = "INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('Used Global','{$_SESSION['login']}',NOW())";
			//$sql2 = mysql_query($query) or die(mysql_error());
			$query = $dbhandle2->prepare("INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('Used Global',?,NOW())");
			$query->execute(array($_SESSION['login']));
				
			$answer = rcon($serverip,$serverport,$rconpassword,$cmd);
			?>
			<script type="text/javascript">
				window.location = 'admin.php';
			</script>
			<?php
		}
		if (isset($_GET["delete"])){
			$todelete = preg_replace('#[^0-9+]#', '', $_GET['delete']);
			//$remquery = "Delete FROM objects WHERE id=".$_GET["delete"];
			//$result = mysql_query($remquery) or die(mysql_error());
			//$class = mysql_fetch_assoc($result);
			//$query = "INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('Removed Object ".$_GET["delete"]."','{$_SESSION['login']}',NOW())";
			//$sql2 = mysql_query($query) or die(mysql_error());
			$remquery = $dbhandle->prepare("DELETE FROM objects WHERE id=?");
			$remquery->execute(array($todelete));
			$query = $dbhandle2->prepare("INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('Removed Object ?',?,NOW())");
			$query->execute(array($todelete,$_SESSION['login']));
			?>
			<script type="text/javascript">
				window.location = 'admin.php?view=map&show=7';
			</script>
			<?php
		}
		if (isset($_GET["deletecheck"])) {
			$todelete = preg_replace('#[^0-9+]#', '', $_GET["deletecheck"]);
			//$remquery = "delete from id using instance_deployable id join survivor s on id.owner_id = s.id where s.id = '".$_GET["deletecheck"]."'";
			//$result = mysql_query($remquery) or die(mysql_error());
			//$class = mysql_fetch_assoc($result);
			//$remquery1 = "Delete FROM survivor WHERE id='".$_GET["deletecheck"]."'";
			//$result1 = mysql_query($remquery1) or die(mysql_error());
			//$class1 = mysql_fetch_assoc($result1);
			$remquery = $dbhandle->prepare("DELETE FROM id USING instance_deployable id JOIN survivor s ON id.owner_id=s.id WHERE s.id=?");
			$remquery->execute(array($todelete));
			$affected = $remquery->rowCount();
			$remquery1 = $dbhandle->prepare("DELETE FROM survivor WHERE id=?");
			$remquery1->execute(array($todelete));
			$affected1 = $remquery1->rowCount();
			
			echo "<b>Deleted $affected deployed items from player.</b><br />";
			echo "<b>Deleted $affected1 player records from the survivor table.</b><br />";
			
			?>
			<script type="text/javascript">
				window.location="admin.php?view=check"
				/*
				setTimeout(function() {
					window.location.href = 'admin.php?view=check';
				}, 5000);
				*/
			</script>
			<?php
		}
		if (isset($_GET["deletespawns"])){
			$todelete = preg_replace('#[^0-9+]#', '', $_GET['deletespawns']);
			//$remquery = "Delete FROM spawns WHERE ObjectID=".$_GET["deletespawns"];
			//$result = mysql_query($remquery) or die(mysql_error());
			//$class = mysql_fetch_assoc($result);
			$deleteSpawnsQuery = $dbhandle->prepare("Delete FROM spawns WHERE ObjectID=?");
			$deleteSpawnsQuery->execute(array($todelete));
			?>
			<script type="text/javascript">
				window.location = 'admin.php?view=map&show=8';
			</script>
			<?php
		}
		if (isset($_GET["resetlocation"])){
			$_getdata = preg_replace('#[^0-9+]#', '', $_GET["resetlocation"]);
			//$remquery = "update survivor set pos = '[]' WHERE id='".$_GET["resetlocation"]."'";
			//$result = mysql_query($remquery) or die(mysql_error());
			//$class = mysql_fetch_assoc($result);
			//$query = "INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('Reset Player Location of ID:".$_GET["resetlocation"]."','{$_SESSION['login']}',NOW())";
			//$sql2 = mysql_query($query) or die(mysql_error());
			$resetLocationQuery = $dbhandle->prepare("update survivor set worldspace = '[]' WHERE id=?");
			$resetLocationQuery->execute(array($_getdata));
			$resetLocationLogQuery = $dbhandle2->prepare("INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('Reset Player Location of ID: ?',?,NOW())");
			$resetLocationLogQuery->execute(array($_getdata,$_SESSION['login']));
			?>
			<script type="text/javascript">
				window.location = 'admin.php?view=table&show=0';
			</script>
			<?php
		}		
	//}
	?>
	<script type="text/javascript">
		window.location = 'admin.php';
	</script>
	<?php

	
	
}
else
{
	header('Location: admin.php');
}
?>