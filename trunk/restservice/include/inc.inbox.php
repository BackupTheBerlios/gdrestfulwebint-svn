<?php
/********************/
/* Inbox            */
/* inbox.php  		*/
/********************/

/****************************************
/* Get Inbox Directories and files

   read inbox and return array
*/
function read_inbox() {
	global $inboxdir;
	$extensions = array ("mp3");
	// scan album dir
	$i = $j = 0;
	if ($dir = opendir($inboxdir)) {
		while (false !== ($file = readdir($dir))) {
			if (is_dir($inboxdir.$file) && (($file) == "albums")) {
				if ($dir = opendir($inboxdir.'albums/')) {
					while (false !== ($file = readdir($dir))) {
						if (is_dir($inboxdir.'albums/'.$file) && ($file !== ".") && ($file !== "..")) {
							$albarr[] = $file;
						}
					}
					asort($albarr);  // sort albums
					foreach ($albarr as $file) {
						$arr['albums'][$j]['name'] = $file;
						$arr['albums'][$j]['dir'] = $inboxdir.'albums/'.$file.'/';
						if ($dir2 = opendir($inboxdir.'albums/'.$file)) {
							$i = 0;
							while (false !== ($file2 = readdir($dir2))) {
								if (is_file($inboxdir.'albums/'.$file.'/'.$file2) && (in_array(substr($file2, -3, 3),$extensions))) {
									$singarr[] = $inboxdir.'albums/'.$file.'/'.$file2;
								}
							}
							if (is_array($singarr)) {
								asort($singarr);  // sort albums
								foreach ($singarr as $file) {
									$arr['albums'][$j]['tracks'][$i] = $file;
									$i++;
								}
							}
							unset($singarr);
							$arr['albums'][$j]['length'] = $i;
						}
						$j++;
					}
					unset($albarr);
				}
			$arr['albums']['length'] = $j;
			}
		}
		closedir($dir);
    }

	// scan single files
    $i = 0;
    if ($dir = opendir($inboxdir)) {
		while (false !== ($file = readdir($dir))) {
			if (is_file($inboxdir.$file) && (in_array(substr($file, -3, 3),$extensions))) {
				$singarr[] = $inboxdir.$file;
			}
		}
		asort($singarr); // sort files
		foreach ($singarr as $file) {
				$arr['singles'][$i] = $file;
				$i++;
		}
		unset($singarr);
		$arr['singles']['dir'] = $inboxdir;
		$arr['singles']['length'] = $i;
		closedir($dir);
    }
	return $arr;
}
?>