<?php

require "../config/bd.php";

// Ajout d'une nouvelle catégorie
function mAjouterCategorie($donnees) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableCategories = $prefixeTables.$tables["courses"]["categories"];

    foreach(array_keys($donnees) as $cle) {
        if ($cle != "cca_id") {
            $champs[] = "`$cle`";
            $valeurs[] = "'".$con->real_escape_string($donnees[$cle])."'";
        }
    }
    $champs = implode(", ", $champs);
    $valeurs = implode(", ", $valeurs);

    $requete = "INSERT INTO `$tableCategories` ";
    $requete .= "($champs) ";
    $requete .= "VALUE ($valeurs)";

    $con->query($requete);

}

// Retourne true si l'id passé en parmètre correspond à une catégorie
function mCategorieExiste($idCategorie) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableCategories = $prefixeTables.$tables["courses"]["categories"];

    $idCategorie = $con->real_escape_string($idCategorie);

    $requete = "SELECT * ";
    $requete .= "FROM `$tableCategories` ";
    $requete .= "WHERE `cca_id` = '$idCategorie';";

    $resultat = $con->query($requete);

    return ($resultat->num_rows != 0);

}

// Modification d'une catégorie
function mModifierCategorie($donnees) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableCategories = $prefixeTables.$tables["courses"]["categories"];

    $idCategorie = $con->real_escape_string($donnees["cca_id"]);
    foreach(array_keys($donnees) as $cle) {
        if ($cle != "cca_id") {
            $donneesAJour[] = "`$cle` = '".$con->real_escape_string($donnees[$cle])."'" ;
        }
    }
    $donneesAJour = implode(", ", $donneesAJour);

    $requete = "UPDATE `$tableCategories` ";
    $requete .= "SET $donneesAJour ";
    $requete .= "WHERE `cca_id` = '$idCategorie';";

    $con->query($requete);

}

// Récupération d'une catégories par son id
function mRecupererCategorie($idCategorie) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableCategories = $prefixeTables.$tables["courses"]["categories"];

    $idCategorie = $con->real_escape_string($idCategorie);

    $requete = "SELECT * ";
    $requete .= "FROM `$tableCategories` ";
    $requete .= "WHERE `cca_id` = '".$idCategorie."';";

    $categorie = $con->query($requete);

    return $categorie->fetch_assoc();

}

// Récupération de toutes les catégories
function mRecupererToutesCategories() {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableCategories = $prefixeTables.$tables["courses"]["categories"];

    $requete = "SELECT * ";
    $requete .= "FROM `$tableCategories` ";
    $requete .= "ORDER BY `cca_fk_ccap_id`, `cca_rang` ASC;";

    $categories = $con->query($requete);

    return $categories;

}

// Récupération de toutes les catégories principales
function mRecupererToutesCategoriesPrincipales() {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableCPs = $prefixeTables.$tables["courses"]["categories_principales"];

    $requete = "SELECT * ";
    $requete .= "FROM `$tableCPs`;";
    #$requete .= "ORDER BY `ccap_nom` ASC;";

    $CPs = $con->query($requete);

    return $CPs;

}

// Suppression d'une catégorie
function mSupprimerCategorie($idCategorie) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableCategories = $prefixeTables.$tables["courses"]["categories"];

    $idCategorie = $con->real_escape_string($idCategorie);

    $requete = "DELETE ";
    $requete .= "FROM `$tableCategories` ";
    $requete .= "WHERE `cca_id` = '$idCategorie';";

    $con->query($requete);

}
