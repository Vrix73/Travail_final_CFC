<!--
    TITRE : VoyageMémo
    Auteur : Guilain Fernandez
    Description : la page qui permet d'ajouter des étapes aux voyages'
    Date : 29/04/2024
-->
<?php
if ($_SESSION['userLogin'] == "") {
    header('Location: index.php?page=home');
}
?>
<main class="container position-relative w-100 h-100">
    <div class="row text-bg-info justify-content-center w-75 h-75 position-absolute top-50 start-50 translate-middle">
        <div class="col-md-9">
            <h2 class="text-center text-white">Nouvelle étape d'un voyage</h2>
            <form class="h-75 d-flex flex-column justify-content-around" action="" method="post" enctype="multipart/form-data">
                <?php
                $travel = getTravelById($_GET["id"]);
                ?>
                <input type="text" class="form-control" value="<?= $travel[0]->title ?>" disabled="disabled">
                <input type="hidden" class="form-control" name="travelId" value="<?= $travel[0]->id ?>">
                <input type="text" class="form-control" name="stageTitle" placeholder="Titre de l'étape">
                <textarea class="form-control" name="stageDescription" placeholder="Description" id="" rows="5"></textarea>
                <div class="input-group">
                    <span class="input-group-text">Début</span>
                    <input type="date" class="form-control" name="startDate">
                    <span class="input-group-text">Fin</span>
                    <input type="date" class="form-control" name="endDate">
                </div>
                <select class="form-select form-select-lg" multiple aria-label="Multiple select activity" name="activity[]">
                    <option disabled>Choisis tes activités</option>
                    <?php
                    $allActivity = getAllActivity();
                    foreach ($allActivity as $key => $value) {
                    ?>
                        <option value="<?= $value->id ?>"><?= $value->name ?></option>
                    <?php
                    }
                    ?>
                </select>
                <input class="form-control" type="file" name="stageMedia[]" accept="image/png, image/jpg, image/jpeg" multiple>
                <button type="submit" name="newStage" class="btn btn-primary">Créer la nouvelle étape</button>
                <?= $error ?>
            </form>
            <div class="d-flex justify-content-between">
                <a class="text-end text-white" href="index.php?page=profil">Profil</a>
                <a class="text-white" href="index.php">Accueil</a>
            </div>
        </div>
    </div>
</main>