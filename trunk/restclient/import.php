<?php
/*********************************************
/* Import Script for GiantDisc Server
   written by Andreas Baierl in 2009

   Project Homepage: http://sourceforge.net/projects/gdrestfulwebint/
   Help: http://sourceforge.net/apps/mediawiki/gdrestfulwebint/index.php!

*/

require_once "includes/inc.includes.php";   // Include necessary files

build_header("Import");
build_body("Import");

// print the main top menu
print "<div class='browsemenu'>\n";
$sel = (strcmp($_GET['level0'], "albums")==0) ? "" : "in";
print "<div class=\"browsemenuitem".$sel."active\"><a href=\"import.php?level0=albums\">Import Albums</a></div>\n";
$sel = (strcmp($_GET['level0'], "singles")==0) ? "" : "in";
print "<div class=\"browsemenuitem".$sel."active\"><a href=\"import.php?level0=singles\">Import Singles</a></div>\n";
print "</div>\n";
print "<div class='results'>\n";
print "<div class='searchresults'>\n";
/*************************************
   Create tables with ID3-Tags
   Possibility to edit and change track-details
**************************************/

// **************************************************
// Table Single Tracks
// is created, if directory "inbox" (single tracks) are selected
//
if ((isset ($_POST['req'])) && (isset ($_POST['single']))) {

    echo '
    <h3> Add Single Track </h3>
    <form action="'.$_SERVER['PHP_SELF'].'" method="POST">
    <table class="importtable">
	<tr>
		<th>Import</>
		<th>Filename</>
		<th>Artist,	Title, Composer</>
		<th>Genre1, Genre2, Year</>
		<th>Lang, Type, Rating</>
		<th>Length</>
		<th>Source, TrackNB, Quality</>
		<th>Lyrics, MoreInfo</>
		<th>Bitrate, Created, Modified, SourceID</>
	</tr>';
		/* 
		// not used yet:
		<th>SourceID</>
		<th>MP3File</>
		<th>Voladjust</>
		<th>LengthFrm</>
		<th>StartFrm</>
		<th>Bpm</>
		<th>Backup</>
		*/
        for ($i=0;$i<$_POST['len'];$i++) {
		$json = json_decode(id3_info(stripslashes($_POST['file_'.$i]))); // get id3 infos (rest-request)
		// create editable track details
		echo '
	    <tr>
			<td><input type="checkbox" name="imp_'.$i.'" value="'.$i.'" /></td>
			<td><input type="hidden" name="file_'.$i.'" value="'.stripslashes(htmlspecialchars($_POST['file_'.$i])).'"/>'.stripslashes(htmlspecialchars($_POST['file_'.$i])).'</td>
			<td><input type="text" name="artist_'.$i.'" value="'.htmlspecialchars($json->tag->artist).'"/>
			<input type="text" name="title_'.$i.'" value="'.htmlspecialchars($json->tag->title).'"/>
			<input type="text" name="composer_'.$i.'" value="'.htmlspecialchars($json->tag->composer).'" /></td>
			<td><select name="genre1_'.$i.'" size="1">'.get_genres_select_options($json->tag->genre1).'</select>
			<select name="genre2_'.$i.'" size="1">'.get_genres_select_options($json->tag->genre2).'</select>
			<input type="text" name="year_'.$i.'" value="'.$json->tag->year.'"/></td>
			<td><select name="lang_'.$i.'" size="1">'.get_language_select_options("en").'</select>
			<select name="type_'.$i.'" size="1">'.get_type_select_options().'</select>
			<select name="rating_'.$i.'" size="1">'.get_rating_select_options().'</select>
			<td><input type="hidden" name="length_'.$i.'" value="'.round((double)$json->playtime_seconds).'"/>'.round((double)$json->playtime_seconds).'</td>
			<td><select name="source_'.$i.'" size="1">'.get_source_select_options("").'</select>
			<input type="text" name="tracknb_'.$i.'" value="'.$json->tag->track_number.'"/>
			<select name="quality_'.$i.'" size="1">'.get_quality_select_options().'</select></td>
			<td><input type="text" name="lyrics_'.$i.'" />
			<input type="text" name="album_'.$j.'_moreinfo_'.$i.'" /></td>
			<td><input type="hidden" name="bitrate_'.$i.'" value="'.$json->fileformat.' '.round(($json->bitrate)/1000).'"/>'.$json->fileformat.' '.round(($json->bitrate)/1000).', 
			<input type="hidden" name="created_'.$i.'" value="'.date("Y-m-d")." ".date("H:i:s").'"/>'.date("Y-m-d")." ".date("H:i:s").', 
			<input type="hidden" name="modified_'.$i.'" value="'.date("Y-m-d")." ".date("H:i:s").'"/>'.date("Y-m-d")." ".date("H:i:s").', 
		</tr>';
		/*
		// not used yet:
		<td><input type="text" name="voladjust_'.$i.'" value="0"/></td>
		<td><input type="text" name="lengthfrm_'.$i.'" value="0"/></td>
		<td><input type="text" name="startfrm_'.$i.'" value="0"/></td>
		<td><input type="text" name="bpm_'.$i.'" value="0"/></td>
		<td><input type="text" name="sourceid_'.$i.'" /></td>
		<td><input type="text" name="mp3file_'.$i.'" /></td>
		<td><input type="text" name="backup_'.$i.'" /></td>
		*/
    }
    echo '
    </table>
    <input type="hidden" name="singles_length" value="'.$i.'" />
    <p><input type="submit" name="submit" value="Add Tracks" /></p>
    </form>';

}

