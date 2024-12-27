<?php

// TODO: ajouter des filtres sur la voiture et le circuit.
// TODO: ajouter le trie sur différentes colonnes.

require "vue_menus.php";

$contenu = '';

// Liste pour la sélection de la categorie
$selected = $idCategorie == '' ? " selected" : '';
$optionsCategorie = "\t\t<option value$selected>Toutes</option>\n";
if ($categories) {
    while ($categorie = $categories->fetch_object()) {
        $selected = $categorie->cca_id == $idCategorie ? " selected" : '';
        $CP = $categorie->cca_fk_ccap_id == 1 ? "SA" : "CR";
        $optionsCategorie .= "\t\t<option value=\"$categorie->cca_id\"$selected>$CP$categorie->cca_rang- $categorie->cca_nom</option>\n";
    }
}

// Liste pour la sélection de la série
$selected = $idSerie == '' ? " selected" : '';
$optionsSerie = "\t\t<option value$selected>Toutes</option>\n";
if ($series) {
    while ($serie = $series->fetch_object()) {
        $selected = $serie->cs_id == $idSerie ? " selected" : '';
        $optionsSerie .= "\t\t<option value=\"$serie->cs_id\"$selected>$serie->cs_rang_principal.$serie->cs_rang_secondaire- $serie->cs_nom</option>\n";
    }
}

// Liste pour la sélection de l'évenement
$selected = $idEvenement == '' ? " selected" : '';
$optionsEvenement = "\t\t<option value$selected>Tous</option>\n";
if ($evenements) {
    while ($evenement = $evenements->fetch_object()) {
        $selected = $evenement->ce_id == $idEvenement ? " selected" : '';
        $optionsEvenement .= "\t\t<option value=\"$evenement->ce_id\"$selected>$evenement->ce_rang- $evenement->ce_nom</option>\n";
    }
}

// Préparation des lignes du tableau
$lignes = '';
if (isset($courses) && $courses) {
    while ($course = $courses->fetch_object()) {
        $circuit = $course->ci_nom;
        $circuit .= isset($course->cic_nom) ? " - ".$course->cic_nom : '';
        $CP = $course->cca_fk_ccap_id == 1 ? "SA" : "CR";

        $lignes .= <<<EOT
<tr>
    <td>$CP$course->cca_rang- $course->cca_nom</td>
    <td>$course->cs_rang_principal.$course->cs_rang_secondaire- $course->cs_nom</td>
    <td>$course->ce_rang- $course->ce_nom</td>
    <td>$course->c_rang</td>
    <td>$course->ct_nom</td>
    <td>$circuit</td>
    <td>$course->cco_nom</td>
    <td>$course->c_ip_min</td>
    <td>$course->c_nb_tours</td>
    <td>$course->c_nb_concurrents</td>
    <td>$course->m_nom</td>
    <td>
        <a class="editer" href="controleur.php?action=modifier_course&c_id=$course->c_id" title="Editer la course"></a>
        <a class="supprimer" href="JavaScript:alertFunction($course->c_id)" title="Supprimer la course"></a>
        <a class="ajouter" href="controleur.php?action=ajouter_performance&c_id=$course->c_id" title="Ajouter une performance"></a>
    </td>
</tr>

EOT;

    }
}

// Scripts utilisés pour la suppression et le filtrage par catégorie, par série et par évenement
$scripts = <<<EOT

function alertFunction(id) {
    var r=confirm("Voulez-vous vraiment supprimer cette course ?");
    if (r == true) {
        var url = "controleur.php?action=supprimer_course&c_id=" + id;
            location.replace(url);
    }
}

function filtrerCategorie() {
   i = document.getElementById("categorie").selectedIndex;
   idCategorie = document.getElementById("categorie").options[i].value;
   parent.location.href = "controleur.php?action=lister_courses&cca_id=" + idCategorie + "&cs_id=&ce_id=";
}

function filtrerSerie() {
   i = document.getElementById("serie").selectedIndex;
   idSerie = document.getElementById("serie").options[i].value;
   parent.location.href = "controleur.php?action=lister_courses&cs_id=" + idSerie + "&ce_id=";
}

function filtrerEvenement() {
   i = document.getElementById("evenement").selectedIndex;
   idEvenement = document.getElementById("evenement").options[i].value;
   parent.location.href = "controleur.php?action=lister_courses&ce_id=" + idEvenement;
}

EOT;

// Affichage de la liste des courses
$contenu .= <<<EOT
    
<h1>$titre :</h1>
<div id="filtres">&nbsp;
    <label for="categorie">Catégorie de courses&nbsp;:&nbsp;</label>
    <select id="categorie" name="cca_id" onChange="filtrerCategorie()">
$optionsCategorie
    </select>

EOT;

if ($idCategorie != '') {
    $contenu .= <<< EOT
    
    <label for="serie">Série&nbsp;:&nbsp;</label>
    <select id="serie" name="cs_id" onChange="filtrerSerie()">
$optionsSerie
    </select>

EOT;
    
    if ($idSerie != '') {
        $contenu .= <<<EOT
        
    <label for="evenement">Événement&nbsp;:&nbsp;</label>
    <select id="evenement" name="ce_id" onChange="filtrerEvenement()">
$optionsEvenement
    </select>

EOT;
    }
}

$contenu .= <<<EOT
</div>
<br>
<table id="liste">
<thead>
<tr>
    <th>Catégorie</th>
    <th>Série</th>
    <th>Événement</th>
    <th>Rang</th>
    <th>Type</th>
    <th>Circuit</th>
    <th>Conditions</th>
    <th>IP Min.</th>
    <th>Nb trs.</th>
    <th>Nb Conc.</th>
    <th>Mon.</th>
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