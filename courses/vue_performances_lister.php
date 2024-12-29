<?php

// TODO: ajouter des statistiques supplémentaires.
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
if (isset($performances) && $performances) {
    while ($performance = $performances->fetch_object()) {
        $circuit = $performance->ci_nom;
        $circuit .= isset($performance->cic_nom) ? " - ".$performance->cic_nom : '';
        $CP = $performance->cca_fk_ccap_id == 1 ? "SA" : "CR";

        if ($performance->up_fk_uv_id) {
            $voiture = mRecupererVoitureUtilisateur($performance->up_fk_uv_id);
            $voiture = $voiture->vco_nom.' '.$voiture->v_modele;
        }
        else {
            $voiture = '';
        }

        $recompense = isset($performance->up_recompense) ? $performance->up_recompense."&nbsp;".$performance->m_nom : '';
        $vitesse = $performance->up_vitesse ? $performance->up_vitesse."&nbsp;km/h" : '';
        $distance = $performance->up_distance ? ($performance->c_fk_ct_id == -1 ? (int)$performance->up_distance."&nbsp;m" : $performance->up_distance."&nbsp;km") : '';
        $lignes .= <<<EOT
<tr>
    <td>$CP$performance->cca_rang- $performance->cca_nom</td>
    <td>$performance->cs_rang_principal.$performance->cs_rang_secondaire- $performance->cs_nom</td>
    <td>$performance->ce_rang- $performance->ce_nom</td>
    <td>$performance->c_rang- $performance->ct_nom</td>
    <td>$circuit</td>
    <td>$voiture</td>
    <td>$performance->up_classement</td>
    <td>$recompense</td>
    <td>$performance->up_reputation</td>
    <td>$performance->up_temps</td>
    <td>$vitesse</td>
    <td>$distance</td>
    <td>$performance->up_nb_depassements</td>
    <td>
        <a class="editer" href="controleur.php?action=modifier_performance&up_id=$performance->up_id" title="Editer la performance"></a>
        <a class="supprimer" href="JavaScript:alertFunction($performance->up_id)" title="Supprimer la performance"></a>
    </td>
</tr>

EOT;

    }
}

// Scripts utilisés pour la suppression et le filtrage par catégorie, par série et par évenement
$scripts = <<<EOT

function alertFunction(id) {
    var r=confirm("Voulez-vous vraiment supprimer cette performance ?");
    if (r == true) {
        var url = "controleur.php?action=supprimer_performance&up_id=" + id;
            location.replace(url);
    }
}

function filtrerCategorie() {
    i = document.getElementById("categorie").selectedIndex;
    idCategorie = document.getElementById("categorie").options[i].value;
    parent.location.href = "controleur.php?action=mes_performances&cca_id=" + idCategorie + "&cs_id=&ce_id=";
}

function filtrerSerie() {
    i = document.getElementById("serie").selectedIndex;
    idSerie = document.getElementById("serie").options[i].value;
    parent.location.href = "controleur.php?action=mes_performances&cs_id=" + idSerie + "&ce_id=";
}

function filtrerEvenement() {
    i = document.getElementById("evenement").selectedIndex;
    idEvenement = document.getElementById("evenement").options[i].value;
    parent.location.href = "controleur.php?action=mes_performances&ce_id=" + idEvenement;
}

EOT;

// Affichage de la liste des performances
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
    <th>Course</th>
    <th>Circuit <span onclick="sortTable(4, 1, false, false, false)">&#9650;</span><span onclick="sortTable(4, 1, false, false, true)">&#9660;</span></th>
    <th>Voiture <span onclick="sortTable(5, 1, false, false, false)">&#9650;</span><span onclick="sortTable(5, 1, false, false, true)">&#9660;</span></th>
    <th>Class<sup>t</sup></th>
    <th>Récomp. <span onclick="sortTable(7, 1, true, false, false)">&#9650;</span><span onclick="sortTable(7, 1, true, false, true)">&#9660;</span></th>
    <th>Rep. <span onclick="sortTable(8, 1, true, false, false)">&#9650;</span><span onclick="sortTable(8, 1, true, false, true)">&#9660;</span></th>
    <th>Temps <span onclick="sortTable(9, 1, false, false, false)">&#9650;</span><span onclick="sortTable(9, 1, false, false, true)">&#9660;</span></th>
    <th>Vitesse <span onclick="sortTable(10, 1, true, false, false)">&#9650;</span><span onclick="sortTable(10, 1, true, false, true)">&#9660;</span></th>
    <th>Distance <span onclick="sortTable(11, 1, true, false, false)">&#9650;</span><span onclick="sortTable(11, 1, true, false, true)">&#9660;</span></th>
    <th>Dépassements <span onclick="sortTable(12, 1, true, false, false)">&#9650;</span><span onclick="sortTable(12, 1, true, false, true)">&#9660;</span></th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
$lignes
</tbody>
</table>
EOT;

require "../config/gabarit.php";
