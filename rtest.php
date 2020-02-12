<?php
function listeFichiers($dir){
    $files = glob($dir."/*.png");
    return $files;
}
// var_dump(listeFichiers("images_png"));


// fonction principale a faire fonctionner
function rglob($pattern, $flags = 0) {
    $files = glob($pattern, $flags); 
    	foreach (glob($pattern.'/*.png', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
		$files = array_merge($files, rglob($dir.'/'.basename($pattern), $flags));
	}
	return $files;
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
function poglob($pattern, $flags = 0)
{
    $files = glob($pattern, $flags);
    foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir){
        $files = array_merge($files, poglob($dir.'/'.basename($pattern), $flags));
    }
    return $files;
}
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
function glob_recursive($pattern, $flags = 0)
{
    $files = glob($pattern, $flags);
    foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir){
        $files = array_merge($files, glob_recursive($dir.'/'.basename($pattern), $flags));
    }
    return $files;
}
// var_dump(glob_recursive("images_png"));
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
function recursiveGlob($dir, $ext) {
    $globFiles = glob("$dir/*.$ext");
    $globDirs  = glob("$dir/*", GLOB_ONLYDIR);

    foreach ($globDirs as $dir) {
        recursiveGlob($dir, $ext);
    }

    foreach ($globFiles as $file) {
        print "$file\n"; 
    }
}
function rrGlob($dir, $ext) {
    $globFiles = glob("$dir/*.$ext");
    $globDirs  = glob("$dir/*", GLOB_ONLYDIR);

    foreach ($globDirs as $dir) {
        rrGlob($dir, $ext);
    }
        foreach ($globFiles as $file) {
            print "$file\n"; 
        }
}
function lister($chemin,$extension)
{
 
    //nom du répertoire à lister
    $nom_repertoire = $chemin;
 
    //on ouvre un pointeur sur le repertoire
    $pointeur = opendir($nom_repertoire);
 
    //pour chaque fichier et dossier
    while ($fichier = readdir($pointeur))
    {
        //on ne traite pas les . et ..
        if(($fichier != '.') && ($fichier != '..') && ($fichier != '.DS_Store'))
        {
            //si c'est un dossier, on le lit
            if (is_dir($nom_repertoire.'/'.$fichier))
            {
                lister($nom_repertoire.'/'.$fichier,$extension);
            }
            else
            {
                //c'est un fichier, on l'affiche
                if(preg_match("#$extension#", "'.$fichier.'")){
                    echo "$fichier<br />";
                }
            }
        }
    }
 
    //fermeture du pointeur
    closedir($pointeur);
}
function listDossier($path){ //liste un dossier

    /* ------------------

            exemple d'utlisation

            $path = '.'; //On définit la racine

            $tableau_elements = $cnxFiles->listDossier($path); //Appel à notre fonction

            $file = fopen('./arborescence.txt', 'w+'); //On ouvre un fichier et on y copie le tableau

            fwrite($file, implode($tableau_elements, "\n"));

            fclose($file);

            ----- */

    global $cnxFiles;

    $tableau_elements = array(); //Déclare le tableau qui contiendra tous les éléments de nos dossiers

    $dir = opendir($path); //ouvre le dossier

    while (($element_dossier = readdir($dir)) !== FALSE){ //Pour chaque élément du dossier...

            if ($element_dossier != '.' && $element_dossier != '..' && is_dir($path.'/'.$element_dossier)){ //Si l'élément est lui-même un dossier (en excluant les dossiers parent et actuel), on appelle la fonction de listage en modifiant la racine du dossier à ouvrir
                    $tableau_elements = array_merge($tableau_elements, $cnxFiles->listDossier($path.'/'.$element_dossier)); //On fusionne ici le tableau grâce à la fonction array_merge. Au final, tous les résultats de nos appels récursifs à la fonction listage fusionneront dans le même tableau
            }
            elseif ($element_dossier != '.' && $element_dossier != '..'){ //Sinon, l'élément est un fichier : on l'enregistre dans le tableau
                    $tableau_elements[] = $path.'/'.$element_dossier;
            }
     }
     closedir($dir); //Ferme le dossier
     return $tableau_elements; //Retourne le tableau
}
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
function recursiveNotScandir($dir, $sortorder = 0) {
    $files = [];
    $ext = substr($file, -1, 4);
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
 // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
?>