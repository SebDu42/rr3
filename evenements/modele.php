<?php
// FIXME: Sécuriser les données contre l'injection SQL

require "../config/bd.php";

// Ajout d'un nouvel événement
function mAjouterEvenement($donnees) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableEvenements = $prefixeTables.$tables["courses"]["evenements"];

    foreach(array_keys($donnees) as $cle) {
        if ($cle != "ce_id") {
            $champs[] = "`$cle`";
            $valeurs[] = "'".$con->real_escape_string($donnees[$cle])."'";
        }
    }
    $champs = implode(", ", $champs);
    $valeurs = implode(", ", $valeurs);

    $requete = "INSERT INTO `$tableEvenements` ";
    $requete .= "($champs) ";
    $requete .= "VALUE ($valeurs)";

    $con->query($requete);

}

// Retire une voiture de la liste des voitures associées à un événement
function mAjouterExclusion($idEvenement, $idAssociation) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!mEstExclue($idEvenement, $idAssociation)) {

        if (!isset($con)) $con = connecterBase();

        $tableExclusions = $prefixeTables.$tables["voitures"]["exclues"];

        $idEvenement = $con->real_escape_string($idEvenement);
        $idAssociation = $con->real_escape_string($idAssociation);

        $requete = "INSERT INTO `$tableExclusions` ";
        $requete .= "(`ve_fk_va_id`, `ve_fk_ce_id`) ";
        $requete .= "VALUE ($idAssociation, $idEvenement)";

        $con->query($requete);

    }

}

// Retourne true si l'id passé en parmètre correspond à une association voiture / série existante.
function mAssociationExiste($idAssociation) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableAssociations = $prefixeTables.$tables["voitures"]["associees"];

    $requete = "SELECT * ";
    $requete .= "FROM `$tableAssociations` ";
    $requete .= "WHERE `va_id` = '$idAssociation';";

    $resultat = $con->query($requete);

    return ($resultat->num_rows != 0);

}

// Retourne true si l'id passé en parmètre correspond à une catégorie
function mCategorieExiste($idCategorie) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableCategories = $prefixeTables.$tables["courses"]["categories"];

    $requete = "SELECT * ";
    $requete .= "FROM `$tableCategories` ";
    $requete .= "WHERE `cca_id` = '$idCategorie';";

    $resultat = $con->query($requete);

    return ($resultat->num_rows != 0);

}

// Retourne true si l'id passé en parmètre correspond à un constructeur
function mConstructeurExiste($idConstructeur) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableConstructeurs = $prefixeTables.$tables["voitures"]["constructeurs"];

    $requete = "SELECT * ";
    $requete .= "FROM `$tableConstructeurs` ";
    $requete .= "WHERE `vco_id` = '$idConstructeur';";

    $resultat = $con->query($requete);

    return ($resultat->num_rows != 0);

}

// Retourne true si la voiture est déjà retirée de la liste des voitures associées à l'événement
function mEstExclue($idEvenement, $idAssociation) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableExclusions = $prefixeTables.$tables["voitures"]["exclues"];

    $idEvenement = $con->real_escape_string($idEvenement);
    $idAssociation = $con->real_escape_string($idAssociation);

    $requete = "SELECT * ";
    $requete .= "FROM `$tableExclusions` ";
    $requete .= "WHERE `ve_fk_ce_id` = '$idEvenement' AND `ve_fk_va_id` = '$idAssociation';";

    $resultat = $con->query($requete);

    return ($resultat->num_rows != 0);

}

// Retourne true si l'id passé en parmètre correspond à une catégorie
function mEvenementExiste($idEvenement) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableEvenements = $prefixeTables.$tables["courses"]["evenements"];

    $requete = "SELECT * ";
    $requete .= "FROM `$tableEvenements` ";
    $requete .= "WHERE `ce_id` = '$idEvenement';";

    $resultat = $con->query($requete);

    return ($resultat->num_rows != 0);

}

// Retourne true si l'id passé en parmètre correspond à une exclusion existante.
function mExclusionExiste($idExclusion) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableExclusions = $prefixeTables.$tables["voitures"]["exclues"];

    $requete = "SELECT * ";
    $requete .= "FROM `$tableExclusions` ";
    $requete .= "WHERE `ve_id` = '$idExclusion';";

    $resultat = $con->query($requete);

    return ($resultat->num_rows != 0);

}

