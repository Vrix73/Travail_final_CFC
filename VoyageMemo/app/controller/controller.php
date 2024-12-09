<?php

/**
 * TITRE : VoyageMémo
 * Auteur : Guilain Fernandez
 * Description : le controller du projet
 * Date : 24/04/2024
 */

 // Définit le fuseau horaire par défaut à utiliser.
date_default_timezone_set('UTC');

 //démarre une nouvelle session
session_start(); 

/* REQUIRES :*/
require_once './model/config/database.php';
require_once './model/usersClass.php';
require_once './model/activityClass.php';
require_once './model/travelClass.php';
require_once './model/stageClass.php';
require_once './model/mediaClass.php';
require_once './model/stagesHasActivityClass.php';

/* Gestion des pages */
if (!isset($_GET['page']) || $_GET['page'] == "home") {
    require_once 'user/userDisconnection.php';
    require_once './view/home.php';
}
if (isset($_GET['page'])) {
    $name = filter_input(INPUT_GET, 'page');
    switch ($name) {
        case 'modifyStage':
            require_once 'media/mediaFunction.php';
            require_once 'stage/modifyStage.php';
            require_once './view/modifyStage.php';
            break;
        case 'newStage':
            require_once 'media/mediaFunction.php';
            require_once 'stage/newStage.php';
            require_once './view/newStage.php';
            break;
        case 'modifyTravel':
            require_once 'travel/modifyTravel.php';
            require_once './view/modifyTravel.php';
            break;
        case 'newTravel':
            require_once 'travel/newTravel.php';
            require_once './view/newTravel.php';
            break;
        case 'detailTravel':
            require_once 'user/userDisconnection.php';
            require_once 'travel/deleteTravel.php';
            require_once 'stage/deleteStage.php';
            require_once './view/detailTravel.php';
            break;
        case 'profil':
            require_once 'user/userDisconnection.php';
            require_once './view/profil.php';
            break;
        case 'newUserAccount':
            require_once 'user/userFunction.php';
            require_once 'user/newAccount.php';
            require_once './view/newUserAccount.php';
            break;
        case 'loginUser':
            require_once 'user/userLogin.php';
            require_once './view/loginUser.php';
            break;
        default:
            require_once 'user/userDisconnection.php';
            require_once './view/home.php';
            break;
    }
}
