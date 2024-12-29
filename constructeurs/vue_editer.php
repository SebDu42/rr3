<?php

require "vue_menus.php";

$contenu = '';

// URL des actions du formulaire
$urlAction = "controleur.php?action=$action";
$urlAnnule = "controleur.php?action=lister";

// Récupération des données
$id = isset($donnees["vco_id"]) ? $donnees["vco_id"] : '';
$nom = isset($donnees["vco_nom"]) ? $donnees["vco_nom"] : '';
$url = isset($donnees["vco_url"]) ? $donnees["vco_url"] : '';

// Mémorisation des enregistrements actifs
$_SESSION["vco_id"] = $id;

// Construction du formulaire
$contenu .= <<<EOT
<h1>$titre :</h1>
<form id="edition_constructeurs" name="edition_constructeurs" method="post" action="$urlAction">
    <fieldset>
        <input type="hidden" name="vco_id" value="$id">
        <label for="nom">Nom du constructeur&nbsp;:&nbsp;</label>
        <input id="nom" type="text" name="vco_nom" value="$nom" required>
        <br>
        <label for="url">URL&nbsp;:&nbsp;</label>
        <input id="url" type="text" name="vco_url" value="$url">
    </fieldset>
    <button id="submit" type="submit">Valider</button>
    <button id="reset" type="reset">Réinitialiser</button>
    <button id="annuler" type="button" onclick="location.href='$urlAnnule'">Annuler</button>
</form>

EOT;

require "../config/gabarit.php";
