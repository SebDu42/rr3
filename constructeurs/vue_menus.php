<?php

// Menu principal
$menuPrincipal = genererMenuPrincipal($itemsMenuPrincipal, "constructeurs");

// Menu secondaire
$itemsMenuActions[] = ["lister", "Lister", "Lister les constructeurs"];
$itemsMenuActions[] = ["ajouter", "Ajouter", "Ajouter un constructeur"];

$menuActions = genererMenuActions($itemsMenuActions, "constructeurs");

?>