<?php

require "vue_menus.php";

$contenu = '';

// Préparation des lignes du tableau
$lignes = '';    
if (isset($categories)) {
    while ($categorie = $categories->fetch_object()) {
        $CP = $categorie->cca_fk_ccap_id == 1 ? "SA" : "CR";
        $lignes .= <<<EOT
<tr>
    <td>$CP$categorie->cca_rang</td>
    <td>$categorie->cca_nom</td>
    <td>
        <a class="editer" href="controleur.php?action=modifier&cca_id=$categorie->cca_id" title="Editer la catégorie"></a>
        <a class="supprimer" href="JavaScript:alertFunction($categorie->cca_id)" title="Supprimer la catégorie"></a>
        <a class="ajouter" href="../series/controleur.php?action=ajouter&cca_id=$categorie->cca_id" title="Ajouter une série"></a>
        <a class="voir" href="../series/controleur.php?action=lister&cca_id=$categorie->cca_id" title="Voir les séries"></a>
    </td>
</tr>
EOT;
    }
}

// Script utilisé pour la suppression
$scripts = <<<EOT

function alertFunction(id) {
    var r=confirm("Voulez-vous vraiment supprimer cette categorie ?");
    if (r == true) {
        var url = "controleur.php?action=supprimer&cca_id=" + id;
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