// ******************************************
// Table albums
// is created, if any album directory (../inbox/albums/...name...) is selected ?
//
if ((isset($_POST['req'])) && (isset($_POST['albumdirs']))) {
    $j=0;
    for ($k=0;$k<$_POST['albumdirs'];$k++) {
		$album_artist = $album_title = $album_composer = null;
		if (isset ($_POST['album_'.$k])) {
		echo '
		<h3> Add Album '.$_POST['album_'.$k].'</h3>
		<form action="'.$_SERVER['PHP_SELF'].'" method="POST">
		Import? <input type="checkbox" name="a_imp_'.$j.'" value="'.$j.'" />
		<table class="importtable">
			<tr>
			<th>Import</>
			<th>Filename</>
			<th>Artist,	Title, Composer</>
			<th>Genre1, Genre2, Year</>
			<th>Lang, Type, Rating</>
			<th>Length</>
			<th>Source, TrackNB, Quality</>
			<th>Lyrics, MoreInfo</>
			<th>Bitrate, Created, Modified, SourceID</>
			</tr>';
			for ($i=0;$i<$_POST['album'.$k.'_len'];$i++) {
				$result = "";
				$json = json_decode(id3_info(stripslashes($_POST['album_'.$k.'_file_'.$i]))); // get id3 infos (rest-request)
				$lasttnb = json_decode(get_lasttnb())->lasttnb; // last used tracknumber from db (rest-request)
				$album_cddbid = $lasttnb + $j + 1;
				// create editable track details
				echo '
				<tr>
				<td><input type="checkbox" checked name="album_'.$j.'_imp_'.$i.'" value="'.$i.'" /></td>
				<td><input type="hidden" name="album_'.$j.'_file_'.$i.'" value="'.stripslashes(htmlspecialchars($_POST['album_'.$k.'_file_'.$i])).'"/>'.stripslashes(htmlspecialchars($_POST['album_'.$k.'_file_'.$i])).'</td>
				<td><input type="text" name="album_'.$j.'_artist_'.$i.'" value="'.htmlspecialchars($json->tag->artist).'"/>
				<input type="text" name="album_'.$j.'_title_'.$i.'" value="'.htmlspecialchars($json->tag->title).'"/>
				<input type="text" name="album_'.$j.'_composer_'.$i.'" value="'.htmlspecialchars($json->tag->composer).'" /></td>
				<td><select name="album_'.$j.'_genre1_'.$i.'" size="1">'.get_genres_select_options($json->tag->genre1).'</select>
				<select name="album_'.$j.'_genre2_'.$i.'" size="1">'.get_genres_select_options($json->tag->genre2).'</select>
				<input type="text" name="album_'.$j.'_year_'.$i.'" value="'.$json->tag->year.'"/></td>
				<td><select name="album_'.$j.'_lang_'.$i.'" size="1">'.get_language_select_options("").'</select>
				<select name="album_'.$j.'_type_'.$i.'" size="1">'.get_type_select_options("").'</select>
				<select name="album_'.$j.'_rating_'.$i.'" size="1">'.get_rating_select_options("").'</select>
				<td><input type="hidden" name="album_'.$j.'_length_'.$i.'" value="'.round((double)$json->playtime_seconds).'"/>'.round((double)$json->playtime_seconds).'</td>
				<td><select name="album_'.$j.'_source_'.$i.'" size="1">'.get_source_select_options("").'</select>
				<input type="text" name="album_'.$j.'_tracknb_'.$i.'" value="'.$json->tag->track_number.'"/>
				<select name="album_'.$j.'_quality_'.$i.'" size="1">'.get_quality_select_options("").'</select></td>
				<td><input type="text" name="album_'.$j.'_lyrics_'.$i.'" />
				<input type="text" name="album_'.$j.'_moreinfo_'.$i.'" /></td>
				<td><input type="hidden" name="album_'.$j.'_bitrate_'.$i.'" value="'.$json->fileformat.' '.round(($json->bitrate)/1000).'"/>'.$json->fileformat.' '.round(($json->bitrate)/1000).', 
				<input type="hidden" name="album_'.$j.'_created_'.$i.'" value="'.date("Y-m-d")." ".date("H:i:s").'"/>'.date("Y-m-d")." ".date("H:i:s").', 
				<input type="hidden" name="album_'.$j.'_modified_'.$i.'" value="'.date("Y-m-d")." ".date("H:i:s").'"/>'.date("Y-m-d")." ".date("H:i:s").', 
				<input type="hidden" name="album_'.$j.'_sourceid_'.$i.'" value="'.sprintf("%08d",$album_cddbid).'" />'.sprintf("%08d",$album_cddbid).'</td>				
				</tr>';
				// Check, if directory contains Various Artists
				if ((isset($album_artist)) && ((string)$album_artist != (string)$json->tag->artist)) {
					$album_artist="Various Artists";
				} else {
					if (!isset($album_artist) && ((string)$json->tag->artist != "")) {
						$album_artist=$json->tag->artist;
					}
				}
				// Use first readable album-entry from id3 of tracks as album-title
				if (!isset($album_title) && ((string)$json->tag->album != "")) {
					$album_title=$json->tag->album;
				}
				// Use first readable composer-entry from id3 of tracks as album-composer
				if (!isset($album_composer) && ((string)$json->tag->composer != "")) {
					$album_composer=$json->tag->composer;
				}
				// Use first readable genre-entry from id3 of tracks as album-genre
				if (("" == $album_genre) && ("" != (string)$json->tag->genre1)) {
					$album_genre=$json->tag->genre1;
				}
				// Use first readable year-entry from id3 of tracks as album-year
				if (("" == $album_year) && ("" != (string)$json->tag->year)) {
					$album_year=$json->tag->year;
				}
			}
		// create editable album details
		echo '
		</table></br></br>
		Artist: <input type="text" name="album_'.$j.'_artist" value="'.htmlspecialchars($album_artist).'" /><input type="checkbox" name="album_'.$j.'_artist_all" value="'.$j.'" />-
		Album: <input type="text" name="album_'.$j.'_title" value="'.htmlspecialchars($album_title).'" />-
		Composer: <input type="text" name="album_'.$j.'_composer" /><input type="checkbox" name="album_'.$j.'_composer_all" value="'.$j.'" />-
		Genre: <select name="album_'.$j.'_genre" size="1">'.get_genres_select_options($album_genre).'</select><input type="checkbox" name="album_'.$j.'_genre_all" value="'.$j.'" /></br>
		Year: <input type="text" name="album_'.$j.'_year" value="'.$album_year.'" /><input type="checkbox" name="album_'.$j.'_year_all" value="'.$j.'" />-
		Language: <select name="album_'.$j.'_lang" size="1">'.get_language_select_options("en").'</select><input type="checkbox" name="album_'.$j.'_lang_all" value="'.$j.'" />-
		Type: <select name="album_'.$j.'_type" size="1">'.get_type_select_options("1").'</select><input type="checkbox" name="album_'.$j.'_type_all" value="'.$j.'" />-
		Rating: <select name="album_'.$j.'_rating" size="1">'.get_rating_select_options().'</select><input type="checkbox" name="album_'.$j.'_rating_all" value="'.$j.'" /></br>
		Source: <select name="album_'.$j.'_source" size="1">'.get_source_select_options().'</select><input type="checkbox" name="album_'.$j.'_source_all" value="'.$j.'" />-
		Quality: <select name="album_'.$j.'_quality" size="1">'.get_quality_select_options().'</select><input type="checkbox" name="album_'.$j.'_quality_all" value="'.$j.'" />-
		Modified: <input type="hidden" name="album_'.$j.'_modified" value="'.date("Y-m-d")." ".date("H:i:s").'" />'.date("Y-m-d")." ".date("H:i:s").', 
		CDDBID: <input type="hidden" name="album_'.$j.'_cddbid" value="'.sprintf("%08d",$album_cddbid).'" />'.sprintf("%08d",$album_cddbid).', 
		Directory: <input type="hidden" name="album_'.$j.'_dir" value="'.stripslashes(htmlspecialchars($_POST['album_'.$k])).'" />'.stripslashes(htmlspecialchars($_POST['album_'.$k])).', 
		<input type="hidden" name="album_'.$j.'_length" value="'.$i.'" />Tracks: '.$i.'
		<hr>';
		$j++;
		}
	}
    echo '<input type="hidden" name="albums" value="'.$j.'" />';
    echo '<p><input type="submit" name="submit" value="Add Albums" /></p>
    </form>';
}


