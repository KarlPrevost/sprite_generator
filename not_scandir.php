<?php
function notScandir($dir, $sortorder = 0) {
   if(is_dir($dir))        {
	   $dirlist = opendir($dir);
	   while( ($file = readdir($dirlist)) !== false) {
		   if(!is_dir($file)) {
			   $files[] = $file;
		   }
	   }
	   ($sortorder == 0) ? asort($files) : rsort($files);
	   return $files;
   } else {
  	 return FALSE;
   }
}