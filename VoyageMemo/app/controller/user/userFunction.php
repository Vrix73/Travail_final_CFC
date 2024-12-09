<?php

/**
 * TITRE : VoyageMémo
 * Auteur : Guilain Fernandez
 * Description : la page où il y a toutes les fonction concernant l'utilisateur
 * Date : 25/04/2024
 */

 /**
 * Compare deux mots de passe pour vérifier s'ils sont identiques.
 *
 * @param string $password Le premier mot de passe à comparer.
 * @param string $passwordVerify Le deuxième mot de passe à comparer.
 * @return bool Retourne vrai si les deux mots de passe sont identiques, sinon faux.
 */
function passwordCompare($password, $passwordVerify)
{
    if ($password == $passwordVerify) {
        return true;
    } else {
        return false;
    }
}

/**
 * Vérifie si la longueur du mot de passe est d'au moins 8 caractères.
 *
 * @param string $password Le mot de passe à vérifier.
 * @return bool Retourne vrai si le mot de passe a une longueur d'au moins 8 caractères, sinon faux.
 */
function passwordLenght($password)
{
    $passwordSize = strlen($password);
    if ($passwordSize >= 8) {
        return true;
    } else {
        return false;
    }
}
