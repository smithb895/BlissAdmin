<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);

//session_start();
//require('config.php');
require('/modules/login_connect.php');


$username = preg_replace('#[^0-9a-z_ \.,\[\]\{\}\!@\#\(\)\-\$%\^&\*\+\\\?=~;:\|+]#i', '', $_POST['login']);
$password = preg_replace('#[^0-9a-z_ \.,\[\]\{\}\!@\#\(\)\-\$%\^&\*\+\\\?=~;:\|+]#i', '', $_POST['password']);
//$username = $_POST['login'];
//$password = $_POST['password'];

//if (!empty($_POST['login']))
if ($username && $password)
{
	//mysql_connect($adminsdb_address, $adminsdb_user, $adminsdb_pass) or die (mysql_error());
	//mysql_select_db($adminsdb_db) or die (mysql_error());
	//$dbhandle2 = new PDO("mysql:host=$adminsdb_address;dbname=$adminsdb_db", $adminsdb_user, $adminsdb_pass);
	
	//$countqry = $dbhandle->query("SELECT count(*) FROM `hive_admins` WHERE `hive_user`
	$query = $dbhandle2->prepare("SELECT `id`,`hive_user`,`hive_password`,`salt`,`salt2`,`tier` FROM `hive_admins` WHERE `hive_user`=? LIMIT 1");
	$query->execute(array($username));
	if ($row=$query->fetch(PDO::FETCH_ASSOC))
	{
		$salt = $row['salt'];
		$salt2 = $row['salt2'];
		
		// More secure password hashing...
		$password = hash('sha512', $salt.$password.$salt2);
		
		if ($password == $row['hive_password'])
		{
			session_start();
			// то мы ставим об этом метку в сессии (допустим мы будем ставить ID пользователя)
			$_SESSION['user_id'] = $row['id'];
			$_SESSION['login'] = $username;
			$_SESSION['tier'] = $row['tier'];
			// если пользователь решил "запомнить себя"
			// то ставим ему в куку логин с хешем пароля
			
			$time = 86400; // ставим куку на 24 часа
			
			if (isset($_POST['remember']))
			{
				setcookie('login', $username, time()+$time, "/");
				setcookie('password', $password, time()+$time, "/");
			}
			
			$query = $dbhandle2->prepare("UPDATE `hive_admins` SET `lastlogin`= NOW() WHERE `hive_user`=? LIMIT 1");
			$query->execute(array($username));
			$query = $dbhandle2->prepare("INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('LOGIN',?,NOW())");
			$query->execute(array($username));
			// и перекидываем его на закрытую страницу
			header('Location: admin.php');
			exit;

			// не забываем, что для работы с сессионными данными, у нас в каждом скрипте должно присутствовать session_start();
		}
		else
		{
			header('Location: admin.php');
		}
	}
	else
	{
		header('Location: admin.php');
	}
	//mysql_close();
}
?>
