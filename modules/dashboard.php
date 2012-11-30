<?php
if (isset($_SESSION['user_id']))
{

//ini_set( "display_errors", 0);
//error_reporting (E_ALL ^ E_NOTICE);

include_once('/config.php');
$pagetitle = "Dashboard";

$logs = "";
//$query = "SELECT * FROM `logs` ORDER BY `timestamp` DESC LIMIT 100";
include('login_connect.php');
$queryAdminLog = $dbhandle2->query('SELECT * FROM `logs` ORDER BY `timestamp` DESC LIMIT 100');
//$res = mysql_query($query) or die(mysql_error());
//while ($row=mysql_fetch_array($res)) {
while ($row = $queryAdminLog->fetch(PDO::FETCH_ASSOC)) {
	$logs .= $row['timestamp'].' '.$row['user'].': '.$row['action'].'<br />';
}
//$xml = file_get_contents('quicklinks.xml', true);

//require_once('xml2array.php');
//$quicklinks = XML2Array::createArray($xml);

?>
<script src="js/dashboard.js" type="text/javascript"></script>
<div id="page-heading">
<?php
	echo "<title>".$pagetitle." - ".$sitename."</title>";
	echo "<h1>".$pagetitle."</h1>";

?>
</div>

<div id="main-page-content">
	<div class="centered">
		<h2>Select Server</h2>
		<center>Under Construction!</center><br />
		<select id="selectserver" name="selectserver">
			<?php
				global $DayZ_Servers;
				foreach ($DayZ_Servers as $dayz_server) {
					echo '<option value="'.$dayz_server->getMissionInstance().'">'.$dayz_server->getServerName().' - '.$dayz_server->getServerMap().' (Instance '.$dayz_server->getMissionInstance().')</option>';
				}
			?>
		</select>
		<br />
		<br />
		<h2>Current Players - <?php echo '<span id="servername">'.$DayZ_Servers[0]->getServerName().'</span> - <span id="servermap">'.$DayZ_Servers[0]->getServerMap().'</span>'; ?></h2>
		<div id="current-players">
			<table id="player_data_table">
			<thead>
				<tr id="column_title">
					<th>Name</th>
					<th>UID</th>
					<th>GUID</th>
					<th>IP</th>
					<th>Z Kills</th>
					<th>B Kills</th>
					<th>P Kills</th>
					<th>Position</th>
					<th>Time Alive</th>
					<th>Last Update</th>
					<th>Ping</th>
				</tr>
			</thead>
				<tbody id="player-data-rows"><?php //include('current_players.php'); ?><!-- Current players table, filled by AJAX --></tbody>
			</table>
		</div>
	</div>
	<br />
	<div class="centered">
		<div id="server_console_div">
			<h2>Server Console</h2>
			<div id="server-console">Testing</div>
		</div>
	</div>
	<br />
	<div class="centered">
		<div id="chat_msg_div">
			<h2>Server Message</h2>
			<form action="" method="post">
				<input type="text" name="say" id="chatbox" />
				<input type="submit" value="Send" id="submit-chat" />
			</form>
		</div>
	</div>
	<br />
	<?php
		if ($_SESSION['tier'] == 1) {
			echo '
				<div class="centered">
					<div id="adminlog">'.$logs.'</div>
				</div>
			';
		}
	?>
</div>

<div class="clear">&nbsp;</div>
<?php
}
else
{
	header('Location: admin.php');
}
?>
