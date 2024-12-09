<!--
    TITRE : VoyageMémo
    Auteur : Guilain Fernandez
    Description : la page qui permet de créer un nouveau compte
    Date : 24/04/2024
 -->
 <main class="container position-relative w-100 h-100">
    <div class="row text-bg-info justify-content-center w-75 h-50 position-absolute top-50 start-50 translate-middle">
        <div class="col-md-9">
            <h1 class="text-center text-white">Créer un compte</h1>
            <form class="h-75 d-flex flex-column justify-content-around" action="" method="post">
                <input type="text" class="form-control" name="login" id="login" placeholder="Entrez votre identifiant">
                <div class="password-field">
                    <input type="password" value="" class="form-control" name="password" id="password" placeholder="Entrez votre mot de passe">
                    <div class="random-icon" onclick="randomString()" data-bs-toggle="tooltip" data-bs-html="true" title="Générer un mot de passe">
                        <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-shuffle' viewBox='0 0 16 16'>
                            <path fill-rule='evenodd' d='M0 3.5A.5.5 0 0 1 .5 3H1c2.202 0 3.827 1.24 4.874 2.418.49.552.865 1.102 1.126 1.532.26-.43.636-.98 1.126-1.532C9.173 4.24 10.798 3 13 3v1c-1.798 0-3.173 1.01-4.126 2.082A9.6 9.6 0 0 0 7.556 8a9.6 9.6 0 0 0 1.317 1.918C9.828 10.99 11.204 12 13 12v1c-2.202 0-3.827-1.24-4.874-2.418A10.6 10.6 0 0 1 7 9.05c-.26.43-.636.98-1.126 1.532C4.827 11.76 3.202 13 1 13H.5a.5.5 0 0 1 0-1H1c1.798 0 3.173-1.01 4.126-2.082A9.6 9.6 0 0 0 6.444 8a9.6 9.6 0 0 0-1.317-1.918C4.172 5.01 2.796 4 1 4H.5a.5.5 0 0 1-.5-.5' />
                            <path d='M13 5.466V1.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384l-2.36 1.966a.25.25 0 0 1-.41-.192m0 9v-3.932a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384l-2.36 1.966a.25.25 0 0 1-.41-.192' />
                        </svg>
                    </div>
                    <div class="password-icon" onclick="pswVisibility()">
                        <svg id="eye" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                        </svg>
                        <svg id="eyeOff" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
                            <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z" />
                            <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829" />
                            <path d="M3.35 5.47q-.27.24-.518.487A13 13 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7 7 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12z" />
                        </svg>
                    </div>
                </div>
                <input type="password" class="form-control" name="validatePsw" id="validatePsw" placeholder="Confirmez votre mot de passe">
                <button type="submit" name="newAccount" class="btn btn-primary">S'inscrire</button>
                <div class="text-danger" id="error"><?= $error ?></div>
            </form>
            <div class="d-flex justify-content-between">
                <a class="text-end text-white" href="index.php?page=loginUser">Connexion</a>
                <a class="text-white" href="index.php">Accueil</a>
            </div>
        </div>
    </div>
</main>

<script>
    var eye = document.getElementById("eye");
    var eyeoff = document.getElementById("eyeOff");
    var pswType = document.getElementById("password");

    function pswVisibility() {
        //quand on clique on m'est le type de l'inpute en text
        if (password.type == "password") {
            eye.style.display = "none";
            eyeoff.style.display = "block";
            pswType.type = "text";
        }
        //quand on clique on mest le type de l'inpute en password
        else {
            eyeoff.style.display = "none";
            eye.style.display = "block";
            pswType.type = "password";
        }
    }

    function randomString() {
        let result = '';
        let length = 15;
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789%&/?!*#';
        const charactersLength = characters.length;
        let counter = 0;
        while (counter < length) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
            counter += 1;
        }
        pswType.value = result;
    }
</script>