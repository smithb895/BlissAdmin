<?php 
if (isset($_SESSION['login']))
{
?>
<!--  start say-box -->
	<form action="admin.php?view=actions" method="post">
		<input type="text" name="say" id="chatbox" />
		<br />
		<input type="submit" value="Send" id="submit-chat" />
	</form>
	<br />

<!--  end say-box -->
<?php
}
else
{
	header('Location: admin.php');
}
?>