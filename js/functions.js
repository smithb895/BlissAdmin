/*
 * -={AWG}=- HIVE Admin
 * Author: Anzu
 * Description: Misc functions for use with HIVE
 * Admin panel.
 * Requires jQuery
*/


function importBans() {
	$('#dvPopup2_content').load('bans/import_bans.php');
}