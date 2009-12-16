<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include "gdweb-settings.php";
$dbh = mysql_connect();
mysql_select_db("GiantDisc", $dbh);
check_default_settings();
//phpinfo();

### functions

###########################################################################
### headers / footers
function print_header($current, $redirect)
// prints the header (<head> and <title> tags, and opening <body> and <div> tags)
//
// if $current is not empty, the main top menu is printed
// $redirect should either be empty, or contain something like "<meta http-equiv=\"REFRESH\" content=\"0;url=$redirect\">"
{
  $redir="";
	//if (strlen($redirect)>1){
	//$redir = "<meta http-equiv=\"REFRESH\" content=\"0;url=$redirect\">";
	//$redir = "<meta http-equiv=\"REFRESH\" content=\"0;url=$redirect\">";
//  }
  header('Content-Type: text/html; charset=iso-8859-1');
  print '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
//  print '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">';
  print "\n<html>
  <head>
  $redirect
  <title>GiantDisc Web Interface v2: $current</title>
  <link href=\"gdweb.css\" rel=\"stylesheet\" type=\"text/css\">
  <SCRIPT LANGUAGE=\"JavaScript\">
  function popUp(URL) {  // *** open small popup window that performs an action and closes immediately after that
    day = new Date(); id = day.getTime();
    eval(\"page\" + id + \" = window.open(URL, '\" + id + \"', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=50,height=50,left = 50,top = 50');\");
  }
  </script>
  </head>
  <body>\n";
	
	print "<div class='maintitle'>GiantDisc Web Interface v2</div>\n";
  
  if (strlen($current)>1){
    // print the main top menu
    print "<div class='menu_box'>\n";
    $sel = (strcmp($current, "overview")==0) ? "" : "in";
    print "<div class=\"menu".$sel."active\"><a class=\"menu\" href=\"overview.php\">Overview</a></div>\n";
    $sel = (strcmp($current, "search")==0) ? "" : "in";
    print "<div class=\"menu".$sel."active\"><a class=\"menu\" href=\"search.php\">Search</a></div>\n";
    $sel = (strcmp($current, "browse")==0) ? "" : "in";
    print "<div class=\"menu".$sel."active\"><a class=\"menu\" href=\"browse.php\">Browse</a></div>\n";
    $sel = (strcmp($current, "playlist")==0) ? "" : "in";
    print "<div class=\"menu".$sel."active\"><a class=\"menu\" href=\"playlist.php\">Playlist</a></div>\n";
    $sel = (strcmp($current, "settings")==0) ? "" : "in";
    print "<div class=\"menu".$sel."active\"><a class=\"menu\" href=\"settings.php\">Options</a></div>\n";
    $sel = (strcmp($current, "shutdown")==0) ? "" : "in";
    print "<div class=\"menu".$sel."active\"><a class=\"menu\" href=\"shutdown.php\">Shutdown</a></div>\n";
    print "</div>\n";
	print "<div class='container'>\n";
	print "<div class='$current'>\n";
  }
  else{
    ;
  }
}


###########################################################################
function print_footer($current)
{
  
	print "</div>\n";
	print "<br style='clear:both;'>";
  	print "</div>\n";
  	print "<div class='foot_box'>\n";
  	print "Copyright 2008 - Andreas Baierl";
  	print "</div>\n";
  	print "</body>\n</html>\n";
}


###########################################################################
### default settings in database
function check_default_settings()
{
  global $parprefix;
  $recset = mysql_query("SELECT * FROM parameter WHERE playerid=0 AND name='$parprefix:showartist'");
	if (mysql_num_rows($recset) < 1){
    // set default values	
    set_param( "showartist", 1);
	  set_param( "showtitle",  1);
	  set_param( "showgenre", 1);
	  set_param( "showlang", 1);
	  set_param( "showyear", 1);
	  set_param( "showrating", 1);
	  set_param( "showlength", 1);
	  set_param( "showedit", 1);
	  set_param( "showdownload", 1);
	  set_param( "showtoplaylist", 1);
	  set_param( "brscolumns", 5);
	  set_param( "playtp", "dl");

  }
}

###########################################################################
### functions
function show_searchform($artist, $title, $composer, $genre, $lang, $yearfrom, $yearto, $rating, $ordercmd,
                         $bpmfrom, $bpmto, $source, $created, $modified){

// type, rating, ordercmd, limit

print "
<table border=\"0\">
<tr>
<td class=\"selformtit\"><b>Artist</b> </td>
<td class=\"selform\"> <input type=\"text\" name=\"artist\" value=\"$artist\" size=\"20\" maxlength=\"200\">
</td></tr>

<tr>
<td class=\"selformtit\"><b>Title</b></td>
<td class=\"selform\"> <input type=\"text\" name=\"title\"  value=\"$title\" size=\"20\" maxlength=\"200\">
</td></tr>

<tr>
<td class=\"selformtit\"><b>Composer</b></td>
<td class=\"selform\"> <input type=\"text\" name=\"composer\" value=\"$composer\" size=\"20\" maxlength=\"200\">
</td></tr>

<tr>
<td class=\"selformtit\"><b>Genre</b></td>
<td class=\"selform\"> 
<select name=\"genre\">";print_genre_options($genre); print "</select>
</td></tr>

<tr>
<td class=\"selformtit\"><b>Year</b></td>
<td class=\"selform\"> 
<input type=\"text\" name=\"yearfrom\"  value=\"$yearfrom\" size=\"4\" maxlength=\"4\"> - 
<input type=\"text\" name=\"yearto\"    value=\"$yearto\"   size=\"4\" maxlength=\"4\">
</td></tr>

<tr>
<td class=\"selformtit\"><b>Language</b></td>
<td class=\"selform\"> 
<select name=\"lang\">"; print_lang_options($lang); print "</select>
</td></tr>

<tr>
<td class=\"selformtit\"><b>Type</b></td>
<td class=\"selform\"> 
<select name=\"type\">"; print_type_options($type); print "</select>
</td></tr>

<tr>
<td class=\"selformtit\"><b>Rating</b></td>
<td class=\"selform\"> 
<select name=\"rating\">"; print_rating_options($rating); print "</select>
</td></tr>

<tr>
<td class=\"selformtit\"><b>Order</b></td>
<td class=\"selform\"> 
<select name=\"ordercmd\">"; print_order_options($ordercmd); print "</select>
</td></tr>

<tr><td colspan=\"2\" class=\"selform\">
<!--input type=\"reset\" value=\"Clear\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
<input type=\"submit\" value=\"&nbsp;&nbsp;Search&nbsp;&nbsp;\"></td></tr>
</table>";

}

function print_genre_options($selected)
{
  $recset = mysql_query("SELECT * FROM genre ORDER BY id");
  print "<option value=\"\">&nbsp;</option>\n";
  while($row = mysql_fetch_array($recset)) {
    print "<option value=\"".$row["id"]."\"";
    if ($row["id"] == $selected) {print(" selected");};
    print ">";
	for($i=1; $i<strlen($row["id"]); $i++){print ("&nbsp;&nbsp;&nbsp;");};
    print $row["genre"]."</option>\n";
  }
}

function print_genre_string($genre)
{
  $recset = mysql_query("SELECT * FROM genre WHERE id='$genre'");
  if($row = mysql_fetch_array($recset)) {
    print $row["genre"];
  }
}

function print_lang_options($selected)
{
  $recset = mysql_query(#"SELECT * FROM language ORDER BY id");
           "SELECT language.id, language.language, COUNT(*) AS freq "
          ."FROM language,tracks WHERE tracks.lang=language.id "
          ."GROUP BY language.id ORDER BY freq DESC ");
  print "<option value=\"\">&nbsp;</option>\n";
  while($row = mysql_fetch_array($recset)) {
    print "<option value=\"".$row["id"]."\"";
    if ($row["id"] == $selected) {print(" selected");};
    print ">";
    print $row["language"]."</option>\n";
  }
}

function print_type_options($selected)
{
  $recset = mysql_query("SELECT * FROM musictype ORDER BY id");
  print "<option value=\"\">&nbsp;</option>\n";
  while($row = mysql_fetch_array($recset)) {
    print "<option value=\"".$row["id"]."\"";
    if ($row["id"] == $selected) {print(" selected");};
    print ">";
    print $row["musictype"]."</option>\n";
  }
}

function print_source_options($selected)
{
  $recset = mysql_query("SELECT * FROM source ORDER BY id");
  print "<option value=\"\">&nbsp;</option>\n";
  while($row = mysql_fetch_array($recset)) {
    print "<option value=\"".$row["id"]."\"";
    if ($row["id"] == $selected) {print(" selected");};
    print ">";
    print $row["source"]."</option>\n";
  }
}

function print_order_options($selected)
{
  $recset = array('random'   => "Random", 
	                'artist'   => "Artist",
									'title'    => "Title",
									'year'     => "Year",
									'recd'     => "Recording date - oldest first",
									'recd-inv' => "Recording date - recent first",
									'modd'     => "Modification date - oldest first",
									'modd-inv' => "Modification date - recent first"
									);
  print "<option value=\"\">&nbsp;</option>\n";
  while(list($key, $value) = each($recset)) {
    print "<option value=\"".$key."\"";
    if (strlen($selected)>0 && $key == $selected) {print(" selected");};
    print ">";
    print $value."</option>\n";
  }
}

function print_rating_options($selected)
{
  $recset = array("-", "0", "+", "++");
  print "<option value=\"\">&nbsp;</option>\n";
  while(list($key, $value) = each($recset)) {
    print "<option value=\"".$key."\"";
    if (strlen($selected)>0 && $key == $selected) {print(" selected");};
    print ">";
    print $value."</option>\n";
  }
}

function print_rating_string($rating)
{
  $recset = array("-", "0", "+", "++");
  while(list($key, $value) = each($recset)) {
    if ($key == $rating) {print($value);};
  }
}

function language_string($language)
{
  $recset = mysql_query("SELECT * FROM language WHERE id='$language'");
  if($row = mysql_fetch_array($recset)) {
    return $row["language"];
  }
}

function type_string($type)
{
  $recset = mysql_query("SELECT * FROM musictype WHERE id='$type'");
  if($row = mysql_fetch_array($recset)) {
    return $row["musictype"];
  }
}

function print_condition_options($selected)
{
  $recset = array("ok", "noisy", "damaged");
  print "<option value=\"\">&nbsp;</option>\n";
  while(list($key, $value) = each($recset)) {
    print "<option value=\"".$key."\"";
    if ($key == $selected) {print(" selected");};
    print ">";
    print $value."</option>\n";
  }
}

function seconds2time($seconds)
{
  return floor($seconds/60).":".sprintf("%02d",($seconds%60));
}

function relative_media_path($fname)
# returns the relative path+filename for the media file 'fname'  (fname is an audio or image file)
# example:
#          fname:   tr0x34ab87fe-5.mp3
#          returns: musichome/01/tr0x34ab87fe-5.mp3
#
# If all media files are in a single volume directory and if $mediavolume is set, this
# function is very fast (because it does not access to the file system)
{
  global $gdhomedir;
	global $relmediadir;
	global $mediavolume;
	
  if (strlen($fname)>=8){
	  if (strlen($mediavolume)==2){
		  return $relmediadir.'/'.$mediavolume.'/'.$fname;
		}
		else{
      exec("/bin/ls $gdhomedir/??/".$fname, $fullname);

      # remove /home/music from path
		  $homedir = str_replace('/', '\/', $gdhomedir);
      preg_match("/^($homedir)\/(.*)/", $fullname[0], $matches);
      return $relmediadir.'/'.$matches[2];		
		}
  }
  else{
    return "";
  }
}

function track_relative_path($trackid)
// Searches for a track that matches $trackid. 
// Returns a path like musichome/01/tr0x34ab87fe-5.mp3
{
  $recset = mysql_query("SELECT * FROM tracks WHERE id='$trackid'");
  if($row = mysql_fetch_array($recset)) {
    return relative_media_path($row["mp3file"]);
  }
  else{
	  print "Error: Track id '$trackid' does not exist in DB!";
	  return "";
	}
}


#######################################################################################

function show_trackrow($rownum, $row, 
                       $showrownum, $showartist, $showtitle, $showcomposer, $showgenre, 
											 $showlang, $showyear, $showrating,
			                 $showbpm, $showsource, $showcreated, $showmodified, $showlength,
                       $showedit, $showdownload, $playtp, $showtoplaylist, $showremove)
// $row is a string with tab seperated fields
{
  list($id,$artist,$title,$composer,$length,$genre1,$year,$type,$rating) = split ("\t",$row);

	if (strlen($year)>2){ $yearstr = "'".substr($year, 2, 2);}
	else                { $yearstr = $year;}

  if ($showrownum){  print "<td class=\"rownum\">".($rownum+1).".</td>"; }
  if ($showartist){  print "<td class=\"trklst\">$artist</td>"; }
  if ($showtitle){   print "<td class=\"trklst\">$title</td>";  }
  if ($showcomposer){print "<td class=\"trklst\">$composer</td>";}
  if ($showgenre){
    print "<td class=\"trklst\">&nbsp;";
    if (strlen($genre1)>0){
	    print_genre_string($genre1);
	  }
    if (strlen($genre1)>0 && strlen($genre2)>0){print "<br>";}
    if (strlen($genre2)>0){
	    print "&nbsp;";
	    print_genre_string($genre2);
	  }
	  print "</td>";
	}
  if ($showrating){
	  print "<td class=\"trklst\">&nbsp;";
    print_rating_string($rating);
	  print "</td>";
	}
  if ($showlang){  print "<td class=\"trklst\">&nbsp;".language_string($lang)." </td>";	}
  if ($showyear){  print "<td class=\"trklst\">&nbsp;".$yearstr." </td>";	}
  if ($showlength){print "<td class=\"trklst\">&nbsp;".seconds2time($length)." </td>";	}
  if ($showedit){
	  print "<td class=\"trklst\">&nbsp;<a href=\"gdedittrack.php?id="
          .$id."\" target=\"_blank\">"
          ."<img border=\"0\" src=\"img/edit-c.gif\" title=\"Edit\"></a></td>";
	}
  if ($showdownload){
    # Download mp3
	  # types: playtp = dl or pl
    if (strcmp($playtp, "dl")==0){
	    print "<td class=\"trklst\">&nbsp;<a href=\"".track_relative_path($id)."\">"
		     ."<img border=\"0\" src=\"img/dl.gif\" title=\"Download\"></a>$mp3path</td>";
	  }
    else{ # all other playtypes handled by "gdaction.php"
        print "<td class=\"trklst\">&nbsp;<a href=\"gdaction.php?action=dlplay&amp;method=$playtp&amp;trackid=$id\">"
		     ."<img border=\"0\" src=\"img/dl.gif\" alt=\"download and play\"></a></td>";
//        print "<td class=\"trklst\">&nbsp;<a href=\"javascript:popUp('gdaction.php?action=dlplay&amp;method=$playtp&amp;trackid=$id')\">"
//		     ."<img border=\"0\" src=\"img/dl.gif\" alt=\"download and play\"></a></td>";
	  }
	}
  if ($showtoplaylist){
    # append to playlist
    print "<td class=\"trklst\">&nbsp;<a href=\"javascript:popUp('gdaction.php?action=addtopl&amp;trackid=$id')\">"
		     ."<img border=\"0\" src=\"img/tri-r.png\" title=\"append to playlist\"></a></td>";
	}
  if ($showremove){
    # append to playlist
    print "<td class=\"trklst\">&nbsp;<a href=\"?removeitem=$rownum\">"
		     ."<img border=\"0\" src=\"img/remove.png\" alt=\"remove item\"></a></td>";
	}
    # goto playlist item
    print "<td class=\"trklst\">&nbsp;<a href=\"?gotoitem=$rownum\">"
		     ."<img border=\"0\" src=\"img/pl.png\" alt=\"jump\"></a></td>";

}

#######################################################################################

function show_track_search_result_table($searchres)
  ### display the tracks in a table
{
  get_column_view_parameters($showartist, $showtitle, $showcomposer, $showgenre, $showlang, $showyear, $showrating,
                             $showlength, $showedit, $showdownload, $showtoplaylist);

	$playtp  = get_param( "playtp");

  $reslen = count($searchres);  
  print("<p><small>$reslen machting tracks</small><br>");
  print("<table border=\"0\" cellspacing=\"0\">\n");
  $evenln = 1;
	foreach ($searchres as $row){
    print "<tr class=\"ln". ($evenln?"even":"odd") ."\">";
    show_trackrow(0/*$rownum*/, $row,
	          false/*showrownum*/, $showartist, $showtitle, $showcomposer, $showgenre, $showlang, $showyear, $showrating,
            $showbpm, $showsource, $showcreated, $showmodified, 
						$showlength, $showedit, $showdownload, $playtp, true/*topl*/, false/*remove*/);
    $evenln = 1-$evenln; #toggle $evenln
	
    print "</tr>\n";
  }
  print("</table>\n");
}

#######################################################################################


function write_albumlink($prefix, $albumrecord, $showedit)
# writes an album as a table row
# if $prefix is empty, no link to tracks is generated
{
  print "<tr>";  #<tr class="lnodd"   ...even>
  print "<td class=\"trklst\">";
  if (strlen($albumrecord["coverimg"])>1){
    //$imgpath = relative_media_path($albumrecord["coverimg"]);
    $imgpath = relative_media_path(basename($albumrecord["coverimg"], ".jpg")."-s90.jpg");  // take scaled image
    print "<a href=\"gdshowalbum.php?cddbid=".$albumrecord["cddbid"]."\">";
    print "<img width=\"40\" src=\"".$imgpath."\" height=\"40\" border=\"0\">";
    print "</a>";
  }
  print "</td>";
  print "<td class=\"trklst\">".$albumrecord["artist"]."</td>";
  print "<td class=\"trklst\"> - </td>";
  if (strlen($prefix)>0){
    print "<td><a class=\"brslst\" href=\"".$prefix.$albumrecord["cddbid"]."\">"
        .$albumrecord["title"]."</a></td> ";
  }
  else{
    print "<td class=\"trklst\">".$albumrecord["title"]."</td> ";
  }


  if ($showedit){
    print "<td class=\"trklst\">&nbsp;<a href=\"gdeditalbum.php?cddbid="
		   		.$albumrecord["cddbid"]."\" target=\"_blank\">"
				."<img border=\"0\" src=\"img/edit-c.gif\" alt=\"Edit\"></a></td>";
  }

  #Album-Playlist-Link
  print "<td class=\"trklst\">&nbsp;<a href=\"gdaction.php?action=dlplay&amp;method=pl"
        ."&amp;albumid=".$albumrecord["cddbid"]
        ."\">"."<img border=\"0\" src=\"img/dl.gif\" alt=\"Download\"></a></td>"; 
  #Album-AddToPlaylist-Link
  print "<td class=\"trklst\">&nbsp;<a href=\"javascript:popUp('gdaction.php?action=aladdtopl&amp;method=pl&amp;albumid=$albumrecord[cddbid]')"
       ."\">"."<img border=\"0\" src=\"img/pl.png\" alt=\"AddToPlaylist\"></a></td>"; 
  print "</tr>\n";
}


###########################################################################
### parameter functions

function get_param($paramname)
{
  global $parprefix;
  $recset = mysql_query("SELECT * FROM parameter WHERE playerid=0 AND name='$parprefix:$paramname'");
  if($row = mysql_fetch_array($recset)) {
    return $row["value"];
  }
	else{
	  return "";
	}
}

function set_param($paramname, $value)
{
  global $parprefix;
  $recset = mysql_query("REPLACE INTO parameter SET playerid=0, name='$parprefix:$paramname', value='$value'");
}


###########################################################################

function get_column_view_parameters(
                       &$showartist, &$showtitle, &$showcomposer, &$showgenre, &$showlang, &$showyear, &$showrating,
//			                 $showbpm, $showsource, $showcreated, $showmodified, 
											 &$showlength, &$showedit, &$showdownload, &$showtoplaylist)
{
  $showartist     = get_param( "showartist");
	$showtitle      = get_param( "showtitle");
	$showcomposer   = get_param( "showcomposer");
	$showgenre      = get_param( "showgenre");
	$showlang       = get_param( "showlang");
	$showyear       = get_param( "showyear");
	$showrating     = get_param( "showrating");
	$showlength     = get_param( "showlength");
	$showedit       = get_param( "showedit");
	$showdownload   = get_param( "showdownload");
	$showtoplaylist = get_param( "showtoplaylist");
}

###########################################################################
### "direct linking" functions

function dl_make_filelist()
{
#  exec("/bin/ls /home/music/??/*0.mp3 > tmp/filelist.txt");

#  $fp = fopen ("tmp/file2.txt", "w");
#  fwrite ($fp, "This is my string\n");
#  fclose($fp);
}




###########################################################################
### communication routines

/*
  The first versions of this client directly accessed to the database and the filesystem, and could
	run totaly independently of a GD server.
	Since the server allows to have multiple connected clients connected to it, it is cleaner to do
	all accesses to the GD system over the protocol that is also used by the Palm. 
	
	In the future, this client will successively be rewritten, and adapted to the GD-server protocol.
	
	Example usage:
    open_transaction();
    send_command("r-ping");
    print "<pre>".print_r (receive_results(),true)."</pre>";
    close_transaction();
*/

/*
print "<p>socket test</p>\n";
$fp = fsockopen(get_param("gdhostname"), get_param("gdport"), $errno, $errstr, 30);
if (!$fp) {
   echo "$errstr ($errno)<br />\n";
} else {
   print "<p>writing ping</p>\n";
   fwrite($fp, "r-ping\r\n");

   while (1) {
       $buffer = fgets($fp); // read one line
			 if (strlen($buffer) > 1){
         print "<br>: $buffer";
			 }
			 else{
			   break;
			 }
   }

	 fclose($fp);
}
*/
$sockhandle;
$lastcommand;

function open_transaction()
{
  global $sockhandle, $gd_server_host, $gd_server_port;
  $sockhandle = fsockopen($gd_server_host, $gd_server_port, $errno, $errstr, 30);
  if (!$sockhandle) {
    echo "$errstr ($errno)<br />\n";
  }
}

function close_transaction()
{
  global $sockhandle;
  fclose($sockhandle);
}

function send_command($command)
{
  global $sockhandle, $lastcommand;
  if (!$sockhandle) {
    echo "Error: no socket connection available!<br />\n";
  } else {
    //print "<p>writing ping</p>\n";
    fwrite($sockhandle, $command."\n");
		$lastcommand = $command;
  }
}

function receive_results()
{
  global $sockhandle;
	$results = array();
	
  while (!feof($sockhandle)) { // eof is usually not the reason to exit the loop
    $buffer = fgets($sockhandle); // read one line
	  if (strlen($buffer) > 1){
      //print "<br>: $buffer";
		  array_push($results, rtrim($buffer));
		}
		else{
		  break;
		}
  }
	return $results;
}

?>