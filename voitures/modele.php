<?php

require "../config/bd.php";

// Ajoute une nouvelle voiture
function mAjouterVoiture($donnees) {

    global $prefixeTables;
    global $tables;
    
    $con = connecterBase();
    
    $tableVoitures = $prefixeTables.$tables["voitures"][0];
    
    foreach(array_keys($donnees) as $cle) {
        if ($cle != "v_id") {
            $champs[] = "`$cle`";
            $valeur = "'".$con->real_escape_string($donnees[$cle])."'";
            $valeurs[] = $valeur == "''" ? "NULL" : $valeur;
        }
    }
    $champs = implode(", ", $champs);
    $valeurs = implode(", ", $valeurs);
    
    $requete = "INSERT INTO `$tableVoitures` ";
    $requete .= "($champs) ";
    $requete .= "VALUE ($valeurs);";

    $con->query($requete);
    
    deconnecterBase($con);
}

// Ajoute une voiture à la liste de voiture de l'utilisateur
function mAjouterVoitureUtilisateur($idUtilisateur, $idVoiture) {

    global $prefixeTables;
    global $tables;
    
    // Si l'utilisateur possède déjà la voiture, on arrête.
    if (mEstPossedee($idUtilisateur, $idVoiture)) return;

    // Récupération des informations sur la voiture
    $voiture = mRecupererVoiture($idVoiture);
    
    // Construction de la structure de données
    $donnees["uv_fk_u_id"] = $idUtilisateur;
    $donnees["uv_fk_v_id"] = $idVoiture;
    $donnees["uv_fk_uvs_id"] = 1;
    $donnees["uv_vitesse"] = $voiture["v_vitesse"];
    $donnees["uv_acceleration"] = $voiture["v_acceleration"];
    $donnees["uv_freinage"] = $voiture["v_freinage"];
    $donnees["uv_adherence"] = $voiture["v_adherence"];
    $donnees["uv_ip"] = $voiture["v_ip"];
    $donnees["uv_am_moteur"] = 0;
    $donnees["uv_am_transmission"] = 0;
    $donnees["uv_am_carrosserie"] = 0;
    $donnees["uv_am_suspension"] = 0;
    $donnees["uv_am_pot"] = 0;
    $donnees["uv_am_freins"] = 0;
    $donnees["uv_am_roues"] = 0;
    
    // Connexion à la base de données
    $con = connecterBase();
    
    // Tables utilisées
    $tableUtilisateursVoitures = $prefixeTables.$tables["utilisateurs"]["voitures"];
    
    // Post-traitement des données
    foreach(array_keys($donnees) as $cle) {
        $champs[] = "`$cle`";
        $valeurs[] = "'".$con->real_escape_string($donnees[$cle])."'";
    }

    $champs = implode(", ", $champs);
    $valeurs = implode(", ", $valeurs);
    
    // Construction de la requète
    $requete = "INSERT INTO `$tableUtilisateursVoitures` ";
    $requete .= "($champs) ";
    $requete .= "VALUE ($valeurs);";

    // Exécution de la requète
    $con->query($requete);
    
    // Fermeture de la connexion à la base de données
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

// Retourne true si une voiture est possédée par l'utilisateur, false sinon
function mEstPossedee($idUtilisateur, $idVoiture) {
    
    global $prefixeTables;
    global $tables;

    $con = connecterBase();
    
    $tableUtilisateursVoitures = $prefixeTables.$tables["utilisateurs"]["voitures"];
    
    $idUtilisateur = $con->real_escape_string($idUtilisateur);
    $idVoiture = $con->real_escape_string($idVoiture);
    
    $requete = "SELECT * ";
    $requete .= "FROM `$tableUtilisateursVoitures` ";
    $requete .= "WHERE `uv_fk_u_id` = '$idUtilisateur' AND `uv_fk_v_id` = '$idVoiture';";
    
    $resultat = $con->query($requete);
    
    return ($resultat->num_rows != 0);
}

// Mets à jour d'un modèle de voiture
function mModifierVoiture($donnees) {

    global $prefixeTables;
    global $tables;
    
    $con = connecterBase();
    
    $tableVoitures = $prefixeTables.$tables["voitures"][0];
    
    $idVoiture = $con->real_escape_string($donnees["v_id"]);
    foreach(array_keys($donnees) as $cle) {
        if ($cle != "v_id") {
            $valeur = "'".$con->real_escape_string($donnees[$cle])."'";
            $valeur = $valeur == "''" ? "NULL" : $valeur;
            $donneesAJour[] = "`$cle` = $valeur" ;
        }
    }
    $donneesAJour = implode(", ", $donneesAJour);
    
    $requete = "UPDATE `$tableVoitures` ";
    $requete .= "SET $donneesAJour ";
    $requete .= "WHERE `v_id` = '$idVoiture';";
    
    $con->query($requete);
    
    deconnecterBase($con);
}

// Mets à jour d'une voiture de l'utilisateur
function mModifierVoitureUtilisateur($donnees) {

    global $prefixeTables;
    global $tables;
    
    $con = connecterBase();
    
    $tableUtilisateursVoitures = $prefixeTables.$tables["utilisateurs"]["voitures"];
    
    $idVoitureUtilisateur = $con->real_escape_string($donnees["uv_id"]);
    foreach(array_keys($donnees) as $cle) {
        if ($cle != "uv_id") {
            $valeur = "'".$con->real_escape_string($donnees[$cle])."'";
            $valeur = $valeur == "''" ? "'0'" : $valeur;
            $donneesAJour[] = "`$cle` = $valeur" ;
        }
    }
    $donneesAJour = implode(", ", $donneesAJour);
    
    $requete = "UPDATE `$tableUtilisateursVoitures` ";
    $requete .= "SET $donneesAJour ";
    $requete .= "WHERE `uv_id` = '$idVoitureUtilisateur';";
    
    $con->query($requete);
    
    deconnecterBase($con);
}

// Récupération de tous les statuts
function mRecupererTousStatuts() {
    
    global $prefixeTables;
    global $tables;
    
    $con = connecterBase();
    
    $tableStatuts = $prefixeTables.$tables["utilisateurs"]["voitures_statuts"];
    
    $requete = "SELECT * ";
    $requete .= "FROM `$tableStatuts` ";
    $requete .= "ORDER BY `uvs_nom` ASC;";
    
    $statuts = $con->query($requete);

    deconnecterBase($con);
    
    return $statuts;
}

// Récupération de tous les constructeurs
function mRecupererTousConstructeurs() {
    
    global $prefixeTables;
    global $tables;
    
    $con = connecterBase();
    
    $tableConstructeurs = $prefixeTables.$tables["voitures"]["constructeurs"];
    
    $requete = "SELECT * ";
    $requete .= "FROM `$tableConstructeurs` ";
    $requete .= "ORDER BY `vco_nom` ASC;";
    
    $constructeurs = $con->query($requete);
    
    deconnecterBase($con);
    
    return $constructeurs;
}

// Récupération de toutres les classes de voitures
function mRecupererToutesClasses() {
    
    global $prefixeTables;
    global $tables;
    
    $con = connecterBase();
    
    $tableClasses = $prefixeTables.$tables["voitures"]["classes"];
    
    $requete = "SELECT * ";
    $requete .= "FROM `$tableClasses` ";
    $requete .= "ORDER BY `vcl_nom` ASC;";
    
    $classes = $con->query($requete);
    
    deconnecterBase($con);
    
    return $classes;
}

// Récupération de tous les monnaies pour les récompenses
function mRecupererToutesMonnaies() {
    
    global $prefixeTables;
    global $tables;
    
    $con = connecterBase();
    
    $tableMonnaies = $prefixeTables.$tables["monnaies"];
    
    $requete = "SELECT * ";
    $requete .= "FROM `$tableMonnaies` ";
    $requete .= "ORDER BY `m_id` ASC;";
    
    $monnaies = $con->query($requete);
    
    deconnecterBase($con);
    
    return $monnaies;
}

// Récupération de tous les type de transmission
function mRecupererToutesTransmissions() {
    
    global $prefixeTables;
    global $tables;
    
    $con = connecterBase();
    
    $tableTransmissions = $prefixeTables.$tables["voitures"]["transmissions"];
    
    $requete = "SELECT * ";
    $requete .= "FROM `$tableTransmissions` ";
    $requete .= "ORDER BY `vt_nom` ASC;";
    
    $transmissions = $con->query($requete);
    
    deconnecterBase($con);
    
    return $transmissions;
}

// Récupération de toutes les voitures, eventuellement filtrées par un constructeur
function mRecupererToutesVoitures($idConstructeur) {
    
    global $prefixeTables;
    global $tables;
    
    $con = connecterBase();
    
    $tableVoitures = $prefixeTables.$tables["voitures"][0];
    $tableConstructeurs = $prefixeTables.$tables["voitures"]["constructeurs"];
    $tableClasses = $prefixeTables.$tables["voitures"]["classes"];
    $tableTransmissions = $prefixeTables.$tables["voitures"]["transmissions"];
    $tableMonnaies = $prefixeTables.$tables["monnaies"];
    
    $idConstructeur = $con->real_escape_string($idConstructeur);
    
    $filtres = $idConstructeur == '' ? '' : "AND `v_fk_vco_id` = '$idConstructeur' ";
    
    $requete = "SELECT * ";
    $requete .= "FROM `$tableVoitures`, `$tableConstructeurs`, `$tableClasses`, `$tableTransmissions`, `$tableMonnaies` ";
    $requete .= "WHERE `v_fk_vco_id` = `vco_id` AND `v_fk_vcl_id` = `vcl_id` AND `v_fk_vt_id` = `vt_id` AND `v_fk_m_id` = `m_id` ";
    $requete .= $filtres;
    $requete .= " ORDER BY `vco_nom` ASC, `v_ip` ASC;";
    
    $voitures = $con->query($requete);
    
    deconnecterBase($con);
    
    return $voitures;
}

// Récupération de toutes les voitures d'un utilisateur, eventuellement filtrées par un constructeur
function mRecupererToutesVoituresUtilisateur($idUtilisateur, $idConstructeur, $idStatut) {
    
    global $prefixeTables;
    global $tables;
    
    $con = connecterBase();
    
    $tableVoituresUtilisateur = $prefixeTables.$tables["utilisateurs"]["voitures"];
    $tableVoituresUtilisateurStatus = $prefixeTables.$tables["utilisateurs"]["voitures_statuts"];
    $tableVoitures = $prefixeTables.$tables["voitures"][0];
    $tableConstructeurs = $prefixeTables.$tables["voitures"]["constructeurs"];
    $tableClasses = $prefixeTables.$tables["voitures"]["classes"];
    $tableTransmissions = $prefixeTables.$tables["voitures"]["transmissions"];
    $tableMonnaies = $prefixeTables.$tables["monnaies"];
    
    $idUtilisateur = $con->real_escape_string($idUtilisateur);
    $idConstructeur = $con->real_escape_string($idConstructeur);
    $idStatut = $con->real_escape_string($idStatut);
    
    $filtres = "AND `uv_fk_u_id` = '$idUtilisateur' ";
    $filtres .= $idConstructeur == '' ? '' : "AND `v_fk_vco_id` = '$idConstructeur' ";
    $filtres .= $idStatut == '' ? '' : "AND `uv_fk_uvs_id` = '$idStatut' ";
    
    $requete = "SELECT * ";
    $requete .= "FROM `$tableVoituresUtilisateur`, `$tableVoituresUtilisateurStatus`, `$tableVoitures`, `$tableConstructeurs`, `$tableClasses`, `$tableTransmissions`, `$tableMonnaies` ";
    $requete .= "WHERE `uv_fk_uvs_id` = `uvs_id` AND `uv_fk_v_id` = `v_id` AND `v_fk_vco_id` = `vco_id` AND `v_fk_vcl_id` = `vcl_id` AND `v_fk_vt_id` = `vt_id` AND `v_fk_m_id` = `m_id` ";
    $requete .= $filtres;
    $requete .= " ORDER BY `vco_nom` ASC, `uv_ip` ASC;";
    
    $voitures = $con->query($requete);
    
    deconnecterBase($con);
    
    return $voitures;
}

// Recupération d'une voiture par son id
function mRecupererVoiture($idVoiture) {

    global $prefixeTables;
    global $tables;
    
    $con = connecterBase();
    
    $tableVoitures = $prefixeTables.$tables["voitures"][0];
    
    $idVoiture = $con->real_escape_string($idVoiture);
    
    $requete = "SELECT * ";
    $requete .= "FROM `$tableVoitures` ";
    $requete .= "WHERE `v_id` = '$idVoiture' ";
    
    $voiture = $con->query($requete);
    
    deconnecterBase($con);
    
    return $voiture->fetch_assoc();    
}

// Recupération d'une voiture de l'utilisateur par son id
function mRecupererVoitureUtilisateur($idVoitureUtilisateur) {
    
    global $prefixeTables;
    global $tables;
    
    $con = connecterBase();
    
    $tableVoitures = $prefixeTables.$tables["voitures"][0];
    $tableVoituresUtilisateur = $prefixeTables.$tables["utilisateurs"]["voitures"];
    
    $idVoitureUtilisateur = $con->real_escape_string($idVoitureUtilisateur);
    
    $requete = "SELECT * ";
    $requete .= "FROM `$tableVoitures`, `$tableVoituresUtilisateur` ";
    $requete .= "WHERE `uv_fk_v_id` = `v_id` AND `uv_id` = '$idVoitureUtilisateur';";
    
    $voitures = $con->query($requete);
    
    deconnecterBase($con);
    
    return $voitures->fetch_assoc();
}

// Retourne true si l'id passé en parmètre correspond à un statut d'une voiture d'un utilisateur
function mStatutVoitureExiste($idStatutVoiture) {
    
    global $prefixeTables;
    global $tables;

    $con = connecterBase();
    
    $tableStatusVoiture = $prefixeTables.$tables["utilisateurs"]["voitures_statuts"];
    
    $idStatutVoiture = $con->real_escape_string($idStatutVoiture);
    
    $requete = "SELECT * ";
    $requete .= "FROM `$tableStatusVoiture` ";
    $requete .= "WHERE `uvs_id` = '$idStatutVoiture';";
    
    $resultat = $con->query($requete);
    
    return ($resultat->num_rows != 0);
}

function mSupprimerVoiture($idVoiture) {

    global $prefixeTables;
    global $tables;
    
    $con = connecterBase();
    
    $tableVoitures = $prefixeTables.$tables["voitures"][0];
    
    $idVoiture = $con->real_escape_string($idVoiture);
    
    $requete = "DELETE FROM `$tableVoitures` ";
    $requete .= "WHERE `v_id` = '$idVoiture';";
    
    $con->query($requete);
    
    deconnecterBase($con);
}

function mSupprimerVoitureUtilisateur($idVoitureUtilisateur) {

    global $prefixeTables;
    global $tables;
    
    $con = connecterBase();
    
    $tableVoituresUtilisateur = $prefixeTables.$tables["utilisateurs"]["voitures"];
    
    $idVoitureUtilisateur = $con->real_escape_string($idVoitureUtilisateur);
    
    $requete = "DELETE FROM `$tableVoituresUtilisateur` ";
    $requete .= "WHERE `uv_id` = '$idVoitureUtilisateur';";
    
    $con->query($requete);
    
    deconnecterBase($con);
}

// Retourne true si l'id passé en parmètre correspond à une voiture
function mVoitureExiste($idVoiture) {
    
    global $prefixeTables;
    global $tables;

    $con = connecterBase();
    
    $tableVoitures = $prefixeTables.$tables["voitures"][0];
    
    $idVoiture = $con->real_escape_string($idVoiture);
    
    $requete = "SELECT * ";
    $requete .= "FROM `$tableVoitures` ";
    $requete .= "WHERE `v_id` = '$idVoiture';";
    
    $resultat = $con->query($requete);
    
    return ($resultat->num_rows != 0);
}

// Retourne true si l'id passé en parmètre correspond à une voiture
function mVoitureUtilisateurExiste($idUtilisateur, $idVoitureUtilisateur) {
    
    global $prefixeTables;
    global $tables;

    $con = connecterBase();
    
    $tableVoituresUtilisateur = $prefixeTables.$tables["utilisateurs"]["voitures"];
    
    $idUtilisateur = $con->real_escape_string($idUtilisateur);
    $idVoitureUtilisateur = $con->real_escape_string($idVoitureUtilisateur);
    
    $requete = "SELECT * ";
    $requete .= "FROM `$tableVoituresUtilisateur` ";
    $requete .= "WHERE `uv_id` = '$idVoitureUtilisateur' ";
    $requete .= "AND `uv_fk_u_id` = '$idUtilisateur';";
    
    $resultat = $con->query($requete);
    
    return ($resultat->num_rows != 0);
}

?>