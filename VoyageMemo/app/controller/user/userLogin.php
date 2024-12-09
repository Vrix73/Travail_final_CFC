<?php

/**
 * TITRE : VoyageMémo
 * Auteur : Guilain Fernandez
 * Description : on vérifie si l'utilisateur entre des données existante
 * Date : 25/04/2024
 */

// Déclaration des variables
$login = "";
$password = "";

if (isset($_POST['login'])) {
    // Récupération des données du formulaire
    $login = filter_input(INPUT_POST, 'userLogin', FILTER_DEFAULT);
    $password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);

    // Vérification que les champs ne sont pas vides
    if ($login != "" && $password != "") {
        // Vérifie si l'utilisateur existe dans la base de données
        if (getUser($login)) {
            $user = getUser($login);
            // Vérifie si le mot de passe est correct
            if (password_verify($password, $user[0]->password)) {
                $_SESSION["userLogin"] = $user[0]->login;
                $_SESSION["userId"] = $user[0]->id;
                // Redirige l'utilisateur vers la page de profil
                header('Location: index.php?page=profil');
            }
        } else {
            $error = "<div class='bg-danger text-white text-center'>L'utilisateur n'existe pas.</div>";
        }
    } else {
        $error = "<div class='bg-danger text-white text-center'>Les champs n'ont pas été remplis.</div>";
    }
}
