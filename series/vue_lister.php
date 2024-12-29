<?php

require "vue_menus.php";

$contenu = '';

// Liste pour la sélection de la categorie
$selection = $idCategorie == '' ? " selected" : '';
$optionsCategories = "\t\t<option value=''$selection>Toutes</option>\n";
while ($categorie = $categories->fetch_object()) {
    $selection = $categorie->cca_id == $idCategorie ? " selected" : '';
    $CP = $categorie->cca_fk_ccap_id == 1 ? "SA" : "CR";
    $optionsCategories .= "\t\t<option value=\"$categorie->cca_id\"$selection>$CP$categorie->cca_rang- $categorie->cca_nom</option>\n";
}

// Liste pour la sélection du statut
$selection = $idStatut == '' ? " selected" : '';
$optionsStatuts = "\t\t<option value=''$selection>Tous</option>\n";
while ($statut = $statuts->fetch_object()) {
    $selection = $statut->css_id == $idStatut ? " selected" : '';
    $optionsStatuts .= "\t\t<option value=\"$statut->css_id\"$selection>$statut->css_nom</option>\n";
}

// Préparation des lignes du tableau
$lignes = '';
if (isset($series)) {
    while ($serie = $series->fetch_object()) {
        $CP = $serie->cca_fk_ccap_id == 1 ? "SA" : "CR";
        $lignes .= <<<EOT
<tr>
    <td>$CP$serie->cca_rang- $serie->cca_nom</td>
    <td>$serie->cs_rang_principal.$serie->cs_rang_secondaire</td>
    <td>$serie->cs_nom</td>
    <td>$serie->css_nom</td>
    <td><meter value="$serie->cs_avancement" max="100"></meter>&nbsp;$serie->cs_avancement&nbsp;%</td>
    <td>$serie->cs_commentaire</td>
    <td>
        <a class="editer" href="controleur.php?action=modifier&cs_id=$serie->cs_id" title="Editer la série"></a>
        <a class="supprimer" href="JavaScript:alertFunction($serie->cs_id)" title="Supprimer la série"></a>
        <a class="associer_voiture" href="controleur.php?action=associer_voitures&cs_id=$serie->cs_id" title="Voitures associées à la série"></a>
        <a class="ajouter" href="../evenements/controleur.php?action=ajouter&cca_id=$serie->cca_id&cs_id=$serie->cs_id" title="Ajouter un événement"></a>
        <a class="voir" href="../evenements/controleur.php?action=lister&cca_id=$serie->cca_id&cs_id=$serie->cs_id" title="Voir les événements"></a>
    </td>
</tr>

EOT;

    }
}

// Scripts utilisés pour la suppression et le filtre par catégorie
$scripts = <<<EOT

function alertFunction(id) {
    var r=confirm("Voulez-vous vraiment supprimer cette série ?");
    if (r == true) {
        var url = "controleur.php?action=supprimer&cs_id=" + id;
            location.replace(url);
    }
}

function filtrerCategorie() {
   i = document.getElementById("categorie").selectedIndex;
   idCategorie = document.getElementById("categorie").options[i].value;
   parent.location.href = "controleur.php?action=lister&cca_id="+idCategorie;
}

function filtrerStatut() {
   i = document.getElementById("statut").selectedIndex;
   idStatut = document.getElementById("statut").options[i].value;
   parent.location.href = "controleur.php?action=lister&css_id="+idStatut;
}

EOT;

// Affichage de la liste des séries
$contenu .= <<<EOT

<h1>$titre :</h1>
<div id="filtres">&nbsp;
    <label for="categorie">Catégorie de courses&nbsp;:&nbsp;</label>
    <select id="categorie" name="cca_id" onChange="filtrerCategorie()">
$optionsCategories
    </select>

    <label for="statut">Statut&nbsp;:&nbsp;</label>
    <select id="statut" name="css_id" onChange="filtrerStatut()">
$optionsStatuts
    </select>
</div>
<br>
<table id="liste">
<thead>
<tr>
    <th>Catégorie</th>
    <th>Rang</th>
    <th>Nom</th>
    <th>Status</th>
    <th>Avancement</th>
    <th>Commentaire</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
$lignes
</tbody>
</table>
EOT;

require "../config/gabarit.php";
