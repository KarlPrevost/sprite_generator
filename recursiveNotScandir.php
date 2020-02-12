<?php
function recursiveNotScandir($dir, $sortorder = 0) {
   $files = [];
   if(is_dir($dir)) {
	   $dirlist = opendir($dir);
	   while(($file = readdir($dirlist)) !== false) {
		   if(!is_dir($dir. "/" .$file)) {
			 array_push($files, $dir. "/" .$file);
		   } else if($file !== "." && $file !== "..") {
			 $files = array_merge($files, recursiveNotScandir($dir. "/" .$file));
		   }
	   }
		($sortorder == 0) ? asort($files) : rsort($files);
		
	   return $files;
   } else {
  	 return FALSE;
   }
}

var_dump(recursiveNotScandir("images_png"));
?>