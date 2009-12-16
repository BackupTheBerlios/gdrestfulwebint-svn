<?php
/*********************************************
/* Query Script for GiantDisc Server
   written by Andreas Baierl in 2009

   Project Homepage: http://sourceforge.net/projects/gdrestfulwebint/
   Help: http://sourceforge.net/apps/mediawiki/gdrestfulwebint/index.php!

*/

require_once "includes/inc.includes.php";   // Include necessary files

build_header("Query");
build_body("Query","2","onload=\"JavaScript:init();\"");

	echo '
	Query-String:<br /><br />
	<input type="text" size="150" name="query_string" id="query_string" />
	<input type="hidden" name="query" value="1" />
	<p><input type="submit" name="submit" value="Submit Query" onclick="do_query();"/></p>
	<div id="queryresult"></div>';

build_footer();
?>