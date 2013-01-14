<?php
if (isset($_SESSION['user_id']))
{
	if ($_SESSION['tier'] == 1) {
		/*
		** Функция для генерации соли, используемоей в хешировании пароля
		** возращает 3 случайных символа
		*/
		//mysql_connect($adminsdb_address, $adminsdb_user, $adminsdb_pass) or die (mysql_error());
		//mysql_select_db($adminsdb_db) or die (mysql_error());
		$dbhandle2 = new PDO("mysql:host=$adminsdb_address;dbname=$adminsdb_db", $adminsdb_user, $adminsdb_pass);
		
		function GenerateSalt($n=64)
		{
			$key = '';
			$pattern = '1234567890abcdefghijklmnopqrstuvwxyz.,*_-=+$&';
			$counter = strlen($pattern)-1;
			for($i=0; $i<$n; $i++)
			{
				$key .= $pattern{rand(0,$counter)};
			}
			return $key;
		}

		if (empty($_POST))
		{
			?>
			<div id="page-heading">
				<h1>Registration</h1>
			</div>
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
				
					<!--  start table-content  -->
					
					<div id="table-content">
						<h2>Enter info for new admin</h2>
						
						<form id="regform" action="admin.php?view=register">
						
							<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
							<tr>
								<th valign="top">Login:</th>
								<td><input type="text" class="inp-form" name="login" /></td>
								<td></td>
							</tr>
							<tr>
								<th valign="top">Password:</th>
								<td><input type="text" class="inp-form" name="password" /></td>
								<td></td>
							</tr>
							<tr>
								<th valign="top">Tier:</th>
								<td>
									<select class="inp-form" id="tier" name="tier" form="regform">
										<option value="4">4 (Player Info Read Only)</option>
										<option value="3">3 (Player Info Read + Maps)</option>
										<option value="2">2 (Modify Players + Maps)</option>
										<option value="1">1 (Full Admin)</option>
									</select>
								</td>
								<td></td>
							</tr>
							<tr>
								<th>&nbsp;</th>
								<td valign="top">
									<input type="submit" value="" class="form-submit" />
								</td>
								<td></td>
							</tr>
							</table>
						</form>		
					</div>
					<div id="result"></div>
					<!--  end table-content  -->
					<script>
						  /* attach a submit handler to the form */
						  $("#regform").submit(function(event) {

							/* stop form from submitting normally */
							event.preventDefault(); 
								
							/* get some values from elements on the page: */
							var $form = $( this ),
								term = $form.find( 'input[name="login"]' ).val(),
								term2 = $form.find( 'input[name="password"]' ).val(),
								term3 = $("#tier").val(),
								url = $form.attr( 'action' );
								
							var d = document.getElementById('content-table-inner');
							var olddiv = document.getElementById('table-content');
							d.removeChild(olddiv);
							
							var d = document.getElementById('dvPopup');
							var olddiv = document.getElementById('closebutton');
							d.removeChild(olddiv);

							/* Send the data using post and put the results in a div */
							$.post( url, { login: term, password: term2, tier: term3 },
							  function( data ) {
								  var content = $( data ).find( '#content' );
								  $( "#result" ).empty().append( content );
							  }
							);
						  });
					</script>
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
		<?php
		}
		else
		{
			// обрабатывае пришедшие данные функцией mysql_real_escape_string перед вставкой в таблицу БД
			
			$login = '';
			$password = '';
			$error = false;
			$errort = '';
			if (isset($_POST['login'])) {
				$login = $_POST['login'];
			}
			if (isset($_POST['password'])) {
				$password = $_POST['password'];
			}
			if (isset($_POST['tier'])) {
				$tier = $_POST['tier'];
			} else {
				//$error = true;
				//$errot .= 'No tier specified';
				$tier = '';
			}
			
			// Sanitize user input
			if (preg_match('#[^0-9a-z_\-@()\.,~\!\+\$+]#i', $login)) {
				$error = true;
				$errort .= 'Invalid character in username.<br />';
			}
			if (preg_match('#[^0-9a-z_\-@()\.,~\!\+\$+]#i', $password)) {
				$error = true;
				$errort .= 'Invalid character in password.<br />';
			}
			if (preg_match('#[^0-9+]#', $tier)) {
				$error = true;
				$errort .= 'Invalid character in tier.<br />';
			}
			
			//$login = (isset($_POST['login'])) ? mysql_real_escape_string($_POST['login']) : '';
			//$password = (isset($_POST['password'])) ? mysql_real_escape_string($_POST['password']) : '';
			
			
			// проверяем на наличие ошибок (например, длина логина и пароля)
						
			if (strlen($login) < 3)
			{
				$error = true;
				$errort .= 'Login must be at least 3 characters.<br />';
			}
			if (strlen($password) < 8)
			{
				$error = true;
				$errort .= 'Password must be at least 8 characters.<br />';
			}
			
			// проверяем, если юзер в таблице с таким же логином
			/*
			$query = "SELECT `id`
						FROM `hive_admins`
						WHERE `hive_user`='{$login}'
						LIMIT 1";
			$sql = mysql_query($query) or die(mysql_error());
			*/
			$query = $dbhandle2->prepare("SELECT `id` FROM `hive_admins` WHERE `hive_user`=? LIMIT 1");
			$query->execute(array($login));
			$userexist = $query->fetchColumn();
			//if (mysql_num_rows($sql)==1)
			if ($userexist > 0) {
				$error = true;
				$errort .= 'Login already used.<br />';
			}
			//print_r($row);
			
			// если ошибок нет, то добавляем юзаре в таблицу
			if (!$error)
			{
				// генерируем соль и пароль
				
				$salt = GenerateSalt();
				$salt2 = GenerateSalt();
				//$hashed_password = md5(md5($password) . $salt);
				$hashed_password = hash('sha512', $salt.$password.$salt2);
				/*
				$query = "INSERT
							INTO `hive_admins`
							SET
								`hive_user`='{$login}',
								`hive_password`='{$hashed_password}',
								`salt`='{$salt}',
								`salt2`='{$salt2}',
								`tier`=2";
				$sql = mysql_query($query) or die(mysql_error());
				*/
				$query = $dbhandle2->prepare("INSERT INTO `hive_admins` SET `hive_user`=?,`hive_password`=?,`salt`=?,`salt2`=?,`tier`=?");
				$query->execute(array($login,$hashed_password,$salt,$salt2,$tier));

				//$query = "INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('REGISTER ADMIN: {$login}','{$_SESSION['login']}',NOW())";
				//$sql2 = mysql_query($query) or die(mysql_error());
				
				$query = $dbhandle2->prepare("INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES (?,?,NOW())");
				$query->execute(array('REGISTER ADMIN: '.$login,$_SESSION['login']));
				?>
				<!--  start message-green -->
				<div id="msg">
					<div id="message-green">
					<table border="0" width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td class="green-left">New admin is succesfully registered!</td>
						<td class="green-right"><a href="#" onclick="window.location.href = 'admin.php?view=admin';" class="close-green"><img src="<?php echo $path;?>images/table/icon_close_green.gif" alt="" /></a></td>
					</tr>
					</table>
					</div>
				</div>
				<!--  end message-green -->
				<?php
			}
			else
			{
				?>
				<div id="msg">
					<div id="message-red">
					<table border="0" width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td class="red-left">Error in registration process!</td>
						<td class="red-right"><a href="#" onclick="window.location.href = 'admin.php?view=admin';" class="close-red"><img src="<?php echo $path;?>images/table/icon_close_red.gif" alt="" /></a></td>
					</tr>
					</table>
					</div>
					<?php print $errort;?>
				</div>
				<?php
				
			}

		}
		//mysql_close();
	} else {
		header('Location: admin.php');
	}
}
else
{
	header('Location: admin.php');
}
?>