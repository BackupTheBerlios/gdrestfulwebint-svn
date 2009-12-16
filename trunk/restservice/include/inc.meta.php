<?php
/********************/
/* Meta             */
/* meta.php         */
/********************/

/****************************************
/* Get Language Table

   get languages
*/
function get_languages() {
    $i = 0;
	$query = "SELECT id,language FROM language ORDER BY language";
    $result = mysql_query($query) or die('Query failed: ' . mysql_error());
   	while ($line = mysql_fetch_assoc($result)) {
		foreach ($line as $key => $col_value) {
				$arr[$i][$key] = htmlspecialchars($col_value); 
		}
		$i++;
	}
	return $arr;
}

/****************************************
/* Get Musictypes Table

   get types
*/
function get_types() {
    $i = 0;
	$query = "SELECT id,musictype FROM musictype";
    $result = mysql_query($query) or die('Query failed: ' . mysql_error());
   	while ($line = mysql_fetch_assoc($result)) {
		foreach ($line as $key => $col_value) {
				$arr[$i][$key] = htmlspecialchars($col_value); 
		}
		$i++;
	}
	return $arr;
}

/****************************************
/* Get Genres Table

   get genres
*/
function get_genres() {
    $i = $j = $k = $m = 0;
	$query = "SELECT id,genre,id3genre FROM genre ORDER BY id";
    $result = mysql_query($query) or die('Query failed: ' . mysql_error());
   	while ($line = mysql_fetch_assoc($result)) {

		if (strlen($line['id']) == 1) { 
			foreach ($line as $key => $col_value) {
					$arr[$i][$key] = htmlspecialchars($col_value);
			}
		$i++;
		$j = 0;		
		}
		if (strlen($line['id']) == 2) { 
			foreach ($line as $key => $col_value) {
					$arr[$i-1]['subgenres'][$j][$key] = htmlspecialchars($col_value);
			}
		$j++;
		$k = 0;	
		}
		if (strlen($line['id']) == 3) { 
			foreach ($line as $key => $col_value) {
					$arr[$i-1]['subgenres'][$j-1]['subgenres'][$k][$key] = htmlspecialchars($col_value);
			}
		$k++;
		$m = 0;	
		}
		if (strlen($line['id']) == 4) { 
			foreach ($line as $key => $col_value) {
					$arr[$i-1]['subgenres'][$j-1]['subgenres'][$k-1]['subgenres'][$m][$key] = htmlspecialchars($col_value);
			}
		$m++;		
		}
	}
	return $arr;
}

function get_genres_old() {
    $i = 0;
	$query = "SELECT id,genre,id3genre FROM genre ORDER BY genre";
    $result = mysql_query($query) or die('Query failed: ' . mysql_error());
   	while ($line = mysql_fetch_assoc($result)) {
		foreach ($line as $key => $col_value) {
				$arr[$i][$key] = htmlspecialchars($col_value); 
		}
		$i++;
	}
	return $arr;
}

/****************************************
/* Get Rating

   get rating
*/
function get_rating() {
		$arr["0"]["id"] = "0";
		$arr["0"]["rating"] = "-"; 
		$arr["1"]["id"] = "1"; 
		$arr["1"]["rating"] = "0"; 
		$arr["2"]["id"] = "2"; 
		$arr["2"]["rating"] = "+"; 
		$arr["3"]["id"] = "3"; 
		$arr["3"]["rating"] = "++"; 
	return $arr;
}

/****************************************
/* Get Quality

   get quality
*/
function get_quality() {
		$arr["0"]["id"] = "0";
		$arr["0"]["quality"] = "ok"; 
		$arr["1"]["id"] = "1"; 
		$arr["1"]["quality"] = "noisy"; 
		$arr["2"]["id"] = "2"; 
		$arr["2"]["quality"] = "damaged"; 
	return $arr;
}

/****************************************
/* Get Source

   get source
*/
function get_source() {
    $i = 0;
	$query = "SELECT id,source FROM source";
    $result = mysql_query($query) or die('Query failed: ' . mysql_error());
   	while ($line = mysql_fetch_assoc($result)) {
		foreach ($line as $key => $col_value) {
				$arr[$i][$key] = htmlspecialchars($col_value); 
		}
		$i++;
	}
	return $arr;
}
?>