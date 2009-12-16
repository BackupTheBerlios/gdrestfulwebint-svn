<?php
/********************************************************************************************************
	Build HTML
*/

function build_header($title="GD") {

print 	'<html>
<head>
<meta http-equiy="content-type" content="text/html; charset=UTF-8">
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<title>GiantDisc Web Interface v2: '.$title.'</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<script type="text/javascript" src="js/jquery/jquery-1.3.2.min.js"></script>

<script type="text/javascript" src="js/json2.js"></script>
<script type="text/javascript" src="js/common.js"></script>
</head>';

//<script type="text/javascript" src="js/jquery/labs_json.js"></script>
print "\n\n";

}

function build_body($title,$class=null,$javascript=null) {
	if ($class != null) {
		print 	"<body class='".$class."' ".$javascript.">";
		print "\n";
	} else {
		print 	'<body>';
		print "\n";
	}
	if (strlen($title)>1){
		print "<div class='menu_box'>\n";    // print the main top menu
		$sel = (strcmp($title, "Import")==0) ? "" : "in";
		print "<div class=\"menu".$sel."active\"><a class=\"menu\" href=\"import.php\">Import</a></div>\n";
		$sel = (strcmp($title, "Browse")==0) ? "" : "in";
		print "<div class=\"menu".$sel."active\"><a class=\"menu\" href=\"browse.php\">Browse</a></div>\n";
		$sel = (strcmp($title, "Query")==0) ? "" : "in";
		print "<div class=\"menu".$sel."active\"><a class=\"menu\" href=\"query.php\">Query</a></div>\n";
		print "</div>\n";
		print "<div class='container'>\n";
		print "<div class='$title'>\n";
	  }
}

function build_footer() {
	print "</div>\n";
	print "<br style='clear:both;'>";
  	print "</div>\n";
  	print "<div class='foot_box'>\n";
  	print "GiantDisc RESTful Webinterface v1   .....   ";
  	print "Copyright 2009 - Andreas Baierl";
  	print "</div>\n";
  	print "</body>\n</html>\n";
}

function write_artistlink($prefix, $artist, $highlight=null) { // writes artistlink
  global $trunclen;
  if (strlen($artist)>$trunclen){
    $art = substr($artist, 0, $trunclen);
    $appendix="..";
  }
  else{
    $art = $artist;
    $appendix="";
  }
	if ($artist == stripslashes($highlight)) {
		print "<a class=\"brslst brslst_active\" href=\"".$prefix.urlencode($art)."\">".$art.$appendix."</a>&nbsp; ";
	} else {
		print "<a class=\"brslst\" href=\"".$prefix.urlencode($art)."\">".$art.$appendix."</a>&nbsp; ";	
	}
}

function write_albumlink($prefix, $cddbid, $album, $highlight=null) { // writes artistlink
  global $trunclen;
  if (strlen($album)>$trunclen){
    $alb = substr($album, 0, $trunclen);
    $appendix="..";
  }
  else{
    $alb = $album;
    $appendix="";
  }
	if ($cddbid == stripslashes($highlight)) {
		print "<a class=\"brslst brslst_active\" href=\"".$prefix.urlencode($cddbid)."\">".$alb.$appendix."</a>&nbsp; ";
	} else {
		print "<a class=\"brslst\" href=\"".$prefix.urlencode($cddbid)."\">".$alb.$appendix."</a>&nbsp; ";	
	}
  
}


function write_alphabet($prefix, $highlight)
{
  	$cnt = ord("a");
  	$str = "";
  	while ($cnt <= ord("z")){
		if ($cnt == ord($highlight)){$str .= "<b>";};
		$str .= "<a class='brslst' href='".$prefix.chr($cnt)."'>".chr($cnt).".. </a>";
		if ($cnt == ord($highlight)){$str .= "</b>";};
		$cnt++;
  	}
  	print "<script type=\"text/javascript\">";
  	print "var alph = document.getElementById(\"alphabeth\");";
  	print "alph.innerHTML = \"$str\";";
  	print "</script>";
}

function write_tracklist($tracklist,$artist=null,$title=null,$cddbid=null)
{
	global $trackrow_param;
	print "<table borders='0'>";
	write_track_table_head($trackrow_param);
	if ($tracklist) { 
		foreach ($tracklist as $track) {
			if (isset($artist)) {
				if (preg_match("/^".stripslashes($artist)."/i", $track->artist)) {
					$json = json_decode(get_track($track->id));
					print "<tr>";
					write_trackrow($json,$trackrow_param);
					print "</tr>";
				}
			} elseif (isset($title)) {
				if (preg_match("/^".stripslashes($title)."/i", $track->title)) {
					$json = json_decode(get_track($track->id));
					print "<tr>";
					write_trackrow($json,$trackrow_param);
					print "</tr>";
				}
			} else {
				$json = json_decode(get_track($track->id));
				print "<tr>";
				write_trackrow($json,$trackrow_param);
				print "</tr>";
			}
		}
	}
	print "</table>";
}

function write_play_button($trackid,$artist,$title) {
	$filename = urlencode($artist." - ".$title.".mp3");
	return("<a href=\"action.php?action=play&id=".$trackid."&filename=".$filename."\" target=\"_blank\"><img src=\"images/icons/16x16/16x16_folder_4.png\" alt=\"play\" title=\"Play Track ".urldecode($filename)."\" border=0></a>");
}

function write_edit_button($trackid) {
	return("<a href=\"action.php?action=edit&id=".$trackid."\" target=\"_blank\"><img src=\"images/icons/16x16/16x16_edit.png\" alt=\"edit\" title=\"Edit Track ".$trackid."\" border=0></a>");
}

function write_trackrow($track,$trackrow_param=null)
{
	$style = "brslist_level2";
	if ($trackrow_param->artist) { print "<td class=\"".$style."\">".html_entity_decode($track->artist,ENT_COMPAT,"UTF-8")."</td>\n"; }
	if ($trackrow_param->title) { print "<td class=\"".$style."\">".html_entity_decode($track->title,ENT_COMPAT,"UTF-8")."</td>\n"; }
	if ($trackrow_param->length) { print "<td class=\"".$style."\">".print_time_string(html_entity_decode($track->length,ENT_COMPAT,"UTF-8"))."</td>\n"; }
	if ($trackrow_param->play) { print "<td class=\"".$style."\">".write_play_button($track->id,$track->artist,$track->title)."</td>\n"; }
	if ($trackrow_param->edit) { print "<td class=\"".$style."\">".write_edit_button($track->id)."</td>\n"; }
}

function write_track_table_head($trackrow_param=null)
{
	$style = "brslist_level2";
	if ($trackrow_param->artist) { print "<th class=\"".$style."\">Artist</th>\n"; }
	if ($trackrow_param->title) { print "<th class=\"".$style."\">Title</th>\n"; }
	if ($trackrow_param->length) { print "<th class=\"".$style."\">Length</th>\n"; }
	if ($trackrow_param->play) { print "<th></th>\n"; }
	if ($trackrow_param->edit) { print "<th></th>\n"; }
}
?>
