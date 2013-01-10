<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--<script src="js/jquery.min.js" type="text/javascript"></script>-->
<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);

//require('session.php');
session_start();
require_once('config.php');
//require_once('/modules/login_connect.php');
global $iid;
global $serverip;
global $serverport;
global $rconpassword;
global $map;
global $world;


if (isset($_GET['logout']))
{
	require_once('/modules/login_connect.php');
	$query = $dbhandle2->prepare("INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('LOGOUT',?,NOW())");
	$query->execute(array($_SESSION['login']));
	
	if (isset($_SESSION['user_id']))
		unset($_SESSION['user_id']);
		
	setcookie('login', '', 0, "/");
	setcookie('password', '', 0, "/");
	header('Location: admin.php');
	exit;
}

if (isset($_SESSION['user_id']))
{
	require_once('/modules/hive_connect.php');
	require_once('/modules/login_connect.php');
	mysql_connect($hostname, $username, $password) or die (mysql_error());
	mysql_select_db($dbName) or die (mysql_error());
	include ('modules/rcon.php');
	include ('modules/tables/rows.php');
	function slashes($el)
	{
		if (is_array($el))
			foreach($el as $k=>$v)
				slashes($el[$k]);
		else $el = stripslashes($el); 
	}

	if (isset($_GET["show"])){
		$show = $_GET["show"];
	}else{
		$show = 0;
	}
	if (isset($_GET['instance_id'])) {
		$_current_instance = preg_replace('#[^0-9]#', '', $_GET['instance_id']);

		foreach ($DayZ_Servers as $server) {
			if ($_current_instance == $server->getMissionInstance()) {
				$iid = $server->getMissionInstance();
				$serverip = $server->getServerIP();
				$serverport = $server->getServerPort();
				$rconpassword = $server->getRconPassword();
				$map = $server->getServerMap();
				$world = $server->getWorldID();
			}
		}
	}


	// Start: page-header 
	include ('modules/header.php');
	// End page-header

	if (isset($_GET['view'])){
		include ('modules/'.$_GET["view"].'.php');
	} else {
		include ('modules/dashboard.php');
	}

	// Start: page-footer 
	include ('modules/footer.php');
	// End page-footer
?>
</div>
<!--  end content -->
</div>
<!--  end content-outer........................................................END -->

<div class="clear">&nbsp;</div>
 
</body>
</html>
<?php
}
else
{
	//include ('modules/login.php');
	header('Location: loginform.php');
}
?>