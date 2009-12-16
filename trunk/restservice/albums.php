<?php
/* **************************************************
/  REST Webservice for GiantDisc
/  created by Andreas Baierl, 25.11.2009
/  
/
/  albums.php - 	get albums infos from db (get)
/					create albums (post)
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
		if ($path_params[2] != null) {
			$rowtitles = get_row_titles("album"); // get rows of gd-table album
			if (in_array($path_params[2],$rowtitles)) {
				render_result(get_album($path_params[1],$path_params[2]),"albums",$type); /* render explicit info of album given by cddbid */
			} else {
				render_result(get_album($path_params[1]),"albums",$type); /* render all infos of album given by cddbid (if wrong info is ordered) */
			}
		} else {
			render_result(get_album($path_params[1]),"albums",$type); /* render all infos of album given by cddbid */
		} 
	} else {
		render_result(get_album(),"albums",$type); /* render all infos of all albums */
	}	
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // POST Request
	$input = file_get_contents("php://input");
	render_result(create_album_record($input),null,$type); /* create album entry */
}

mysql_close($database);
?>


