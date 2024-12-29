<?php
// FIXME: Sécuriser les données contre l'injection SQL

require "../config/bd.php";

// Ajout d'une nouvelle course
function mAjouterCourse($donnees) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableCourses = $prefixeTables.$tables["courses"][0];

    foreach(array_keys($donnees) as $cle) {
        if ($cle != "c_id") {
            $champs[] = "`$cle`";
            $valeur = "'".$con->real_escape_string($donnees[$cle])."'";
            $valeurs[] = $valeur == "''" ? "NULL" : $valeur;
        }
    }
    $champs = implode(", ", $champs);
    $valeurs = implode(", ", $valeurs);

    $requete = "INSERT INTO `$tableCourses` ";
    $requete .= "($champs) ";
    $requete .= "VALUE ($valeurs)";

    $con->query($requete);

}

// Ajout d'une nouvelle performance
function mAjouterPerformance($idUtilisateur, $idCourse) {

    global $prefixeTables;
    global $tables;
    global $con;

    // Récupération des informations sur la course
    $course = mRecupererCourse($idCourse);
    $circuit = mRecupererConfiguration($course["c_fk_cic_id"]);

    // Construction de la structure de données
    $donnees["up_fk_u_id"] = $idUtilisateur;
    $donnees["up_fk_c_id"] = $idCourse;
    $donnees["up_nb_depassements"] = isset($course["c_nb_concurrents"]) ? $course["c_nb_concurrents"] - 1 : '';
    if (isset($course["c_nb_tours"]) && ($course["c_fk_ct_id"]) != 2) {
        $donnees["up_distance"] = $course["c_nb_tours"] * $circuit["cic_longueur"];
    }
    if ($course["c_fk_ct_id"] == 5) {
        $donnees["up_temps"] = "2:20.000";
    }

    // Connexion à la base de données
    if (!isset($con)) $con = connecterBase();

    // Tables utilisées
    $tableUtilisateursPerformances = $prefixeTables.$tables["utilisateurs"]["performances"];

    // Post-traitement des données
    foreach(array_keys($donnees) as $cle) {
        $champs[] = "`$cle`";
        $valeur = "'".$con->real_escape_string($donnees[$cle])."'";
        $valeurs[] = $valeur == "''" ? "NULL" : $valeur;
    }

    $champs = implode(", ", $champs);
    $valeurs = implode(", ", $valeurs);

    // Construction de la requète
    $requete = "INSERT INTO `$tableUtilisateursPerformances` ";
    $requete .= "($champs) ";
    $requete .= "VALUE ($valeurs);";

    // Exécution de la requète
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

// Retourne true si l'id passé en parmètre correspond à une course
function mCourseExiste($idCourse) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableCourse = $prefixeTables.$tables["courses"][0];

    $idCourse = $con->real_escape_string($idCourse);

    $requete = "SELECT * ";
    $requete .= "FROM `$tableCourse` ";
    $requete .= "WHERE `c_id` = '$idCourse';";

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

    $idEvenement = $con->real_escape_string($idEvenement);

    $requete = "SELECT * ";
    $requete .= "FROM `$tableEvenements` ";
    $requete .= "WHERE `ce_id` = '$idEvenement';";

    $resultat = $con->query($requete);

    return ($resultat->num_rows != 0);

}

// Mets à jour d'une course
function mModifierCourse($donnees) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableCourses = $prefixeTables.$tables["courses"][0];

    $id = $donnees["c_id"];
    foreach(array_keys($donnees) as $cle) {
        if ($cle != "c_id") {
            $valeur = "'".$con->real_escape_string($donnees[$cle])."'";
            $valeur = $valeur == "''" ? "NULL" : $valeur;
            $donneesAJour[] = "`$cle` = $valeur" ;
        }
    }
    $donneesAJour = implode(", ", $donneesAJour);

    $requete = "UPDATE `$tableCourses` ";
    $requete .= "SET $donneesAJour ";
    $requete .= "WHERE `c_id` = '$id';";

    $con->query($requete);

}

