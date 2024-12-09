<?php

/**
 * TITRE : VoyageMémo
 * Auteur : Guilain Fernandez
 * Description : déconnecte l'utilisateur
 * Date : 25/04/2024
 */

if (isset($_POST["disconnect"])) {
    session_destroy();
    // header('index.php?page=home');
    // exit;
}
