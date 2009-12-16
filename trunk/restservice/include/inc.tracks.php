<?php
/****************/
/* Tracks       */
/* tracks.php   */
/****************/

/****************************************
/* Get Trackinfo:

   get info of track with given $trackid
   if $trackid is "current", use current track
   if $sel is given, select only $sel
   if no argument is given, return all tracks limited to $limit
*/
function get_track($track_id=null,$sel=null,$sourceid=null) {
    global $limit;
	$i = 0;
    $arr = '';
	if ($track_id == "current") {$track_id = get_current_track_id(); }
    if (isset($track_id)) {
		if (isset($sel)) {
			$query = "SELECT $sel FROM tracks WHERE id = $track_id";
		} else {
			$query = "SELECT * FROM tracks WHERE id = $track_id";
		}
	} elseif (isset($sourceid)) {
		$query = "SELECT title,artist,id FROM tracks WHERE sourceid = $sourceid ORDER BY title";
	} else {
		$query = "SELECT title,artist,id FROM tracks ORDER BY title LIMIT $limit";
	}
	$result = mysql_query($query) or die ('Query failed: ' . mysql_error());
	while ($line = mysql_fetch_assoc($result)) {
		foreach ($line as $key => $col_value) {
			if (!isset($track_id) && !isset($sel)) {
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
/* Get Audio File:

   return binary audiodata of given $trackid,
*/
function get_audio_data($track_id) {
    global $basedir;
	if ($track_id == "current") {$track_id = get_current_track_id(); }
    header('Content-Type: audio/mp3');
    $t_artist = get_db_track_info("artist",$track_id);
    $t_title = get_db_track_info("title",$track_id);
    $filename = $t_artist." - ".$t_title.".mp3";
    header("Content-Disposition: attachment; filename=\"".$filename."\"");
    // header("Content-Disposition: inline; filename=\"".$filename."\"");
    $ad_link  = track_relative_path($track_id);
    $audio_file = $basedir.$ad_link;
    $fp = fopen($audio_file , "r");
    $inhalt = fread($fp, filesize($audio_file));
    fclose($fp);
    echo($inhalt);
}

/****************************************
/* Create Track Record:

   create track record and copy file
*/
function create_track_record($input) {
    // $input = str_replace ('&', '&amp;', $input);
	$json = json_decode($input);
	$i = 0;
		foreach ($json->tracks->track as $track) {
			$lasttid = get_lasttnb();
			$trackid = $lasttid["lasttnb"] + 1;
			$mp3file = sprintf("trxx%08d.mp3", $trackid);
				$query = 	"INSERT INTO tracks 
						(artist,
						title,
						composer,
						genre1,
						genre2,
						year,
						lang,
						type,
						rating,
						length,
						source,
						sourceid,
						tracknb,
						quality,
						lyrics,
						moreinfo,
						bitrate,
						created,
						modified,
						mp3file ) 
					VALUES ('".html_entity_decode($track->artist)."',
						'".html_entity_decode($track->title)."',
						'".html_entity_decode($track->composer)."',
						'$track->genre1',
						'$track->genre2',
						'$track->year',
						'$track->lang',
						'$track->type',
						'$track->rating',
						'$track->length',
						'$track->source',
						'$track->sourceid',
						'$track->tracknb',
						'$track->quality',
						'$track->lyrics',
						'$track->moreinfo',
						'$track->bitrate',
						'$track->created',
						'$track->modified',
						'$mp3file' )";
			$result = mysql_query($query) or die('Query failed: ' . mysql_error());
			if ($result != "1") { 
				$res[$i]["created"] = false;
				$res[$i]["file"] = stripslashes(htmlspecialchars_decode($track->filename,ENT_QUOTES));
				$res[$i]["copied"] = false;			
				$i++;
			} else { 
				$res[$i]["created"] = true;
				$res[$i]["file"] = stripslashes(htmlspecialchars_decode($track->filename,ENT_QUOTES));
				$res[$i]["copied"] = copy_track(htmlspecialchars_decode($track->filename,ENT_QUOTES),$mp3file,htmlspecialchars_decode($track->mvdir,ENT_QUOTES));
				$i++;
			}
		}
	return $res;
}



?>