<?php
/* **************************************************
/  REST Webservice for GiantDisc
/  created by Andreas Baierl, 25.11.2009
/  
/
/  inbox.php - 	get inbox files and directories (get) 
/
/
/* *************************************************/

require_once "include/includes.php";

$database = init_database();

if (isset($_GET['type'])) {
	$type = $_GET['type'];
}

// Check for the path elements
$path = $_SERVER[PATH_INFO];
if ($path != null) {
    $path_params = spliti("/", $path);
}

if (isset($_GET['type'])) {
	$type = $_GET['type'];
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') { // GET Request
		set_headers($type);
		render_result(read_inbox(),"inbox",$type); /* render inbox directories and files (.mp3) */
}

mysql_close($database);
?>