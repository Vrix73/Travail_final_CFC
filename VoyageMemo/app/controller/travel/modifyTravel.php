<?php
/*
    TITRE : VoyageMémo
    Auteur : Guilain Fernandez
    Description : la page qui permet de modifier un voyage
    Date : 24/04/2024
*/

if (isset($_POST['modifyTravel'])) {
    $travelTitle = filter_input(INPUT_POST, 'travelTitle', FILTER_DEFAULT);
    $travelId = filter_input(INPUT_POST, 'travelId', FILTER_DEFAULT);

    // Vérification que les champs ne sont pas vides
    if ($travelTitle != "") {
        // Met à jour le titre du voyage dans la base de données
        if (travelUpdate($travelId, $travelTitle)) {
            $error = "<div class='bg-success text-white text-center'>Le voyage a bien été modifié.</div>";
        } else {
            $error = "<div class='bg-danger text-white text-center'>Une erreur est survenue et le voyage n'a pas pu être créé.</div>";
        }
    } else {
        $error = "<div class='bg-danger text-white text-center'>Les champs n'ont pas été remplis.</div>";
    }
}