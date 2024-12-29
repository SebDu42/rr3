<?php

require "vue_menus.php";

$contenu = '';

// Liste pour la sélection du constructeur
$selection = $idConstructeur == '' ? " selected" : '';
$options = "\t\t<option value=''$selection>Tous</option>\n";
while ($constructeur = $constructeurs->fetch_object()) {
    $selection = $constructeur->vco_id == $idConstructeur ? " selected" : '';
    $options .= "\t\t<option value=\"$constructeur->vco_id\"$selection>$constructeur->vco_nom</option>\n";
}

// Liste pour la sélection du statut
if ($action == "mes_voitures") {
    $selection = $idStatut == '' ? " selected" : '';
    $optionsStatuts = "\t\t<option value=''$selection>Tous</option>\n";
    while ($statut = $statuts->fetch_object()) {
        $selection = $statut->uvs_id == $idStatut ? " selected" : '';
        $optionsStatuts .= "\t\t<option value=\"$statut->uvs_id\"$selection>$statut->uvs_nom</option>\n";
    }
}

// Préparation des lignes du tableau
$lignes = '';
$cpt_ligne = 0;
while ($voiture = $voitures->fetch_object()) {
    $cpt_ligne += 1;
    $aTotal = isset($voiture->v_am_moteur_max) ? $voiture->v_am_moteur_max : 0;
    $aTotal += isset($voiture->v_am_transmission_max) ? $voiture->v_am_transmission_max : 0;
    $aTotal += isset($voiture->v_am_carrosserie_max) ? $voiture->v_am_carrosserie_max : 0;
    $aTotal += isset($voiture->v_am_suspension_max) ? $voiture->v_am_suspension_max : 0;
    $aTotal += isset($voiture->v_am_pot_max) ? $voiture->v_am_pot_max : 0;
    $aTotal += isset($voiture->v_am_freins_max) ? $voiture->v_am_freins_max : 0;
    $aTotal += isset($voiture->v_am_roues_max) ? $voiture->v_am_roues_max : 0;
    if ($action == "lister") {
        $statut = '<td style="display: None;"></td>';
        $vitesse = $voiture->v_vitesse;
        $acceleration = $voiture->v_acceleration;
        $freinage = $voiture->v_freinage;
        $adherence = $voiture->v_adherence;
        $ip = $voiture->v_ip;
        $aMoteur = isset($voiture->v_am_moteur_max) ? $voiture->v_am_moteur_max : '';
        $aTransmission = isset($voiture->v_am_transmission_max) ? $voiture->v_am_transmission_max : '';
        $aCarrosserie = isset($voiture->v_am_carrosserie_max) ? $voiture->v_am_carrosserie_max : '';
        $aSuspension = isset($voiture->v_am_suspension_max) ? $voiture->v_am_suspension_max : '';
        $aPot = isset($voiture->v_am_pot_max) ? $voiture->v_am_pot_max : '';
        $aFreins = isset($voiture->v_am_freins_max) ? $voiture->v_am_freins_max : '';
        $aRoues = isset($voiture->v_am_roues_max) ? $voiture->v_am_roues_max : '';
    }
    else {
        $statut = "<td>$voiture->uvs_nom</td>";
        $vitesse = $voiture->uv_vitesse;
        $acceleration = $voiture->uv_acceleration;
        $freinage = $voiture->uv_freinage;
        $adherence = $voiture->uv_adherence;
        $ip = $voiture->uv_ip;
        $aMoteur = isset($voiture->v_am_moteur_max) ? "$voiture->uv_am_moteur/$voiture->v_am_moteur_max" : '';
        $aTransmission = isset($voiture->v_am_transmission_max) ? "$voiture->uv_am_transmission/$voiture->v_am_transmission_max" : '';
        $aCarrosserie = isset($voiture->v_am_carrosserie_max) ? "$voiture->uv_am_carrosserie/$voiture->v_am_carrosserie_max" : '';
        $aSuspension = isset($voiture->v_am_suspension_max) ? "$voiture->uv_am_suspension/$voiture->v_am_suspension_max" : '';
        $aPot = isset($voiture->v_am_pot_max) ? "$voiture->uv_am_pot/$voiture->v_am_pot_max" : '';
        $aFreins = isset($voiture->v_am_freins_max) ? "$voiture->uv_am_freins/$voiture->v_am_freins_max" : '';
        $aRoues = isset($voiture->v_am_roues_max) ? "$voiture->uv_am_roues/$voiture->v_am_roues_max" : '';
        $aVoiture = isset($voiture->uv_am_moteur) ? $voiture->uv_am_moteur : 0;
        $aVoiture += isset($voiture->uv_am_transmission) ? $voiture->uv_am_transmission : 0;
        $aVoiture += isset($voiture->uv_am_carrosserie) ? $voiture->uv_am_carrosserie : 0;
        $aVoiture += isset($voiture->uv_am_suspension) ? $voiture->uv_am_suspension : 0;
        $aVoiture += isset($voiture->uv_am_pot) ? $voiture->uv_am_pot : 0;
        $aVoiture += isset($voiture->uv_am_freins) ? $voiture->uv_am_freins : 0;
        $aVoiture += isset($voiture->uv_am_roues) ? $voiture->uv_am_roues : 0;
        $aTotal = "$aVoiture/$aTotal";
    }
    $coutRevisionInstantanee = isset($voiture->v_cout_revision_instantanee) ? "$voiture->v_cout_revision_instantanee Or" : '';
    $idVoiture = $action == "lister" ? "v_id=$voiture->v_id" : "uv_id=$voiture->uv_id";
    $actionAjouter = $action == "lister" ? "<a class=\"ajouter\" href=\"controleur.php?action=ajouter&$idVoiture\" title=\"Ajouter à mes voitures\"></a>" : '';
    $lienO = isset($voiture->v_url) ? '<a href="'.$voiture->v_url.'" target="_blank">' : '';
    $lienF = isset($voiture->v_url) ? '</a>' : '';
    $lignes .= <<<EOT
<tr>
<td>$cpt_ligne</td>
$statut
<td>$voiture->vco_nom</td>
<td>$lienO$voiture->v_modele$lienF</td>
<td>$voiture->vcl_nom</td>
<td>$voiture->vt_nom</td>
<td>$voiture->v_prix&nbsp;$voiture->m_nom</td>
<td style="text-align: right">$vitesse&nbsp;km/h</td>
<td style="text-align: right">$acceleration&nbsp;s</td>
<td style="text-align: right">$freinage&nbsp;m</td>
<td style="text-align: right">$adherence&nbsp;g</td>
<td style="text-align: right">$ip</td>
<td style="text-align: right">$voiture->v_cout_revision&nbsp;R$</td>
<td style="text-align: right">$voiture->v_duree_revision</td>
<td style="text-align: right">$coutRevisionInstantanee</td>
<td style="text-align: right">$aMoteur</td>
<td style="text-align: right">$aTransmission</td>
<td style="text-align: right">$aCarrosserie</td>
<td style="text-align: right">$aSuspension</td>
<td style="text-align: right">$aPot</td>
<td style="text-align: right">$aFreins</td>
<td style="text-align: right">$aRoues</td>
<td style="text-align: right">$aTotal</td>
<td>
    <a class="editer" href="controleur.php?action=modifier&$idVoiture" title="Editer la voiture"></a>
    <a class="supprimer" href="JavaScript:alertFunction('$idVoiture')" title="Supprimer la voiture"></a>
    $actionAjouter
</td>
</tr>

EOT;
}

