<?php
if (isset($_SESSION['user_id']))
{
	if ($_SESSION['tier'] == 1) {
		$pagetitle = "Database Admin (Under Construction)";
		//$query = "INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('DATABASE ADMIN','{$_SESSION['login']}',NOW())";
		//$sql2 = mysql_query($query) or die(mysql_error());
		$query = $dbhandle2->prepare("INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('DATABASE ADMIN',?,NOW())");
		$query->execute(array($_SESSION['login']));
?>

<div id="page-heading">
<?php
	echo "<title>".$pagetitle." - ".$sitename."</title>";
	echo "<h1>".$pagetitle."</h1>";
?>
</div>

<div id="main-page-content">
	<div class="left">
		<h2>Import Vehicle Spawns from mission.sqm</h2>
		<p>Here you can upload a mission.sqm that was created in the mission editor, and it will insert all vehicles placed in the mission editor into the HIVE.  Make sure you ONLY place vehicles in the mission.sqm and NOTHING ELSE!</p>
		<br />
		<form enctype="multipart/form-data" action="modules/uploader.php" method="POST">
			Choose Map: 
			<br />
			&nbsp;&nbsp;&nbsp;<input type="radio" name="worldID"  value="1" /> Chernarus
			<br />
			&nbsp;&nbsp;&nbsp;<input type="radio" name="worldID"  value="2" /> Lingor
			<br />
			&nbsp;&nbsp;&nbsp;<input type="radio" name="worldID"  value="8" /> Namalsk
			<br />
			<br />
			<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
			Choose file: <br />
			&nbsp;&nbsp;&nbsp;<input name="missionfile" type="file" />
			<br />
			<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Upload" />
		</form>
		<br />
		<br />
		<br />
	</div>
	<div class="right">
		<h2>Database Cleanup</h2>
		<br />
		<br />
		<br />
	</div>
	<div class="centered">
		<h2>Database Stats</h2>
		<br />
		<br />
		<br />
	</div>
	
</div>
<div class="clear">&nbsp;</div>
<?php
	} else {
		$pagetitle = "Access Denied";
		echo '
			<div id="page-heading">
				<title>'.$pagetitle.' - '.$sitename.'</title>
				<h1>'.$pagetitle.'</h1>
			</div>
		';
	}
} else {
	header('Location: admin.php');
}
?>