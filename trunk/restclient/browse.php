<?php
/*********************************************
/* Browse Script for GiantDisc Server
   written by Andreas Baierl in 2009

   Project Homepage: http://sourceforge.net/projects/gdrestfulwebint/
   Help: http://sourceforge.net/apps/mediawiki/gdrestfulwebint/index.php

*/

require_once "includes/inc.includes.php";   // Include necessary files

build_header("Browse");
build_body("Browse");

// get parameters
$level0 = $_GET['level0'];
$level1 = $_GET['level1'];
$level2 = urldecode($_GET['level2']);
$ar = $_GET['ar'];
$tr = $_GET['tr'];
$sourceid = $_GET['sourceid'];

// set trackrow parameters (to be improved! OOP!)
$trackrow_param->artist = TRUE;
$trackrow_param->title = TRUE;
$trackrow_param->length = TRUE;
$trackrow_param->play = TRUE;
$trackrow_param->edit = TRUE;


#######################################################################################

### Show first level:

// print the main top menu
print "<div class='browsemenu'>\n";
$sel = (strcmp($level0, "tr-ar")==0) ? "" : "in";
print "<div class=\"browsemenuitem".$sel."active\"><a href=\"browse.php?level0=tr-ar\">Tracks by Artist</a></div>\n";
$sel = (strcmp($level0, "tr-ti")==0) ? "" : "in";
print "<div class=\"browsemenuitem".$sel."active\"><a href=\"browse.php?level0=tr-ti\">Tracks by Title</a></div>\n";
$sel = (strcmp($level0, "al-ar")==0) ? "" : "in";
print "<div class=\"browsemenuitem".$sel."active\"><a href=\"browse.php?level0=al-ar\">Albums by Artist</a></div>\n";
$sel = (strcmp($level0, "al-ti")==0) ? "" : "in";
print "<div class=\"browsemenuitem".$sel."active\"><a href=\"browse.php?level0=al-ti\">Albums by Title</a></div>\n";


print "</div>\n";
// print alphabeth div - container
if (isset($level0)){
	if ((strcmp($level0, "tr-gn")==0) || (strcmp($level0, "al-gn")==0) ) { } else {	print "<div class='alphabeth_box' id='alphabeth'>"; print "</div>"; }
}


if (isset($level0)){

/// Tracks by Artist
	if (strcmp($level0, "tr-ar")==0){
		$trackrow_param->artist = FALSE;
		write_alphabet("browse.php?level0=tr-ar&level1=", $level1); // print alphabet into container

		print "<div class='results'>\n";	
		print "<div class='searchresults'>\n";
		print "<table borders=\"0\">\n";
		print "<th class='brslist_level2'>Artists</th>";
		
		$json = json_decode(get_artists("tracks",$level1));
		if ($json) {
			foreach ($json as $artist) {
				print "<tr><td class='brslist_level1'>\n";			
				write_artistlink("browse.php?level0=tr-ar&level1=$level1&level2=", html_entity_decode($artist->artist,ENT_COMPAT,"UTF-8"),$level2);
				print "</td></tr>\n";
			}
		}
		print "</table>\n";
		print "</div>\n";
			
		if (((isset($level0)) && ($level2 == "") && ($level1 == "")) || ($level2 != "")){  # artist / level 2 specified?
			print "<div class='searchresults border-left'>\n";
			$tracklist = json_decode(get_tracks());
			write_tracklist($tracklist,$level2);
			print "</div>\n";
		}
		print "</div>\n";
	}

/// Tracks by Title
	if (strcmp($level0, "tr-ti")==0){
		write_alphabet("browse.php?level0=tr-ti&level1=", $level1);
		print "<div class='results'>\n";	
		print "<div class='searchresults'>\n";
		$tracklist = json_decode(get_tracks());
		write_tracklist($tracklist,null,$level1);
		print "</div>\n";
		print "</div>\n";
	}

/// Albums by Artist
	if (strcmp($level0, "al-ar")==0){
		$trackrow_param->artist = FALSE;
		write_alphabet("browse.php?level0=al-ar&level1=", $level1); // print alphabet into container
		print "<div class='results'>\n";
		print "<div class='searchresults'>\n";
		$json = json_decode(get_artists("albums",$level1));
		print "<table borders=\"0\">\n";
		print "<th class='brslist_level2'>Artists</th>";
		if ($json) {
			foreach ($json as $artist) {
				print "<tr><td class='brslist_level1'>\n";
				write_artistlink("browse.php?level0=al-ar&level1=$level1&level2=", html_entity_decode($artist->artist,ENT_COMPAT,"UTF-8"),$level2);
				print "</td></tr>\n";
			}
		}
		print "</table>\n";
		print "</div>\n";
		
		if (((isset($level0)) && ($level2 == "") && ($level1 == "")) || ($level2 != "")){  # artist / level 2 specified?
			print "<div class='searchresults border-left'>\n";
			print "<table borders=\"0\">\n";		
			print "<th class='brslist_level2'>Albums</th>";
			$albumlist = json_decode(get_albums());
			if ($albumlist) {
				foreach ($albumlist as $album) {
					if (isset($level2)) {
						if (preg_match("/^".stripslashes($level2)."/i", $album->artist)) {
							$json = json_decode(get_album($album->cddbid));
							print "<tr><td class='brslist_level1'>\n";
							write_albumlink("browse.php?level0=al-ar&level1=$level1&level2=".urlencode(stripslashes($level2))."&sourceid=", $album->cddbid,html_entity_decode($album->title,ENT_COMPAT,"UTF-8"),$sourceid);
							print "</td></tr>\n";
						}
					}
				}
			}
			print "</table>\n";
			print "</div>\n"; 
		}
		
		if (isset($sourceid)){ # cddbid / level 3 specified?
			print "<div class='searchresults border-left'>\n";
			$tracklist = json_decode(get_album_tracks($sourceid));
			write_tracklist($tracklist);
			print "</div>\n";
		}
		print "</div>\n";
	}

/// Albums by Title
	if (strcmp($level0, "al-ti")==0){
		write_alphabet("browse.php?level0=al-ti&level1=", $level1);
		print "<div class='results'>\n";
		print "<div class='searchresults'>\n";
		print "<table borders=\"0\">\n";	
		print "<th class='brslist_level2'>Albums</th>";	
		$albumlist = json_decode(get_albums());
		if ($albumlist) { 
			foreach ($albumlist as $album) {
				if (isset($level1)) {
					if (preg_match("/^".stripslashes($level1)."/i", $album->title)) {
						print "<tr><td class='brslist_level1'>\n";
						write_albumlink("browse.php?level0=al-ti&level1=$level1&sourceid=", $album->cddbid,html_entity_decode($album->title,ENT_COMPAT,"UTF-8"),$sourceid);
						print "</td></tr>\n";
					}
				} else {
					print "<tr><td class='brslist_level1'>\n";
					write_albumlink("browse.php?level0=al-ti&level1=$level1&sourceid=", $album->cddbid,html_entity_decode($album->title,ENT_COMPAT,"UTF-8"),$sourceid);
					print "</td></tr>\n";
				}
			}
		}
		print "</table>\n";
		print "</div>\n"; 
		if (isset($sourceid)){ # sourceid specified?
			print "<div class='searchresults border-left'>\n";
			$tracklist = json_decode(get_album_tracks($sourceid));
			write_tracklist($tracklist);
			print "</div>\n";
		}
		print "</div>\n"; 
	}
}

build_footer ("browse");
?>
