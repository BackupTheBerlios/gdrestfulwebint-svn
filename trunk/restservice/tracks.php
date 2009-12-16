<?php
/* **************************************************
/  REST Webservice for GiantDisc
/  created by Andreas Baierl, 25.11.2009
/  
/
/  tracks.php - 	get tracks infos from db (get)
/					create tracks (post)
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
		if ($path_params[1] == "artists") {
			if ($path_params[2] != null) {
				render_result(get_artist("tracks",$path_params[2]),"artists",$type);				
			} else {
				render_result(get_artist("tracks"),"artists",$type);
			}
		} else {
			if ($path_params[2] != null) {
				$rowtitles = get_row_titles("tracks"); // get rows of gd-table tracks
				if (in_array($path_params[2],$rowtitles)) {
					render_result(get_track($path_params[1],$path_params[2]),"tracks",$type); /* render explicit info of track given by id */
				} elseif ($path_params[2] == "audio") {
					get_audio_data($path_params[1]); /* deliver binary audio-data of track given by id */
				} else {
					render_result(get_track($path_params[1]),"tracks",$type); /* render all infos of track given by id (if wrong info is ordered) */
				}
			} else {
				render_result(get_track($path_params[1]),"tracks",$type); /* render all infos of track given by id */
			}
		}
	} else {
		render_result(get_track(),"tracks",$type); /* render all infos of all tracks */
	}
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // POST Request
	$input = file_get_contents("php://input");
	render_result(create_track_record($input),null,$type); /* create track entry and copy file */
}


mysql_close($database);
?>