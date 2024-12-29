<?php

// Menu principal
$menuPrincipal = genererMenuPrincipal($itemsMenuPrincipal, "evenements");

// Menu secondaire
$itemsMenuActions[] = ["lister", "Lister", "Lister les événements"];
$itemsMenuActions[] = ["ajouter", "Ajouter", "Ajouter un événement"];
//$itemsMenuActions[] = ["associer_voiture", "Associer voitures", "Associer des voitures un événement"];

$menuActions = genererMenuActions($itemsMenuActions, "evenements");
