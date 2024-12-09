<!--
    TITRE : VoyageMémo
    Auteur : Guilain Fernandez
    Description : la page des détails des voyages
    Date : 24/04/2024
-->

<header>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php?page=home">
                <img class="img-fluid" src="./public/img/logo_voyagememo.png" alt="">
            </a>
            <ul class="navbar-nav">
                <li><a class="nav-link fs-3" href="index.php?page=home">Accueil</a></li>
            </ul>
            <div class="dropdown">
                <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="./public/img/utilisateur.png" alt="">
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <?php
                    if ($_SESSION['userLogin'] != "") {
                    ?>
                        <li><em><?= $_SESSION['userLogin'] ?></em></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="nav-link link-primary" href="index.php?page=profil">Profil</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="" method="post">
                                <input class="btn btn-primary" type="submit" name="disconnect" value="Déconnexion">
                            </form>
                        </li>
                    <?php
                    } else {
                    ?>
                        <li><a class="nav-link link-primary" href="index.php?page=loginUser">Connexion</a></li>
                        <li><a class="nav-link link-primary" href="index.php?page=newUserAccount">Créer un compte</a></li>
                    <?php
                    }
                    ?>

                </ul>
            </div>
        </div>
    </nav>
</header>
<main class="mt-5 d-flex justify-content-center">
    <div class="container m-0 p-0">
        <?php
        // Récupération du voyage selectionné
        $travel = getTravelById($_GET["id"]);
        if ($_SESSION['userId'] == $travel[0]->userId) {
        ?>
            <div class="position-relative d-flex justify-content-evenly">
                <a class="btn btn-primary" href="index.php?page=newStage&id=<?= $travel[0]->id ?>">Nouvelle étape</a>
                <a class="btn btn-primary" href="index.php?page=modifyTravel&id=<?= $travel[0]->id ?>">Modifier voyage</a>
                <form action="" method="post">
                    <input type="hidden" name="travelId" value="<?= $_GET["id"] ?>">
                    <button type="submit" name="deleteTravel" class="btn btn-primary">Supprimer voyage</button>
                </form>
            </div>
        <?php
        }
        ?>
        <h1 class="text-center"><?= $travel[0]->title ?></h1>
        <?php
        // Récupération des étapes du voyage
        $travelStage = getStageByTravelId($travel[0]->id);

        $travelStartDate = new DateTime($travelStage[0]->date);
        $travelEndDate = new DateTime("0000-00-00");
        foreach ($travelStage as $key => $value) {
            $travelDuration += $value->durationDays;
        }

        $travelDurationInterval = new DateInterval("P" . $travelDuration . "D");
        $startDateFormat = $travelStartDate->format("d/m/Y");
        $travelEndDate = $travelStartDate->add($travelDurationInterval);
        $endDateFormat = $travelEndDate->format("d/m/Y");
        ?>
        <div class="text-center"><?= $startDateFormat . " - " . $endDateFormat ?></div>
        <?php
        foreach ($travelStage as $stageKey => $stageValue) {
            // Récupération des activités et des médias des étapes
            $stageActivity = getActivityByStageId($stageValue->id);
            $stageMedia = getMediaByStageId($stageValue->id);

            if ($stageValue->durationDays == "") {
                $stageDuration = "1";
            } else {
                $stageDuration = $stageValue->durationDays;
            }
            $date = new DateTime($stageValue->date);
            $date = $date->format("d/m/Y");

        ?>
            <div class="card mt-5">
                <div class="card-body">
                    <h3 class="card-title text-center"><?= $stageValue->title ?></h3>
                    <p class="card-text text-center m-0"><em><?= $date ?></em></p>
                    <p class="card-text text-center"><em>(<?= $stageDuration ?> jours)</em></p>
                    <p class="card-text text-center"><?= $stageValue->description ?></p>
                    <h5 class="card-text fw-bold text-center">Activités</h5>
                    <div class="d-flex flex-row justify-content-evenly">
                        <?php
                        foreach ($stageActivity as $key => $stageActivityValue) {
                        ?>
                            <p class="card-text">
                                <?= $stageActivityValue->name ?>
                            </p>
                        <?php
                        }
                        ?>
                    </div>
                    <h5 class="card-text fw-bold text-center">Images</h5>
                    <?php
                    // Vérifie si un formulaire a été soumis
                    if (isset($_POST['swap'])) {
                        // Récupère l'ID de l'image cliquée à partir des données du formulaire
                        $clickedImageID = $_POST['swap'];

                        // Vérifie l'ID de l'image cliquée pour déterminer si elle doit être affichée en grand ou en petit
                        foreach ($stageMedia as $keyMedia => $valueMedia) {
                            if ($valueMedia->id == $clickedImageID) {
                                // Si l'ID de l'image correspond à celle cliquée, inverse le statut d'affichage
                                $valueMedia->isBig = !$valueMedia->isBig;
                            }
                        }
                    }

                    // Affiche la liste des images avec leurs boutons pour afficher en grand
                    ?>
                    <div class="row">
                        <?php foreach ($stageMedia as $keyMedia => $valueMedia) { ?>
                            <div class="col-md-3 position-relative"> <!-- Ajout de la classe position-relative pour positionner l'image cliquée -->
                                <form action="" method="post">
                                    <input type="hidden" name="swap" value="<?= $valueMedia->id ?>">
                                    <button class="btn" type="submit">
                                        <img src="<?= $valueMedia->isBig ? './public/img/uploads/hd/' . $valueMedia->filenameHd : './public/img/uploads/vignettes/' . $valueMedia->filenameVignette ?>" alt="" class="img-fluid">
                                    </button>
                                </form>
                                <?php if ($valueMedia->isBig) { ?>
                                    <div class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 10;"> <!-- Utilisation de z-index pour afficher l'image en grand au-dessus des autres -->
                                        <img src="./public/img/uploads/hd/<?= $valueMedia->filenameHd ?>" alt="" class="img-fluid">
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <?php
                    if ($_SESSION['userId'] == $travel[0]->userId) {
                    ?>
                        <div class="mt-3 text-end d-flex justify-content-end">
                            <a class="btn btn-primary me-2" href="index.php?page=modifyStage&id=<?= $travelStage[0]->id ?>">Modifier</a>
                            <form action="" method="post">
                                <input type="hidden" name="stageId" value="<?= $stageValue->id ?>">
                                <button type="submit" name="deleteStage" class="btn btn-primary">Supprimer</button>
                            </form>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
</main>