// Scripts utilisés pour la suppression et le filtre par constructeur
$scripts = <<<EOT

function alertFunction(id) {
    var r=confirm("Voulez-vous vraiment supprimer cette voiture ?");
    if (r == true) {
        var url = "controleur.php?action=supprimer&" + id;
            location.replace(url);
    }
}

function filtrerConstructeur() {
   i = document.getElementById("constructeur").selectedIndex;
   idConstructeur = document.getElementById("constructeur").options[i].value;
   parent.location.href = "controleur.php?action=$action&vco_id="+idConstructeur;
}

function filtrerStatut() {
    i = document.getElementById("statut").selectedIndex;
    idStatut = document.getElementById("statut").options[i].value;
    parent.location.href = "controleur.php?action=mes_voitures&uvs_id="+idStatut;
}

EOT;

// Affichage de la liste des séries
$amelioration = $action == "lister" ? "Améliorations max." : "Améliorations";
$contenu .= <<<EOT

<h1>$titre :</h1>
<div id="filtres">&nbsp;
    <label for="constructeur">Constructeur&nbsp;:&nbsp;</label>
    <select id="constructeur" name="vco_id" onChange="filtrerConstructeur()">
$options
    </select>
EOT;

if ($action == "mes_voitures") {
    $contenu .= <<<EOT
    <label for="statut">Statut&nbsp;:&nbsp;</label>
    <select id="statut" name="css_id" onChange="filtrerStatut()">
$optionsStatuts
    </select>
EOT;
}

