/*
 * -={AWG}=- HIVE Admin
 * Author: Anzu
 * Description: This script will fetch the
 * server log and player list and insert 
 * the data into the appropriate sections 
 * on the page.
 * Requires jQuery
*/

var log_update_interval = 15000;
var log_timer = 0;
var playerlist_update_interval = 120000;
var playerlist_timer = 0;

function fetch_log() {
	$("#server-console").load("modules/fetchlog.php", 
		function() {
			$("#server-console")[0].scrollTop = 999999;
		});
	return false;
}
function fetch_player_list(_selectserver) {
	//$('#player-data-rows').load("modules/current_players.php", "selectserver="+selectserver);
	$("#loadingicon").css('visibility','visible');
	var postdata = 'selectserver='+_selectserver;
	//var serverName = $("#selectserver").attr('servername');
	//var serverMap = $("#selectserver").attr('servermap');
	//alert(serverName);
	//alert(serverMap);
	//$("#servername").html(serverName);
	//$("#servermap").html(serverMap);
	var serverInfo = getServerInfo(_selectserver);
	//alert(serverInfo);
	
	$.ajax({
		type: "POST",
		url: "modules/current_players.php",
		data: postdata,
		timeout: 30000,
		success: function(response) {
			$('#player-data-rows').html(response);
			$("#loadingicon").css('visibility','hidden');
		},
		error: function() {
			$('#player-data-rows').html("<br /><center><b>ERROR: Timeout fetching player list</b></center>");
			$("#loadingicon").css('visibility','hidden');
		}
	});
	
	/*
	$.get("modules/current_players.php", { selectserver: _selectserver }, function(data) {
		$('#player-data-rows').html(data);
	});
	*/
	/*$.ajax({
		type: "GET",
		url: "modules/current_players.php",
		success: function(response) {
			$(#"player-data-rows").html(response);
		}
	});*/
	return false;
}


$(document).ready(function() {
	var selectedserver = $("#selectserver").val();
	//getServerInfo(selectedserver);
	//var serverInfo = $.data(document.body, 'serverInfo');
	//var serverName = serverInfoJSON[0];
	//alert(serverInfoJSON);
	//fetch_log();
	fetch_player_list(selectedserver);
	playerlist_timer = setInterval(fetch_player_list(selectedserver),playerlist_update_interval);
	//log_timer = setInterval("fetch_log()",log_update_interval);
	$("#submit-chat").click(function() {
		var _msg = $("#chatbox").val();
		var postdata = '&say=' + _msg;
		if ((_msg.length > 0) && (_msg.length < 255)) {
			$.ajax({
				type: "POST",
				url: "admin.php?view=actions",
				data: postdata,
				success: function(response) {
					alert('Message sent');
					$("#chatbox").val('');
					//fetch_log();
				}
			});
		} else {
			alert('Chat message is too short or too long (max=255 chars)');
		}
		return false;
	});
	$("#selectserver").change(function() {
		var selectedserver = $("#selectserver").val();
		
		fetch_player_list(selectedserver);
		playerlist_timer = setInterval(fetch_player_list(selectedserver),playerlist_update_interval);
	});
});
