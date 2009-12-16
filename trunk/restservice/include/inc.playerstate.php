<?php
/********************/
/* Playerstate      */
/* playerstate.php  */
/********************/

/****************************************
/* Get Playerstateinfo:

   get info of playerstate
*/
function get_playerstate($sel=null) {
    if (isset($sel)) {
		$query = "SELECT $sel FROM playerstate WHERE 1";
	} else {
		$query = "SELECT * FROM playerstate";
	}
    $result = mysql_query($query) or die('Query failed: ' . mysql_error());
	while ($line = mysql_fetch_assoc($result)) {
		foreach ($line as $key => $col_value) {
				$arr[$key] = htmlspecialchars($col_value); 
		}
	}
	return $arr;
}
?>