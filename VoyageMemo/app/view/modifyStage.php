<!--
    TITRE : VoyageMémo
    Auteur : Guilain Fernandez
    Description : la page qui permet de modifier une étape
    Date : 05/06/2024
-->

<?php
if ($_SESSION['userLogin'] == "") {
    header('Location: index.php?page=home');
}
$stage = getStageById($_GET["id"]);
$stageMedia = getMediaByStageId($stage[0]->id);
$date = date_create($stage[0]->date);
$durationToString = strval($stage[0]->durationDays);
date_modify($date, '+' . $durationToString . 'days');
$endDate = $date->format("Y-m-d");
?>
<main class="container position-relative vw-100 vh-100 p-0">
    <div class="row text-bg-info justify-content-center w-75 h-75 position-absolute top-50 start-50 translate-middle">
        <div class="col-md-9 p-0">
            <h2 class="text-center text-white">Modifier l'étape d'un voyage</h2>
            <form class="modifyStage d-flex flex-column justify-content-around" action="" method="post" enctype="multipart/form-data">
                <input type="text" class="form-control" name="stageTitle" id="stageTitle" value="<?= $stage[0]->title ?>" placeholder="Titre de l'étape">
                <textarea class="form-control" name="stageDescription" placeholder="Description" rows="5"><?= $stage[0]->description ?></textarea>
                <div class="input-group">
                    <span class="input-group-text">Début</span>
                    <input type="date" class="form-control" name="startDate" value="<?= $stage[0]->date ?>">
                    <span class="input-group-text">Fin</span>
                    <input type="date" class="form-control" name="endDate" value="<?= $endDate ?>">
                </div>
                <select class="form-select form-select-lg" multiple aria-label="Multiple select activity" name="activity[]" id="activity">
                    <option disabled>Choisis tes activités</option>
                    <?php
                   $allActivity = getAllActivity();
                   $stageActivity = getActivityByStageId($stage[0]->id);
                   
                   foreach ($allActivity as $ActivityValue) {
                       $selected = false;
                       foreach ($stageActivity as $stageActivityValue) {
                           if ($stageActivityValue->id == $ActivityValue->id) {
                               $selected = true;
                               break;
                           }
                       }
                       ?>
                       <option <?= $selected ? 'selected' : '' ?> value="<?= $ActivityValue->id ?>"><?= $ActivityValue->name ?></option>
                       <?php
                   }
                    ?>
                </select>
                <div class="pictureList row overflow-auto d-flex justify-content-center">
                    <?php
                    foreach ($stageMedia as $keyMedia => $valueMedia) {
                    ?>
                        <div class="card col-5 col-md-4 col-lg-3 col-xl-2 m-1">
                            <img src="./public/img/uploads/vignettes/<?= $valueMedia->filenameVignette ?>" alt="">
                            <p class="text-center m-1">Supprimer</p>
                            <input class="text-center m-1" type="checkbox" name="media[]" value="<?= $valueMedia->id ?>">
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <input class="form-control" type="file" name="stageMedia[]" id="stageMedia" accept="image/png, image/jpg, image/jpeg" multiple>
                <input type="hidden" name="stageId" value="<?= $_GET["id"] ?>">
                <button type="submit" name="modifyStage" class="btn btn-primary">Modifier l'étape</button>
                <?= $error ?>
            </form>
            <div class="d-flex justify-content-between">
                <a class="text-end text-white" href="index.php?page=profil">Profil</a>
                <a class="text-white" href="index.php">Accueil</a>
            </div>
        </div>
    </div>
</main>