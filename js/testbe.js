/*
 * Author: Anzu
 * Starts BE RCON connection and 
 * keeps it alive by sending keepalive 
 * packets every 40 sec.
 * Requires: jQuery
*/

var _seq = 0;
var _timeout = 40000;


function keepalive(_seq) {
	$("#responsebox").load("_testbe.php", "receive=1&seq=" + _seq, function(){ _seq += 1; });
	/*$.get("_testbe.php", { keepalive: "1", seq: _seq },
		function(response){
			$("#responsebox").html(response);
			_seq += 1;
		});*/
}
function login() {
	_seq = 0;
	$("#responsebox").load("_testbe.php", function(){ _seq += 1; });
	/*$.get("_testbe.php",
		function(response){
			$("#responsebox").html(response);
			_seq += 1;
		});*/
}


$(document).ready(function() {
	login();
	setTimeout(function(){ keepalive(_seq); },15000);
	
});
