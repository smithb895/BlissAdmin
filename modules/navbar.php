<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);

include_once('config.php');

if (isset($_SESSION['user_id']))
{
?>
<!--  start nav-outer-repeat................................................................................................. START -->
<div class="nav-outer-repeat"> 
<!--  start nav-outer -->
<div class="nav-outer"> 

		<!-- start nav-right -->
		<div id="nav-right">
		
			<div class="nav-divider">&nbsp;</div>
			<!-- <div class="showhide-account"><img src="images/shared/nav/nav_myaccount.gif" width="93" height="14" alt="" /></div>
			<a href="/admin.php?view=register" id="logout"><img src="images/shared/nav/nav_myaccount.gif" width="64" height="14" alt="" /></a>
			<div class="nav-divider">&nbsp;</div>-->
			<a href="admin.php?logout" id="logout"><img src="images/shared/nav/nav_logout.gif" width="64" height="14" alt="" /></a>
			<div class="clear">&nbsp;</div>
		
			<!--  start account-content -->	
			<!--<div class="account-content">
			<div class="account-drop-inner">
				<a href="" id="acc-settings">Settings</a>
				<div class="clear">&nbsp;</div>
				<div class="acc-line">&nbsp;</div>
				<a href="" id="acc-details">Personal details </a>
				<div class="clear">&nbsp;</div>
				<div class="acc-line">&nbsp;</div>
				<a href="" id="acc-project">Project details</a>
				<div class="clear">&nbsp;</div>
				<div class="acc-line">&nbsp;</div>
				<a href="" id="acc-inbox">Inbox</a>
				<div class="clear">&nbsp;</div>
				<div class="acc-line">&nbsp;</div>
				<a href="" id="acc-stats">Statistics</a> 
			</div>
			</div>-->
			<!--  end account-content -->
		
		</div>
		<!-- end nav-right -->


		<!--  start nav -->
		<div class="mc-nav">
			<div class="table">
				<ul class="select menutop level1">
				<li class="li-dashboard root active"><a href="admin.php" style="color:#FFF;" class="dashboard item">Dashboard</a></li>
				<li class="li-users parent root"><span class=" daddy item"><span>Admin</span></span>
					<ul class="level2 parent-users">
						<li class="li-mass-mail-users"><a href="admin.php?view=vip" class="class:massmail item">Manage VIPs</a></li>
						<li class="li-mass-mail-users"><a href="admin.php?view=admin" class="class:massmail item">Manage admins</a></li>
						<li class="li- separator"><span></span></li>
						<li class="li-mass-mail-users"><a href="admin.php?view=database" class="class:massmail item">Database Admin</a></li>
					</ul>
				</li>
				<li class="li-users parent root"><span class=" daddy item"><span>View Inventories</span></span>
					<ul class="level2 parent-users">
					<?php
						foreach ($DayZ_Servers as $server) {
							$_servermap = $server->getServerMap();
							$_serverinstance = $server->getMissionInstance();
							echo '					
					<li class="li-user-manager parent"><a href="#nogo" class="class:user daddy item">ID '.$_serverinstance.' - '.$_servermap.' </a>
						<ul class="level3 parent-user-manager">
							<li class="li-user-manager parent"><a href="#nogo" class="class:user daddy item">Players</a>
								<ul class="level3 parent-user-manager">
									<li class="li-add-new-user"><a href="admin.php?view=table&show=0&instance='.$_serverinstance.'" class="class:newarticle item">Online</a></li>
								</ul>
							</li>
							<li class="li-groups parent"><a href="#nogo" class="class:groups daddy item">Vehicles</a>
								<ul class="level3 parent-groups">
									<li class="li-add-new-group"><a href="admin.php?view=table&show=4&instance='.$_serverinstance.'" class="class:newarticle item">Ingame</a></li>
								</ul>
							</li>
						</ul>';
						}
					?>
					<!--<li class="li-user-manager parent"><a href="#nogo" class="class:user daddy item">Database</a>
						<ul class="level3 parent-user-manager">
						<li class="li-user-manager parent"><a href="#nogo" class="class:user daddy item">Players</a>
							<ul class="level3 parent-user-manager">
								<li class="li-add-new-user"><a href="admin.php?view=table&show=1" class="class:newarticle item">Alive</a></li>
								<li class="li-add-new-user"><a href="admin.php?view=table&show=2" class="class:newarticle item">Dead</a></li>
								<li class="li-add-new-user"><a href="admin.php?view=table&show=3" class="class:newarticle item">All</a></li>
							</ul>
						</li>
						<li class="li-groups parent"><a href="#nogo" class="class:groups daddy item">Vehicles</a>
							<ul class="level3 parent-groups">
							<li class="li-add-new-group"><a href="admin.php?view=table&show=4" class="class:newarticle item">All Vehicles</a></li>
								<li class="li-add-new-group"><a href="admin.php?view=table&show=5" class="class:newarticle item">Spawn locations</a></li>
							</ul>
						</li>
						</ul>
					</li>-->
						<li class="li- separator"><span></span></li>
						<li class="li-mass-mail-users"><a href="admin.php?view=check" class="class:massmail item">Check items</a></li>
						<li class="li- separator"><span></span></li>
						<li class="li-mass-mail-users"><a href="admin.php?view=search" class="class:massmail item">Search</a></li>
					</ul>
				</li>
				<li class="li-dashboard root active"><a href="_gear/index.php" style="color:#FFF;" class="dashboard item">Gear &amp; Pos</a></li>
				
				<!--<li class="li-dashboard root active"><a href="admin.php" style="color:#FFF;" class="dashboard item">Dashboard</a></li>-->
				<?php if ($map == "Chernarus") { ?>
				<li class="li-users parent root"><span class=" daddy item"><span>Chern</span></span>
					<ul class="level2 parent-users">
						<li class="li-user-manager parent"><a href="#nogo" class="class:user daddy item">Instance ID:<?php echo $iid?></a>
							<ul class="level3 parent-user-manager">
								<li class="li-groups parent"><a href="#nogo" class="class:groups daddy item">Recent Players</a>
									<ul class="level3 parent-groups">
										<li class="li-add-new-user"><a href="admin.php?view=map&show=0" class="class:newarticle item">Within 1 Min</a></li>
									</ul>
								</li>
								<li class="li-groups parent"><a href="#nogo" class="class:groups daddy item">Deployables</a>
									<ul class="level3 parent-groups">
										<li class="li-add-new-group"><a href="admin.php?view=map&show=4" class="class:newarticle item">Vehicles Ingame</a></li>
										<li class="li-add-new-group"><a href="admin.php?view=map&show=6" class="class:newarticle item">All Ingame Tents</a></li>
										<li class="li-add-new-group"><a href="admin.php?view=map&show=7" class="class:newarticle item">Other Deployables</a></li>
									</ul>
									<li class="li-add-new-user"><a href="admin.php?view=map&show=8" class="class:newarticle item">Everything</a></li>
								</li>
							</ul>
						</li>
						<!--
						<li class="li-user-manager parent"><a href="#nogo" class="class:user daddy item">Database</a>
							<ul class="level3 parent-user-manager">
							<li class="li-groups parent"><a href="#nogo" class="class:groups daddy item">Players</a>
							<ul class="level3 parent-groups">
								<li class="li-add-new-user"><a href="admin.php?view=map&show=1" class="class:newarticle item">Alive</a></li>
								<li class="li-add-new-user"><a href="admin.php?view=map&show=2" class="class:newarticle item">Dead</a></li>
								<li class="li-add-new-user"><a href="admin.php?view=map&show=3" class="class:newarticle item">All</a></li>
							</ul>
							<li class="li-groups parent"><a href="#nogo" class="class:groups daddy item">Deployables</a>
							<ul class="level3 parent-groups">
								<li class="li-add-new-group"><a href="admin.php?view=map&show=5" class="class:newarticle item">Spawn locations</a></li>
							
							</ul>
						</li>
						</ul>-->
					</ul>
				</li>
				<?php } else { ?>
				<li class="li-users parent root"><span class=" daddy item"><span>Maps</span></span>
					<ul class="level2 parent-users">
					<?php
						foreach ($DayZ_Servers as $server) {
							$_servermap = $server->getServerMap();
							$_serverinstance = $server->getMissionInstance();
							echo '
								<li class="li-user-manager parent"><a href="#nogo" class="class:user daddy item">ID '.$_serverinstance.' - '.$_servermap.'</a>
									<ul class="level3 parent-user-manager">
										<li class="li-groups parent"><a href="#nogo" class="class:groups daddy item">Recent Players</a>
										<ul class="level3 parent-groups">
											<li class="li-add-new-user"><a href="admin.php?view='.strtolower($_servermap).'map&show=0&instance_id='.$_serverinstance.'" class="class:newarticle item">Within 2 Min</a></li>
										</ul>
										</li>
										<li class="li-groups parent"><a href="#nogo" class="class:groups daddy item">Objects</a>
											<ul class="level3 parent-groups">
												<li class="li-add-new-group"><a href="admin.php?view='.strtolower($_servermap).'map&show=4" class="class:newarticle item">Vehicles Ingame</a></li>
												<li class="li-add-new-group"><a href="admin.php?view='.strtolower($_servermap).'map&show=6" class="class:newarticle item">All Ingame Tents</a></li>
												<li class="li-add-new-group"><a href="admin.php?view='.strtolower($_servermap).'map&show=7" class="class:newarticle item">Other Deployables</a></li>
											</ul>
											<li class="li-add-new-user"><a href="admin.php?view='.strtolower($_servermap).'map&show=8" class="class:newarticle item">Everything</a></li>
										</li>
									</ul>
								</li>
							
							';
						}
					?>
					<!--
						<li class="li-user-manager parent"><a href="#nogo" class="class:user daddy item">Instance ID:<?php echo $iid?></a>
							<ul class="level3 parent-user-manager">
								<li class="li-groups parent"><a href="#nogo" class="class:groups daddy item">Recent Players</a>
								<ul class="level3 parent-groups">
									<li class="li-add-new-user"><a href="admin.php?view=lingormap&show=0" class="class:newarticle item">Within 1 Min</a></li>
								</ul>					
						<li class="li-groups parent"><a href="#nogo" class="class:groups daddy item">Objects</a>
							<ul class="level3 parent-groups">
								<li class="li-add-new-group"><a href="admin.php?view=lingormap&show=4" class="class:newarticle item">Vehicles Ingame</a></li>
								<li class="li-add-new-group"><a href="admin.php?view=lingormap&show=6" class="class:newarticle item">All Ingame Tents</a></li>
								<li class="li-add-new-group"><a href="admin.php?view=lingormap&show=7" class="class:newarticle item">Other Deployables</a></li>
							</ul>
							<li class="li-add-new-user"><a href="admin.php?view=lingormap&show=8" class="class:newarticle item">Everything</a></li>
						</li>
							</ul>
						</li>
					-->
						<!--
						<li class="li-user-manager parent"><a href="#nogo" class="class:user daddy item">Database</a>
							<ul class="level3 parent-user-manager">
							<li class="li-groups parent"><a href="#nogo" class="class:groups daddy item">Players</a>
							<ul class="level3 parent-groups">
								<li class="li-add-new-user"><a href="admin.php?view=lingormap&show=1" class="class:newarticle item">Alive</a></li>
								<li class="li-add-new-user"><a href="admin.php?view=lingormap&show=2" class="class:newarticle item">Dead</a></li>
								<li class="li-add-new-user"><a href="admin.php?view=lingormap&show=3" class="class:newarticle item">All</a></li>
							</ul>
							<li class="li-groups parent"><a href="#nogo" class="class:groups daddy item">Objects</a>
							<ul class="level3 parent-groups">
								<li class="li-add-new-group"><a href="admin.php?view=lingormap&show=5" class="class:newarticle item">Spawn locations</a></li>
							
							</ul>
						</li>
						
				</li>-->
				
<?php } ?>
				</ul>
				<li class="li-dashboard root active"><a href="admin.php?view=viewbans" style="color:#FFF;" class="dashboard item">Bans</a></li>
				<li class="li-dashboard root active"><a href="admin.php?view=playerdb" style="color:#FFF;" class="dashboard item">Player DB</a></li>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
		<!--  start nav -->



</div>
<div class="clear"></div>
<!--  start nav-outer -->
</div>
<!--  start nav-outer-repeat................................................... END -->
<?php
}
else
{
	header('Location: admin.php');
}
?>