// Mets à jour une performance
function mModifierPerformance($donnees) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tablePerformances = $prefixeTables.$tables["utilisateurs"]["performances"];

    $id = $donnees["up_id"];
    foreach(array_keys($donnees) as $cle) {
        if ((strpos($cle, "up_") === 0) && ($cle != "up_id")) {
            $valeur = "'".$con->real_escape_string($donnees[$cle])."'";
            $valeur = $valeur == "''" ? "NULL" : $valeur;
            $donneesAJour[] = "`$cle` = $valeur" ;
        }
    }
    $donneesAJour = implode(", ", $donneesAJour);

    $requete = "UPDATE `$tablePerformances` ";
    $requete .= "SET $donneesAJour ";
    $requete .= "WHERE `up_id` = '$id';";

    $con->query($requete);

}

// Retourne true si l'id passé en parmètre correspond à une performance de l'utilisateur
function mPerformanceExiste($idPerformance, $idUtilisateur) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tablePerformances = $prefixeTables.$tables["utilisateurs"]["performances"];

    $idPerformance = $con->real_escape_string($idPerformance);
    $idUtilisateur = $con->real_escape_string($idUtilisateur);

    $requete = "SELECT * ";
    $requete .= "FROM `$tablePerformances` ";
    $requete .= "WHERE `up_id` = '$idPerformance' ";
    $requete .= "AND `up_fk_u_id` = '$idUtilisateur';";

    $resultat = $con->query($requete);

    return ($resultat->num_rows != 0);

}

// Récupération d'une configuration à partir de son id
function mRecupererConfiguration($idConfiguration) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableCircuits = $prefixeTables.$tables["circuits"][0];
    $tableConfigurations = $prefixeTables.$tables["circuits"]["configurations"];

    $requete = "SELECT * ";
    $requete .= "FROM `$tableCircuits`, `$tableConfigurations` ";
    $requete .= "WHERE `cic_fk_ci_id` = `ci_id` AND `cic_id` = '$idConfiguration' ";

    $configuration = $con->query($requete);

    return $configuration->fetch_assoc();

}

// Recupération d'une course par son id
function mRecupererCourse($idCourse) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableCourses = $prefixeTables.$tables["courses"][0];
    $tableEvenements = $prefixeTables.$tables["courses"]["evenements"];
    $tableSeries = $prefixeTables.$tables["courses"]["series"];
    $tableCategories = $prefixeTables.$tables["courses"]["categories"];
    $tableMonnaies = $prefixeTables.$tables["monnaies"];

    $requete = "SELECT * ";
    $requete .= "FROM `$tableCourses`, `$tableEvenements`, `$tableSeries`, `$tableCategories`, `$tableMonnaies` ";
    $requete .= "WHERE `c_id` = '$idCourse' ";
    $requete .= "AND `c_fk_ce_id` = `ce_id` ";
    $requete .= "AND `ce_fk_cs_id` = `cs_id` ";
    $requete .= "AND `cs_fk_cca_id` = `cca_id`";
    $requete .= "AND `c_fk_m_id` = `m_id`;";

    $course = $con->query($requete);

    return $course->fetch_assoc();

}

// Récupération d'un performance par son id
function mRecupererPerformance($idPerformance) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tablePerformances = $prefixeTables.$tables["utilisateurs"]["performances"];
    $tableCourses = $prefixeTables.$tables["courses"][0];
    $tableEvenements = $prefixeTables.$tables["courses"]["evenements"];
    $tableSeries = $prefixeTables.$tables["courses"]["series"];
    $tableCategories = $prefixeTables.$tables["courses"]["categories"];
    $tableCircuits = $prefixeTables.$tables["circuits"][0];
    $tableTypes = $prefixeTables.$tables["courses"]["types"];
    $tableConfigurations = $prefixeTables.$tables["circuits"]["configurations"];
    $tableMonnaies = $prefixeTables.$tables["monnaies"];

    $requete = "SELECT * ";
    $requete .= "FROM `$tablePerformances`, `$tableCourses`, `$tableEvenements`, `$tableSeries`, `$tableCategories`, `$tableCircuits`, `$tableTypes`, `$tableConfigurations`, `$tableMonnaies` ";
    $requete .= "WHERE `up_id` = '$idPerformance' ";
    $requete .= "AND `up_fk_c_id` = `c_id` ";
    $requete .= "AND `c_fk_ce_id` = `ce_id` ";
    $requete .= "AND `ce_fk_cs_id` = `cs_id` ";
    $requete .= "AND `cs_fk_cca_id` = `cca_id` ";
    $requete .= "AND `c_fk_ct_id` = `ct_id` ";
    $requete .= "AND `c_fk_cic_id` = `cic_id` ";
    $requete .= "AND `cic_fk_ci_id` = `ci_id`";
    $requete .= "AND `c_fk_m_id` = `m_id`;";

    $performance = $con->query($requete);

    return $performance->fetch_assoc();

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

