<?php
$debug = '';
//ob_end_clean();
if (isset($_SESSION['login']))
{
	include ('info/'.$show.'.php');
}
else
{
	header('Location: admin.php');
}
//ob_end_clean();
?>