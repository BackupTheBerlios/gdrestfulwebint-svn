<?php
/********************/
/* Query            */
/* query.php        */
/********************/

/****************************************
/* Execute Query

   render results
*/
function execute_query($query_string) {
    $i = 0;
	$json = json_decode($query_string);
//    $query = html_entity_decode($json->query);
	$query = utf8_decode($json->query);
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
   	while ($line = mysql_fetch_assoc($result)) {
		foreach ($line as $key => $col_value) {
				$arr[$i][$key] = utf8_encode($col_value); 
		}
		$i++;
	}
	return $arr;
}
?>