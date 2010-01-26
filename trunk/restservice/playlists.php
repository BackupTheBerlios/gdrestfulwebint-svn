<?php
/* **************************************************
/  REST Webservice for GiantDisc
/  created by Andreas Baierl, 25.11.2009
/  
/
/  playlists.php - 	get playlist infos from db (get)
/					create playlists (post)
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
		if ($path_params[1] == "current") {
			render_result(get_tracklist(),"playlists",$type); /* render current tracklist */
		} elseif ($path_params[2] != null) {
			$rowtitles = get_row_titles("playlist"); // get rows of gd-table playlist
			if (in_array($path_params[2],$rowtitles)) {
				render_result(get_playlist($path_params[1],$path_params[2]),"playlists",$type); /* render explicit info of playlist given by id */
			} elseif ($path_params[2] == "tracks" ) {
				render_result(get_tracklist($path_params[1]),"playlists",$type); /* render tracklist from playlist given by id */
			} else {
				render_result(get_playlist($path_params[1]),"playlists",$type); /* render all infos of playlist given by id (if wrong info is ordered) */
			}
		} else {
			render_result(get_playlist($path_params[1]),"playlists",$type); /* render all infos of playlist given by id */
		} 
	
	} else {
		render_result(get_playlist(),"playlists",$type); /* render list of all playlists */
	}
}
/* not implemented yet
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // POST Request
	$input = file_get_contents("php://input");
	render_result(create_playlist_entry($input),null,$type); /* create playlist entry */
}
*/
mysql_close($database);
?>


