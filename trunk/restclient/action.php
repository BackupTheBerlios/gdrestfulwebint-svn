<?php
/*********************************************
/* Action Script for GiantDisc Server
   written by Andreas Baierl in 2009

   Project Homepage: http://sourceforge.net/projects/gdrestfulwebint/
   Help: http://sourceforge.net/apps/mediawiki/gdrestfulwebint/index.php

*/

require_once "includes/inc.includes.php";   // Include necessary files
	$action = $_GET['action'];
	$id = $_GET['id'];
	$filename = urldecode($_GET['filename']);

if (strcmp($action, 'play')==0){
	header("Content-Type: audio/mp3");
	header("Content-Disposition: attachment; filename=\"".$filename."\"");
	echo (get_audio($id));
}

if (strcmp($action, 'edit')==0){
	echo "Edit Track";
}


?>
