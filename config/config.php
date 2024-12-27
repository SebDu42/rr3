<?php

$config = include('secret_config.php');
// Localisation de l'application
$urlDeBase = $config['urlDeBase'];
// Variables de configuration de la base
$serveurBD = $config['serveurBD'];
$utilisateurBD = $config['utilisateurBD'];
$motDePasseBD = $config['motDePasseBD'];
$base = $config['base'];

// Prefixe des tables
$prefixeTables = "rr3_";

// Noms des tables
$tables["circuits"][0] = "circuits";
$tables["circuits"]["configurations"] = "circuits_configurations";
$tables["courses"][0] = "courses";
$tables["courses"]["categories"] = "courses_categories";
$tables["courses"]["categories_principales"] = "courses_categories_principales";
$tables["courses"]["conditions"] = "courses_conditions";
$tables["courses"]["evenements"] = "courses_evenements";
$tables["courses"]["series"] = "courses_series";
$tables["courses"]["series_statuts"] = "courses_series_statuts";
$tables["courses"]["types"] = "courses_types";
$tables["monnaies"] = "monnaies";
$tables["utilisateurs"][0] = "utilisateurs";
$tables["utilisateurs"]["performances"] = "utilisateurs_performances";
$tables["utilisateurs"]["roles"] = "utilisateurs_roles";
$tables["utilisateurs"]["voitures"] = "utilisateurs_voitures";
$tables["utilisateurs"]["voitures_statuts"] = "utilisateurs_voitures_statuts";
$tables["voitures"][0] = "voitures";
$tables["voitures"]["classes"] = "voitures_classes";
$tables["voitures"]["constructeurs"] = "voitures_constructeurs";
$tables["voitures"]["exclues"] = "voitures_exclues";
$tables["voitures"]["associees"] = "voitures_associees";
$tables["voitures"]["transmissions"] = "voitures_transmissions";

// Affichage pour le debugage
function affiche($texte) {
    echo "<pre>\n";
    print_r($texte);
    echo "\n</pre>\n";
}
