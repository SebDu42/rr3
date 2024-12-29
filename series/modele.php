<?php

require "../config/bd.php";

// Associer une voiture à une série
function mAjouterAssociation($idSerie, $idVoiture) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!mEstAssociee($idSerie, $idVoiture)) {

        if (!isset($con)) $con = connecterBase();

        $tableAssociations = $prefixeTables.$tables["voitures"]["associees"];

        $idSerie = $con->real_escape_string($idSerie);
        $idVoiture = $con->real_escape_string($idVoiture);

        $requete = "INSERT INTO `$tableAssociations` ";
        $requete .= "(`va_fk_v_id`, `va_fk_cs_id`) ";
        $requete .= "VALUE ($idVoiture, $idSerie)";

        $con->query($requete);

    }

}

// Ajout d'une nouvelle série
function mAjouterSerie($donnees) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableSeries = $prefixeTables.$tables["courses"]["series"];

    foreach(array_keys($donnees) as $cle) {
        if ($cle != "cs_id") {
            $champs[] = "`$cle`";
            $valeurs[] = "'".$con->real_escape_string($donnees[$cle])."'";
        }
    }
    $champs = implode(", ", $champs);
    $valeurs = implode(", ", $valeurs);

    $requete = "INSERT INTO `$tableSeries` ";
    $requete .= "($champs) ";
    $requete .= "VALUE ($valeurs)";

    $con->query($requete);

}

// Retourne true si l'id passé en parmètre correspond à une catégorie
function mAssociationExiste($idAssociation) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableAssociations = $prefixeTables.$tables["voitures"]["associees"];

    $idAssociation = $con->real_escape_string($idAssociation);

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

    $idCategorie = $con->real_escape_string($idCategorie);

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

    $idConstructeur = $con->real_escape_string($idConstructeur);

    $requete = "SELECT * ";
    $requete .= "FROM `$tableConstructeurs` ";
    $requete .= "WHERE `vco_id` = '$idConstructeur';";

    $resultat = $con->query($requete);

    return ($resultat->num_rows != 0);

}

// Retourne true si la voiture est associée à la série
function mEstAssociee($idSerie, $idVoiture) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableAssociations = $prefixeTables.$tables["voitures"]["associees"];

    $idSerie = $con->real_escape_string($idSerie);
    $idVoiture = $con->real_escape_string($idVoiture);

    $requete = "SELECT * ";
    $requete .= "FROM `$tableAssociations` ";
    $requete .= "WHERE `va_fk_cs_id` = '$idSerie' AND `va_fk_v_id` = '$idVoiture';";

    $resultat = $con->query($requete);

    return ($resultat->num_rows != 0);

}

// Modification d'une série
function mModifierSerie($donnees) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableSeries = $prefixeTables.$tables["courses"]["series"];

    $idSerie = $con->real_escape_string($donnees["cs_id"]);
    foreach(array_keys($donnees) as $cle) {
        if ($cle != "cs_id") {
            $donneesAJour[] = "`$cle` = '".$con->real_escape_string($donnees[$cle])."'" ;
        }
    }
    $donneesAJour = implode(", ", $donneesAJour);

    $requete = "UPDATE `$tableSeries` ";
    $requete .= "SET $donneesAJour ";
    $requete .= "WHERE `cs_id` = '$idSerie';";

    $con->query($requete);

}

// Récupération d'une série par son id
function mRecupererSerie($idSerie) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableSeries = $prefixeTables.$tables["courses"]["series"];

    $idSerie = $con->real_escape_string($idSerie);

    $requete = "SELECT * ";
    $requete .= "FROM `$tableSeries` ";
    $requete .= "WHERE `cs_id` = '$idSerie';";

    $serie = $con->query($requete);

    return $serie->fetch_assoc();

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

// Récupération de tous les status des séries
function mRecupererTousStatutsSeries() {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableStatuts = $prefixeTables.$tables["courses"]["series_statuts"];

    $requete = "SELECT * ";
    $requete .= "FROM `$tableStatuts` ";

    $statuts = $con->query($requete);

    return $statuts;

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

// Récupération de toures les séries, eventuellement filtrées par une catégorie
function mRecupererToutesSeries($idCategorie, $idStatut) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableSeries = $prefixeTables.$tables["courses"]["series"];
    $tableCategories = $prefixeTables.$tables["courses"]["categories"];
    $tableStatuts = $prefixeTables.$tables["courses"]["series_statuts"];

    $idCategorie = $con->real_escape_string($idCategorie);
    $idStatut = $con->real_escape_string($idStatut);

    $filtres = $idCategorie == '' ? '' : "AND `cs_fk_cca_id` = '$idCategorie' ";
    $filtres .= $idStatut == '' ? '' : "AND `cs_fk_css_id` = '$idStatut' ";

    $requete = "SELECT * FROM ";
    $requete .= "`$tableSeries`, `$tableCategories`, `$tableStatuts` ";
    $requete .= "WHERE `cs_fk_cca_id`= `cca_id` ";
    $requete .= "AND `cs_fk_css_id` = `css_id` ";
    $requete .= $filtres;
    $requete .= "ORDER BY `cca_rang` ASC, `cs_rang_principal` ASC, `cs_rang_secondaire` ASC;";

    $series = $con->query($requete);

    return $series;

}

