<?php
function listeFichiers($dir){
    $files = glob($dir."/*.png");
    return $files;
}
?>