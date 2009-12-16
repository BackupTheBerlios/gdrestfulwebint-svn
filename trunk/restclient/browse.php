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
$level2 = $_GET['level2'];
$ar = $_GET['ar'];
$tr = $_GET['tr'];
$sourceid = $_GET['sourceid'];

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



$whereclause = ""; # clear query string

if (isset($level0)){

### Tracks by Artist
if (strcmp($level0, "tr-ar")==0){
	write_alphabet("browse.php?level0=tr-ar&level1=", $level1); // print alphabet into container
	print "<div class='results'>\n";	
	print "<div class='searchresults'>\n";
	$json = json_decode(get_artists("tracks/",$level1));
	print "<table borders=\"0\">\n";
	print "<th class='brslist_level2'>Artists</th>";
	if ($json) {
		foreach ($json as $artist) {
			print "<tr><td class='brslist_level1'>\n";			
			write_artistlink("browse.php?level0=tr-ar&level1=$level1&ar=", html_entity_decode($artist->artist,ENT_COMPAT,"UTF-8"));
			print "</td></tr>\n";
		}
	}
	print "</table>\n";
	print "</div>\n";
	if (isset($ar)){ # artist / level 2 specified?
		$query_str = "SELECT * FROM tracks WHERE artist LIKE \"".$ar."%\"";
		$query["query"] = stripslashes(htmlentities($query_str,ENT_COMPAT,"UTF-8"));
		$json = json_decode(send_query(json_encode($query)));
		print "<div class='searchresults border-left'>\n";
		write_tracklist($json);
		print "</div>\n";
	}
	print "</div>\n";
}

### Tracks by Title
if (strcmp($level0, "tr-ti")==0){
	write_alphabet("browse.php?level0=tr-ti&level1=", $level1);
	print "<div class='results'>\n";
	print "<div class='searchresults'>\n";
	$query_str = "SELECT * FROM tracks WHERE title LIKE \"".$level1."%\"";
	$query["query"] = stripslashes(htmlentities($query_str,ENT_COMPAT,"UTF-8"));
	$json = json_decode(send_query(json_encode($query)));
	write_tracklist($json);
	print "</div>\n";
	print "</div>\n";
}

### Albums by Artist
if (strcmp($level0, "al-ar")==0){
	write_alphabet("browse.php?level0=al-ar&level1=", $level1); // print alphabet into container
	print "<div class='results'>\n";
	print "<div class='searchresults'>\n";
	$json = json_decode(get_artists("album/",$level1));
	print "<table borders=\"0\">\n";
	print "<th class='brslist_level2'>Artists</th>";
	if ($json) {
		foreach ($json as $artist) {
			print "<tr><td class='brslist_level1'>\n";
			write_artistlink("browse.php?level0=al-ar&level1=$level1&level2=", html_entity_decode($artist->artist,ENT_COMPAT,"UTF-8"));
			print "</td></tr>\n";
		}
	}
	print "</table>\n";
	print "</div>\n";
	if (isset($level2)){ # artist / level 2 specified?
		$query_str = "SELECT * FROM album WHERE artist LIKE \"".$level2."%\"";
		$query["query"] = stripslashes(htmlentities($query_str,ENT_COMPAT,"UTF-8"));
		$json = json_decode(send_query(json_encode($query)));
		print "<div class='searchresults border-left'>\n";
		print "<table borders=\"0\">\n";		
		print "<th class='brslist_level2'>Albums</th>";
		if ($json) {
			foreach ($json as $album) {
				print "<tr><td class='brslist_level1'>\n";
				write_albumlink("browse.php?level0=al-ar&level1=$level1&level2=$level2&sourceid=", $album->cddbid,html_entity_decode($album->title,ENT_COMPAT,"UTF-8"));
				print "</td></tr>\n";
			}
		}
		print "</table>\n";
		print "</div>\n"; 
	}
	if (isset($sourceid)){ # cddbid / level 3 specified?
		$query_str = "SELECT * FROM tracks WHERE sourceid LIKE \"".$sourceid."%\"";
		$query["query"] = stripslashes(htmlentities($query_str,ENT_COMPAT,"UTF-8"));
		$json = json_decode(send_query(json_encode($query)));
		print "<div class='searchresults border-left'>\n";
		write_tracklist($json);
		print "</div>\n"; 
	}
	print "</div>\n";
}

### Albums by Title
if (strcmp($level0, "al-ti")==0){
	write_alphabet("browse.php?level0=al-ti&level1=", $level1);
	print "<div class='results'>\n";
	$query_str = "SELECT * FROM album WHERE title LIKE \"".$level1."%\"";
	$query["query"] = stripslashes(htmlentities($query_str,ENT_COMPAT,"UTF-8"));
	$json = json_decode(send_query(json_encode($query)));
	print "<div class='searchresults'>\n";
	print "<table borders=\"0\">\n";	
	print "<th class='brslist_level2'>Albums</th>";	
	if ($json) {
		foreach ($json as $album) {
			print "<tr><td class='brslist_level1'>\n";			
			write_albumlink("browse.php?level0=al-ti&level1=$level1&sourceid=", $album->cddbid,html_entity_decode($album->title,ENT_COMPAT,"UTF-8"));
			print "</td></tr>\n";
		}
	}
	print "</table>\n";
	print "</div>\n"; 
	if (isset($sourceid)){ # cddbid / level 3 specified?
		$query_str = "SELECT * FROM tracks WHERE sourceid LIKE \"".$sourceid."%\"";
		$query["query"] = stripslashes(htmlentities($query_str,ENT_COMPAT,"UTF-8"));
		$json = json_decode(send_query(json_encode($query)));
		print "<div class='searchresults border-left'>\n";
		write_tracklist($json);
		print "</div>\n"; 
	}
	print "</div>\n"; 
}

/* 

  if (strcmp($l0, "al-ti")==0){
    write_alphabet("browse.php?l0=al-ti&l1=", $l1);
    if (isset($l1)){ # level 1 (initial char) specified?
      print "<div class='leftform'>\n";
    	$tempquery = "SELECT * FROM album WHERE title LIKE '$l1%' ORDER BY title";
      $recset = mysql_query($tempquery);
      if (mysql_affected_rows()>0){print("<p><small>".mysql_affected_rows()." albums</small><br>");}
      print "<table borders=\"0\">";
      while($row = mysql_fetch_array($recset)) {
        write_albumlink("browse.php?l0=al-ti&l1=$l1&albid=", $row, $showedit);
      }
      print "</table>";
    print "</div>\n";
    }
    if (isset($albid)){ # albumid / level 2 specified?
      $whereclause = "WHERE sourceid = '$albid' ORDER BY tracknb ";
    }
  }
*/
}

build_footer ("browse");
?>
