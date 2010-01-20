<?php
require_once "../includes/inc.includes.php";   // Include necessary files
$search = $_GET['search'];
$value = $_GET['value'];

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
	foreach($results[$search] as $res){
		if(substr(strtolower($res),0,$len)===$txt)$found[]=addslashes($res);
	}
}

echo '{ "results" : [ ';
if(count($found))echo '"'.join('" , "',$found).'"';
echo ' ] }';
?>