// Racupération de tous les circuits
function mRecupererTousCircuits() {


    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableCircuits = $prefixeTables.$tables["circuits"][0];
    $tableConfigurations = $prefixeTables.$tables["circuits"]["configurations"];

    $requete = "SELECT * ";
    $requete .= "FROM `$tableCircuits`, `$tableConfigurations` ";
    $requete .= "WHERE `cic_fk_ci_id` = `ci_id` ";
    $requete .= "ORDER BY `ci_nom` ASC, `cic_nom` ASC;";

    $types = $con->query($requete);

    return $types;

}

// Récupération de tous les types de course
function mRecupererTousTypes() {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableTypes = $prefixeTables.$tables["courses"]["types"];

    $requete = "SELECT * ";
    $requete .= "FROM `$tableTypes` ";
    $requete .= "ORDER BY `ct_nom` ASC;";

    $types = $con->query($requete);

    return $types;

}

// Récupération de toutes les catégories
function mRecupererToutesCategories() {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableCategories = $prefixeTables.$tables["courses"]["categories"];
    $tableSeries = $prefixeTables.$tables["courses"]["series"];
    $tableEvenements = $prefixeTables.$tables["courses"]["evenements"];

    $requete = "SELECT * ";
    $requete .= "FROM `$tableCategories` ";
    $requete .= "WHERE `cca_id` IN (";
    $requete .= "SELECT `cs_fk_cca_id` ";
    $requete .= "FROM `$tableSeries`, `$tableEvenements` ";
    $requete .= "WHERE `ce_fk_cs_id` = `cs_id` ";
    $requete .= "GROUP BY `cs_fk_cca_id` ";
    $requete .= ")";
    $requete .= "ORDER BY `cca_fk_ccap_id`ASC, `cca_rang` ASC;";

    $categories = $con->query($requete);

    return $categories;

}

// Récupération de toutes les conditions de courses
function mRecupererToutesConditions() {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableConditions = $prefixeTables.$tables["courses"]["conditions"];

    $requete = "SELECT * ";
    $requete .= "FROM `$tableConditions` ";
    $requete .= "ORDER BY `cco_nom` ASC;";

    $types = $con->query($requete);

    return $types;

}

// Récupération de toutes les courses
function mRecupererToutesCourses($idCategorie, $idSerie, $idEvenement) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableCourses = $prefixeTables.$tables["courses"][0];
    $tableEvenements = $prefixeTables.$tables["courses"]["evenements"];
    $tableSeries = $prefixeTables.$tables["courses"]["series"];
    $tableCategories = $prefixeTables.$tables["courses"]["categories"];
    $tableTypes = $prefixeTables.$tables["courses"]["types"];
    $tableCircuits = $prefixeTables.$tables["circuits"][0];
    $tableConfigurations = $prefixeTables.$tables["circuits"]["configurations"];
    $tableConditions = $prefixeTables.$tables["courses"]["conditions"];
    $tableMonnaies = $prefixeTables.$tables["monnaies"];


    $filtre = $idCategorie == '' ? '' : "AND `cs_fk_cca_id` = '$idCategorie' ";
    $filtre .= $idSerie == '' ? '' : "AND `ce_fk_cs_id` = '$idSerie' ";
    $filtre .= $idEvenement == '' ? '' : "AND `c_fk_ce_id` = '$idEvenement' ";

    $requete = "SELECT * ";
    $requete .= "FROM `$tableCourses`, `$tableEvenements`, `$tableSeries`, `$tableCategories`, `$tableTypes`, `$tableCircuits`, `$tableConfigurations`, `$tableConditions`, `$tableMonnaies` ";
    $requete .= "WHERE `c_fk_ce_id` = `ce_id` AND `ce_fk_cs_id` = `cs_id` AND `cs_fk_cca_id` = `cca_id` AND `c_fk_ct_id` = `ct_id` AND `c_fk_cic_id` = `cic_id` AND `cic_fk_ci_id` = `ci_id` AND `c_fk_cco_id` = `cco_id` AND `c_fk_m_id` = `m_id` ";
    $requete .= $filtre;
    $requete .= "ORDER BY `cca_rang` ASC, `cs_rang_principal` ASC, `cs_rang_secondaire` ASC, `ce_rang` ASC, `c_rang` ASC;";

    $courses = $con->query($requete);

    return $courses;

}

