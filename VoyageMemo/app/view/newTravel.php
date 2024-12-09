<!--
    TITRE : VoyageMémo
    Auteur : Guilain Fernandez
    Description : la page qui permet de créer un voyage
    Date : 24/04/2024
-->
<?php
if ($_SESSION['userLogin'] == "") {
    header('Location: index.php?page=home');
}
?>
<main class="container position-relative w-100 h-100">
    <div class="row text-bg-info justify-content-center w-75 h-50 position-absolute top-50 start-50 translate-middle">
        <div class="col-md-9">
            <h1 class="text-center text-white">Nouveau voyage</h1>
            <form class="h-75 d-flex flex-column justify-content-around" action="" method="post">
                <input type="text" class="form-control" name="travelTitle" id="travelTitle" placeholder="Titre du voyage">
                <button type="submit" name="newTravel" class="btn btn-primary">Créer le nouveau voyage</button>
                <div class="text-danger" id="error"><?= $error ?></div>
            </form>
            <div class="d-flex justify-content-between">
                <a class="text-end text-white" href="index.php?page=profil">Profil</a>
                <a class="text-white" href="index.php">Accueil</a>
            </div>
        </div>
    </div>
</main>