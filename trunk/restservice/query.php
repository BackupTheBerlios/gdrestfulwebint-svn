<?php
/* **************************************************
/  REST Webservice for GiantDisc
/  created by Andreas Baierl, 25.11.2009
/  
/
/  query.php - 	get several meta infos from db-tables (get) 
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

if ($_SERVER['REQUEST_METHOD'] == 'GET') { // GET Request
	set_headers($type);
	if ($path_params[1] != null) {
	}
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // POST Request
	$input = file_get_contents("php://input");
	set_headers($type);
	render_result(execute_query($input),null,$type); /* execute query */
}

mysql_close($database);
?>