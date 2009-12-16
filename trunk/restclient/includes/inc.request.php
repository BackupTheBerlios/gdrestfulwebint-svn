<?php

/********************************************************************************************************
	Rest Requests
	send to $restservicedir

*********************************/

function send_request ($url,$method="get",$input=null) {
	global $restservicedir;
	$address = $restservicedir.$url;
	$client = new RESTClient();
	if ($method == "post") {
		$result = $client->post($address,$input);
	} elseif ($method == "get") {
		$result = $client->get($address,$input);
	} else {
		$result = "Error: no method specified";
	}
	return $result;
}

/*******************************
	Post Requests
*/

function send_tracks($input) { // send track records
	return(send_request('tracks.php','post',$input));
}

function send_albums($input) { // send album records
	return(send_request('albums.php','post',$input));
}

function send_query($input) { // send query
	return(send_request('query.php','post',$input));
}

/*******************************
	Get Requests
*/

function id3_info($input) { // get id3 infos
	$input=str_replace("/","___",$input);
	return(send_request('id3/'.urlencode($input),'get'));
}

function get_lasttnb() { // (get_new_albumid and) get_lasttnb
	return(send_request('infos.php/lasttnb','get'));
}

function get_inbox() { // get_inbox
	return(send_request('inbox.php','get'));
}

function get_languages() { // get_languages
	return(send_request('meta.php/languages','get'));
}

function get_types() { // get_types
	return(send_request('meta.php/types','get'));
}

function get_rating() { // get_rating
	return(send_request('meta.php/rating','get'));
}

function get_quality() { // get_quality
	return(send_request('meta.php/quality','get'));
}

function get_genres() { // get_genres
	return(send_request('meta.php/genres','get'));
}

function get_source() { // get_source
	return(send_request('meta.php/source','get'));
}

function get_artists($table,$letter) { // get different artists from table $table (tracks or album) equal to $letter if set (DISTINCT)
	if ($table == "albums") {
		return(send_request('albums.php/artists/'.$letter,'get'));
	}
	if ($table == "tracks") {
		return(send_request('tracks.php/artists/'.$letter,'get'));
	}
}

function get_tracks() { // get track list
	return(send_request('tracks.php/','get'));
}

function get_track($id) { // get track details
	return(send_request('tracks.php/'.$id,'get'));
}

function get_albums() { // get album list
	return(send_request('albums.php/','get'));
}

function get_album($id) { // get album details
	return(send_request('albums.php/'.$id,'get'));
}

function get_album_tracks($id) { // get album tracks
	return(send_request('albums.php/'.$id.'/tracks','get'));
}

function get_audio($trackid,$filename) { // get_artists
//	header("Content-Type: audio/mp3");
//	header("Content-Disposition: attachment; filename=\"".$filename."\"");
//	header("Content-Disposition: attachment; filename=\"test.mp3\"");
	echo(send_request('tracks.php/'.$trackid.'/audio','get'));
}

?>
