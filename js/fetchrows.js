/*
 * -={AWG}=- HIVE Admin
 * Author: Anzu
 * Description: This script will fetch
 * table rows one page at a time 
 * using AJAX.
 * Requires jQuery
*/

$(document).ready(function() {
	function loading_show() {
		//$('#loading').html("<img src='images/loading.gif'/>").fadeIn('fast');
	}

	function loading_hide() {
		//$('#loading').fadeOut();
	} 

	function loadData(page) {
		//loading_show(); 
		$.ajax ({
			type: "POST",
			url: "modules/getbans.php",
			data: "page="+page,
			success: function(response) {
				//$("#container").ajaxComplete(function(event, request, settings) {
					//loading_hide();
					$("#paged_table_rows").html(response);
				//});
			}
		});
	}
	loadData(1); // For first time page load default results
	$('#paged_table_rows .pagination li.active').live('click',function(){
		var page = $(this).attr('p');
		loadData(page);
	}); 
});