// Modification d'un événement
function mModifierEvenement($donnees) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableEvenements = $prefixeTables.$tables["courses"]["evenements"];

    $id = $donnees["ce_id"];
    foreach(array_keys($donnees) as $cle) {
        if ($cle != "ce_id") {
            $donneesAJour[] = "`$cle` = '".$con->real_escape_string($donnees[$cle])."'" ;
        }
    }
    $donneesAJour = implode(", ", $donneesAJour);

    $requete = "UPDATE `$tableEvenements` ";
    $requete .= "SET $donneesAJour ";
    $requete .= "WHERE `ce_id` = '$id';";

    $con->query($requete);

}

// Récupération d'un événement par son id
function mRecupererEvenement($id) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableEvenements = $prefixeTables.$tables["courses"]["evenements"];
    $tableSeries = $prefixeTables.$tables["courses"]["series"];
    $tableCategories = $prefixeTables.$tables["courses"]["categories"];

    $requete = "SELECT * ";
    $requete .= "FROM `$tableEvenements`, `$tableSeries`, `$tableCategories` ";
    $requete .= "WHERE `ce_id` = '$id' ";
    $requete .= "AND `ce_fk_cs_id` = `cs_id` ";
    $requete .= "AND `cs_fk_cca_id` = `cca_id`; ";

    $evenement = $con->query($requete);

    return $evenement->fetch_assoc();

}

// Récupération de toutes les catégories
function mRecupererToutesCategories() {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableCategories = $prefixeTables.$tables["courses"]["categories"];
    $tableSeries = $prefixeTables.$tables["courses"]["series"];

    $requete = "SELECT * ";
    $requete .= "FROM `$tableCategories` ";
    $requete .= "WHERE `cca_id` IN ( ";
    $requete .= "SELECT `cs_fk_cca_id` ";
    $requete .= "FROM `$tableSeries` ";
    $requete .= "GROUP BY `cs_fk_cca_id` ";
    $requete .= ") ";
    $requete .= "ORDER BY `cca_fk_ccap_id`, `cca_rang` ASC;";

    $categories = $con->query($requete);

    return $categories;

}

// Récupération de tous les constructeurs
function mRecupererTousConstructeurs() {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableConstructeurs = $prefixeTables.$tables["voitures"]["constructeurs"];

    $requete = "SELECT * ";
    $requete .= "FROM `$tableConstructeurs` ";
    $requete .= "ORDER BY `vco_nom` ASC;";

    $constructeurs = $con->query($requete);

    return $constructeurs;

}

// Récupération de tous les événements, éventuellement filtrés par une catégorie et une série.
function mRecupererTousEvenements($idCategorie, $idSerie) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableEvenements = $prefixeTables.$tables["courses"]["evenements"];
    $tableSeries = $prefixeTables.$tables["courses"]["series"];
    $tableCategories = $prefixeTables.$tables["courses"]["categories"];

    $filtre = $idCategorie == '' ? '' : "AND `cs_fk_cca_id` = '$idCategorie' ";
    $filtre .= $idSerie == '' ? '' : "AND `ce_fk_cs_id` = '$idSerie' ";

    $requete = "SELECT * ";
    $requete .= "FROM `$tableEvenements`, `$tableSeries`, `$tableCategories` ";
    $requete .= "WHERE `ce_fk_cs_id` = `cs_id` AND `cs_fk_cca_id`= `cca_id` ";
    $requete .= $filtre;
    $requete .= "ORDER BY `cca_rang` ASC, `cs_rang_principal` ASC, `cs_rang_secondaire` ASC, `ce_rang` ASC;";

    $evenements = $con->query($requete);

    return $evenements;

}

// Récupération de toures les séries, eventuellement filtrées par une catégorie
function mRecupererToutesSeries($idCategorie) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableSeries = $prefixeTables.$tables["courses"]["series"];

    $filtre = $idCategorie == '' ? '' : "WHERE `cs_fk_cca_id` = '$idCategorie' ";

    $requete = "SELECT * FROM ";
    $requete .= "`$tableSeries` ";
    $requete .= $filtre;
    $requete .= "ORDER BY `cs_rang_principal` ASC, `cs_rang_secondaire` ASC;";

    $series = $con->query($requete);

    return $series;

}

