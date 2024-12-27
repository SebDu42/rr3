<?php

require "vue_menus.php";

$contenu = '';

// Liste pour la sélection du constructeur
$selected = $idConstructeur == '' ? " selected" : '';
$optionsConstructeur = "\t<option value=''$selected>Tous</option>\n";
while ($constructeur = $constructeurs->fetch_object()) {
    $selected = $constructeur->vco_id == $idConstructeur ? " selected" : '';
    $optionsConstructeur .= "\t<option value=\"$constructeur->vco_id\"$selected>$constructeur->vco_nom</option>\n";
}

// Préparation des lignes du tableau des voitures associées
$lignesAssociees = '';
while ($voiture = $voituresAssociees->fetch_object()) {
    $lignesAssociees .= <<<EOT
<tr>
<td>$voiture->vco_nom</td>
<td>$voiture->v_modele</td>
<td style="text-align: right">$voiture->v_vitesse km/h</td>
<td style="text-align: right">$voiture->v_acceleration s</td>
<td style="text-align: right">$voiture->v_freinage m</td>
<td style="text-align: right">$voiture->v_adherence g</td>
<td style="text-align: right">$voiture->v_ip</td>
<td>            
    <a class="retirer" href="controleur.php?action=retirer_voiture&va_id=$voiture->va_id" title="Retirer la voiture des voitures assocées"></a>
</td>
</tr>

EOT;
}

// Préparation des lignes du tableau des voitures disponibles
$lignesDisponibles = '';
while ($voiture = $voituresDisponibles->fetch_object()) {
    $lignesDisponibles .= <<<EOT
<tr>
<td>$voiture->vco_nom</td>
<td>$voiture->v_modele</td>
<td style="text-align: right">$voiture->v_vitesse km/h</td>
<td style="text-align: right">$voiture->v_acceleration s</td>
<td style="text-align: right">$voiture->v_freinage m</td>
<td style="text-align: right">$voiture->v_adherence g</td>
<td style="text-align: right">$voiture->v_ip</td>
<td>            
    <a class="ajouter" href="controleur.php?action=ajouter_voiture&cs_id=$idSerie&v_id=$voiture->v_id" title="Associer la voiture"></a>
</td>
</tr>

EOT;
}

// Scripts utilisé pour le filtrage par constructeur
$scripts = <<<EOT

function filtrerConstructeur() {
   i = document.getElementById("constructeur").selectedIndex;
   idConstructeur = document.getElementById("constructeur").options[i].value;
   parent.location.href = "controleur.php?action=associer_voitures&cs_id=$idSerie&vco_id=" + idConstructeur;
}

EOT;

// Affichage des listes des voitures associées et des voitures disponibles
$nomSerie = $serie["cs_nom"];
$contenu .= <<<EOT
    
<h1>$titre :</h1>
<h2>$nomSerie</h2>
<fieldset>
    <legend>Voitures associées</legend>
    <table id="liste_associees">
    <thead>
    <tr>
        <th rowspan=2>Constructeur</th>
        <th rowspan=2>Modèle</th>
        <th colspan=5>Performances</th>
        <th rowspan=2>Action</th>
    </tr>
    <tr>
        <th>Vit.</th>
        <th>Acc.</th>
        <th>Freinage</th>
        <th>Adher.</th>
        <th>I.P.</th>
    </tr>
    </thead>
    <tbody>
    $lignesAssociees
    </tbody>
    </table>
</fieldset>
<fieldset>
    <legend>Voitures disponibles</legend>
    <label for="constructeur">Constructeur :&nbsp;</label>
    <select id="constructeur" name="vco_id" onChange="filtrerConstructeur()">
    $optionsConstructeur
    </select><br />
    <table id="liste_disponibles">
    <thead>
    <tr>
        <th rowspan=2>Constructeur</th>
        <th rowspan=2>Modèle</th>
        <th colspan=5>Performances</th>
        <th rowspan=2>Action</th>
    </tr>
    <tr>
        <th>Vit.</th>
        <th>Acc.</th>
        <th>Freinage</th>
        <th>Adher.</th>
        <th>I.P.</th>
    </tr>
    </thead>
    <tbody>
    $lignesDisponibles
    </tbody>
    </table>
</fieldset>
EOT;
    
require "../config/gabarit.php";

?>