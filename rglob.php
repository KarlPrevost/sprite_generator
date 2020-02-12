<?php
function rglob($pattern, $flags = 0) {
	$files = glob($pattern, $flags);
	foreach (glob(dirname($pattern. "/*"), GLOB_ONLYDIR|GLOB_NOSORT) as $dir) { 
		$files = array_merge($files, rglob($dir.'/'.basename($pattern), $flags));
	}
$files2=[];
	foreach ($files as $path){
		$ext = substr($path, -4);
		if(preg_match("/.png/i",$ext)){
		array_push($files2, $path);
		} 
	}
	return $files2;
}
var_dump(rglob("images_png/*"));
?>