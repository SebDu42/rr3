<?php

// Menu principal
$menuPrincipal = genererMenuPrincipal($itemsMenuPrincipal, "courses");

// Menu secondaire
$itemsMenuActions[] = ["mes_performances", "Mes performances", "Lister mes performances"];
$itemsMenuActions[] = ["lister_courses", "Lister", "Lister les courses"];
$itemsMenuActions[] = ["ajouter_course", "Ajouter", "Ajouter une courses"];

$menuActions = genererMenuActions($itemsMenuActions, "courses");

?>
