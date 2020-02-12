<?php
//Chaque vommentaire correspond aux lignes du dessous
//retourne les éléments passés en arguments arr
$argumentsArray = array_slice($argv, 1);
//retourne uniquement le dernier élément str
$directory = end($argumentsArray); 
// récupère les éléments passés en options sous forme de tableau avec 
// en index l'option et en valeur l'argument entré s'il existe 
$options = getopt("ri:s:p:o:c:", [
                                    "recursive",
                                    "output-image:",
                                    "output-style:",
                                    "padding:",
                                    "override-size:",
                                    "columns_number:"
                                                    ]); 
// liste les fichiers png du dossier specifié en argument
function listeFichiers($dir){
    $files = glob($dir."/*.png");
    return $files;
}
// liste tous les fichiers d'un dossier et de ses sous dossiers 
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
// verifier si un élément est présent dans un array
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
// echo $optionR . PHP_EOL . $imageName . PHP_EOL . $styleSheetName . PHP_EOL . $paddingNumber . PHP_EOL . $imageSize . PHP_EOL . $columnsNumber . PHP_EOL . $directory;
if($optionR == true){
$imageFiles = listeFichiersR($directory . "/*");
}else{
$imageFiles = listeFichiers($directory);
}
// fonction imagick creation d'un padding
function frameImage($imagePath, $color, $width, $height, $innerBevel, $outerBevel)
{
    $imagick = new \Imagick(realpath($imagePath));
    $width = $width + $innerBevel + $outerBevel;
    $height = $height + $innerBevel + $outerBevel;
 
    $imagick->frameimage(
        $color,
        $width,
        $height,
        $innerBevel,
        $outerBevel
    );
    header("Content-Type: image/jpg");
    echo $imagick->getImageBlob();
}
// imagick creation d'un sprite
$im1 = new Imagick(); 
for ($i=0; $i< count($imageFiles); $i++){
    $fileInLoop = $imageFiles[$i];
    $im1->readImage($fileInLoop);
}
$im1->resetIterator();
$ima = $im1->appendImages(true);
$ima->setImageFormat("png");
    file_put_contents("$imageName",$ima);
// creation des lignes de css 
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
// fclose("style/".$styleSheetName.".css");
fclose($openFile);
// ternaire pour tester si le fichier est correctement fermé
// echo '$openFile is resource = ' . (is_resource($openFile) ? 'true'."\n": 'false'."\n");
?>