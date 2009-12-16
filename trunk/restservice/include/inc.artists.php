<?php
/****************/
/* Artists       */
/* artists.php   */
/****************/

/****************************************
/* Get Artists:

   get artists of album or tracks
   list all albums of table $table
   if $letter is given, select only artists beginnig with $letter
*/
function get_artist($table="tracks",$letter=null) {
    global $limit;
	$i = 0;
    $arr = '';
    if (isset($table)) {
		if (isset($letter)) {
			$query = "SELECT DISTINCT artist FROM $table WHERE artist LIKE '$letter%' ORDER BY artist";			
		} else {
			$query = "SELECT DISTINCT artist FROM $table ORDER BY artist";
		}
	}
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	while ($line = mysql_fetch_assoc($result)) {
		foreach ($line as $key => $col_value) {
			$arr[$i][$key] = htmlentities($col_value);
		}
		$i++;
	}
	return $arr;
}
?>