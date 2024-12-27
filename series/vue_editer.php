<?php

require "vue_menus.php";

$contenu = '';

// URL des actions du formulaire
$urlAction = "controleur.php?action=$action";
$urlAnnule = "controleur.php?action=lister";

// Liste pour la sélection de la catégorie
$idCategorie = isset($donnees["cs_fk_cca_id"]) ? $donnees["cs_fk_cca_id"] : $idCategorie;
$optionsCategories = ($idCategorie == '') ? "\t\t\t<option hidden value='' selected>Choisissez une catégorie</option>\n" : '';
while ($categorie = $categories->fetch_object()) {
    $selection = $categorie->cca_id == $idCategorie ? " selected" : '';
    $CP = $categorie->cca_fk_ccap_id == 1 ? "SA" : "CR";
    $optionsCategories .= "\t\t\t<option value=\"$categorie->cca_id\"$selection>$CP$categorie->cca_rang- $categorie->cca_nom</option>\n";
}

// Liste pour la sélection du statut
$idStatut = isset($donnees["cs_fk_css_id"]) ? $donnees["cs_fk_css_id"] : $idStatut;
$optionsStatuts = ($idStatut == '') ? "\t\t\t<option hidden value='' selected>Choisissez un status</option>\n" : '';
while ($statut = $statuts->fetch_object()) {
    $selection = $statut->css_id == $idStatut ? " selected" : '';
    $optionsStatuts .= "\t\t\t<option value=\"$statut->css_id\"$selection>$statut->css_nom</option>\n";
}


// Récupération des données
$id = isset($donnees["cs_id"]) ? $donnees["cs_id"] : '';
$rang_principal = isset($donnees["cs_rang_principal"]) ? $donnees["cs_rang_principal"] : '';
$rang_secondaire = isset($donnees["cs_rang_secondaire"]) ? $donnees["cs_rang_secondaire"] : '';
$nom = isset($donnees["cs_nom"]) ? $donnees["cs_nom"] : '';
$avancement = isset($donnees["cs_avancement"]) ? $donnees["cs_avancement"] : '';
$commentaire = isset($donnees["cs_commentaire"]) ? $donnees["cs_commentaire"] : '';

// Mémorisation des enregistrements actifs
$_SESSION["cs_id"] = $id;
$_SESSION["cca_id"] = $idCategorie;
//$_SESSION["css_id"] = $idStatut;

// Récupération des éventuelles erreurs de saisie
$erreurRangPrincipal = isset($erreurs["rang_principal"]) ? $erreurs["rang_principal"] : '';
$erreurRangSecondaire = isset($erreurs["rang_secondaire"]) ? $erreurs["rang_secondaire"] : '';
$erreurAvancement = isset($erreurs["avancement"]) ? $erreurs["avancement"] : '';

// Construction du formulaire
$contenu .= <<<EOT

<h1>$titre :</h1>
<form id="edition_serie" name="edition_serie" method="post" action="$urlAction">
    <fieldset>
        <input type="hidden" name="cs_id" value="$id">
        <label for="categorie">Catégorie de courses :&nbsp;</label>
        <select id="categorie" name="cs_fk_cca_id" required>
$optionsCategories
        </select>
        <br>
        <label for="rang_principal">Rang principal de la série&nbsp;:&nbsp;</label>
        <input id="rang_principal" type="text" name="cs_rang_principal" value="$rang_principal" required>
        <div class="erreur">$erreurRangPrincipal</div>
        <label for="rang_secondaire">Rang secondaire de la série&nbsp;:&nbsp;</label>
        <input id="rang_secondaire" type="text" name="cs_rang_secondaire" value="$rang_secondaire" required>
        <div class="erreur">$erreurRangSecondaire</div>
        <label for="nom">Nom de la série&nbsp;:&nbsp;</label>
        <input id="nom" type="text" name="cs_nom" value="$nom" required>
        <br>
        <label for="status">Satus des séries&nbsp;:&nbsp;</label>
        <select id="status" name="cs_fk_css_id" required>
$optionsStatuts
        </select>
        <br>
        <label for="avancement">Avancement de la série&nbsp;:&nbsp;</label>
        <input id="avancement" type="number" name="cs_avancement" value="$avancement" required>
        <div class="erreur">$erreurAvancement</div>
        <label for)"commentaire">Commentaire&nbsp;:&nbsp;</label>
        <textarea id="commentaire" name="cs_commentaire">$commentaire</textarea>
        <br>
    </fieldset>
    <button id="submit" type="submit">Valider</button>
    <button id="reset" type="reset">Réinitialiser</button>
    <button id="annuler" type="button" onclick="location.href='$urlAnnule'">Annuler</button>
</form>

EOT;

require "../config/gabarit.php";

?>