<?php
/* **************************************************
/  REST Webservice for GiantDisc
/  created by Andreas Baierl, 25.11.2009
/  
/
/  id3.php - 	get id3/file-infos of tracks (get) 
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
		if (($path_params[1] != null) && ($path_params[2] != null) ) {
			render_result(get_id3($path_params[1],$path_params[2]),"id3",$type); /* render explicit id3/file-info of track given by full pathname or id */
		} else if (($path_params[1] != null)) {
			render_result(get_id3($path_params[1]),"id3",$type); /* render all id3/file-infos of track given by full pathname or id (if already imported) */
		}
	}
}

mysql_close($database);
?>