// Récupération de tous les monnaies pour les récompenses
function mRecupererToutesMonnaies() {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableMonnaies = $prefixeTables.$tables["monnaies"];

    $requete = "SELECT * ";
    $requete .= "FROM `$tableMonnaies` ";
    $requete .= "ORDER BY `m_id` ASC;";

    $monnaies = $con->query($requete);

    return $monnaies;

}

// Récupération de toutes les courses
function mRecupererToutesPerformances($idUtilisateur, $idCategorie, $idSerie, $idEvenement) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tablePerformances = $prefixeTables.$tables["utilisateurs"]["performances"];
    $tableCourses = $prefixeTables.$tables["courses"][0];
    $tableEvenements = $prefixeTables.$tables["courses"]["evenements"];
    $tableSeries = $prefixeTables.$tables["courses"]["series"];
    $tableCategories = $prefixeTables.$tables["courses"]["categories"];
    $tableTypes = $prefixeTables.$tables["courses"]["types"];
    $tableCircuits = $prefixeTables.$tables["circuits"][0];
    $tableConfigurations = $prefixeTables.$tables["circuits"]["configurations"];
    $tableConditions = $prefixeTables.$tables["courses"]["conditions"];
    $tableMonnaies = $prefixeTables.$tables["monnaies"];

    $filtre = "AND `up_fk_u_id` = '$idUtilisateur' ";
    $filtre .= $idCategorie == '' ? '' : "AND `cs_fk_cca_id` = '$idCategorie' ";
    $filtre .= $idSerie == '' ? '' : "AND `ce_fk_cs_id` = '$idSerie' ";
    $filtre .= $idEvenement == '' ? '' : "AND `c_fk_ce_id` = '$idEvenement' ";

    $requete = "SELECT * ";
    $requete .= "FROM `$tablePerformances`, `$tableCourses`, `$tableEvenements`, `$tableSeries`, `$tableCategories`, `$tableTypes`, `$tableCircuits`, `$tableConfigurations`, `$tableConditions`, `$tableMonnaies` ";
    $requete .= "WHERE `up_fk_c_id` = `c_id` AND `c_fk_ce_id` = `ce_id` AND `ce_fk_cs_id` = `cs_id` AND `cs_fk_cca_id` = `cca_id` AND `c_fk_ct_id` = `ct_id` AND `c_fk_cic_id` = `cic_id` AND `cic_fk_ci_id` = `ci_id` AND `c_fk_cco_id` = `cco_id` AND `c_fk_m_id` = `m_id` ";
    $requete .= $filtre;
    $requete .= "ORDER BY `cca_rang` ASC, `cs_rang_principal` ASC, `cs_rang_secondaire` ASC, `ce_rang` ASC, `c_rang` ASC;";

    $performances = $con->query($requete);

    return $performances;

}

// Récupération de toures les séries, eventuellement filtrées par une catégorie
function mRecupererToutesSeries($idCategorie) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableSeries = $prefixeTables.$tables["courses"]["series"];
    $tableEvenements = $prefixeTables.$tables["courses"]["evenements"];

    $filtre = $idCategorie == '' ? '' : "AND `cs_fk_cca_id` = '$idCategorie' ";

    $requete = "SELECT * FROM ";
    $requete .= "`$tableSeries` ";
    $requete .= "WHERE `cs_id` IN (";
    $requete .= "SELECT `ce_fk_cs_id` ";
    $requete .= "FROM `$tableEvenements` ";
    $requete .= "GROUP BY `ce_fk_cs_id` ";
    $requete .= ") ";
    $requete .= $filtre;
    $requete .= "ORDER BY `cs_rang_principal` ASC, `cs_rang_secondaire` ASC;";

    $series = $con->query($requete);

    return $series;

}

