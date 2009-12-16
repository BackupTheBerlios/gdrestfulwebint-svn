<?php

/********************************************************************************************************
// Render Select-Options in import-tables
// get infos from rest-webservice
//
*********************************/

// Get Languages
function get_language_select_options($default="") {
	$str = "";
	$json = json_decode(get_languages());
	$str .= '<option value="">wie Album</option>';
	$str .= "\n";
	foreach ($json as $language) {
		$str .= '<option value="'.$language->id.'" ';
		if ((isset($default)) && (string)$language->id == $default ) { $str .= "selected"; }
		$str .= ' >';
		$str .= $language->language.'</option>';
		$str .= "\n";
	}
	return $str;
}

// Get Musictypes
function get_type_select_options($default="1") {
	$str = "";
	$json = json_decode(get_types());
	$str .= '<option value="">wie Album</option>';
	$str .= '<option value="1" ';
	if ( $default == "1") { $str .= "selected "; }
	$str .= '>bitte w&auml;hlen</option>';
	$str .= "\n";
	foreach ($json as $type) {
		$str .= '<option value="'.$type->id.'" ';
		if ((string)$type->id == $default ) { $str .= "selected"; }
		$str .= ' >';
		$str .= $type->musictype.'</option>';
		$str .= "\n";
	}
	return $str;
}

// Get Sources
function get_source_select_options($default="0") {
	$str = "";
	$json = json_decode(get_source());
	$str .= '<option value="">wie Album</option>';
	$str .= '<option value="0" ';
	if ( $default == "0") { $str .= "selected "; }
	$str .= '>bitte w&auml;hlen</option>';
	$str .= "\n";
	foreach ($json as $source) {
		$str .= '<option value="'.$source->id.'" ';
		if ((string)$source->id == $default ) { $str .= "selected"; }
		$str .= ' >';
		$str .= $source->source.'</option>';
		$str .= "\n";
	}
	return $str;
}

// Get Ratings
function get_rating_select_options($default="0") {
	$str = "";
	$json = json_decode(get_rating());
	$str .= '<option value="">wie Album</option>';
	$str .= "\n";
	foreach ($json as $rating) {
		$str .= '<option value="'.$rating->id.'" ';
		if ((string)$rating->id == $default ) { $str .= "selected"; }
		$str .= ' >';
		$str .= $rating->rating.'</option>';
		$str .= "\n";
	}
	return $str;
}

// Get Quality
function get_quality_select_options($default="0") {
	$str = "";
	$json = json_decode(get_quality());
	$str .= '<option value="">wie Album</option>';
	$str .= "\n";
	foreach ($json as $quality) {
		$str .= '<option value="'.$quality->id.'" ';
		if ((string)$quality->id == $default ) { $str .= "selected"; }
		$str .= ' >';
		$str .= $quality->quality.'</option>';
		$str .= "\n";
	}
	return $str;
}

// Get Genres
function get_genres_select_options($default="bm") {
	$str = "";
	$json = json_decode(get_genres());
	$str .= '<option value="">wie Album</option>';
	$str .= "\n";
	foreach ($json as $genres) {
		$str .= '<option value="'.$genres->id.'" ';
		if ((string)$genres->id == $default ) { $str .= "selected"; }
		$str .= ' >';
		$str .= $genres->genre.'</option>';
		$str .= "\n";
	}
	return $str;
}

function print_time_string($seconds) {
	return floor($seconds/60).":".sprintf("%02d",($seconds%60));
}
?>
