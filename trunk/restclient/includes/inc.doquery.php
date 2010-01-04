<?php
require_once "../includes/inc.includes.php";   // Include necessary files

// set trackrow parameters (to be improved! OOP!)
$trackrow_param->artist = TRUE;
$trackrow_param->title = TRUE;
$trackrow_param->length = TRUE;
$trackrow_param->play = TRUE;
$trackrow_param->edit = TRUE;

$input = file_get_contents("php://input");
$tracklist = json_decode(send_query($input));
write_tracklist($tracklist,null,$level1);
?>