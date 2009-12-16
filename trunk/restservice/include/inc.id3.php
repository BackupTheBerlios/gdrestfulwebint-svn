<?php
/************************/
/* ID3-Infos form file  */
/* id3.php              */
/************************/

/****************************************
/* Return ID3-Info:

   Return value of id3-tags ($id3tag) of mp3file with given $trackid or path,
*/
function get_id3($track,$id3tag=null) {
    global $basedir;
	if (is_numeric($track)) { // track_id is given
		$ad_link  = track_relative_path($track);
		$audio_file = $basedir.$ad_link;
	} else { // path is given
		$track = urldecode($track);
		$track = str_replace("___","/",$track);
		$audio_file = $track;
	}
    $getID3 = new getID3;
    $fileinfo = $getID3->analyze($audio_file);
	
	// associate object attributes
	$arr["tag"]["artist"] = html_entity_decode($fileinfo["tags_html"]["id3v2"]["artist"][0],ENT_COMPAT,"UTF-8");		
	$arr["tag"]["album"] = html_entity_decode($fileinfo["tags_html"]["id3v2"]["album"][0],ENT_COMPAT,"UTF-8");		
	$arr["tag"]["title"] = html_entity_decode($fileinfo["tags_html"]["id3v2"]["title"][0],ENT_COMPAT,"UTF-8");		
	$arr["tag"]["composer"] = html_entity_decode($fileinfo["tags_html"]["id3v2"]["composer"][0],ENT_COMPAT,"UTF-8");		
	$arr["tag"]["track_number"] = $fileinfo["tags_html"]["id3v2"]["track_number"][0];		
	$arr["tag"]["year"] = $fileinfo["tags_html"]["id3v2"]["year"][0];		
		$id3genre = $fileinfo["tags_html"]["id3v2"]["content_type"][0];
		$query = "SELECT id FROM genre WHERE genre = \"$id3genre\"";
		$result = mysql_query($query) or die('Query failed: ' . mysql_error());
		$genre = mysql_fetch_assoc($result);
		if (strlen($genre)>0) {
			$genre1 = $genre["id"];
		}
	$arr["tag"]["genre1"] = $genre1;
	$arr["tag"]["genre2"] = $fileinfo["tags_html"]["id3v2"]["content_type"][1];
	$arr["fileformat"] = $fileinfo["fileformat"];
	$arr["filepath"] = $fileinfo["filepath"];
	$arr["filenamepath"] = $fileinfo["filenamepath"];
	$arr["filename"] = $fileinfo["filename"];
	$arr["filesize"] = $fileinfo["filesize"];
	$arr["playtime_seconds"] = $fileinfo["playtime_seconds"];
	$arr["playtime_string"] = $fileinfo["playtime_string"];
	$arr["bitrate"] = $fileinfo["bitrate"];

	if (isset($id3tag)) {
		$info[$id3tag] = $arr[$id3tag];
		return $info;
	} else {
		return $arr;
	}
}
?>