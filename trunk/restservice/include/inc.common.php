<?php

/*********************/
/* Database routines */
/*********************/

// init database (first check settings.php!)
function init_database() {
    global $dbhost, $dbuser, $dbpasswd, $dbname;
    $link = mysql_connect( $dbhost , $dbuser , $dbpasswd ) or die('Could not connect: ' . mysql_error());
    mysql_select_db( $dbname ) or die('Could not select database');
    return $link;
}


/*******************/
/* Render routines */
/*******************/

function set_headers($type="xml") {
	switch ($type) {
		// XML
		case "xml":
			header("Content-Type: text/xml; charset=utf-8");
			break;
		//JSON
		case "json":
			header("Content-Type: application/json; charset=utf-8");
			break;
		//JSON
		case "json_read":
			header("Content-Type: text/plain");
			break;
		//HTML
		case "html":
			header("Content-Type: text/html");		
			break;
		//HTML-table
		case "html_table":
			header("Content-Type: text/html");		
			break;
		//RAW
		case "raw":
			break;
	}
}

function render_result($array, $wrapper="array", $type="json") {
	switch ($type) {
		// XML
		case "xml":
			echo array2xml($array,$wrapper);
			break;
		//JSON
		case "json":
			echo json_encode($array); // php internal function, input is array
			break;
		//JSON - human-readable ( only for debug)
		case "json_read":
			echo make_readable(json_encode($array)); // php internal function, input is array
			break;
		//HTML
		case "html":
			html_show_array($array);
			break;
		//HTML Table
		case "html_table":
			html_table_show_array($array);
			break;
		//RAW
		case "raw":
			print_r($array); // php internal function, input is array (only for debug)
			break;
	}
}

/**************************************
/ Render XML
/ Input is an array
/ *************************************/

function array2xml($array, $tag="array", $subst="nr") {
	return "<$tag>".ia2xml($array,$subst)."</$tag>";
}

function ia2xml($array,$subst) {
	$xml="";
	foreach ($array as $key=>$value) {
		if (is_array($value)) {
			if (is_numeric($key)) {
				$xml.="<".$subst."_".$key.">".(ia2xml($value,$subst))."</".$subst."_".$key.">";
			} else {
				$xml.="<$key>".ia2xml($value,$subst)."</$key>";
			}
		} else {
			/*$value = preg_replace("/&(?!amp;)/","&amp;",$value);
			$value = str_replace("<","&lt;",$value);
			$value = str_replace(">","&gt;",$value);
			$value = str_replace("\"","&quot;",$value);
			$value = str_replace("'","&apos;",$value);
			$value = str_replace("%","&#37;",$value);*/
			$value = htmlspecialchars($value);
			if (is_numeric($key)) {
				$xml.="<".$subst."_".$key.">".$value."</".$subst."_".$key.">" ;
			} else {
				$xml.="<$key>".$value."</$key>" ;
			}
		}
	}
	return $xml;
}

/**************************************
/ Make JSON-String readable
/ Input is a json string
/ *************************************/

function make_readable($json) {
    $result    = '';
    $pos       = 0;
    $strLen    = strlen($json);
    $indentStr = '  ';
    $newLine   = "\n";
    for($i = 0; $i <= $strLen; $i++) {
        // Grab the next character in the string
        $char = substr($json, $i, 1);
        // If this character is the end of an element, 
        // output a new line and indent the next line
        if($char == '}' || $char == ']') {
            $result .= $newLine;
            $pos --;
            for ($j=0; $j<$pos; $j++) {
                $result .= $indentStr;
            }
        }
        // Add the character to the result string
        $result .= $char;
        // If the last character was the beginning of an element, 
        // output a new line and indent the next line
        if ($char == ',' || $char == '{' || $char == '[') {
            $result .= $newLine;
            if ($char == '{' || $char == '[') {
                $pos ++;
            }
            for ($j = 0; $j < $pos; $j++) {
                $result .= $indentStr;
            }
        }
    }
    return $result;
}


/**************************************
/* Render HTML
   Input is an array

*/

function do_offset($level){
    $offset = "";             // offset for subarry 
    for ($i=1; $i<$level;$i++){
    $offset = $offset . "<td></td>";
    }
    return $offset;
}

function show_array($array, $level, $sub){
    if (is_array($array) == 1){          // check if input is an array
       foreach($array as $key_val => $value) {
           $offset = "";
           if (is_array($value) == 1){   // array is multidimensional
           echo "<tr>";
           $offset = do_offset($level);
           echo $offset . "<td>" . $key_val . "</td>";
           show_array($value, $level+1, 1);
           }
           else{                        // (sub)array is not multidim
           if ($sub != 1){          // first entry for subarray
               echo "<tr nosub>";
               $offset = do_offset($level);
           }
           $sub = 0;
           echo $offset . "<td main ".$sub." width=\"120\">" . $key_val . 
               "</td><td width=\"120\">" . $value . "</td>"; 
           echo "</tr>\n";
           }
       } // foreach $array
    }  
    else { // argument $array is not an array
        return;
    }
}

