<?php

// Menu principal
$menuPrincipal = genererMenuPrincipal($itemsMenuPrincipal, "categories");

// Menu secondaire
$itemsMenuActions[] = ["lister", "Lister", "Lister les catégories"];
$itemsMenuActions[] = ["ajouter", "Ajouter", "Ajouter une catégorie"];

$menuActions = genererMenuActions($itemsMenuActions, "categories");

?>