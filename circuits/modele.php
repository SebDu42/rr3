<?php

require "../config/bd.php";

// Ajoute une nouvelle configuration, et éventuellement un nouveau circuit
function mAjouterConfiguration($donnees) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableCircuits = $prefixeTables.$tables["circuits"][0];
    $tableConfigurations = $prefixeTables.$tables["circuits"]["configurations"];

    if ($donnees["ci_id"] == '') {
        // Récupération des données à insérer pour le circuit
        $nomCircuit = $con->real_escape_string($donnees["ci_nom"]);
        $localisationCircuit = $con->real_escape_string($donnees["ci_localisation"]);
        $urlCircuit = $con->real_escape_string($donnees["ci_url"]);


        $requete = "INSERT INTO `$tableCircuits` ";
        $requete .= "(`ci_nom`, `ci_localisation`, `ci_url`) ";
        $requete .= "VALUE ('$nomCircuit', '$localisationCircuit', '$urlCircuit');";

        $con->query($requete);
        $donnees["ci_id"] = $con->insert_id;
    }

    // Récupération des données à insérer pour la configuration
    $idCircuit = $con->real_escape_string($donnees["ci_id"]);
    $nomConfiguration = "'".$con->real_escape_string($donnees["cic_nom"])."'";
    $nomConfiguration = $nomConfiguration == "''" ? "NULL" : $nomConfiguration;
    $longueurConfiguration = $con->real_escape_string($donnees["cic_longueur"]);

    $requete = "INSERT INTO `$tableConfigurations` ";
    $requete .= "(`cic_fk_ci_id`, `cic_nom`, `cic_longueur`) ";
    $requete .= "VALUE ('$idCircuit', $nomConfiguration, '$longueurConfiguration');";

    $con->query($requete);

}

// Retourne true si l'id passé en parmètre correspond à un circuit
function mCircuitExiste($idCircuit) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableCircuits = $prefixeTables.$tables["circuits"][0];

    $idCircuit = $con->real_escape_string($idCircuit);

    $requete = "SELECT * ";
    $requete .= "FROM `$tableCircuits` ";
    $requete .= "WHERE `ci_id` = '$idCircuit';";

    $resultat = $con->query($requete);

    return ($resultat->num_rows != 0);

}

// Retourne true si l'id passé en parmètre correspond à un circuit
function mConfigurationExiste($idConfiguration) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableConfigurations = $prefixeTables.$tables["circuits"]["configurations"];

    $idConfiguration = $con->real_escape_string($idConfiguration);

    $requete = "SELECT * ";
    $requete .= "FROM `$tableConfigurations` ";
    $requete .= "WHERE `cic_id` = '$idConfiguration';";

    $resultat = $con->query($requete);

    return ($resultat->num_rows != 0);

}

// Modifie une nouvelle configuration et/ou le circuit associé
function mModifierConfiguration($donnees) {
    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableCircuits = $prefixeTables.$tables["circuits"][0];
    $tableConfigurations = $prefixeTables.$tables["circuits"]["configurations"];

    // Récupération des données à mettre à jour
    $idCircuit = $con->real_escape_string($donnees["ci_id"]);
    $nomCircuit = $con->real_escape_string($donnees["ci_nom"]);
    $localisationCircuit = $con->real_escape_string($donnees["ci_localisation"]);
    $urlCircuit = $con->real_escape_string($donnees["ci_url"]);

    $idConfiguration = $con->real_escape_string($donnees["cic_id"]);
    $nomConfiguration = "'".$con->real_escape_string($donnees["cic_nom"])."'";
    $nomConfiguration = $nomConfiguration == "''" ? "NULL" : $nomConfiguration;
    $longueurConfiguration = $con->real_escape_string($donnees["cic_longueur"]);

    $requete = "UPDATE `$tableCircuits` ";
    $requete .= "SET `ci_nom` = '$nomCircuit', `ci_localisation` = '$localisationCircuit', `ci_url` = '$urlCircuit' ";
    $requete .= "WHERE `ci_id` = '$idCircuit';\n";

    $con->query($requete);

    $requete = "UPDATE `$tableConfigurations` ";
    $requete .= "SET `cic_nom` = $nomConfiguration, `cic_longueur` = '$longueurConfiguration' ";
    $requete .= "WHERE `cic_id` = '$idConfiguration';";

    $con->query($requete);

}

// Récupération d'un circuit à partir de son id
function mRecupererCircuit($idCircuit) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableCircuits = $prefixeTables.$tables["circuits"][0];

    $idCircuit = $con->real_escape_string($idCircuit);

    $requete = "SELECT * ";
    $requete .= "FROM `$tableCircuits` ";
    $requete .= "WHERE `ci_id` = '$idCircuit' ";

    $circuit = $con->query($requete);

    return $circuit->fetch_assoc();

}

// Récupération d'une configuration à partir de son id
function mRecupererConfiguration($idConfiguration) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $idConfiguration = $con->real_escape_string($idConfiguration);

    $tableCircuits = $prefixeTables.$tables["circuits"][0];
    $tableConfigurations = $prefixeTables.$tables["circuits"]["configurations"];

    $requete = "SELECT * ";
    $requete .= "FROM `$tableCircuits`, `$tableConfigurations` ";
    $requete .= "WHERE `cic_fk_ci_id` = `ci_id` AND `cic_id` = '$idConfiguration' ";

    $configuration = $con->query($requete);

    return $configuration->fetch_assoc();

}

// Récupération de toutes les configurations d'un circuit
function mRecupererConfigurationsCircuit($idCircuit) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableConfigurations = $prefixeTables.$tables["circuits"]["configurations"];

    $idCircuit = $con->real_escape_string($idCircuit);

    $requete = "SELECT * ";
    $requete .= "FROM `$tableConfigurations` ";
    $requete .= "WHERE `cic_fk_ci_id` = $idCircuit ";
    $requete .= "ORDER BY `cic_nom` ASC;";

    $configurations = $con->query($requete);

    return $configurations;

}

// Récupération de toutes les circuits
function mRecupererTousCircuits() {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableCircuits = $prefixeTables.$tables["circuits"][0];

    $requete = "SELECT * ";
    $requete .= "FROM `$tableCircuits` ";
    $requete .= "ORDER BY `ci_nom` ASC;";

    $circuits = $con->query($requete);

    return $circuits;

}

// Supprime une configuration, et le circuit associé s'il n'a plus de configuration
function mSupprimerConfiguration($idConfiguration) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableCircuits = $prefixeTables.$tables["circuits"][0];
    $tableConfigurations = $prefixeTables.$tables["circuits"]["configurations"];

    $idConfiguration = $con->real_escape_string($idConfiguration);

    $configuration = mRecupererConfiguration($idConfiguration);
    $idCircuit = $configuration["cic_fk_ci_id"];

    $requete = "DELETE FROM `$tableConfigurations` ";
    $requete .= "WHERE `cic_id` = '$idConfiguration';";

    $con->query($requete);

    $configurations = mRecupererConfigurationsCircuit($idCircuit);
    if ($configurations->num_rows == 0) {
        $requete = "DELETE FROM `$tableCircuits` ";
        $requete .= "WHERE `ci_id` = '$idCircuit';";

        $con->query($requete);
    }

}
