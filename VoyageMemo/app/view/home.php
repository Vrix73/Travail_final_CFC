<!--
    TITRE : VoyageMémo
    Auteur : Guilain Fernandez
    Description : la page accueil du site web
    Date : 24/04/2024
-->


<header>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img class="object-fit-md-scale" src="./public/img/logo_voyagememo.png" alt="">
            </a>
            <ul class="navbar-nav">
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
<main class="m-3">
    <div class="container">
        <?php
        if ($_SESSION['userLogin'] != "") {
        ?>
            <div class="position-relative">
                    <a class="btn btn-primary position-absolute top-0 end-0" href="index.php?page=newTravel">Nouveau voyage</a>
            </div>
        <?php
        }
        ?>
        <div class="row">
            <?php
            // Récupération de tous les voyages
            $allTravel = getAllTravel();
            foreach ($allTravel as $travelKey => $travelValue) {
            ?>
                <div class="col-sm-12 col-md-4 col-lg-3">
                    <div class="card mt-5">
                        <div class="card-body">
                            <h4 class="card-title text-center"><?= $travelValue->title ?></h4>
                            <?php
                            // Récupération des étapes du voyage
                            $travelStage = getStageByTravelId($travelValue->id);
                            // réinitialise les variables a chaque nouveau voyage 
                            $stageMediaThumbnail = array();
                            $activity = "";
                            $travelDuration = 0;
                            $travelStartDate = new DateTime("0000-00-00");
                            $travelEndDate = new DateTime("0000-00-00");
                            foreach ($travelStage as $stageKey => $stageValue) {
                                // Récupération des activités et des médias des étapes
                                $stageActivity = getActivityByStageId($stageValue->id);
                                $stageMedia = getMediaByStageId($stageValue->id);
                                $travelDuration = $travelDuration += $stageValue->durationDays;

                                foreach ($stageMedia as $key => $stageMediaValue) {
                                    //on met le nom des vignettes de chaque étape dans un tableau
                                    array_push($stageMediaThumbnail, $stageMediaValue->filenameVignette);
                                }

                                foreach ($stageActivity as $key => $stageActivityValue) {
                                    $activity = $activity . '<p class="card-text col-6">' . $stageActivityValue->name . '</p>';
                                }

                                if ($travelStartDate == new DateTime("0000-00-00")) {
                                    $travelStartDate = $stageValue->date;
                                    $travelStartDate = new DateTime($travelStartDate);
                                }
                            }


                            // Sélection aléatoire d'une vignette pour le voyage
                            if ($stageMediaThumbnail != null) {
                                $thumbnail = array_rand($stageMediaThumbnail, 1);
                            }

                            
                            $travelDurationInterval = new DateInterval("P" . $travelDuration . "D");
                            $startDateFormat = $travelStartDate->format("d/m/Y");
                            $travelEndDate = $travelStartDate->add($travelDurationInterval);
                            $endDateFormat = $travelEndDate->format("d/m/Y");

                            if ($travelStartDate == new DateTime("0000-00-00")) {
                                $startDateFormat = "00/00/0000";
                            }

                            if ($travelEndDate == new DateTime("0000-00-00")) {
                                $endDateFormat = "00/00/0000";
                            }
                            ?>
                            <p class="card-text">Début : <em><?= $startDateFormat ?></em></p>
                            <p class="card-text">Fin : <em><?= $endDateFormat ?></em></p>
                            <p class="card-text">Durée du voyage : <em><?= $travelDuration ?> jours</em></p>
                            <p class="card-Text">Nombre d'activités : <?= $travelValue->numberDisplay?></p>
                            <div class="row">
                                <?= $activity ?>
                            </div>
                            <div class="mt-3">
                                <?php if ($stageMediaThumbnail != null) { ?>
                                    <img src="./public/img/uploads/vignettes/<?= $stageMediaThumbnail[$thumbnail] ?>" alt="">
                                <?php } ?>
                            </div>
                            <div class="text-end"><a class="card-link" href="index.php?page=detailTravel&id=<?= $travelValue->id ?>">Détail-></a></div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</main>