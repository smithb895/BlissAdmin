/*
 * -={AWG}=- HIVE Admin
 * Author: Anzu
 * Description: This script will fetch
 * table rows one page at a time
 * using AJAX.
 * Requires jQuery
*/

$(document).ready(function() {
	fetchDBRows('bans','none','none',1);
	importBans();
	$('#paged_table_rows .pagination li.active').live('click',function(){
		var searching = $('#searching_for').html();
		var page = $(this).attr('p');
		if (searching.length > 2) {
			var searchTerm = $('#search_box').val();
			var searchType = $('#search_type').val();
		} else {
			var searchType = 'none';
			var searchTerm = 'none';
		}
		fetchDBRows('bans',searchType,searchTerm,page);
		$('html, body').animate({ scrollTop: 0 }, 'fast');
	});

	$('#search_btn').click(function() {
		var page = 1;
		var searchTerm = $('#search_box').val();
		if (searchTerm.length > 0) {
			var searchType = $('#search_type').val();
		} else {
			var searchType = 'none';
		}
		fetchDBRows('bans',searchType,searchTerm,page);
		//$('#searching_for').html(' Searching for: ' + searchTerm);
	});



});

