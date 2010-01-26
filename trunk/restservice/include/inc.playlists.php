<?php
/****************/
/* Playlists       */
/* playlists.php   */
/****************/

/****************************************
/* Get Playlistinfo:

   get info of playlist with given $playlist_id
   if $playlist_id is "current", use current tracklist
   if $sel is given, select only $sel
   if no argument is given, return all playlistss limited to $limit
*/
function get_playlist($playlist_id=null,$sel=null) {
    global $limit;
	$i = 0;
    $arr = '';
	if ($playlist_id == "current") {
		$playlist_id = get_current_source_id();
	} else {
		if (isset($playlist_id)) {
			if (isset($sel)) {
				$query = "SELECT $sel FROM playlist WHERE id = \"$playlist_id\"";
			} else {
				$query = "SELECT * FROM playlist WHERE id = \"$playlist_id\"";
			}
		} else {
			$query = "SELECT id FROM playlist ORDER BY title LIMIT $limit";
		}
	}
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	while ($line = mysql_fetch_assoc($result)) {
		foreach ($line as $key => $col_value) {
			if (!isset($album_id) && !isset($sel)) {
				$arr[$i][$key] = utf8_encode($col_value);
			} else {
				$arr[$key] = utf8_encode($col_value); 
			}
		}
		$i++;
	}
	return $arr;
}

/****************************************
/* Get Tracklist:

   get tracks of playlist with given $playlist_id
   if $sel is given, select only $sel
   if no argument is given, return all current tracklist
*/


function get_tracklist($playlist_id=null) {
    global $limit;
	$i = 0;
    $arr = '';
	if (isset($playlist_id)) {
		if (isset($sel)) {
			$query = "SELECT $sel FROM playlistitem WHERE playlist = \"$playlist_id\" ORDER BY tracknumber";
		} else {
			$query = "SELECT tracknumber,trackid FROM playlistitem WHERE playlist = \"$playlist_id\" ORDER BY tracknumber";
		}
	} else {
		$query = "SELECT tracknb,trackid FROM tracklistitem ORDER BY tracknb";
	}
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	while ($line = mysql_fetch_assoc($result)) {
		foreach ($line as $key => $col_value) {
			if (!isset($album_id) && !isset($sel)) {
				$arr[$i][$key] = utf8_encode($col_value);
			} else {
				$arr[$key] = utf8_encode($col_value); 
			}
		}
		$i++;
	}
	return $arr;
}

?>