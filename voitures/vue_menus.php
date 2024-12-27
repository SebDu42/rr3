<?php

// Menu principal
$menuPrincipal = genererMenuPrincipal($itemsMenuPrincipal, "voitures");

// Menu secondaire
$itemsMenuActions[] = ["mes_voitures", "Mes voitures", "Lister mes voitures"];
$itemsMenuActions[] = ["lister", "Lister", "Lister les voitures disponibles"];
$itemsMenuActions[] = ["ajouter", "Ajouter", "Ajouter un modèle de voiture"];

$menuActions = genererMenuActions($itemsMenuActions, "voitures");

?>