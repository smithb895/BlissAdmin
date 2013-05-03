<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);
if (isset($_SESSION['login'])) {
	include_once('config.php');
	//include_once('hive_connect.php');
	include_once('modules/login_connect.php');
	if ($_SESSION['tier'] < 5) {
		if (isset($_GET['banlist'])) {
			$banlist = preg_replace('#[^0-9]#', '', $_GET['banlist']);
		} else {
			$banlist = 0;
		}
		$banListName = $banlistnames[$banlist];
		$pagename = "Manage Bans";
		$pagetitle = "Manage Bans - <span id='banlist' banlistid='".$banlist."'>$banListName</span> (Under Construction)";
		//include_once('modules/bans_connect.php');
		//include_once('rcon.php');
		//global $DayZ_Servers;
		
		// Log access to page
		$query = $dbhandle2->prepare("INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES ('Manage BANS',?,NOW())");
		$query->execute(array($_SESSION['login']));
		/*
		// If deleting a ban..
		if (isset($_POST['delban'])) {
			$bansRemoved = 0;
			$bansToDelete = preg_replace('#[^0-9+]#', '', $_POST['delban']); // array
			for ($i=0; $i< count($bansToDelete); $i++) {
				// Get GUID or IP for ban to be removed
				$queryBan = $dbhandle3->prepare('SELECT `GUID_IP` FROM `bans` WHERE `ID`=?');
				$queryBan->execute(array($bansToDelete[$i]));
				$banGUID_IP = $queryBan->fetchColumn();
				
				// Log ban removal
				$queryLog = $dbhandle2->prepare("INSERT INTO `logs`(`action`, `user`, `timestamp`) VALUES (?,?,NOW())");
				$queryLog->execute(array('UNBAN: '.$banGUID_IP,$_SESSION['login']));
				
				// Remove ban
				$queryDelBan = $dbhandle3->prepare('UPDATE `bans` SET `active`=0 WHERE `ID`=?');
				$queryDelBan->execute(array($bansToDelete[$i]));
				$bansRemoved += $queryDelBan->rowCount();
				
			}
			echo "Successfully removed $bansRemoved bans.<br />";
		}
		*/
		
?>
	<script src="js/functions.js" type="text/javascript"></script>
	<script src="js/bansdb.js" type="text/javascript"></script>
	
	<!--<div id="dvPopup" style="display:none; width:900px; height: 450px; border:4px solid #000000; background-color:#FFFFFF;">
					<a id="closebutton" style="float:right;" href="#" onclick="HideModalPopup('dvPopup'); return false;"><img src="images/table/action_delete.gif" alt="" /></a><br />Not working yet
					<?php //include ('modules/addban.php'); ?>
	</div>
	
	<div id="dvPopup2" style="display:none; width:900px; height: 450px; border:4px solid #000000; background-color:#FFFFFF;">
					<a id="closebutton" style="float:right;" href="#" onclick="HideModalPopup('dvPopup2');"><img src="images/table/action_delete.gif" alt="" /></a><br />
					<span id='dvPopup2_content'></span>
	</div>-->
	
	<div id="page-heading">
<?php
	echo "<title>".$pagename." - ".$sitename."</title>";
	echo "<h1>".$pagetitle."</h1>";
?>
	</div>
	
	<!-- begin search form -->
	<div id="search_db_div">
		<form action="" onClick="return false;" method="post" id="left_margin_50">
			<input id="search_box" name="search" type="text" value="Search" onblur="if (this.value=='') { this.value='Search'; }" onfocus="if (this.value=='Search') { this.value=''; }" class="top-search-inp" />
			<select id="search_type" name="type" class="styledselect">
				<option value="guidip">GUID/IP</option>
				<option value="known_names">Known Names</option>
				<option value="admin">Admin</option>
				<option value="reason">Reason</option>
			</select>
			<input type="submit" id="search_btn" class="submit-login" />
			<span id="searching_for"></span>
		</form>
	</div>
	<!-- end search form -->
	<span id="notice_msg">Don&rsquo;t forget to click <b><i>Commit Changes</i></b> after adding or removing bans!</span>
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
				
				<div id="related-activities">
					<div id="related-act-top">
						<img width="271" height="43" alt="" src="images/forms/header_related_act.gif">
					</div>
					<div id="related-act-bottom">
						<!--<div id="related-act-inner">
							<div class="left">
								<img width="21" height="21" alt="" src="images/forms/icon_plus.gif">
							</div>
							<div class="right">
								<h5><a href="#" onclick="showAddBan(); return false;">Add Ban</a></h5>
								Add new Ban
							</div>
							<div class="clear"></div>
						</div>-->
						<div id="add_ban_div">
							<h4 class="margin_left_20px">Add Ban</h4>
							<form action="" id="add_ban_form" method="post">
								GUID/IP:<br />
								<input name="addban" type="text" value="Enter GUID/IP" onblur="if (this.value=='') { this.value='Enter GUID/IP'; }" onfocus="if (this.value=='Enter GUID/IP') { this.value=''; }" class="top-search-inp" />
								Length of Ban:<br />
								<select id="length_of_ban" name="banlength" class="styledselect">
									<option value="1">Permament</option>
									<option value="2">10 Minutes</option>
									<option value="3">1 Hour</option>
									<option value="4">1 Day</option>
									<option value="5">1 Week</option>
									<option value="6">1 Month</option>
								</select>
								<input type="submit" id="add_ban_btn" onClick="addBan(); return false;" value="Ban" />
								Reason for Ban:<br />
								<input name="reason" type="text" value="Cheating/Hacking" onblur="if (this.value=='') { this.value='Cheating/Hacking'; }" onfocus="if (this.value=='Cheating/Hacking') { this.value=''; }" class="top-search-inp" /><br />
							</form>
							<div class="clear"></div>
						</div>
						<div id="related-act-inner">
							<div class="left">
								<img width="21" height="21" alt="" src="images/forms/icon_plus.gif">
							</div>
							<div class="right">
								<h5><a href="#" onclick="importBans(); return false;">Update DB</a></h5>
								Import new bans from live servers
							</div>
							<div class="clear"></div>
						</div>
						<div id="related-act-inner">
							<div class="left">
								<img width="21" height="21" alt="" src="images/forms/icon_plus.gif">
							</div>
							<div class="right">
								<h5><a href="#" onclick="exportBans(); return false;">Commit Changes</a></h5>
								Export new bans/unbans from DB to live servers.
							</div>
							<div class="clear"></div>
						</div>
						<div id="popup_msg">
							<!-- Filled by AJAX -->
						</div>
						<div id="loading_div">
							<div id="importing_msg">
								<span id="loading_msg"></span>
								<br />
								Please wait...
								<br />
								<br />
								<center><img id="loadingicon" src="images\icons\gifs\loading_bar.gif"/></center>
							</div>
							<div id="done_msg">
								<span></span>
							</div>
							<div class="clear"></div>
						</div>
					</div>
				</div>
				
				<!--  start table-content  -->
				<div id="table-content">
					<table border="0" width="75%" cellpadding="0" cellspacing="0" id="product-table">
					<thead>
						<tr>
							<th class="table-header-repeat line-left" width="3%"><a href="">Unban</a></th>
							<th class="table-header-repeat line-left" width="9%"><a href="">Ban #</a></th>
							<th class="table-header-repeat line-left minwidth-1" width="25%"><a href="">GUID/IP</a></th>
							<th class="table-header-repeat line-left" width="9%"><a href="">Length</a></th>
							<th class="table-header-repeat line-left minwidth-1" width="35%"><a href="">Reason</a></th>
							<th class="table-header-repeat line-left" width="8%"><a href="">Admin</a></th>
							<th class="table-header-repeat line-left" width="8%"><a href="">Added</a></th>
							<th class="table-header-repeat line-left" width="3%"><a href="">Status</a></th>
						</tr>
					</thead>
						<!-- table rows begin -->
					<tbody id="paged_table_rows"></tbody>
						<!-- table rows end -->
					</table>
					<input type="submit" onClick="unBan(); return false;"  class="submit-login"  />
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