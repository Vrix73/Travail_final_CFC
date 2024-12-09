<?php

/**
 * TITRE : VoyageMémo
 * Auteur : Guilain Fernandez
 * Description : Ce script permet de contrôler les informations entrées par l'utilisateur et de modifier l'étape sélectionnée du voyage en conséquence.
 * Date : 06/05/2024
 */

// Déclaration des variables
$stageTitle = ""; // Titre de l'étape
$stageDescription = ""; // Description de l'étape
$startDate = "00-00-0000"; // Date de début de l'étape
$endDate = "00-00-0000"; // Date de fin de l'étape
$activity = array(); // Tableau des activités associées à l'étape
$mediaEdit = array(); // Tableau des médias édités à supprimer
$media = array(); // Tableau des nouveaux médias à ajouter
$uploadOk = 1; // Indicateur pour le téléchargement de médias
$destinationMediaVignette = "././public/img/uploads/vignettes/"; // Chemin du dossier où seront stockées les vignettes
$destinationMediaHd = "././public/img/uploads/hd/"; // Chemin du dossier où seront stockées les images haute définition
$destinationMediaTemp = "././public/img/uploads/temp/"; // Chemin du dossier où seront stockées les images temporaires
$todayDate = date("Y-m-d"); // Date actuelle
$ratio = 4 / 3; // Ratio d'aspect des images (4:3)

// Vérification si le formulaire de modification d'étape a été soumis
if (isset($_POST['modifyStage'])) {
    EDatabase::beginTransaction(); // Début de la transaction

    // Récupération des données du formulaire
    $stageTitle = filter_input(INPUT_POST, 'stageTitle', FILTER_DEFAULT);
    $stageDescription = filter_input(INPUT_POST, 'stageDescription', FILTER_DEFAULT);
    $startDate = filter_input(INPUT_POST, 'startDate', FILTER_DEFAULT);
    $endDate = filter_input(INPUT_POST, 'endDate', FILTER_DEFAULT);
    $stageId = filter_input(INPUT_POST, 'stageId', FILTER_DEFAULT);
    $activity = $_POST['activity'];
    $media = $_FILES['stageMedia'];
    $mediaEdit = $_POST['media'];

    // Vérification que les champs obligatoires ne sont pas vides
    if ($stageTitle != "") {
        if ($startDate != null && $endDate != null) {
            if ($activity != null) {
                $startDateTime = new DateTime($startDate);
                $endDateTime = new DateTime($endDate);

                // récupère les données de l'étape
                $stage = getStageById($stageId);

                // Vérification que la date de début est antérieure ou égale à la date de fin
                if ($startDate <= $endDate) {
                    $durationDays = $endDateTime->diff($startDateTime);

                    // Mise à jour de l'étape du voyage dans la base de données
                    stageUpdate($stageId, $stageTitle, $startDate, $durationDays->days, $stageDescription);


                    // Récupération des activités associées à cette étape
                    $stageActivity = getActivityByStageId($stageId);

                    $travel = getTravelById($stage[0]->travelId);
                    $numberDisplay = $travel[0]->numberDisplay;
                    $numberActivity = ($numberDisplay - count($stageActivity)) + count($activity);
                    travelNumberDisplayUpdate($stage[0]->travelId, $numberActivity);

                    // Ajout des nouvelles activités sélectionnées par l'utilisateur
                    foreach ($activity as $key => $valueActivity) {
                        foreach ($stageActivity as $key => $valueStageActivity) {
                            if ($valueActivity != $valueStageActivity->id) {
                                $addActivity = true;
                            } else {
                                $addActivity = false;
                                break;
                            }
                        }
                        if ($addActivity == true) {
                            addActivity($stageId, $valueActivity);
                        }
                    }
                    // Suppression des activités qui ont été désélectionnées par l'utilisateur
                    foreach ($stageActivity as $key => $valueStageActivity) {
                        foreach ($activity as $key => $valueActivity) {
                            if ($valueStageActivity->id == $valueActivity) {
                                $activityDelete = false;
                                break;
                            } else {
                                $activityDelete = true;
                            }
                        }
                        if ($activityDelete == true) {
                            deleteStageActivityAssociationByStageIdAndActivityId($stageId, $valueStageActivity->id);
                        }
                    }

                    // Gestion des médias
                    if ($mediaEdit != null) {
                        // Suppression des médias édités et de leurs enregistrements dans la base de données
                        foreach ($mediaEdit as $key => $value) {
                            $deleteMedia = getMediaById($value);
                            if (deleteMediaById($value)) {
                                // Supprime les médias dans les fichiers
                                unlink($destinationMediaHd . $deleteMedia[0]->filenameHd);
                                unlink($destinationMediaVignette . $deleteMedia[0]->filenameVignette);
                            }
                        }
                    }

                    // Traitement des nouveaux médias ajoutés
                    try {
                        foreach ($media['error'] as $key => $v) {
                            if ($media['error'][$key] != 0) {
                                throw new Exception("file error", 1);
                                die;
                            }
                            $uniqid = uniqid(); // nom unique pour l'image
                            $imageFileType = strtolower(pathinfo($media["name"][$key], PATHINFO_EXTENSION)); // récupère l'extension du fichier
                            $media["name"][$key] = $uniqid . "." . $imageFileType; // renomme l'image avec l'uniqid
                            $nameVignette = basename($uniqid . "_vignette." . $imageFileType); // créer un nom pour la vignette avec l'uniqid
                            $nameHd = basename($uniqid . "_hd." . $imageFileType); // créer un nom pour l'image hd avec l'uniqid
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
                                if (!createMedia($todayDate, $nameVignette, $nameHd, $stageId)) {
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
                        EDatabase::commit();
                        $error = "<div class='bg-success text-white text-center'>Les modifications ont été enregistrées. (Aucun média n'a été ajouté).</div>";
                    }
                } else {
                    EDatabase::rollBack();
                    $error = "<div class='bg-danger text-white text-center'>La date de fin est antérieure à la date de début.</div>";
                }
            } else {
                $error = "<div class='bg-danger text-white text-center'>Il n'y a pas d'activités selectionné.</div>";
            }
        } else {
            $error = "<div class='bg-danger text-white text-center'>Les champs dates sont vides.</div>";
        }
    } else {
        EDatabase::rollBack();
        $error = "<div class='bg-danger text-white text-center'>L'étape n'a pas de titre.</div>";
    }
}
