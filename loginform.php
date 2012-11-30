<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Login - DayZ Administration</title>
<link rel="stylesheet" href="css/screen.css" type="text/css" media="screen" title="default" />
<!-- pngFix for IE 5.5 and 6 PNG transparency -->
<script src="/js/jquery/jquery-1.8.2.min.js" type="text/javascript"></script>
<script src="js/jquery/jquery.pngFix.pack.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
$(document).pngFix( );
});
</script>
<!--  End pngFix -->
</head>
<body id="login-bg"> 
 
<div id="login-holder">

	<div id="logo-login">
		<a href="/"><img src="images/logo.png" width="451px" height="218px" alt="" /></a>
	</div>
	
	<div class="clear"></div>
	<form action="login.php" method="post">
		<div id="loginbox">	
			<div id="login-inner">
				<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<th>Username</th>
					<td><input type="text" name="login" class="login-inp" /></td>
				</tr>
				<tr>
					<th>Password</th>
					<td><input type="password" name="password" value="************"  onfocus="this.value=''" class="login-inp" /></td>
				</tr>
				<tr>
					<th></th>
					<td valign="top"><input type="checkbox" name="remember" class="checkbox-size" id="login-check" /><label for="login-check">Remember me</label></td>
				</tr>
				<tr>
					<th></th>
					<td><input type="submit" class="submit-login"  /></td>
				</tr>
				</table>
			</div>
			<div class="clear"></div>
		</div>
	</form>
</div>
</body>
</html>