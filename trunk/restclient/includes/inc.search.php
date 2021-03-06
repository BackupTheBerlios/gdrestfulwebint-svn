<?php
require_once "../includes/inc.includes.php";   // Include necessary files
$search = $_GET['search'];
$value = html_entity_decode(stripslashes($_GET['value']),ENT_COMPAT,"UTF-8");
$limit = 10;

if($search == "artist" ) {
	$json = json_decode(get_artists("tracks"));
	if ($json) {
		foreach ($json as $result) {
			$results['artist'][] = html_entity_decode($result->artist,ENT_COMPAT,"UTF-8");
		}
	}
}

if($search == "title" ) {
	$json = json_decode(get_tracks());
	if ($json) {
		foreach ($json as $result) {
			$results['title'][] = html_entity_decode($result->title,ENT_COMPAT,"UTF-8");
		}
	}
}

if($search == "album" ) {
	$json = json_decode(get_albums());
	if ($json) {
		foreach ($json as $result) {
			$results['albums'][] = html_entity_decode($result->title,ENT_COMPAT,"UTF-8");
		}
	}
}

$found=array();

if(isset($results[@$search])){
	$txt=strtolower(@$value);
	$len=strlen($txt);
	$i = 0;
	foreach($results[$search] as $res){
		if (isset($limit)) {
			if( (substr(strtolower($res),0,$len)===$txt) && ($i<$limit) ) {
				$found[]=htmlentities($res,ENT_COMPAT,"UTF-8");
				$i++;
			}
		} else {
			if(substr(strtolower($res),0,$len)===$txt)$found[]=addslashes($res);
		}
	}
	$found = array_values(array_unique($found));
}

echo '{ "results" : [ ';
if(count($found))echo '"'.join('" , "',$found).'"';
echo ' ] }';
?>