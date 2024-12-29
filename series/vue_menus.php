<?php

// Menu principal
$menuPrincipal = genererMenuPrincipal($itemsMenuPrincipal, "series");

// Menu secondaire
$itemsMenuActions[] = ["lister", "Lister", "Lister les séries"];
$itemsMenuActions[] = ["ajouter", "Ajouter", "Ajouter une séries"];
$menuActions = genererMenuActions($itemsMenuActions, "series");
