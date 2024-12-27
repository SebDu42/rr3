<?php

require "vue_menus.php";

$contenu = '';

// Préparation des lignes du tableau
$lignes = '';
if (isset($constructeurs) && $constructeurs) {
    while ($constructeur = $constructeurs->fetch_object()) {
        $lienO = isset($constructeur->vco_url) ? '<a href="'.$constructeur->vco_url.'" target="_blank">' : '';
        $lienF = isset($constructeur->vco_url) ? '</a>' : '';
        $lignes .= <<<EOT
<tr>
    <td>$lienO$constructeur->vco_nom$lienF</td>
    <td>
        <a class="editer" href="controleur.php?action=modifier&vco_id=$constructeur->vco_id" title="Editer la catégorie"></a>
        <a class="supprimer" href="JavaScript:alertFunction($constructeur->vco_id)" title="Supprimer la catégorie"></a>
        <a class="ajouter" href="../voitures/controleur.php?action=ajouter&vco_id=$constructeur->vco_id" title="Ajouter une voiture"></a>
        <a class="voir" href="../voitures/controleur.php?action=mes_voitures&vco_id=$constructeur->vco_id" title="Voir mes voitures"></a>
    </td>
</tr>

EOT;
    }
}

// Scripts utilisé pour la suppression
$scripts = <<<EOT
    
function alertFunction(id) {
    var r=confirm("Voulez-vous vraiment supprimer ce constructeur ?");
    if (r == true) {
        var url = "controleur.php?action=supprimer&vco_id=" + id;
            location.replace(url);
    }
}

EOT;

// Affichage de la liste des constructeurs
$contenu .= <<<EOT
    
<h1>$titre :</h1>
<table id="liste">
<thead>
<tr>
    <th>Nom <span onclick="sortTable(0, 1, false, true, false)">&#9650;</span><span onclick="sortTable(0, 1, false, true, true)">&#9660;</span></th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
$lignes
</tbody>
</table>\n

EOT;

require "../config/gabarit.php";

?>