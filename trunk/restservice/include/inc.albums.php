<?php
/****************/
/* Albums       */
/* albums.php   */
/****************/

/****************************************
/* Get Albuminfo:

   get info of album with given $album_id
   if $album_id is "current", use current album
   if $sel is given, select only $sel
   if no argument is given, return all albums limited to $limit
*/
function get_album($album_id=null,$sel=null) {
    global $limit;
	$i = 0;
    $arr = '';
	if ($album_id == "current") {$album_id = get_current_source_id();}
    if (isset($album_id)) {
		if (isset($sel)) {
			$query = "SELECT $sel FROM album WHERE cddbid = \"$album_id\"";
		} else {
			$query = "SELECT * FROM album WHERE cddbid = \"$album_id\"";
		}
	} else {
		$query = "SELECT * FROM album LIMIT $limit";
	}
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	while ($line = mysql_fetch_assoc($result)) {
		foreach ($line as $key => $col_value) {
			if (!isset($album_id) && !isset($sel)) {
				$arr[$i][$key] = htmlspecialchars($col_value);
			} else {
				$arr[$key] = htmlspecialchars($col_value); 
			}
		}
		$i++;
	}
	return $arr;
}

/****************************************
/* Create Album Record:

   create album record and copy file
*/
function create_album_record($input) {
    // $input = str_replace ('&', '&amp;', $input);
	$json = json_decode($input);
	$i = 0;
	// if (false != ($json)) {
		foreach ($json->albums->album as $album) {
			$query = 	"INSERT INTO album 
					(artist,
					title,
					cddbid,
					composer,
					modified,
					genre ) 
				VALUES ('".html_entity_decode($album->artist)."',
					'".html_entity_decode($album->title)."',
					'$album->cddbid',
					'".html_entity_decode($album->composer)."',
					'$album->modified',
					'$album->genre' )";
			$result = mysql_query($query) or die('Query failed: ' . mysql_error());
			if ($result != "1") { 
				$res[$i]["created"] = false;
				$res[$i]["dir"] = stripslashes(htmlspecialchars_decode($album->dir,ENT_QUOTES));
				$i++;
			} else { 
				$res[$i]["created"] = true;
				$res[$i]["dir"] = stripslashes(htmlspecialchars_decode($album->dir,ENT_QUOTES));
				$i++;
			}
		}
	// }
	return($res);
}
?>