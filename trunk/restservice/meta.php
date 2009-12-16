<?php
/* **************************************************
/  REST Webservice for GiantDisc
/  created by Andreas Baierl, 25.11.2009
/  
/
/  meta.php - 	get several meta infos from db-tables (get) 
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
		if ($path_params[1] == languages) {
			render_result(get_languages(),"meta",$type); /* render language table */
		}
		if ($path_params[1] == types) {
			render_result(get_types(),"meta",$type); /* render musictype table */
		}
		if ($path_params[1] == rating) {
			render_result(get_rating(),"meta",$type); /* render possible rating values */
		}
		if ($path_params[1] == quality) {
			render_result(get_quality(),"meta",$type); /* render possible quality values */
		}
		if ($path_params[1] == genres) {
			render_result(get_genres(),"meta",$type); /* render genre table */
		}
		if ($path_params[1] == source) {
			render_result(get_source(),"meta",$type); /* render source table */
		}
	}
}

mysql_close($database);
?>