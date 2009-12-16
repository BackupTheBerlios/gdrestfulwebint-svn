<?php
/********************/
/* Infos            */
/* infos.php  */
/********************/

/****************************************
/* Get Last Used Tracknumber

   get lasttnb
*/
function get_lasttnb() {
    $query = "SELECT mp3file FROM tracks WHERE mp3file like \"trxx________%\" ORDER BY mp3file DESC LIMIT 1";
    $nbmp3files = mysql_query($query) or die('Query failed: ' . mysql_error());
    if ($nbmp3files > 0) {
		$tracks = mysql_fetch_assoc($nbmp3files);
		preg_match('/trxx([0-9]{8}).\.*/',$tracks["mp3file"],$treffer);
	$arr['lasttnb'] = $treffer[1];
	return $arr;
    }
}
?>