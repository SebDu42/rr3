<?php

// Menu principal
$menuPrincipal = genererMenuPrincipal($itemsMenuPrincipal, "circuits");

// Menu secondaire
$itemsMenuActions[] = ["lister", "Lister", "Lister les circuits"];
$itemsMenuActions[] = ["ajouter", "Ajouter", "Ajouter un circuit"];

$menuActions = genererMenuActions($itemsMenuActions, "circuits");

?>