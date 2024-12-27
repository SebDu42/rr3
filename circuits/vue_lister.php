<?php

// TODO: transférer l'appel à la fonction "mRecupererConfigurationsCircuit" dans le contrôleur.

require "vue_menus.php";

$contenu = '';

// Préparation des lignes du tableau
$lignes = '';    
if (isset($circuits)) {
    while ($circuit = $circuits->fetch_object()) {
        $configurations = mRecupererConfigurationsCircuit($circuit->ci_id);
        $configuration = $configurations->fetch_object();
        $lienO = isset($circuit->ci_url) ? '<a href="'.$circuit->ci_url.'" target="_blank">' : '';
        $lienF = isset($circuit->ci_url) ? '</a>' : '';
        $lignes .= <<<EOT
<tr>
    <td rowspan=$configurations->num_rows>$lienO$circuit->ci_nom$lienF</td>
    <td rowspan=$configurations->num_rows>$circuit->ci_localisation</td>
    <td>$configuration->cic_nom</td>
    <td>$configuration->cic_longueur&nbsp;kms</td>
    <td>
        <a class="editer" href="controleur.php?action=modifier&cic_id=$configuration->cic_id" title="Editer la configuration"></a>
        <a class="supprimer" href="JavaScript:alertFunction($configuration->cic_id)" title="Supprimer la configuration"></a>
    </td>
    <td  rowspan=$configurations->num_rows>
        <a class="ajouter" href="controleur.php?action=ajouter&ci_id=$circuit->ci_id" title="Ajouter une configuration"></a>
    </td>
</tr>

EOT;
        while ($configuration = $configurations->fetch_object()) {
            $lignes .= <<<EOT
<tr>
    <td>$configuration->cic_nom</td>
    <td>$configuration->cic_longueur&nbsp;kms</td>
    <td>
        <a class="editer" href="controleur.php?action=modifier&cic_id=$configuration->cic_id" title="Editer la configuration"></a>
        <a class="supprimer" href="JavaScript:alertFunction($configuration->cic_id)" title="Supprimer la configuration"></a>
    </td>
</tr>

EOT;
        }
    }
}

// Script utilisé pour la suppression
$scripts = <<<EOT

function alertFunction(id) {
    var r=confirm("Voulez-vous vraiment supprimer cette configuration ?");
    if (r == true) {
        var url = "controleur.php?action=supprimer&cic_id=" + id;
            location.replace(url);
    }
}

EOT;


// Affichage de la liste des categories
$contenu .= <<<EOT

<h1>$titre :</h1>
<table id="liste">
<thead>
<tr>
    <th>Nom</th>
    <th>Localisation</th>
    <th>Configuration</th>
    <th>Longueur</th>
    <th colspan=2>Actions</th>
</tr>
</thead>
<tbody>
$lignes
</tbody>
</table>

EOT;

require "../config/gabarit.php";

?>