<?php

/**
 * TITRE : VoyageMémo
 * Auteur : Guilain Fernandez
 * Description : on va controller si toutes les informations entré par l'utilisateur son bonne et après on créer son compte
 * Date : 25/04/2024
 */

// Déclaration des variables
$login = "";
$password = "";
$passwordValidate = "";

if (isset($_POST['newAccount'])) {
    // Récupération des données du formulaire
    $login = filter_input(INPUT_POST, 'login', FILTER_DEFAULT);
    $password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);
    $passwordValidate = filter_input(INPUT_POST, 'validatePsw', FILTER_DEFAULT);

    // Vérification que les champs ne sont pas vides
    if ($login != "" && $password != "" && $passwordValidate != "") {
        // Vérification de la concordance des mots de passe
        if (passwordCompare($password, $passwordValidate)) {
            // Vérification de la longueur du mot de passe
            if (passwordLenght($password)) {
                // Vérification si l'utilisateur existe déjà
                if (!verifyUserExiste($login)) {
                    // Création de l'utilisateur
                    if (createUser($login, $password)) {
                        $error = "<div class='bg-success text-white text-center'>Le compte a été créé.</div>";
                    } else {
                        $error = "<div class='bg-danger text-white text-center'>Une erreur est survenue et le compte n'a pas pu être créé.</div>";
                    }
                } else {
                    $error = "<div class='bg-danger text-white text-center'>Le login existe déjà.</div>";
                }
            } else {
                $error = "<div class='bg-danger text-white text-center'>Le mot de passe est trop court.</div>";
            }
        } else {
            $error = "<div class='bg-danger text-white text-center'>Les mots de passe ne répondent pas.</div>";
        }
    } else {
        $error = "<div class='bg-danger text-white text-center'>Les champs n'ont pas été remplis.</div>";
    }
}
