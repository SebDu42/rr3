<?php

require "vue_menus.php";

$contenu = '';

// URL des actions du formulaire
$urlAction = "controleur.php?action=$action";
$urlAnnuler = "controleur.php?action=lister";

// Utilisé pour rendre inactif la partie ciruit lorsqu'on ajoute une configuration
$inactif = (isset($donnees["ci_id"]) and !isset($donnees["cic_id"])) ? "disabled" : '';

// Récupération des données
$idCircuit = isset($donnees["ci_id"]) ? $donnees["ci_id"] : '';
$circuit = isset($donnees["ci_nom"]) ? $donnees["ci_nom"] : '';
$localisation = isset($donnees["ci_localisation"]) ? $donnees["ci_localisation"] : '';
$url = isset($donnees["ci_url"]) ? $donnees["ci_url"] : '';
$idConfiguration = isset($donnees["cic_id"]) ? $donnees["cic_id"] : '';
$Configuration = isset($donnees["cic_nom"]) ? $donnees["cic_nom"] : '';
$longueur = isset($donnees["cic_longueur"]) ? $donnees["cic_longueur"] : '';

// Mémorisation des enregistrements actifs
$_SESSION["ci_id"] = $idCircuit;
$_SESSION["cic_id"] = $idConfiguration;

// Récupération des éventuelles erreurs de saisie
$erreurLongueur = isset($erreurs["longueur"]) ? $erreurs["longueur"] : '';

// Construction du formulaire
$contenu .= <<<EOT

<h1>$titre</h1>
<form id="edition_circuit" name="edition_circuit" method="post" action="$urlAction">
    <input type="hidden" name="ci_id" value="$idCircuit">
    <fieldset $inactif>
        <legend>Circuit</legend>
        <label for="circuit">Nom du circuit&nbsp;:&nbsp;</label>
        <input id="circuit" type="text" name="ci_nom" value="$circuit" required>
        <br>
        <label for="localisation">Localisation&nbsp;:&nbsp;</label>
        <input id="localisation" type="text" name="ci_localisation" value="$localisation" required/>
        <br>
        <label for="url">URL&nbsp;:&nbsp;</label>
        <input id="url" type="text" name="ci_url" value="$url">
    </fieldset>
    <fieldset>
        <legend>Configuration</legend>
        <input type="hidden" name="cic_id" value="$idConfiguration">
        <label for="configuration">Nom de la configuration&nbsp;:&nbsp;</label>
        <input id="configuration" type="text" name="cic_nom" value="$Configuration">
        <br>
        <label for="longueur">Longueur de la piste&nbsp;:&nbsp;</label>
        <input id="longueur" type="text" name="cic_longueur" value="$longueur" required/> km(s)
        <div class="erreur">$erreurLongueur</div>
    </fieldset>
    <button id="submit" type="submit">Valider</button>
    <button id="reset" type="reset">Réinitialiser</button>
    <button id="annuler" type="button" onclick="location.href='$urlAnnuler'">Annuler</button>
</form>

EOT;

require "../config/gabarit.php";
