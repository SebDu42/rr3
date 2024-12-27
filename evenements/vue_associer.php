<?php

require "vue_menus.php";

$contenu = '';

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
    <a class="retirer" href="controleur.php?action=exclure_voiture&ce_id=$idEvenement&va_id=$voiture->va_id" title="Exclure la voiture de la liste des voitures associées"></a>
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
    <a class="ajouter" href="controleur.php?action=retablir_voiture&ve_id=$voiture->ve_id" title="Rétablir la voiture dans la liste des voitures associées"></a>
</td>
</tr>

EOT;
}

// Affichage des listes des voitures héritées de la série et des voitures retirées de l'événement.
$nomEvenement = $evenement["ce_nom"];
$contenu .= <<<EOT
    
<h1>$titre :</h1>
<h2>$nomEvenement</h2>
<fieldset>
    <legend>Voitures héritées de la série</legend>
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
    <legend>Voitures retirées de l'événement</legend>
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
