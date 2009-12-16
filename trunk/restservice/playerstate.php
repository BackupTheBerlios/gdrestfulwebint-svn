<?php
/* **************************************************
/  REST Webservice for GiantDisc
/  created by Andreas Baierl, 25.11.2009
/  
/
/  playerstate.php - 	get playerstate infos from db (get) 
/ 
/
/* *************************************************/

require_once "include/includes.php";

$database = init_database();

if (isset($_GET['type'])) {
	$type = $_GET['type'];
}

// Check for the path elements
$path = $_SERVER[PATH_INFO];
if ($path != null) {
    $path_params = spliti("/", $path);
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') { // GET Request
	if ($path_params[1] != null) {
		$rowtitles = get_row_titles("playerstate"); // get rows of gd-table playerstate
		if (in_array($path_params[1],$rowtitles)) {
			render_result(get_playerstate($path_params[1]),"playerstate",$type); /* render explicit info of playerstate */
		} else {
			render_result(get_playerstate(),"playerstate",$type); /* render all infos of playerstate (wrong explicit info given) */
		}
	} else {
		render_result(get_playerstate(),"playerstate",$type); /* render all infos of playerstate */
	}
} 

mysql_close($database);
?>