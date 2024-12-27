<?php

// TODO: ajouter le trie sur différentes colonnes

require "vue_menus.php";

$contenu = '';

// Liste pour la sélection de la categorie
$selection = $idCategorie == '' ? " selected" : '';
$optionsCategorie = "\t\t<option value=''$selection>Toutes</option>\n";
while ($categorie = $categories->fetch_object()) {
    $selection = $categorie->cca_id == $idCategorie ? " selected" : '';
    $CP = $categorie->cca_fk_ccap_id == 1 ? "SA" : "CR";
    $optionsCategorie .= "\t\t<option value=\"$categorie->cca_id\"$selection>$CP$categorie->cca_rang- $categorie->cca_nom</option>\n";
}

// Liste pour la sélection de la série
$selection = $idSerie == '' ? " selected" : '';
$optionsSerie = "\t\t<option value=''$selection>Toutes</option>\n";
while ($serie = $series->fetch_object()) {
    $selection = $serie->cs_id == $idSerie ? " selected" : '';
    $optionsSerie .= "\t\t<option value=\"$serie->cs_id\"$selection>$serie->cs_rang_principal.$serie->cs_rang_secondaire- $serie->cs_nom</option>\n";
}

// Préparation des lignes du tableau
$lignes = '';
if (isset($evenements) && $evenements) {
    while ($evenement = $evenements->fetch_object()) {
        $CP = $evenement->cca_fk_ccap_id == 1 ? "SA" : "CR";
        $lignes .= <<<EOT
<tr>
    <td>$CP$evenement->cca_rang- $evenement->cca_nom</td>
    <td>$evenement->cs_rang_principal.$evenement->cs_rang_secondaire- $evenement->cs_nom</td>
    <td>$evenement->ce_rang</td>
    <td>$evenement->ce_nom</td>
    <td>
        <a class="editer" href="controleur.php?action=modifier&ce_id=$evenement->ce_id" title="Editer l'événement"></a>
        <a class="supprimer" href="JavaScript:alertFunction($evenement->ce_id)" title="Supprimer l'événement"></a>
        <a class="associer_voiture" href="controleur.php?action=associer_voitures&ce_id=$evenement->ce_id" title="Voitures associées à l'événement"></a>
        <a class="ajouter" href="../courses/controleur.php?action=ajouter_course&cca_id=$evenement->cca_id&cs_id=$evenement->cs_id&ce_id=$evenement->ce_id" title="Ajouter une course"></a>
        <a class="voir" href="../courses/controleur.php?action=mes_performances&cca_id=$evenement->cca_id&cs_id=$evenement->cs_id&ce_id=$evenement->ce_id" title="Voir mes courses"></a>
    </td>
</tr>

EOT;

    }
}

// Scripts utilisés pour la suppression et le filtrage par catégorie et par série
$scripts = <<<EOT

function alertFunction(id) {
    var r=confirm("Voulez-vous vraiment supprimer cet événement ?");
    if (r == true) {
        var url = "controleur.php?action=supprimer&ce_id=" + id;
            location.replace(url);
    }
}

function filtrerCategorie() {
   i = document.getElementById("categorie").selectedIndex;
   idCategorie = document.getElementById("categorie").options[i].value;
   parent.location.href = "controleur.php?action=lister&cca_id=" + idCategorie + "&cs_id=";
}

function filtrerSerie() {
   i = document.getElementById("serie").selectedIndex;
   idSerie = document.getElementById("serie").options[i].value;
   parent.location.href = "controleur.php?action=lister&cs_id=" + idSerie;
}

EOT;

// Affichage de la liste des événements
$contenu .= <<<EOT
    
<h1>$titre :</h1>
<div id="filtres">&nbsp;
    <label for="categorie">Catégorie de courses&nbsp;:&nbsp;</label>
    <select id="categorie" name="cca_id" onChange="filtrerCategorie()">
$optionsCategorie
    </select>

EOT;

if ($idCategorie != '') {
    $contenu .= <<<EOT
    
    <label for="serie">Série&nbsp;:&nbsp;</label>
    <select id="serie" name="cs_id" onChange="filtrerSerie()">
$optionsSerie
    </select>

EOT;
}

$contenu .= <<<EOT
</div>
<br>
<table id="liste">
<thead>
<tr>
    <th>Catégorie</th>
    <th>Série</th>
    <th>Rang</th>
    <th>Nom</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
$lignes
</tbody>
</table>
EOT;
    
require "../config/gabarit.php";

?>
