<?php

$urlAction = "controleur.php?action=connecter";
$courriel = isset($_POST["u_courriel"]) ? $_POST["u_courriel"] : "";
$motDePasse = isset($_POST["u_mot_de_passe"]) ? $_POST["u_mot_de_passe"] : "";
$erreurAuthentification = isset($erreurs["authentification"]) ? $erreurs["authentification"] : "";

$contenu = <<<EOT
<form id="authentification" name="authentification" method="post" action="controleur.php?action=connecter">
    <fieldset>
        <label for="courriel">Adresse de courriel :&nbsp;</label>
        <input id="courriel" type="text" name="u_courriel" value="$courriel" required />
        <br />
        <label for="motDePasse">Mot de passe :&nbsp;</label>
        <input id="motDePasse" type="password" name="u_mot_de_passe" value="$motDePasse" required />
        <div class="erreur">$erreurAuthentification</div>
    </fieldset>
    <button name="connexion" type="submit" id="connexion">Connexion</button>
    <button id="reset" type="reset" />Réinitialiser</button>
</form>

EOT;

require "../config/gabarit.php";

?>
