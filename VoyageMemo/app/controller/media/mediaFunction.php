<?php

/**
 * TITRE : VoyageMémo
 * Auteur : Guilain Fernandez
 * Description : la fonction qui vas créer une image hd a partir d'une image de base
 * Date : 29/04/2024
 */


/**
 * Redimensionne une image aux dimensions spécifiées tout en conservant son ratio d'aspect.
 * 
 * @param string $originalpicture Le chemin de l'image source à redimensionner.
 * @param int $width La largeur souhaitée de l'image redimensionnée.
 * @param int $height La hauteur souhaitée de l'image redimensionnée.
 * @return GdImage|bool Retourne une ressource image de l'image redimensionnée ou false en cas d'erreur.
 */
function resize($originalpicture, $width, $height)
{
    // Crée une image depuis l'image source
    $sourceImage = imagecreatefromstring(file_get_contents($originalpicture));

    // Obtient la largeur te la hauteur de l'image source
    $sourceWidth = imagesx($sourceImage);
    $sourceHeight = imagesy($sourceImage);

    // Calcule les nouvelles dimensions tout en conservant le ratio d'aspect de l'image source
    if ($sourceWidth > $sourceHeight) {
        $ratioH = $sourceHeight / $height;
        $newWidth = $sourceWidth / $ratioH;
        $newHeight = $height;
    } elseif ($sourceWidth < $sourceHeight) {
        $ratioW = $sourceWidth / $width;
        $newHeight = $sourceHeight / $ratioW;
        $newWidth = $width;
    } elseif ($sourceWidth < $width) {
        $ratioW = $width / $sourceWidth;
        $newHeight = $sourceHeight * $ratioW;
        $newWidth = $width;
    } elseif ($sourceHeight < $height) {
        $ratioH = $height / $sourceHeight;
        $newWidth = $sourceWidth * $ratioH;
        $newHeight = $height;
    }

    // Convertit les nouvelles dimensions en entiers
    $newWidth = intval($newWidth);
    $newHeight = intval($newHeight);

    // Crée une image vide avec les nouvelles dimensions
    $newImage = imagecreatetruecolor($newWidth, $newHeight);

    // Copie et redimensionne l'image source vers la nouvelle image
    imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);

    // Libère la mémoire de l'image source
    imagedestroy($sourceImage);

    // Retourne l'image redimensionnée
    return $newImage;
}

/**
 * Découpe une image centrale selon les dimensions spécifiées et enregistre le résultat dans un fichier.
 *
 * @param  mixed $sourceImage La ressource de l'image source à découper.
 * @param  string $destinationPath Le chemin de destination où enregistrer l'image découpée.
 * @param  int $width La largeur souhaitée de l'image découpée.
 * @param  int $height La hauteur souhaitée de l'image découpée.
 * @return void
 */
function pictureTrim($sourceImage, $destinationPath, $width, $height)
{
    // Obtient les dimensions de l'image source
    $sourceWidth = imagesx($sourceImage);
    $sourceHeight = imagesy($sourceImage);

    if ($sourceWidth > $sourceHeight) {
        // largeur
        $cut = ($sourceWidth - $width) / 2;
        $cut = intval($cut);
        $newImage = imagecreatetruecolor($width, $sourceHeight); // Crée une nouvelle image vide
        $srcX = $cut;
        $srcY = 0;
    } else {
        // hateur
        $cut = ($sourceHeight - $height) / 2;
        $cut = intval($cut);
        $newImage = imagecreatetruecolor($sourceWidth, $height); // Crée une nouvelle image vide
        $srcX = 0;
        $srcY = $cut;
    }

    // Convertit les coordonnées en entiers
    $srcX = intval($srcX);
    $srcY = intval($srcY);

    // Copie et redimensionne la zone centrale de l'image source dans la nouvelle image
    imagecopyresampled($newImage, $sourceImage, 0, 0, $srcX, $srcY, $sourceWidth, $sourceHeight, $sourceWidth, $sourceHeight); // Crée une band en copiant la partie centrale de l'image

    // Enregistre la nouvelle image découpée au format JPEG avec une qualité de 100
    imagejpeg($newImage, $destinationPath, 100);

    // Libère la mémoire de l'image source et de l'image découpée
    imagedestroy($sourceImage);
    imagedestroy($newImage);
}
