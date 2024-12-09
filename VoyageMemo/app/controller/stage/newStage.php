<?php

/**
 * TITRE : VoyageMémo
 * Auteur : Guilain Fernandez
 * Description : Ce script permet de contrôler les informations entrées par l'utilisateur et d'ajouter une nouvelle étape au voyage sélectionné.
 * Date : 29/04/2024
 */

// Déclaration des variables
$stageTitle = "";
$stageDescription = "";
$startDate = "00-00-0000";
$endDate = "00-00-0000";
$activity = [];
$uploadOk = 1;
$destinationMediaVignette = "././public/img/uploads/vignettes/"; //chemin du dossier où l'on va stocker les vignettes
$destinationMediaHd = "././public/img/uploads/hd/"; //chemin du dossier où l'on va stocker les images haute définition
$destinationMediaTemp = "././public/img/uploads/temp/"; //chemin du dossier où l'on va stocker les images temporaires
$todayDate = date("Y-m-d"); // la date du jour
$ratio = 4 / 3;
$numberDisplay = 0;

if (isset($_POST['newStage'])) {
    EDatabase::beginTransaction(); //début de la transaction

    $stageTitle = filter_input(INPUT_POST, 'stageTitle', FILTER_DEFAULT);
    $stageDescription = filter_input(INPUT_POST, 'stageDescription', FILTER_DEFAULT);
    $startDate = filter_input(INPUT_POST, 'startDate', FILTER_DEFAULT);
    $endDate = filter_input(INPUT_POST, 'endDate', FILTER_DEFAULT);
    $activity = $_POST['activity'];
    $media = $_FILES['stageMedia'];
    $travelId = filter_input(INPUT_POST, 'travelId', FILTER_DEFAULT);

    // Vérification que les champs ne sont pas vides
    if ($stageTitle != "" && $travelId != "") {
        if ($startDate != null && $endDate != null) {
            if ($activity != null) {
                $startDateTime = new DateTime($startDate);
                $endDateTime = new DateTime($endDate);
                if ($startDate <= $endDate) {
                    $durationDays = $endDateTime->diff($startDateTime);
                    createStage($stageTitle, $startDate, $durationDays->days, $stageDescription, $travelId);
                    $lastid = Edatabase::lastInsertId();

                    $travel = getTravelById($travelId);
                    foreach ($travel as $key => $travelValue) {
                        $numberDisplay += $travelValue->numberDisplay;
                    }
                    $numberActivity = $numberDisplay + count($activity);
                    travelNumberDisplayUpdate($travelId, $numberActivity);
                    if ($numberActivity != 0) {
                        foreach ($activity as $key => $value) {
                            addActivity($lastid, $value);
                        }
                    }
                    try {
                        foreach ($media['error'] as $key => $v) {
                            if ($media['error'][$key] != 0) {
                                throw new Exception("file error", 1);
                                die;
                            }
                            $uniqid = uniqid(); //nom unique pour l'image
                            $imageFileType = strtolower(pathinfo($media["name"][$key], PATHINFO_EXTENSION)); //récupère l'extention du fichier
                            $media["name"][$key] = $uniqid . "." . $imageFileType; // renomme l'image avec l'uniqid
                            $nameVignette = basename($uniqid . "_vignette." . $imageFileType);
                            $nameHd = basename($uniqid . "_hd." . $imageFileType);
                            $saveFileVignette = $destinationMediaVignette . $nameVignette;
                            $saveFileHd = $destinationMediaHd . $nameHd;

                            // Récupère les dimensions originales de l'image
                            $originalDimension = getimagesize($media["tmp_name"][$key]);
                            $originalWidth = $originalDimension[0];
                            $originalHeight = $originalDimension[1];

                            if ($originalDimension !== false) {
                                $uploadOk = 1;
                            } else {
                                $uploadOk = 0;
                            }

                            // Vérifie si le fichier a été téléchargé avec succès et déplacé vers le dossier de destination
                            if (move_uploaded_file($media["tmp_name"][$key], $destinationMediaTemp . $media["name"][$key])) {
                                // Redimensionne l'image pour créer une vignette et une version HD
                                $vignette = resize($destinationMediaTemp . $media["name"][$key], 100, 75);
                                $hd = resize($destinationMediaTemp . $media["name"][$key], 640, 480);

                                // Vérifie si l'image respecte le ratio 4:3 ou 3:4
                                if (($originalHeight / $originalWidth) == $ratio || ($originalWidth / $originalHeight) == $ratio) {
                                    // Enregistre l'image redimensionnée au format JPEG
                                    imagejpeg($vignette, $saveFileVignette, 80);
                                    imagejpeg($hd, $saveFileHd, 100);

                                    // Libère la mémoire des nouvelles images
                                    imagedestroy($vignette); 
                                    imagedestroy($hd);
                                } else {
                                    // Si l'image ne respecte pas le ratio attendu, la découpe pour obtenir une vignette et une version HD
                                    pictureTrim($vignette, $saveFileVignette, 100, 75);
                                    pictureTrim($hd, $saveFileHd, 640, 480);
                                }

                                // Supprime le fichier temporaire téléchargé
                                unlink($destinationMediaTemp . $media["name"][$key]);
                                
                                // Crée une nouvelle entrée pour le média dans la base de données
                                if (!createMedia($todayDate, $nameVignette, $nameHd, $lastid)) {
                                    $uploadOk = 0;
                                }
                            } else {
                                $uploadOk = 0;
                            }
                        }
                        if ($uploadOk == 0) {
                            EDatabase::rollBack();
                            $error = "<div class='bg-danger text-white text-center'>Une erreur est survenue.</div>";
                        } else {
                            EDatabase::commit();
                            $error = "<div class='bg-success text-white text-center'>L'étape a été créée.</div>";
                        }
                    } catch (\Throwable $th) {
                        $error = "<div class='bg-danger text-white text-center'>Une erreur est survenue.</div>";
                    }
                } else {
                    $error = "<div class='bg-danger text-white text-center'>La date de fin est antérieure à la date de début.</div>";
                }
            } else {
                $error = "<div class='bg-danger text-white text-center'>Aucune activité sélectionnée.</div>";
            }
        } else {
            $error = "<div class='bg-danger text-white text-center'>Les champs dates sont vides.</div>";
        }
    } else {
        $error = "<div class='bg-danger text-white text-center'>L'étape n'a pas de titre.</div>";
    }
}
