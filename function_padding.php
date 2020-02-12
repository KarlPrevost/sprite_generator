<?php
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

$combined = $im->montageImage(new ImagickDraw(),colonexcolone, taillextaille+padding+padding, 0, '0');
?>