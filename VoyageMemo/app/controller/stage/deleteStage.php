<?php

/**
 * TITRE : VoyageMémo
 * Auteur : Guilain Fernandez
 * Description : permet de supprimer une étape
 * Date : 02/05/2024
 */

// Déclaration des variables
$stageId = "";

if (isset($_POST['deleteStage'])) {
    $stageId = filter_input(INPUT_POST, 'stageId', FILTER_DEFAULT);

    // Pour chaque média associé à l'étape, supprime les fichiers de vignette et de haute définition
    foreach (getMediaByStageId($stageId) as $key => $value) {
        unlink("././public/img/uploads/vignettes/" . $value->filenameVignette);
        unlink("././public/img/uploads/hd/" . $value->filenameHd);
    }
    // Supprime l'étape de la base de données
    deleteStageById($stageId);
}
