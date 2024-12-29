<?php

$urlAction = "controleur.php?action=connecter";
$courriel = isset($_POST["u_courriel"]) ? $_POST["u_courriel"] : "";
$motDePasse = isset($_POST["u_mot_de_passe"]) ? $_POST["u_mot_de_passe"] : "";

require_once "../config/gabarit_top.php";

?>

    <main class="container bg-body-tertiary flex-grow-1">
        <div class="py-4 m-auto form-signin">
            <form method="post" action="./controleur.php?action=connecter">

                <?php if (isset($erreurs["authentification"])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $erreurs["authentification"]; ?>
                </div>
                <?php } ?>

                <div class="form-floating">
                    <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com"
                        name="u_courriel" value="<?php echo $courriel; ?>" required />
                    <label for="floatingInput">Adresse de courriel</label>
                </div>

                <div class="form-floating">
                    <input type="password" class="form-control" id="floatingPassword" placeholder="Password"
                        name="u_mot_de_passe" value="<?php echo $motDePasse; ?>" required />
                    <label for="floatingPassword">Mot de passe</label>
                </div>

                <!--
        <div class="form-check text-start my-3">
            <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
            <label class="form-check-label" for="flexCheckDefault">
                Remember me
            </label>
        </div>
        -->

                <button class="btn btn-primary w-100 py-2" type="submit">Connexion</button>
                <!--<p class="mt-5 mb-3 text-body-secondary">&copy; 2017–2024</p>-->
            </form>
        </div>
    </main>

<?php

    require_once "../config/gabarit_bottom.php";
