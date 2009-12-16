<?php
/* **************************************************
/  REST Webservice for GiantDisc
/  created by Andreas Baierl, 25.11.2009
/  
/
/  artists.php - 	get artists from db (get)
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
	set_headers($type);
	if ($path_params[1] != null) {
		if (($path_params[1] == "tracks") || ($path_params[1] == "album")) {
			if ($path_params[2] != null) {
				render_result(get_artist($path_params[1],$path_params[2]),"artists",$type);
			} else {
				render_result(get_artist($path_params[1]),"artists",$type);
			}
		}
	}
}

mysql_close($database);
?>