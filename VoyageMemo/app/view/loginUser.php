<!--
    TITRE : VoyageMémo
    Auteur : Guilain Fernandez
    Description : la page qui permet de se connecter à son compte
    Date : 24/04/2024
-->

<main class="container position-relative w-100 h-100">
    <div class="row text-bg-info justify-content-center w-75 h-50 position-absolute top-50 start-50 translate-middle">
        <div class="col-md-9">
            <h1 class="text-center text-white">Connexion</h1>
            <form class="h-75 d-flex flex-column justify-content-around" action="" method="post">
                <input type="text" class="form-control" name="userLogin" id="userLogin" placeholder="Entrez votre identifiant">
                <input type="password" class="form-control" name="password" id="password" placeholder="Entrer votre mot de passe">
                <button type="submit" name="login" class="btn btn-primary">Se connecter</button>
                <?= $error ?>
            </form>
            <div class="d-flex justify-content-between">
                <a class="text-end text-white" href="index.php?page=newUserAccount">Créer un compte</a>
                <a class="text-white" href="index.php">Accueil</a>
            </div>
        </div>
    </div>
</main>