// Récupération des voitures associées à une courses
function mRecupererVoituresAssociees($idUtilisateur, $idCourse) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableVoituresUtilisateur = $prefixeTables.$tables["utilisateurs"]["voitures"];
    $tableVoitures = $prefixeTables.$tables["voitures"][0];
    $tableConstructeurs = $prefixeTables.$tables["voitures"]["constructeurs"];
    $tableCourses = $prefixeTables.$tables["courses"][0];
    $tableEvenements = $prefixeTables.$tables["courses"]["evenements"];
    $tableExclusions = $prefixeTables.$tables["voitures"]["exclues"];
    $tableAssociations = $prefixeTables.$tables["voitures"]["associees"];

    $requete = "SELECT `uv_id`, `vco_nom`, `v_modele` ";
    $requete .= "FROM `$tableVoituresUtilisateur`, `$tableVoitures`, `$tableConstructeurs`, `$tableAssociations`, `$tableEvenements`, `$tableCourses` ";
    $requete .= "WHERE `uv_fk_u_id` = '$idUtilisateur' ";
    $requete .= "AND `c_id` = '$idCourse' ";
    $requete .= "AND `uv_fk_v_id` = `v_id` ";
    $requete .= "AND `v_fk_vco_id` = `vco_id` ";
    $requete .= "AND `v_id` = `va_fk_v_id` ";
    $requete .= "AND `va_fk_cs_id` = `ce_fk_cs_id` ";
    $requete .= "AND `ce_id` = `c_fk_ce_id` ";
    $requete .= "AND `va_id` NOT IN (";
    $requete .= "SELECT `ve_fk_va_id` ";
    $requete .= "FROM `$tableExclusions`, `$tableCourses` ";
    $requete .= "WHERE `c_id` = '$idCourse' ";
    $requete .= "AND `ve_fk_ce_id` = `c_fk_ce_id` ";
    $requete .= ") ";
    $requete .= "ORDER BY  `v_ip` ASC, `vco_nom` ASC, `v_modele` ASC;";

    $voitures = $con->query($requete);

    return $voitures;

}

// Récupération d'une voiture de l'utilisateur par son id
function mRecupererVoitureUtilisateur($idVoitureUtilisateur) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableVoitures = $prefixeTables.$tables["voitures"][0];
    $tableConstructeurs = $prefixeTables.$tables["voitures"]["constructeurs"];
    $tableVoituresUtilisateur = $prefixeTables.$tables["utilisateurs"]["voitures"];

    $requete = "SELECT * ";
    $requete .= "FROM `$tableVoituresUtilisateur`, `$tableVoitures`, `$tableConstructeurs` ";
    $requete .= "WHERE `uv_id` = '$idVoitureUtilisateur' ";
    $requete .= "AND `uv_fk_v_id` = `v_id` ";
    $requete .= "AND `v_fk_vco_id`= `vco_id`;";

    $voitures = $con->query($requete);

    return $voitures->fetch_object();

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

// Suppression d'une course
function mSupprimerCourse($idCourse) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tableCourses = $prefixeTables.$tables["courses"][0];

    $requete = "DELETE FROM `$tableCourses` ";
    $requete .= "WHERE `c_id` = '$idCourse';";

    $con->query($requete);

}

// Suppression d'une performance
function mSupprimerPerformance($idPerformance) {

    global $prefixeTables;
    global $tables;
    global $con;

    if (!isset($con)) $con = connecterBase();

    $tablePerformances = $prefixeTables.$tables["utilisateurs"]["performances"];

    $requete = "DELETE FROM `$tablePerformances` ";
    $requete .= "WHERE `up_id` = '$idPerformance';";

    $con->query($requete);

}
