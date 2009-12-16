<?php

/* Database Settings */
$dbhost = "localhost";  // Database Host
$dbuser = "music";  // Database User
$dbpasswd = "";  // Database Password
$dbname = "GiantDisc2";  // Database Name

/* Rendering Settings */
$type = "json"; // How should the request be rendered ? (json|xml|html|raw)

/* Query Settings */
$limit = 1000; // Limit for Track- and Album - Queries

/* Settings for file access */
$gdhomedir = "/home/music"; 		// music home directory
$inboxdir = $gdhomedir."/inbox/"; 	// inbox directory
$relmediadir = "musichome"; 		// relative path prefix for media file access
$mediavolume = "";   		/* media files volume directory
				   Media files typically can be distributed over volume directories wit 2-digit names eg. '00', '01', ...
				   If all files are in a single volume, it can be specified here. Otherwise it must be empty.
				   It is alwas save to leave this setting empty, but performance is greatly improved
				   if a single media volume can be specified, because less file system accesses are needed.
				   no leading and preceding slashes! The length of this string must be 0 or 2. */
$basedir = "/var/www/";
?>
