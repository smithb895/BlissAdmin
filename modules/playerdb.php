<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);
if (isset($_SESSION['user_id'])) {
	include_once('config.php');
	//include_once('hive_connect.php');
	include_once('modules/login_connect.php');
	if ($_SESSION['tier'] < 3) {
		$pagetitle = "Player Database (Under Construction)";
		//include_once('modules/bans_connect.php');
		//include_once('rcon.php');
		//global $DayZ_Servers;
		
		// Log access to page
		$query = $dbhandle2->prepare("INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('Player DB',?,NOW())");
		$query->execute(array($_SESSION['login']));
		
?>
	<script src="js/playerdb.js" type="text/javascript"></script>
	
	<div id="page-heading">
<?php
	echo "<title>".$pagetitle." - ".$sitename."</title>";
	echo "<h1>".$pagetitle."</h1>";
?>
	</div>
	
	<!-- begin search form -->
	<form action="" onClick="return false;" method="post" id="left_margin_50">
		<table border="0" cellpadding="0" cellspacing="0">
		<tr>
		<td><input id="search_box" name="search" type="text" value="Search" onblur="if (this.value=='') { this.value='Search'; }" onfocus="if (this.value=='Search') { this.value=''; }" class="top-search-inp" /></td>
		<td>
		<select id="search_type" name="type" class="styledselect">
			<option value="guid">GUID</option>
			<option value="known_names">Known Names</option>
			<option value="known_ips">Known IPs</option>
		</select> 
		</td>
		<td>
		<input type="submit" id="search_btn" class="submit-login"  />
		</td>
		<td id="searching_for"></td>
		</tr>
		</table>
	</form>
	<!-- end search form -->
	
	<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
		<tr>
			<th rowspan="3" class="sized"></th>
			<th class="topleft"></th>
			<td id="tbl-border-top">&nbsp;</td>
			<th class="topright"></th>
			<th rowspan="3" class="sized"></th>
		</tr>
		<tr>
			<td id="tbl-border-left"></td>
			<td>
			<!--  start content-table-inner ...................................................................... START -->
			<div id="content-table-inner">
				<!--
				<div id="related-activities">
					<div id="related-act-top">
						<img width="271" height="43" alt="" src="images/forms/header_related_act.gif">
					</div>
					<div id="related-act-bottom">
						<div id="related-act-inner">
							<div class="left"><a href="#" onclick="ShowModalPopup('dvPopup'); return false;">
								<img width="21" height="21" alt="" src="images/forms/icon_plus.gif"></a>
							</div>
							<div class="right">
								<h5><a href="#" onclick="ShowModalPopup('dvPopup'); return false;">Add Ban</a></h5>
								Add new Ban
							</div>
							<div class="clear"></div>
						</div>
					</div>
				</div>
				-->
				<!--  start table-content  -->
				<div id="table-content">
					<table border="0" cellpadding="0" cellspacing="0" id="product-table">
					<thead>
						<tr>
							<th class="table-header-repeat line-left" width="21%"><a href="">GUID</a></th>
							<th class="table-header-repeat line-left minwidth-1" width="26%"><a href="">Known Names</a></th>
							<th class="table-header-repeat line-left" width="25%"><a href="">Known IPs</a></th>
							<th class="table-header-repeat line-left minwidth-1" width="8%"><a href="">Last Seen</a></th>
							<th class="table-header-repeat line-left" width="8%"><a href="">First Seen</a></th>
							<th class="table-header-repeat line-left" width="12%"><a href="">Server</a></th>
						</tr>
					</thead>
						<!-- table rows begin -->
					<tbody id="paged_table_rows"></tbody>
						<!-- table rows end -->
					</table>
				</div>
				
				<!--  end table-content  -->
				
				<!-- Pagination links go here? -->
				
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
	<div class="clear">&nbsp;</div>

<?php
	} else {
		echo '
		<div id="page-heading">
			<h1>Access Denied</h1>
		</div>';
	}
} else {
	header('Location: admin.php');
}
?>