// Récupération de toutes les voitures associées à un événement
function mRecupererVoituresAssociees($idSerie) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableVoitures = $prefixeTables.$tables["voitures"][0];
    $tableConstructeurs = $prefixeTables.$tables["voitures"]["constructeurs"];
    $tableAssociation = $prefixeTables.$tables["voitures"]["associees"];

    $idSerie = $con->real_escape_string($idSerie);

    $requete = "SELECT * ";
    $requete .= "FROM `$tableAssociation`, `$tableVoitures`, `$tableConstructeurs` ";
    $requete .= "WHERE `va_fk_cs_id` = '$idSerie' AND `va_fk_v_id` = `v_id` AND `v_fk_vco_id` = `vco_id` ";
    $requete .= " ORDER BY `v_ip` ASC, `vco_nom` ASC;";

    $voitures = $con->query($requete);

    return $voitures;

}

// Récupération de toutes les voitures non associées à une série,
// eventuellement filtrées par un constructeur
function mRecupererVoituresDisponibles($idSerie, $idConstructeur) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableVoitures = $prefixeTables.$tables["voitures"][0];
    $tableConstructeurs = $prefixeTables.$tables["voitures"]["constructeurs"];
    $tableAssociations = $prefixeTables.$tables["voitures"]["associees"];

    $idSerie = $con->real_escape_string($idSerie);
    $idConstructeur = $con->real_escape_string($idConstructeur);

    $filtres = $idConstructeur == '' ? '' : "AND `v_fk_vco_id` = '$idConstructeur' ";

    $requete = "SELECT * ";
    $requete .= "FROM `$tableVoitures`, `$tableConstructeurs` ";
    $requete .= "WHERE `v_fk_vco_id` = `vco_id` ";
    $requete .= $filtres;
    $requete .= "AND `v_id` NOT IN (";
    $requete .= "SELECT `va_fk_v_id` ";
    $requete .= "FROM `$tableAssociations` ";
    $requete .= "WHERE `va_fk_cs_id` = '$idSerie'";
    $requete .= ") ";
    $requete .= "ORDER BY `vco_nom` ASC, `v_ip` ASC;";

    $voitures = $con->query($requete);

    return $voitures;

}

// Retourne true si l'id passé en parmètre correspond à une série
function mSerieExiste($idSerie) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableSeries = $prefixeTables.$tables["courses"]["series"];

    $idSerie = $con->real_escape_string($idSerie);

    $requete = "SELECT * ";
    $requete .= "FROM `$tableSeries` ";
    $requete .= "WHERE `cs_id` = '$idSerie';";

    $resultat = $con->query($requete);

    return ($resultat->num_rows != 0);

}

// Retourne true si l'id passé en parmètre correspond à un statut de série
function mStatutSerieExiste($idStatutSerie) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableStatusSeries = $prefixeTables.$tables["courses"]["series_statuts"];

    $idStatutSerie = $con->real_escape_string($idStatutSerie);

    $requete = "SELECT * ";
    $requete .= "FROM `$tableStatusSeries` ";
    $requete .= "WHERE `css_id` = '$idStatutSerie';";

    $resultat = $con->query($requete);

    return ($resultat->num_rows != 0);

}

// Retire une voiture de la liste des voitures associées à un événement
function mSupprimerAssociation($idAssociation) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableAssociations = $prefixeTables.$tables["voitures"]["associees"];;

    $idAssociation = $con->real_escape_string($idAssociation);

    $requete = "DELETE FROM `$tableAssociations` ";
    $requete .= "WHERE `va_id` = '$idAssociation';";

    $con->query($requete);

}

// Suppression d'une série
function mSupprimerSerie($idSerie) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableSeries = $prefixeTables.$tables["courses"]["series"];

    $idSerie = $con->real_escape_string($idSerie);

    $requete = "DELETE FROM `$tableSeries` ";
    $requete .= "WHERE `cs_id` = '$idSerie';";

    $con->query($requete);

}

// Retourne true si l'id passé en parmètre correspond à une série
function mVoitureExiste($idVoiture) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableVoitures = $prefixeTables.$tables["voitures"][0];

    $idVoiture = $con->real_escape_string($idVoiture);

    $requete = "SELECT * ";
    $requete .= "FROM `$tableVoitures` ";
    $requete .= "WHERE `v_id` = '$idVoiture';";

    $resultat = $con->query($requete);

    return ($resultat->num_rows != 0);

}
