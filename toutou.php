<?php
    list($varW, $varH) = getimagesize("sprite.png");
    $varH1 = intval($varH, 10);
    $varH2 = (int)$varH;
    $imageWidthInt2 = 12;
    if(is_int($varH)==true){
        echo $varH. " Is int.\n";
    }else { echo "non\n";
    }
?>