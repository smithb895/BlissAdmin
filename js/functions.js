/*
 * -={AWG}=- HIVE Admin
 * Author: Anzu
 * Description: Misc functions for use with HIVE
 * Admin panel.
 * Requires jQuery
*/

function loading_show() {
	//$('#loading').html("<img src='images/loading.gif'/>").fadeIn('fast');
}

function loading_hide() {
	//$('#loading').fadeOut();
}

function importBans() {
	//$('#dvPopup2_content').load('bans/import_bans.php');
	$("#loading_msg").html('Importing new bans from servers.');
	$("#loading_div").slideDown('fast').show();
	$("#loadingicon").show();
	$.ajax ({
		type: "GET",
		url: 'bans/import_bans.php',
		success: function(response) {
				$("#done_msg span").html(response);
				$("#loadingicon").hide();
				setTimeout(function() {
					$("#loading_div").slideUp('fast');
					$("#done_msg span").html('');
					$("#loading_msg").html('');
				}, 5000);
		}
	});
}

function exportBans() {
	//$('#dvPopup2_content').load('bans/import_bans.php');
	$("#loading_msg").html('Exporting bans DB to live server.');
	$("#loading_div").slideDown('fast').show();
	$("#loadingicon").show();
	$.ajax ({
		type: "GET",
		url: 'bans/export_bans.php',
		success: function(response) {
				$("#done_msg span").html(response);
				$("#loadingicon").hide();
				setTimeout(function() {
					$("#loading_div").slideUp('fast');
					$("#done_msg span").html('');
					$("#loading_msg").html('');
				}, 5000);
		}
	});
}

function showAddBan() {
	//$("#add_ban_div").slideDown('fast').show();
}

function addBan() {
	var banGUIDIP = $("input[name=addban]").val();
	var banLENGTH = $("select[name=banlength]").val();
	var banREASON = $("input[name=reason]").val();
	
	if (banGUIDIP.length < 5) {
		alert('GUID/IP is too short');
	} else if (banGUIDIP.length > 32) {
		alert('GUID/IP is too long.  Max 32chars');
	} else if (banREASON.length > 64) {
		alert('Ban reason is too long.  Max 64chars');
	} else if (banGUIDIP.search(/^[a-f0-9\.]+$/) != 0) {
		alert('Invalid character(s) in GUID/IP');
	} else if (banREASON.search(/^[a-zA-Z0-9 \.,\-!\?\(\)\[\]\{\}\+/]+$/) != 0) {
		alert('Invalid character(s) in ban reason');
	} else {
		var postData = 'addban='+banGUIDIP+'&banlength='+banLENGTH+'&reason='+banREASON;
		$.ajax ({
			type: "POST",
			url: 'modules/getbans.php',
			data: postData,
			success: function(response) {
					//$("#paged_table_rows").html(response);
					$("#popup_msg").html(response);
					//fetchDBRows('bans','none','none',1);
					$("#popup_msg").slideDown('fast').show();
					//setTimeout(function() {
					//	$("#popup_msg").slideUp('fast');
					//}, 4000);
					//alert(banREASON);
			}
		});
	}
}

function unBan() {
	var toUnban = [];
	$("input[name='delban[]']:checked").each(function() {
		toUnban.push($(this).val());
	});
	$('html, body').animate({ scrollTop: 0 }, 'fast');
	if (toUnban.length > 0) {
		$.ajax ({
			type: "POST",
			url: 'modules/getbans.php',
			data: { delban : toUnban },
			success: function(response) {
					$("#popup_msg").html(response);
					$("#popup_msg").slideDown('fast').show();
					//setTimeout(function() {
						//fetchDBRows('bans','none','none',1);
						//$("#popup_msg").slideUp('fast');
					//}, 4000);
					
			}
		});
	}
}

function addVIP() {
	var loadoutid = $("select[name=loadoutid]").val();
	var playerid = $("input[name=playerid]").val();
	var postData = 'loadoutid=' + loadoutid + '&playerid=' + playerid;
	$.ajax ({
		type: "POST",
		url: 'modules/addvip.php',
		data: postData,
		success: function(response) {
				$("#add_vip_response").html(response);
		}
	});
}

function searchBans(type,searchTerm) {
	$('#searching_for').html(' Searching for: ' + searchTerm);
	$('#search_box').val(searchTerm);
	$('#search_type').val('guidip');
	var postData = 'type=guidip&search='+searchTerm;
	$.ajax ({
		type: "POST",
		url: 'modules/getbans.php',
		data: postData,
		success: function(response) {
				$("#paged_table_rows").html(response);
		}
	});

}

function fetchDBRows(db,searchType,searchTerm,page) {
	if (searchType == 'none') {
		$('#searching_for').html('');
		var postData = 'page='+page;
	} else {
		$('#searching_for').html(' Searching for: ' + searchTerm);
		$('#search_box').val(searchTerm);
		$('#search_type').val(searchType);
		var postData = 'type='+searchType+'&search='+searchTerm+'&page='+page;
	}
	$.ajax ({
		type: "POST",
		url: 'modules/get'+db+'.php',
		data: postData,
		success: function(response) {
			//$("#container").ajaxComplete(function(event, request, settings) {
				//loading_hide();
				$("#paged_table_rows").html(response);
			//});
		}
	});
}

function getServerInfo(instance_num) {
	$.ajax ({
		type: "GET",
		url: 'modules/getServerInfo.php',
		data: {instance : instance_num},
		success: function(response) {
			//$.data(document.body, 'serverInfo', JSON.parse(response));
			return response;
		}
	});
}