$titreStatut = $action == "mes_voitures" ? "<th rowspan=2>Statut</th>" : "<th rowspan=2 style=\"display: None;\"></th>";

$contenu .= <<<EOT
</div>
<br>
<table id="liste">
<thead>
<tr>
    <th rowspan=2>N°<span onclick="sortTable(0, 2, true, false, false)">&#9650;</span><span onclick="sortTable(0, 2, true, false, true)">&#9660;</span></th>
    $titreStatut
    <th rowspan=2>Constructeur <span onclick="sortTable(2, 2, false, false, false)">&#9650;</span><span onclick="sortTable(2, 2, false, false, true)">&#9660;</span></th>
    <th rowspan=2>Modèle <span onclick="sortTable(3, 2, false, true, false)">&#9650;</span><span onclick="sortTable(3, 2, false, true, true)">&#9660;</span></th>
    <th rowspan=2>Cat. <span onclick="sortTable(4, 2, false, false, false)">&#9650;</span><span onclick="sortTable(4, 2, false, false, true)">&#9660;</span></th>
    <th rowspan=2>Trans. <span onclick="sortTable(5, 2, false, false, false)">&#9650;</span><span onclick="sortTable(5, 2, false, false, true)">&#9660;</span></th>
    <th rowspan=2>Prix <span onclick="sortTable(6, 2, true, false, false)">&#9650;</span><span onclick="sortTable(6, 2, true, false, true)">&#9660;</span></th>
    <th colspan=5>Performances</th>
    <th colspan=3>Révision</th>
    <th colspan=8>$amelioration</th>
    <th rowspan=2>Actions</th>
</tr>
<tr>
    <th>Vit. <span onclick="sortTable(7, 2, true, false, false)">&#9650;</span><span onclick="sortTable(7, 2, true, false, true)">&#9660;</span></th>
    <th>Acc. <span onclick="sortTable(8, 2, true, false, false)">&#9650;</span><span onclick="sortTable(8, 2, true, false, true)">&#9660;</span></th>
    <th>Freinage <span onclick="sortTable(9, 2, true, false, false)">&#9650;</span><span onclick="sortTable(9, 2, true, false, true)">&#9660;</span></th>
    <th>Adher. <span onclick="sortTable(10, 2, true, false, false)">&#9650;</span><span onclick="sortTable(10, 2, true, false, true)">&#9660;</span></th>
    <th>I.P. <span onclick="sortTable(11, 2, true, false, false)">&#9650;</span><span onclick="sortTable(11, 2, true, false, true)">&#9660;</span></th>
    <th>Coût</th>
    <th>Durée</th>
    <th>Inst.</th>
    <th>Mot.</th>
    <th>Trans.</th>
    <th>Carros.</th>
    <th>Susp.</th>
    <th>Pot</th>
    <th>Freins</th>
    <th>Roues</th>
    <th>Total</th>
</tr>
</thead>
<tbody>
$lignes
</tbody>
</table>

EOT;

require "../config/gabarit.php";