function html_show_array($array){
  echo "<html>";
  echo "<body>";
  echo "<table cellspacing=\"0\" border=\"2\">\n";
  show_array($array, 1, 0);
  echo "</table>\n";
  echo "</html>";
  echo "</body>";
}

function html_table_show_array($array){
  echo "<table cellspacing=\"0\" border=\"2\">\n";
  show_array($array, 1, 0);
  echo "</table>\n";
}


/*******************/
/* helper routines */
/*******************/


/****************************************
/* Return Raw Trackinfo:

   return "$sel" of track with given $trackid
   if $trackid is "current", use current track
*/
function get_db_track_info($sel,$track_id) { // used 
    if ($track_id == "current") {$track_id = get_current_track_id();}
    $query = "SELECT $sel FROM tracks WHERE id = \"$track_id\"";
    $result = mysql_query($query) or die('Query failed: ' . mysql_error());
    $t_info = mysql_fetch_assoc($result);
    return $t_info[$sel];
}


function get_current_track_id() { // used 	// returns the trackid (tracklistitem->trackid, same as tracks->id) of the currently playing (or stopped) track
											// (get linked to the current track)
    $current_playing = get_player_state();
    $tnb = $current_playing["currtracknb"];
    $plid = $current_playing["playerid"];
    $query = "SELECT trackid FROM tracklistitem WHERE tracknb=$tnb AND playerid=$plid";
    $result = mysql_query($query) or die('Query failed: ' . mysql_error());
    $row = mysql_fetch_array($result, MYSQL_ASSOC);
    return $row["trackid"];
}


function get_current_source_id() { // used	// returns the sourceid / cddbid (tracks->sourceid, same as albums->cddbid) of the currently playing (or stopped) track
											// (get linked to the current album)
    $track_id = get_current_track_id();
    $query = "SELECT * FROM tracks WHERE id=$track_id";
    $result = mysql_query($query) or die('Query failed: ' . mysql_error());
    $row = mysql_fetch_array($result, MYSQL_ASSOC);
    return $row["sourceid"];
}


function get_row_titles($table) { // used // returns the keys of the given table as array
    $rowtitles = array();
    $query = "SELECT * FROM $table LIMIT 1";
    $result = mysql_query($query) or die('Query failed: ' . mysql_error());
    $fields = mysql_num_fields($result);
    for($i=0;$i<$fields;$i++) {
		$name = mysql_field_name($result, $i);
		array_push($rowtitles,$name);
    }
    return $rowtitles;
}

function create_hyperlink($filename) { // used  // creates hyperlink to mp3-file
/* returns the relative path+filename for the media file '$filename'  ($filename is an audio or image file)
   example:
           fname:   tr0x34ab87fe-5.mp3
           returns: musichome/01/tr0x34ab87fe-5.mp3

   If all media files are in a single volume directory and if $mediavolume is set, this
   function is very fast (because it does not access to the file system)
*/
    global $gdhomedir;
    global $relmediadir;
    global $mediavolume;
    if (strlen($filename)>=8){
		if (strlen($mediavolume)==2){
			return $relmediadir.'/'.$mediavolume.'/'.$filename;
		} else {
			exec("/bin/ls $gdhomedir/??/".$filename, $fullname);
			# remove /home/music from path
			$homedir = str_replace('/', '\/', $gdhomedir);
			preg_match("/^($homedir)\/(.*)/", $fullname[0], $matches);
			return $relmediadir.'/'.$matches[2];
		}
	} else {
		return "";
    }
}

function get_mp3path($track_id) { // used // returns the mp3path of the given track
    $query = "SELECT * FROM tracks WHERE id=$track_id";
    $result = mysql_query($query) or die('Query failed: ' . mysql_error());
    $row = mysql_fetch_array($result, MYSQL_ASSOC);
    return $row["mp3file"];
}

function track_relative_path($trackid) // used
/* Searches for a track that matches $trackid. 
   Returns a path like musichome/01/tr0x34ab87fe-5.mp3
*/
{
    $filename = get_mp3path($trackid);
    return create_hyperlink($filename);
}

function move_track($source,$dir) // used
{
	if (!file_exists($inboxdir."imported/".$dir."/")) {
		exec("/bin/mkdir \"".$inboxdir."imported/".$dir."/\"");
	}
	exec("/bin/mv --target-directory=\"".$inboxdir."imported/".$dir."\" \"".addcslashes(stripslashes($source),'"')."\"");
}

function copy_track($source,$dest,$dir) // used
{
	$mediavolume = "00";
    exec("/bin/cp \"".addcslashes(stripslashes($source),'"')."\" \"".$gdhomedir."/".$mediavolume."/".$dest."\"");
	if (is_file($gdhomedir."/".$mediavolume."/".$dest)) {
		move_track($source,$dir);
		return true;
	} else {
		return false;
	}
}

?>