<?php

/**
 * TITRE : VoyageMémo
 * Auteur : Guilain Fernandez
 * Description : on va controller si toutes les informations entré par l'utilisateur son bonne et après on créer un voyage
 * Date : 29/04/2024
 */

// Déclaration des variables
$travelTitle = "";

if (isset($_POST['newTravel'])) {
    $travelTitle = filter_input(INPUT_POST, 'travelTitle', FILTER_DEFAULT);

    // Vérification que les champs ne sont pas vides
    if ($travelTitle != "") {
        $userCreator = getUser($_SESSION["userLogin"]);
        // Création du voyage dans la base de données
        if (createTravel($travelTitle, 0, $userCreator[0]->id)) {
            $error = "<div class='bg-success text-white text-center'>Le voyage a été créé</div>";
        } else {
            $error = "<div class='bg-danger'>Une erreur est survenue et le voyage n'a pas pu être créé</div>";
        }
    } else {
        $error = "<div class='bg-danger'>Les champs n'ont pas été remplis.</div>";
    }
}