// Récupération de toutes les voitures associées à un événement
function mRecupererVoituresAssociees($idEvenement) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableVoitures = $prefixeTables.$tables["voitures"][0];
    $tableConstructeurs = $prefixeTables.$tables["voitures"]
        ["constructeurs"];
    $tableEvenements = $prefixeTables.$tables["courses"]["evenements"];
    $tableExclusions = $prefixeTables.$tables["voitures"]
        ["exclues"];
    $tableAssociations = $prefixeTables.$tables["voitures"]
        ["associees"];

    $requete = "SELECT * ";
    $requete .= "FROM `$tableEvenements`, `$tableAssociations`, `$tableVoitures`, `$tableConstructeurs` ";
    $requete .= "WHERE `ce_id` = '$idEvenement' AND `ce_fk_cs_id` = `va_fk_cs_id` AND `va_fk_v_id` = `v_id` AND `v_fk_vco_id` = `vco_id` ";
    $requete .= " AND `va_id` NOT IN (";
    $requete .= "SELECT `ve_fk_va_id` ";
    $requete .= "FROM `$tableExclusions` ";
    $requete .= "WHERE `ve_fk_ce_id` = '$idEvenement'";
    $requete .= ") ";
    $requete .= "ORDER BY `v_ip` ASC, `vco_nom` ASC;";

    $voitures = $con->query($requete);

    return $voitures;

}

// Récupération de toutes les voitures non associées à un événement
function mRecupererVoituresExclues($idEvenement) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableVoitures = $prefixeTables.$tables["voitures"][0];
    $tableConstructeurs = $prefixeTables.$tables["voitures"]
        ["constructeurs"];
    $tableExclusions = $prefixeTables.$tables["voitures"]
        ["exclues"];
    $tableAssociations = $prefixeTables.$tables["voitures"]
        ["associees"];

    $requete = "SELECT * ";
    $requete .= "FROM `$tableExclusions`, `$tableAssociations`, `$tableVoitures`, `$tableConstructeurs` ";
    $requete .= "WHERE `ve_fk_ce_id` = '$idEvenement' AND `ve_fk_va_id` = `va_id` AND `va_fk_v_id` = `v_id` AND `v_fk_vco_id` = `vco_id` ";
    $requete .= "ORDER BY `vco_nom` ASC, `v_ip` ASC;";

    $voitures = $con->query($requete);

    return $voitures;

}

// Retrait d'une voiture de la liste des voitures exclues d'un événement
function mRetirerExclusion($idExclusion) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableExclusions = $prefixeTables.$tables["voitures"]["exclues"];

    $idExclusion = $con->real_escape_string($idExclusion);

    $requete = "DELETE FROM `$tableExclusions` ";
    $requete .= "WHERE `ve_id` = '$idExclusion';";

    $con->query($requete);

}

// Retourne true si l'id passé en parmètre correspond à une série
function mSerieExiste($idSerie) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableSeries = $prefixeTables.$tables["courses"]["series"];

    $requete = "SELECT * ";
    $requete .= "FROM `$tableSeries` ";
    $requete .= "WHERE `cs_id` = '$idSerie';";

    $resultat = $con->query($requete);

    return ($resultat->num_rows != 0);

}

// Suppression d'un événement
function mSupprimerEvenement($idEvenement) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableEvenements = $prefixeTables.$tables["courses"]["evenements"];

    $requete = "DELETE FROM `$tableEvenements` ";
    $requete .= "WHERE `ce_id` = '$idEvenement';";

    $con->query($requete);

}

// Retourne true si l'id passé en parmètre correspond à une série
function mVoitureExiste($idVoiture) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableVoitures = $prefixeTables.$tables["voitures"][0];

    $requete = "SELECT * ";
    $requete .= "FROM `$tableVoitures` ";
    $requete .= "WHERE `v_id` = '$idVoiture';";

    $resultat = $con->query($requete);

    return ($resultat->num_rows != 0);

}
