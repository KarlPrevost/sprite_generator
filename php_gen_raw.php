<?php
$argumentsArray = array_slice($argv, 1);
$directory = end($argumentsArray); 
$options = getopt("ri:s:p:o:c:", [
                                    "recursive",
                                    "output-image:",
                                    "output-style:",
                                    "padding:",
                                    "override-size:",
                                    "columns_number:"
                                                    ]); 
function listeFichiers($dir){
    $files = glob($dir."/*.png");
    return $files;
}
function listeFichiersR($pattern, $flags = 0) {
	$files = glob($pattern, $flags);
	foreach (glob(dirname($pattern. "/*"), GLOB_ONLYDIR|GLOB_NOSORT) as $dir) { 
		$files = array_merge($files, listeFichiersR($dir.'/'.basename($pattern), $flags));
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
$optionR = false; $optionI = false; $optionS = false; $optionP = false; $optionO = false; $optionC = false;
$imageName = "sprite.png"; $styleSheetName = "style.css";
foreach($options as $optionKey => $optionValue){
    if($optionKey == "r" || $optionKey == "recursive"){
        $optionR = true;
    }
    if($optionKey == "i" || $optionKey == "output-image"){
        $optionI = true;
        if(is_string($optionValue) == true && $optionValue != "")
        $imageName = $optionValue;
    }
    if($optionKey == "s" || $optionKey == "output-style"){
        $optionS = true;
        if(is_string($optionValue) == true && $optionValue != "")
        $styleSheetName = $optionValue;
    }
    if($optionKey == "p" || $optionKey == "padding"){
        $optionP = true;
        if(is_string($optionValue) === true && $optionValue != "")
        $paddingNumber = $optionValue;
    }
    if($optionKey == "o" || $optionKey == "override-size"){
        $optionO = true;
        if(is_string($optionValue) === true && $optionValue != "")
        $imageSize = $optionValue;
    }
    if($optionKey == "c" || $optionKey == "columns_number"){
        $optionC = true;
        if(is_string($optionValue) === true && $optionValue != "")
        $columnsNumber = $optionValue;
    }
}
if($optionR == true){
$imageFiles = listeFichiersR($directory . "/*");
}else{
$imageFiles = listeFichiers($directory);
}
$im1 = new Imagick(); 
for ($i=0; $i< count($imageFiles); $i++){
    $fileInLoop = $imageFiles[$i];
    $im1->readImage($fileInLoop);
}
$im1->resetIterator();
$ima = $im1->appendImages(true);
$ima->setImageFormat("png");
    file_put_contents("$imageName",$ima);
$cssSheet = "";
foreach($imageFiles as $imagesListePourCss){
    if (end($imageFiles) != $imagesListePourCss){
        $cssSheet = $cssSheet.".".basename("$imagesListePourCss", ".png"). ", ";
    }else{
        $cssSheet = $cssSheet.".".basename("$imagesListePourCss", ".png")."\n";
    }
}
$cssSheet =  $cssSheet."{ display: block; background: url('/$imageName') no-repeat; overflow: hidden; text-indent: -9999px; text-align: left; }\n";
$imageHeightTemp = 0;
$firstImageFiles = $imageFiles[0];
$firstImageFiles = $imageFiles[0];

foreach($imageFiles as $imagesListePourCss){
    list($imageWidth, $imageHeight) = getimagesize("$imagesListePourCss");
    if($firstImageFiles == $imagesListePourCss){
    $cssSheet = $cssSheet .".". basename("$imagesListePourCss", ".png")." { background-position: -0px -0px; width: ".$imageWidth."px; height: ".$imageHeight."px; }\n"; 
    $imageOffset = $imageHeight;   
}else{
        $cssSheet = $cssSheet .".". basename("$imagesListePourCss", ".png")." { background-position: -0px -".$imageOffset."px; width: ".$imageWidth."px; height: ".$imageHeight."px; }\n";
        $imageOffset += $imageHeight;
    }
}
if(!file_exists("style")){
    mkdir("style");
}
$openFile = fopen("style/".$styleSheetName.".css", 'w+');
fwrite($openFile, $cssSheet);
fclose($openFile);
?>