/*****************************************
  Daten als xml per post an RestService schicken 
  und DB-Eintrag erstellen - Datei kopieren
*****************************************/

if (( $_POST['singles_length'] > 0 ) || ( $_POST['albums'] > 0 )) {
    $k=0;
	// Create single tracks (only checked files) - without album association (db-entry & copying)
	// build tracks-xml-string for post-request to rest-webservice
	// execute post-request
	// display success
	//
	if ( $_POST['singles_length'] > 0 ) {
		for ( $i=0;$i<$_POST['singles_length'];$i++ ) {
			if ((isset($_POST['artist_'.$i])) && (isset($_POST['file_'.$i])) && (isset($_POST['imp_'.$i]))) {
				// build tracks-json-string
				$json_str_tracks["tracks"]["track"][$k]["filename"] = stripslashes(htmlspecialchars($_POST['file_'.$i],ENT_QUOTES));
				$json_str_tracks["tracks"]["track"][$k]["artist"] = htmlentities($_POST['artist_'.$i],ENT_COMPAT,"UTF-8");
				$json_str_tracks["tracks"]["track"][$k]["title"] = htmlentities($_POST['title_'.$i],ENT_COMPAT,"UTF-8");
				$json_str_tracks["tracks"]["track"][$k]["composer"] = htmlentities($_POST['composer_'.$i],ENT_COMPAT,"UTF-8");
				$json_str_tracks["tracks"]["track"][$k]["genre1"] = $_POST['genre1_'.$i];
				$json_str_tracks["tracks"]["track"][$k]["genre2"] = $_POST['genre2_'.$i];
				$json_str_tracks["tracks"]["track"][$k]["year"] = $_POST['year_'.$i];
				$json_str_tracks["tracks"]["track"][$k]["lang"] = $_POST['lang_'.$i];
				$json_str_tracks["tracks"]["track"][$k]["type"] = $_POST['type_'.$i];
				$json_str_tracks["tracks"]["track"][$k]["rating"] = $_POST['rating_'.$i];
				$json_str_tracks["tracks"]["track"][$k]["length"] = $_POST['length_'.$i];
				$json_str_tracks["tracks"]["track"][$k]["source"] = $_POST['source_'.$i];
				$json_str_tracks["tracks"]["track"][$k]["sourceid"] = "";
				$json_str_tracks["tracks"]["track"][$k]["tracknb"] = $_POST['tracknb_'.$i];
				$json_str_tracks["tracks"]["track"][$k]["quality"] = $_POST['quality_'.$i];
				$json_str_tracks["tracks"]["track"][$k]["lyrics"] = $_POST['lyrics_'.$i];
				$json_str_tracks["tracks"]["track"][$k]["moreinfo"] = $_POST['moreinfo_'.$i];
				$json_str_tracks["tracks"]["track"][$k]["bitrate"] = $_POST['bitrate_'.$i];
				$json_str_tracks["tracks"]["track"][$k]["created"] = $_POST['created_'.$i];
				$json_str_tracks["tracks"]["track"][$k]["modified"] = $_POST['modified_'.$i];
				$json_str_tracks["tracks"]["track"][$k]["mvdir"] = "Singles";
				$json_tracks = json_encode($json_str_tracks);
				$k++;
			}
		}
		$result_tracks = send_tracks($json_tracks); // execute rest-request
		if (false != $result_tracks) { // handle result
			$res_tracks = json_decode($result_tracks);
			foreach ($res_tracks as $error) {
				if ( $error->created ) {
					echo "Track ".stripslashes(htmlspecialchars($error->file,ENT_QUOTES))." <strong>created</strong></br>";
				} else {
					echo "Track ".stripslashes(htmlspecialchars($error->file,ENT_QUOTES))." <strong>creation failed:</strong></br>";
				}
				if ( $error->copied ) {
					echo "Track ".stripslashes(htmlspecialchars($error->file,ENT_QUOTES))." <strong>copied</strong></br>";
				} else {
					echo "Track ".stripslashes(htmlspecialchars($error->file,ENT_QUOTES))." <strong>copy failed:</strong></br>";
				}
			}
		}
	}
	// Create albums (only checked files/albums)
	// and associated tracks (db-entry & copying)
	// build albums- and tracks-json-string for post-request to rest-webservice
	// execute post-request
	// display success
	//
	if ( $_POST['albums'] > 0 ) {
		for ( $j=0;$j<$_POST['albums'];$j++ ) {
			// check, if track album, title and checkbox are set
			if ((isset($_POST['album_'.$j.'_artist'])) && (isset($_POST['album_'.$j.'_title'])) && (isset($_POST['a_imp_'.$j]))) {
				for ( $i=0;$i<$_POST['album_'.$j.'_length'];$i++ ) {
					if ((isset($_POST['album_'.$j.'_artist_'.$i])) && (isset($_POST['album_'.$j.'_file_'.$i])) && (isset($_POST['album_'.$j.'_imp_'.$i]))) {
					// check if album details should override track details
					// if album detail is checked, override
					// if album detail is not checked, fill up, if track details are empty
					// album details use default settings	
						if (isset($_POST['album_'.$j.'_artist_all'])) {
									$track_artist = $_POST['album_'.$j.'_artist'];
								} elseif (($_POST['album_'.$j.'_artist_'.$i]) == "" ) {
									$track_artist = $_POST['album_'.$j.'_artist'];
								} else {
									$track_artist = $_POST['album_'.$j.'_artist_'.$i];
								}
						if (isset($_POST['album_'.$j.'_composer_all'])) {
									$track_composer = $_POST['album_'.$j.'_composer'];
								} elseif (($_POST['album_'.$j.'_composer_'.$i]) == "" ) {
									$track_composer = $_POST['album_'.$j.'_composer'];
								} else {
									$track_composer = $_POST['album_'.$j.'_composer_'.$i];
								}
						if (isset($_POST['album_'.$j.'_genre_all'])) {
									$track_genre = $_POST['album_'.$j.'_genre'];
								} elseif (($_POST['album_'.$j.'_genre1_'.$i]) == "" ) {
									$track_genre = $_POST['album_'.$j.'_genre'];
								} else {
									$track_genre = $_POST['album_'.$j.'_genre1_'.$i];
								}
						if (isset($_POST['album_'.$j.'_year_all'])) {
									$track_year = $_POST['album_'.$j.'_year'];
								} elseif (($_POST['album_'.$j.'_year_'.$i]) == "" ) {
									$track_year = $_POST['album_'.$j.'_year'];
								} else {
									$track_year = $_POST['album_'.$j.'_year_'.$i];
								}
						if (isset($_POST['album_'.$j.'_lang_all'])) {
									$track_lang = $_POST['album_'.$j.'_lang'];
								} elseif (($_POST['album_'.$j.'_lang_'.$i]) == "" ) {
									$track_lang = $_POST['album_'.$j.'_lang'];
								} else {
									$track_lang = $_POST['album_'.$j.'_lang_'.$i];
								}
						if (isset($_POST['album_'.$j.'_type_all'])) {
									$track_type = $_POST['album_'.$j.'_type'];
								} elseif (($_POST['album_'.$j.'_type_'.$i]) == "" ) {
									$track_type = $_POST['album_'.$j.'_type'];
								} else {
									$track_type = $_POST['album_'.$j.'_type_'.$i];
								}
						if (isset($_POST['album_'.$j.'_rating_all'])) {
									$track_rating = $_POST['album_'.$j.'_rating'];
								} elseif (($_POST['album_'.$j.'_rating_'.$i]) == "" ) {
									$track_rating = $_POST['album_'.$j.'_rating'];
								} else {
									$track_rating = $_POST['album_'.$j.'_rating_'.$i];
								}
						if (isset($_POST['album_'.$j.'_source_all'])) {
									$track_source = $_POST['album_'.$j.'_source'];
								} elseif (($_POST['album_'.$j.'_source_'.$i]) == "" ) {
									$track_source = $_POST['album_'.$j.'_source'];
								} else {
									$track_source = $_POST['album_'.$j.'_source_'.$i];
								}
						if (isset($_POST['album_'.$j.'_quality_all'])) {
									$track_quality = $_POST['album_'.$j.'_quality'];
								} elseif (($_POST['album_'.$j.'_quality_'.$i]) == "" ) {
									$track_quality = $_POST['album_'.$j.'_quality'];
								} else {
									$track_quality = $_POST['album_'.$j.'_quality_'.$i];
								}
					// build tracks-json-string
						$json_str_tracks["tracks"]["track"][$k]["filename"] = htmlspecialchars($_POST['album_'.$j.'_file_'.$i],ENT_QUOTES);
						$json_str_tracks["tracks"]["track"][$k]["artist"] = htmlentities($track_artist,ENT_COMPAT,"UTF-8");
						$json_str_tracks["tracks"]["track"][$k]["title"] = htmlentities($_POST['album_'.$j.'_title_'.$i],ENT_COMPAT,"UTF-8");
						$json_str_tracks["tracks"]["track"][$k]["composer"] = htmlentities($track_composer,ENT_COMPAT,"UTF-8");
						$json_str_tracks["tracks"]["track"][$k]["genre1"] = $track_genre;
						$json_str_tracks["tracks"]["track"][$k]["genre2"] = $_POST['album_'.$j.'_genre2_'.$i];
						$json_str_tracks["tracks"]["track"][$k]["year"] = $track_year;
						$json_str_tracks["tracks"]["track"][$k]["lang"] = $track_lang;
						$json_str_tracks["tracks"]["track"][$k]["type"] = $track_type;
						$json_str_tracks["tracks"]["track"][$k]["rating"] = $track_rating;
						$json_str_tracks["tracks"]["track"][$k]["length"] = $_POST['album_'.$j.'_length_'.$i];
						$json_str_tracks["tracks"]["track"][$k]["source"] = $track_source;
						$json_str_tracks["tracks"]["track"][$k]["sourceid"] = $_POST['album_'.$j.'_sourceid_'.$i];
						$json_str_tracks["tracks"]["track"][$k]["tracknb"] = $_POST['album_'.$j.'_tracknb_'.$i];
						$json_str_tracks["tracks"]["track"][$k]["quality"] = $track_quality;
						$json_str_tracks["tracks"]["track"][$k]["lyrics"] = $_POST['album_'.$j.'_lyrics_'.$i];
						$json_str_tracks["tracks"]["track"][$k]["moreinfo"] = $_POST['album_'.$j.'_moreinfo_'.$i];
						$json_str_tracks["tracks"]["track"][$k]["bitrate"] = $_POST['album_'.$j.'_bitrate_'.$i];
						$json_str_tracks["tracks"]["track"][$k]["created"] = $_POST['album_'.$j.'_created_'.$i];
						$json_str_tracks["tracks"]["track"][$k]["modified"] = $_POST['album_'.$j.'_modified_'.$i];
						$json_str_tracks["tracks"]["track"][$k]["mvdir"] = (htmlentities($_POST['album_'.$j.'_artist'],ENT_COMPAT,"UTF-8"))." - ".(htmlentities($_POST['album_'.$j.'_title'],ENT_COMPAT,"UTF-8"));
						$json_tracks = json_encode($json_str_tracks);
						$k++;
					}
				}
			// build albums-json-string
				$json_str_album["albums"]["album"][$j]["artist"] = htmlentities($_POST['album_'.$j.'_artist'],ENT_COMPAT,"UTF-8");
				$json_str_album["albums"]["album"][$j]["title"] = htmlentities($_POST['album_'.$j.'_title'],ENT_COMPAT,"UTF-8");
				$json_str_album["albums"]["album"][$j]["cddbid"] = $_POST['album_'.$j.'_cddbid'];
				$json_str_album["albums"]["album"][$j]["composer"] = htmlentities($_POST['album_'.$j.'_composer'],ENT_COMPAT,"UTF-8");
				$json_str_album["albums"]["album"][$j]["modified"] = $_POST['album_'.$j.'_modified'];
				$json_str_album["albums"]["album"][$j]["genre"] = $_POST['album_'.$j.'_genre'];
				$json_str_album["albums"]["album"][$j]["dir"] = htmlspecialchars($_POST['album_'.$j.'_dir']);
				$json_albums = json_encode($json_str_album);
			}
		}
		$result_albums = send_albums($json_albums); // execute rest-request albums
		if (false != $result_albums) { // handle result albums
			$res_albums = json_decode($result_albums);
			foreach ($res_albums as $error) {
				if ( $error->created ) {
					echo "Album ".$error->dir." <strong>created</strong></br>";
				} else {
					echo "Album ".$error->dir." <strong>creation failed:</strong></br>";
				}
			}   
		}
		$result_tracks = send_tracks($json_tracks); // execute rest-request tracks
		if (false != $result_tracks) { // handle result tracks
			$res_tracks = json_decode($result_tracks);
			foreach ($res_tracks as $error) {
				if ($error->created) {
					echo "Track ".$error->file." <strong>created</strong></br>";
				} else {
					echo "Track ".$error->file." <strong>creation failed:</strong></br>";
				}
				if ($error->copied) {
					echo "Track ".$error->file." <strong>copied</strong></br>";
				} else {
					echo "Track ".$error->file." <strong>copy failed</strong></br>";
				}
			}
		}
	}
}


