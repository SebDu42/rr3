<?php

require "../config/bd.php";

// Ajout d'un nouveau constructeur
function mAjouterConstructeur($donnees) {
    
    global $prefixeTables;
    global $tables;
    
    $con = connecterBase();
    
    $tableConstructeurs = $prefixeTables.$tables["voitures"]["constructeurs"];
    
    foreach(array_keys($donnees) as $cle) {
        if ($cle != "vco_id") {
            $champs[] = "`$cle`";
            $valeurs[] = "'".$con->real_escape_string($donnees[$cle])."'";
        }
    }
    $champs = implode(", ", $champs);
    $valeurs = implode(", ", $valeurs);
    
    $requete = "INSERT INTO `$tableConstructeurs` ";
    $requete .= "($champs) ";
    $requete .= "VALUE ($valeurs)";
    
    $con->query($requete);
    
    deconnecterBase($con);
}

// Retourne true si l'id passé en parmètre correspond à un constructeur
function mConstructeurExiste($idConstructeur) {
    
    global $prefixeTables;
    global $tables;

    $con = connecterBase();
    
    $tableConstructeurs = $prefixeTables.$tables["voitures"]["constructeurs"];
    
    $idConstructeur = $con->real_escape_string($idConstructeur);
    
    $requete = "SELECT * ";
    $requete .= "FROM `$tableConstructeurs` ";
    $requete .= "WHERE `vco_id` = '$idConstructeur';";
    
    $resultat = $con->query($requete);
    
    return ($resultat->num_rows != 0);
}

// Modification d'un constructeur
function mModifierConstructeur($donnees) {
    
    global $prefixeTables;
    global $tables;
    
    $con = connecterBase();
    
    $tableConstructeurs = $prefixeTables.$tables["voitures"]["constructeurs"];
    
    $id = $con->real_escape_string($donnees["vco_id"]);
    foreach(array_keys($donnees) as $cle) {
        if ($cle != "vco_id") {
            $donneesAJour[] = "`$cle` = '".$con->real_escape_string($donnees[$cle])."'" ;
        }
    }
    $donneesAJour = implode(", ", $donneesAJour);
    
    $requete = "UPDATE `$tableConstructeurs` ";
    $requete .= "SET $donneesAJour ";
    $requete .= "WHERE `vco_id` = '$id';";
    
    $con->query($requete);
    
    deconnecterBase($con);
}

// Récupération d'un constructeur par son id
function mRecupererConstructeur($idConstructeur) {
    
    global $prefixeTables;
    global $tables;
    
    $con = connecterBase();
    
    $tableConstructeurs = $prefixeTables.$tables["voitures"]["constructeurs"];
    
    $idConstructeur = $con->real_escape_string($idConstructeur);
    
    $requete = "SELECT * ";
    $requete .= "FROM `$tableConstructeurs` ";
    $requete .= "WHERE `vco_id` = '$idConstructeur';";
    
    $constructeur = $con->query($requete);
    
    deconnecterBase($con);
    
    return $constructeur->fetch_assoc();    
}

// Récupération de toutes les catégories
function mRecupererTousConstructeurs() {
    
    global $prefixeTables;
    global $tables;
    
    $con = connecterBase();
    
    $tableConstructeurs = $prefixeTables.$tables["voitures"]["constructeurs"];
    
    $requete = "SELECT * ";
    $requete .= "FROM `$tableConstructeurs` ";
    $requete .= "ORDER BY `vco_nom` ASC;";
    
    $constructeur = $con->query($requete);
    
    deconnecterBase($con);
    
    return $constructeur;
}

// Suppression d'un constructeur
function mSupprimerConstructeur($idConstructeur) {
    
    global $prefixeTables;
    global $tables;
    
    $con = connecterBase();
    
    $tableConstructeurs = $prefixeTables.$tables["voitures"]["constructeurs"];
    
    $idConstructeur = $con->real_escape_string($idConstructeur);

    $requete = "DELETE FROM `$tableConstructeurs` ";
    $requete .= "WHERE `vco_id` = '$idConstructeur';";
    
    $con->query($requete);
    
    deconnecterBase($con);
}

?>