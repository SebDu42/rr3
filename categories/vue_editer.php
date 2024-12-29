<?php

require "vue_menus.php";

$contenu = '';

// URL des actions du formulaire
$urlAction = "controleur.php?action=$action";
$urlAnnule = "controleur.php?action=lister";

// Liste pour la sélection de la catégorie principale
$idCP = isset($donnees["cca_fk_ccap_id"]) ? $donnees["cca_fk_ccap_id"] : $idCP;
$optionsCPs = ($idCP == '') ? "\t\t\t<option hidden value='' selected>Choisissez une catégorie principale</option>\n" : '';
while ($CP = $CPs->fetch_object()) {
    $selection = $CP->ccap_id == $idCP ? " selected" : '';
    $optionsCPs .= "\t\t\t<option value=\"$CP->ccap_id\"$selection>$CP->ccap_nom</option>\n";
}

// Récupération des données
$id = isset($donnees["cca_id"]) ? $donnees["cca_id"] : '';
$rang = isset($donnees["cca_rang"]) ? $donnees["cca_rang"] : '';
$nom = isset($donnees["cca_nom"]) ? $donnees["cca_nom"] : '';

// Mémorisation des enregistrements actifs
$_SESSION["cca_id"] = $id;

// Récupération des éventuelles erreurs de saisie
$erreurRang = isset($erreurs["rang"]) ? $erreurs["rang"] : '';

// Construction du formulaire
$contenu .= <<<EOT

<h1>$titre :</h1>
<form id="edition_categorie" name="edition_categorie" method="post" action="$urlAction">
    <fieldset>
        <input type="hidden" name="cca_id" value="$id" />
        <label for="categorieP">Catégorie principale :&nbsp;</label>
        <select id="categorieP" name="cca_fk_ccap_id" required>
$optionsCPs
        </select>
        <br>
        <label for="rang">Rang de la catégorie :&nbsp;</label>
        <input id="rang" type="number" name="cca_rang" value="$rang" required />
        <div class="erreur">$erreurRang</div>
        <label for="nom">Nom de la catégorie :&nbsp;</label>
        <input id="nom" type="text" name="cca_nom" value="$nom" required />
    </fieldset>
    <button id="submit" type="submit" />Valider</button>
    <button id="reset" type="reset" />Réinitialiser</button>
    <button id="annuler" type="button" onclick="location.href='$urlAnnule'" />Annuler</button>
</form>

EOT;

require "../config/gabarit.php";