/************************************
 List Files in inbox and 
 inbox/albums/..name.. directories
************************************/
	echo '<form action="'.$_SERVER['PHP_SELF'].'" method="POST">';
	$inbox = json_decode(get_inbox());  // get inbox directories and files from rest-webservice as json
	if ($_GET["level0"] == "albums") {
	// scan album dirs (albums) and list files
		if ($inbox->albums->length > 0) {
			echo "<h4> Choose Albums to import: </h4>";
			for ($j=0;$j<$inbox->albums->length;$j++) {
				if ($inbox->albums->$j->length > 0) {
					echo "<strong>".htmlspecialchars($inbox->albums->$j->name)."</strong> (";
					echo htmlspecialchars($inbox->albums->$j->dir)."):  ";
					echo '<input type="checkbox" name="album_'.$j.'" value="'.htmlspecialchars($inbox->albums->$j->dir).'" /></br>';
					echo "\n";
					echo '<input type="hidden" name="album_'.$j.'_name" value="'.htmlspecialchars($inbox->albums->$j->name).'" />';
					echo "\n";
					for ($i=0;$i<$inbox->albums->$j->length;$i++) {
						echo '<input type="hidden" name="album_'.$j.'_file_'.$i.'" value="'.htmlspecialchars($inbox->albums->$j->tracks[$i]).'" />';
						echo "\n";
					}
					echo '<input type="hidden" name="album'.$j.'_len" value="'.$i.'"/>';
					echo "\n";
				}
			}
			echo '<input type="hidden" name="albumdirs" value="'.$j.'"/>';
			echo "\n";
		}
	}

	if ($_GET["level0"] == "singles") {
	// scan inbox dir (singles) and list directories
		if ($inbox->singles->length > 0) {
			echo "<h4> Choose Singles to import: </h4>";
			echo $inbox->singles->dir;
			echo '<input type="checkbox" name="single" value="'.$inbox->singles->dir.'" /></br>';
			echo "\n";
			for ($i=0;$i<$inbox->singles->length;$i++) {
				// echo 'file_'.$i.':    ';
				echo '----->   '.htmlspecialchars($inbox->singles->$i);
				echo '<input type="hidden" name="file_'.$i.'" value="'.htmlspecialchars($inbox->singles->$i).'" />';
				echo '</br>';
				echo "\n";
			}
			echo '<input type="hidden" name="len" value="'.$i.'"/>';
			echo "\n";
		}
	}

	if (isset($_GET["level0"])) {
		echo '<p><input type="hidden" name="req" /></p>
		<p><input type="submit" name="submit" value="Load Files" /></p>
		</form>';
	}
	print "</div>\n";
	print "</div>\n";
build_footer();
?>