<?php

require "vue_menus.php";

$contenu = '';

// Préparation des données pour le formulaire
$urlAction = "controleur.php?action=$action";
$urlAnnuler = "controleur.php?action=lister";

// Liste pour la sélection de la categorie
$idCategorie = isset($donnees["cca_id"]) ? $donnees["cca_id"] : $idCategorie;
$faireChoix = true;
$optionsCategorie = '';
while ($categorie = $categories->fetch_object()) {
    $selection = $categorie->cca_id == $idCategorie ? " selected" : '';
    $CP = $categorie->cca_fk_ccap_id == 1 ? "SA" : "CR";
    $faireChoix &= $categorie->cca_id != $idCategorie;
    $optionsCategorie .= "\t\t<option value=\"$categorie->cca_id\"$selection>$CP$categorie->cca_rang- $categorie->cca_nom</option>\n";
}
$optionsCategorie = ($faireChoix ? "\t\t\t<option hidden value='' selected>Choisissez une catégorie</option>\n" : '').$optionsCategorie;

// Liste pour la sélection de la série
$idSerie = isset($donnees["cs_id"]) ? $donnees["cs_id"] : $idSerie;
$faireChoix = true;
$optionsSerie = '';
while ($serie = $series->fetch_object()) {
    $selection = $serie->cs_id == $idSerie ? " selected" : '';
    $faireChoix &= $serie->cs_id != $idSerie;
    $optionsSerie .= "\t\t\t<option value=\"$serie->cs_id\"$selection>$serie->cs_rang_principal.$serie->cs_rang_secondaire- $serie->cs_nom</option>\n";
}
$optionsSerie = ($faireChoix ? "\t\t\t<option hidden value='' selected>Choisissez une série</option>\n" : '').$optionsSerie;

// Récupération des données
$id = isset($donnees["ce_id"]) ? $donnees["ce_id"] : '';
$rang = isset($donnees["ce_rang"]) ? $donnees["ce_rang"] : '';
$nom =  isset($donnees["ce_nom"]) ? $donnees["ce_nom"] : '';

// Mémorisation des enregistrements actifs
$_SESSION["ce_id"] = $id;
$_SESSION["cs_id"] = $idSerie;
$_SESSION["cca_id"] = $idCategorie;

// Récupération des éventuelles erreurs de saisie
$erreurRang = isset($erreurs["rang"]) ? $erreurs["rang"] : '';

// Script utilisé pour le filtrage par catégorie
$scripts = <<<EOT

function filtrerCategorie() {
    i = document.getElementById("categorie").selectedIndex;
    idCategorie = document.getElementById("categorie").options[i].value;
    parent.location.href = "$urlAction&cca_id=" + idCategorie + "&cs_id=&ce_id=$id";
}

EOT;

// Construction du formulaire
$contenu .= <<<EOT

<h1>$titre :</h1>
<div id="selection">&nbsp;
    <label for="categorie">Catégorie de courses :&nbsp;</label>
    <select id="categorie" name="cca_id" onChange="filtrerCategorie()">
$optionsCategorie
    </select>
</div>
EOT;

if ($idCategorie != '') {
    $contenu .= <<<EOT

<br />
<form id="edition_evenement" name="edition_evenement" method="post" action="$urlAction">
    <fieldset>
        <input type="hidden" name="ce_id" value="$id" />
        <label for="serie">Série&nbsp;:&nbsp;</label>
        <select id="serie" name="ce_fk_cs_id" required>
$optionsSerie
        </select>
        <br />
        <label for="rang">Rang de l'événement&nbsp;:&nbsp;</label>
        <input id="rang" type="text" name="ce_rang" value="$rang" required />
        <div class="erreur">$erreurRang</div>
        <label for="nom">Nom de l'événement&nbsp;:&nbsp;</label>
        <input id="nom" type="text" name="ce_nom" value="$nom" required />
    </fieldset>
    <button id="submit" type="submit" />Valider</button>
    <button id="reset" type="reset" />Réinitialiser</button>
    <button id="annuler" type="button" onclick="location.href='$urlAnnuler'" />Annuler</button>
</form>

EOT;
}
else {
    $contenu .= <<<EOT

<button id="annuler" type="button" onclick="location.href='$urlAnnuler'" />Annuler</button>

EOT;
}

require "../config/gabarit.php";
