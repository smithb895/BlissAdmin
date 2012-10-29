<?php
if (isset($_SESSION['user_id']))
{	
	//if (isset($_GET["url"])){
		if (isset($_GET["kick"])){
			$cmd = "kick ".$_GET["kick"];
			//$query = "INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('Player Kicked','{$_SESSION['login']}',NOW())";
			//$sql2 = mysql_query($query) or die(mysql_error());
			$query = $dbhandle->prepare("INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('Player Kicked',?,NOW())");
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
			$query = $dbhandle->prepare("INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('Player Banned',?,NOW())");
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
			$query = $dbhandle->prepare("INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('Used Global',?,NOW())");
			$query->execute(array($_SESSION['login']));
				
			$answer = rcon($serverip,$serverport,$rconpassword,$cmd);
			?>
			<script type="text/javascript">
				window.location = 'admin.php';
			</script>
			<?php
		}
		if (isset($_GET["delete"])){

			//$remquery = "Delete FROM objects WHERE id=".$_GET["delete"];
			//$result = mysql_query($remquery) or die(mysql_error());
			//$class = mysql_fetch_assoc($result);
			//$query = "INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('Removed Object ".$_GET["delete"]."','{$_SESSION['login']}',NOW())";
			//$sql2 = mysql_query($query) or die(mysql_error());
			
			$remquery = $dbhandle->prepare("Delete FROM objects WHERE id=?");
			$result = $remquery->execute(array($_GET['delete']));
			$class = $result->fetch();
			$query = $dbhandle->prepare("INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('Removed Object ?',?,NOW())"); // Might need fixing...  <<<<<<<<<<<<<<<<<<<<<
			$query->execute(array($_GET["delete"],$_SESSION['login']));
			
			?>
			<script type="text/javascript">
				window.location = 'admin.php?view=map&show=7';
			</script>
			<?php
		}
		if (isset($_GET["deletecheck"])){

			//$remquery = "delete from id using instance_deployable id join survivor s on id.owner_id = s.id where s.id = '".$_GET["deletecheck"]."'";
			//$result = mysql_query($remquery) or die(mysql_error());
			//$class = mysql_fetch_assoc($result);
			//$remquery1 = "Delete FROM survivor WHERE id='".$_GET["deletecheck"]."'";
			//$result1 = mysql_query($remquery1) or die(mysql_error());
			//$class1 = mysql_fetch_assoc($result1);
			
			$remquery = $dbhandle->prepare("delete from id using instance_deployable id join survivor s on id.owner_id = s.id where s.id = ?");
			$result = $remquery->execute(array($_GET["deletecheck"]));
			$class = $result->fetch();
			$remquery1 = $dbhandle->prepare("Delete FROM survivor WHERE id=?");
			$result1 = $remquery1->execute(array($_GET["deletecheck"]));
			$class1 = $result1->fetch();
			
			?>
			<script type="text/javascript">
				window.location = 'admin.php?view=check';
			</script>
			<?php
		}
		if (isset($_GET["deletespawns"])){

			//$remquery = "Delete FROM spawns WHERE ObjectID=".$_GET["deletespawns"];
			//$result = mysql_query($remquery) or die(mysql_error());
			//$class = mysql_fetch_assoc($result);
			
			$remquery = $dbhandle->prepare("Delete FROM spawns WHERE ObjectID=?");
			$result = $remquery->execute(array($_GET['deletespawns']));
			$class = $result->fetch();
			?>
			<script type="text/javascript">
				window.location = 'admin.php?view=map&show=8';
			</script>
			<?php
		}
		if (isset($_GET["resetlocation"])){

			//$remquery = "update survivor set pos = '[]' WHERE id='".$_GET["resetlocation"]."'";
			//$result = mysql_query($remquery) or die(mysql_error());
			//$class = mysql_fetch_assoc($result);
			//$query = "INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('Reset Player Location of ID:".$_GET["resetlocation"]."','{$_SESSION['login']}',NOW())";
			//$sql2 = mysql_query($query) or die(mysql_error());
			
			$remquery = $dbhandle->prepare("update survivor set pos = '[]' WHERE id=?");
			$result = $remquery->execute(array($_GET['resetlocation']));
			$class = $result->fetch();
			$query = $dbhandle->prepare("INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('Reset Player Location of ID:?',?,NOW())");
			$query->execute(array($_GET['resetlocation'],$_SESSION['login']));
			
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
