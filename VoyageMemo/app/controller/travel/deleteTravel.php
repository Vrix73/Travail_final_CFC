<?php

/**
 * TITRE : VoyageMémo
 * Auteur : Guilain Fernandez
 * Description : Permet de supprimer un voyage
 * Date : 202/05/2024
 */

// Déclaration des variables
$travelId = "";

if (isset($_POST['deleteTravel'])) {
    $travelId = filter_input(INPUT_POST, 'travelId', FILTER_DEFAULT);

    // Parcourt tous les stages associés au voyage et supprime leurs médias
    foreach (getStageByTravelId($travelId) as $key => $stageValue) {
        foreach (getMediaByStageId($stageValue->id) as $key => $mediaValue) {
            unlink("././public/img/uploads/vignettes/" . $mediaValue->filenameVignette);
            unlink("././public/img/uploads/hd/" . $mediaValue->filenameHd);
        }
    }
    // Supprime le voyage de la base de données
    deleteTravelById($travelId);
    // Redirige vers la page de profil après la suppression
    header('Location: index.php?page=profil');
}
