/*
 * -={AWG}=- HIVE Admin
 * Author: Anzu
 * Description: This script will fetch
 * table rows one page at a time
 * using AJAX.
 * Requires jQuery
*/

$(document).ready(function() {
/*
	function loadData(page) {
		//loading_show();
		var searching = $('#searching_for').html();
		if (searching.length > 2) {
			//var fetchPage = 'search';
			var searchTerm = $('#search_box').val();
			var searchType = $('#search_type').val();
			var postData = 'type='+searchType+'&search='+searchTerm+'&page='+page;
		} else {
			//var fetchPage = 'get';
			var postData = 'page='+page;
		}
		$.ajax ({
			type: "POST",
			url: 'modules/getbans.php',
			data: postData,
			success: function(response) {
				//$("#container").ajaxComplete(function(event, request, settings) {
					//loading_hide();
					$("#paged_table_rows").html(response);
				//});
			}
		});
	}
*/
	//loadData(1); // For first time page load default results
	fetchDBRows('bans','none','none',1);
	importBans();
	//ShowModalPopup('dvPopup2');
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
		//loadData(page);
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

