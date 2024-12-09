<!--
    TITRE : VoyageMémo
    Auteur : Guilain Fernandez
    Description : la page profile de l'utilisateur connecter
    Date : 24/04/2024
-->
<?php
if ($_SESSION['userLogin'] == "") {
    header('Location: index.php?page=home');
}
?>
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
<main class="m-3">
    <div class="container">
        <?php
        if ($_SESSION['userLogin'] != "") {
        ?>
            <div class="position-relative">
                <div>Nom utilisateur: <em><?= $_SESSION['userLogin'] ?></em></div>
                <button class="btn btn-primary position-absolute top-0 end-0">
                    <a class="nav-link" href="index.php?page=newTravel">Nouveau voyage</a>
                </button>
            </div>
        <?php
        }
        ?>
        <div class="row">
            <table class="table table-hover mt-5">
                <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">Début</th>
                        <th scope="col">Fin</th>
                        <th scope="col">Durée</th>
                        <th scope="col"></th>

                    </tr>
                </thead>
                <?php
                // Récupération de tous les voyages
                $allTravel = getTravelByUserId($_SESSION['userId']);
                foreach ($allTravel as $travelKey => $travelValue) {
                ?>
                    <tbody>
                        <th scope="col"><?= $travelValue->title ?></th>
                        <?php
                        // Récupération des étapes du voyage
                        $travelStage = getStageByTravelId($travelValue->id);
                        // réinitialise les variables a chaque nouveau voyage 
                        $travelDuration = 0;
                        $travelStartDate = new DateTime("0000-00-00");
                        $travelEndDate = new DateTime("0000-00-00");
                        foreach ($travelStage as $stageKey => $stageValue) {
                            // Récupération des activités et des médias des étapes
                            $travelDuration = $travelDuration += $stageValue->durationDays;

                            if ($travelStartDate == new DateTime("0000-00-00")) {
                                $travelStartDate = $stageValue->date;
                                $travelStartDate = new DateTime($travelStartDate);
                            }
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
                        <td><?= $startDateFormat ?></td>
                        <td><?= $endDateFormat ?></td>
                        <td><?= $travelDuration ?> jours</td>
                        <td><a href="index.php?page=detailTravel&id=<?= $travelValue->id ?>">détail-></a></td>
                    </tbody>
                <?php
                }
                ?>
            </table>
        </div>
    </div>
</main>

</html>